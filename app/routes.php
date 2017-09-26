<?php

$router->get('',             'PagesController@guest');
$router->get('login',        'PagesController@login');
$router->get('first-login',  'PagesController@firstLogin');
$router->get('logout',       'PagesController@logout');
$router->get('signup',       'PagesController@signup');
$router->get('add-project',  'PagesController@addProject');
$router->get('add-task',     'PagesController@addTask');

$router->post('signup',      'ActionController@signup');
$router->post('login',       'ActionController@login');
$router->post('add-project', 'ActionController@addProject');
$router->post('add-task',    'ActionController@addTask');
$router->get('search',       'ActionController@search');
$router->get('complete',     'ActionController@complete');
