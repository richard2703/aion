<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class tareas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tareas';

    protected $fillable = [
        'area_id',
        'departamento_id',
        'minuta_id',
        'tarea',
        'responsable_id',
        'fecha',
        'notas',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }

    public function minuta(): BelongsTo
    {
        return $this->belongsTo(minutas::class, 'minuta_id', 'id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id', 'id');
    }
}