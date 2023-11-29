<?php

namespace App\Controllers;

use App\Models\Organizador;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OrganizadorController
{
    private function verificarToken(Request $request, array $scopes): bool
    {
        $token = $request->getAttribute("token");

        return array_intersect($scopes, $token["scope"]) !== [];
    }

    public function criarOrganizador(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';

        $message = Organizador::criarOrganizador($nome, $email);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarOrganizador(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $organizador = $args['organizador'];
        $organizadores = Organizador::buscarOrganizador($organizador);

        $response->getBody()->write(json_encode($organizadores));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarOrganizadorId(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $organizador = $args['organizador'];
        $organizadorInfo = Organizador::buscarOrganizadorId($organizador);

        $response->getBody()->write(json_encode($organizadorInfo));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function excluirOrganizador(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["delete"])) {
            return $response->withStatus(401);
        }

        $organizador = $args['organizador'];
        $message = Organizador::excluirOrganizador($organizador);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}

