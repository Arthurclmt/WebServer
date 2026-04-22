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
    'description',
    'room_id'
    ];

    protected $table = 'devices';

    public function room(){
        return $this->belongsTo(Room::class);
    }

}
