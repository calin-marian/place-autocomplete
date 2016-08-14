<?php

use GooglePlacesAPIAutocomplete\Component\Country;
use Markenwerk\Iso3166Country\Iso3166CountryInformation;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GooglePlacesAPIAutocomplete\Component\Country
 */
class CountryTest extends TestCase {

  /**
   * @covers ::__toString
   */
  public function testToString() {
    // TODO: mock country.
    $country = Iso3166CountryInformation::getByIso3166Alpha2('FR');
    $countryComponent = new Country($country);
    $this->assertEquals('country:fr', (string) $countryComponent);
  }

}
