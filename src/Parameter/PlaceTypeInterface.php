<?php
namespace GooglePlaceAutocomplete\Parameter;

/**
 * @file
 * Contains GooglePlaceAutocomplete\Parameter\PlaceTypeInterface.
 */

interface PlaceTypeInterface extends ParameterInterface {

  /**
   * PlaceType constructor.
   *
   * @param string $placeType
   * @throws \UnexpectedValueException
   */
  public function __construct($placeType);

}
