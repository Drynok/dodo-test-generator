<?php

use DodoTestGenerator\Robo\Task\loadTasks;
use Robo\Tasks;
use Symfony\Component\Finder\Finder;

class RoboFile extends Tasks {

  use loadTasks;
  use \Robo\Task\Testing\loadTasks;
  use \Robo\Task\File\loadTasks;

  public function build() {
    $this->stopOnFail(TRUE);
    $tests = $this->taskTestsLocator(new Finder())
      ->run();

    if ($tests->wasSuccessful()) {
      foreach ($tests->getData() as &$test) {
        $test['transpiled'] = $this->getTraspiledFile($test['test_path']);
        $test = $this->taskParseTests($test)
          ->run()->getData();
        $test = $this->taskGenerateTest($test)->run()->getData();
        $this->writeTest('asp', print_r($test['ast'], TRUE));
        $this->writeTest('asp_tr', print_r($test['transpiled'], TRUE));
        break;
      }
    }
  }

  /**
   * @param $file
   *
   * @return string|null
   */
  public function getTraspiledFile($file) {
    return $this->taskExec('npx js2php')
      ->arg($file)
      ->printOutput(FALSE)
      ->run()
      ->getMessage();
  }

  /**
   * @param $test_name
   * @param $test_code
   */
  public function writeTest($test_name, $test_code) {
    $this->taskWriteToFile('/var/www/tests/' . $test_name . '.php')
      ->text($test_code)
      ->run();
  }

  /**
   * @param $test
   * @param $method
   *
   * @return \Robo\Result
   */
  public function runTest($test, $method) {
    return $this->taskPhpUnit()
      ->bootstrap('/var/www/tests/bootstrap.php')
      ->filter($method)
      ->file('/var/www/tests/' . $test . '.php')
      ->run();
  }

}