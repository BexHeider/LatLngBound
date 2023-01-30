<?php

use BexHeider\LatLngBound;

test('can find bound from a list of lat and lng', function () {

    $latlng = array(
        array(
            "latitude" => "6.1654001",
            "longitude" => "-75.5790331",
        ),
        array(
            "latitude" => "6.1632867",
            "longitude" => "-75.5822003",
        ),
        array(
            "latitude" => "6.1615857",
            "longitude" => "-75.58343013",
        ),
        array(
            "latitude" => "6.1598382",
            "longitude" => "-75.5828625",
        ),
        array(
            "latitude" => "6.162661666666666",
            "longitude" => "-75.58254833333334",
        ),
        array(
            "latitude" => "6.158924822023581",
            "longitude" => "-75.58277507365956",
        ),
        array(
            "latitude" => "6.16294015198946",
            "longitude" => "-75.58146734721959",
        ),
        array(
            "latitude" => "6.165298839818693",
            "longitude" => "-75.58550008765606",
        ),
        array(
            "latitude" => "6.158552097437861",
            "longitude" => "-75.5822389367566",
        ),
        array(
            "latitude" => "6.166839497163892",
            "longitude" => "-75.58289872482419",
        ),
        array(
            "latitude" => "6.16473716",
            "longitude" => "-75.58426622",
        ),
        array(
            "latitude" => "6.16358757019025",
            "longitude" => "-75.58314800262231",
        ),
        array(
            "latitude" => "6.1680492",
            "longitude" => "-75.58381361",
        ),
        array(
            "latitude" => "6.164467334747135",
            "longitude" => "-75.58368444442529",
        ),
        array(
            "latitude" => "6.159403831078167",
            "longitude" => "-75.58251804443547",
        ),
        array(
            "latitude" => "6.16592628",
            "longitude" => "-75.58604817",
        ),
        array(
            "latitude" => "6.165701150893986",
            "longitude" => "-75.58428525924462",
        ),
        array(
            "latitude" => "6.1670448537973375",
            "longitude" => "-75.58186196729129",
        ),
        array(
            "latitude" => "6.159693002700627",
            "longitude" => "-75.58168888091821",
        ),
        array(
            "latitude" => "6.155514",
            "longitude" => "-75.5722456",
        ),
        array(
            "latitude" => "6.165465116500675",
            "longitude" => "-75.58248281478662",
        ),
        array(
            "latitude" => "6.164896488189518",
            "longitude" => "-75.58510065078515",
        ),
        array(
            "latitude" => "6.15957741",
            "longitude" => "-75.58288141",
        ),
        array(
            "latitude" => "6.16391212",
            "longitude" => "-75.58364367",
        ),
        array(
            "latitude" => "6.16271202",
            "longitude" => "-75.58221478",
        ),
        array(
            "latitude" => "6.15951481",
            "longitude" => "-75.58256114",
        ),
        array(
            "latitude" => "6.158443847671151",
            "longitude" => "-75.58281959965825",
        ),
        array(
            "latitude" => "6.165765523910343",
            "longitude" => "-75.58417797088403",
        ),
        array(
            "latitude" => "6.164553165435612",
            "longitude" => "-75.58250427245873",
        ),
        array(
            "latitude" => "6.166150000000001",
            "longitude" => "-75.58187",
        ),
        array(
            "latitude" => "6.168383359908878",
            "longitude" => "-75.58419942855615",
        ),
        array(
            "latitude" => "6.163995265960514",
            "longitude" => "-75.58274030685205",
        ),
        array(
            "latitude" => "6.165431044064462",
            "longitude" => "-75.5796863604337",
        ),
        array(
            "latitude" => "6.16565149",
            "longitude" => "-75.58585554",
        ),
        array(
            "latitude" => "6.1672698",
            "longitude" => "-75.5821857",
        ),
        array(
            "latitude" => "6.167400596379214",
            "longitude" => "-75.58236992475439",
        ),
        array(
            "latitude" => "6.16089",
            "longitude" => "-75.58170166666667",
        ),
    );

    if(count($latlng) > 0){
        $x0 = null; $x1 = null; $y0 = null; $y1 = null;

        for ($i = 0; $i < count($latlng); $i++) {
            if(gettype($latlng[$i]['latitude']) == 'string'){
                $latlng[$i]['latitude'] = floatval($latlng[$i]['latitude']);
            }

            if(gettype($latlng[$i]['longitude'])){
                $latlng[$i]['longitude'] = floatval($latlng[$i]['longitude']);
            }

            if ($x0 == null) {
                $x0 = $x1 = $latlng[$i]['latitude'];
                $y0 = $y1 = $latlng[$i]['longitude'];
            } else {
                if ($latlng[$i]['latitude'] > $x1) $x1 = $latlng[$i]['latitude'];
                if ($latlng[$i]['latitude'] < $x0) $x0 = $latlng[$i]['latitude'];
                if ($latlng[$i]['longitude'] > $y1) $y1 = $latlng[$i]['longitude'];
                if ($latlng[$i]['longitude'] < $y0) $y0 = $latlng[$i]['longitude'];
            }
        }
    }

    $LatLngBounds = new LatLngBounds(new LatLng($x1, $y1), new LatLng($x0, $y0));

    expect($LatLngBounds->west())->toEqual(-755722456);
    expect($LatLngBounds->south())->toEqual(61683833599089);
    expect($LatLngBounds->center()->getLongitude())->toEqual(-13189845088458);
    expect($LatLngBounds->center()->getLatitude())->toEqual(76407055856654);
    expect($LatLngBounds->north())->toEqual(61683833599089);
    expect($LatLngBounds->east())->toEqual(-755722456);

    
});