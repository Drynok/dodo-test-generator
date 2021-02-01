<?php

namespace DodoTestGenerator\Robo\Task;

use DodoTestGenerator\Robo\Task\Generator\TestGenerator;
use DodoTestGenerator\Robo\Task\Locator\TestsLocator;
use DodoTestGenerator\Robo\Task\Parser\TestsParser;
use Robo\Collection\CollectionBuilder;

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

  protected function taskGenerateTest($test = NULL): CollectionBuilder {
    return $this->task(TestGenerator::class, $test);
  }

}
