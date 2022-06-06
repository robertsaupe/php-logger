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
 * @internal
 */
class LogMessageVerbosity {

    public function __construct(
        protected string $key = LogAbstract::VERBOSITY_KEY_NORMAL,
        protected int $value = LogAbstract::VERBOSITY_VALUE_NORMAL
    ) {
        
    }

    public function setKey(string $key): void {
        $this->key = $key;
    }

    public function setValue(int $value): void {
        $this->value = $value;
    }

    public function getKey(): string {
        return $this->key;
    }

    public function getValue(): int {
        return $this->value;
    }

    /**
     * @return mixed[] LogMessageVerbosity as Array
     */
    public function getArray(): array {
        return [
            'key' => $this->key,
            'value' => $this->value,
            $this->key => $this->value
        ];
    }

}

?>