<?php


namespace App\Respositories;


use App\Models\AppointmentModel;
use App\Models\ContactModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AppointmentRespository
{
    public function create($appointmentData){

        $user=auth()->user();
       //Validate data
        $validator = Validator::make($appointmentData, [
            'appointmentAddress' => 'required|string',
            'appointmentDate' => 'required|required|date_format:Y-m-d H:i',
            'contactId'=>'required|numeric',
            'mode'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $userCoordinates=getPostApiCoordinates($user->address);
        $contactCoordinates=getPostApiCoordinates($appointmentData['appointmentAddress']);

        //check if address is true
        if(verifyAddress($userCoordinates,$contactCoordinates)==false)
            return response()->json([
                'success' => false,
                'error' => 'Address Error',
                'userlat'=>$userCoordinates->latitude,
                'contactlat'=>$contactCoordinates->latitude,
                'userlong'=>$userCoordinates->longitude,
                'contactlong'=>$contactCoordinates->longitude,
            ], Response::HTTP_OK);

        //prepare data to send googleApiHelper
        $apiData= [
            'user_latitude'=>$userCoordinates->latitude,'user_longitude'=>$userCoordinates->longitude,
            'contact_latitude'=>$contactCoordinates->latitude,'contact_longitude'=>$contactCoordinates->longitude,
            'meeting_time'=>strtotime($appointmentData['appointmentDate']),
            'meeting_finish_time'=>strtotime($appointmentData['appointmentDate'])+3600,
            'mode'=>$appointmentData['mode']
        ];

        $durations=  getGoogleApiDurations($apiData);


        //Request is valid, create new user
        $appointment = AppointmentModel::create([
            'appointment_address' => $appointmentData['appointmentAddress'],
            'appointment_time' => $durations['meetingTime'],
            'leave_office_time'=>$durations['leaveOfficeTime'],
            'return_office_time'=>$durations['returnOfficeTime'],
            'contact_id' => $appointmentData['contactId'],
            'user_id'=>$user->id,

        ]);


        //Appointment created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Appointment created successfully',
            'data' => $appointment
        ], Response::HTTP_OK);

    }
}
