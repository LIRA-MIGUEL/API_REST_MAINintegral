<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistente extends Model
{
    protected $table = 'asistentes';
    
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'evento_id'
    ];

    /**
     * Relación con el evento
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }
}
