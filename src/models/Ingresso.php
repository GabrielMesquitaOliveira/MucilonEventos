<?php

namespace App\models;

use App\models\Conexao;

class Ingresso extends Conexao
{
    public static function criarIngresso($idCliente, $idEvento, $valor, $formaPagamento, $quantidade)
    {
        $sql = "INSERT INTO ingresso (cliente_id, evento_id, valor, forma_pagamento) VALUES (?, ?, ?, ?)";

        $stmt = self::getConexao()->prepare($sql);

        for ($i = 0; $i < $quantidade; $i++) {
            $stmt->bind_param("iiis", $idCliente, $idEvento, $valor, $formaPagamento);

            if (!$stmt->execute()) {
                trigger_error("Erro ao criar ingresso: " . $stmt->error);
            }

            self::subtrairContadorEvento($idEvento);
        }

        $stmt->close();
    }

    public static function buscarIngressos($evento)
    {
        $sql = "SELECT * FROM ingresso WHERE evento_id = ?";

        $stmt = self::getConexao()->prepare($sql);
        $stmt->bind_param("i", $evento);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        } else {
            trigger_error("Erro ao buscar ingressos: " . $stmt->error);
            return null;
        }
    }

    public static function excluirIngresso($ingresso)
    {
        $sql = "DELETE FROM ingresso WHERE id = ?";

        $stmt = self::getConexao()->prepare($sql);
        $stmt->bind_param("i", $ingresso);

        if ($stmt->execute()) {
            echo "Ingresso estornado com sucesso!";
        } else {
            trigger_error("Erro ao estornar ingresso: " . $stmt->error);
        }

        $stmt->close();
    }

    public static function atualizarIngresso($idIngresso, $idCliente, $idEvento, $valor, $formaPagamento)
    {
        $sql = "UPDATE ingresso SET cliente_id = ?, evento_id = ?, valor = ?, forma_pagamento = ? WHERE id = ?";

        $stmt = self::getConexao()->prepare($sql);
        $stmt->bind_param("iiisi", $idCliente, $idEvento, $valor, $formaPagamento, $idIngresso);

        if ($stmt->execute()) {
            echo "Ingresso atualizado com sucesso!";
        } else {
            trigger_error("Erro ao atualizar o ingresso: " . $stmt->error);
        }

        $stmt->close();
    }

    private static function subtrairContadorEvento($idEvento)
    {
        $sql = "UPDATE evento SET contador = contador - 1 WHERE id = ?";

        $stmt = self::getConexao()->prepare($sql);
        $stmt->bind_param("i", $idEvento);

        if ($stmt->execute() === false) {
            trigger_error("Erro ao subtrair contador do evento: " . $stmt->error);
        }

        $stmt->close();
    }
}
