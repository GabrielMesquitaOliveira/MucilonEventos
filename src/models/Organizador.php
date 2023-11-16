<?php

namespace App\models;

use App\models\Conexao;

class Organizador extends Conexao
{
    public static function criarOrganizador($nome, $email)
    {
        $stmt = self::getConexao()->prepare("INSERT INTO organizador (nome, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);

        if ($stmt->execute()) {
            return 'Organizador criado com sucesso';
        } else {
            return "Erro ao criar Organizador: " . $stmt->error;
        }
    }

    public static function buscarOrganizador($organizador)
    {
        $stmt = self::getConexao()->prepare("SELECT organizador.nome
            FROM evento
            INNER JOIN organizador ON evento.organizador_id = organizador.id
            WHERE organizador.nome LIKE ?");
        $organizadorParam = "%$organizador%";
        $stmt->bind_param("s", $organizadorParam);
        $stmt->execute();

        $result = $stmt->get_result();
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public static function buscarOrganizadorId($organizador)
    {
        $stmt = self::getConexao()->prepare("SELECT * FROM organizador WHERE id = ?");
        $stmt->bind_param("i", $organizador);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public static function excluirOrganizador(int $organizador)
    {
        $stmt = self::getConexao()->prepare("DELETE FROM organizador WHERE id = ?");
        $stmt->bind_param("i", $organizador);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return "Organizador excluído com sucesso!";
        } else {
            return "Erro ao excluir organizador: " . $stmt->error;
        }
    }
}
