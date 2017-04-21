<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'token',
        'owner',
        'project_id',
        'command',
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

    public function toIni()
    {
        return [
            'app' => [
                'name' => $this->name,
                'project' => $this->project->name,
                'token' => $this->token,
                'command' => $this->command,
                'param' => $this->param ?: '>> /dev/null',
                'enable' => (bool) $this->enabled,
            ],
            'schedule' => [
                'interval' => (int) $this->interval,
                'timer' => (string) $this->timer,
            ],
            'error' => [
                'min_time' => $this->min_time,
                'max_time' => $this->max_time,
                'timeout' => $this->timeout,
                'retry_times' => (int) $this->retry_times,
                'retry_interval' => (int) $this->retry_interval,
                'error_code' => $this->error_code ?: '',
            ],
            'notify' => [
                'enable' => (bool) $this->notify_enabled,
                'email' => $this->notify_email ?: '',
                'success' => (bool) $this->notify_success,
                'fail' => (bool) $this->notify_fail,
            ],
        ];
    }
}
