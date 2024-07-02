<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class objetivos extends Model
{
    use HasFactory;
    protected $table = 'objetivos';

    protected $fillable = [
        'titulo', 'objetivo'
    ];
}
