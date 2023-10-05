<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use CrudTrait;
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone',
        'date',
        'service_id'
    ];
}
