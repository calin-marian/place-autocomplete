<?php
namespace GooglePlacesAPIAutocomplete\Parameter;

/**
 * @file
 * Contains GooglePlacesAPIAutocomplete\Parameter\LocationInterface.
 */

interface LocationInterface extends ParameterInterface {
  
  /**
   * Location constructor.
   * @param float $latitude
   * @param float $longitude
   *
   * @throws \InvalidArgumentException
   * @throws \UnexpectedValueException
   */
  public function __construct($latitude, $longitude);

}