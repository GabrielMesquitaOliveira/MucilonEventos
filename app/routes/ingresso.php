<?php
/**
* Aqui, estão sendo importadas as interfaces ResponseInterface e ServerRequestInterface do PSR (PHP-FIG Standard Recommendation) para representar as interfaces de resposta e requisição HTTP, respectivamente.
*A interface RouteCollectorProxyInterface é importada do Slim Framework para permitir a definição de grupos de rotas.
*A classe Ingresso do namespace App\models também é importada para ser usada nas rotas.
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\models\Ingresso;

/**
 * Grupo de rotas para manipulação de dados relacionados a ingressos.
 *
 * Este grupo inclui operações como criar um novo ingresso, buscar ingressos por evento,
 * excluir um ingresso e atualizar informações de um ingresso.
 */
$app->group('/ingressos', function (Group $group) {

    /**
     * [POST] Cria um novo ingresso.
     *
     * @param string $idCliente       ID do cliente que adquiriu o ingresso.
     * @param string $idEvento        ID do evento associado ao ingresso.
     * @param float  $valor           Valor do ingresso.
     * @param string $formaPagamento  Forma de pagamento utilizada.
     * @param int    $quantidade      Quantidade de ingressos adquiridos.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->post('', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $idCliente = $data['idCliente'] ?? '';
        $idEvento = $data['idEvento'] ?? '';
        $valor = $data['valor'] ?? '';
        $formaPagamento = $data['formaPagamento'] ?? '';
        $quantidade = $data['quantidade'] ?? '';

        Ingresso::criarIngresso($idCliente, $idEvento, $valor, $formaPagamento, $quantidade);

        $response->getBody()->write(json_encode(['message' => 'Ingresso(s) criado(s) com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [GET] Busca ingressos por evento.
     *
     * @param string $evento Nome ou ID do evento para o qual os ingressos estão sendo buscados.
     * @return Response Retorna uma lista de ingressos para o evento em formato JSON.
     */
    $group->get('/{evento}', function (Request $request, Response $response, $args) {
        $evento = $args['evento'];
        $ingressos = Ingresso::buscarIngressos($evento);

        $response->getBody()->write(json_encode($ingressos));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [DELETE] Exclui um ingresso pelo ID.
     *
     * @param int $ingresso ID do ingresso a ser excluído.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->delete('/{ingresso}', function (Request $request, Response $response, $args) {
        $ingresso = $args['ingresso'];
        Ingresso::excluirIngresso($ingresso);

        $response->getBody()->write(json_encode(['message' => 'Ingresso estornado com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * [PUT] Atualiza informações de um ingresso pelo ID.
     *
     * @param int    $ingresso        ID do ingresso a ser atualizado.
     * @param string $idCliente       Novo ID do cliente que adquiriu o ingresso.
     * @param string $idEvento        Novo ID do evento associado ao ingresso.
     * @param float  $valor           Novo valor do ingresso.
     * @param string $formaPagamento  Nova forma de pagamento utilizada.
     * @return Response Retorna uma mensagem indicando o resultado da operação.
     */
    $group->put('/{ingresso}', function (Request $request, Response $response, $args) {
        $ingresso = $args['ingresso'];
        $data = $request->getParsedBody();
        $idCliente = $data['idCliente'] ?? '';
        $idEvento = $data['idEvento'] ?? '';
        $valor = $data['valor'] ?? '';
        $formaPagamento = $data['formaPagamento'] ?? '';

        Ingresso::atualizarIngresso($ingresso, $idCliente, $idEvento, $valor, $formaPagamento);

        $response->getBody()->write(json_encode(['message' => 'Ingresso atualizado com sucesso!']));
        return $response->withHeader('Content-Type', 'application/json');
    });

});

