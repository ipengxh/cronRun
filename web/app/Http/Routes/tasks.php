<?php

Route::group(['middleware' => 'auth', 'prefix' => 'task'], function () {
    Route::get('index', 'TaskController@index');

    Route::get('show', 'TaskController@show');

    Route::get('create', 'TaskController@create');

    Route::post('store', 'TaskController@store');

    Route::get('edit/{id}', 'TaskController@edit');

    Route::post('update/{id}', 'TaskController@update');

    Route::post('destroy/{id}', 'TaskController@destroy');
});
