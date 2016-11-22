<?php

namespace GooglePlaceAutocomplete\Request;

/**
 * @file
 * Contains GooglePlaceAutocomplete\Request\Request.
 */

use GooglePlaceAutocomplete\Exception\RequestException;
use GooglePlaceAutocomplete\Parameter\LocationInterface;
use GooglePlaceAutocomplete\Parameter\PlaceType;
use Fig\Cache\Memory\MemoryPool;
use GooglePlaceAutocomplete\Response\Response;
use GoogleSupportedLanguages\Languages\LanguageInterface;
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
   * The result of the query.
   *
   * @var array
   */
  private $result;

  /**
   * Constructor for Query.
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

      $response = $client->get($this->prepareUri());

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

    // Add the key and the input to them.
    $parameters = array(
      'key' => $this->key,
      'input' => $this->input,
    ) + $this->options;

    // Url encode the parameters.
    $processed_parameters = array();
    foreach ($parameters as $name => $value) {
      $processed_parameters[] = urlencode($name) . '=' . urlencode($value);
    }

    // Add the parameters to the endpoint and return them.
    return 'json?' . implode('&', $processed_parameters);
  }

  /**
   * Set the types parameter for the request.
   *
   * @param \GooglePlaceAutocomplete\Parameter\PlaceType $placeType
   * @return $this
   */
  public function setTypes(PlaceType $placeType) {
    $this->options['types'] = $placeType;

    return $this;
  }

  /**
   * Set the offest parameter for the request.
   *
   * The position, in the input term, of the last character that the service
   * uses to match predictions. For example, if the input is 'Google' and the
   * offset is 3, the service will match on 'Goo'. The string determined by the
   * offset is matched against the first word in the input term only. For
   * example, if the input term is 'Google abc' and the offset is 3, the service
   * will attempt to match against 'Goo abc'. If no offset is supplied, the
   * service will use the whole term. The offset should generally be set to the
   * position of the text caret.
   *
   * @param int $offset
   * @return $this
   *
   * @throws \InvalidArgumentException
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
   * Set the location parameter for the request.
   *
   * The point around which you wish to retrieve place information.
   *
   * @param \GooglePlaceAutocomplete\Parameter\LocationInterface $location
   * @return $this
   */
  public function setLocation(LocationInterface $location) {
    $this->options['location'] = $location;

    return $this;
  }

  /**
   * Set the radius parameter for the request.
   *
   * The distance (in meters) within which to return place results. Note that
   * setting a radius biases results to the indicated area, but may not fully
   * restrict results to the specified area. See Location Biasing below.
   *
   * @param int $radius
   * @return $this
   *
   * @throws \InvalidArgumentException
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
   * Set the language paramenter for the request.
   *
   * The language in which the results should be returned, if possible. Searches
   * are also biased to the selected language; results in the selected language
   * may be given a higher ranking. If language is not supplied, the Place
   * Autocomplete service will attempt to use the native language of the domain
   * from which the request is sent.
   *
   * @param \GoogleSupportedLanguages\Languages\LanguageInterface $language
   * @return $this
   */
  public function setLanguage(LanguageInterface $language) {
    $this->options['language'] = $language;

    return $this;
  }

  /**
   * Set the components paramenter for the request.
   *
   * A grouping of places to which you would like to restrict your results.
   * Currently, you can use components to filter by country. For example:
   * components=country:fr would restrict your results to places within France
   *
   * @param \GooglePlaceAutocomplete\Request\ComponentsInterface $components
   * @return $this
   */
  public function setComponents(ComponentsInterface $components) {
    $this->options['components'] = $components;

    return $this;
  }

}
