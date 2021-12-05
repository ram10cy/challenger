<?php

function getPostApiCoordinates($address){
    // to get locations of adresss
    $postCodesApiUrl = 'http://api.postcodes.io/postcodes/'.$address;
    //read json file from url in php
    $readJSONFile = file_get_contents($postCodesApiUrl);
    $array = json_decode($readJSONFile);
    return $array->result;
}
