<?php
define('LIB_DIR', dirname(__DIR__));
define('LIB_TEST_DIR', __DIR__);
include_once(LIB_DIR . '/vendor/autoload.php');

use robertsaupe\Logger\BaseLogger;

$logger = new BaseLogger(BaseLogger::VERBOSITY_KEY_DEBUG);

$logger->error('error');
$logger->warning('warning');
$logger->info('info');
$logger->normal('normal');
$logger->verbose('verbose');
$logger->veryverbose('veryverbose');
$logger->debug('debug');

$message = $logger->normal("Testmessage");
print_r($message);
print_r($message->getArray());

print($logger->getFormattedMessagesByVerbosity());

print($logger->getFormattedMessagesByVerbosity(true));
print(PHP_EOL);
?>