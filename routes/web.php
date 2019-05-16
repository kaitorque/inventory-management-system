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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'LoginController@login')->name('login');
Route::get('/test', 'TestController@test')->name('test');
Route::post('/', 'LoginController@login_post')->name('login.post');
Route::get('/home', 'HomeController@home')->name('home');
Route::post('/logout', 'LoginController@logout')->name('logout');
//User
Route::get('/user-list', 'HomeController@userlist')->name('userlist');
Route::post('/user-list', 'HomeController@userlist')->name('userlist.search');
Route::get('/user-add', 'HomeController@useradd')->name('useradd');
Route::get('/user-edit', 'HomeController@useredit')->name('useredit');
Route::get('/user-del', 'HomeController@userdel')->name('userdel');
//Ajax
Route::post('/check-empid', 'AjaxController@checkempid')->name('checkempid');
