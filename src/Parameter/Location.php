<?php

namespace Dreamproduction\GooglePlacesAPI\Parameter;

/**
 * @file
 * Contains Dreamproduction\GooglePlacesAPI\Location.
 */

class Location {
  /**
   * The latitude.
   *
   * @var float
   */
  private $latitude;

  /**
   * The longitude.
   *
   * @var float
   */
  private $longitude;


  /**
   * Location constructor.
   * @param float $latitude
   * @param float $longitude
   */
  public function __construct($latitude, $longitude) {
    if (!is_numeric($latitude)) {
      throw new \InvalidArgumentException('The latitude has to be numeric');
    }

    if (!is_numeric($longitude)) {
      throw new \InvalidArgumentException('The longitude has to be numeric');
    }
    $this->latitude = $latitude;
    $this->longitude = $longitude;
  }

  /**
   * Convert this to string.
   *
   * @return string
   */
  public function __toString() {
    return $this->formatString("@latitude,@longitude", ['@latitude' => $this->latitude, "@longitude" => $this->longitude]);
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