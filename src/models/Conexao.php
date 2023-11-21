<?php

// Namespace para o modelo de Conexao
namespace App\models;

// Classe Conexao
class Conexao
{
    // Propriedade estática para armazenar a conexão
    protected static $con;

    /**
     * Método para estabelecer a conexão com o banco de dados.
     */
    public static function conectar()
    {
        // Cria uma nova instância de mysqli (substitua as credenciais conforme necessário)
        self::$con = new \mysqli("localhost", "root", "", "mucilon");

        // Verifica se houve algum erro na conexão
        if (mysqli_connect_error()) {
            // Gera um erro em caso de falha na conexão
            trigger_error("Falha ao conectar: " . mysqli_connect_error());
        }
    }

    /**
     * Método para obter a conexão com o banco de dados.
     *
     * @return \mysqli A instância do objeto de conexão com o banco de dados.
     */
    public static function getConexao()
    {
        // Verifica se a conexão já foi estabelecida
        if (!self::$con) {
            // Se não, estabelece a conexão
            self::conectar();
        }

        // Retorna a instância da conexão
        return self::$con;
    }
}
