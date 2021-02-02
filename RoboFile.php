<?php

use DodoTestGenerator\Robo\Task\loadTasks;
use Robo\Tasks;

use Symfony\Component\Finder\Finder;

class RoboFile extends Tasks {

  use loadTasks;
  use \Robo\Task\Testing\loadTasks;
  use \Robo\Task\File\loadTasks;

  /**
   * @param string $mode
   * $mode = ['file']
   */
  public function build($mode = 'file', $run = TRUE, $debug = TRUE) {
    $this->stopOnFail(TRUE);
    //locate W3C and WPT tests
    $tests = $this->taskTestsLocator(new Finder())
      ->run();

    if ($tests->wasSuccessful()) {
      foreach ($tests->getData() as &$test) {
        $test['transpiled'] = $this->getTraspiledFile($test['test_path']);
        $test = $this->taskParseTests($test)
          ->run()->getData();
        $test = $this->taskGenerateTest($test)->run()->getData();

        if ($mode == 'file') {
          $method_name = ucfirst($test['name']);
          $this->writeTest($method_name, $test['transpiled']);

          if ($run) {
            $this->runTestFromFile($method_name, $method_name);
          }
          //$this->runTestFromFile($method_name . '.php', $method_name);

          if ($debug) {
            $this->writeTest($method_name . 'AstTest', print_r($test['ast'], TRUE));
          }
        }
        else {
          //execute right away
        }
        break;
      }
    }
    else {
      $this->say('Something went wrong');
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
  public function runTestFromFile($test, $method) {
    return $this->taskPhpUnit()
      ->bootstrap('/var/www/vendor/autoload.php')
      ->filter($method)
      ->file('/var/www/tests/' . $test . '.php')
      ->run();
  }

}