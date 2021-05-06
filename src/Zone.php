<?php

namespace Bitapp\ZoneLocator;

use geoPHP;

class Zone
{
    public $code;
    public $name;
    public $agency;
    public $basin;
    public $points;
    protected $polygon;

    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->name = $data['name'];
        $this->agency = $data['agency'] ?? null;
        $this->basin = $data['basin'] ?? null;

        $this->points = $data['points'];

        $pointsString = '';

        foreach ($this->points as $point) {
            $pointsString .= "{$point[0]} {$point[1]},";
        }

        $pointsString .= "{$this->points[0][0]} {$this->points[0][1]}";

        $this->polygon = geoPHP::load("POLYGON(({$pointsString}))", 'wkt');
    }

    public function contains(Point $point) : bool
    {
        return $this->polygon->pointInPolygon($point);
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'agency' => $this->agency,
            'basin' => $this->basin,
            'points' => $this->points,
        ];
    }

    public static function locatePoint(array $zones, Point $point) : ?Zone
    {
        foreach ($zones as $zone) {
            if ($zone->contains($point)) {
                return $zone;
            }
        }

        return null;
    }

    public function loadFromFile(string $path) : array
    {
        $zonesArray = json_decode(file_get_contents($path), true);

        $zones = [];

        foreach ($zonesArray as $zone) {
            $zones[] = new Zone($zone);
        }

        return $zones;
    }
}