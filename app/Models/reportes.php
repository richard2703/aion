<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class reportes extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'reportes';

    protected $fillable = [
        'departamento_id',
        'semana_id',
        'aviso',
        'created_for',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }
    public function semana(): BelongsTo
    {
        return $this->belongsTo(reporteSemanal::class, 'semana_id', 'id');
    }
    // public function procedimientos()
    // {
    //     return $this->hasMany(Procedimiento::class);
    // }
    public function highlight()
    {
        return $this->hasMany(lights::class, 'reporte_id')->where('tipo', 1);
    }
    public function lowlight()
    {
        return $this->hasMany(lights::class, 'reporte_id')->where('tipo', 0);
    }
}
