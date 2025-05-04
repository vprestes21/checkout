<?php
// If this file doesn't exist, create it or add these routes to your existing routing configuration

// Dashboard routes
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/index', 'DashboardController@index');
$router->get('/dashboard/utm', 'DashboardController@utm');

// ...existing routes...
