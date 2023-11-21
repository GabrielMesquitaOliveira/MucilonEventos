<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;

/**
 * Configuração de injeção de dependência para mapear UserRepository para sua implementação InMemoryUserRepository.
 *
 * Este código é destinado a ser utilizado como parte da configuração de um contêiner de injeção de dependência usando PHP-DI.
 *
 * @param ContainerBuilder $containerBuilder Construtor do contêiner de injeção de dependência.
 *
 * @return void
 */
return function (ContainerBuilder $containerBuilder) {
    // Mapeia a interface UserRepository para sua implementação InMemoryUserRepository
    $containerBuilder->addDefinitions([
        /**
         * Configuração da injeção de dependência para UserRepository.
         *
         * @var UserRepository $userRepo Instância de UserRepository.
         */
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
    ]);
};

