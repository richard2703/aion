<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class minutas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'minutas';

    protected $fillable = [
        'area_id',
        'departamento_id',
        'alias',
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
