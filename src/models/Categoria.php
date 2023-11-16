<?php

namespace App\models;

use App\models\Conexao;

class Categoria extends Conexao
{
    public static function criarCategoria($nome)
    {
        $stmt = self::getConexao()->prepare("INSERT INTO categoria (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);

        if ($stmt->execute()) {
            return 'Categoria criada com sucesso';
        } else {
            return "Erro ao criar categoria: " . $stmt->error;
        }
    }

    public static function buscarCategoria($categoria)
    {
        $stmt = self::getConexao()->prepare("SELECT categoria.nome 
            FROM evento
            INNER JOIN categoria ON evento.categoria_id = categoria.id 
            WHERE categoria.nome LIKE ?");
        $categoriaParam = "%$categoria%";
        $stmt->bind_param("s", $categoriaParam);
        $stmt->execute();

        $result = $stmt->get_result();
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function buscarCategoriaId($categoria)
    {
        $stmt = self::getConexao()->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->bind_param("i", $categoria);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public static function excluirCategoria(int $categoria)
    {
        $stmt = self::getConexao()->prepare("DELETE FROM categoria WHERE id = ?");
        $stmt->bind_param("i", $categoria);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return "Categoria excluída com sucesso!";
        } else {
            return "Erro ao excluir categoria: " . $stmt->error;
        }
    }
}
