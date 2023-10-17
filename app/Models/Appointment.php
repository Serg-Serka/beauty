<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function service() :BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
