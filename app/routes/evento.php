<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Evento do namespace App\models também é importada para ser usada nas rotas.
*/
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\EventoController;

// ... outras rotas ...

// Grupo de rotas para manipulação de dados relacionados a eventos.
$app->group('/eventos', function (Group $group) {
    $group->post('/create', [EventoController::class, 'criarEvento']);
    $group->get('/{id}', [EventoController::class, 'obterEvento']);
    $group->put('/{id}', [EventoController::class, 'atualizarEvento']);
    $group->delete('/{id}', [EventoController::class, 'excluirEvento']);
    $group->get('', [EventoController::class, 'listarEventos']);
});
