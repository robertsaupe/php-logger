<?php
define('LIB_DIR', dirname(__DIR__));
define('LIB_TEST_DIR', __DIR__);
include_once(LIB_DIR . '/vendor/autoload.php');

use robertsaupe\Logger\LogFile;

$logger = new LogFile(dirname(__DIR__).'/logs', 'test', verbosityKey:LogFile::VERBOSITY_KEY_DEBUG);

$logger->error('error');
$logger->warning('warning');
$logger->info('info');
$logger->normal('normal');
$logger->verbose('verbose');
$logger->veryverbose('veryverbose');
$logger->debug('debug');

?>