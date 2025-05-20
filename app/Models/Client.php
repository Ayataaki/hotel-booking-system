<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'pays',
        'region',
        'numTel',
        'typeId',
        'CIN',
        'passeport',
        'utilisateur_id'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
