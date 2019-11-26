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

        list('domain1' => $domain1, 'domain2' => $domain2, 'domain3' => $domain3) = $scanner->getTranslations();

        $this->assertCount(6, $domain1);
        $this->assertCount(4, $domain2);
        $this->assertCount(1, $domain3);

        $scanner->setDefaultDomain('domain1');
        $scanner->extractCommentsStartingWith('');
        $scanner->scanFile($file);

        $this->assertCount(39, $domain1);
        $this->assertCount(4, $domain2);
        $this->assertCount(1, $domain3);

        //Extract comments
        $translation = $domain1->find('CONTEXT', 'All comments');
        $this->assertNotNull($translation);
        $this->assertSame([$file => [66]], $translation->getReferences()->toArray());
        $this->assertCount(1, $translation->getExtractedComments());

        $translation = $domain1->find(null, 'i18n tagged %s');
        $this->assertNotNull($translation);
        $this->assertSame([$file => [75]], $translation->getReferences()->toArray());
        $this->assertCount(1, $translation->getExtractedComments());
        $this->assertSame(['i18n Tagged comment on the line before'], $translation->getExtractedComments()->toArray());
    }

    public function testInvalidFunction()
    {
        $this->expectException(Exception::class);

        $scanner = new PhpScanner(Translations::create('messages'));
        $scanner->scanString('<?php __(ucfirst("invalid function"));', 'file.php');

        list('messages' => $translations) = array_values($scanner->getTranslations());

        $this->assertCount(0, $translations);
    }

    public function testIgnoredInvalidFunction()
    {
        $scanner = new PhpScanner(Translations::create('messages'));
        $scanner->ignoreInvalidFunctions();
        $scanner->scanString('<?php __(ucfirst("invalid function"));', 'file.php');

        list('messages' => $translations) = $scanner->getTranslations();

        $this->assertCount(0, $translations);
    }
}
