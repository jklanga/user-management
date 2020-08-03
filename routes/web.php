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
    return view('home');
});

Auth::routes();

Auth::routes(['verify' => true]);
Route::group(['middleware' => 'auth', 'middleware' => 'verified'], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::match(['POST'],'user/interests', array('as' => 'user.interests', 'uses' => 'UserController@editInterests'));
    Route::match(['POST'], 'user/update', array('as' => 'user.update', 'uses' => 'UserController@update'));
});
