<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NodePermission extends Model
{
    protected $table = 'node_permissions';
    protected $fillable = [
        'node_id',
        'user_id',
        'role'
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
        return $query->with('user', 'node')
        ->whereUserId(\Auth::user()->id)
        ->get();
    }

    public function scopeManage($query)
    {
        return $query->with('user', 'node')
        ->whereUserId(\Auth::user()->id)
        ->whereRole('manager')
        ->get();
    }

    public function scopeWatch($query)
    {
        return $query->with('user', 'node')
        ->whereUserId(\Auth::user()->id)
        ->whereRole('watcher')
        ->get();
    }
}
