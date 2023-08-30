<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('account', static function($routes) {
    service('auth')->routes($routes);
});

$routes->get('/dashboard', 'Dashboard::index' );

//language route
//filter
//https://codeigniter.com/user_guide/incoming/routing.html#applying-filters
//https://codeigniter.com/user_guide/incoming/filters.html?highlight=filter
$routes->get('lang/{locale}', 'Language::index',['filter' => 'afterlangchange']);


