<?php
namespace GooglePlaceAutocomplete\Component;

use Markenwerk\Iso3166Country\Iso3166Country;

interface CountryInterface extends ComponentInterface {

  /**
   * Country constructor.
   *
   * @param \Markenwerk\Iso3166Country\Iso3166Country $country
   */
  public function __construct(Iso3166Country $country);

}
