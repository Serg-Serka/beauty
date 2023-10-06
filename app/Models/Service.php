<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use CrudTrait;
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'working_days',
        'working_hours',
        'is_enabled',
        'salon_id',
        'type_id'
    ];
}
