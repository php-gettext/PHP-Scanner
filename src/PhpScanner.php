<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Gettext\Translations;

/**
 * Class to scan PHP files and get gettext translations
 */
class PhpScanner extends CodeScanner
{
    use FunctionsHandlersTrait;

    protected $functions = [
        'gettext' => 'gettext',
        '_' => 'gettext',
        '__' => 'gettext',
        'ngettext' => 'ngettext',
        'n__' => 'ngettext',
        'pgettext' => 'pgettext',
        'p__' => 'pgettext',
        'dgettext' => 'dgettext',
        'd__' => 'dgettext',
        'dngettext' => 'dngettext',
        'dn__' => 'dngettext',
        'dpgettext' => 'dpgettext',
        'dp__' => 'dpgettext',
        'npgettext' => 'npgettext',
        'np__' => 'npgettext',
        'dnpgettext' => 'dnpgettext',
        'dnp__' => 'dnpgettext',
        'noop' => 'gettext',
        'noop__' => 'gettext',
    ];

    public function getFunctionsScanner(): FunctionsScannerInterface
    {
        return new PhpFunctionsScanner(array_keys($this->functions));
    }
}
