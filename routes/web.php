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

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'ukuran'], function () {
    Route::get('/', 'UkuranController@index')->name('ukuran');
    Route::get('form/{act}/{id?}', 'UkuranController@FormAction')->name('ukuran_form');
    Route::match(['get', 'post'], 'ukuranAction', 'UkuranController@ActionAjax')->name('ukuran_action');
});

Route::group(['prefix' => 'warna'], function () {
    Route::get('/', 'WarnaController@index')->name('warna');
    Route::get('form/{act}/{id?}', 'WarnaController@FormAction')->name('warna_form');
    Route::match(['get', 'post'], 'warnaAction', 'WarnaController@ActionAjax')->name('warna_action');
});

Route::group(['prefix' => 'produk'], function () {
    Route::get('/', 'ProdukController@index')->name('produk');
    Route::get('form/{act}/{id?}', 'ProdukController@FormAction')->name('produk_form');
    Route::match(['get', 'post'], 'produkAction', 'ProdukController@ActionAjax')->name('produk_action');
});
