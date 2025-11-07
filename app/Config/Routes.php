<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth\Auth::index');
$routes->post('/login', 'Auth\Auth::processLogin');
$routes->get('/forgot-password', 'Auth\Auth::forgotPassword');
$routes->post('reset', 'Auth\Auth::resetPassword');

// Routes with auth filter
$routes->group('', ['filter' => 'auth'], static function ($routes) {});
