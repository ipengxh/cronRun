<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPermission extends Model
{
    protected $table = 'project_permissions';
    protected $fillable = [
        'project_id',
        'user_id'
    ];
    public $timestamps = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
