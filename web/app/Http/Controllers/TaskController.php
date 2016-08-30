<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TaskController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function show()
    {
        return view('home');
    }
}
