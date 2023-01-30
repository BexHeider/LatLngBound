<?php

namespace BexHeider\LatLngBound;

use BexHeider\LatLng;

class LatLngBoundClass
{
    public $_sw; 
    public $_ne;

    public function __construct($corner1, $corner2)
    {
        $this->extend($corner1);
        $this->extend($corner2);
    }

    public function fromPoints($points){
        if(count($points) > 0){
            $minX = 0;
            $minY = 0;
            $maxX = 0;
            $maxY = 0;

            for ($i = 0; $i < count($points); $i++) {
                $x = deg2rad($points[$i]['longitude']);
                $y = deg2rad($points[$i]['latitude']);
        
                if ($minX == null || $minX > $x) {
                  $minX = $x;
                }
        
                if ($minY == null || $minY > $y) {
                  $minY = $y;
                }
        
                if ($maxX == null || $maxX < $x) {
                  $maxX = $x;
                }
        
                if ($maxY == null || $maxY < $y) {
                  $maxY = $y;
                }
            }
        }

        $this->_sw = new LatLng(rad2deg($minY), rad2deg($minX));
        $this->_ne = new LatLng(rad2deg($maxY), rad2deg($maxX));
    }

    /// Expands bounding box by [latlng] coordinate point. This method mutates
    /// the bounds object on which it is called.
    public function extend($latlng) {
        if($latlng == null) return;
        $this->_extend($latlng, $latlng);
    }

    /// Expands bounding box by other [bounds] object. If provided [bounds] object
    /// is smaller than current one, it is not shrunk. This method mutates
    /// the bounds object on which it is called.
    public function extendBounds($bounds) {
        $this->_extend($bounds->_sw, $bounds->_ne);
    }

    public function _extend($sw2, $ne2) {
        if ($this->_sw == null && $this->_ne == null) {
          $this->_sw = new LatLng($sw2->getLatitude(), $sw2->getLongitude());
          $this->_ne = new LatLng($ne2->getLatitude(), $ne2->getLongitude());
        } else {
          $this->_sw->setLatitude(min($sw2->getLatitude(), $this->_sw->getLongitude()));
          $this->_sw->setLongitude(min($sw2->getLongitude(), $this->_sw->getLongitude()));
          $this->_ne->setLatitude(max($ne2->getLatitude(), $this->_ne->getLatitude()));
          $this->_ne->setLongitude(max($this->_ne->getLongitude(), $this->_ne->getLongitude()));
        }
    }

    /// Obtain west edge of the bounds
    public function west(){
        return $this->southWest()->getLongitude();
    }

    /// Obtain south edge of the bounds
    public function south(){
        return $this->southWest()->getLatitude();
    }

    /// Obtain east edge of the bounds
    public function east(){
        return $this->northEast()->getLongitude();
    }

    /// Obtain north edge of the bounds
    public function north(){
        return $this->northEast()->getLatitude();
    }

    /// Obtain coordinates of southwest corner of the bounds
    public function southWest(){
        return $this->_sw;
    }

    /// Obtain coordinates of northeast corner of the bounds
    public function northEast(){
        return $this->_ne;
    }

    /// Obtain coordinates of northwest corner of the bounds
    public function northWest(){
        return new LatLng($this->north, $this->west);
    }

    /// Obtain coordinates of southeast corner of the bounds
    public function southEast(){
        return new LatLng($this->south, $this->east);
    }


    /// Obtain coordinates of the bounds center
    public function center(){

        /* https://stackoverflow.com/a/4656937
        http://www.movable-type.co.uk/scripts/latlong.html

        coord 1: southWest
        coord 2: northEast

        phi: lat
        lambda: lng
        */    

        $phi1 = deg2rad($this->southWest()->latitudeInRad());
        $lambda1 = deg2rad($this->southWest()->longitudeInRad());
        $phi2 = deg2rad($this->northEast()->latitudeInRad());
    
        $dLambda = deg2rad($this->northEast()->getLongitude() - $this->southWest()->getLongitude()); // delta lambda = lambda2-lambda1
    
        $bx = cos($phi2) * cos($dLambda);
        $by = cos($phi2) * sin($dLambda);
        $phi3 = atan2(sin($phi1) + sin($phi2), sqrt((cos($phi1) + $bx) * (cos($phi1) + $bx) + $by * $by));
        $lambda3 = $lambda1 + atan2($by, cos($phi1) + $bx);
    
        // phi3 and lambda3 are actually in radians and LatLng wants degrees
        return new LatLng(rad2deg($phi3), rad2deg($lambda3)); 
    }

    /// Checks whether bound object is valid
    public function isValid() {
        return $this->_sw != null && $this->_ne != null;
    }

    /// Checks whether [point] is inside bounds
    public function contains($point) {
        if(!$this->isValid()) return false;
        $sw2 = $point;
        $ne2 = $point;
        return $this->containsBounds(new LatLngBounds($sw2, $ne2));
    }

    /// Checks whether [bounds] is contained inside bounds
    public function containsBounds($bounds) {
        $sw2 = $bounds->_sw;
        $ne2 = $bounds->_ne;
        return ($sw2->getLatitude() >= $_sw->getLatitude()) &&
            ($ne2->getLatitude() <= $_ne->getLatitude()) &&
            ($sw2->getLongitude() >= $_sw->getLongitude()) &&
            ($ne2->getLongitude() <= $_ne->getLongitude());
    }


    /// Checks whether at least one edge of [bounds] is overlapping with some
    /// other edge of bounds
    public function isOverlapping($bounds) {
        if (!$this->isValid) return false;
        /* check if bounding box rectangle is outside the other, if it is then it's
        considered not overlapping
        */
        if ($_sw->getLatitude() > $bounds->_ne->getLatitude() ||
            $_ne->getLatitude() < $bounds->_sw->getLatitude() ||
            $_ne->getLongitude() < $bounds->_sw->getLongitude() ||
            $_sw->getLongitude() > $bounds->_ne->getLongitude()) {
            return false;
        }
        return true;
    }

    /// Expands bounds by decimal degrees unlike [extend] or [extendBounds]
    public function pad($bufferRatio) {
        $heightBuffer = ($_sw->getLatitude() - $_ne->getLatitude()).abs() * $bufferRatio;
        $widthBuffer = ($_sw->getLongitude() - $_ne->getLongitude()).abs() * $bufferRatio;

        $_sw = new LatLng($_sw->getLatitude() - $heightBuffer, $_sw->getLongitude() - $widthBuffer);
        $_ne = new LatLng($_ne->getLatitude() + $heightBuffer, $_ne->getLongitude() + $widthBuffer);
    }

    /*
    int get hashCode => _sw.hashCode + _ne.hashCode;
    */
}
