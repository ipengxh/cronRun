<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'key',
        'owner',
        'node_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function node()
    {
        return $this->belongsTo(Node::class);
    }
}
