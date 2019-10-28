# PHP Scanner

Created by Oscar Otero <http://oscarotero.com> <oom@oscarotero.com> (MIT License)

PHP code scanner to use with [gettext/gettext](https://github.com/php-gettext/Gettext)

## Installation

```
composer require gettext/php-scanner
```

## Usage example

```php
use Gettext\Scanner\PhpScanner;
use Gettext\Generator\PoGenerator;
use Gettext\Translations;

//Create a new scanner, adding a translation for each domain we want to get:
$phpScanner = new PhpScanner(
    Translations::create('domain1'),
    Translations::create('domain2'),
    Translations::create('domain3')
);

//Scan files
foreach (glob('*.php') as $file) {
    $phpScanner->scanFile($file);
}

//Save the translations in .po files
$generator = new PoGenerator();

foreach ($phpScanner->getTranslations() as $translations) {
    $domain = $translations->getDomain();
    $generator->generateFile($translations, "locales/{$domain}.po");
}
```
