<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Evento do namespace App\models também é importada para ser usada nas rotas.
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Evento;

/**
 * Grupo de rotas para manipulação de dados relacionados a eventos.
 *
 * Este grupo inclui operações como listar todos os eventos, obter detalhes de um evento,
 * criar um novo evento, atualizar informações de um evento, e excluir um evento.
 */
$app->group('/eventos', function (Group $group) {

    /**
     * [GET] Lista todos os eventos.
     *
     * @return Response Retorna uma lista de todos os eventos em formato JSON.
     */
    $group->get('', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode(Evento::listarEventos()));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Obtém detalhes de um evento pelo ID.
     *
     * @param int $id ID do evento.
     * @return Response Retorna os detalhes do evento em formato JSON.
     */
    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $evento = Evento::obterEvento($id);

        if ($evento) {
            // Evento encontrado
            $response->getBody()->write(json_encode($evento));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            // Evento não encontrado
            $response->getBody()->write(json_encode(['error' => 'Evento não encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

    /**
     * [POST] Cria um novo evento.
     *
     * @param string $nomeEvento   Nome do evento.
     * @param string $dataEvento   Data do evento.
     * @param string $local        Local do evento.
     * @param string $descricao    Descrição do evento.
     * @param int    $artistaId    ID do artista associado ao evento.
     * @param int    $categoriaId  ID da categoria associada ao evento.
     * @param int    $eventoId     ID de outro evento relacionado (opcional).
     * @param int    $localId      ID do local associado ao evento (opcional).
     * @param int    $contador     Contador do evento (opcional).
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->post('', function (Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $nomeEvento = $data['nome_evento'] ?? '';
        $dataEvento = $data['data_evento'] ?? '';
        $local = $data['local'] ?? '';
        $descricao = $data['descricao'] ?? '';
        $artistaId = $data['artista_id'] ?? 0;
        $categoriaId = $data['categoria_id'] ?? 0;
        $eventoId = $data['evento_id'] ?? 0;
        $localId = $data['local_id'] ?? 0;
        $contador = $data['contador'] ?? 0;

        $resultado = Evento::criarEvento($nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [PUT] Atualiza informações de um evento pelo ID.
     *
     * @param int    $id           ID do evento.
     * @param string $nomeEvento   Novo nome do evento.
     * @param string $dataEvento   Nova data do evento.
     * @param string $local        Novo local do evento.
     * @param string $descricao    Nova descrição do evento.
     * @param int    $artistaId    Novo ID do artista associado ao evento.
     * @param int    $categoriaId  Novo ID da categoria associada ao evento.
     * @param int    $eventoId     Novo ID de outro evento relacionado (opcional).
     * @param int    $localId      Novo ID do local associado ao evento (opcional).
     * @param int    $contador     Novo contador do evento (opcional).
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->put('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
        $nomeEvento = $data['nome_evento'] ?? '';
        $dataEvento = $data['data_evento'] ?? '';
        $local = $data['local'] ?? '';
        $descricao = $data['descricao'] ?? '';
        $artistaId = $data['artista_id'] ?? 0;
        $categoriaId = $data['categoria_id'] ?? 0;
        $eventoId = $data['evento_id'] ?? 0;
        $localId = $data['local_id'] ?? 0;
        $contador = $data['contador'] ?? 0;

        $resultado = Evento::atualizarEvento($id, $nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [DELETE] Exclui um evento pelo ID.
     *
     * @param int $id ID do evento a ser excluído.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $resultado = Evento::excluirEvento($id);

        $response->getBody()->write(json_encode(['message' => $resultado]));
        return $response->withHeader('Content-Type', 'application/json');
    });
});
