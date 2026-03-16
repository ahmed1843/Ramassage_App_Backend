<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
