<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'reservation_id', 'numero_facture', 'montant_total',
        'date_emission', 'statut', 'details'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
