<?php

/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Artista do namespace App\models também é importada para ser usada nas rotas.
*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Artista;

/**
 * Grupo de rotas para manipulação de dados relacionados a artistas.
 *
 * Este grupo inclui operações como listar todos os artistas, obter detalhes de um
 * artista específico, criar um novo artista, buscar artistas por nome e excluir um artista.
 */
$app->group('/artistas', function (Group $group) {

    /**
     * [GET] Lista todos os artistas.
     *
     * @return Response Retorna uma lista de todos os artistas em formato JSON.
     */
    $group->get('', function (Request $request, Response $response, $args) {
        // Lógica para buscar todos os artistas
        $response->getBody()->write(json_encode(Artista::buscarArtistas()));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Obtém detalhes de um artista específico.
     *
     * @param int $id ID do artista.
     * @return Response Retorna os detalhes do artista em formato JSON.
     */
    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $artista = Artista::obterArtista($id);

        if ($artista) {
            // Artista encontrado
            $response->getBody()->write(json_encode($artista));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            // Artista não encontrado
            $response->getBody()->write(json_encode(['error' => 'Artista não encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

    /**
     * [POST] Cria um novo artista.
     *
     * @param string $nome Nome do artista.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->post('', function (Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? '';

        $resultado = Artista::criarArtista($nome);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Busca artistas por nome.
     *
     * @param string $nome Nome do artista a ser buscado.
     * @return Response Retorna uma lista de artistas correspondentes ao nome em formato JSON.
     */
    $group->get('/buscar/{nome}', function (Request $request, Response $response, $args) {
        $nome = $args['nome'];
        $artistas = Artista::buscarArtistaPorNome($nome);

        $response->getBody()->write(json_encode($artistas));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [DELETE] Exclui um artista pelo ID.
     *
     * @param int $id ID do artista a ser excluído.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $resultado = Artista::excluirArtista($id);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });
});
