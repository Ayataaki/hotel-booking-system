<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    /** @use HasFactory<\Database\Factories\HistoriqueFactory> */
    use HasFactory;
    protected $fillable = [
        'chambre_id',
        'reservation_id',
    ];

    public function chambre()
    {
        return $this->belongsTo(Chambre::class);
    }

    // Relation avec la réservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

}
