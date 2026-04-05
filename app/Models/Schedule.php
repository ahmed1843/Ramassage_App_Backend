<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'zone_id', 
        'day_of_week', 
        'pickup_time', 
        'truck_name'
    ];

    public function zone() {
        return $this->belongsTo(Zone::class);
    }
}
