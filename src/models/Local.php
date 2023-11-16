<?php

namespace App\models;

use App\models\Conexao;

class Local extends Conexao
{
    public static function criarLocal($capacidade, $localidade, $area)
    {
        $stmt = self::getConexao()->prepare("INSERT INTO local (capacidade, localidade, area) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $capacidade, $localidade, $area);

        if ($stmt->execute()) {
            return "Local criado com sucesso!";
        } else {
            return "Erro ao criar local: " . $stmt->error;
        }
    }

    public static function buscarLocal($local)
    {
        $stmt = self::getConexao()->prepare("SELECT local.area FROM evento INNER JOIN local ON evento.local_id = local.id WHERE local.area LIKE ?");
        $local = "%{$local}%";
        $stmt->bind_param("s", $local);
        $stmt->execute();

        $result = $stmt->get_result();
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public static function buscarLocalId($local)
    {
        $stmt = self::getConexao()->prepare("SELECT * FROM local WHERE id = ?");
        $stmt->bind_param("i", $local);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public static function excluirLocal($local)
    {
        $localExistente = self::buscarLocalId($local);

        if ($localExistente) {
            $stmt = self::getConexao()->prepare("DELETE FROM local WHERE id = ?");
            $stmt->bind_param("i", $local);
            $stmt->execute();

            return $stmt->affected_rows > 0 ? "Local excluído com sucesso!" : "Erro ao excluir local";
        } else {
            return "Local não existe";
        }
    }

    public static function listarLocais()
    {
        $result = self::getConexao()->query("SELECT * FROM local");

        $locais = array();
        while ($local = $result->fetch_assoc()) {
            $locais[] = $local;
        }

        return $locais;
    }
}
