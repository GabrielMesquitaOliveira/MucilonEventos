<?php

// Declaração estrita de tipos
declare(strict_types=1);

// Namespace para o repositório de usuário em memória
namespace App\Infrastructure\Persistence\User;

// Importa as classes necessárias
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

// Implementação do repositório de usuário em memória
class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private array $users;

    /**
     * Construtor da classe InMemoryUserRepository.
     *
     * @param User[]|null $users Array de usuários (opcional).
     */
    public function __construct(array $users = null)
    {
        // Inicializa a lista de usuários com valores padrão, se não for fornecida uma lista.
        $this->users = $users ?? [
            1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        // Retorna os valores da lista de usuários
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        // Verifica se o usuário com o ID fornecido existe na lista
        if (!isset($this->users[$id])) {
            // Lança uma exceção se o usuário não for encontrado
            throw new UserNotFoundException();
        }

        // Retorna o usuário encontrado
        return $this->users[$id];
    }
}
