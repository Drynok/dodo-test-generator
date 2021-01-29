<?php

namespace DodoTestGenerator\Robo\Task\Parser;

use Nette\PhpGenerator\ClassType;
use PhpParser\BuilderFactory;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Expression;
use PHPUnit_Framework_TestCase;
use Robo\Result;
use Robo\Task\BaseTask;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;
use Symfony\Component\Finder\Finder;
use PhpParser\NodeFinder;
use PhpParser\Node\Expr\Variable;

class TestsParser extends BaseTask {

  private $test;

  public function __construct($test) {
    $this->test = $test;
  }

  public function run() {
    if (!$this->test) {
      return Result::error($this, "No tests were provided.");
    }

    $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

    try {
      $ast = $parser->parse($this->test['transpiled']);
      $test_function = $this->test['name'];

      $traverser = new NodeTraverser;
      $traverser->addVisitor(new class($this->test['name']) extends NodeVisitorAbstract {

        public $test_name;

        public function __construct($test_name) {
          $this->test_name = $test_name;
        }

        public function leaveNode(Node $node) {
          if ($node instanceof Function_ && $node->name->toString() === $this->test_name) {
            return $node;
          }

          //remove all other functions
          if ($node instanceof Function_ || $node instanceof Comment || $node instanceof Doc) {
            return NodeTraverser::REMOVE_NODE;
          }

          //unset variable
          if ($node instanceof Expression && $node->expr instanceof Assign && $node->expr->var instanceof Variable && ($node->expr->var->name == 'docsLoaded' || $node->expr->var->name == 'builder')) {
            return NodeTraverser::REMOVE_NODE;
          }
        }

      });
      $modifiedStmts = $traverser->traverse($ast);

      $prettyPrinter = new PrettyPrinter\Standard;
      $this->test = $prettyPrinter->prettyPrint($modifiedStmts);

    } catch (Error $error) {
      return Result::error($this, "Parse error: {$error->getMessage()}\n");
    }

    return Result::success($this, 'All good.', $this->test);
  }

  protected function rebuild($ast) {

  }

  protected function modify($ast) {


    //    return $this->rebuild($test_function);
  }

}