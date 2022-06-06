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

use InvalidArgumentException;

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

}

?>