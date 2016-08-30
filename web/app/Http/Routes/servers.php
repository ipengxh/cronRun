<?php

Route::group(['middleware' => 'auth', 'prefix' => 'server'], function () {
    Route::get('index', 'ServerController@index');

    Route::get('show', 'ServerController@show');

    Route::get('create', 'ServerController@create');

    Route::post('store', 'ServerController@store');

    Route::get('edit/{id}', 'ServerController@edit');

    Route::post('update/{id}', 'ServerController@update');

    Route::post('destroy/{id}', 'ServerController@destroy');
});
