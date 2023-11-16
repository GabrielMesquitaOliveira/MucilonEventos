<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Artista;

$app->group('/artistas', function (Group $group) {

    $group->get('', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode(Artista::buscarArtistas()));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $artista = Artista::obterArtista($id);

        if ($artista) {
            $response->getBody()->write(json_encode($artista));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Artista não encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

    $group->post('', function (Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';

        $resultado = Artista::criarArtista($nome);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/buscar/{nome}', function (Request $request, Response $response, $args) {
        $nome = $args['nome'];
        $artistas = Artista::buscarArtistaPorNome($nome);

        $response->getBody()->write(json_encode($artistas));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $resultado = Artista::excluirArtista($id);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });
});