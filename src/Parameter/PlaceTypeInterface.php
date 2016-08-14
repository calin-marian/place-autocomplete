<?php
namespace GooglePlacesAPIAutocomplete\Parameter;

/**
 * @file
 * Contains GooglePlacesAPIAutocomplete\Parameter\PlaceTypeInterface.
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