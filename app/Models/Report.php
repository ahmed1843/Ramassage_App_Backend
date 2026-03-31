<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title', 
        'latitude', 
        'longitude', 
        'status', 
        'description', 
        'user_id', 
        'zone_id',
        'image' // ✅ IL MANQUAIT CELUI-LÀ !
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function zone() {
        return $this->belongsTo(Zone::class);
    }
}
