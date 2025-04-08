<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;
    protected $fillable = [
        'datePa',
        'montant',
        'mode',
        'reservation_id'
    ];
}
