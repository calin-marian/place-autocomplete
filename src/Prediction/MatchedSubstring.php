<?php
namespace GooglePlaceAutocomplete\Prediction;


class MatchedSubstring {

  /**
   * @var int
   */
  private $length;

  /**
   * @var int
   */
  private $offset;

  /**
   * MatchedSubstring constructor.
   * @param int $length
   * @param int $offset
   */
  public function __construct($length, $offset) {
    $this->length = $length;
    $this->offset = $offset;
  }

  /**
   * @return int
   */
  public function getLength() {
    return $this->length;
  }

  /**
   * @return int
   */
  public function getOffset() {
    return $this->offset;
  }
  
}
