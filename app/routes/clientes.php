<?php

/**
 * Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
 *A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
 *A classe Cliente do namespace App\models também é importada para ser usada nas rotas.
 */

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\ClienteController;

$app->group('/clientes', function (Group $group) {

    $group->post('', [ClienteController::class, 'criarCliente']);

    $group->get('/{id}', [ClienteController::class, 'obterCliente']);

    $group->put('/{id}', [ClienteController::class, 'atualizarCliente']);

    $group->delete('/{id}', [ClienteController::class, 'excluirCliente']);

    $group->get('', [ClienteController::class, 'listarClientes']);

    // Rota para autenticação do cliente e obtenção de token JWT
    $group->post('/autenticar', [ClienteController::class, 'autenticarCliente']);
});
