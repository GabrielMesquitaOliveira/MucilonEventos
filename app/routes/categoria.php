<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Categoria;

$app->group('/categorias', function (Group $group) {

    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';

        $message = Categoria::criarCategoria($nome);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/{categoria}', function (Request $request, Response $response, $args) {
        $categoria = $args['categoria'];
        $categorias = Categoria::buscarCategoria($categoria);

        $response->getBody()->write(json_encode($categorias));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{categoria}', function (Request $request, Response $response, $args) {
        $categoria = $args['categoria'];
        $message = Categoria::excluirCategoria($categoria);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

});
