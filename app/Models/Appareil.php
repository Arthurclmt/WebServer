<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appareil extends Model
{
    protected $fillable = [
    'name', 
    'id',
    'type', 
    'brand',
    'image',
    'status', 
    'description'
    ];

    protected $table = 'devices';
}
