<?php

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\IngressoController;

$app->group('/ingressos', function (Group $group) {

    $group->post('/create', [IngressoController::class, 'criarIngresso']);

    $group->get('/{id}', [IngressoController::class, 'buscarIngressos']);

    $group->put('/{id}', [IngressoController::class, 'atualizarIngresso']);

    $group->delete('/{id}', [IngressoController::class, 'excluirIngresso']);

});
