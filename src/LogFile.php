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

use function sprintf;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class LogFile extends LogBasic {

    public const DEFAULT_FILE_EXTENSION = 'log';

    public const DEFAULT_FILE_DATE_FORMAT = 'Y-m-d_H-i-s';

    protected Filesystem $fileSystem;

    protected string $filePath;

    protected bool $isWritable = true;

    public function __construct(
        protected string $fileBasePath,
        protected string $fileBaseName,
        protected int|float $chmod = 0600,
        protected string $fileExtension = self::DEFAULT_FILE_EXTENSION,
        protected string $fileDateFormat = self::DEFAULT_FILE_DATE_FORMAT,
        protected string $verbosityKey = self::VERBOSITY_KEY_NORMAL,
        protected string $dateFormat = self::DEFAULT_DATE_FORMAT,
        protected string $messageFormat = self::DEFAULT_MESSAGE_FORMAT
    ) {
        parent::__construct($this->verbosityKey, $this->dateFormat, $this->messageFormat);

        $this->fileSystem = new Filesystem();
        $this->fileBasePath = trim($this->fileBasePath);
        $this->fileBasePath = Path::canonicalize($this->fileBasePath);
        $this->fileBaseName = trim($this->fileBaseName);
        $this->fileExtension = trim($this->fileExtension);
        $this->fileDateFormat = trim($this->fileDateFormat);

        $this->filePath = sprintf(
            '%s/%s_%s.%s',
            $this->fileBasePath,
            date($this->fileDateFormat),
            $this->fileBaseName,
            $this->fileExtension
        );

        $this->mkdir();
        $this->writeToFile('');
        $this->chmod();
    }

    /**
     * @throws IOExceptionInterface 
     */
    protected function log(string $message, string $verbosityKey = self::VERBOSITY_KEY_NORMAL): LogMessage {
        $messageObject = parent::log($message, $verbosityKey);
        $this->writeMessageToFile($messageObject);
        return $messageObject;
    }

    /**
     * @throws IOExceptionInterface 
     */
    protected function mkdir(): void {
        if ($this->isWritable) {
            try{
                $this->fileSystem->mkdir($this->fileBasePath);
            } catch (IOExceptionInterface $exception) {
                $this->isWritable = false;
                throw $exception;
            }
        }
    }

    /**
     * @throws IOExceptionInterface 
     */
    protected function chmod(): void {
        if ($this->isWritable) {
            try{
                $this->fileSystem->chmod($this->filePath, $this->chmod);
            } catch (IOExceptionInterface $exception) {
                $this->isWritable = false;
                throw $exception;
            }
        }
    }

    /**
     * @throws IOExceptionInterface 
     */
    protected function writeToFile(string $content): void {
        if ($this->isWritable) {
            try{
                $this->fileSystem->appendToFile($this->filePath, $content);
            } catch (IOExceptionInterface $exception) {
                $this->isWritable = false;
                throw $exception;
            }
        }
    }

    /**
     * @throws IOExceptionInterface 
     */
    protected function writeMessageToFile(LogMessage $messageObject): void {
        if ($this->getVerbosityValue($this->verbosityKey) >= $messageObject->getVerbosityValue()) {
            $this->writeToFile($this->getFormattedMessage($messageObject));
        }
    }

}

?>