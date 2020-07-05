<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/authorization', 'UserController@login');
Route::group(['middleware' => 'auth:api'], function (){
    Route::get('/auth-user', "UserController@auth_user");
    Route::get('/get-dir-name/{folder_id}', 'DirController@get_name');
    Route::get('/dir-path/{folder_id}', 'DirController@get_path');
    Route::get('/users', 'UserController@index')->middleware('can:admin');
    Route::delete('/users/{user}', 'UserController@destroy')->middleware('can:admin');
    Route::delete('/logout', 'UserController@logout');
    Route::get('/devices', 'UserController@devices')->middleware('can:admin');
    Route::delete('/devices/{auth_token}', 'UserController@device_destroy')->middleware('can:admin');
    Route::post('/users', 'UserController@store')->middleware('can:admin');
    Route::post('/folder/{folder_id}', 'DirController@store');
    Route::get('/folder/{folder_id}', 'DirController@view');
    Route::post('/folder/{folder_id}/files', 'FileController@store');
    Route::delete('/folder/{dir}', 'DirController@destroy');
    Route::delete('/file/{file}', 'FileController@destroy');
    Route::get('/access/{user}/{folder_id}', 'UserController@access')->middleware('can:admin');
});
