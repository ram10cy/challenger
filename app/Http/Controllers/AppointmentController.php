<?php

namespace App\Http\Controllers;

use App\Respositories\AppointmentRespository;
use Illuminate\Http\Request;

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
        return $this->appointmentRespository->create($request->all());
    }
    // Update appointment
    public function update(Request $request){
        return $this->appointmentRespository->update($request->all());
    }
    //Appointment Info
    public function info(Request $request){
        return $this->appointmentRespository->info($request->appointmentId);
    }
    // List appointment
    public function listAppointments(Request $request){
        return $this->appointmentRespository->listAppointments($request->all());
    }
    // List appointment
    public function delete(Request $request){
        return $this->appointmentRespository->delete($request->id);
    }



}
