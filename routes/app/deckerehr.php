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
Route::group(['prefix'=>'app/deckerehr'], function () use ($router) {

    /* GENERIC STUFF */
    Route::group(['middleware'=>['saml2.auth']], function () use ($router) {
        Route::get('/', ['uses'=>'App\deckerehr\AppController@getViewer']);
        Route::get('/admin/{page?}', ['uses'=>'App\deckerehr\AdminController@admin']);
    });

    /* API STUFF */
    Route::group(['prefix' => 'api','middleware'=>['no.save.session']], function () use ($router) {
    });
});