<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

// @var RouteCollection $routes
$routes->get('dashboard', 'Dashboard::index');
$routes->get('activites', 'Dashboard::activities');
$routes->get('coros/connect', 'Coros::connect');
$routes->get('coros/callback', 'Coros::callback');
$routes->post('coros/sync', 'Coros::sync');
