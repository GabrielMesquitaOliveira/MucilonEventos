<?php

// Namespace para o modelo de Categoria, que herda de Conexao
namespace App\models;

// Classe Categoria, que herda de Conexao
class Categoria extends Conexao
{
    /**
     * Cria uma nova categoria no banco de dados.
     *
     * @param string $nome Nome da categoria a ser criada.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function criarCategoria($nome)
    {
        // Prepara a consulta SQL para inserir uma nova categoria
        $stmt = self::getConexao()->prepare("INSERT INTO categoria (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return 'Categoria criada com sucesso';
        } else {
            return "Erro ao criar categoria: " . $stmt->error;
        }
    }

    /**
     * Busca categorias pelo nome, usando correspondência parcial.
     *
     * @param string $categoria Nome (ou parte do nome) da categoria a ser buscada.
     *
     * @return array Retorna um array de arrays associativos contendo informações das categorias encontradas.
     */
    public static function buscarCategoria($categoria)
    {
        // Prepara a consulta SQL para buscar categorias pelo nome (correspondência parcial)
        $stmt = self::getConexao()->prepare("SELECT categoria.nome 
            FROM evento
            INNER JOIN categoria ON evento.categoria_id = categoria.id 
            WHERE categoria.nome LIKE ?");
        $categoriaParam = "%$categoria%";
        $stmt->bind_param("s", $categoriaParam);
        $stmt->execute();

        // Obtém o resultado da consulta
        $result = $stmt->get_result();

        // Processa o resultado e retorna a lista de categorias encontradas
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Obtém as informações de uma categoria com base no ID.
     *
     * @param int $categoria ID da categoria a ser obtida.
     *
     * @return mixed Retorna um array associativo com as informações da categoria ou false se não encontrada.
     */
    public static function buscarCategoriaId($categoria)
    {
        // Prepara a consulta SQL para obter uma categoria pelo ID
        $stmt = self::getConexao()->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->bind_param("i", $categoria);
        $stmt->execute();

        // Obtém o resultado da consulta
        $result = $stmt->get_result();

        // Verifica se há resultados e retorna o resultado ou false se não encontrado
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Exclui uma categoria com base no ID.
     *
     * @param int $categoria ID da categoria a ser excluída.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function excluirCategoria(int $categoria)
    {
        // Prepara a consulta SQL para excluir uma categoria pelo ID
        $stmt = self::getConexao()->prepare("DELETE FROM categoria WHERE id = ?");
        $stmt->bind_param("i", $categoria);
        $stmt->execute();

        // Verifica o número de linhas afetadas e retorna a mensagem apropriada
        if ($stmt->affected_rows > 0) {
            return "Categoria excluída com sucesso!";
        } else {
            return "Erro ao excluir categoria: " . $stmt->error;
        }
    }

     /**
     * Atualiza uma categoria no banco de dados.
     *
     * @param int $id ID da categoria a ser atualizada.
     * @param string $nome Novo nome da categoria.
     * @param string $descricao Nova descrição da categoria.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function atualizarCategoria($id, $nome, $descricao)
    {
        // Prepara a consulta SQL para atualizar uma categoria
        $stmt = self::getConexao()->prepare("UPDATE categoria SET nome = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $descricao, $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return "Categoria atualizada com sucesso!";
        } else {
            return "Erro ao atualizar categoria: " . $stmt->error;
        }
    }

    /**
     * Obtém uma lista de todas as categorias.
     *
     * @return array Retorna um array de arrays associativos contendo informações de todas as categorias.
     */
    public static function listarCategorias()
    {
        // Executa a consulta SQL para obter todas as categorias
        $result = self::getConexao()->query("SELECT * FROM categoria");

        // Processa o resultado e retorna a lista de categorias
        $categorias = array();
        while ($categoria = $result->fetch_assoc()) {
            $categorias[] = $categoria;
        }

        return $categorias;
    }
}
