<?php

class Cliente
{
    private $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = $con;
    }

    public function criarCliente($nome, $email, $senha, $telefone)
    {
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        $stmt = $this->con->prepare("INSERT INTO cliente (nome, email, senha, telefone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $senhaHash, $telefone);

        if ($stmt->execute()) {
            return "Cliente cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar o cliente: " . $stmt->error;
        }
    }

    public function obterCliente($id)
    {
        $stmt = $this->con->prepare("SELECT * FROM cliente WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $cliente = $result->fetch_assoc();

        return $cliente;
    }

    public function atualizarCliente($id, $nome, $email, $telefone)
    {
        $stmt = $this->con->prepare("UPDATE cliente SET nome = ?, email = ?, telefone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $email, $telefone, $id);

        if ($stmt->execute()) {
            return "Cliente atualizado com sucesso!";
        } else {
            return "Erro ao atualizar o cliente: " . $stmt->error;
        }
    }

    public function excluirCliente($id)
    {
        $stmt = $this->con->prepare("DELETE FROM cliente WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return "Cliente excluído com sucesso!";
        } else {
            return "Erro ao excluir o cliente: " . $stmt->error;
        }
    }

    public function listarClientes()
    {
        $result = $this->con->query("SELECT * FROM cliente");

        $clientes = array();
        while ($cliente = $result->fetch_assoc()) {
            $clientes[] = $cliente;
        }

        return $clientes;
    }
}
