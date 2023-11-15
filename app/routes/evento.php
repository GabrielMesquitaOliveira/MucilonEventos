<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Evento;

$app->group('/eventos', function (Group $group) {

    $group->get('', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode(Evento::listarEventos()));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $evento = Evento::obterEvento($id);

        if ($evento) {
            $response->getBody()->write(json_encode($evento));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Evento não encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

    $group->post('', function (Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $nomeEvento = $data['nome_evento'] ?? '';
        $dataEvento = $data['data_evento'] ?? '';
        $local = $data['local'] ?? '';
        $descricao = $data['descricao'] ?? '';
        $artistaId = $data['artista_id'] ?? 0;
        $categoriaId = $data['categoria_id'] ?? 0;
        $eventoId = $data['evento_id'] ?? 0;
        $localId = $data['local_id'] ?? 0;
        $contador = $data['contador'] ?? 0;

        $resultado = Evento::criarEvento($nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->put('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
        $nomeEvento = $data['nome_evento'] ?? '';
        $dataEvento = $data['data_evento'] ?? '';
        $local = $data['local'] ?? '';
        $descricao = $data['descricao'] ?? '';
        $artistaId = $data['artista_id'] ?? 0;
        $categoriaId = $data['categoria_id'] ?? 0;
        $eventoId = $data['evento_id'] ?? 0;
        $localId = $data['local_id'] ?? 0;
        $contador = $data['contador'] ?? 0;

        $resultado = Evento::atualizarEvento($id, $nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $resultado = Evento::excluirEvento($id);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });
});
