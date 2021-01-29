<?php

namespace Grobmeier\PHPUnit;

class SimpleTest extends TestCase {

  public function setUp() {
    parent::setUp();
  }

  public function testDivide() {
    $this->assertEquals(5, 0);
  }

}