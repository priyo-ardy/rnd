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
        $routes->get('data-seed', 'MasterData\CommonData\Customer\Customer::dataSeed');
    });

    // Master data satuan
    $routes->group('/satuan', static function ($routes) {
        $routes->get('', 'MasterData\CommonData\Satuan\Satuan::index');
        $routes->post('save', 'MasterData\CommonData\Satuan\Satuan::saveData');
        $routes->post('edit', 'MasterData\CommonData\Satuan\Satuan::getData');
        $routes->post('update', 'MasterData\CommonData\Satuan\Satuan::updateData');
        $routes->post('delete', 'MasterData\CommonData\Satuan\Satuan::deleteData');
        $routes->get('export', 'MasterData\CommonData\Satuan\Satuan::exportData');
        $routes->post('table', 'MasterData\CommonData\Satuan\Satuan::loadTable');
        $routes->get('data-seed', 'MasterData\CommonData\Satuan\Satuan::dataSeed');
    });

    // Production routes
    $routes->group('/routes', static function ($routes) {
        $routes->get('', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::index');
        $routes->post('save', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::saveData');
        $routes->post('edit', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::getData');
        $routes->post('update', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::updateData');
        $routes->post('delete', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::deleteData');
        $routes->get('export', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::exportData');
        $routes->post('table', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::loadTable');
        $routes->get('data-seed', 'MasterData\CommonData\ProductionRoutes\ProductionRoutes::dataSeed');
    });

    // Material Category
    $routes->group('/material_category', static function ($routes) {
        $routes->get('', 'MasterData\CommonData\MaterialCategory\MaterialCategory::index');
        $routes->post('save', 'MasterData\CommonData\MaterialCategory\MaterialCategory::saveData');
        $routes->post('edit', 'MasterData\CommonData\MaterialCategory\MaterialCategory::getData');
        $routes->post('update', 'MasterData\CommonData\MaterialCategory\MaterialCategory::updateData');
        $routes->post('delete', 'MasterData\CommonData\MaterialCategory\MaterialCategory::deleteData');
        $routes->get('export', 'MasterData\CommonData\MaterialCategory\MaterialCategory::exportData');
        $routes->post('table', 'MasterData\CommonData\MaterialCategory\MaterialCategory::loadTable');
        $routes->get('data-seed', 'MasterData\CommonData\MaterialCategory\MaterialCategory::dataSeed');
    });

    // Workshop
    $routes->group('/workshop', static function ($routes) {
        $routes->get('', 'MasterData\CommonData\Workshop\Workshop::index');
        $routes->post('save', 'MasterData\CommonData\Workshop\Workshop::saveData');
        $routes->post('edit', 'MasterData\CommonData\Workshop\Workshop::getData');
        $routes->post('update', 'MasterData\CommonData\Workshop\Workshop::updateData');
        $routes->post('delete', 'MasterData\CommonData\Workshop\Workshop::deleteData');
        $routes->get('export', 'MasterData\CommonData\Workshop\Workshop::exportData');
        $routes->post('table', 'MasterData\CommonData\Workshop\Workshop::loadTable');
        $routes->get('data-seed', 'MasterData\CommonData\Workshop\Workshop::dataSeed');
    });

    // Material
    $routes->group('/material', static function ($routes) {
        $routes->get('', 'MasterData\CommonData\Material\Material::index');
        $routes->get('add', 'MasterData\CommonData\Material\Material::addData');
        $routes->post('save', 'MasterData\CommonData\Material\Material::saveData');
        $routes->get('show/(:any)', 'MasterData\CommonData\Material\Material::getData/$1');
        $routes->post('update', 'MasterData\CommonData\Material\Material::updateData');
        $routes->post('delete', 'MasterData\CommonData\Material\Material::deleteData');
        $routes->get('export', 'MasterData\CommonData\Material\Material::exportData');
        $routes->post('table', 'MasterData\CommonData\Material\Material::loadTable');
        $routes->get('data-seed', 'MasterData\CommonData\Material\Material::dataSeed');
        $routes->post('check_code', 'MasterData\CommonData\Material\Material::cekMaterialCode');
        $routes->post('update_code', 'MasterData\CommonData\Material\Material::changeMaterialCode');
        $routes->post('prev', 'MasterData\CommonData\Material\Material::prevData');
        $routes->post('next', 'MasterData\CommonData\Material\Material::nextData');
    });
});
