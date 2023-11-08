<?php
class local
{

    public $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = $con;
    }

    public function criarLocal($capacidade, $localidade, $area)
    {

        $sql = "INSERT INTO local (capacidade, localidade, area) VALUES ($capacidade, '$localidade', '$area')";

        $result = $this->con->query($sql);

        if ($result) {
            echo 'local criado com sucesso';
        } else {
            echo "Erro ao criar local: " . mysqli_error($this->con);
        }
    }

    public function buscarLocal($local)
    {
        $sql = "SELECT local.area
        FROM evento
        INNER JOIN local ON evento.local_id = local.id
        WHERE local.area LIKE '%" . $local . "%'";

        $result = $this->con->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function buscarLocalId($local)
    {
        $sql = "SELECT * FROM local WHERE id = $local";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {
            // Ingresso encontrado, retornar o resultado
            return $result;
        } else {
            // Ingresso não encontrado, retornar falso
            return false;
        }
    }
    public function excluirLocal(int $local)
    {
        $localExistente = $this->buscarLocalId($local);

        if ($localExistente) {
            $sql = "DELETE FROM local WHERE id = $local";
            $result = $this->con->query($sql);

            if ($result) {
                echo "local excluido com sucesso!";
            } else {
                echo "Erro ao excluir local: " . mysqli_error($this->con);
            }
        } else {
            echo "local não existe";
        }
    }
}
