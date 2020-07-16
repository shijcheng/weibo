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

// 重置密码发送邮箱页
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// 邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// 密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// 执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Statuses RESTful 路由
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);

// 关注人列表
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
// 粉丝列表
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

