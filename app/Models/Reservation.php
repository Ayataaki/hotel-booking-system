<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'dateDeb',
        'dateFin',
        'totalPayer',
        'soldePayer',
        'receptionniste_id',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function supplementaires()
    {
        return $this->belongsToMany(Supplementaire::class, 'posseders', 'reservation_id', 'supplementaire_id');
    }

}
