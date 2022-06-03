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

/**
 * create a logger
 */
abstract class LogAbstract {

    public const VERBOSITY_KEY_QUIT = 'quit';
    public const VERBOSITY_KEY_ERROR = 'error';
    public const VERBOSITY_KEY_WARNING = 'warning';
    public const VERBOSITY_KEY_INFO = 'info';
    public const VERBOSITY_KEY_NORMAL = 'normal';
    public const VERBOSITY_KEY_WRITE = 'normal';
    public const VERBOSITY_KEY_VERBOSE = 'verbose';
    public const VERBOSITY_KEY_VERYVERBOSE = 'veryverbose';
    public const VERBOSITY_KEY_VERYVERYVERBOSE = 'debug';
    public const VERBOSITY_KEY_DEBUG = 'debug';

    public const VERBOSITY_VALUE_QUIT = 0;
    public const VERBOSITY_VALUE_ERROR = 8;
    public const VERBOSITY_VALUE_WARNING = 16;
    public const VERBOSITY_VALUE_INFO = 32;
    public const VERBOSITY_VALUE_NORMAL = 64;
    public const VERBOSITY_VALUE_WRITE = 64;
    public const VERBOSITY_VALUE_VERBOSE = 128;
    public const VERBOSITY_VALUE_VERYVERBOSE = 256;
    public const VERBOSITY_VALUE_VERYVERYVERBOSE = 512;
    public const VERBOSITY_VALUE_DEBUG = 512;

    public const DEFAULT_DATE_FORMAT = 'c';

    public const DEFAULT_MESSAGE_FORMAT = "(%s)\t[%s]: \t%s\n";

    protected string $verbosityKey;

    protected string $dateFormat;
    protected string $messageFormat;

    protected array $messages = [];

    public static function getVerbosityKey(int $verbosityValue): string {
        return match($verbosityValue) {
            self::VERBOSITY_VALUE_QUIT => self::VERBOSITY_KEY_QUIT,
            self::VERBOSITY_VALUE_ERROR => self::VERBOSITY_KEY_ERROR,
            self::VERBOSITY_VALUE_WARNING => self::VERBOSITY_KEY_WARNING,
            self::VERBOSITY_VALUE_INFO => self::VERBOSITY_KEY_INFO,
            self::VERBOSITY_VALUE_NORMAL,
            self::VERBOSITY_VALUE_WRITE => self::VERBOSITY_KEY_NORMAL,
            self::VERBOSITY_VALUE_VERBOSE => self::VERBOSITY_KEY_VERBOSE,
            self::VERBOSITY_VALUE_VERYVERBOSE => self::VERBOSITY_KEY_VERYVERBOSE,
            self::VERBOSITY_VALUE_DEBUG,
            self::VERBOSITY_VALUE_VERYVERYVERBOSE => self::VERBOSITY_KEY_DEBUG,
            default => self::VERBOSITY_KEY_NORMAL
        };
    }

    public static function getVerbosityValue(string $verbosityKey): int {
        return match($verbosityKey) {
            self::VERBOSITY_KEY_QUIT => self::VERBOSITY_VALUE_QUIT,
            self::VERBOSITY_KEY_ERROR => self::VERBOSITY_VALUE_ERROR,
            self::VERBOSITY_KEY_WARNING => self::VERBOSITY_VALUE_WARNING,
            self::VERBOSITY_KEY_INFO => self::VERBOSITY_VALUE_INFO,
            self::VERBOSITY_KEY_NORMAL,
            self::VERBOSITY_KEY_WRITE => self::VERBOSITY_VALUE_NORMAL,
            self::VERBOSITY_KEY_VERBOSE => self::VERBOSITY_VALUE_VERBOSE,
            self::VERBOSITY_KEY_VERYVERBOSE => self::VERBOSITY_VALUE_VERYVERBOSE,
            self::VERBOSITY_KEY_DEBUG,
            self::VERBOSITY_KEY_VERYVERYVERBOSE => self::VERBOSITY_VALUE_DEBUG,
            default => self::VERBOSITY_VALUE_NORMAL
        };
    }

    public static function isVerbosityKeyValid(string $verbosityKey): bool {
        return match($verbosityKey) {
            self::VERBOSITY_KEY_QUIT,
            self::VERBOSITY_KEY_ERROR,
            self::VERBOSITY_KEY_WARNING,
            self::VERBOSITY_KEY_INFO,
            self::VERBOSITY_KEY_NORMAL,
            self::VERBOSITY_KEY_WRITE,
            self::VERBOSITY_KEY_VERBOSE,
            self::VERBOSITY_KEY_VERYVERBOSE,
            self::VERBOSITY_KEY_DEBUG,
            self::VERBOSITY_KEY_VERYVERYVERBOSE => true,
            default => false
        };
    }

    public static function isVerbosityValueValid(int $verbosityValue): bool {
        return match($verbosityValue) {
            self::VERBOSITY_VALUE_QUIT,
            self::VERBOSITY_VALUE_ERROR,
            self::VERBOSITY_VALUE_WARNING,
            self::VERBOSITY_VALUE_INFO,
            self::VERBOSITY_VALUE_NORMAL,
            self::VERBOSITY_VALUE_WRITE,
            self::VERBOSITY_VALUE_VERBOSE,
            self::VERBOSITY_VALUE_VERYVERBOSE,
            self::VERBOSITY_VALUE_DEBUG,
            self::VERBOSITY_VALUE_VERYVERYVERBOSE => true,
            default => false
        };
    }

    abstract protected function log(string $message, string $verbosityKey): LogMessage;

    abstract public function error(string $message): LogMessage;

    abstract public function warning(string $message): LogMessage;

    abstract public function info(string $message): LogMessage;

    abstract public function normal(string $message): LogMessage;

    public function write(string $message): LogMessage {
        return $this->normal($message);
    }

    abstract public function verbose(string $message): LogMessage;

    abstract public function veryverbose(string $message): LogMessage;

    public function veryveryverbose(string $message): LogMessage {
        return $this->debug($message);
    }

    abstract public function debug(string $message): LogMessage;

    abstract public function getMessages(): array;

    abstract public function getMessagesByVerbosityKey(string $verbosityKey): array;

    abstract public function getMessagesByVerbosityValue(int $verbosityValue): array;

    abstract public function getFormattedMessage(LogMessage $message, bool $isHTML): string;

    abstract public function getFormattedMessages(bool $isHTML): string;

    abstract public function getFormattedMessagesByVerbosityKey(string $verbosityKey, bool $isHTML): string;

    abstract public function getFormattedMessagesByVerbosityValue(int $verbosityValue, bool $isHTML): string;

    abstract public function getFormattedMessagesByVerbosity(bool $isHTML): string;

}

?>