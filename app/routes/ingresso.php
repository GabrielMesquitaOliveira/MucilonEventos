<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Ingresso;

$app->group('/ingressos', function (Group $group) {

    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $idCliente = $data['idCliente'] ?? '';
        $idEvento = $data['idEvento'] ?? '';
        $valor = $data['valor'] ?? '';
        $formaPagamento = $data['formaPagamento'] ?? '';
        $quantidade = $data['quantidade'] ?? '';

        Ingresso::criarIngresso($idCliente, $idEvento, $valor, $formaPagamento, $quantidade);

        $response->getBody()->write(json_encode(['message' => 'Ingresso(s) criado(s) com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/{evento}', function (Request $request, Response $response, $args) {
        $evento = $args['evento'];
        $ingressos = Ingresso::buscarIngressos($evento);

        $response->getBody()->write(json_encode($ingressos));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{ingresso}', function (Request $request, Response $response, $args) {
        $ingresso = $args['ingresso'];
        Ingresso::excluirIngresso($ingresso);

        $response->getBody()->write(json_encode(['message' => 'Ingresso estornado com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->put('/{ingresso}', function (Request $request, Response $response, $args) {
        $ingresso = $args['ingresso'];
        $data = $request->getParsedBody();
        $idCliente = $data['idCliente'] ?? '';
        $idEvento = $data['idEvento'] ?? '';
        $valor = $data['valor'] ?? '';
        $formaPagamento = $data['formaPagamento'] ?? '';

        Ingresso::atualizarIngresso($ingresso, $idCliente, $idEvento, $valor, $formaPagamento);

        $response->getBody()->write(json_encode(['message' => 'Ingresso atualizado com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    });

});
