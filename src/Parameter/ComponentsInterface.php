<?php

namespace GooglePlaceAutocomplete\Parameter;

use GooglePlaceAutocomplete\Component\ComponentInterface;

interface ComponentsInterface extends ParameterInterface {

  /**
   * Add a component to the parameter.
   * 
   * @param \GooglePlaceAutocomplete\Component\ComponentInterface $component
   */
  public function addComponent(ComponentInterface $component);

}
