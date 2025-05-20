<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationReceptionniste extends Model
{
    use HasFactory;

    /**
     * Nom de la table dans la base de données
     */
    protected $table = 'reservations_receptionniste';

    /**
     * Les attributs qui sont assignables en masse
     */
    protected $fillable = [
        'dateDeb',
        'dateFin',
        'totalPayer',
        'soldePayer',
        'receptionniste_id',
        'client_id',
        'statut',
        'methodePaiement',
        'notes'
    ];

    /**
     * Les attributs qui doivent être convertis en dates
     */
    protected $dates = [
        'dateDeb',
        'dateFin',
        'created_at',
        'updated_at'
    ];

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec le réceptionniste (utilisateur)
     */
    public function receptionniste()
    {
        return $this->belongsTo(User::class, 'receptionniste_id');
    }

    /**
     * Relation avec les chambres via la table historiques_receptionniste
     */
    public function chambres()
    {
        return $this->belongsToMany(Chambre::class, 'historiques_receptionniste', 'reservation_receptionniste_id', 'chambre_id')
                    ->withTimestamps();
    }

    
    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'idCh'); // Vérifiez que la clé étrangère est correcte
    }


}
