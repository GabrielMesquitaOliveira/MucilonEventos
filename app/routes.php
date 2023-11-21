<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

/**
 * Configuração de rotas para um aplicativo Slim.
 *
 * Este código é destinado a ser utilizado como parte da configuração de rotas para um aplicativo Slim Framework.
 *
 * @param App $app Instância do aplicativo Slim.
 *
 * @return void
 */
return function (App $app) {
    // Configuração para lidar com requisições CORS OPTIONS pre-flight
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // Retorna a resposta para requisições CORS pre-flight OPTIONS
        return $response;
    });

    // Rota inicial para a página principal
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Home');
        return $response;
    });

    // Inclui as rotas definidas em arquivos na pasta 'routes'
    foreach (glob(__DIR__ . '/routes/*.php') as $file) {
        require_once($file);
    }

};

