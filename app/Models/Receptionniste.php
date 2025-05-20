<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receptionniste extends Model
{
    // use HasFactory;
    // protected $fillable = [
    //     'nomRec',
    //     'prenomRec',
    //     'CIN',
    //     'numTel',
    //     'user_id'
    // ];

    use HasFactory;

    protected $fillable = [
        'prenomRec',
        'nomRec',
        'email',
        'numTel',
        'CIN',
        'adresse',
        'statut',
        'user_id',
        'created_at',
        'updated_at'
    ];

}
