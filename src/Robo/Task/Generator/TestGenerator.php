<?php

namespace DodoTestGenerator\Robo\Task\Generator;

use PhpParser\NodeDumper;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use Robo\Result;
use Robo\Task\BaseTask;

/**
 * Class TestGenerator
 *
 * @package DodoTestGenerator\Robo\Task\Generator
 */
class TestGenerator extends BaseTask {

  private $test;

  /**
   * @var \PhpParser\NodeFinder
   */
  private $finder;

  /**
   * @var \PhpParser\Parser|\PhpParser\Parser\Multiple|\PhpParser\Parser\Php5|\PhpParser\Parser\Php7
   */
  private $parser;

  /**
   * @var \PhpParser\NodeDumper
   */
  private $dumper;

  /**
   * TestGenerator constructor.
   *
   * @param $test
   */
  public function __construct($test) {
    $this->test = $test;
    $this->finder = new NodeFinder;
    $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    $this->dumper = new NodeDumper;
  }

  public function run(): Result {


    return Result::success($this, 'All good.', $this->test);
  }

  /**
   *
   */
  protected function replaceDisparity() {

  }

}