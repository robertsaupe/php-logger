<?php

declare(strict_types=1);

/*
 * This file is part of the robertsaupe/php-logger package.
 *
 * (c) Robert Saupe <mail@robertsaupe.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace robertsaupe\Logger;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class LogHTML extends LogFile {

    public const DEFAULT_FILE_EXTENSION = 'html';

    public function __construct(
        protected string $fileBasePath,
        protected string $fileBaseName,
        protected int $chmod = 0600,
        protected string $fileExtension = self::DEFAULT_FILE_EXTENSION,
        protected string $fileDateFormat = self::DEFAULT_FILE_DATE_FORMAT,
        protected string $verbosityKey = self::VERBOSITY_KEY_NORMAL,
        protected string $dateFormat = self::DEFAULT_DATE_FORMAT,
        protected string $messageFormat = self::DEFAULT_MESSAGE_FORMAT
    ) {
        parent::__construct(
            $this->fileBasePath,
            $this->fileBaseName,
            $this->chmod,
            $this->fileExtension,
            $this->fileDateFormat,
            $this->verbosityKey,
            $this->dateFormat,
            $this->messageFormat
        );
    }

    /**
     * @throws IOExceptionInterface 
     */
    protected function writeMessageToFile(LogMessage $messageObject): void {
        if ($this->getVerbosityValue($this->verbosityKey) >= $messageObject->getVerbosityValue()) {
            $this->writeToFile($this->getFormattedMessage($messageObject, true));
        }
    }

}

?>