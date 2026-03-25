<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
// Dans SignalementResource.php
public function toArray($request)
{
    $createdAt = \Carbon\Carbon::parse($this->created_at);
    $isOld = $createdAt->diffInHours(now()) >= 48;
    
    // On vérifie "pending" (attente) et le type
    $isUrgent = ($this->type === 'Déchets dangereux' || ($this->status === 'pending' && $isOld));

    return [
        'id'          => $this->id,
        'titre'       => $this->title, // Mappe 'title' vers 'titre' pour ton JS
        'description' => $this->description,
        'type'        => $this->type,
        'status'      => $this->status, 
        'is_priority' => $isUrgent,
        'photo_url'   => $this->image ? asset('storage/' . $this->image) : null,
        'date'        => $this->created_at->format('d/m/Y H:i'),
    ];
}



}

