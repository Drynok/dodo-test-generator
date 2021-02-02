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
    $this->generateFromScaffold();
    return Result::success($this, 'All good.', $this->test);
  }

  protected function generateFromScaffold() {
    $test_scaffold = file_get_contents('/var/www/tests/TestScaffold.php');
    $test_scaffold = str_replace("%test_function%", $this->test['transpiled'], $test_scaffold);
    $test_scaffold = str_replace("%test_class%", ucfirst($this->test['name']), $test_scaffold);
    $this->test['transpiled'] = $test_scaffold;
  }

  /**
   *
   */
  protected function replaceDisparity() {

  }

}