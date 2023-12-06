<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'rubriques',
        'amounts',
        'date',
        'user',
        'commantaire',
        'validateBy',
        'validationDate',
        'intervention_id',
        'intervention_lead_id',
    ];

    public function schoolIntevention()
    {
        return $this->belongsTo(SchoolIntervention::class,'intervention_id');
    }


    public function leadIntervention()
    {
        return $this->belongsTo(Lead_interv::class,'intervention_lead_id');
    }


    public static function rubriques()
    {
       
        $transports = array(
            'in-driver' => 'in-Driver',
            'taxi' => 'Taxi',
            'tramway' => 'Tramway',
            'train' => 'Train',
            'gasoil' => 'Gasoil',
            'péage' => 'Péage',
            'petit-déjeuner' => 'Petit-déjeuner',
            'déjeuner' => 'Déjeuner'
        );

        return $transports;
    }
}
