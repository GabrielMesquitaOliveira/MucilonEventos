<?php

// Namespace para o modelo de Cliente, que herda de Conexao
namespace App\models;

// Classe Cliente, que herda de Conexao
class Cliente extends Conexao
{
    /**
     * Cria um novo cliente no banco de dados.
     *
     * @param string $nome     Nome do cliente a ser criado.
     * @param string $email    Endereço de e-mail do cliente.
     * @param string $senha    Senha do cliente (será hash usando Bcrypt).
     * @param string $telefone Número de telefone do cliente.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function criarCliente($nome, $email, $senha, $telefone)
    {
        // Gera o hash da senha usando Bcrypt
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        // Prepara a consulta SQL para inserir um novo cliente
        $stmt = self::getConexao()->prepare("INSERT INTO cliente (nome, email, senha, telefone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $senhaHash, $telefone);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return "Cliente cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar o cliente: " . $stmt->error;
        }
    }

    /**
     * Obtém as informações de um cliente com base no ID.
     *
     * @param int $id ID do cliente a ser obtido.
     *
     * @return mixed Retorna um array associativo com as informações do cliente ou false se não encontrado.
     */
    public static function obterCliente($id)
    {
        // Prepara a consulta SQL para obter um cliente pelo ID
        $stmt = self::getConexao()->prepare("SELECT * FROM cliente WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Obtém o resultado da consulta
        $result = $stmt->get_result();

        // Obtém as informações do cliente
        $cliente = $result->fetch_assoc();

        return $cliente;
    }

    /**
     * Atualiza as informações de um cliente com base no ID.
     *
     * @param int    $id       ID do cliente a ser atualizado.
     * @param string $nome     Novo nome do cliente.
     * @param string $email    Novo endereço de e-mail do cliente.
     * @param string $telefone Novo número de telefone do cliente.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function atualizarCliente($id, $nome, $email, $telefone)
    {
        // Prepara a consulta SQL para atualizar um cliente pelo ID
        $stmt = self::getConexao()->prepare("UPDATE cliente SET nome = ?, email = ?, telefone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $email, $telefone, $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return "Cliente atualizado com sucesso!";
        } else {
            return "Erro ao atualizar o cliente: " . $stmt->error;
        }
    }

    /**
     * Exclui um cliente com base no ID.
     *
     * @param int $id ID do cliente a ser excluído.
     *
     * @return string Mensagem indicando o resultado da operação.
     */
    public static function excluirCliente($id)
    {
        // Prepara a consulta SQL para excluir um cliente pelo ID
        $stmt = self::getConexao()->prepare("DELETE FROM cliente WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            return "Cliente excluído com sucesso!";
        } else {
            return "Erro ao excluir o cliente: " . $stmt->error;
        }
    }

    /**
     * Lista todos os clientes no banco de dados.
     *
     * @return array Retorna um array de arrays associativos contendo informações de todos os clientes.
     */
    public static function listarClientes()
    {
        // Executa a consulta SQL para obter todos os clientes
        $result = self::getConexao()->query("SELECT * FROM cliente");

        // Processa o resultado e retorna a lista de clientes
        $clientes = array();
        while ($cliente = $result->fetch_assoc()) {
            $clientes[] = $cliente;
        }

        return $clientes;
    }

    public static function autenticarCliente($email, $senha)
    {
        $cliente = self::obterClientePorEmail($email);

        if ($cliente && password_verify($senha, $cliente['senha'])) {
            // Autenticação bem-sucedida, você pode gerar o token JWT aqui
            // Exemplo básico: retornar os detalhes do cliente (ou apenas o ID) que será usado para gerar o token
            return $cliente;
        }

        return null; // Autenticação falhou
    }

    // Adicione este método para obter o cliente por email
    public static function obterClientePorEmail($email)
    {
        $stmt = self::$con->prepare("SELECT * FROM cliente WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $cliente = $result->fetch_assoc();

        return $cliente;
    }
}
