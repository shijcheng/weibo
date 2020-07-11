<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

// 注册页
Route::get('signup', 'UsersController@create')->name('signup');

// User RESTful 路由
Route::resource('users', 'UsersController');

// 登录页
Route::get('login', 'SessionsController@create')->name('login');
// 登录
Route::post('login', 'SessionsController@store')->name('login');
// 退出登录
Route::delete('logout', 'SessionsController@destroy')->name('logout');

// email 激活
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
