<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Evento;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Home');
        return $response;
    });

    $app->get('/eventos', function (Request $request, Response $response) {
        $response->getBody()->write('Deve listar todos os eventos!');
        return $response;
    });

    $app->get('/clientes', function (Request $request, Response $response) {
        $response->getBody()->write('Deve listar todos os clientes!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->group('/evento', function (Group $group) {

        $group->get('', function (Request $request, Response $response, $args) {
            $response->getBody()->write(json_encode(Evento::listarEventos()));
            return $response->withHeader('Content-Type', 'application/json');
        });
    
    });
};
