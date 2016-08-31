<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');


/**
 * Unauthed
 */

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', 'HomeController@index');



Route::group(['middleware' => 'auth'], function () {
    Route::get('nodes', 'NodeController@index');

    Route::group(['prefix' =>'Node'], function () {
        Route::get('show', 'NodeController@show');

        Route::get('create', 'NodeController@create');

        Route::post('store', 'NodeController@store');

        Route::get('edit/{id}', 'NodeController@edit');

        Route::post('update/{id}', 'NodeController@update');

        Route::post('destroy/{id}', 'NodeController@destroy');
    });


    Route::get('projects', 'ProjectController@index');

    Route::group(['prefix' => 'project'], function () {
        Route::get('show', 'ProjectController@show');

        Route::get('create', 'ProjectController@create');

        Route::post('store', 'ProjectController@store');

        Route::get('edit/{id}', 'ProjectController@edit');

        Route::post('update/{id}', 'ProjectController@update');

        Route::post('destroy/{id}', 'ProjectController@destroy');
    });


    Route::get('tasks', 'TaskController@index');

    Route::group(['prefix' => 'task'], function () {
        Route::get('show', 'TaskController@show');

        Route::get('create', 'TaskController@create');

        Route::post('store', 'TaskController@store');

        Route::get('edit/{id}', 'TaskController@edit');

        Route::post('update/{id}', 'TaskController@update');

        Route::post('destroy/{id}', 'TaskController@destroy');
    });


    Route::get('settings', 'SettingController@index');
    Route::get('profile', 'SettingController@profile');
});

