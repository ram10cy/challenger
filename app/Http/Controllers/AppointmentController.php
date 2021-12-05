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
        return $this->appointmentRespository->create($request->all());
    }
    // Update appointment
    public function update(Request $request){
        return $this->appointmentRespository->create($request->all());
    }
}
