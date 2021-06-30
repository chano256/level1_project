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
    return view('pages.welcome');
});

Route::get('/index', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');

Route::resource('posts', 'PostsController');

/**
 * Route::get('/home', function () {
 * return view('index');
 * });
 */

Route::get('user/{name}/{id}', function ($name, $id) {
    return 'This is user ' . $name . ' with id = ' . $id;
});

Route::get('/home', 'HomeController@index');
