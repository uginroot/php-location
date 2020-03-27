# Php location type

## Install
```bash
composer require uginroot/php-location:^1.1
```

## Example
```php
use Uginroot\PhpLocation\Location;

$location = new Location(1, 1);
$location
    ->move(1, 1) // Location(2, 2)
    ->moveLatitude(1) // Location(3, 2)
    ->moveLongitude(1) // Location(3, 3)
    ->distance(new Location(4, 4)) // float(1.4142135623731)
;

$location
    ->setLatitude(55.7539) // Location(55.7539, 1)
    ->setLongitude(37.6208) // Location(55.7539, 37.6208)
    ->distanceEarth(new Location(59.9398, 30.3146)) // 634568.9802775994 ~ 635km
;

Location::createFromString('55.7539', '37.6208'); // Location(55.7539, 37.6208)
```