<?php

namespace GooglePlaceAutocomplete\Request;

/**
 * @file
 * Contains GooglePlaceAutocomplete\Request\Request.
 */

use GooglePlaceAutocomplete\Exception\RequestException;
use GooglePlaceAutocomplete\Parameter\ComponentsInterface;
use GooglePlaceAutocomplete\Parameter\Language;
use GooglePlaceAutocomplete\Parameter\Location;
use GooglePlaceAutocomplete\Parameter\LocationInterface;
use GooglePlaceAutocomplete\Parameter\PlaceType;
use Fig\Cache\Memory\MemoryPool;
use GooglePlaceAutocomplete\Response\Response;
use GuzzleHttp\Client;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Queries the Google Places API for autocomplete suggestions.
 */
class Request implements RequestInterface {

  const BASE_URL = 'https://maps.googleapis.com/maps/api/place/autocomplete/';

  /**
   * The Google API key.
   *
   * @var string
   */
  private $key;

  /**
   * The input from the user.
   *
   * @var string
   */
  private $input;

  /**
   * The options (parameters) for the request to the Places API.
   *
   * @var array
   */
  private $options = array();

  /**
   * Cache service.
   *
   * @var \Psr\Cache\CacheItemPoolInterface
   */
  private $cachePool;

  /**
   * Constructor for Request.
   *
   * @param string $key
   *   The Google API key.
   * @param \Psr\Cache\CacheItemPoolInterface $cachePool
   *   A cache pool to use for caching responses.
   */
  public function __construct($key, CacheItemPoolInterface $cachePool = NULL) {
    $this->key = $key;

    // If a permanent cache was provided, use it as the cache backends.
    if ($cachePool) {
      $this->cachePool = $cachePool;
    }
    else {
      // Use an in memory cache backend for results as a last resort.
      $this->cachePool = new MemoryPool();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setInput($input) {
    $this->input = $input;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    // First, attempt to get the data from cache. If this fails, we will query
    // the Places API.
    if (!$responseBody = $this->cacheGet()) {
      $client = new Client(array(
        'base_uri' => static::BASE_URL,
      ));

      $response = $client->get($this->prepareUri(), $this->prepareOptions());

      if ($response->getStatusCode() !== 200) {
        $error_msg = strtr("Request failed with status: @status.", array('@status' => $response->getStatusCode()));
        throw new RequestException($error_msg, 1);
      }

      $responseBody = $response->getBody()->getContents();

      // Save these to cache for future requests.
      $this->cacheSet($responseBody);
    }

    return new Response($responseBody);
  }

  /**
   * Retrieves the results from cache.
   *
   * @return array
   *   The cached results, or NULL.
   */
  private function cacheGet() {
    $item = $this->cachePool->getItem($this->getCid());
    $data = $item->get();
    // If we found a cache value, validate the input is the same (to prevent
    // an hypotetical situation where 2 input strings have the same hash).
    if ($item->isHit() && $data['input'] == $this->input ) {
      return $data['value'];
    }
  }

  /**
   * Stores a value in the cache.
   *
   * @param mixed $response
   *   The value to be stored.
   */
  private function cacheSet($response) {
    $data = array(
      'input' => $this->input,
      'response' => $response
    );

    $cache_item = $this->cachePool->getItem($this->getCid());
    $cache_item->set($data);
    $this->cachePool->save($cache_item);
  }

  /**
   * Constructs a cache id based on the input.
   *
   * @return string
   *   The cid
   */
  private function getCid() {
    return 'hash.' . md5([$this->input] + $this->options);
  }

  /**
   * Constructs the uri for the request.
   *
   * @return string
   *   The uri.
   */
  private function prepareUri() {
    return 'json';
  }

  private function prepareOptions() {
    // Add the key and the input to options.
    $parameters = [
      'key' => $this->key,
      'input' => $this->input,
    ] + $this->options;

    $processed_parameters = [];
    foreach ($parameters as $name => $value) {
      $processed_parameters[$name] = (string) $value;
    }
    return ['query' => $processed_parameters];
  }

  /**
   * {@inheritdoc}
   */
  public function setTypes(PlaceType $placeType) {
    $this->options['types'] = $placeType;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOffset($offset) {
    // Validate the offset is integer.
    if (!is_int($offset)) {
      throw new \InvalidArgumentException("The offset is not integer.");
    }

    $this->options['offset'] = $offset;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setLocation(Location $location) {
    $this->options['location'] = $location;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setRadius($radius) {
    // Validate the radius is integer.
    if (!is_int($radius)) {
      throw new \InvalidArgumentException("The radius is not integer.");
    }

    $this->options['radius'] = $radius;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setLanguage(Language $language) {
    $this->options['language'] = $language;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setComponents(ComponentsInterface $components) {
    $this->options['components'] = $components;

    return $this;
  }

}
