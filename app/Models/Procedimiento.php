<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procedimiento extends Model
{
    use HasFactory;

    protected $table = 'procedimientos';

    protected $fillable = [
        'proceso_id',
        'nombre',
        'descripcion',
        'link_externo',
    ];

    public function proceso(): BelongsTo
    {
        return $this->belongsTo(Proceso::class, 'proceso_id', 'id');
    }
}
