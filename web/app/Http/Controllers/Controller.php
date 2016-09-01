<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct()
    {
        $locale = \Session::get('locale');
        if (!$locale) {
            $supportedLangs = ['en', 'zh'];
            $browserLang = substr(\Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (!in_array($browserLang, $supportedLangs)) {
                \Session::set('locale', config('app.locale'));
            } else {
                \Session::set('locale', $browserLang);
            }
        }
        \App::setlocale(\Session::get('locale'));
    }
}
