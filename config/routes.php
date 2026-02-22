<?php

use App\Controllers\ProjectController;
use App\Router;

/** @var Router $router */

$router->get('/api/projects', [ProjectController::class, 'index']);
$router->get('/api/projects/{id}', [ProjectController::class, 'view']);
$router->post('/api/projects', [ProjectController::class, 'store']);
$router->put('/api/projects/{id}', [ProjectController::class, 'update']);
$router->delete('/api/projects/{id}', [ProjectController::class, 'delete']);
