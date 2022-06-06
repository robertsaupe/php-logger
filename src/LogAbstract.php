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
use function htmlentities;
use UnexpectedValueException;

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

    /**
     * @var LogMessage[]
     */
    protected array $messages = [];

    public static function getVerbosityKey(int $verbosityValue): string {
        return match($verbosityValue) {
            self::VERBOSITY_VALUE_QUIT => self::VERBOSITY_KEY_QUIT,
            self::VERBOSITY_VALUE_ERROR => self::VERBOSITY_KEY_ERROR,
            self::VERBOSITY_VALUE_WARNING => self::VERBOSITY_KEY_WARNING,
            self::VERBOSITY_VALUE_INFO => self::VERBOSITY_KEY_INFO,
            self::VERBOSITY_VALUE_NORMAL => self::VERBOSITY_KEY_NORMAL,
            self::VERBOSITY_VALUE_VERBOSE => self::VERBOSITY_KEY_VERBOSE,
            self::VERBOSITY_VALUE_VERYVERBOSE => self::VERBOSITY_KEY_VERYVERBOSE,
            self::VERBOSITY_VALUE_DEBUG => self::VERBOSITY_KEY_DEBUG,
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
            self::VERBOSITY_VALUE_VERBOSE,
            self::VERBOSITY_VALUE_VERYVERBOSE,
            self::VERBOSITY_VALUE_DEBUG => true,
            default => false
        };
    }

    protected function log(string $message, string $verbosityKey = self::VERBOSITY_KEY_NORMAL): LogMessage {
        $messageObject = new LogMessage($message, date($this->dateFormat), $verbosityKey);
        $this->messages[] = $messageObject;
        return $messageObject;
    }

    public function error(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_ERROR);
    }

    public function warning(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_WARNING);
    }

    public function info(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_INFO);
    }

    public function normal(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_NORMAL);
    }

    public function write(string $message): LogMessage {
        return $this->normal($message);
    }

    public function verbose(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_VERBOSE);
    }

    public function veryverbose(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_VERYVERBOSE);
    }

    public function veryveryverbose(string $message): LogMessage {
        return $this->debug($message);
    }

    public function debug(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_DEBUG);
    }

    /**
     * @return LogMessage[]
     */
    public function getMessages(): array {
        return $this->messages;
    }

    /**
     * @return LogMessage[]
     * @throws UnexpectedValueException
     */
    public function getMessagesByVerbosityKey(string $verbosityKey): array {
        $messages = [];
        foreach ($this->messages as $message) {
            if (self::getVerbosityValue($verbosityKey) >= $message->getVerbosityValue()) {
                $messages[] = $message;
            }
        }
        return $messages;
    }

    /**
     * @return LogMessage[]
     * @throws UnexpectedValueException 
     */
    public function getMessagesByVerbosityValue(int $verbosityValue): array {
        return $this->getMessagesByVerbosityKey(self::getVerbosityKey($verbosityValue));
    }

    /**
     * @return LogMessage[]
     */
    public function getMessagesByVerbosity(): array {
        return $this->getMessagesByVerbosityKey($this->verbosityKey);
    }

    public function getFormattedMessage(LogMessage $messageObject, bool $isHTML = false): string {
        $formattedMessage = sprintf(
            $this->messageFormat,
            $messageObject->getDate(),
            ucfirst($messageObject->getVerbosityKey()),
            ($isHTML) ? htmlentities($messageObject->getMessage()) : $messageObject->getMessage()
        );
        if ($isHTML) {
            $fontColor = '#5f5f5f';
            $backgroundColor = 'unset';
            switch ($messageObject->getVerbosityKey()) {
                case self::VERBOSITY_KEY_ERROR:
                    $fontColor =  '#ff0000';
                    break;
                case self::VERBOSITY_KEY_WARNING:
                    $fontColor =  '#ff8600';
                    break;
                case self::VERBOSITY_KEY_INFO:
                    $fontColor =  '#008000';
                    break;
                case self::VERBOSITY_KEY_NORMAL:
                case self::VERBOSITY_KEY_WRITE:
                default:
                    $fontColor =  '#000000';
                    break;
                case self::VERBOSITY_KEY_VERBOSE:
                    $fontColor =  '#000000';
                    $backgroundColor = '#ffd99a';
                    break;
                case self::VERBOSITY_KEY_VERYVERBOSE:
                    $fontColor =  '#4e4e4e';
                    $backgroundColor = '#ffd99a';
                    break;
                case self::VERBOSITY_KEY_DEBUG:
                case self::VERBOSITY_KEY_VERYVERYVERBOSE:
                    $fontColor =  '#767676';
                    $backgroundColor = '#ffd99a';
                    break;
            }
            return sprintf(
                '<pre style="color:%s;background-color:%s;margin:unset;">%s</pre>',
                $fontColor,
                $backgroundColor,
                $formattedMessage
            );
        } else {
            return $formattedMessage;
        }
    }

    /**
     * @throws UnexpectedValueException 
     */
    public function getFormattedMessages(bool $isHTML = false): string {
        $messages = '';
        foreach ($this->messages as $message) {
            $messages .= $this->getFormattedMessage($message, $isHTML);
        }
        return $messages;
    }

    /**
     * @throws UnexpectedValueException 
     */
    public function getFormattedMessagesByVerbosityKey(string $verbosityKey, bool $isHTML = false): string {
        $messages = '';
        foreach ($this->messages as $message) {
            if (self::getVerbosityValue($verbosityKey) >= $message->getVerbosityValue()) {
                $messages .= $this->getFormattedMessage($message, $isHTML);
            }
        }
        return $messages;
    }

    /**
     * @throws UnexpectedValueException 
     */
    public function getFormattedMessagesByVerbosityValue(int $verbosityValue, bool $isHTML = false): string {
        return $this->getFormattedMessagesByVerbosityKey(self::getVerbosityKey($verbosityValue), $isHTML);
    }

    /**
     * @throws UnexpectedValueException 
     */
    public function getFormattedMessagesByVerbosity(bool $isHTML = false): string {
        return $this->getFormattedMessagesByVerbosityKey($this->verbosityKey, $isHTML);
    }

}

?>