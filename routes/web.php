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
    Route::get('/', ['uses'=>'AppController@home']);
    Route::get('/team/{team_id}', ['uses'=>'AppController@getViewer']);
    Route::get('/admin/teams/{team_id}/configuration', ['uses'=>'AppController@getTeamConfig']);
    Route::get('/admin/scenarios/{scenario_id}/configuration', ['uses'=>'AppController@getScenarioConfig']);
    Route::get('/admin/teams/{team?}/{type?}', ['uses'=>'AdminController@admin_teams']);
    Route::get('/admin/{page?}', ['uses'=>'AdminController@admin']);
});

Route::get('/login', ['as'=>'wayf_login','uses' => 'Saml2Controller@wayf']);
Route::any('/idp/demo', ['uses' => 'DemoController@list']);
Route::get('/idp/google', 'Saml2Controller@google_redirect');
Route::get('/idp/google/callback', 'Saml2Controller@google_callback');
Route::get('/logout', ['as' => 'saml_logout','uses' => 'Saml2Controller@logout']);
Route::get('/health', ['as'=>'health_check','uses' => 'Saml2Controller@health']);
/* SAML Stuff */
Route::group(['prefix' => '/saml2','middleware' => ['saml']], function () {
    Route::get('/metadata',['as' => 'saml_metadata','uses' => 'Saml2Controller@metadata']);
    Route::post('/acs',['as' => 'saml_acs','uses' => 'Saml2Controller@acs']);
    Route::get('/sls',['as' => 'saml_sls','uses' => 'Saml2Controller@sls']);
    Route::get('/wayf/{site}', ['as' => 'saml_wayf','uses' => 'Saml2Controller@wayfcallback']);
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
    Route::get('/teams/{team}/activity/{last_activity_id?}',['uses'=>'TeamController@activity']);
    Route::put('/teams/{team}',['uses'=>'TeamController@edit'])->middleware('can:manage,team');
    Route::post('/teams',['uses'=>'TeamController@add'])->middleware('can:manage,App\Team');
    Route::delete('/teams/{team}',['uses'=>'TeamController@delete'])->middleware('can:manage,team');

    Route::get('/teams/{team}/members',['uses'=>'TeamController@list_members'])->middleware('can:manage,team');
    Route::post('/teams/{team}/members/{user}',['uses'=>'TeamController@add_member'])->middleware('can:manage,team');
    Route::delete('/teams/{team}/members/{user_id}',['uses'=>'TeamController@remove_member'])->middleware('can:manage,team');
    Route::put('/teams/{team}/members/{user}',['uses'=>'TeamController@update_member'])->middleware('can:manage,team');

    Route::get('/teams/{team}/messages',['uses'=>'TeamController@list_messages'])->middleware('can:view,team');
    Route::post('/teams/{team}/messages/',['uses'=>'TeamController@add_message'])->middleware('can:view,team');
    Route::delete('/teams/{team}/messages/{message}',['uses'=>'TeamController@remove_message'])->middleware('can:manage,team');

    Route::get('/teams/{team}/notes',['uses'=>'TeamController@list_notes'])->middleware('can:view,team');
    Route::post('/teams/{team}/notes/',['uses'=>'TeamController@add_note'])->middleware('can:manage,team');
    Route::delete('/teams/{team}/notes/{note}',['uses'=>'TeamController@remove_note'])->middleware('can:manage,team');

    Route::get('/teams/{team}/activity',['uses'=>'TeamController@list_activity_log'])->middleware('can:view,team');
    Route::post('/teams/{team}/activity/',['uses'=>'TeamController@add_activity_log'])->middleware('can:manage,team');

    Route::get('/teams/{team}/scenario_logs',['uses'=>'TeamController@list_scenario_logs'])->middleware('can:view,team');
    Route::post('/teams/{team}/scenario_logs/{user?}',['uses'=>'TeamController@add_scenario_log'])->middleware('can:view,team');

    //** ROLES **//
    Route::get('/roles',['uses'=>'RoleController@browse']);
    Route::get('/roles/{role_id}',['uses'=>'RoleController@read']);

    //** SCENARIOS **//
    Route::get('/scenarios',['uses'=>'ScenarioController@browse']);
    Route::get('/scenarios/{scenario}',['uses'=>'ScenarioController@read']);
    Route::put('/scenarios/{scenario}',['uses'=>'ScenarioController@edit'])->middleware('can:manage,App\Scenario');
    Route::post('/scenarios',['uses'=>'ScenarioController@add'])->middleware('can:manage,App\Scenario');
    Route::delete('/scenarios/{scenario}',['uses'=>'ScenarioController@delete'])->middleware('can:manage,App\Scenario');

    //** Libraries **//
    Route::get('/library',['uses'=>'LibraryController@browse_all']);
    Route::get('/library/{library_type}',['uses'=>'LibraryController@browse']);
    Route::get('/library/{library_type}/{library}',['uses'=>'LibraryController@read']);
    Route::get('/library/{library_type}/{library}/img',['uses'=>'LibraryController@read_image']);
    Route::put('/library/{library_type}/{library}',['uses'=>'LibraryController@edit'])->middleware('can:manage,App\Library');
    Route::post('/library/{library_type}',['uses'=>'LibraryController@add'])->middleware('can:manage,App\Library');
    Route::delete('/library/{library_type}/{library}',['uses'=>'LibraryController@delete'])->middleware('can:manage,App\Library');

    Route::get('/idps',['uses'=>'Saml2Controller@idps']);
});