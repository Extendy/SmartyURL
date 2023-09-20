<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('account', static function ($routes) {
    service('auth')->routes($routes);
    $routes->get('changepwd', 'Account::changepwd');
    $routes->post('changepwd', 'Account::changepwdAction');
});

$routes->get('/dashboard', 'Dashboard::index');

// URL
$routes->group('url', static function ($routes) {
    $routes->get('/', 'Url::index');
    $routes->get('new', 'Url::new');
    $routes->post('new', 'Url::newAction');
});

// URL Go
$routes->get('go/(:any)', 'Go::go/$1');

// testing , must be removed after test
$routes->get('/url/none', 'Url::none');
$routes->get('/tests/1', 'Tests::index');

// language route
// filter
// https://codeigniter.com/user_guide/incoming/routing.html#applying-filters
// https://codeigniter.com/user_guide/incoming/filters.html?highlight=filter
$routes->get('lang/{locale}', 'Language::index', ['filter' => 'afterlangchange']);
