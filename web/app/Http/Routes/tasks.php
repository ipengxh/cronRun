<?php

Route::group(['middleware' => 'auth'], function () {

    Route::get('tasks', 'TaskController@index');

    Route::group(['prefix' => 'task'], function () {
        Route::get('show', 'TaskController@show');

        Route::get('create', 'TaskController@create');

        Route::post('store', 'TaskController@store');

        Route::get('edit/{id}', 'TaskController@edit');

        Route::post('update/{id}', 'TaskController@update');

        Route::post('destroy/{id}', 'TaskController@destroy');
    });
});
