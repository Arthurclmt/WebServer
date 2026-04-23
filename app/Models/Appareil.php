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
    'room_id',
    'start_hour',
    'end_hour',
    'usage_time',
    'consumption',
    'delete_request_number',
    'delete_requested_by'
    ];

    protected $casts = [
        'delete_requested_by' => 'array',
    ];
    protected $table = 'devices';

    public function room(){
        return $this->belongsTo(Room::class);
    }

}
