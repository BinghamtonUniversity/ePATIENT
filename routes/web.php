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

$router->get('/', ['uses'=>'AppController@getViewerApp']);
$router->get('/admin', ['uses'=>'AppController@getAdminApp']);

$router->group(['prefix' => 'api'], function () use ($router) {
    //** APP Init **//
    $router->post('/init/{app_id}',['uses'=>'AppController@initData']);

    //** USERS **//
    $router->get('/users',['uses'=>'UserController@browse']);
    $router->get('/users/{user_id}',['uses'=>'UserController@read']);
    $router->put('/users/{user_id}',['uses'=>'UserController@edit']);
    $router->post('/users',['uses'=>'UserController@add']);
    $router->delete('/users/{user_id}',['uses'=>'UserController@delete']);
    $router->get('/users/{user_id}/teams',['uses'=>'UserController@user_teams']);

    //** TEAMS **//
    $router->get('/teams',['uses'=>'TeamController@browse']);
    $router->get('/teams/{team_id}',['uses'=>'TeamController@read']);
    $router->get('/teams/{team_id}/activity/{last_activity_id?}',['uses'=>'TeamController@activity']);
    $router->put('/teams/{team_id}',['uses'=>'TeamController@edit']);
    $router->post('/teams',['uses'=>'TeamController@add']);
    $router->delete('/teams/{team_id}',['uses'=>'TeamController@delete']);

    $router->get('/teams/{team_id}/members',['uses'=>'TeamController@list_members']);
    $router->post('/teams/{team_id}/members/{user_id}',['uses'=>'TeamController@add_member']);
    $router->delete('/teams/{team_id}/members/{user_id}',['uses'=>'TeamController@remove_member']);

    $router->get('/teams/{team_id}/messages',['uses'=>'TeamController@list_messages']);
    $router->post('/teams/{team_id}/messages/{user_id}',['uses'=>'TeamController@add_message']);
    $router->delete('/teams/{team_id}/messages/{user_id}',['uses'=>'TeamController@remove_message']);

    $router->get('/teams/{team_id}/notes',['uses'=>'TeamController@list_notes']);
    $router->post('/teams/{team_id}/notes/{user_id}',['uses'=>'TeamController@add_note']);
    $router->delete('/teams/{team_id}/notes/{user_id}',['uses'=>'TeamController@remove_note']);

    $router->get('/teams/{team_id}/scenario_logs',['uses'=>'TeamController@list_scenario_logs']);
    $router->post('/teams/{team_id}/scenario_logs/{user_id}',['uses'=>'TeamController@add_scenario_log']);

    //** ROLES **//
    $router->get('/roles',['uses'=>'RoleController@browse']);
    $router->get('/roles/{user_id}',['uses'=>'RoleController@read']);
    $router->put('/roles/{user_id}',['uses'=>'RoleController@edit']);
    $router->post('/roles',['uses'=>'RoleController@add']);
    $router->delete('/roles/{user_id}',['uses'=>'RoleController@delete']);

    //** SCENARIOS **//
    $router->get('/scenarios',['uses'=>'ScenarioController@browse']);
    $router->get('/scenarios/{scenario_id}',['uses'=>'ScenarioController@read']);
    $router->put('/scenarios/{scenario_id}',['uses'=>'ScenarioController@edit']);
    $router->post('/scenarios',['uses'=>'ScenarioController@add']);
    $router->delete('/scenarios/{scenario_id}',['uses'=>'ScenarioController@delete']);

    //** Libraries **//
    $router->get('/library',['uses'=>'LibraryController@browse_all']);
    $router->get('/library/{library_type}',['uses'=>'LibraryController@browse']);
    $router->get('/library/{library_type}/{library_id}',['uses'=>'LibraryController@read']);
    $router->put('/library/{library_type}/{library_id}',['uses'=>'LibraryController@edit']);
    $router->post('/library/{library_type}',['uses'=>'LibraryController@add']);
    $router->delete('/library/{library_type}/{library_id}',['uses'=>'LibraryController@delete']);

});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
