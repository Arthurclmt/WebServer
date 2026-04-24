<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'capacity', 'description'];

    public function devices()
    {
        return $this->hasMany(Appareil::class, 'room_id');
    }
}