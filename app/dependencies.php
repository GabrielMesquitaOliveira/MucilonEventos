<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\models\Evento;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Configuração de injeção de dependência para fornecer uma instância do logger usando Monolog.
 *
 * @param ContainerBuilder $containerBuilder Construtor do contêiner de injeção de dependência.
 *
 * @return void
 */
return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        /**
         * Configuração do serviço LoggerInterface.
         *
         * @param ContainerInterface $c Contêiner de injeção de dependência.
         *
         * @return LoggerInterface Instância configurada do logger.
         */
        LoggerInterface::class => function (ContainerInterface $c) {
            // Obtém as configurações da aplicação
            $settings = $c->get(SettingsInterface::class);

            // Extrai as configurações específicas do logger
            $loggerSettings = $settings->get('logger');

            // Instancia um novo objeto Logger do Monolog
            $logger = new Logger($loggerSettings['name']);

            // Adiciona um UID único a cada registro de log
            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            // Envia as mensagens de log para um arquivo no caminho especificado nas configurações
            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            // Retorna o logger configurado
            return $logger;
        },
    ]);
};
