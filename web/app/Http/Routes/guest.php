<?php

/**
 * Unauthed
 */

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', 'HomeController@index');
