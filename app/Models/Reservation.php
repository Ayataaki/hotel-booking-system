<?php

namespace App\Models;

use Carbon\Carbon;
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

    /* public function client()
    {
        return $this->belongsTo(Client::class);
    } */

    public function supplementaires()
    {
        return $this->belongsToMany(Supplementaire::class, 'posseders', 'reservation_id', 'supplementaire_id');
    }

    /**
     * Les attributs qui doivent être castés
     *
     * @var array
     */

    // C'est faux :
    // protected $casts = [
    //     'date_arrivee' => 'datetime',
    //     'date_depart' => 'datetime',
    // ];

    // C'est juste.
    protected $casts = [
        'dateDeb' => 'date',
        'dateFin' => 'date',
    ];

    /**
     * Relation avec la chambre
     */
    public function chambre()
    {
        return $this->belongsTo(Chambre::class);
    }

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Calculer la durée du séjour en nuits
     *
     * @return int
     */
    public function getDureeSejourAttribute()
    {
        return $this->date_arrivee->diffInDays($this->date_depart);
    }

    /**
     * Calculer le prix total du séjour
     *
     * @return float
     */
    public function calculerPrixTotal()
    {
        $duree = $this->duree_sejour;
        $prixNuit = $this->chambre->prixNuit;

        return $duree * $prixNuit;
    }

    /**
     * Vérifier si les dates de réservation chevauchent une réservation existante
     *
     * @param Carbon $dateArrivee
     * @param Carbon $dateDepart
     * @param int|null $excludeReservationId
     * @return bool
     */
    public static function checkOverlap($chambreId, $dateArrivee, $dateDepart, $excludeReservationId = null)
    {
        $query = self::where('chambre_id', $chambreId)
            ->where(function ($query) use ($dateArrivee, $dateDepart) {
                $query->where(function ($q) use ($dateArrivee, $dateDepart) {
                    // Réservation qui commence pendant la période demandée
                    $q->where('date_arrivee', '>=', $dateArrivee)
                      ->where('date_arrivee', '<', $dateDepart);
                })->orWhere(function ($q) use ($dateArrivee, $dateDepart) {
                    // Réservation qui finit pendant la période demandée
                    $q->where('date_depart', '>', $dateArrivee)
                      ->where('date_depart', '<=', $dateDepart);
                })->orWhere(function ($q) use ($dateArrivee, $dateDepart) {
                    // Réservation qui englobe la période demandée
                    $q->where('date_arrivee', '<=', $dateArrivee)
                      ->where('date_depart', '>=', $dateDepart);
                });
            });

        // Exclure la réservation en cours de modification si nécessaire
        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return $query->exists();
    }

        public function supplements()
        {
            return $this->belongsToMany(Supplementaire::class, 'posseders', 'reservation_id', 'supplementaire_id');
        }

        // Dans le modèle Supplementaire
        public function reservations()
        {
            return $this->belongsToMany(Reservation::class, 'posseders', 'supplementaire_id', 'reservation_id');
        }




    /**
     * Obtenir les chambres associées à cette réservation.
     */
    public function chambres()
    {
        return $this->belongsTo(Chambre::class);
    }

    public function historique()
    {
        return $this->hasOne(Historique::class, 'reservation_id', 'id');
    }





}
