<?php

/**
 * Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
 *A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
 *A classe Cliente do namespace App\models também é importada para ser usada nas rotas.
 */

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\ClienteController;

$controller = new ClienteController;
