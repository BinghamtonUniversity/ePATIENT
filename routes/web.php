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

/* GENERIC STUFF */
Route::group(['middleware'=>['saml2.auth']], function () use ($router) {
    Route::get('/', ['uses'=>'HomeController@home']);
    Route::get('/admin/teams/{team?}/{type?}', ['uses'=>'AdminController@admin_teams']);
    Route::get('/admin/{page?}', ['uses'=>'AdminController@admin']);
});

/* API STUFF */

Route::group(['prefix' => 'api','middleware'=>['no.save.session']], function () use ($router) {
    //** USERS **//
    Route::get('/users',['uses'=>'UserController@browse'])->middleware('can:browse,App\User');
    Route::get('/users/{user}',['uses'=>'UserController@read'])->middleware('can:view,user');
    Route::put('/users/{user}',['uses'=>'UserController@edit'])->middleware('can:manage,App\User');
    Route::post('/users',['uses'=>'UserController@add'])->middleware('can:manage,App\User');
    Route::delete('/users/{user}',['uses'=>'UserController@delete'])->middleware('can:manage,App\User');
    Route::get('/users/{user}/teams',['uses'=>'UserController@user_teams'])->middleware('can:view,user');
    Route::post('/users/{user}/permissions',['uses'=>'UserController@update_permissions'])->middleware('can:manage_permissions,App\User');;

    //** TEAMS **//
    Route::get('/teams',['uses'=>'TeamController@browse'])->middleware('can:browse,App\Team');
    Route::get('/teams/{team}',['uses'=>'TeamController@read'])->middleware('can:view,team');
    Route::put('/teams/{team}',['uses'=>'TeamController@edit'])->middleware('can:manage,team');
    Route::post('/teams',['uses'=>'TeamController@add'])->middleware('can:manage,App\Team');
    Route::delete('/teams/{team}',['uses'=>'TeamController@delete'])->middleware('can:manage,team');

    Route::get('/teams/{team}/members',['uses'=>'TeamController@list_members'])->middleware('can:manage,team');
    Route::post('/teams/{team}/members/{user}',['uses'=>'TeamController@add_member'])->middleware('can:manage,team');
    Route::delete('/teams/{team}/members/{user_id}',['uses'=>'TeamController@remove_member'])->middleware('can:manage,team');
    Route::put('/teams/{team}/members/{user}',['uses'=>'TeamController@update_member'])->middleware('can:manage,team');
});