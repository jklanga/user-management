<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Auth::routes(['verify' => true]);
Route::group(['middleware' => 'auth', 'middleware' => 'verified'], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::match(['POST'],'user/interests', ['as' => 'user.interests', 'uses' => 'UserController@editInterests']);
    Route::match(['POST'], 'user/update', ['as' => 'user.update', 'uses' => 'UserController@updateProfile']);
    Route::match(['GET'], 'user/list', ['as' => 'user.list', 'uses' => 'UserController@list'])->middleware('can:isAdminManager');
    Route::match(['GET'], 'interests/list', ['as' => 'interests.list', 'uses' => 'UserController@interests']);
    Route::match(['GET'], 'user/edit', ['as' => 'user.edit', 'uses' => 'UserController@edit'])->middleware('can:isAdminManager');
    Route::match(['POST'], 'user/edit', ['as' => 'user.edit', 'uses' => 'UserController@edit'])->middleware('can:isAdmin');
    Route::match(['POST'], 'reset/password', ['as' => 'reset.password', 'uses' => 'UserController@resetPassword']);
    Route::match(['POST'],'user/delete', ['as' => 'user.delete', 'uses' => 'UserController@delete'])->middleware('can:isAdmin');
});
