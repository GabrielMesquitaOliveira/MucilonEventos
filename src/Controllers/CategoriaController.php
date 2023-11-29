<?php

namespace App\Controllers;

use App\Models\Categoria;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoriaController
{
    private function verificarToken(Request $request, array $scopes): bool
    {
        $token = $request->getAttribute("token");

        return array_intersect($scopes, $token["scope"]) !== [];
    }

    public function criarCategoria(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';

        $message = Categoria::criarCategoria($nome);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obterCategoria(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $categoria = Categoria::buscarCategoriaId($id);

        $response->getBody()->write(json_encode($categoria));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function atualizarCategoria(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $descricao = $data['descricao'] ?? '';

        $message = Categoria::atualizarCategoria($id, $nome, $descricao);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function excluirCategoria(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["delete"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $message = Categoria::excluirCategoria($id);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listarCategorias(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $categorias = Categoria::listarCategorias();

        $response->getBody()->write(json_encode($categorias));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarCategoriaPorNome(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $nome = $args['nome'];
        $categorias = Categoria::buscarCategoria($nome);

        $response->getBody()->write(json_encode($categorias));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
