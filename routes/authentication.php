<?php

Route::get('/login', ['as'=>'wayf_login','uses' => 'AuthSaml2Controller@wayf']);
Route::any('/idp/demo', ['uses' => 'AuthDemoController@list']);
Route::get('/idp/google', 'AuthSaml2Controller@google_redirect');
Route::get('/idp/google/callback', 'AuthSaml2Controller@google_callback');
Route::get('/logout', ['as' => 'saml_logout','uses' => 'AuthSaml2Controller@logout']);

/* SAML Stuff */
Route::group(['prefix' => '/saml2','middleware' => ['saml']], function () {
    Route::get('/metadata',['as' => 'saml_metadata','uses' => 'AuthSaml2Controller@metadata']);
    Route::post('/acs',['as' => 'saml_acs','uses' => 'AuthSaml2Controller@acs']);
    Route::get('/sls',['as' => 'saml_sls','uses' => 'AuthSaml2Controller@sls']);
    Route::get('/wayf/{site}', ['as' => 'saml_wayf','uses' => 'AuthSaml2Controller@wayfcallback']);
});

/* API STUFF */
Route::group(['prefix' => 'api','middleware'=>['no.save.session']], function () use ($router) {
    Route::get('/idps',['uses'=>'AuthSaml2Controller@idps']);
});