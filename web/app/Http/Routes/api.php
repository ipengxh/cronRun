<?php
Route::get('config.ini', 'ApiController@downloadConfig');


Route::get('/server/register', 'ServerController@register');
