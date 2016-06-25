<?php

namespace Dreamproduction\GooglePlacesAPI\Query;

/**
 * Contains Dreamproduction\GooglePlacesAPI\Query\QueryInterface.
 */

interface QueryInterface {

  /**
   * Performs the search on the Places API.
   *
   * @param string $input
   *   The query string.
   * @param array $options
   * @return QueryInterface
   * @throws \Dreamproduction\GooglePlacesAPI\Exception\RequestException
   */
  public function query($input, $options = array());

  /**
   * Execute the query.
   *
   * @return array
   *   The results.
   */
  public function execute();

}