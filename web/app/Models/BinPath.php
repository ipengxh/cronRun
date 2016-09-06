<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinPath extends Model
{
    protected $table = 'bin_paths';
    protected $fillable = [
        'node_id',
        'name',
        'path'
    ];

    public $timestamps = null;

    public function node()
    {
        return $this->belongsTo(Node::class);
    }
}
