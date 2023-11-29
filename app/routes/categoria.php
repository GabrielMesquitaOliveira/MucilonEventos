<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Categoria do namespace App\models também é importada para ser usada nas rotas.
*/use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\CategoriaController;

$app->group('/categorias', function (Group $group) {

    $group->post('/create', [CategoriaController::class, 'criarCategoria']);

    $group->get('/{id}', [CategoriaController::class, 'obterCategoria']);

    $group->put('/{id}', [CategoriaController::class, 'atualizarCategoria']);

    $group->delete('/{id}', [CategoriaController::class, 'excluirCategoria']);

    $group->get('', [CategoriaController::class, 'listarCategorias']);

    $group->get('/buscar/{nome}', [CategoriaController::class, 'buscarCategoriaPorNome']);
});
