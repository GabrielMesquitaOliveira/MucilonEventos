<?php

class Ingresso
{
    private $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = $con;
    }

    public function criarIngresso($idCliente, $idEvento, $valor, $formaPagamento, $quantidade)
    {
        $sql = "INSERT INTO ingresso (cliente_id, evento_id, valor, forma_pagamento) VALUES (?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);

        for ($i = 0; $i < $quantidade; $i++) {
            $stmt->bind_param("iiis", $idCliente, $idEvento, $valor, $formaPagamento);
            if ($stmt->execute() === false) {
                trigger_error("Erro ao criar ingresso: " . $stmt->error);
            }
        }
        $stmt->close();
    }

    public function buscarIngressos($evento)
    {
        $sql = "SELECT * FROM ingresso WHERE evento_id = ?";
        $stmt = $this->con->prepare($sql);
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

    public function excluirIngresso($ingresso)
    {
        $sql = "DELETE FROM ingresso WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $ingresso);

        if ($stmt->execute()) {
            echo "Ingresso estornado com sucesso!";
        } else {
            trigger_error("Erro ao estornar ingresso: " . $stmt->error);
        }
        $stmt->close();
    }

    public function atualizarIngresso($idIngresso, $idCliente, $idEvento, $valor, $formaPagamento)
    {
        $sql = "UPDATE ingresso SET cliente_id = ?, evento_id = ?, valor = ?, forma_pagamento = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("iiisi", $idCliente, $idEvento, $valor, $formaPagamento, $idIngresso);

        if ($stmt->execute()) {
            echo "Ingresso atualizado com sucesso!";
        } else {
            trigger_error("Erro ao atualizar o ingresso: " . $stmt->error);
        }
        $stmt->close();
    }
}
