<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('projects', 'ProjectController@index');

    Route::group(['prefix' => 'project'], function () {
        Route::get('show', 'ProjectController@show');

        Route::get('create', 'ProjectController@create');

        Route::post('store', 'ProjectController@store');

        Route::get('edit/{id}', 'ProjectController@edit');

        Route::post('update/{id}', 'ProjectController@update');

        Route::post('destroy/{id}', 'ProjectController@destroy');
    });
});
