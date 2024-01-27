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

final class SystemClock implements Clock
{
    private \DateTimeZone $timezone;

    /**
     * @throws \Charon\Clock\ClockException
     */
    public function __construct(\DateTimeZone|string $timezone = null) {
        $this->timezone = \is_string($timezone ??= self::DEFAULT_TIMEZONE)
            ? $this->withTimeZone($timezone)->timezone
            : $timezone;
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
                throw new ClockException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $self = clone $this;
        $self->timezone = $timezone;

        return $self;
    }

    /**
     * @inheritDoc
     */
    public function sleep(float|int $seconds): void
    {
        if (0 < $s = (int) $seconds) {
            \sleep($s);
        }

        if (0 < $us = $seconds - $s) {
            \usleep((int)($us * 1E6));
        }
    }

    /**
     * @inheritDoc
     */
    public function now(): \DateTimeImmutable
    {
        try {
            return new \DateTimeImmutable('now', $this->timezone);
        } catch (\Exception $exception) {
            throw new ClockException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
