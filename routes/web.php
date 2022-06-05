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


// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
Route::get('/', 'TransactionController@home')->name('dashboard');
Route::get('/dashboard', 'TransactionController@home')->name('dashboard2');
Route::get('/item', 'ItemController@index')->name('item.index');
Route::post('/item', 'ItemController@create')->name('item.create');
Route::post('/item/{id}', 'ItemController@update')->name('item.update');
Route::get('/item/{id}', 'ItemController@detail')->name('item.detail');
Route::post('/item/{id}/delete', 'ItemController@delete')->name('item.delete');

Route::get('/transaction', 'TransactionController@index')->name('transaction.index');
Route::get('/order', 'TransactionController@orderindex')->name('transaction.orderindex');
Route::get('/itemin', 'TransactionController@inindex')->name('transaction.inindex');
Route::get('/itemout', 'TransactionController@outindex')->name('transaction.outindex');
Route::post('/transaction', 'TransactionController@create')->name('transaction.create');
Route::post('/transaction/accept', 'TransactionController@accept')->name('transaction.create');
Route::post('/transaction/{id}', 'TransactionController@update')->name('transaction.update');
Route::get('/transaction/{id}', 'TransactionController@detail')->name('transaction.detail');
Route::post('/transaction/{id}/delete', 'TransactionController@delete')->name('transaction.delete');

Route::get('/category', 'ItemCategoryController@index')->name('category.index');
Route::post('/category', 'ItemCategoryController@create')->name('category.create');
Route::post('/category/{id}', 'ItemCategoryController@update')->name('category.update');
Route::get('/category/{id}', 'ItemCategoryController@detail')->name('category.detail');
Route::post('/category/{id}/delete', 'ItemCategoryController@delete')->name('category.delete');

Route::get('/unit', 'UnitController@index')->name('unit.index');
Route::post('/unit', 'UnitController@create')->name('unit.create');
Route::post('/unit/{id}', 'UnitController@update')->name('unit.update');
Route::get('/unit/{id}', 'UnitController@detail')->name('unit.detail');
Route::post('/unit/{id}/delete', 'UnitController@delete')->name('unit.delete');

Route::get('/customer', 'CustomerController@index')->name('customer.index');
Route::post('/customer', 'CustomerController@create')->name('customer.create');
Route::post('/customer/{id}', 'CustomerController@update')->name('customer.update');
Route::get('/customer/{id}', 'CustomerController@detail')->name('customer.detail');
Route::post('/customer/{id}/delete', 'CustomerController@delete')->name('customer.delete');

Route::get('/supplier', 'SupplierController@index')->name('supplier.index');
Route::post('/supplier', 'SupplierController@create')->name('supplier.create');
Route::post('/supplier/{id}', 'SupplierController@update')->name('supplier.update');
Route::get('/supplier/{id}', 'SupplierController@detail')->name('supplier.detail');
Route::post('/supplier/{id}/delete', 'SupplierController@delete')->name('supplier.delete');

Route::get('/user', 'UserController@index')->name('user.index');
Route::post('/user', 'UserController@create')->name('user.create');
Route::post('/user/{id}', 'UserController@update')->name('user.update');
Route::get('/user/{id}', 'UserController@detail')->name('user.detail');
Route::post('/user/{id}/delete', 'UserController@delete')->name('user.delete');

Route::get('/warehouse', 'WarehouseController@index')->name('warehouse.index');
Route::post('/warehouse', 'WarehouseController@create')->name('warehouse.create');
Route::post('/warehouse/{id}', 'WarehouseController@update')->name('warehouse.update');
Route::get('/warehouse/{id}', 'WarehouseController@detail')->name('warehouse.detail');
Route::post('/warehouse/{id}/delete', 'WarehouseController@delete')->name('warehouse.delete');

Route::get('/job-title', 'JobTitleController@index')->name('jobtitle.index');
Route::post('/job-title', 'JobTitleController@create')->name('jobtitle.create');
Route::post('/job-title/{id}', 'JobTitleController@update')->name('jobtitle.update');
Route::get('/job-title/{id}', 'JobTitleController@detail')->name('jobtitle.detail');
Route::post('/job-title/{id}/delete', 'JobTitleController@delete')->name('jobtitle.delete');

Route::get('/partner-type', 'BussinesPartnerTypeController@index')->name('partnertype.index');
Route::post('/partner-type', 'BussinesPartnerTypeController@create')->name('partnertype.create');
Route::post('/partner-type/{id}', 'BussinesPartnerTypeController@update')->name('partnertype.update');
Route::get('/partner-type/{id}', 'BussinesPartnerTypeController@detail')->name('partnertype.detail');
Route::post('/partner-type/{id}/delete', 'BussinesPartnerTypeController@delete')->name('partnertype.delete');

Route::get('/partner', 'BussinesPartnerController@index')->name('partner.index');
Route::post('/partner', 'BussinesPartnerController@create')->name('partner.create');
Route::post('/partner/{id}', 'BussinesPartnerController@update')->name('partner.update');
Route::get('/partner/{id}', 'BussinesPartnerController@detail')->name('partner.detail');
Route::post('/partner/{id}/delete', 'BussinesPartnerController@delete')->name('partner.delete');

Route::get('/fetch/item', 'ItemController@fetch')->name('fetch.item');
Route::get('/fetch/unit', 'UnitController@fetch')->name('fetch.unit');
Route::get('/fetch/JobTitle', 'JobTitleController@fetch')->name('fetch.JobTitle');
Route::get('/fetch/warehouse', 'WarehouseController@fetch')->name('fetch.warehouse');
Route::get('/fetch/category', 'ItemCategoryController@fetch')->name('fetch.category');
Route::get('/fetch/supplier', 'SupplierController@fetch')->name('fetch.supplier');
Route::get('/fetch/partner-type', 'BussinesPartnerTypeController@fetch')->name('fetch.partnertype');
// });
