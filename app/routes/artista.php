<?php

/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Artista do namespace App\models também é importada para ser usada nas rotas.
*/

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\ArtistaController;

$app->group('/artistas', function (Group $group) {

    // Rotas para operações CRUD
    $group->post('/criar', [ArtistaController::class, 'criarArtista']);
    $group->get('/{id}', [ArtistaController::class, 'obterArtista']);
    $group->put('/{id}', [ArtistaController::class, 'atualizarArtista']);
    $group->delete('/{id}', [ArtistaController::class, 'excluirArtista']);
    $group->get('', [ArtistaController::class, 'listarArtistas']);

    // Rota adicional, por exemplo, para buscar artistas por nome
    $group->get('/buscar/{nome}', [ArtistaController::class, 'buscarArtistaPorNome']);
});

