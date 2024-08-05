<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class minutas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'minutas';

    protected $fillable = [
        'area_id',
        'departamento_id',
        'lider_id',
        'proceso_id',
        'alias',
        'tipo',
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

    public function proceso(): BelongsTo
    {
        return $this->belongsTo(Proceso::class, 'proceso_id', 'id');
    }
    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lider_id', 'id');
    }

    public function tipoMinuta(): BelongsTo
    {
        return $this->belongsTo(TipoMinuta::class, 'tipo', 'id');
    }
}
