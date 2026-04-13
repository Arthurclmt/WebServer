<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedMember extends Model
{
    protected $table = 'allowed_members';

    protected $fillable = [
        'email',
        'is_registered',
        'added_by'
    ];
}
