# Usage

```\Charon\Clock\Clock``` provides some implementations for different uses cases:
- ```\Charon\Clock\FrozenClock``` provides a frozen clock.
- ```\Charon\Clock\SystemClock``` is a basic implementation of PSR-20 standard.

## Example usage

```php
$clock = new \Charon\Clock\SystemClock();

// Gets a current time
$clock->now();

// Sets a custom timezone
$clock->withZone(new \DateTimeZone('Europe\Warsaw'))

// Sleeps clock for 5 seconds
$clock->sleep(5);
```
