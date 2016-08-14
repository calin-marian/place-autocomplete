<?php
namespace GooglePlacesAPIAutocomplete\Parameter;


use GooglePlacesAPIAutocomplete\Component\ComponentInterface;

class Components implements ComponentsInterface {

  /**
   * @var ComponentInterface[]
   */
  private $components;

  public function addComponent(ComponentInterface $component) {
    $this->components []= $component;
  }

  public function __toString() {
    $stringComponents = [];
    foreach ($this->components as $component) {
      $stringComponents []= (string) $component;
    }
    
    return implode(',', $stringComponents);
  }
}