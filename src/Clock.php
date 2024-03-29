<?php

/*
 * This file is part of the charonlab/charon-clock.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Clock;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    public const DEFAULT_TIMEZONE = 'UTC';

    /**
     * @throws \Charon\Clock\ClockException
     */
    public function now(): DateTimeImmutable;

    /**
     * Return an instance with the specified TimeZone.
     *
     * @param \DateTimeZone|non-empty-string $timezone TimeZone
     * @return $this
     *
     * @throws \Charon\Clock\ClockException
     */
    public function withTimeZone(\DateTimeZone|string $timezone): static;

    /**
     * Delays the program execution for the given number of seconds.
     *
     * @param int|float $seconds Halt time in seconds or microseconds.
     * @return void
     */
    public function sleep(int|float $seconds): void;
}
