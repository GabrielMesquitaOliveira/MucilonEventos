<?php

// Namespace para o modelo de Ingresso
namespace App\models;

// Use a classe Conexao para obter acesso à conexão com o banco de dados
use App\models\Conexao;

// Classe Ingresso
class Ingresso extends Conexao
{
    /**
     * Cria ingressos no banco de dados.
     *
     * @param int    $idCliente       O ID do cliente associado aos ingressos.
     * @param int    $idEvento        O ID do evento associado aos ingressos.
     * @param float  $valor           O valor dos ingressos.
     * @param string $formaPagamento  A forma de pagamento dos ingressos.
     * @param int    $quantidade      A quantidade de ingressos a serem criados.
     *
     * @return void
     */
    public static function criarIngresso($idCliente, $idEvento, $valor, $formaPagamento, $quantidade)
    {
        // Instrução SQL para inserir um ingresso
        $sql = "INSERT INTO ingresso (cliente_id, evento_id, valor, forma_pagamento) VALUES (?, ?, ?, ?)";
        
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare($sql);

        // Loop para criar a quantidade desejada de ingressos
        for ($i = 0; $i < $quantidade; $i++) {
            // Vincula os parâmetros à instrução SQL
            $stmt->bind_param("iiis", $idCliente, $idEvento, $valor, $formaPagamento);

            // Executa a instrução SQL
            if ($stmt->execute() === false) {
                trigger_error("Erro ao criar ingresso: " . $stmt->error);
            }

            // Subtrai o contador do evento
            self::subtrairContadorEvento($idEvento);
        }

        // Fecha a instrução SQL
        $stmt->close();
    }

    /**
     * Busca ingressos de um evento no banco de dados.
     *
     * @param int $evento O ID do evento associado aos ingressos.
     *
     * @return array|null A lista de ingressos ou null em caso de erro.
     */
    public static function buscarIngressos($evento)
    {
        // Instrução SQL para selecionar ingressos de um evento
        $sql = "SELECT * FROM ingresso WHERE evento_id = ?";
        
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare($sql);

        // Vincula o parâmetro à instrução SQL
        $stmt->bind_param("i", $evento);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            // Obtém o resultado
            $result = $stmt->get_result();
            
            // Obtém todas as linhas como um array associativo
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            
            // Fecha a instrução SQL
            $stmt->close();
            
            // Retorna a lista de ingressos
            return $rows;
        } else {
            // Em caso de erro, exibe uma mensagem e retorna null
            trigger_error("Erro ao buscar ingressos: " . $stmt->error);
            return null;
        }
    }

    /**
     * Exclui um ingresso do banco de dados.
     *
     * @param int $ingresso O ID do ingresso a ser excluído.
     *
     * @return void
     */
    public static function excluirIngresso($ingresso)
    {
        // Instrução SQL para excluir um ingresso
        $sql = "DELETE FROM ingresso WHERE id = ?";
        
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare($sql);

        // Vincula o parâmetro à instrução SQL
        $stmt->bind_param("i", $ingresso);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            echo "Ingresso estornado com sucesso!";
        } else {
            // Em caso de erro, exibe uma mensagem
            trigger_error("Erro ao estornar ingresso: " . $stmt->error);
        }

        // Fecha a instrução SQL
        $stmt->close();
    }

    /**
     * Atualiza as informações de um ingresso no banco de dados.
     *
     * @param int    $idIngresso      O ID do ingresso a ser atualizado.
     * @param int    $idCliente       O novo ID do cliente associado ao ingresso.
     * @param int    $idEvento        O novo ID do evento associado ao ingresso.
     * @param float  $valor           O novo valor do ingresso.
     * @param string $formaPagamento  A nova forma de pagamento do ingresso.
     *
     * @return void
     */
    public static function atualizarIngresso($idIngresso, $idCliente, $idEvento, $valor, $formaPagamento)
    {
        // Instrução SQL para atualizar um ingresso
        $sql = "UPDATE ingresso SET cliente_id = ?, evento_id = ?, valor = ?, forma_pagamento = ? WHERE id = ?";
        
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare($sql);

        // Vincula os parâmetros à instrução SQL
        $stmt->bind_param("iiisi", $idCliente, $idEvento, $valor, $formaPagamento, $idIngresso);

        // Executa a instrução SQL
        if ($stmt->execute()) {
            echo "Ingresso atualizado com sucesso!";
        } else {
            // Em caso de erro, exibe uma mensagem
            trigger_error("Erro ao atualizar o ingresso: " . $stmt->error);
        }

        // Fecha a instrução SQL
        $stmt->close();
    }

    /**
     * Subtrai o contador de um evento no banco de dados.
     *
     * @param int $idEvento O ID do evento cujo contador será decrementado.
     *
     * @return void
     */
    private static function subtrairContadorEvento($idEvento)
    {
        // Instrução SQL para subtrair o contador do evento
        $sql = "UPDATE evento SET contador = contador - 1 WHERE id = ?";
        
        // Prepara a instrução SQL
        $stmt = self::getConexao()->prepare($sql);

        // Vincula o parâmetro à instrução SQL
        $stmt->bind_param("i", $idEvento);

        // Executa a instrução SQL
        if ($stmt->execute() === false) {
            // Em caso de erro, exibe uma mensagem
            trigger_error("Erro ao subtrair contador do evento: " . $stmt->error);
        }

        // Fecha a instrução SQL
        $stmt->close();
    }
}
