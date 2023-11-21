<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Cliente do namespace App\models também é importada para ser usada nas rotas.
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Cliente;

/**
 * Grupo de rotas para manipulação de dados relacionados a clientes.
 *
 * Este grupo inclui operações como criar um novo cliente, obter detalhes de um cliente,
 * atualizar informações de um cliente, excluir um cliente e listar todos os clientes.
 */
$app->group('/clientes', function (Group $group) {

    /**
     * [POST] Cria um novo cliente.
     *
     * @param string $nome     Nome do cliente.
     * @param string $email    Email do cliente.
     * @param string $senha    Senha do cliente.
     * @param string $telefone Número de telefone do cliente.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';
        $telefone = $data['telefone'] ?? '';

        $message = Cliente::criarCliente($nome, $email, $senha, $telefone);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Obtém detalhes de um cliente pelo ID.
     *
     * @param int $id ID do cliente.
     * @return Response Retorna os detalhes do cliente em formato JSON.
     */
    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $cliente = Cliente::obterCliente($id);

        $response->getBody()->write(json_encode($cliente));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [PUT] Atualiza informações de um cliente pelo ID.
     *
     * @param int    $id       ID do cliente.
     * @param string $nome     Novo nome do cliente.
     * @param string $email    Novo email do cliente.
     * @param string $telefone Novo número de telefone do cliente.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->put('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $telefone = $data['telefone'] ?? '';

        $message = Cliente::atualizarCliente($id, $nome, $email, $telefone);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [DELETE] Exclui um cliente pelo ID.
     *
     * @param int $id ID do cliente a ser excluído.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $message = Cliente::excluirCliente($id);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Lista todos os clientes.
     *
     * @return Response Retorna uma lista de todos os clientes em formato JSON.
     */
    $group->get('', function (Request $request, Response $response) {
        $clientes = Cliente::listarClientes();

        $response->getBody()->write(json_encode($clientes));
        return $response->withHeader('Content-Type', 'application/json');
    });

});
