<?php

use GooglePlaceAutocomplete\Parameter\Location;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GooglePlaceAutocomplete\Paramter\Location
 */
class LocationTest extends TestCase {

  /**
   * @covers ::__toString
   */
  public function testToString() {
    $location = new Location(1, 2);

    $this->assertEquals('1,2', (string) $location);
  }

  public function testNonNumericLatitude() {
    $this->expectException(InvalidArgumentException::class);
    $location = new Location('a', 2);
  }

  public function testNonNumericLongitude() {
    $this->expectException(InvalidArgumentException::class);
    $location = new Location(1, 'a');
  }

  public function testLatitudetooSmall() {
    $this->expectException(DomainException::class);
    $location = new Location(-91, 2);
  }

  public function testLatitudetooLarge() {
    $this->expectException(DomainException::class);
    $location = new Location(91, 2);
  }

  public function testLongitudetooSmall() {
    $this->expectException(DomainException::class);
    $location = new Location(1, -181);
  }

  public function testLongitudetooLarge() {
    $this->expectException(DomainException::class);
    $location = new Location(1, 181);
  }

}
