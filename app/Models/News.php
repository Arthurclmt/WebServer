<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ['title', 'content', 'image', 'type', 'event_id'];

    public function event(){
        return $this->belongsTo(Event::class);
    }
}
