<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['zone_id', 'collection_day', 'start_time', 'end_time'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
