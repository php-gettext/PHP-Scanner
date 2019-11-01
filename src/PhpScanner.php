<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Gettext\Translations;

/**
 * Class to scan PHP files and get gettext translations
 */
class PhpScanner extends CodeScanner
{
    public function getFunctionsScanner(): FunctionsScannerInterface
    {
        return new PhpFunctionsScanner(array_keys($this->functions));
    }
}
