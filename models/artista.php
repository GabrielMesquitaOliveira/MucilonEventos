<?php

class artista
{

    public $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = $con;
    }

    public function criarArtista($nome)
    {

        $sql = "INSERT INTO artista (nome) VALUES ('$nome')";

        $result = $this->con->query($sql);

        if ($result) {
            echo 'Artista criado com sucesso';
        } else {
            echo "Erro ao criar artista: " . mysqli_error($this->con);
        }
    }

    public function buscarArtista($artista)
    {
        $sql = "SELECT * FROM artista WHERE id = $artista";

        $result = $this->con->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function buscarArtistaId($artista)
    {
        $sql = "SELECT * FROM artista WHERE id = $artista";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {

            return $result;
        } else {

            return false;
        }
    }

    public function excluirArtista(int $artista)
    {
        $artistaExistente = $this->buscarArtistaId($artista);

        if ($artistaExistente) {
            $sql = "DELETE FROM artista WHERE id = $artista";
            $result = $this->con->query($sql);

            if ($result) {
                echo "Artista excluido com sucesso!";
            } else {
                echo "Erro ao excluir artista: " . mysqli_error($this->con);
            }
        } else {
            echo "artista não existe";
        }
    }
}
