<?php
namespace GooglePlaceAutocomplete\Prediction;


class Prediction {

  /**
   * @var string
   */
  private $description;

  /**
   * @var string
   */
  private $id;

  /**
   * @var MatchedSubstring[]
   */
  private $matched_substrings = [];

  /**
   * @var string
   */
  private $place_id;

  /**
   * @var string
   */
  private $reference;

  /**
   * @var Term[]
   */
  private $terms = [];

  /**
   * @var string[]
   */
  private $types = [];

  /**
   * Prediction constructor.
   * @param $data
   */
  public function __construct($data) {
    $this->description = $data->description;
    $this->id = $data->id;
    foreach ($data->matched_substrings as $matched_substring) {
      $this->matched_substrings []= new MatchedSubstring($matched_substring->length, $matched_substring->offset);
    }
    $this->place_id = $data->place_id;
    $this->reference = $data->reference;
    foreach ($data->terms as $term) {
      $this->terms []= new Term($term->value, $term->offset);
    }
    $this->types = $data->types;
  }

  /**
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return \GooglePlaceAutocomplete\Prediction\MatchedSubstring[]
   */
  public function getMatchedSubstrings() {
    return $this->matched_substrings;
  }

  /**
   * @return string
   */
  public function getPlaceId() {
    return $this->place_id;
  }

  /**
   * @return string
   */
  public function getReference() {
    return $this->reference;
  }

  /**
   * @return \GooglePlaceAutocomplete\Prediction\Term[]
   */
  public function getTerms() {
    return $this->terms;
  }

  /**
   * @return string[]
   */
  public function getTypes() {
    return $this->types;
  }
  
}
