<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'key',
        'owner'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'owner');
    }
}
