<?php

// Namespace para o modelo de Artista, que estende a classe de conexão
namespace App\models;

// Classe Artista, que herda de Conexao
class Artista extends Conexao
{
    /**
     * Cria um novo artista no banco de dados.
     *
     * @param string $nome Nome do artista a ser criado.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function criarArtista($nome)
    {
        // Prepara a consulta SQL para inserir um novo artista
        $stmt = self::getConexao()->prepare("INSERT INTO artista (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return "Artista criado com sucesso!";
        } else {
            return "Erro ao criar artista: " . $stmt->error;
        }
    }

    /**
     * Obtém as informações de um artista com base no ID.
     *
     * @param int $id ID do artista a ser obtido.
     *
     * @return array|null Retorna um array associativo com as informações do artista ou null se não encontrado.
     */
    public static function obterArtista($id)
    {
        // Prepara a consulta SQL para obter um artista pelo ID
        $stmt = self::getConexao()->prepare("SELECT * FROM artista WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Obtém o resultado da consulta
        $result = $stmt->get_result();
        $artista = $result->fetch_assoc();

        return $artista;
    }

    public static function atualizarArtista($id, $nome, $descricao)
    {
        // Prepara a consulta SQL para atualizar um artista pelo ID
        $stmt = self::getConexao()->prepare("UPDATE artista SET nome = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $descricao, $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return "Artista atualizado com sucesso!";
        } else {
            return "Erro ao atualizar artista: " . $stmt->error;
        }
    }

    /**
     * Obtém uma lista de todos os artistas.
     *
     * @return array Retorna um array de arrays associativos contendo informações de todos os artistas.
     */
    public static function listarArtistas()
    {
        // Executa a consulta SQL para obter todos os artistas
        $result = self::getConexao()->query("SELECT * FROM artista");

        // Processa o resultado e retorna a lista de artistas
        $artistas = array();
        while ($artista = $result->fetch_assoc()) {
            $artistas[] = $artista;
        }

        return $artistas;
    }

    /**
     * Busca artistas pelo nome, usando correspondência parcial.
     *
     * @param string $nome Nome (ou parte do nome) do artista a ser buscado.
     *
     * @return array Retorna um array de arrays associativos contendo informações dos artistas encontrados.
     */
    public static function buscarArtistaPorNome($nome)
    {
        // Prepara a consulta SQL para buscar artistas pelo nome (correspondência parcial)
        $stmt = self::getConexao()->prepare("SELECT * FROM artista WHERE nome LIKE ?");
        $likeNome = "%$nome%";
        $stmt->bind_param("s", $likeNome);
        $stmt->execute();

        // Obtém o resultado da consulta
        $result = $stmt->get_result();

        // Processa o resultado e retorna a lista de artistas encontrados
        $artistas = array();
        while ($artista = $result->fetch_assoc()) {
            $artistas[] = $artista;
        }

        return $artistas;
    }

    /**
     * Exclui um artista com base no ID.
     *
     * @param int $id ID do artista a ser excluído.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function excluirArtista($id)
    {
        // Prepara a consulta SQL para excluir um artista pelo ID
        $stmt = self::getConexao()->prepare("DELETE FROM artista WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return "Artista excluído com sucesso!";
        } else {
            return "Erro ao excluir artista: " . $stmt->error;
        }
    }
}
