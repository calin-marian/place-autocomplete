<?php

namespace GooglePlaceAutocomplete\Component;

use Markenwerk\Iso3166Country\Iso3166Country;

class Country implements CountryInterface {

  const PARAMETER_NAME = 'country';

  /**
   * The country code in ISO-3166-1 Alpha2 format.
   *
   * @var \Markenwerk\Iso3166Country\Iso3166Country
   */
  private $country;

  /**
   * Country constructor.
   *
   * @param \Markenwerk\Iso3166Country\Iso3166Country $country
   */
  public function __construct(Iso3166Country $country) {
    $this->country = $country;
  }

  function __toString() {
    return strtr("@parameter:@value", [
      '@parameter' => static::PARAMETER_NAME,
      "@value" => strtolower($this->country->getIso3166Alpha2CountryCode())
    ]);
  }
  
}
