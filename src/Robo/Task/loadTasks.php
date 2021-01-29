<?php

namespace DodoTestGenerator\Robo\Task;

use DodoTestGenerator\Robo\Task\Locator\TestsLocator;
use DodoTestGenerator\Robo\Task\TestGenerator\PhpTestGenerator;
use DodoTestGenerator\Robo\Task\Parser\TestsParser;
use DodoTestGenerator\Robo\Task\Traspiler\Transpiler;
use Robo\Collection\CollectionBuilder;
use Robo\Result;

/**
 * Load DodoTestGenerator Robo tasks.
 */
trait loadTasks {

  protected function taskTestsLocator($finder = NULL): CollectionBuilder {
    return $this->task(TestsLocator::class, $finder);
  }

  protected function taskParseTests($test = NULL): CollectionBuilder {
    return $this->task(TestsParser::class, $test);
  }

  protected function taskGenerateTest($tests = NULL): CollectionBuilder {
    return $this->task(TestsParser::class, $tests);
  }
}
