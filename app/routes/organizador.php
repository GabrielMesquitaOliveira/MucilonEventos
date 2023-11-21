<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Organizador do namespace App\models também é importada para ser usada nas rotas.
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Organizador;

/**
 * Grupo de rotas para manipulação de dados relacionados a organizadores.
 *
 * Este grupo inclui operações como criar um novo organizador, buscar detalhes de um organizador,
 * excluir um organizador e buscar todos os organizadores.
 */
$app->group('/organizadores', function (Group $group) {

    /**
     * [POST] Cria um novo organizador.
     *
     * @param string $nome  Nome do organizador.
     * @param string $email Email do organizador.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';

        $message = Organizador::criarOrganizador($nome, $email);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Obtém detalhes de um organizador pelo ID.
     *
     * @param int $id ID do organizador.
     * @return Response Retorna os detalhes do organizador em formato JSON.
     */
    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $organizador = Organizador::buscarOrganizadorId($id);

        $response->getBody()->write(json_encode($organizador));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [DELETE] Exclui um organizador pelo ID.
     *
     * @param int $id ID do organizador a ser excluído.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $message = Organizador::excluirOrganizador($id);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Busca todos os organizadores.
     *
     * @return Response Retorna uma lista de todos os organizadores em formato JSON.
     */
    $group->get('', function (Request $request, Response $response) {
        $organizadores = Organizador::buscarOrganizador('');

        $response->getBody()->write(json_encode($organizadores));
        return $response->withHeader('Content-Type', 'application/json');
    });

});

