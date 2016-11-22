<?php

namespace GooglePlaceAutocomplete\Parameter;

use GoogleSupportedLanguages\Languages\LanguageInterface;

class Language implements ParameterInterface {

  /**
   * @var \GoogleSupportedLanguages\Languages\LanguageInterface
   */
  private $language;

  public function __construct(LanguageInterface $language) {
    $this->language = $language;
  }

  public function __toString() {
    return $this->language->getLanguageCode();
  }

}
