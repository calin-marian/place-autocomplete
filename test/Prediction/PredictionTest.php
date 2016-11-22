<?php

use GooglePlaceAutocomplete\Parameter\PlaceType;
use GooglePlaceAutocomplete\Prediction\MatchedSubstring;
use GooglePlaceAutocomplete\Prediction\Prediction;
use GooglePlaceAutocomplete\Prediction\Term;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GooglePlaceAutocomplete\Prediction\Prediction
 */
class PredictionTest extends TestCase {

  /**
   * @covers ::getDescription
   * @covers ::getId
   * @covers ::getMatchedSubstrings
   * @covers ::getPlaceId
   * @covers ::getReference
   * @covers ::getTerms
   * @covers ::getTypes
   */
  public function testPrediction() {
    $prediction_data = json_decode(file_get_contents(dirname(__FILE__) . '/prediction.json'));
    $prediction = new Prediction($prediction_data);

    $expected_description = "Paris Street, Carlton, New South Wales, Australia";
    $this->assertEquals($expected_description, $prediction->getDescription());

    $expected_id = "bee539812eeda477dad282bcc8310758fb31d64d";
    $this->assertEquals($expected_id, $prediction->getId());

    $matched_substrings = $prediction->getMatchedSubstrings();
    $matched_substring = reset($matched_substrings);
    $this->assertInstanceOf(MatchedSubstring::class, $matched_substring);

    $expected_place_id = "ChIJCfeffMi5EmsRp7ykjcnb3VY";
    $this->assertEquals($expected_place_id, $prediction->getPlaceId());

    $expected_reference = "CkQ1AAAAAERlxMXkaNPLDxUJFLm4xkzX_h8I49HvGPvmtZjlYSVWp9yUhQSwfsdveHV0yhzYki3nguTBTVX2NzmJDukq9RIQNcoFTuz642b4LIzmLgcr5RoUrZhuNqnFHegHsAjtoUUjmhy4_rA";
    $this->assertEquals($expected_reference, $prediction->getReference());

    $terms = $prediction->getTerms();
    $term = reset($terms);
    $this->assertInstanceOf(Term::class, $term);

    $types = $prediction->getTypes();
    $this->assertContains(PlaceType::GEOCODE, $types);

  }

}
