<?php

namespace App\Models;

use Carbon\Carbon;
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
        'reservation_id',
        'image',
        'capacite'
    ];
    /*    public function category()
    {
        return $this->belongsTo(Categorie::class);
              }
        */

    // Pour savoir la catégorie de chaque chambre, ça va nous aider dans la page d'admin.
    public function categorie()
    {
        // return $this->belongsTo(Categorie::class, 'categorie_id');
        return $this->belongsTo(Categorie::class);
    }
    // Relation avec l'historique
    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }
    /* public function chambres()
    {
        return $this->belongsToMany(Chambre::class, 'historiques', 'reservation_id', 'chambre_id')
                    ->withTimestamps();
    } */
    public function chambres()
    {
        return $this->belongsToMany(Chambre::class, 'historiques', 'reservation_id', 'chambre_id')
                    ->withTimestamps();
    }

     /**
     * Vérifie si la chambre est disponible pour les dates spécifiées
     *
     * @param string $dateArrivee
     * @param string $dateDepart
     * @return bool
     */
    public function isAvailableForDates($dateArrivee, $dateDepart)
    {
        // Si la chambre est occupée (status = 1), elle n'est pas disponible
        if ($this->status == 1) {
            return false;
        }

        // Convertir les dates en objets Carbon pour faciliter les comparaisons
        $arrivee = Carbon::parse($dateArrivee);
        $depart = Carbon::parse($dateDepart);

        // Vérifier s'il existe des réservations qui se chevauchent avec la période demandée
        $reservationCount = $this->reservations()
            ->where(function ($query) use ($arrivee, $depart) {
                $query->where(function ($q) use ($arrivee, $depart) {
                    // Réservation qui commence pendant la période demandée
                    $q->where('date_arrivee', '>=', $arrivee)
                      ->where('date_arrivee', '<', $depart);
                })->orWhere(function ($q) use ($arrivee, $depart) {
                    // Réservation qui finit pendant la période demandée
                    $q->where('date_depart', '>', $arrivee)
                      ->where('date_depart', '<=', $depart);
                })->orWhere(function ($q) use ($arrivee, $depart) {
                    // Réservation qui englobe la période demandée
                    $q->where('date_arrivee', '<=', $arrivee)
                      ->where('date_depart', '>=', $depart);
                });
            })
            ->count();

        // La chambre est disponible s'il n'y a pas de réservations qui se chevauchent
        return $reservationCount === 0;
    }

    /**
     * Récupère toutes les chambres disponibles pour les dates spécifiées
     *
     * @param string $dateArrivee
     * @param string $dateDepart
     * @param int|null $categorieId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAvailableRooms($dateArrivee, $dateDepart, $categorieId = null)
    {
        $arrivee = Carbon::parse($dateArrivee);
        $depart = Carbon::parse($dateDepart);

        $query = self::with('categorie')
            ->where('status', 0) // Uniquement les chambres non occupées
            ->whereDoesntHave('reservations', function ($query) use ($arrivee, $depart) {
                $query->where(function ($q) use ($arrivee, $depart) {
                    $q->where(function ($q) use ($arrivee, $depart) {
                        $q->where('date_arrivee', '>=', $arrivee)
                          ->where('date_arrivee', '<', $depart);
                    })->orWhere(function ($q) use ($arrivee, $depart) {
                        $q->where('date_depart', '>', $arrivee)
                          ->where('date_depart', '<=', $depart);
                    })->orWhere(function ($q) use ($arrivee, $depart) {
                        $q->where('date_arrivee', '<=', $arrivee)
                          ->where('date_depart', '>=', $depart);
                    });
                });
            });

        // Filtrer par catégorie si spécifiée
        if ($categorieId) {
            $query->where('categorie_id', $categorieId);
        }

        return $query->get();
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            0 => 'Maintenance',
            1 => 'Disponible',
            2 => 'Occupée',
            default => 'Inconnu'
        };
    }


    /**
     * Relation avec les réservations du réceptionniste
     */
    public function reservationsReceptionniste()
    {
        return $this->belongsToMany(ReservationReceptionniste::class, 'historiques_receptionniste', 'chambre_id', 'reservation_receptionniste_id')
                    ->withTimestamps();
    }

    /**
     * Relation avec les historiques du réceptionniste
     */
    public function historiquesReceptionniste()
    {
        return $this->hasMany(HistoriqueReceptionniste::class);
    }

    /**
     * Vérifie si la chambre est disponible pour les dates spécifiées (pour réservations réceptionniste)
     */
    public function isAvailableForReceptionistDates($dateArrivee, $dateDepart)
    {
        // Si la chambre n'est pas disponible (status != 1), elle n'est pas disponible
        if ($this->status != 1) {
            return false;
        }

        // Convertir les dates en objets Carbon pour faciliter les comparaisons
        $arrivee = Carbon::parse($dateArrivee);
        $depart = Carbon::parse($dateDepart);

        // Vérifier s'il existe des réservations en ligne qui se chevauchent
        $reservationOnlineCount = $this->historiques()
            ->whereHas('reservation', function ($query) use ($arrivee, $depart) {
                $query->where(function ($q) use ($arrivee, $depart) {
                    $q->where(function ($q) use ($arrivee, $depart) {
                        $q->where('dateDeb', '>=', $arrivee)
                        ->where('dateDeb', '<', $depart);
                    })->orWhere(function ($q) use ($arrivee, $depart) {
                        $q->where('dateFin', '>', $arrivee)
                        ->where('dateFin', '<=', $depart);
                    })->orWhere(function ($q) use ($arrivee, $depart) {
                        $q->where('dateDeb', '<=', $arrivee)
                        ->where('dateFin', '>=', $depart);
                    });
                });
            })
            ->count();

        // Vérifier s'il existe des réservations réceptionniste qui se chevauchent
        $reservationReceptionnisteCount = $this->historiquesReceptionniste()
            ->whereHas('reservationReceptionniste', function ($query) use ($arrivee, $depart) {
                $query->where(function ($q) use ($arrivee, $depart) {
                    $q->where(function ($q) use ($arrivee, $depart) {
                        $q->where('dateDeb', '>=', $arrivee)
                        ->where('dateDeb', '<', $depart);
                    })->orWhere(function ($q) use ($arrivee, $depart) {
                        $q->where('dateFin', '>', $arrivee)
                        ->where('dateFin', '<=', $depart);
                    })->orWhere(function ($q) use ($arrivee, $depart) {
                        $q->where('dateDeb', '<=', $arrivee)
                        ->where('dateFin', '>=', $depart);
                    });
                })
                ->where('statut', '!=', 'annulée');
            })
            ->count();

        // La chambre est disponible s'il n'y a pas de réservations qui se chevauchent
        return $reservationOnlineCount === 0 && $reservationReceptionnisteCount === 0;
    }



}
