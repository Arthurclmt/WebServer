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
    'status', 
    'description'
    ];

    protected $table = 'devices';
}
