<?php

namespace Dreamproduction\GooglePlacesAPI\Request;

/**
 * @file
 * Contains Dreamproduction\GooglePlacesAPI\Request\Request.
 */

use Dreamproduction\GooglePlacesAPI\Exception\RequestException;
use Dreamproduction\GooglePlacesAPI\Parameter\Location;
use Dreamproduction\GooglePlacesAPI\Parameter\PlaceTypes;
use Fig\Cache\Memory\MemoryPool;
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
//    language — The language code, indicating in which language the results should be returned, if possible. Searches are also biased to the selected language; results in the selected language may be given a higher ranking. See the list of supported languages and their codes. Note that we often update supported languages so this list may not be exhaustive. If language is not supplied, the Place Autocomplete service will attempt to use the native language of the domain from which the request is sent.
//    components — A grouping of places to which you would like to restrict your results. Currently, you can use components to filter by country. The country must be passed as a two character, ISO 3166-1 Alpha-2 compatible country code. For example: components=country:fr would restrict your results to places within France
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
  public function query($input, $options = array()) {
    $this->input = $input;
    $this->options = $options;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    // First, attempt to get the data from cache. If this fails, we will query
    // the Places API.
    if (!$this->result = $this->cacheGet()) {
      $client = new Client(array(
        'base_uri' => static::BASE_URL,
      ));

      $response = $client->get($this->prepareUri());

      if ($response->getStatusCode() !== 200) {
        $error_msg = strtr("Request failed with status: @status.", array('@status' => $response->getStatusCode()));
        throw new RequestException($error_msg, 1);
      }

      // Decode the response json.
      $decoded_response = json_decode($response->getBody()->getContents());

      // If not an object we hit some unknown error.
      if (!is_object($decoded_response)) {
        $error_msg = "Unknown error getting data from Google Places API.";
        throw new RequestException($error_msg, 1);
      }

      // If status code is not OK or ZERO_RESULTS, we hit a defined Places API error
      if (!in_array($decoded_response->status, array('OK', 'ZERO_RESULTS'))) {
        $error_msg = strtr("Google responded with status: @status @error_mesage.", array(
          '@status' => $decoded_response->status,
          '@error_message' => isset($decoded_response->error_message) ? $decoded_response->error_message : ''
        ));
        throw new RequestException($error_msg, 1);
      }

      // Set the results.
      $this->result = $decoded_response;

      // Save these to cache for future requests.
      $this->cacheSet($this->result);
    }

    return $this->result;
  }

  /**
   * Retrieves the results from cache.
   *
   * @return array
   *   The cached results, or NULL.
   */
  private function cacheGet() {
    foreach ($this->cachePool as $cache) {
      $item = $cache->getItem($this->getCid());
      $data = $item->get();
      // If we found a cache value, validate the input is the same (to prevent
      // an hypotetical situation where 2 input strings have the same hash).
      if ($item->isHit() && $data['input'] == $this->input ) {
        return $data['value'];
      }
    }
  }

  /**
   * Stores a value in the cache.
   *
   * @param mixed $value
   *   The value to be stored.
   */
  private function cacheSet($value) {
    $data = array(
      'input' => $this->input,
      'value' => $value
    );

    foreach ($this->cachePool as $cache) {
      $cache_item = $cache->getItem($this->getCid());
      $cache_item->set($data);
      $cache->save($cache_item);
    }
  }

  /**
   * Constructs a cache id based on the input.
   *
   * @return string
   *   The cid
   */
  private function getCid() {
    return 'hash.' . md5($this->input);
  }

  /**
   * Constructs the uri for the request.
   *
   * @return string
   *   The uri.
   */
  private function prepareUri() {
    // Get the options for the request.
    $options = $this->options;

    // Add the key and the input to them.
    $parameters = array(
      'key' => $this->key,
      'input' => $this->input,
    ) + $options;

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
   * @param string $placeType
   * @return $this
   *
   * @throws \InvalidArgumentException
   *
   * @see PlaceTypes
   */
  public function setTypes($placeType) {
    // Validate the type is one of the supported ones.
    if (!in_array($placeType, PlaceTypes::getAllPlaceTypes())) {
      throw new \InvalidArgumentException("The place type is not one of the supported ones.");
    }

    // If the type is ALL, that is achieved by not passing the parameter.
    if ($placeType != PlaceTypes::ALL) {
      $this->options['types'] = $placeType;
    }

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
   * @param \Dreamproduction\GooglePlacesAPI\Parameter\Location $location
   * @return $this
   */
  public function setLocation(Location $location) {
    $this->options['location'] = (string) $location;

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

  
}
