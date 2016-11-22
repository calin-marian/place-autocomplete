<?php

namespace GooglePlaceAutocomplete\Request;

use GooglePlaceAutocomplete\Component\ComponentInterface;
use GooglePlaceAutocomplete\Parameter\Location;
use GooglePlaceAutocomplete\Parameter\PlaceType;
use GoogleSupportedLanguages\Languages\LanguageInterface;

/**
 * Contains GooglePlaceAutocomplete\Request\RequestInterface.
 */

interface RequestInterface {

  /**
   * Execute the request.
   *
   * @return \GooglePlaceAutocomplete\Response\Response
   * @throws \GooglePlaceAutocomplete\Exception\RequestException
   */
  public function execute();

  /**
   * Set the imput for the autocomplete.
   *
   * @param string $input
   * @return $this
   */
  public function setInput($input);

  /**
   * Set the types parameter for the request.
   *
   * @param \GooglePlaceAutocomplete\Parameter\PlaceType $placeType
   * @return $this
   */
  public function setTypes(PlaceType $placeType);

  /**
   * Set the offest parameter for the request.
   *
   * The position, in the input term, of the last character that the service
   * uses to match predictions. For example, if the input is 'Google' and the
   * offset is 3, the service will match on 'Goo'. The string determined by the
   * offset is matched against the first word in the input term only. For
   * example, if the input term is 'Google abc' and the offset is 3, the service
   * will attempt to match against 'Goo abc'. If no offset is supplied, the
   * service will use the whole term. The offset should generally be set to the
   * position of the text caret.
   *
   * @param int $offset
   * @return $this
   *
   * @throws \InvalidArgumentException
   */
  public function setOffset($offset);

  /**
   * Set the location parameter for the request.
   *
   * The point around which you wish to retrieve place information.
   *
   * @param \GooglePlaceAutocomplete\Parameter\Location $location
   * @return $this
   */
  public function setLocation(Location $location);

  /**
   * Set the radius parameter for the request.
   *
   * The distance (in meters) within which to return place results. Note that
   * setting a radius biases results to the indicated area, but may not fully
   * restrict results to the specified area. See Location Biasing below.
   *
   * @param int $radius
   * @return $this
   *
   * @throws \InvalidArgumentException
   */
  public function setRadius($radius);

  /**
   * Set the language paramenter for the request.
   *
   * The language in which the results should be returned, if possible. Searches
   * are also biased to the selected language; results in the selected language
   * may be given a higher ranking. If language is not supplied, the Place
   * Autocomplete service will attempt to use the native language of the domain
   * from which the request is sent.
   *
   * @param \GoogleSupportedLanguages\Languages\LanguageInterface $language
   * @return $this
   */
  public function setLanguage(LanguageInterface $language);

  /**
   * Set the components paramenter for the request.
   *
   * A grouping of places to which you would like to restrict your results.
   * Currently, you can use components to filter by country. For example:
   * components=country:fr would restrict your results to places within France
   *
   * @param \GooglePlaceAutocomplete\Component\ComponentInterface $components
   * @return $this
   */
  public function setComponents(ComponentInterface $components);

}
