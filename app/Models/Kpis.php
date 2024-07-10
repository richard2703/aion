<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kpis extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'kpis';

    protected $fillable = [
        'procedimiento_id',
        'nombre',
        'descripcion',
        'link_externo',
    ];

    public function procedimiento(): BelongsTo
    {
        return $this->belongsTo(Procedimiento::class, 'procedimiento_id', 'id');
    }
}
