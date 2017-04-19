<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'nodes';

    protected $fillable = ['name', 'token', 'key', 'owner'];

    protected $dates = ['created_at', 'updated_at'];

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function scopeOwn($query)
    {
        $permissions = NodePermission::whereUserId(\Auth::user()->id)
            ->get();
        return $query->whereIn('id', array_column($permissions->toArray(), 'node_id'));
    }
}
