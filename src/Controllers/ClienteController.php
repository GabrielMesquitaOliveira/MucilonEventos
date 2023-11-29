<?php

namespace App\Controllers;

use App\models\Cliente;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClienteController
{
    public function criarCliente(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';
        $telefone = $data['telefone'] ?? '';

        $message = Cliente::criarCliente($nome, $email, $senha, $telefone);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obterCliente(Request $request, Response $response, array $args): Response
    {
        $token = $request->getAttribute("token");

        if (in_array("read", $token["scope"])) {
            $id = $args['id'];
            $cliente = Cliente::obterCliente($id);

            $response->getBody()->write(json_encode($cliente));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $response->withStatus(401);
        }
    }

    public function atualizarCliente(Request $request, Response $response, array $args): Response
    {
        $token = $request->getAttribute("token");

        if (in_array("write", $token["scope"])) {
            $id = $args['id'];
            $data = $request->getParsedBody();
            $nome = $data['nome'] ?? '';
            $email = $data['email'] ?? '';
            $telefone = $data['telefone'] ?? '';

            $message = Cliente::atualizarCliente($id, $nome, $email, $telefone);

            $response->getBody()->write(json_encode(['message' => $message]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            /* No scope so respond with 401 Unauthorized */
            return $response->withStatus(401);
        }
    }

    public function excluirCliente(Request $request, Response $response, array $args): Response
    {
        $token = $request->getAttribute("token");

        if (in_array("delete", $token["scope"])) {
            $id = $args['id'];
            $message = Cliente::excluirCliente($id);

            $response->getBody()->write(json_encode(['message' => $message]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $response->withStatus(401);
        }
    }

    public function listarClientes(Request $request, Response $response): Response
    {
        $token = $request->getAttribute("token");

        if (in_array("read", $token["scope"])) {
            $clientes = Cliente::listarClientes();

            $response->getBody()->write(json_encode($clientes));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $response->withStatus(401);
        }
    }
    /**
     * Autentica um cliente e retorna um token JWT em caso de sucesso.
     *
     * @param Request $request   Pedido HTTP.
     * @param Response $response Resposta HTTP.
     *
     * @return Response
     */
    public function autenticarCliente(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';
        $cliente = Cliente::autenticarCliente($email, $senha);

        if ($cliente) {
            // Autenticação bem-sucedida
            $token = $this->gerarTokenJWT($cliente);

            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            // Autenticação falhou
            $response->getBody()->write(json_encode(['error' => 'Autenticação falhou']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }

    /**
     * Gera um token JWT para o cliente fornecido.
     *
     * @param array $cliente Dados do cliente.
     *
     * @return string Token JWT.
     */
    private function gerarTokenJWT(array $cliente): string
    {
        // Configurações do token (tempo de expiração, algoritmo de assinatura, etc.)
        $configuracoes = [
            'iat' => time(), // Tempo de emissão do token
            'exp' => time() + 86400, // Tempo de expiração do token (1 dia = 24 horas * 60 minutos * 60 segundos)
        ];

        // Chave secreta para assinar o token
        $chaveSecreta = '12345000';

        // Informações do cliente que você deseja incluir no token
        $dadosCliente = [
            'id' => $cliente['id'],
            'nome' => $cliente['nome'],
            'email' => $cliente['email'],
            'scope' =>  ["read", "write", "delete"]
        ];

        // Gere o token JWT usando a biblioteca Firebase\JWT
        $token = JWT::encode($dadosCliente, $chaveSecreta, 'HS256', null, $configuracoes);

        return $token;
    }

    public function token(Request $request, Response $response): Response
    {
        $token = $request->getAttribute("token");
        $response->getBody()->write(json_encode(['token' => $token]));
        return $response->withHeader('Content-Type', 'application/json');

    }
}
