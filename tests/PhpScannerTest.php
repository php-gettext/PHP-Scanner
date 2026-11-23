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

        /**
         * @var Translations $domain1
         * @var Translations $domain2
         * @var Translations $domain3
         */
        ['domain1' => $domain1, 'domain2' => $domain2, 'domain3' => $domain3] = $scanner->getTranslations();

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
        $this->assertSame(['php-format'], $translation->getFlags()->toArray());
    }

    /**
     * @see https://github.com/php-gettext/PHP-Scanner/issues/19
     */
    public function testCommentsInArrays()
    {
        $code = <<<'PHP'
<?php
/* PREV: Comment 1 */
$arg = [__('String 1')];

$args = array_merge($args, [
    'title' => /* PREV: Comment 2 */ __('String 2'),
    'noscript' => [
        /* PREV: Comment 3 */
        Flash::INFO => [__('String 3')]
    ],
    'breadcrumb' => [
        ['text' => /* PREV: Comment 4 */ d__('admin', 'String 4'), 'link' => get_homepage_url()],
    ],
    'metatags' => new Meta([
        /* PREV: Comment 5 */
        'description' => __('String 5'),
    ]),
]);
PHP;

        $scanner = new PhpScanner(
            Translations::create('default'),
            Translations::create('admin')
        );
        $scanner->setFunctions(['__' => 'gettext', 'd__' => 'dgettext']);
        $scanner->ignoreInvalidFunctions(true);
        $scanner->setDefaultDomain('default');
        $scanner->extractCommentsStartingWith('PREV:');
        $scanner->scanString($code, 'file.php');

        ['default' => $default, 'admin' => $admin] = $scanner->getTranslations();

        foreach ([1, 2, 3, 5] as $number) {
            $translation = $default->find(null, "String $number");
            $this->assertNotNull($translation);
            $this->assertSame(["PREV: Comment $number"], $translation->getExtractedComments()->toArray());
        }

        $translation = $admin->find(null, 'String 4');
        $this->assertNotNull($translation);
        $this->assertSame(['PREV: Comment 4'], $translation->getExtractedComments()->toArray());
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
