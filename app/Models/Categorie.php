<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = [
        'typeChambre',
        'description'
    ];

    /**
     * Les attributs qui doivent être castés
     *
     * @var array
     */
    protected $casts = [
        'features' => 'array',
    ];
    
    /**
     * Relation avec les chambres
     */
    public function chambres()
    {
        return $this->hasMany(Chambre::class);
    }
    
    /**
     * Obtenir le nom formaté pour l'affichage
     */
    public function getNomAttribute()
    {
        return $this->typeChambre;
    }
    
    /**
     * Accesseur pour les caractéristiques des chambres sous forme de tableau
     *
     * @param string|null $value
     * @return array
     */
    public function getFeaturesAttribute($value)
    {
        if (empty($value)) {
            // Caractéristiques par défaut selon le type de chambre
            switch($this->typeChambre) {
                case 'standard':
                    return [
                        'Lit queen-size',
                        'Wi-Fi gratuit',
                        'Climatisation',
                        'Bureau de travail',
                        'Petit-déjeuner inclus'
                    ];
                case 'deluxe':
                    return [
                        'King-size bed',
                        'Vue panoramique',
                        'Climatisation',
                        'Mini-bar',
                        'Wi-Fi premium'
                    ];
                case 'prestige':
                    return [
                        'Salon privé',
                        'Baignoire en marbre',
                        'Vue sur mer',
                        'Dressing',
                        'Service en chambre 24/7'
                    ];
                default:
                    return ['Wi-Fi gratuit', 'Climatisation', 'Service de ménage quotidien'];
            }
        }
        
        return is_array($value) ? $value : json_decode($value, true);
    }


}
