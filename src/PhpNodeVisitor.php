<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\NodeVisitor;

class PhpNodeVisitor implements NodeVisitor
{
    protected $validFunctions;
    protected $filename;
    protected $functions = [];

    public function __construct(string $filename, array $validFunctions = null)
    {
        $this->filename = $filename;
        $this->validFunctions = $validFunctions;
    }

    public function beforeTraverse(array $nodes)
    {
        return null;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof FuncCall) {
            $name = ($node->name instanceof Name) ? $node->name->getLast() : null;

            if ($name && ($this->validFunctions === null || in_array($name, $this->validFunctions))) {
                $this->functions[] = $this->createFunction($node);
            }

            return null;
        }

        return null;
    }

    public function leaveNode(Node $node)
    {
        return null;
    }

    public function afterTraverse(array $nodes)
    {
        return null;
    }

    public function getFunctions(): array
    {
        return $this->functions;
    }

    protected function createFunction(FuncCall $node): ParsedFunction
    {
        $function = new ParsedFunction(
            $node->name->getLast(),
            $this->filename,
            $node->getStartLine(),
            $node->getEndLine()
        );

        foreach ($node->getComments() as $comment) {
            $function->addComment(static::getComment($comment));
        }

        foreach ($node->args as $argument) {
            $value = $argument->value;

            foreach ($argument->getComments() as $comment) {
                $function->addComment(static::getComment($comment));
            }

            $function->addArgument(static::getValue($value));
        }

        return $function;
    }

    protected static function getComment(Comment $comment): string
    {
        $text = $comment->getReformattedText();

        $lines = array_map(function ($line) {
            $line = ltrim($line, "#*/ \t");
            $line = rtrim($line, "#*/ \t");
            return trim($line);
        }, explode("\n", $text));

        return trim(implode("\n", $lines));
    }

    protected static function getValue(Expr $value)
    {
        $type = $value->getType();

        switch ($type) {
            case 'Scalar_String':
            case 'Scalar_LNumber':
            case 'Scalar_DNumber':
                return $value->value;
            case 'Expr_Array':
                $arr = [];

                foreach ($value->items as $item) {
                    $value = static::getValue($item->value);

                    if ($item->key === null) {
                        $arr[] = $value;
                    } else {
                        $key = static::getValue($item->key);
                        $arr[$key] = $value;
                    }
                }

                return $arr;
        }
    }
}
