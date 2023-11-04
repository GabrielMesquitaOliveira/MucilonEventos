<?php

class Ingresso
{

    public $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = new mysqli($server, $nome, $senha, $bancodados);
        if (mysqli_connect_error()) {
            trigger_error("falha ao conectar");
        } else {
            $this->con;
        }
    }

    public function criarIngresso($cliente, $evento, $valor)
    {
    }

    public function buscarIngressos($evento)
    {
        $sql = "SELECT * FROM ingresso WHERE `evento_id` = $evento";
        $result = $this->con->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}
