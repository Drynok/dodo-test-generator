<?php

namespace DodoTestGenerator\Robo\Task\Locator;

use Robo\Result;
use Robo\Task\BaseTask;
use Symfony\Component\Finder\Finder;

/**
 * Class TestsLocator
 *
 * @todo
 * - rename to w3c test locator
 * @package DodoTestGenerator\Robo\Task\Locator
 *
 */
class TestsLocator extends BaseTask {

  const W3C_TESTS = "/var/www/vendor/fgnass/domino/test/w3c/level1";

  const WPT_TESTS = "/var/www/vendor/web-platform-tests/wpt/dom/nodes";

  /**
   * FileLoader constructor.
   *
   * @param \Symfony\Component\Finder\Finder $finder
   */
  public function __construct(Finder $finder) {
    $this->finder = $finder;
  }

  /**
   * @return array
   */
  public function run(): Result {
    if (!$this->finder) {
      return Result::error($this, 'No finder provided.');
    }

    $tests = [];

    //pick a few W3C tests
    $w3c_test = $this->getSampleW3cTests();

    if (!$this->finder->hasResults()) {
      return Result::error($this, 'No W3C tests were found');
    }

    foreach ($w3c_test as $file) {
      $tests[$file->getFilename()] = [
        'type' => 'w3c',
        'name' => $file->getFilenameWithoutExtension(),
        'test_path' => $file->getRealPath(),
        'html_file' => '', //going to fill this later after converting to php
      ];
    }

    $wpt_test = $this->getSampleWptTests();

    foreach ($w3c_test as $file) {
      $tests[$file->getFilename()] = [
        'type' => 'w3c',
        'name' => $file->getFilenameWithoutExtension(),
        'test_path' => $file->getRealPath(),
        'html_file' => $file->getRealPath(),
      ];
    }

    return Result::success($this, 'All good.', $tests);
  }

  /**
   * @return \Symfony\Component\Finder\Finder
   */
  protected function getSampleW3cTests() {
    return $this->finder->name('doc01.js')->name('HTMLDocument01.js')
      ->in(self::W3C_TESTS);
  }

  /**
   * Return sample tests
   *
   * @return \Symfony\Component\Finder\Finder
   */
  protected function getSampleWptTests() {
    return $this->finder->name("append-on-Document.html")
      ->name("Document-doctype.html")
      ->name('CharacterData-appendChild.html')
      ->in(self::WPT_TESTS);
  }

  /**
   * @TODO Implement
   */
  protected function getW3cTests() {

  }

  /**
   * @TODO Implement
   *
   */
  protected function getWptTests() {

  }

}