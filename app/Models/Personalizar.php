<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personalizar extends Model
{
    use HasFactory;
    protected $table = 'personalizar';

    protected $fillable = [
        'proposito',
        'logo',
        'banner',
    ];
}
