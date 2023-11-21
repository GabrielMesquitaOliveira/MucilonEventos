<?php

// Namespace para o modelo de Organizador
namespace App\models;

// Use a classe Conexao para obter acesso à conexão com o banco de dados
use App\models\Conexao;

// Classe Organizador
class Organizador extends Conexao
{
    /**
     * Cria um novo organizador no banco de dados.
     *
     * @param string $nome  O nome do organizador.
     * @param string $email O e-mail do organizador.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function criarOrganizador($nome, $email)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("INSERT INTO organizador (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            return 'Organizador criado com sucesso';
        } else {
            return "Erro ao criar Organizador: " . $stmt->error;
        }
    }

    /**
     * Busca organizadores com base em uma parte do nome.
     *
     * @param string $organizador A parte do nome do organizador a ser pesquisada.
     *
     * @return array A lista de nomes de organizadores encontrados.
     */
    public static function buscarOrganizador($organizador)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("SELECT organizador.nome
            FROM evento
            INNER JOIN organizador ON evento.organizador_id = organizador.id
            WHERE organizador.nome LIKE ?");
        $organizadorParam = "%$organizador%";
        $stmt->bind_param("s", $organizadorParam);

        // Executa a instrução SQL
        $stmt->execute();

        // Obtém o resultado
        $result = $stmt->get_result();

        // Inicializa um array para armazenar os nomes de organizadores encontrados
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Retorna a lista de nomes de organizadores encontrados
        return $rows;
    }

    /**
     * Busca informações de um organizador com base no ID.
     *
     * @param int $organizador O ID do organizador a ser pesquisado.
     *
     * @return mixed Retorna as informações do organizador se existirem, caso contrário, retorna false.
     */
    public static function buscarOrganizadorId($organizador)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("SELECT * FROM organizador WHERE id = ?");
        $stmt->bind_param("i", $organizador);

        // Executa a instrução SQL
        $stmt->execute();

        // Obtém o resultado
        $result = $stmt->get_result();

        // Verifica se há resultados
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Exclui um organizador do banco de dados com base no ID.
     *
     * @param int $organizador O ID do organizador a ser excluído.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function excluirOrganizador(int $organizador)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("DELETE FROM organizador WHERE id = ?");
        $stmt->bind_param("i", $organizador);

        // Executa a instrução SQL
        $stmt->execute();

        // Retorna uma mensagem com base no número de linhas afetadas
        return $stmt->affected_rows > 0 ? "Organizador excluído com sucesso!" : "Erro ao excluir organizador: " . $stmt->error;
    }
}
