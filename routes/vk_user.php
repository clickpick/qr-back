<?php

Route::post('auth', 'MeController@me');

Route::post('post-story', 'MeController@postStory');

Route::get('projects/{project}/user-project-key', 'ProjectController@getUserProjectKey');

Route::get('projects/{project}/activated-project-keys', 'ProjectController@getUserActivatedProjectKeys');
Route::post('projects/{project}/activate-project-key', 'ProjectController@activateProjectKey');
Route::get('projects/{project}/project-facts', 'ProjectController@getFacts');
Route::get('projects/{project}/winners', 'ProjectController@getWinners');

Route::get('active-project', 'ProjectController@getActive');

Route::post('notifications', 'MeController@setNotificationsAreEnabled');

Route::post('vk-pay-order', 'VkPayController@makeOrder');
Route::post('vk-pay-cheat-order', 'VkPayController@makeCheatOrder');

Route::post('activate-cheat','AvailableCheatController@activateCheat');

Route::post('request-funding', 'Admin\ProjectController@store');

