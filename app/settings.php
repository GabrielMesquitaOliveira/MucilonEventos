<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

/**
 * Configuração de injeção de dependência para fornecer um objeto de configuração global Settings.
 *
 * Este código é destinado a ser utilizado como parte da configuração de um contêiner de injeção de dependência usando PHP-DI.
 *
 * @param ContainerBuilder $containerBuilder Construtor do contêiner de injeção de dependência.
 *
 * @return void
 */
return function (ContainerBuilder $containerBuilder) {

    // Objeto de Configurações Globais
    $containerBuilder->addDefinitions([
        /**
         * Configuração da injeção de dependência para SettingsInterface.
         *
         * @return SettingsInterface Instância de configurações globais.
         */
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Deve ser definido como false em produção
                'logError'            => true,
                'logErrorDetails'     => true,
                'logger' => [
                    'name' => 'MucilonEventos',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ]);
        }
    ]);
};
