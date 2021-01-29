<?php

namespace DodoTestGenerator\Robo\Task\Locator;

use _HumbugBox5d215ba2066e\ReflectionUnionType;
use DodoTestGenerator\Helper\Sanitizer;
use Exception;
use Robo\Common\IO;
use Robo\Common\TaskIO;
use Robo\Result;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Robo\Task\BaseTask;

/**
 * Class TestsLocator
 *
 * @todo
 * - rename to w3c test locator
 * @package DodoTestGenerator\Robo\Task\Locator
 *
 */
class TestsLocator extends BaseTask {

  const W3C_TESTS = "/var/www/vendor/fgnass/domino/test/w3c/level1/html";

  //  const WPT_TESTS = "/var/www/vendor/web-platform-tests/wpt/dom/nodes";

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
    //pick a few for testing
    $w3c_test = $this->finder->name('anchor01.js')->name('HTMLDocument01.js')
      ->in(self::W3C_TESTS);

    if ($this->finder->hasResults()) {
      foreach ($w3c_test as $file) {
        $tests[$file->getFilename()] = [
          'name' => $file->getFilenameWithoutExtension(),
          'test_path' => $file->getRealPath(),
          'html_file' => '', //going to fill this later after converting to php
        ];
      }

      return Result::success($this, 'All good.', $tests);
    }

    return Result::error($this, 'No tests were found');
  }

}