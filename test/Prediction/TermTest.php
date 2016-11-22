<?php

use GooglePlaceAutocomplete\Prediction\Term;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GooglePlaceAutocomplete\Prediction\Term
 */
class TermTest extends TestCase {

  /**
   * @covers ::getValue
   * @covers ::getOffset
   */
  public function testTerm() {
    $term = new Term("Paris", 2);

    $this->assertEquals("Paris", $term->getValue());
    $this->assertEquals(2, $term->getOffset());
  }

}
