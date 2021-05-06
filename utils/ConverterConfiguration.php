<?php

namespace Bitapp\ZoneLocatorUtility;

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
        $this->projection = $data['projection'];
        $this->zoneCode = $data['zoneCode'];
        $this->zoneName = $data['zoneName'];
        $this->agency = $data['agency'];
        $this->basin = $data['basin'];
    }

}