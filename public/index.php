<?php

// Habilita a declaração estrita de tipos
declare(strict_types=1);

// Importa as classes necessárias
use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

// Autoloader do Composer para carregar classes automaticamente
require __DIR__ . '/../vendor/autoload.php';

// Instancia o construtor de contêiner PHP-DI
$containerBuilder = new ContainerBuilder();

// Ativa a compilação apenas em produção
if (false) { // Deve ser definido como true em produção
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Carrega as configurações do aplicativo
$settings = require __DIR__ . '/../app/settings.php';
$dependencies = require __DIR__ . '/../app/dependencies.php';
$repositories = require __DIR__ . '/../app/repositories.php';

// Aplica as configurações e dependências ao construtor de contêiner
$settings($containerBuilder);
$dependencies($containerBuilder);
$repositories($containerBuilder);

// Constrói o contêiner PHP-DI
$container = $containerBuilder->build();

// Configura o contêiner para o Slim
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Registra os middlewares
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Registra as rotas
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

/** @var SettingsInterface $settings */
$settings = $container->get(SettingsInterface::class);

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

// Cria uma instância do objeto Request a partir das variáveis globais
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Cria o manipulador de erros
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Cria o manipulador de encerramento
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Adiciona o middleware de roteamento
$app->addRoutingMiddleware();

// Adiciona o middleware de análise de corpo
$app->addBodyParsingMiddleware();

// Define um caminho base para a aplicação (comentado para manter a flexibilidade)
// $app->setBasePath('/muciloneventos');

// Adiciona o middleware de erro
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Executa a aplicação e emite a resposta
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
