<?php

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\ClienteController;

$app->group('/api', function (Group $group) {
    $group->post('/token', [ClienteController::class, 'autenticarCliente']);
});
