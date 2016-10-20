<?php
namespace GooglePlacesAPIAutocomplete\Parameter;

use GooglePlacesAPIAutocomplete\Component\ComponentInterface;

class Components implements ComponentsInterface {

  /**
   * @var ComponentInterface[]
   */
  private $components;

  /**
   * Add a component to the parameter.
   *
   * @param \GooglePlacesAPIAutocomplete\Component\ComponentInterface $component
   */
  public function addComponent(ComponentInterface $component) {
    $this->components []= $component;
  }

  /**
   * @return string
   * @throws \DomainException
   */
  public function __toString() {
    if (empty($this->components)) {
      throw new \DomainException('The components parameter must be nonempty. If you don\'t want to filter by components, dont\'t provide the parameter at all.');
    }
    
    $stringComponents = [];
    foreach ($this->components as $component) {
      $stringComponents []= (string) $component;
    }
    
    return implode(',', $stringComponents);
  }
}