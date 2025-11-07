<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth\Auth::index');
$routes->post('/login', 'Auth\Auth::processLogin');
$routes->get('/forgot-password', 'Auth\Auth::forgotPassword');
$routes->post('reset', 'Auth\Auth::resetPassword');
$routes->get('kirim-email', 'Test\Email\Email::sendTest');
$routes->get('test-email', 'Test\Email\Email::testConnection');
$routes->get('logout', 'Auth\Auth::logOut');

// Routes with auth filter
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('/dashboard', 'Dashboard\Dashboard::index');

    // Master Data Customer
    $routes->group('/customer', static function ($routes) {
        $routes->get('', 'MasterData\CommonData\Customer\Customer::index');
        $routes->get('add', 'MasterData\CommonData\Customer\Customer::addData');
        $routes->post('save', 'MasterData\CommonData\Customer\Customer::saveData');
        $routes->get('show/(:any)', 'MasterData\CommonData\Customer\Customer::showData/$1');
        $routes->post('update', 'MasterData\CommonData\Customer\Customer::updateData');
        $routes->post('delete', 'MasterData\CommonData\Customer\Customer::deleteData');
        $routes->post('prev', 'MasterData\CommonData\Customer\Customer::prevData');
        $routes->post('next', 'MasterData\CommonData\Customer\Customer::nextData');
        $routes->get('export', 'MasterData\CommonData\Customer\Customer::exportData');
        $routes->post('table', 'MasterData\CommonData\Customer\Customer::loadTable');
    });
});
