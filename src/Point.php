<?php

namespace Bitapp\ZoneLocator;

use geoPHP;
use Point as GeoPoint;

class Point
{
    public $latitude;
    public $longitude;
    protected $point;

    public function __construct(array $data)
    {
        $this->latitude = $data['latitude'] ?? $data[1];
        $this->longitude = $data['longitude'] ?? $data[0];
        $this->point = geoPHP::load("POINT({$this->longitude} {$this->latitude})", 'wkt');
    }

    public function getGeoPoint() : GeoPoint
    {   
        return $this->point;
    }

    public function isInside(Zone $zone) : bool
    {
        return $zone->contains($this);
    }

    public function locate(array $zones) : ?Zone
    {
        return Zone::locatePoint($zones, $this);
    }
}