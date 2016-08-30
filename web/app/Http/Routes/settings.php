<?php
Route::group(['middleware' => 'auth'], function () {
    Route::get('settings', 'SettingController@index');
    Route::get('profile', 'SettingController@profile');
});