<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='contacts';
    protected $fillable=['name','surname','email','phone','address','user_id'];
}
