<?php

namespace DodoTestGenerator\Robo\Task\Parser;

use Node\Expr;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeDumper;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Robo\Result;
use Robo\Task\BaseTask;

class TestsParser extends BaseTask {

  private $test;

  private $parser;

  /**
   * @var \PhpParser\NodeFinder
   */
  private $finder;

  /**
   * @var \PhpParser\NodeDumper
   */
  private $dumper;

  public function __construct($test) {
    $this->test = $test;
    $this->finder = new NodeFinder;
    $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    $this->dumper = new NodeDumper;
  }

  public function run() {
    try {
      $test_type = $this->test['type'];

      if ($test_type == 'w3c') {
        $this->parseW3cTest();
      }

    } catch (Error $error) {
      return Result::error($this, "Parse error: {$error->getMessage()}\n");
    }

    return Result::success($this, 'All good.', $this->test);
  }

  protected function parseW3cTest() {
    $ast = $this->parser->parse($this->test['transpiled']);
    $test_function = $this->test['name'];

    $traverser = new NodeTraverser;

    //clean up
    $traverser->addVisitor(new class($test_function) extends NodeVisitorAbstract {

      public $test_name;

      public function __construct($test_name) {
        $this->test_name = $test_name;
      }

      public function leaveNode(Node $node) {
        if ($node instanceof Function_ && $node->name->toString() === $this->test_name) {
          $node->setDocComment(new Doc('')); //remove unnecessary doc comment
          $node->name = 'test' . ucfirst($node->name);
          return $node;
        }

        if ($node instanceof FuncCall) {

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

    $ast = $traverser->traverse($ast);
    $this->test['ast'] = $ast;

    $load_func = $this->findFuncCall($ast, 'load');
    $this->test['html_file'] = $load_func->var->name;

    //AST -> PHP
    $prettyPrinter = new PrettyPrinter\Standard;
    $this->test['transpiled'] = $prettyPrinter->prettyPrint($ast);
  }

  /**
   *
   * @param $ast
   * @param $name
   */
  protected function findFuncCall($ast, $name) {
    return $this->finder->findFirst($ast, function (Node $node) use ($name) {
      if (isset($node->expr) && isset($node->expr->name) && $node->expr instanceof FuncCall
        && $node->expr->name->toString() == $name) {
        return $node;
      }
    });
  }

  protected function parseWptTest() {

  }

}