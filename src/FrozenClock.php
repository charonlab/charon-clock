<?php

/*
 * This file is part of the charonlab/charon-routing.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Clock;

use DateTimeImmutable;

class FrozenClock implements Clock
{
    private \DateTimeImmutable $datetime;

    /**
     * @param \DateTimeImmutable|non-empty-string $datetime
     * @param \DateTimeZone|non-empty-string $timezone
     *
     * @throws \Charon\Clock\ClockException
     */
    public function __construct(
        \DateTimeImmutable|string $datetime = 'now',
        \DateTimeZone|string $timezone = Clock::DEFAULT_TIMEZONE
    ) {
        if (\is_string($timezone)) {
            try {
                $timezone = new \DateTimeZone($timezone);
            } catch (\Exception $e) {
                throw new ClockException($e->getMessage(), (int) $e->getCode(), $e);
            }
        }

        if (\is_string($datetime)) {
            try {
                $datetime = new \DateTimeImmutable($datetime);
            } catch (\Exception $e) {
                throw new ClockException($e->getMessage(), (int) $e->getCode(), $e);
            }
        }

        $this->datetime = $datetime->setTimezone($timezone);
    }

    /**
     * @inheritDoc
     */
    public function now(): DateTimeImmutable
    {
        return $this->datetime;
    }

    /**
     * @inheritDoc
     */
    public function withTimeZone(\DateTimeZone|string $timezone): static
    {
        if (\is_string($timezone)) {
            try {
                $timezone = new \DateTimeZone($timezone);
            } catch (\Exception $e) {
                throw new ClockException($e->getMessage(), (int) $e->getCode(), $e);
            }
        }

        $self = clone $this;
        $self->datetime = $self->datetime->setTimezone($timezone);

        return $self;
    }

    /**
     * @inheritDoc
     */
    public function sleep(float|int $seconds): void
    {
        $wholeSeconds = \floor($seconds);
        $microSeconds = \round(($seconds - $wholeSeconds) * 1E6);

        if ($seconds > 0) {
            if ($dt = $this->datetime->modify("$wholeSeconds second")) {
                $this->datetime = $dt;
            }
        }

        if ($microSeconds > 0) {
            if($dt = $this->datetime->modify("$microSeconds microsecond")) {
                $this->datetime = $dt;
            }
        }
    }
}
