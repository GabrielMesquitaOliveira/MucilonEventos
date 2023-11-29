<?php

declare(strict_types=1);

use Slim\App;
use Tuupola\Middleware\JwtAuthentication;

/**
 * Configuração do middleware JwtAuthentication para autenticação JWT.
 *
 * Este código é destinado a ser utilizado como parte da configuração de uma aplicação Slim Framework.
 *
 * @param App $app Instância do aplicativo Slim.
 *
 * @return void
 */
return function (App $app) {
    $app->add(new JwtAuthentication([
        /**
         * Configuração do middleware JwtAuthentication.
         *
         * - "relaxed": Lista de hosts permitidos para CORS.
         * - "secret": Chave secreta para verificar a assinatura do token JWT.
         * - "algorithm": Lista de algoritmos de assinatura permitidos.
         * - "attribute": Nome do atributo que conterá os dados decodificados do JWT.
         * - "error": Função de tratamento de erros personalizada para lidar com falhas na autenticação JWT.
         *
         * @param array $arguments Argumentos de erro fornecidos pelo middleware.
         * @param \Slim\Psr7\Response $response Resposta Slim.
         *
         * @return \Slim\Psr7\Response Resposta Slim com detalhes de erro em formato JSON.
         */
        "relaxed" => ["localhost", "example.com"],
        "secret" => '12345000',
        "algorithm" => ["HS256"],
        "attribute" => "jwt",
        "ignore" => ["/muciloneventos/clientes/autenticar", "/muciloneventos/clientes/create"],
        "error" => function ($response, $arguments) {
            // Prepara os dados de erro em formato JSON
            $data["status"] = "error";
            $data["message"] = $arguments["message"];

            // Escreve os dados de erro no corpo da resposta
            $response->getBody()->write(
                json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            );

            // Define o cabeçalho Content-Type como application/json
            return $response->withHeader("Content-Type", "application/json");
        },
    ]));
};
