<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Local do namespace App\models também é importada para ser usada nas rotas.
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Local;

/**
 * Grupo de rotas para manipulação de dados relacionados a locais.
 *
 * Este grupo inclui operações como listar todos os locais, obter detalhes de um local,
 * criar um novo local e excluir um local.
 */
$app->group('/locais', function (Group $group) {

    /**
     * [GET] Lista todos os locais.
     *
     * @return Response Retorna uma lista de todos os locais em formato JSON.
     */
    $group->get('', function (Request $request, Response $response) {
        $locais = Local::listarLocais();
        $response->getBody()->write(json_encode($locais));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Obtém detalhes de um local pelo ID.
     *
     * @param int $id ID do local.
     * @return Response Retorna os detalhes do local em formato JSON.
     */
    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $local = Local::buscarLocalId($id);

        if ($local) {
            // Local encontrado
            $response->getBody()->write(json_encode($local->fetch_assoc()));
        } else {
            // Local não encontrado
            $response->getBody()->write(json_encode(['message' => 'Local não encontrado']));
            $response = $response->withStatus(404);
        }

        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [POST] Cria um novo local.
     *
     * @param int    $capacidade Capacidade do local.
     * @param string $localidade Nome ou descrição do local.
     * @param float  $area       Área total do local.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $capacidade = $data['capacidade'] ?? '';
        $localidade = $data['localidade'] ?? '';
        $area = $data['area'] ?? '';

        $result = Local::criarLocal($capacidade, $localidade, $area);

        $response->getBody()->write(json_encode(['message' => $result]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [DELETE] Exclui um local pelo ID.
     *
     * @param int $id ID do local a ser excluído.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $result = Local::excluirLocal($id);

        $response->getBody()->write(json_encode(['message' => $result]));
        return $response->withHeader('Content-Type', 'application/json');
    });

});
