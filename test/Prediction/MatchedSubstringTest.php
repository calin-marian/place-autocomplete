<?php

use GooglePlacesAPIAutocomplete\Prediction\MatchedSubstring;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \GooglePlacesAPIAutocomplete\Prediction\MatchedSubstring
 */
class MatchedSubstringTest extends TestCase {

  /**
   * @covers ::getLength
   * @covers ::getOffset
   */
  public function testSubstring() {
    $matched_substring = new MatchedSubstring(10,2);

    $this->assertEquals(10, $matched_substring->getLength());
    $this->assertEquals(2, $matched_substring->getOffset());
  }

}
