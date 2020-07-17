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
Route::group(['prefix'=>'app/epatient'], function () use ($router) {

    /* GENERIC STUFF */
    Route::group(['middleware'=>['saml2.auth']], function () use ($router) {
        Route::get('/', ['uses'=>'App\epatient\AppController@home']);
        Route::get('/team/{team_id}', ['uses'=>'App\epatient\AppController@getViewer']);
        Route::get('/admin/teams/{team_id}/configuration', ['uses'=>'App\epatient\AppController@getTeamConfig']);
        Route::get('/admin/scenarios/{scenario_id}/configuration', ['uses'=>'App\epatient\AppController@getScenarioConfig']);
        Route::get('/admin/teams/{team?}/{type?}', ['uses'=>'App\epatient\AdminController@admin_teams']);
        Route::get('/admin/{page?}', ['uses'=>'App\epatient\AdminController@admin']);
    });

    /* API STUFF */
    Route::group(['prefix' => 'api','middleware'=>['no.save.session']], function () use ($router) {
        //** TEAMS **//

        Route::get('/teams/{team}',['uses'=>'TeamController@read'])->middleware('can:view,team');
        Route::get('/teams/{team}/activity/{last_activity_id?}',['uses'=>'App\epatient\TeamController@activity']);
        Route::get('/teams/{team}/messages',['uses'=>'App\epatient\TeamController@list_messages'])->middleware('can:view,team');
        Route::post('/teams/{team}/messages/',['uses'=>'App\epatient\TeamController@add_message'])->middleware('can:view,team');
        Route::delete('/teams/{team}/messages/{message}',['uses'=>'App\epatient\TeamController@remove_message'])->middleware('can:manage,team');
        Route::get('/teams/{team}/notes',['uses'=>'App\epatient\TeamController@list_notes'])->middleware('can:view,team');
        Route::post('/teams/{team}/notes/',['uses'=>'App\epatient\TeamController@add_note'])->middleware('can:manage,team');
        Route::delete('/teams/{team}/notes/{note}',['uses'=>'App\epatient\TeamController@remove_note'])->middleware('can:manage,team');
        Route::get('/teams/{team}/activity',['uses'=>'App\epatient\TeamController@list_activity_log'])->middleware('can:view,team');
        Route::post('/teams/{team}/activity/',['uses'=>'App\epatient\TeamController@add_activity_log'])->middleware('can:manage,team');
        Route::get('/teams/{team}/scenario_logs',['uses'=>'App\epatient\TeamController@list_scenario_logs'])->middleware('can:view,team');
        Route::post('/teams/{team}/scenario_logs/{user?}',['uses'=>'App\epatient\TeamController@add_scenario_log'])->middleware('can:view,team');

        //** ROLES **//
        Route::get('/roles',['uses'=>'App\epatient\RoleController@browse']);
        Route::get('/roles/{role_id}',['uses'=>'App\epatient\RoleController@read']);

        //** SCENARIOS **//
        Route::get('/scenarios',['uses'=>'App\epatient\ScenarioController@browse']);
        Route::get('/scenarios/{scenario}',['uses'=>'App\epatient\ScenarioController@read']);
        Route::put('/scenarios/{scenario}',['uses'=>'App\epatient\ScenarioController@edit'])->middleware('can:manage,App\Scenario');
        Route::post('/scenarios',['uses'=>'App\epatient\ScenarioController@add'])->middleware('can:manage,App\Scenario');
        Route::delete('/scenarios/{scenario}',['uses'=>'App\epatient\ScenarioController@delete'])->middleware('can:manage,App\Scenario');

        //** Libraries **//
        Route::get('/library',['uses'=>'App\epatient\LibraryController@browse_all']);
        Route::get('/library/{library_type}',['uses'=>'App\epatient\LibraryController@browse']);
        Route::get('/library/{library_type}/{library}',['uses'=>'App\epatient\LibraryController@read']);
        Route::get('/library/{library_type}/{library}/img',['uses'=>'App\epatient\LibraryController@read_image']);
        Route::put('/library/{library_type}/{library}',['uses'=>'App\epatient\LibraryController@edit'])->middleware('can:manage,App\Library');
        Route::post('/library/{library_type}',['uses'=>'App\epatient\LibraryController@add'])->middleware('can:manage,App\Library');
        Route::delete('/library/{library_type}/{library}',['uses'=>'App\epatient\LibraryController@delete'])->middleware('can:manage,App\Library');
    });
});