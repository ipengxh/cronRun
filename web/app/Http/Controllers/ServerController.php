<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ServerController extends Controller
{
    public function index()
    {
        return view('server.index');
    }
}
