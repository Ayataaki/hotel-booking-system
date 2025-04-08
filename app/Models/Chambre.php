<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chambre extends Model
{
    use HasFactory;
    protected $fillable = [
        'NumEtg',
        'NumCh',
        'status',
        'prixNuit',
        'categorie_id',
        'reservation_id'
    ];
/*    public function category()
    {
        return $this->belongsTo(Categorie::class);
              }
        */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
        
}
