<?php

Route::group(['middleware' => 'auth', 'prefix' => 'project'], function () {
    Route::get('index', 'ProjectController@index');

    Route::get('show', 'ProjectController@show');

    Route::get('create', 'ProjectController@create');

    Route::post('store', 'ProjectController@store');

    Route::get('edit/{id}', 'ProjectController@edit');

    Route::post('update/{id}', 'ProjectController@update');

    Route::post('destroy/{id}', 'ProjectController@destroy');
});
