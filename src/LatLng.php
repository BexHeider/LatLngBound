<?php

namespace BexHeider\LatLng;

class LatLng {

    public $_latitude;
    public $_longitude;

    public function __construct($latitude, $longitude)
    {
        $this->_latitude  = filter_var(
            $latitude,
            FILTER_SANITIZE_NUMBER_FLOAT
        );
        
        $this->_longitude = filter_var(
            $longitude,
            FILTER_SANITIZE_NUMBER_FLOAT
        );

        if ($this->_latitude < -90 || $this->_latitude > 90) {
          throw new Exception('Latitude must be between -90 and 90 degrees');
        } else if ($this->_longitude < -180 || $this->_longitude > 180) {
          throw new Exception('Longitude must be between -180 and 180 degrees');
        }
          
    }

    public function setLatitude($value) {
      if ($value < -90 || $value > 90) {
        throw new Exception('Latitude must be between -90 and 90 degrees');
      }
      $this->_latitude = $value;
    }

    public function getLatitude() {
      return $this->_latitude;
    }

    public function setLongitude($value){
      if ($value < -180 || $value > 180) {
        throw new Exception('Longitude must be between -180 and 180 degrees');
      }
      $this->_longitude = $longitude;
    }

    public function getLongitude(){
      return $this->_longitude;
    }

    public function latitudeInRad(){
      return deg2rad($this->_latitude);
    }

    public function longitudeInRad(){
      return deg2rad($this->_longitude);
    }

    public function fromJson($json){
      $this->_latitude = $json['coordinates'][0];
      $this->_longitude = $json['coordinates'][1];
    }

    public function toJson(){
      return (object) [ 'coordinates' => [$this->_latitude, $this->_longitude] ];
    }

    public function toString(){
      return 'LatLng(latitude:' . number_format($this->_latitude, '0.0#####') . ')},longitude:' . number_format($this->_longitude, '0.0#####') . ')})';
    }

    /*public function toSexagesimal(){
      $latDirection = $this->_latitude >= 0 ? 'N' : 'S';
      $lonDirection = $this->_longitude >= 0 ? 'E' : 'W';

      return dec2hex($this->_latitude) . ' ' . $latDirection . ' ' . dec2hex($this->_longitude) . ' ' . $lonDirection;
    }*/

    public function round($decimals = 6){
      return LatLng(
        $this-_round($this->latitude, $decimals),
        $this->_round($this->_longitude, $decimals)
      );
    }


    private function _round($value, $decimals = 6){
      return (value * round(pow(10, decimals))) / pow(10, decimals);
    }

    public function hasCode(){
      return hash($this->_latitude + $this->_longitude);
    }
    


}
