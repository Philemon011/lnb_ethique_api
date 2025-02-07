<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueSignalement extends Model
{
    use HasFactory;

    protected $fillable = [
        'signalement_id',
        'user_id',
        'modifications'
    ];

    protected $casts = [
        'modifications' => 'array', // Convertit le JSON en tableau PHP automatiquement
    ];

    public function signalement()
    {
        return $this->belongsTo(Signalement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
