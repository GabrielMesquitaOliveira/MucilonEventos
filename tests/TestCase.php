<?php

/**
 * Declaração de tipos estritos para este arquivo.
 *
 * Este arquivo contém uma classe TestCase usada para testes PHPUnit.
 * Ele define métodos úteis para inicializar uma instância do aplicativo Slim para testes
 * e criar solicitações HTTP para simular interações com o aplicativo.
 */

declare(strict_types=1);

namespace Tests;

use DI\ContainerBuilder;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

/**
 * Classe base para testes PHPUnit.
 */
class TestCase extends PHPUnit_TestCase
{
    use ProphecyTrait;

    /**
     * Obtém uma instância do aplicativo Slim configurada para testes.
     *
     * @return App
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        // Instancia o construtor do contêiner PHP-DI
        $containerBuilder = new ContainerBuilder();

        // O contêiner não é compilado intencionalmente para testes.

        // Configuração de definições
        $settings = require __DIR__ . '/../app/settings.php';
        $settings($containerBuilder);

        // Configuração de dependências
        $dependencies = require __DIR__ . '/../app/dependencies.php';
        $dependencies($containerBuilder);

        // Configuração de repositórios
        $repositories = require __DIR__ . '/../app/repositories.php';
        $repositories($containerBuilder);

        // Constrói a instância do contêiner PHP-DI
        $container = $containerBuilder->build();

        // Instancia o aplicativo Slim
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Registra middleware
        $middleware = require __DIR__ . '/../app/middleware.php';
        $middleware($app);

        // Registra rotas
        $routes = require __DIR__ . '/../app/routes.php';
        $routes($app);

        return $app;
    }

    /**
     * Cria uma solicitação HTTP para testes.
     *
     * @param string $method
     * @param string $path
     * @param array  $headers
     * @param array  $cookies
     * @param array  $serverParams
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
