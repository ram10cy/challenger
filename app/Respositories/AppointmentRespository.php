<?php


namespace App\Respositories;


use App\Models\AppointmentModel;
use App\Models\ContactModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AppointmentRespository
{

    protected $appointment;

    /**
     * AppointModel constructor.
     * @param AppointmentModel $user
     */
    public function __construct(AppointmentModel $appointment)
    {
        $this->appointment = $appointment;
    }


    /**
     * @param $appointmentData
     * @return mixed
     */
    public function create($appointmentData){


       //Validate data
        $validator = Validator::make($appointmentData, [
            'appointmentAddress' => 'required|string',
            'appointmentDate' => 'required|required|date_format:Y-m-d H:i',
            'contactId'=>'required|numeric',
            'mode'=>'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'error' => $validator->messages()], 200);
        }

        //check if contact exists
        if (!ContactModel::find($appointmentData['contactId'])) {
            return response()->json([
                'success'=>false,
                'error' => 'Cotact Id number:'.$appointmentData['contactId'].' doesnt exists']);
        }

        //check if another appointment exists on this period of time
        if ($this->appointment->where('leave_office_time', '<', $appointmentData['appointmentDate'])
                            ->where('return_office_time', '>', $appointmentData['appointmentDate'])->exists()) {
            return response()->json([
                'success'=>false,
                'error' => 'You are not available for meeting at this time!']);
        }

        $user=auth()->user();
        $userCoordinates=getPostApiCoordinates($user->address);
        $contactCoordinates=getPostApiCoordinates($appointmentData['appointmentAddress']);

        //check if address is true
        if(verifyAddress($userCoordinates,$contactCoordinates)==false)
            return response()->json([
                'success' => false,
                'error' => 'Address Error',
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


        //Request is valid, create new appintment
        $appointment = $this->appointment->create([
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
    /**
     * @param $appointmentData
     * @return mixed
     */
    public function update($appointmentData){

        $user=auth()->user();
        //Validate data
        $validator = Validator::make($appointmentData, [
            'appointmentAddress' => 'required|string',
            'appointmentDate' => 'required|required|date_format:Y-m-d H:i',
            'contactId'=>'required|numeric',
            'mode'=>'required',
            'id'=>'required|numeric'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'error' => $validator->messages()], 200);
        }

        if($this->appointment->where('id',$appointmentData['id'])->doesntExist())
            return response()->json([
                'success'=>false,
                'error' => 'Appointment not found! Please check Appointment Id'], 200);

        if(ContactModel::where('id',$appointmentData['contactId'])->doesntExist())
            return response()->json([
                'success'=>false,
                'error' => 'Contact not found! Please check Contact Id'], 200);

        $userCoordinates=getPostApiCoordinates($user->address);
        $contactCoordinates=getPostApiCoordinates($appointmentData['appointmentAddress']);

        //check if address is true
        if(verifyAddress($userCoordinates,$contactCoordinates)==false)
            return response()->json([
                'success' => false,
                'error' => 'Address Error'
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
        //Request is valid, update appointment
        $this->appointment->find($appointmentData['id'])->update([
            'appointment_address' => $appointmentData['appointmentAddress'],
            'appointment_time' => $durations['meetingTime'],
            'leave_office_time'=>$durations['leaveOfficeTime'],
            'return_office_time'=>$durations['returnOfficeTime'],
            'contact_id' => $appointmentData['contactId'],
            'user_id'=>$user->id,

        ]);

        $appointment=$this->appointment->find($appointmentData['id']);
        //Appointment updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully',
            'data' => $appointment
        ], Response::HTTP_OK);

    }
    /**
     * @param $appointmentId
     * @return mixed
     */
    public function info($appointmentId){
        $appointment = $this->appointment->find($appointmentId);
        if($appointment)
            return response()->json([
                'success'=>true,
                'appointment' => $appointment]);
        else
            return response()->json([
                'success'=>false,
                'appointment' => 'Appointment id:'.$appointmentId.' not found!']);
    }
    /**
     * @param $appointmentDates
     * @return mixed
     */
    public function listAppointments($appointmentDates){

        //Validate data
        $validator = Validator::make($appointmentDates, [
            'fromDate' => 'nullable|date_format:Y-m-d',
            'toDate' => 'nullable|date_format:Y-m-d',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'error' => $validator->messages()], 200);
        }

        if($appointmentDates['fromDate']) {
            if($appointmentDates['toDate']) {
                $appointments=$this->appointment->whereBetween('created_at', [$appointmentDates['fromDate'], $appointmentDates['toDate']])->where('status',1)->get();
                if($appointments->count())
                    return response()->json([
                        'success'=>true,
                        'fromDate'=>$appointmentDates['fromDate'],
                        'toDate'=>$appointmentDates['toDate'],
                        'appointments' => $appointments]);
                else
                    return response()->json([
                        'success'=>false,
                        'error' => 'According to filter  you have not  any appointments yet, first create one ;)']);
            }
            else {
                $appointments=$this->appointment->where('created_at', '>=',$appointmentDates['fromDate'])->where('status',1)->get();
                if($appointments->count())
                    return response()->json([
                        'success'=>true,
                        'fromDate'=>$appointmentDates['fromDate'],
                        'toDate'=>'No filter',
                        'appointments' => $appointments]);
                else
                    return response()->json([
                        'success'=>false,
                        'error' => 'According to filter you have not  any appointments  yet, first create one ;)']);

            }

        }

        if($appointmentDates['toDate']) {
            $appointments=$this->appointment->where('created_at', '<=',$appointmentDates['toDate'])->where('status',1)->get();
            if($appointments->count())
                return response()->json([
                    'success'=>true,
                    'fromDate'=>'No filter',
                    'toDate'=>$appointmentDates['toDate'],
                    'appointments' => $appointments]);
            else
                return response()->json([
                    'success'=>false,
                    'error' => 'According to filter you have not  any appointments  yet, first create one ;)']);
        }


        $appointments=$this->appointment->where('status',1)->get();
        if($appointments->count())
            return response()->json([
                'fromDate'=>'No filter',
                'toDate'=>'No filter',
                'appointments' => $appointments]);
        else
            return response()->json([
                'success'=>false,
                'error' => 'According to filter you have not  any appointments  yet, first create one ;)']);

    }
    /**
     * @param $appointmentId
     * @return mixed
     */
    public function delete($appointmentId){
        if($appointment=$this->appointment->find($appointmentId)){
            $appointment->delete();
            return response()->json([
                'success'=>true,
                'user' => 'Appointment deleted!']);
        }
        return response()->json([
            'success'=>false,
            'error' => 'Appointment id:'.$appointmentId.' not found!']);
    }
}
