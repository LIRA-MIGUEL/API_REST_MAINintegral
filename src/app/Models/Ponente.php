<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ponente extends Model
{
    protected $table = 'ponentes';
    
    protected $fillable = [
        'nombre',
        'biografia',
        'especialidad'
    ];

    // Relaciones comentadas temporalmente para evitar errores
    /*
    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'evento_ponente');
    }
    */
}
