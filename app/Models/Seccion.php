<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'secciones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'created_by',
    ];


    public function challenge(): HasMany
    {
        return $this->hasMany(Challenge::class, 'seccion_id', 'id');
    }
}
