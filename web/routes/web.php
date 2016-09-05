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
Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

/**
 * Unauthed
 */

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', 'HomeController@index');


Route::group(['middleware' => 'auth'], function () {
    Route::get('nodes', 'NodeController@index');

    Route::group(['prefix' =>'node'], function () {
        Route::get('show', 'NodeController@show');

        Route::post('store', 'NodeController@store');

        Route::get('edit/{id}', [
                'as' => 'node:edit',
                'uses' => 'NodeController@edit'
            ]);

        Route::post('update/{id}', [
                'as' => 'node:update',
                'uses' => 'NodeController@update'
            ]);

        Route::post('destroy/{id}', [
                'as' => 'node:destroy',
                'uses' => 'NodeController@destroy'
            ]);
    });


    Route::get('projects', 'ProjectController@index');

    Route::group(['prefix' => 'project'], function () {
        Route::get('node/{id}', [
                'as' => 'node:projects',
                'uses' => 'ProjectController@node'
            ]);

        Route::get('show', 'ProjectController@show');

        Route::get('create', 'ProjectController@create');

        Route::post('store', 'ProjectController@store');

        Route::get('edit/{id}', [
                'as' => 'project:edit',
                'uses' => 'ProjectController@edit'
            ]);

        Route::post('update/{id}', 'ProjectController@update');

        Route::post('destroy/{id}', 'ProjectController@destroy');
    });


    Route::get('tasks', 'TaskController@index');

    Route::group(['prefix' => 'task'], function () {
        Route::get('project/{id}', [
                'as' => 'project:tasks',
                'uses' => 'TaskController@project'
            ]);
        Route::get('show', 'TaskController@show');

        Route::get('create', 'TaskController@create');

        Route::post('store', 'TaskController@store');

        Route::get('edit/{id}', [
                'as' => 'task:edit',
                'task:edit' => 'TaskController@edit'
            ]);

        Route::post('update/{id}', 'TaskController@update');

        Route::post('destroy/{id}', 'TaskController@destroy');
    });


    Route::get('settings', 'SettingController@index');
    Route::get('profile', 'SettingController@profile');
});


Route::get('/test', 'TestController@index');
