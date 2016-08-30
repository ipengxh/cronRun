<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.settings');
    }

    public function profile()
    {
        return view('setting.profile');
    }
}
