<?php

class organizador
{

    public $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = $con;
    }

    public function criarOrganizador($nome, $email)
    {

        $sql = "INSERT INTO organizador (nome, email) VALUES ('$nome' , '$email')";

        $result = $this->con->query($sql);

        if ($result) {
            echo 'Organizador criado com sucesso';
        } else {
            echo "Erro ao criar Organizador: " . mysqli_error($this->con);
        }
    }

    public function buscarOrganizador($organizador)
    {
        $sql = "SELECT organizador.nome
        FROM evento
        INNER JOIN organizador ON evento.organizador_id = organizador.id
        WHERE organizador.nome LIKE '%" . $organizador . "%'";

        $result = $this->con->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function buscarOrganizadorId($organizador)
    {
        $sql = "SELECT * FROM organizador WHERE id = $organizador";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {
            // Ingresso encontrado, retornar o resultado
            return $result;
        } else {
            // Ingresso não encontrado, retornar falso
            return false;
        }
    }
    public function excluirOrganizador(int $organizador)
    {
        $organizadorExistente = $this->buscarOrganizadorId($organizador);

        if ($organizadorExistente) {
            $sql = "DELETE FROM organizador WHERE id = $organizador";
            $result = $this->con->query($sql);

            if ($result) {
                echo "Organizador excluido com sucesso!";
            } else {
                echo "Erro ao excluir organizador: " . mysqli_error($this->con);
            }
        } else {
            echo "organizador não existe";
        }
    }
}
