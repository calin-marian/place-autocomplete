<?php
namespace GooglePlacesAPIAutocomplete\Prediction;


class Term {

  /**
   * @var string
   */
  private $value;

  /**
   * @var int
   */
  private $offset;

  /**
   * Term constructor.
   * @param string $value
   * @param int $offset
   */
  public function __construct($value, $offset) {
    $this->value = $value;
    $this->offset = $offset;
  }

  /**
   * @return string
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * @return int
   */
  public function getOffset() {
    return $this->offset;
  }
  
}