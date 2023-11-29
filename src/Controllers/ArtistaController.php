<?php

namespace App\Controllers;

use App\Models\Artista;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ArtistaController
{
    private function verificarToken(Request $request, array $scopes): bool
    {
        $token = $request->getAttribute("token");

        return array_intersect($scopes, $token["scope"]) !== [];
    }

    public function criarArtista(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $descricao = $data['descricao'] ?? '';

        $message = Artista::criarArtista($nome);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obterArtista(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $artista = Artista::obterArtista($id);

        $response->getBody()->write(json_encode($artista));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function atualizarArtista(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $descricao = $data['descricao'] ?? '';

        $message = Artista::atualizarArtista($id, $nome, $descricao);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function excluirArtista(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["delete"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $message = Artista::excluirArtista($id);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listarArtistas(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $artistas = Artista::listarArtistas();

        $response->getBody()->write(json_encode($artistas));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarArtistaPorNome(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $nome = $args['nome'];
        $artistas = Artista::buscarArtistaPorNome($nome);

        $response->getBody()->write(json_encode($artistas));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
