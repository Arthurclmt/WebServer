<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLog extends Model
{
    use HasFactory;

    // On autorise l'assignation de ces colonnes
    protected $fillable = [
        'user_id',
        'action',
        'points_earned',
    ];

    /**
     * Relation : Un log appartient à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}