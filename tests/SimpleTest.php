<?php

use PHPUnit\Framework\TestCase;
use Dodo;
use Dodo\Document;

class SimpleTest extends TestCase {

  public function testDivide() {
    $this->assertEquals(5, 5);
  }

  function testDoc01() {
    global $builder;
    $success = NULL;
    //    if (checkInitialization($builder, 'doc01') != null) {
    //      return;
    //    }
    $vtitle = NULL;
    $doc = NULL;
    $docRef = NULL;
    //    if (gettype($this->doc) != NULL) {
    //      $docRef = $this->doc;
    //    }
    $doc = new Document('html', '/var/www/tests/document.html');
    //$doc = load($docRef, 'doc', 'anchor');
    //$vtitle = $doc->title;
    //assertEquals('titleLink', 'NIST DOM HTML Test - Anchor', $vtitle);
  }


}