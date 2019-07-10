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
    Route::get('/', ['uses'=>'AppController@getViewerApp']);
    Route::get('/admin', ['uses'=>'AppController@getAdminApp']);
});
Route::get('/logout', ['as' => 'saml_logout','uses' => 'Saml2Controller@logout']);
/* SAML Stuff */
Route::group(['prefix' => 'saml2','middleware' => ['saml']], function () {
    Route::get('/metadata',['as' => 'saml_metadata','uses' => 'Saml2Controller@metadata']);
    Route::post('/acs',['as' => 'saml_acs','uses' => 'Saml2Controller@acs']);
    Route::get('/sls',['as' => 'saml_sls','uses' => 'Saml2Controller@sls']);
    Route::get('/wayf/{site?}', ['as' => 'saml_wayf','uses' => 'Saml2Controller@wayf']);
});

/* API STUFF */

Route::group(['prefix' => 'api','middleware'=>['no.save.session']], function () use ($router) {
    //** APP Init **//
    Route::post('/init/{app_id}',['uses'=>'AppController@initData']);

    //** USERS **//
    Route::get('/users',['uses'=>'UserController@browse']);
    Route::get('/users/{user_id}',['uses'=>'UserController@read']);
    Route::put('/users/{user_id}',['uses'=>'UserController@edit']);
    Route::post('/users',['uses'=>'UserController@add']);
    Route::delete('/users/{user_id}',['uses'=>'UserController@delete']);
    Route::get('/users/{user_id}/teams',['uses'=>'UserController@user_teams']);

    //** TEAMS **//
    Route::get('/teams',['uses'=>'TeamController@browse']);
    Route::get('/teams/{team_id}',['uses'=>'TeamController@read']);
    Route::get('/teams/{team_id}/activity/{last_activity_id?}',['uses'=>'TeamController@activity']);
    Route::put('/teams/{team_id}',['uses'=>'TeamController@edit']);
    Route::post('/teams',['uses'=>'TeamController@add']);
    Route::delete('/teams/{team_id}',['uses'=>'TeamController@delete']);

    Route::get('/teams/{team_id}/members',['uses'=>'TeamController@list_members']);
    Route::post('/teams/{team_id}/members/{user_id}',['uses'=>'TeamController@add_member']);
    Route::delete('/teams/{team_id}/members/{user_id}',['uses'=>'TeamController@remove_member']);

    Route::get('/teams/{team_id}/messages',['uses'=>'TeamController@list_messages']);
    Route::post('/teams/{team_id}/messages/{user_id?}',['uses'=>'TeamController@add_message']);
    Route::delete('/teams/{team_id}/messages/{message_id}',['uses'=>'TeamController@remove_message']);

    Route::get('/teams/{team_id}/notes',['uses'=>'TeamController@list_notes']);
    Route::post('/teams/{team_id}/notes/{user_id}',['uses'=>'TeamController@add_note']);
    Route::delete('/teams/{team_id}/notes/{note_id}',['uses'=>'TeamController@remove_note']);

    Route::get('/teams/{team_id}/scenario_logs',['uses'=>'TeamController@list_scenario_logs']);
    Route::post('/teams/{team_id}/scenario_logs/{user_id?}',['uses'=>'TeamController@add_scenario_log']);

    //** ROLES **//
    Route::get('/roles',['uses'=>'RoleController@browse']);
    Route::get('/roles/{user_id}',['uses'=>'RoleController@read']);
    Route::put('/roles/{user_id}',['uses'=>'RoleController@edit']);
    Route::post('/roles',['uses'=>'RoleController@add']);
    Route::delete('/roles/{user_id}',['uses'=>'RoleController@delete']);

    //** SCENARIOS **//
    Route::get('/scenarios',['uses'=>'ScenarioController@browse']);
    Route::get('/scenarios/{scenario_id}',['uses'=>'ScenarioController@read']);
    Route::put('/scenarios/{scenario_id}',['uses'=>'ScenarioController@edit']);
    Route::post('/scenarios',['uses'=>'ScenarioController@add']);
    Route::delete('/scenarios/{scenario_id}',['uses'=>'ScenarioController@delete']);

    //** Libraries **//
    Route::get('/library',['uses'=>'LibraryController@browse_all']);
    Route::get('/library/{library_type}',['uses'=>'LibraryController@browse']);
    Route::get('/library/{library_type}/{library_id}',['uses'=>'LibraryController@read']);
    Route::put('/library/{library_type}/{library_id}',['uses'=>'LibraryController@edit']);
    Route::post('/library/{library_type}',['uses'=>'LibraryController@add']);
    Route::delete('/library/{library_type}/{library_id}',['uses'=>'LibraryController@delete']);
});