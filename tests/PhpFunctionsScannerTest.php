<?php

namespace Gettext\Tests;

use Gettext\Scanner\PhpFunctionsScanner;
use PHPUnit\Framework\TestCase;

class PhpFunctionsScannerTest extends TestCase
{
    public function testPhpFunctionsExtractor()
    {
        $scanner = new PhpFunctionsScanner();
        $file = __DIR__.'/assets/functions.php';
        $code = file_get_contents($file);
        $functions = $scanner->scan($code, $file);

        $this->assertCount(11, $functions);

        //fn1
        $function = array_shift($functions);
        $this->assertSame('fn1', $function->getName());
        $this->assertSame(3, $function->countArguments());
        $this->assertSame(['arg1', 'arg2', 3], $function->getArguments());
        $this->assertSame(4, $function->getLine());
        $this->assertSame(4, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(1, $function->getComments());

        $comments = $function->getComments();
        $this->assertSame('This comment is related with the first function', array_shift($comments));

        //fn2
        $function = array_shift($functions);
        $this->assertSame('fn2', $function->getName());
        $this->assertSame(1, $function->countArguments());
        $this->assertSame(5, $function->getLine());
        $this->assertSame(5, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(0, $function->getComments());

        //fn3
        $function = array_shift($functions);
        $this->assertSame('fn3', $function->getName());
        $this->assertSame(3, $function->countArguments());
        $this->assertSame([null, 'arg5', null], $function->getArguments());
        $this->assertSame(6, $function->getLine());
        $this->assertSame(6, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(0, $function->getComments());

        //fn4
        $function = array_shift($functions);
        $this->assertSame('fn4', $function->getName());
        $this->assertSame(1, $function->countArguments());
        $this->assertSame(['arg4'], $function->getArguments());
        $this->assertSame(6, $function->getLine());
        $this->assertSame(6, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(0, $function->getComments());

        //fn5
        $function = array_shift($functions);
        $this->assertSame('fn5', $function->getName());
        $this->assertSame(2, $function->countArguments());
        $this->assertSame([6, 7.5], $function->getArguments());
        $this->assertSame(6, $function->getLine());
        $this->assertSame(6, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(0, $function->getComments());

        //fn6
        $function = array_shift($functions);
        $this->assertSame('fn6', $function->getName());
        $this->assertSame(1, $function->countArguments());
        $this->assertSame([null], $function->getArguments());
        $this->assertSame(7, $function->getLine());
        $this->assertSame(7, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(0, $function->getComments());

        //fn7
        $function = array_shift($functions);
        $this->assertSame('fn7', $function->getName());
        $this->assertSame(1, $function->countArguments());
        $this->assertSame([null], $function->getArguments());
        $this->assertSame(8, $function->getLine());
        $this->assertSame(8, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(0, $function->getComments());

        //fn9
        $function = array_shift($functions);
        $this->assertSame('fn9', $function->getName());
        $this->assertSame(1, $function->countArguments());
        $this->assertSame([null], $function->getArguments());
        $this->assertSame(11, $function->getLine());
        $this->assertSame(11, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(2, $function->getComments());

        $comments = $function->getComments();
        $this->assertSame('fn_8();', array_shift($comments));
        $this->assertSame('ALLOW: This is a comment to fn9', array_shift($comments));


        //fn10
        $function = array_shift($functions);
        $this->assertSame('fn10', $function->getName());
        $this->assertSame(0, $function->countArguments());
        $this->assertSame(13, $function->getLine());
        $this->assertSame(13, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(1, $function->getComments());

        $comments = $function->getComments();
        $this->assertSame('Comment to fn10', array_shift($comments));

        //fn11
        $function = array_shift($functions);
        $this->assertSame('fn11', $function->getName());
        $this->assertSame(2, $function->countArguments());
        $this->assertSame(['arg9', 'arg10'], $function->getArguments());
        $this->assertSame(16, $function->getLine());
        $this->assertSame(16, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(2, $function->getComments());

        $comments = $function->getComments();
        $this->assertSame('Related comment 1', array_shift($comments));
        $this->assertSame('ALLOW: Related comment 2', array_shift($comments));

        //fn12
        $function = array_shift($functions);
        $this->assertSame('fn12', $function->getName());
        $this->assertSame(2, $function->countArguments());
        $this->assertSame(['arg11', 'arg12'], $function->getArguments());
        $this->assertSame(22, $function->getLine());
        $this->assertSame(28, $function->getLastLine());
        $this->assertSame($file, $function->getFilename());
        $this->assertCount(3, $function->getComments());

        $comments = $function->getComments();
        $this->assertSame("Related comment\nnumber one", array_shift($comments));
        $this->assertSame('Related comment 2', array_shift($comments));
        $this->assertSame('ALLOW: Related comment 3', array_shift($comments));
    }

    public function _testPhpFunctionsScannerWithDisabledComments()
    {
        $scanner = new PhpFunctionsScanner();
        $scanner->includeComments(false);
        $file = __DIR__.'/assets/functions.php';
        $code = file_get_contents($file);
        $functions = $scanner->scan($code, $file);

        $this->assertCount(11, $functions);

        foreach ($functions as $function) {
            $this->assertCount(0, $function->getComments());
        }
    }

    public function _testPhpFunctionsScannerWithPrefixedComments()
    {
        $scanner = new PhpFunctionsScanner();
        $scanner->includeComments(['ALLOW:']);
        $file = __DIR__.'/assets/functions.php';
        $code = file_get_contents($file);
        $functions = $scanner->scan($code, $file);

        $this->assertCount(11, $functions);

        //fn12
        $function = $functions[10];
        $this->assertCount(1, $function->getComments());

        $comments = $function->getComments();
        $comment = $comments[0];
        $this->assertSame(23, $comment->getLine());
        $this->assertSame(23, $comment->getLastLine());
        $this->assertSame('ALLOW: Related comment 3', $comment->getComment());
    }

    public function stringDecodeProvider()
    {
        return [
            ['"test"', 'test'],
            ["'test'", 'test'],
            ["'DATE \a\\t TIME'", 'DATE \a\t TIME'],
            ["'DATE \a\\t TIME$'", 'DATE \a\t TIME$'],
            ["'DATE \a\\t TIME\$'", 'DATE \a\t TIME$'],
            ["'DATE \a\\t TIME\$a'", 'DATE \a\t TIME$a'],
            ['"FIELD\\tFIELD"', "FIELD\tFIELD"],
            ['"$"', '$'],
            ['"Hi $"', 'Hi $'],
            ['"$ hi"', '$ hi'],
            ['"Hi\t$name"', "Hi\t\$name"],
            ['"Hi\\\\"', 'Hi\\'],
            ['"{$obj->name}"', '{$obj->name}'],
            ['"a\x20b $c"', 'a b $c'],
            ['"a\x01b\2 \1 \01 \001 \r \n \t \v \f"', "a\1b\2 \1 \1 \1 \r \n \t \v \f"],
            ['"$ \$a \""', '$ $a "'],
        ];
    }

    /**
     * @dataProvider stringDecodeProvider
     */
    public function _testStringDecode($source, $decoded)
    {
        $this->assertSame($decoded, PhpFunctionsScanner::decode($source));
    }
}
