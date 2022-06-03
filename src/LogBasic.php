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
use function is_object;
use InvalidArgumentException;
use UnexpectedValueException;

class LogBasic extends LogAbstract {

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected string $verbosityKey = self::VERBOSITY_KEY_NORMAL,
        protected string $dateFormat = self::DEFAULT_DATE_FORMAT,
        protected string $messageFormat = self::DEFAULT_MESSAGE_FORMAT
        ) {
        if (!self::isVerbosityKeyValid($this->verbosityKey)) {
            throw new InvalidArgumentException('verbosityKey is invalid: ' . $this->verbosityKey);
        }
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

    public function verbose(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_VERBOSE);
    }

    public function veryverbose(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_VERYVERBOSE);
    }

    public function debug(string $message): LogMessage {
        return $this->log($message, self::VERBOSITY_KEY_DEBUG);
    }

    public function getMessages(): array {
        return $this->messages;
    }

    /**
     * @throws UnexpectedValueException
     */
    public function getMessagesByVerbosityKey(string $verbosityKey): array {
        $messages = [];
        foreach ($this->messages as $message) {
            if (is_object($message) && $message instanceof LogMessage) {
                if (self::getVerbosityValue($verbosityKey) >= $message->getVerbosityValue()) {
                    $messages[] = $message;
                }
            } else {
                throw new UnexpectedValueException('Message is not type of LoggerMessage: ' . print_r($message, true));
            }
        }
        return $messages;
    }

    /**
     * @throws UnexpectedValueException 
     */
    public function getMessagesByVerbosityValue(int $verbosityValue): array {
        return $this->getMessagesByVerbosityKey(self::getVerbosityKey($verbosityValue));
    }

    /**
     * @throws UnexpectedValueException 
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
            if (is_object($message) && $message instanceof LogMessage) {
                $messages .= $this->getFormattedMessage($message, $isHTML);
            } else {
                throw new UnexpectedValueException('Message is not type of LoggerMessage: ' . print_r($message, true));
            }
        }
        return $messages;
    }

    /**
     * @throws UnexpectedValueException 
     */
    public function getFormattedMessagesByVerbosityKey(string $verbosityKey, bool $isHTML = false): string {
        $messages = '';
        foreach ($this->messages as $message) {
            if (is_object($message) && $message instanceof LogMessage) {
                if (self::getVerbosityValue($verbosityKey) >= $message->getVerbosityValue()) {
                    $messages .= $this->getFormattedMessage($message, $isHTML);
                }
            } else {
                throw new UnexpectedValueException('Message is not type of LoggerMessage: ' . print_r($message, true));
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