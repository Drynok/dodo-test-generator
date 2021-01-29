<?php

namespace DodoTestGenerator\Robo\Task\TestGenerator;

use Nette\PhpGenerator\ClassType;
use PHPUnit_Framework_TestCase;
use Robo\Result;
use Robo\Task\BaseTask;

class PhpTestGenerator extends BaseTask {

  private $tests;

  public function __construct($tests) {
    $this->tests = $tests;
  }

  public function run(): Result {
    foreach ($this->tests as $test_name => $test) {
      $class = new ClassType('SimpleTest');
      $class
        ->setExtends(PHPUnit_Framework_TestCase::class)
        ->addMethod($test_name);
    }

    return Result::success($this, 'All good.', $this->tests);
  }

}