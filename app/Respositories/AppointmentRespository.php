<?php


namespace App\Respositories;


use App\Models\AppointmentModel;
use App\Models\ContactModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AppointmentRespository
{
    public function store($appointmentData){

        //Validate data
        $validator = Validator::make($appointmentData, [
            'appointmentAddress' => 'required|string',
            'appointmentDate' => 'required|required|date_format:Y-m-d H:i',
            'contactId'=>'required|number'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $user=auth()->user();

        //Request is valid, create new user
        $appointment = AppointmentModel::create([
            'appointment_address' => $appointmentData['appointmentAddress'],
            'appointment_date' => $appointmentData['appointmentDate'],
            'contact_id' => $appointmentData['contactId'],
            'user_id'=>$user->id,

        ]);



        //Contact created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'data' => $appointment
        ], Response::HTTP_OK);

    }
}
