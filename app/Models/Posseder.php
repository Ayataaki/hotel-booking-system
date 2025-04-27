<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posseder extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation_id',
        'supplementaire_id'
    ];
}
