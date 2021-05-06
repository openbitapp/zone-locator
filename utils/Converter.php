<?php

namespace Bitapp\ZoneLocatorUtility;

use Bitapp\ZoneLocator\Zone;
use proj4php\Point;
use proj4php\Proj;
use proj4php\Proj4php;
use Shapefile\ShapefileReader;

class Converter
{
    /**
     * Load the zones from shapefiles
     *
     * @param \Bitapp\ZoneLocator\Utility\ConverterConfiguration|\Bitapp\ZoneLocator\Utility\ConverterConfiguration[] $configs
     */
    public static function load($configs) : array
    {
        if (!is_array($configs)) {
            $configs = [$configs];
        }

        $zones = [];

        $proj4 = new Proj4php();

        foreach ($configs as $config) {
            $shapefile = new ShapefileReader($config->path);
    
            $projSrc = new Proj($config->projection, $proj4);
            $projWGS84 = new Proj('EPSG:4326', $proj4);

            while ($geometry = $shapefile->fetchRecord()) {
                if ($geometry->isDeleted()) {
                    continue;
                }
        
                $points = [];
            
                foreach ($geometry->getArray()['points'] as $point) {
                    $pointSrc = new Point($point['x'], $point['y'], $projSrc);
                    $pointDest = $proj4->transform($projWGS84, $pointSrc);
            
                    $point = $pointDest->toArray();
                }
        
                $zones[] = new Zone([
                    'code' => $geometry->getData($config->zoneCode),
                    'name' => $geometry->getData($config->zoneName),
                    'agency' => $config->agency,
                    'basin' => $config->basin,
                    'points' => $points,
                ]);
            }
        }

        return $zones;
    }

    public static function save(array $zones, string $path)
    {
        $zonesArray = [];

        foreach ($zones as $zone) {
            $zonesArray[] = $zone->toArray();
        }

        return file_put_contents($path, json_encode($zonesArray));
    }
}