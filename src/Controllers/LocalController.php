<?php

namespace App\Controllers;

use App\Models\Local;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LocalController
{
    private function verificarToken(Request $request, array $scopes): bool
    {
        $token = $request->getAttribute("token");

        return array_intersect($scopes, $token["scope"]) !== [];
    }

    public function criarLocal(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $data = $request->getParsedBody();
        $capacidade = $data['capacidade'] ?? '';
        $localidade = $data['localidade'] ?? '';
        $area = $data['area'] ?? '';

        $message = Local::criarLocal($capacidade, $localidade, $area);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarLocal(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $local = $args['local'];
        $locais = Local::buscarLocal($local);

        $response->getBody()->write(json_encode($locais));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarLocalId(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $local = $args['local'];
        $localInfo = Local::buscarLocalId($local);

        $response->getBody()->write(json_encode($localInfo));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function excluirLocal(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["delete"])) {
            return $response->withStatus(401);
        }

        $local = $args['local'];
        $message = Local::excluirLocal($local);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listarLocais(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $locais = Local::listarLocais();

        $response->getBody()->write(json_encode($locais));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
