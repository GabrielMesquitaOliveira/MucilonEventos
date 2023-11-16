<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Local;

$app->group('/locais', function (Group $group) {

    $group->get('', function (Request $request, Response $response) {
        $locais = Local::listarLocais();
        $response->getBody()->write(json_encode($locais));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $local = Local::buscarLocalId($id);

        if ($local) {
            $response->getBody()->write(json_encode($local->fetch_assoc()));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Local não encontrado']));
            $response = $response->withStatus(404);
        }

        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $capacidade = $data['capacidade'] ?? '';
        $localidade = $data['localidade'] ?? '';
        $area = $data['area'] ?? '';

        $result = Local::criarLocal($capacidade, $localidade, $area);

        $response->getBody()->write(json_encode(['message' => $result]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $result = Local::excluirLocal($id);

        $response->getBody()->write(json_encode(['message' => $result]));
        return $response->withHeader('Content-Type', 'application/json');
    });

});
