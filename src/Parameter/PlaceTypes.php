<?php

namespace Dreamproduction\GooglePlacesAPI\Parameter;

/**
 * @file
 * Contains Dreamproduction\GooglePlacesAPI\PlaceTypes.
 */

class PlaceTypes {

  /**
   * Intructs the Place Autocomplete service to return all types of places.
   */
  CONST ALL = null;

  /**
   * Instructs the Place Autocomplete service to return only geocoding results,
   * rather than business results. Generally, you use this request to
   * disambiguate results where the location specified may be indeterminate.
   */
  CONST GEOCODE = "geocode";
  /**
   * Instructs the Place Autocomplete service to return only geocoding results
   * with a precise address. Generally, you use this request when you know the
   * user will be looking for a fully specified address.
   */
  CONST ADDRESS = "address";
  /**
   * Instructs the Place Autocomplete service to return only business results.
   */
  CONST ESTABLISHMENT = "establishment";

  /**
   * Instructs the Places service to return any result matching the following
   * types:
   *   - locality
   *   - sublocality
   *   - postal_code
   *   - country
   *   - administrative_area_level_1
   *   - administrative_area_level_2
   */
  CONST REGIONS = "(regions)";

  /**
   * Instructs the Places service to return results that match locality or
   * administrative_area_level_3.
   */
  CONST CITIES = "(cities)";

  /**
   * Get a list of all the place types.
   *
   * @return string[]
   */
  static function getAllPlaceTypes() {
    return [
      static::ALL,
      static::GEOCODE,
      static::ADDRESS,
      static::ESTABLISHMENT,
      static::REGIONS,
      static::CITIES
    ];
  }

}