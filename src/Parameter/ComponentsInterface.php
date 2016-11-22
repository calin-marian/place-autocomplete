<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 8/14/16
 * Time: 13:41
 */

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
