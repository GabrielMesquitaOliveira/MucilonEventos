<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Categoria do namespace App\models também é importada para ser usada nas rotas.
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Categoria;

/**
 * Grupo de rotas para manipulação de dados relacionados a categorias.
 *
 * Este grupo inclui operações como criar uma nova categoria, buscar categorias e excluir uma categoria.
 */
$app->group('/categorias', function (Group $group) {

    /**
     * [POST] Cria uma nova categoria.
     *
     * @param string $nome Nome da categoria a ser criada.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';

        $message = Categoria::criarCategoria($nome);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Busca categorias por nome.
     *
     * @param string $categoria Nome da categoria a ser buscada.
     * @return Response Retorna uma lista de categorias correspondentes ao nome em formato JSON.
     */
    $group->get('/{categoria}', function (Request $request, Response $response, $args) {
        $categoria = $args['categoria'];
        $categorias = Categoria::buscarCategoria($categoria);

        $response->getBody()->write(json_encode($categorias));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [DELETE] Exclui uma categoria pelo nome.
     *
     * @param string $categoria Nome da categoria a ser excluída.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->delete('/{categoria}', function (Request $request, Response $response, $args) {
        $categoria = $args['categoria'];
        $message = Categoria::excluirCategoria($categoria);

        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withHeader('Content-Type', 'application/json');
    });

});

