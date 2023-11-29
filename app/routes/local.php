<?php

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\LocalController;

$app->group('/locais', function (Group $group) {

    // Endpoint para criar um local
    $group->post('/create', [LocalController::class, 'criarLocal']);

    // Endpoint para buscar locais por área
    $group->get('/buscar/{local}', [LocalController::class, 'buscarLocal']);

    // Endpoint para buscar informações de um local por ID
    $group->get('/{local}', [LocalController::class, 'buscarLocalId']);

    // Endpoint para excluir um local
    $group->delete('/{local}', [LocalController::class, 'excluirLocal']);

    // Endpoint para listar todos os locais
    $group->get('', [LocalController::class, 'listarLocais']);
    
});
