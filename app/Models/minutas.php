<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class minutas extends Model
{
    use HasFactory;

    protected $table = 'minutas';

    protected $fillable = [
        'alias',
        'area_id',
        'departamento_id',
        'tipo',
        'pilar',
        'proceso',
        'procedimientos',
        'tareas',
        'responsable_id',
        'notas',
        'estatus'
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id', 'id');
    }
}
