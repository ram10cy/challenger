<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='appointments';
    protected $fillable=['appointment_address','appointment_time','leave_office_time','return_office_time','contact_id','user_id','status'];
}
