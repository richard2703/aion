<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class objetivoSteps extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'objetivoSteps';

    protected $fillable = [
        'objetivo_id', 'step'
    ];
}
