<?php

// Namespace para o modelo de Local
namespace App\models;

// Use a classe Conexao para obter acesso à conexão com o banco de dados
use App\models\Conexao;

// Classe Local
class Local extends Conexao
{
    /**
     * Cria um novo local no banco de dados.
     *
     * @param int    $capacidade  A capacidade do local.
     * @param string $localidade  A localidade do local.
     * @param string $area        A área do local.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function criarLocal($capacidade, $localidade, $area)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("INSERT INTO local (capacidade, localidade, area) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $capacidade, $localidade, $area);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            return "Local criado com sucesso!";
        } else {
            return "Erro ao criar local: " . $stmt->error;
        }
    }

    /**
     * Busca locais com base em uma parte da área.
     *
     * @param string $local A parte da área a ser pesquisada.
     *
     * @return array A lista de áreas encontradas.
     */
    public static function buscarLocal($local)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("SELECT local.area FROM evento INNER JOIN local ON evento.local_id = local.id WHERE local.area LIKE ?");
        $local = "%{$local}%";
        $stmt->bind_param("s", $local);

        // Executa a instrução SQL
        $stmt->execute();

        // Obtém o resultado
        $result = $stmt->get_result();

        // Inicializa um array para armazenar as áreas encontradas
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Retorna a lista de áreas encontradas
        return $rows;
    }

    /**
     * Busca informações de um local com base no ID.
     *
     * @param int $local O ID do local a ser pesquisado.
     *
     * @return mixed Retorna as informações do local se existir, caso contrário, retorna false.
     */
    public static function buscarLocalId($local)
    {
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare("SELECT * FROM local WHERE id = ?");
        $stmt->bind_param("i", $local);

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
     * Exclui um local do banco de dados com base no ID.
     *
     * @param int $local O ID do local a ser excluído.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function excluirLocal($local)
    {
        // Verifica se o local existe
        $localExistente = self::buscarLocalId($local);

        if ($localExistente) {
            // Prepara a instrução SQL
            $stmt = self::getConexao()->prepare("DELETE FROM local WHERE id = ?");
            $stmt->bind_param("i", $local);

            // Executa a instrução SQL
            $stmt->execute();

            // Retorna uma mensagem com base no número de linhas afetadas
            return $stmt->affected_rows > 0 ? "Local excluído com sucesso!" : "Erro ao excluir local";
        } else {
            return "Local não existe";
        }
    }

    /**
     * Lista todos os locais no banco de dados.
     *
     * @return array A lista de locais.
     */
    public static function listarLocais()
    {
        // Executa a consulta SQL para obter todos os locais
        $result = self::getConexao()->query("SELECT * FROM local");

        // Inicializa um array para armazenar os locais
        $locais = array();
        while ($local = $result->fetch_assoc()) {
            $locais[] = $local;
        }

        // Retorna a lista de locais
        return $locais;
    }
}
