<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NodePermission extends Model
{
    protected $table = 'node_permissions';
    protected $fillable = [
        'node_id',
        'user_id'
    ];

    public $timestamps = null;

    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwn($query)
    {
        return $query->whereUserId(\Auth::user()->id);
    }

    public function scopePermission($query, $id)
    {
        return $query->whereUserId(\Auth::user()->id)->whereNodeId($id);
    }
}
