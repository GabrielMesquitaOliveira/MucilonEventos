<?php

// Namespace para o modelo de Evento
namespace App\models;

// Use a classe Conexao para obter acesso à conexão com o banco de dados
use App\models\Conexao;

// Classe Evento
class Evento extends Conexao
{
    /**
     * Método para criar um novo evento no banco de dados.
     *
     * @param string $nomeEvento   O nome do evento.
     * @param string $dataEvento   A data do evento.
     * @param string $local        O local do evento.
     * @param string $descricao    A descrição do evento.
     * @param int    $artistaId    O ID do artista associado ao evento.
     * @param int    $categoriaId  O ID da categoria associada ao evento.
     * @param int    $eventoId     O ID do evento (se aplicável).
     * @param int    $localId      O ID do local associado ao evento.
     * @param int    $contador     O contador associado ao evento.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function criarEvento($nomeEvento, $dataEvento, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("INSERT INTO evento (nome_evento, data_evento, descricao, artista_id, categoria_id, evento_id, local_id, contador) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Vincula os parâmetros à instrução SQL
        $stmt->bind_param("sssiiiii", $nomeEvento, $dataEvento, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            return "Evento cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar o evento: " . $stmt->error;
        }
    }

    /**
     * Método para obter informações sobre um evento com base no ID.
     *
     * @param int $id O ID do evento a ser recuperado.
     *
     * @return array As informações do evento.
     */
    public static function obterEvento($id)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("SELECT * FROM evento WHERE id = ?");

        // Vincula o parâmetro à instrução SQL
        $stmt->bind_param("i", $id);

        // Executa a instrução SQL
        $stmt->execute();

        // Obtém o resultado
        $result = $stmt->get_result();

        // Retorna as informações do evento
        return $result->fetch_assoc();
    }

    /**
     * Método para atualizar as informações de um evento no banco de dados.
     *
     * @param int    $id           O ID do evento a ser atualizado.
     * @param string $nomeEvento   O novo nome do evento.
     * @param string $dataEvento   A nova data do evento.
     * @param string $local        O novo local do evento.
     * @param string $descricao    A nova descrição do evento.
     * @param int    $artistaId    O novo ID do artista associado ao evento.
     * @param int    $categoriaId  O novo ID da categoria associada ao evento.
     * @param int    $eventoId     O novo ID do evento (se aplicável).
     * @param int    $localId      O novo ID do local associado ao evento.
     * @param int    $contador     O novo contador associado ao evento.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function atualizarEvento($id, $nomeEvento, $dataEvento, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("UPDATE evento SET nome_evento = ?, data_evento = ?, descricao = ?, artista_id = ?, categoria_id = ?, evento_id = ?, local_id = ?, contador = ? WHERE id = ?");

        // Vincula os parâmetros à instrução SQL
        $stmt->bind_param("sssiiiiii", $nomeEvento, $dataEvento, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador, $id);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            return "Evento atualizado com sucesso!";
        } else {
            return "Erro ao atualizar o evento: " . $stmt->error;
        }
    }

    /**
     * Método para excluir um evento do banco de dados com base no ID.
     *
     * @param int $id O ID do evento a ser excluído.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function excluirEvento($id)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("DELETE FROM evento WHERE id = ?");

        // Vincula o parâmetro à instrução SQL
        $stmt->bind_param("i", $id);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            return "Evento excluído com sucesso!";
        } else {
            return "Erro ao excluir o evento: " . $stmt->error;
        }
    }

    /**
     * Método para listar todos os eventos no banco de dados.
     *
     * @return array A lista de eventos.
     */
    public static function listarEventos()
    {
        // Executa a consulta SQL para obter todos os eventos
        $result = self::getConexao()->query("SELECT * FROM evento");

        // Inicializa um array para armazenar os eventos
        $eventos = array();

        // Itera sobre o resultado e adiciona cada evento ao array
        while ($evento = $result->fetch_assoc()) {
            $eventos[] = $evento;
        }

        // Retorna a lista de eventos
        return $eventos;
    }

    /**
     * Método para decrementar o contador de um evento com base no ID.
     *
     * @param int $id O ID do evento cujo contador será decrementado.
     *
     * @return bool true se a operação for bem-sucedida, false caso contrário.
     */
    public static function subtrairContador($id)
    {
        // Executa a consulta SQL para decrementar o contador
        $result = self::getConexao()->query("UPDATE evento SET contador = contador - 1 WHERE id = '$id'");

        // Retorna o resultado da operação
        return $result;
    }
}
