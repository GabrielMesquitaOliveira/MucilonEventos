<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Organizador;

$app->group('/organizadores', function (Group $group) {

    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';

        $message = Organizador::criarOrganizador($nome, $email);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $organizador = Organizador::buscarOrganizadorId($id);

        $response->getBody()->write(json_encode($organizador));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $message = Organizador::excluirOrganizador($id);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('', function (Request $request, Response $response) {
        $organizadores = Organizador::buscarOrganizador('');

        $response->getBody()->write(json_encode($organizadores));
        return $response->withHeader('Content-Type', 'application/json');
    });

});
