<?php

namespace App\Controllers;

use App\Models\Evento;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EventoController
{
    private function verificarToken(Request $request, array $scopes): bool
    {
        $token = $request->getAttribute("token");

        return array_intersect($scopes, $token["scope"]) !== [];
    }
    public function criarEvento(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $data = $request->getParsedBody();
        $nomeEvento = $data['nome_evento'] ?? '';
        $dataEvento = $data['data_evento'] ?? '';
        $descricao = $data['descricao'] ?? '';
        $artistaId = $data['artista_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $categoriaId = $data['categoria_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $eventoId = $data['evento_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $localId = $data['local_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $contador = $data['contador'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado

        $message = Evento::criarEvento($nomeEvento, $dataEvento, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function obterEvento(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $evento = Evento::obterEvento($id);

        $response->getBody()->write(json_encode($evento));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function atualizarEvento(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["write"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $data = $request->getParsedBody();
        $nomeEvento = $data['nome_evento'] ?? '';
        $dataEvento = $data['data_evento'] ?? '';
        $descricao = $data['descricao'] ?? '';
        $artistaId = $data['artista_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $categoriaId = $data['categoria_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $eventoId = $data['evento_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $localId = $data['local_id'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado
        $contador = $data['contador'] ?? 0; // Certifique-se de fornecer um valor padrão apropriado

        $message = Evento::atualizarEvento($id, $nomeEvento, $dataEvento, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function excluirEvento(Request $request, Response $response, array $args): Response
    {
        if (!$this->verificarToken($request, ["delete"])) {
            return $response->withStatus(401);
        }

        $id = $args['id'];
        $message = Evento::excluirEvento($id);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listarEventos(Request $request, Response $response): Response
    {
        if (!$this->verificarToken($request, ["read"])) {
            return $response->withStatus(401);
        }

        $eventos = Evento::listarEventos();

        $response->getBody()->write(json_encode($eventos));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
