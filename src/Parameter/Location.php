<?php
namespace GooglePlaceAutocomplete\Parameter;

/**
 * @file
 * Contains GooglePlaceAutocomplete\Parameter\Location.
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
   *
   * @throws \InvalidArgumentException
   * @throws \UnexpectedValueException
   */
  public function __construct($latitude, $longitude) {
    $this->latitude = $latitude;
    $this->validateLatitude();

    $this->longitude = $longitude;
    $this->validateLongitude();
  }

  /**
   * Convert this to string.
   *
   * @return string
   */
  public function __toString() {
    return strtr("@latitude,@longitude", ['@latitude' => $this->latitude, "@longitude" => $this->longitude]);
  }

  /**
   * Validate latitude
   */
  private function validateLatitude() {
    if (!is_numeric($this->latitude)) {
      throw new \InvalidArgumentException('The latitude has to be numeric');
    }

    // We need to typecast the value to float, because we test for numeric above
    // and that means also strings that contain numbers.
    $this->latitude = (float) $this->latitude;

    if ($this->latitude > 90 || $this->latitude < -90) {
      throw new \DomainException('The latitude must be between -90 and 90');
    }
  }

  /**
   * Validate longitude
   */
  private function validateLongitude() {
    if (!is_numeric($this->longitude)) {
      throw new \InvalidArgumentException('The longitude has to be numeric');
    }

    // We need to typecast the value to float, because we test for numeric above
    // and that means also strings that contain numbers.
    $this->longitude = (float) $this->longitude;

    if ($this->longitude > 180 || $this->longitude < -180) {
      throw new \DomainException('The longitude must be between -180 and 180');
    }
  }

}
