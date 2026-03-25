<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    return [
        'id'          => $this->id,
        'nom'         => $this->name,
        'description' => $this->description,
        'horaires'    => $this->schedules->map(fn($s) => [
            'jour'   => $s->collection_day,
            'debut'  => substr($s->start_time, 0, 5), // Enlève les secondes (08:00 au lieu de 08:00:00)
            'fin'    => substr($s->end_time, 0, 5)
        ]),
        'date_creation' => $this->created_at->format('d/m/Y'),
    ];
}

}
