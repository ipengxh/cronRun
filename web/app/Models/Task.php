<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'key',
        'owner',
        'project_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeProject($query, $id)
    {
        return $query->whereProjectId($id);
    }

    public function scopeOwn($query)
    {
        return $query->whereOwner(\Auth::user()->id);
    }
}
