<?php

function getPostApiCoordinates($address){
    // to get locations of adresss
    $postCodesApiUrl = 'http://api.postcodes.io/postcodes/'.$address;
 // validate if address is valid
    $validateAddress= $postCodesApiUrl.'/validate';
    $readJSONFile = file_get_contents($validateAddress);
    $array = json_decode($readJSONFile);
    if($array->result==false)
    {
        $array->message='Address not found';
        return $array;
    }
    //return address coordinates
    $readJSONFile = file_get_contents($postCodesApiUrl);
    $array = json_decode($readJSONFile);
    return $array->result;

}
function verifyAddress($userAddress,$contactAddress){
    if(
        isset($userAddress->latitude) and
        isset($userAddress->longitude) and
        isset($contactAddress->latitude) and
        isset($contactAddress->longitude)
    )
       return True;
    else
      return False;


}
function getGoogleApiDurations($data){

    $urlArrival='https://api.distancematrix.ai/maps/api/distancematrix/json?origins='.$data['user_latitude'].','.$data['user_longitude'].'&destinations='.$data['contact_latitude'].','.$data['contact_longitude'].'&arrival_time='.$data['meeting_time'].'&mode=driving&key=2e826TqOA3JE8rI3wmrbL3A31RmWd';
    $urlDeparture='https://api.distancematrix.ai/maps/api/distancematrix/json?origins='.$data['contact_latitude'].','.$data['contact_longitude'].'&destinations='.$data['user_latitude'].','.$data['user_longitude'].'&departure_time='.$data['meeting_finish_time'].'&mode=driving&key=2e826TqOA3JE8rI3wmrbL3A31RmWd';

    $readJSONFileArrival = file_get_contents($urlArrival);
    $readJSONFileDeparture = file_get_contents($urlDeparture);

    $arrivalDuration = json_decode($readJSONFileArrival)->rows[0]->elements[0]->duration;
    $departureDuration = json_decode($readJSONFileDeparture)->rows[0]->elements[0]->duration;

    $meetingTime=date('Y-m-d H:i',$data['meeting_time']);
    $leaveOfficeTime=date('Y-m-d H:i',($data['meeting_time']-$arrivalDuration->value));
    $returnToOfficeTime=date('Y-m-d H:i',($data['meeting_finish_time']+$departureDuration->value));

    $array=[
         'meetingTime'=>$meetingTime,
         'leaveOfficeTime'=> $leaveOfficeTime,
         'returnOfficeTime'=>$returnToOfficeTime
    ];

    return $array;

}
