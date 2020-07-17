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
Route::group(['prefix'=>'apps/epatient'], function () use ($router) {

    /* GENERIC STUFF */
    Route::group(['middleware'=>['saml2.auth']], function () use ($router) {
        Route::get('/', ['uses'=>'EPatientAppController@home']);
        Route::get('/team/{team_id}', ['uses'=>'EPatientAppController@getViewer']);
        Route::get('/admin/teams/{team_id}/configuration', ['uses'=>'EPatientAppController@getTeamConfig']);
        Route::get('/admin/scenarios/{scenario_id}/configuration', ['uses'=>'EPatientAppController@getScenarioConfig']);
        Route::get('/admin/teams/{team?}/{type?}', ['uses'=>'EPatientAdminController@admin_teams']);
        Route::get('/admin/{page?}', ['uses'=>'EPatientAdminController@admin']);
    });

    /* API STUFF */
    Route::group(['prefix' => 'api','middleware'=>['no.save.session']], function () use ($router) {
        //** TEAMS **//
        
        Route::get('/teams/{team}',['uses'=>'TeamController@read'])->middleware('can:view,team');
        Route::get('/teams/{team}/activity/{last_activity_id?}',['uses'=>'EPatientTeamController@activity']);
        Route::get('/teams/{team}/messages',['uses'=>'EPatientTeamController@list_messages'])->middleware('can:view,team');
        Route::post('/teams/{team}/messages/',['uses'=>'EPatientTeamController@add_message'])->middleware('can:view,team');
        Route::delete('/teams/{team}/messages/{message}',['uses'=>'EPatientTeamController@remove_message'])->middleware('can:manage,team');
        Route::get('/teams/{team}/notes',['uses'=>'EPatientTeamController@list_notes'])->middleware('can:view,team');
        Route::post('/teams/{team}/notes/',['uses'=>'EPatientTeamController@add_note'])->middleware('can:manage,team');
        Route::delete('/teams/{team}/notes/{note}',['uses'=>'EPatientTeamController@remove_note'])->middleware('can:manage,team');
        Route::get('/teams/{team}/activity',['uses'=>'EPatientTeamController@list_activity_log'])->middleware('can:view,team');
        Route::post('/teams/{team}/activity/',['uses'=>'EPatientTeamController@add_activity_log'])->middleware('can:manage,team');
        Route::get('/teams/{team}/scenario_logs',['uses'=>'EPatientTeamController@list_scenario_logs'])->middleware('can:view,team');
        Route::post('/teams/{team}/scenario_logs/{user?}',['uses'=>'EPatientTeamController@add_scenario_log'])->middleware('can:view,team');

        //** ROLES **//
        Route::get('/roles',['uses'=>'EPatientRoleController@browse']);
        Route::get('/roles/{role_id}',['uses'=>'EPatientRoleController@read']);

        //** SCENARIOS **//
        Route::get('/scenarios',['uses'=>'EPatientScenarioController@browse']);
        Route::get('/scenarios/{scenario}',['uses'=>'EPatientScenarioController@read']);
        Route::put('/scenarios/{scenario}',['uses'=>'EPatientScenarioController@edit'])->middleware('can:manage,App\Scenario');
        Route::post('/scenarios',['uses'=>'EPatientScenarioController@add'])->middleware('can:manage,App\Scenario');
        Route::delete('/scenarios/{scenario}',['uses'=>'EPatientScenarioController@delete'])->middleware('can:manage,App\Scenario');

        //** Libraries **//
        Route::get('/library',['uses'=>'EPatientLibraryController@browse_all']);
        Route::get('/library/{library_type}',['uses'=>'EPatientLibraryController@browse']);
        Route::get('/library/{library_type}/{library}',['uses'=>'EPatientLibraryController@read']);
        Route::get('/library/{library_type}/{library}/img',['uses'=>'EPatientLibraryController@read_image']);
        Route::put('/library/{library_type}/{library}',['uses'=>'EPatientLibraryController@edit'])->middleware('can:manage,App\Library');
        Route::post('/library/{library_type}',['uses'=>'EPatientLibraryController@add'])->middleware('can:manage,App\Library');
        Route::delete('/library/{library_type}/{library}',['uses'=>'EPatientLibraryController@delete'])->middleware('can:manage,App\Library');
    });
});