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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/*Category Routes*/
Route::get('/category/get_categories', 'CategoryController@get_categories');
Route::get('/category/trash', 'CategoryController@trashbin');
Route::get('/category/getSoftDeletesCategories', 'CategoryController@getSoftDeletesCategories');
Route::post('/category/restore/{categoryID}', 'CategoryController@restore');
Route::any('/category/searchCategories', 'CategoryController@searchCategories');
Route::delete('/category/permanentlyDelete/{categoryID}', 'CategoryController@permanentlyDelete');


/*Item Routes*/
Route::get('/item/getCategories', 'ItemController@getCategories');
Route::get('/item/getItems', 'ItemController@getItems');
Route::get('/item/trash', 'ItemController@trashbin');
Route::get('/item/getSoftDeleteItems', 'ItemController@getSoftDeleteItems');
Route::post('/item/restore/{itemID}', 'ItemController@restore');
Route::any('item/searchItems', 'ItemController@searchItems');
Route::delete('/item/permanentlyDelete/{itemID}', 'ItemController@permanentlyDelete');

/*Location Routes*/
Route::get('/location/getLocations', 'LocationController@get_locations');

/*Route Resource*/
Route::resource('category', 'CategoryController');
Route::resource('item', 'ItemController');
Route::resource('location', 'LocationController');

