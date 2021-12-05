<?php

namespace App\Http\Controllers;

use App\Respositories\AppointmentRespository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    /**
     * @var AppointmentRespository
     */
    private $appointmentRespository;
    public function __construct(AppointmentRespository $appointmentRespository){
        $this->appointmentRespository = $appointmentRespository;
    }
    // Create new appointment
    public function create(Request $request){

        $url = 'http://api.postcodes.io/postcodes/adasdsa';

//read json file from url in php
        $readJSONFile = file_get_contents($url);

//convert json to array in php
        $array = json_decode($readJSONFile);
       $result=$array->result->longitude;
        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'data' => $result
        ], Response::HTTP_OK);
      //  return $this->appointmentRespository->create($request->all());
    }
}
