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

    protected $dates = false;

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

    public function scopeManage($query)
    {
        return $this->scopeOwn($query)->whereRole('manager');
    }

    public function scopeWatch($query)
    {
        return $this->scopeOwn($query)->whereRole('watcher');
    }
}
