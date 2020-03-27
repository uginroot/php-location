<?php


namespace Uginroot\PhpLocation\Test;


use PHPUnit\Framework\TestCase;
use Uginroot\PhpLocation\Exception\NotNumericValueException;
use Uginroot\PhpLocation\Exception\NotPointValueException;
use Uginroot\PhpLocation\Location;

class LocationTest extends TestCase
{
    public function testSet():void
    {
        $location = new Location(1, 1);
        $location->setLatitude(2);
        $location->setLongitude(2);
        $this->assertSame(1.0, $location->getLatitude());
        $this->assertSame(1.0, $location->getLongitude());
    }

    public function testCreateFromString():void
    {
        $location = Location::createFromString('1', '1');
        $this->assertSame(1.0, $location->getLatitude());
        $this->assertSame(1.0, $location->getLongitude());
    }

    public function testCreateFromStringException():void
    {
        $this->expectException(NotNumericValueException::class);
        Location::createFromString('', '');
    }

    public function testCreateFromPoint():void
    {
        $location = Location::createFromPoint('POINT(1 1)');
        $this->assertSame(1.0, $location->getLatitude());
        $this->assertSame(1.0, $location->getLongitude());
    }

    public function testCreateFromPointException():void
    {
        $this->expectException(NotPointValueException::class);
        Location::createFromPoint('');
    }

    public function testDistance():void
    {
        $location = new Location(0, 0);
        $locationA = new Location(1, 0);
        $locationB = new Location(1, 1);

        $this->assertSame(1.0, $location->distance($locationA));
        $this->assertSame(sqrt(2), $location->distance($locationB));
    }

    public function testDistanceEarth():void
    {
        $location1 = new Location(55.7539, 37.6208);
        $location2 = new Location(59.9398, 30.3146);

        $this->assertSame(634568, (int)$location1->distanceEarth($location2));
    }

    public function testToPoint():void
    {
        $location = new Location(1.1, 1.1);
        $this->assertSame('POINT(1.100000 1.100000)', $location->toPoint());
    }

    public function testMove():void
    {
        $location1 = new Location(1, 1);
        $location2 = $location1->moveLatitude(1);
        $location3 = $location2->moveLongitude(1);
        $location4 = $location1->move(1, 1);

        $this->assertSame(1.0, $location1->getLatitude());
        $this->assertSame(1.0, $location1->getLongitude());

        $this->assertSame(2.0, $location2->getLatitude());
        $this->assertSame(1.0, $location2->getLongitude());

        $this->assertSame(2.0, $location3->getLatitude());
        $this->assertSame(2.0, $location3->getLongitude());

        $this->assertSame(2.0, $location4->getLatitude());
        $this->assertSame(2.0, $location4->getLongitude());
    }
}