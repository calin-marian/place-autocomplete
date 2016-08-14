<?php
namespace GooglePlacesAPIAutocomplete\Component;


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
    return $this->formatString("@parameter:@value", [
      '@parameter' => static::PARAMETER_NAME,
      "@value" => strtolower($this->country->getIso3166Alpha2CountryCode())
    ]);
  }

  /**
   * Replace tokens with their values in a string.
   *
   * @param string $string
   * @param array $replacements
   * @return string
   */
  private function formatString($string, $replacements) {
    return str_replace(array_keys($replacements), array_values($replacements), $string);
  }

}