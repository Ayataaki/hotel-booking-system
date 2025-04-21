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
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
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
}
