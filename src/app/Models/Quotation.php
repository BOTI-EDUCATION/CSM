<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;


    protected $fillable = [
        'type',
        'niveaux',
        'nombre',
        'pack',
        'ville',
        'tel',
        'email',
        'nom'
    ];

    public function files(){
        return $this->hasMany(QuotationFile::class,'quotation_id','id');
    }

    public function getArrayFile(){
        $files = array();

        foreach ($this->files()->get() as $file) {
            $files[] = [
                'label' => $file->titre,
                'path' => $file->file
            ];
        }

        return $files;
    }
}
