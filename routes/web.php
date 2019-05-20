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
Route::post('/user-list', 'HomeController@userlist')->name('userlist.post');
Route::get('/user-add', 'HomeController@useradd')->name('useradd');
Route::post('/user-add', 'HomeController@useradd_post')->name('useradd.post');
Route::get('/user-edit', 'HomeController@useredit')->name('useredit');
Route::post('/user-edit', 'HomeController@useredit_post')->name('useredit.post');
Route::post('/user-del', 'HomeController@userdel')->name('userdel');
//Ajax
Route::post('/check-empid', 'AjaxController@checkempid')->name('checkempid');
Route::post('/check-nname', 'AjaxController@checknname')->name('checknname');
Route::post('/check-nnameedit', 'AjaxController@checknnameedit')->name('checknnameedit');
Route::post('/check-pid', 'AjaxController@checkpid')->name('checkpid');
Route::post('/check-rid', 'AjaxController@checkrid')->name('checkrid');
Route::post('/check-did', 'AjaxController@checkdid')->name('checkdid');
Route::post('/check-puid', 'AjaxController@checkpuid')->name('checkpuid');
Route::post('/invlist', 'AjaxController@invlist')->name('invlist');
//Inventory
Route::get('/inventory-list', 'InvController@inventorylist')->name('inventorylist');
Route::post('/inventory-list', 'InvController@inventorylist')->name('inventorylist.post');
Route::get('/inventory-add', 'InvController@inventoryadd')->name('inventoryadd');
Route::post('/inventory-add', 'InvController@inventoryadd_post')->name('inventoryadd.post');
Route::get('/inventory-edit', 'InvController@inventoryedit')->name('inventoryedit');
Route::post('/inventory-edit', 'InvController@inventoryedit_post')->name('inventoryedit.post');
Route::post('/inventory-del', 'InvController@inventorydel')->name('inventorydel');
//Request
Route::get('/request-list', 'ReqController@requestlist')->name('requestlist');
Route::post('/request-list', 'ReqController@requestlist')->name('requestlist.post');
Route::get('/request-add', 'ReqController@requestadd')->name('requestadd');
Route::post('/request-add', 'ReqController@requestadd_post')->name('requestadd.post');
Route::get('/request-edit', 'ReqController@requestedit')->name('requestedit');
Route::post('/request-edit', 'ReqController@requestedit_post')->name('requestedit.post');
Route::post('/request-del', 'ReqController@requestdel')->name('requestdel');
//Delivery
Route::get('/delivered-list', 'DelController@deliveredlist')->name('deliveredlist');
Route::post('/delivered-list', 'DelController@deliveredlist')->name('deliveredlist.post');
Route::get('/delivered-add', 'DelController@deliveredadd')->name('deliveredadd');
Route::post('/delivered-add', 'DelController@deliveredadd_post')->name('deliveredadd.post');
Route::get('/delivered-edit', 'DelController@deliverededit')->name('deliverededit');
Route::post('/delivered-edit', 'DelController@deliverededit_post')->name('deliverededit.post');
Route::post('/delivered-del', 'DelController@delivereddel')->name('delivereddel');
//Purchase
Route::get('/purchase-list', 'PurchaseController@purchaselist')->name('purchaselist');
Route::post('/purchase-list', 'PurchaseController@purchaselist')->name('purchaselist.post');
Route::get('/purchase-add', 'PurchaseController@purchaseadd')->name('purchaseadd');
Route::post('/purchase-quick', 'PurchaseController@purchasequick')->name('purchasequick');
Route::post('/purchase-add', 'PurchaseController@purchaseadd_post')->name('purchaseadd.post');
Route::get('/purchase-edit', 'PurchaseController@purchaseedit')->name('purchaseedit');
Route::post('/purchase-edit', 'PurchaseController@purchaseedit_post')->name('purchaseedit.post');
Route::post('/purchase-del', 'PurchaseController@purchasedel')->name('purchasedel');
