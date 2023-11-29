<?php

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\OrganizadorController;

$app->group('/organizadores', function (Group $group) {

    $group->post('/create', [OrganizadorController::class, 'criarOrganizador']);

    $group->get('/{organizador}', [OrganizadorController::class, 'buscarOrganizador']);

    $group->get('/info/{organizador}', [OrganizadorController::class, 'buscarOrganizadorId']);

    $group->delete('/{organizador}', [OrganizadorController::class, 'excluirOrganizador']);
    
});
