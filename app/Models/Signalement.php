<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Signalement extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_de_signalement_id',
        'description',
        'piece_jointe',
        'code_de_suivi',
        "status_id",
        'cloturer_verification',
        'date_evenement',
        'raison',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
