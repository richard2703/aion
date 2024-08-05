<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    use HasFactory;

    protected $table = 'acciones';

    protected $fillable = [
        'id',
        'tipo_id',
        'area_id',
        'proceso_id',
        'titulo',
        'link',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id')->withTrashed();
    }

    public function proceso()
    {
        return $this->belongsTo(Proceso::class, 'proceso_id')->withTrashed();
    }

    public function tipo()
    {
        return $this->belongsTo(TipoAccion::class, 'tipo_id')->withTrashed();
    }
}
