<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index', ['filter' => 'session']);

$routes->group('account', static function ($routes) {
    service('auth')->routes($routes);
    $routes->get('changepwd', 'Account::changepwd', ['filter' => 'session']);
    $routes->post('changepwd', 'Account::changepwdAction', ['filter' => 'session']);
});

$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'session']);

// URL
$routes->group('url', static function ($routes) {
    $routes->get('/', 'Url::index', ['filter' => 'session']);
    $routes->get('user/(:num)', 'Url::listuserurls/$1', ['filter' => 'session']); // list user urls
    $routes->get('tag/(:num)', 'Url::listtagurls/$1', ['filter' => 'session']); // list tag urls
    $routes->get('listdata', 'Url::listData', ['filter' => 'session']); // json list urls
    $routes->get('view/(:num)', 'Url::view/$1', ['filter' => 'session']);
    $routes->get('new', 'Url::new', ['filter' => 'session']);
    $routes->post('new', 'Url::newAction', ['filter' => 'session']);
    $routes->get('edit/(:num)', 'Url::edit/$1', ['filter' => 'session']);
    $routes->post('edit/(:num)', 'Url::editAction/$1', ['filter' => 'session']);
    $routes->get('hits/(:num)', 'Url::hitslist/$1', ['filter' => 'session']);
    $routes->get('hitslistdata/(:num)', 'Url::hitslistData/$1', ['filter' => 'session']); // json hits list for url
    $routes->get('qrcode/(:num)', 'Url::generateQRCode/$1', ['filter' => 'session']);
    $routes->post('del/(:num)', 'Url::delUrl/$1', ['filter' => 'session']); // json del url
});

// Users
$routes->group('users', static function ($routes) {
    $routes->get('/', 'Users::index', ['filter' => 'session']);
});

// language route
// filter
// https://codeigniter.com/user_guide/incoming/routing.html#applying-filters
// https://codeigniter.com/user_guide/incoming/filters.html?highlight=filter
$routes->get('lang/{locale}', 'Language::index', ['filter' => 'afterlangchange']);

// Assist
$routes->get('assist/newurl', 'Assist::getAddNewUrlJsAssist', ['filter' => 'session']);
$routes->get('assist/smartyurl', 'Assist::getSmartyUrlGlobalJsAssist');
$routes->get('assist/listurls', 'Assist::getListUrlsJsAssist', ['filter' => 'session']);

// URL redirects
// with go route
$routes->get('go/(:any)', 'Go::go/$1', ['filter' => 'webratelimit']);
// Route any undefined request to Go Controller.
$routes->get('(:any)', 'Go::go/$1', ['filter' => 'webratelimit']);

// testing , @TODO must be removed after testing before any production release
$routes->get('/url/none', 'Url::none');
$routes->get('/tests/1', 'Tests::index');
$routes->get('/tests/testCountry', 'Tests::testCountry');
