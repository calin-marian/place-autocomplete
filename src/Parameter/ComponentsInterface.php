<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 8/14/16
 * Time: 13:41
 */

namespace GooglePlacesAPIAutocomplete\Parameter;


use GooglePlacesAPIAutocomplete\Component\ComponentInterface;

interface ComponentsInterface extends ParameterInterface {

  /**
   * Add a component to the parameter.
   * 
   * @param \GooglePlacesAPIAutocomplete\Component\ComponentInterface $component
   */
  public function addComponent(ComponentInterface $component);

}