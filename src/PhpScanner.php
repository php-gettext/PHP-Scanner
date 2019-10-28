<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Exception;
use Gettext\Translations;
use Gettext\Translation;
use Gettext\Scanner\ParsedFunction;
use Gettext\Scanner\PhpFunctionsScanner;
use Gettext\Scanner\FunctionsScannerInterface;

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
