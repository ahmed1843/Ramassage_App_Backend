<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'location',
    'category',      // ← ajout
    'photo_path',
    'status',
    'user_id',
];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relation avec l'utilisateur (si authentification)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}