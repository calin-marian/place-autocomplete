<?php

use GooglePlacesAPIAutocomplete\Parameter\PlaceType;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GooglePlacesAPIAutocomplete\Paramter\Location
 */
class PlaceTypeTest extends TestCase {

  /**
   * @covers ::__toString
   */
  public function testToString() {
    $placeType = new PlaceType(PlaceType::GEOCODE);

    $this->assertEquals(PlaceType::GEOCODE, (string) $placeType);
  }

  public function testInvalidPlaceType() {
    $this->expectException(UnexpectedValueException::class);
    $placeType = new PlaceType('invalid');
  }


}
