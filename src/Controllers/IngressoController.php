<?php

namespace App\Controllers;

use App\Models\Ingresso;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IngressoController
{
    private function verificarToken(Request $request, array $scopes): bool
    {
        $token = $request->getAttribute("token");

        return array_intersect($scopes, $token["scope"]) !== [];
    }

    public function criarIngresso(Request $request, Response $response, array $args): Response
    {
        // ... (verificação do token)
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }
        $data = $request->getParsedBody();
        $idCliente = $data['cliente_id'] ?? 0;
        $idEvento = $data['evento_id'] ?? 0;
        $valor = $data['valor'] ?? 0;
        $formaPagamento = $data['forma_pagamento'] ?? '';
        $quantidade = $data['quantidade'] ?? 0;

        Ingresso::criarIngresso($idCliente, $idEvento, $valor, $formaPagamento, $quantidade);

        $response->getBody()->write(json_encode(['message' => 'Ingressos criados com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function buscarIngressos(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $eventoId = $args['evento_id'];
        $ingressos = Ingresso::buscarIngressos($eventoId);

        $response->getBody()->write(json_encode($ingressos));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function excluirIngresso(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["delete"])) {
            return $response->withStatus(401);
        }

        $ingressoId = $args['ingresso_id'];
        Ingresso::excluirIngresso($ingressoId);

        $response->getBody()->write(json_encode(['message' => 'Ingresso excluído com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function atualizarIngresso(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $ingressoId = $args['ingresso_id'];
        $data = $request->getParsedBody();
        $idCliente = $data['cliente_id'] ?? 0;
        $idEvento = $data['evento_id'] ?? 0;
        $valor = $data['valor'] ?? 0;
        $formaPagamento = $data['forma_pagamento'] ?? '';

        Ingresso::atualizarIngresso($ingressoId, $idCliente, $idEvento, $valor, $formaPagamento);

        $response->getBody()->write(json_encode(['message' => 'Ingresso atualizado com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
