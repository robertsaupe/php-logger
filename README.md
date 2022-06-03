# php-logger

[![Minimum PHP version: 8.0.2](https://img.shields.io/badge/php-8.0.2%2B-blue.svg?color=blue&style=for-the-badge)](https://packagist.org/packages/robertsaupe/php-logger)
[![Packagist Version](https://img.shields.io/packagist/v/robertsaupe/php-logger?color=blue&style=for-the-badge)](https://packagist.org/packages/robertsaupe/php-logger)
[![Packagist Downloads](https://img.shields.io/packagist/dt/robertsaupe/php-logger?color=blue&style=for-the-badge)](https://packagist.org/packages/robertsaupe/php-logger)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge)](LICENSE)

php library to create a logger

## Supporting

[GitHub](https://github.com/sponsors/robertsaupe) |
[Patreon](https://www.patreon.com/robertsaupe) |
[PayPal](https://www.paypal.com/donate?hosted_button_id=SQMRNY8YVPCZQ) |
[Amazon](https://www.amazon.de/ref=as_li_ss_tl?ie=UTF8&linkCode=ll2&tag=robertsaupe-21&linkId=b79bc86cee906816af515980cb1db95e&language=de_DE)

## Installing

```sh
composer require robertsaupe/php-logger
```

## Getting started

### Basic

```php
use robertsaupe\Logger\LogBasic;

$logger = new LogBasic();

$logger->error('error');
$logger->warning('warning');
$logger->info('info');
$logger->normal('normal');
$logger->verbose('verbose');
$logger->veryverbose('veryverbose');
$logger->debug('debug');

//return messageObject
$message = $logger->normal("Testmessage");
print_r($message);
print_r($message->getArray());

//return all messages
print($logger->getFormattedMessagesByVerbosity());

//return all messages as html
print($logger->getFormattedMessagesByVerbosity(true));
```

### File

```php
use robertsaupe\Logger\LogFile;

$logger = new LogFile(dirname(__DIR__).'/logs', 'test');

//now writes the messages to a log-file
```

### HTML

```php
use robertsaupe\Logger\LogHTML;

$logger = new LogHTML(dirname(__DIR__).'/logs', 'test');

//now writes the messages to a html-file
```

## Credits

- [Symfony](https://github.com/symfony) for [Filesystem](https://symfony.com/doc/current/components/filesystem.html)
