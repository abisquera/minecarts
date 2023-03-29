<?php

require_once "qr/qrlib.php";  

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
    if (isset($_SERVER['HTTP_HOST'])) {
        $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), -1, PREG_SPLIT_NO_EMPTY);
        $core = $core[0];

        $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
        $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
        $base_url = sprintf( $tmplt, $http, $hostname, $end );
    }
    else $base_url = 'http://localhost/';

    if ($parse) {
        $base_url = parse_url($base_url);
        if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
    }

    return $base_url;
}

function request_url($extension,$method = "get"){
    return base_url(true) . "request/$method.php/$extension";
}


function makeQR($code)
{
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = 'temp/';

    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);


    $filename = $PNG_TEMP_DIR."$code.png";
    
    $errorCorrectionLevel = 'H';   

    $matrixPointSize = 8;

        
    $filename = $PNG_TEMP_DIR.'test'.md5($code.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
    QRcode::png($code, $filename, "H", $matrixPointSize, 2); 
    
    return $PNG_WEB_DIR.basename($filename);
}


function url_scheme($origin,$desti)
{
    $origin = implode(",",$origin); // encode the starting point
    $destination = implode(",",$desti); // encode the destination

    $url = "https://www.google.com/maps/dir/?api=1";
    $url .= "&origin={$origin}";
    $url .= "&destination={$destination}";
    $url .= "&dirflg=d"; // set the "dirflg" parameter to "d" to indicate driving directions

    // construct the URL scheme for opening the Google Maps app
    $url_scheme = "comgooglemaps://?daddr={$destination}&directionsmode=driving&saddr={$origin}";

    // check if the Google Maps app is installed on the device
    if (preg_match('/iPad|iPhone|iPod/', $_SERVER['HTTP_USER_AGENT'])) {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'OS 6') || strpos($_SERVER['HTTP_USER_AGENT'], 'OS 5') ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'OS 4') || strpos($_SERVER['HTTP_USER_AGENT'], 'OS 3')) {
            // open the Apple Maps app on iOS versions before iOS 7
            return "http://maps.apple.com/?saddr={$origin}&daddr={$destination}";
        } else {
            // open the Google Maps app on iOS 7 and later
            return $url_scheme;
        }
    } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
        // open the Google Maps app on Android
            return $url_scheme;
    } else {
        // redirect the user to the Google Maps website on other devices
        return $url;
    }
    exit;

}

function validateParkingInfo($placeId)
{
    include_once "fn.php";

    if(empty(getLotInfo($placeId)))
        return false;

    return true;
}
