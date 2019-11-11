<?php

namespace Gettext\Tests;

use Exception;
use Gettext\Scanner\PhpScanner;
use Gettext\Translations;
use PHPUnit\Framework\TestCase;

class PhpScannerTest extends TestCase
{
    public function testPhpCodeScanner()
    {
        $file = __DIR__.'/assets/code.php';

        $scanner = new PhpScanner(
            Translations::create('domain1'),
            Translations::create('domain2'),
            Translations::create('domain3')
        );

        $this->assertCount(3, $scanner->getTranslations());

        $scanner->scanFile($file);

        list($domain1, $domain2, $domain3) = array_values($scanner->getTranslations());

        $this->assertCount(6, $domain1);
        $this->assertCount(4, $domain2);
        $this->assertCount(1, $domain3);

        $scanner->setDefaultDomain('domain1');
        $scanner->scanFile($file);

        $this->assertCount(39, $domain1);
        $this->assertCount(4, $domain2);
        $this->assertCount(1, $domain3);
    }

    public function testInvalidFunction()
    {
        $this->expectException(Exception::class);

        $scanner = new PhpScanner(Translations::create('messages'));
        $scanner->scanString('<?php __(ucfirst("invalid function"));', 'file.php');

        list($translations) = array_values($scanner->getTranslations());

        $this->assertCount(0, $translations);
    }

    public function testIgnoredInvalidFunction()
    {
        $scanner = new PhpScanner(Translations::create('messages'));
        $scanner->ignoreInvalidFunctions();
        $scanner->scanString('<?php __(ucfirst("invalid function"));', 'file.php');

        list($translations) = array_values($scanner->getTranslations());

        $this->assertCount(0, $translations);
    }
}
