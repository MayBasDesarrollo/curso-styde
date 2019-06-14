<?php

Route::get('/', function () {
    return 'Home';
});

Route::get('usuarios', 'UserController@index')
    ->name('users.index');

Route::get('usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('users.show');

Route::get('usuarios/nuevo', 'UserController@create')
    ->name('users.create');

Route::post('usuarios', 'UserController@store');

Route::get('usuarios/{user}/editar', 'UserController@edit')
    ->where('id', '[0-9]+')
    ->name('users.edit');

Route::put('usuarios/{user}', 'UserController@update');

Route::get('saludos/{name}/{nickname?}','WelcomeUserController'); //

Route::delete('usuarios/{user}', 'UserController@destroy')
    ->name('users.delete');
