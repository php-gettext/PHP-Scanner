<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;

class PhpFunctionsScanner implements FunctionsScannerInterface
{
    /** @var Parser */
    protected $parser;

    /** @var array|null */
    protected $validFunctions;

    public function __construct(?array $validFunctions = null, ?Parser $parser = null)
    {
        $this->validFunctions = $validFunctions;
        $this->parser = $parser ?: static::createParser();
    }

    protected static function createParser(): Parser
    {
        $factory = new ParserFactory();

        // nikic/php-parser 5
        if (method_exists($factory, 'createForNewestSupportedVersion')) {
            return $factory->createForNewestSupportedVersion();
        }

        // nikic/php-parser 4
        return $factory->create(ParserFactory::PREFER_PHP7);
    }

    public function scan(string $code, string $filename): array
    {
        $ast = $this->parser->parse($code);

        if (empty($ast)) {
            return [];
        }

        $visitor = $this->createNodeVisitor($filename);

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        return $visitor->getFunctions();
    }

    protected function createNodeVisitor(string $filename): PhpNodeVisitor
    {
        return new PhpNodeVisitor($filename, $this->validFunctions);
    }
}
