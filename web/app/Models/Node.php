<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'nodes';

    protected $fillable = ['name', 'key', 'owner'];
}
