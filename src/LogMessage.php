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

use function date;

/**
 * @internal
 */
class LogMessage {

    protected LogMessageVerbosity $verbosity;

    protected int $verbosityValue;

    public function __construct(
        protected string $message = '',
        protected ?string $date = null,
        protected string $verbosityKey = LogAbstract::VERBOSITY_KEY_NORMAL
    ) {
        $this->setDate($this->date);
        $this->setVerbosityByKey($this->verbosityKey);
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function setDate(?string $date): void {
        if ($date === null || $date === '') {
            $this->date = date(LogAbstract::DEFAULT_DATE_FORMAT);
        } else {
            $this->date = $date;
        }
    }

    protected function setVerbosityByKey(string $verbosityKey): void {
        $this->verbosityKey = $verbosityKey;
        $this->verbosityValue = LogAbstract::getVerbosityValue($verbosityKey);
        $this->verbosity = new LogMessageVerbosity($this->verbosityKey, $this->verbosityValue);
    }

    public function setVerbosityByValue(int $verbosityValue): void {
        $this->verbosityKey = LogAbstract::getVerbosityKey($verbosityValue);
        $this->verbosityValue = $verbosityValue;
        $this->verbosity = new LogMessageVerbosity($this->verbosityKey, $this->verbosityValue);
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getVerbosity(): LogMessageVerbosity {
        return $this->verbosity;
    }

    public function getVerbosityKey(): string {
        return $this->verbosityKey;
    }

    public function getVerbosityValue(): int {
        return $this->verbosityValue;
    }

    /**
     * @return mixed[] LogMessage as Array
     */
    public function getArray(): array {
        return [
            'message' => $this->message,
            'date' => $this->date,
            'verbosity' => $this->verbosity->getArray(),
            'verbosityKey' => $this->verbosityKey,
            'verbosityValue' => $this->verbosityValue
        ];
    }

}

?>