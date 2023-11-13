<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Evento;

$app->group('/evento', function (Group $group) {

    $group->get('', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode(Evento::listarEventos()));
        return $response->withHeader('Content-Type', 'application/json');
    });

});

