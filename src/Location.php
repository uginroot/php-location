<?php


namespace Uginroot\PhpLocation;


use Uginroot\PhpLocation\Exception\NotNumericValueException;

class Location
{
    public const POINT_FORMAT = 'POINT(%f %f)';

    public const EARTH_DIAMETER = 6371000 * 2;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @param string $latitude
     * @param string $longitude
     * @return static
     */
    public static function createFromString(string $latitude, string $longitude):self
    {
        if(!is_numeric($latitude) || !is_numeric($longitude)){
            throw new NotNumericValueException('Expected numeric $latitude and $longitude parameters');
        }
        $latitudeFloat = filter_var($latitude, FILTER_VALIDATE_FLOAT);
        $longitudeFloat = filter_var($longitude, FILTER_VALIDATE_FLOAT);
        return new static($latitudeFloat, $longitudeFloat);
    }

    /**
     * @param string $point
     * @return static
     */
    public static function createFromPoint(string $point):self
    {
        [$latitude, $longitude] = sscanf($point, static::POINT_FORMAT);

        if($latitude === null || $longitude === null){
            throw new NotPointValueException(sprintf('Unexpected point string. Expected format: "%s"', static::POINT_FORMAT));
        }

        return new static($latitude, $longitude);
    }

    /**
     * Location constructor.
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude():float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return $this
     */
    public function setLatitude(float $latitude):self
    {
        return new static($latitude, $this->getLongitude());
    }

    /**
     * @return float
     */
    public function getLongitude():float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude(float $longitude):self
    {
        return new static($this->getLatitude(), $longitude);
    }

    /**
     * @param float $distance
     * @return $this
     */
    public function moveLatitude(float $distance):self
    {
        return new static($this->getLatitude() + $distance, $this->getLongitude());
    }

    /**
     * @param float $distance
     * @return $this
     */
    public function moveLongitude(float $distance):self
    {
        return new static($this->getLatitude(), $this->getLongitude() + $distance);
    }

    /**
     * @param float $distanceLatitude
     * @param float $distanceLongitude
     * @return $this
     */
    public function move(float $distanceLatitude, float $distanceLongitude):self
    {
        return new static($this->getLatitude() + $distanceLatitude, $this->getLongitude() + $distanceLongitude);
    }

    /**
     * @return string
     */
    public function toPoint():string
    {
        return sprintf(static::POINT_FORMAT, $this->getLatitude(), $this->getLongitude());
    }

    public function distance(self $point):float
    {
        $latitudeDiff = $point->getLatitude() - $this->getLatitude();
        $longitudeDiff = $point->getLongitude() - $this->getLongitude();

        return sqrt($latitudeDiff * $latitudeDiff + $longitudeDiff * $longitudeDiff);
    }

    public function distanceEarth(self $point):float
    {
        $x1 = deg2rad($this->getLatitude());
        $x2 = deg2rad($point->getLatitude());
        $y1 = deg2rad($this->getLongitude());
        $y2 = deg2rad($point->getLongitude());

        $sin2X = sin(($x2 - $x1) / 2)**2;
        $sin2Y = sin(($y2 - $y1) / 2)**2;
        $cosX1 = cos($x1);
        $cosX2 = cos($x2);

        return static::EARTH_DIAMETER * asin(sqrt($sin2X + $cosX1 * $cosX2 * $sin2Y));
    }
}