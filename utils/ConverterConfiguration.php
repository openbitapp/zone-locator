<?php

namespace Bitapp\ZoneLocator\Utility;

class ConverterConfiguration
{
    public $path;
    public $projection;
    public $zoneCode;
    public $zoneName;
    public $agency;
    public $basin;

    public function __construct(array $data)
    {
        $this->path = $data['path'];
        $this->proj = $data['proj'];
        $this->zoneCode = $data['zoneCode'];
        $this->zoneName = $data['zoneName'];
        $this->agency = $data['agency'];
        $this->basin = $data['basin'];
    }

}