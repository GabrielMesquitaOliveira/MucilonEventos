<?php

namespace App\models;

class Artista extends Conexao
{
    public static function criarArtista($nome)
    {
        $stmt = self::getConexao()->prepare("INSERT INTO artista (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);

        if ($stmt->execute()) {
            return "Artista criado com sucesso!";
        } else {
            return "Erro ao criar artista: " . $stmt->error;
        }
    }

    public static function obterArtista($id)
    {
        $stmt = self::getConexao()->prepare("SELECT * FROM artista WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $artista = $result->fetch_assoc();

        return $artista;
    }

    public static function buscarArtistas()
    {
        $result = self::getConexao()->query("SELECT * FROM artista");

        $artistas = array();
        while ($artista = $result->fetch_assoc()) {
            $artistas[] = $artista;
        }

        return $artistas;
    }

    public static function buscarArtistaPorNome($nome)
    {
        $stmt = self::getConexao()->prepare("SELECT * FROM artista WHERE nome LIKE ?");
        $likeNome = "%$nome%";
        $stmt->bind_param("s", $likeNome);
        $stmt->execute();

        $result = $stmt->get_result();
        $artistas = array();
        while ($artista = $result->fetch_assoc()) {
            $artistas[] = $artista;
        }

        return $artistas;
    }

    public static function excluirArtista($id)
    {
        $stmt = self::getConexao()->prepare("DELETE FROM artista WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return "Artista excluído com sucesso!";
        } else {
            return "Erro ao excluir artista: " . $stmt->error;
        }
    }
}
