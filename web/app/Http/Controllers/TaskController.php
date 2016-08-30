<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TaskController extends Controller
{
    public function index()
    {
        return view('task.index');
    }

    public function show()
    {
        return view('home');
    }
}
