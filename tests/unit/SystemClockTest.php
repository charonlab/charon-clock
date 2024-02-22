<?php

/*
 * This file is part of the charonlab/charon-clock.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Tests\Unit;

use Charon\Clock\Clock;
use Charon\Clock\ClockException;
use Charon\Clock\SystemClock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

#[Group('unit')]
#[CoversClass(SystemClock::class)]
class SystemClockTest extends TestCase
{
    public function testInstanceOfClockInterface(): void {
        $clock = new SystemClock();

        self::assertInstanceOf(Clock::class, $clock);
        self::assertInstanceOf(ClockInterface::class, $clock);
    }

    public function testSleep(): void {
        $clock = new SystemClock();

        $before = \microtime(true);
        $clock->sleep(1.25);
        $after = (float) $clock->now()->format('U.u');

        self::assertGreaterThanOrEqual($before + 1.25, $after);
    }

    public function testWithTimeZone(): void {
        $clock = new SystemClock();
        $newClock = $clock->withTimeZone(new \DateTimeZone('Europe/Warsaw'));

        self::assertNotSame($newClock, $clock);
        self::assertSame('Europe/Warsaw', $newClock->now()->getTimezone()->getName());
    }

    public function testWithTimeZoneUnknownException(): void {
        self::expectException(ClockException::class);
        new SystemClock('zaqw');
    }

    public function testNow(): void {
        $clock = new SystemClock();

        $before = new \DateTimeImmutable();
        \usleep(10);
        $now = $clock->now();
        \usleep(10);
        $after = new \DateTimeImmutable();

        self::assertSame($now->getTimezone()->getName(), Clock::DEFAULT_TIMEZONE);
        self::assertGreaterThan($before, $now);
        self::assertLessThan($after, $now);
    }
}
