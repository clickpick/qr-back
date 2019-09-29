<?php

Route::post('auth', 'MeController@me');

Route::get('projects/{project}/user-project-key', 'ProjectController@getUserProjectKey');

Route::get('projects/{project}/activated-project-keys', 'ProjectController@getUserActivatedProjectKeys');
Route::post('projects/{project}/activate-project-key', 'ProjectController@activateProjectKey');
Route::get('projects/{project}/project-facts', 'ProjectController@getFacts');

Route::get('active-project', 'ProjectController@getActive');

Route::post('notifications', 'MeController@setNotificationsAreEnabled');
