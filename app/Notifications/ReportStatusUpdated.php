<?php

namespace App\Notifications;

use App\Models\Notification as NotificationModel; 
use App\Models\Schedule;

class ReportStatusUpdated
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function send($user)
    {
        $zoneName = $this->report->zone ? $this->report->zone->name : 'Dakar';
        $schedule = Schedule::where('zone_id', $this->report->zone_id)->first();
        $heure = $schedule ? $schedule->start_time : "Bientôt";

        $messages = [
            'pending'     => "Signalement reçu à $zoneName.",
            'in_progress' => "🚚 Camion en route ! Arrivée vers $heure.",
            'resolved'    => "✅ Déchets ramassés ! Dakar propre.",
        ];

        return NotificationModel::create([
            'user_id' => $user->id,
            'title'   => "Mise à jour #{$this->report->id}",
            'message' => $messages[$this->report->status] ?? "Statut modifié",
            'is_read' => false
        ]);
    }
}
