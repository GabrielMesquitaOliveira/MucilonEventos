<?php

class categoria
{

    private $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = $con;
    }
    public function criarCategoria($nome)
    {

        $sql = "INSERT INTO categoria (nome) VALEUS ('$nome')";

        $result = $this->con->query($sql);

        if ($result) {
            echo 'Categoria criado com sucesso';
        } else {
            echo "Erro ao criar categoria:" . mysqli_error($this->con);
        }
    }
    public function buscarCategoria($categoria)
    {
        $sql = "SELECT categoria.nome 
        FROM evento
        INNER JOIN categoria ON evento.categoria_id = categoria.id 
        WHERE categoria.nome LIKE '%" . $categoria . "%' ";

        $result = $this->con->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function buscarCategoriaId($categoria)
    {
        $sql = "SELECT * FROM categoria WHERE id = $categoria";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }
    public function excluirCategoria(int $categoria)
    {
        $categoriaExistente = $this->buscarCategoriaId($categoria);

        if ($categoriaExistente) {
            $sql = "DELETE FROM categoria WHERE id = $categoria";
            $result = $this->con->query($sql);

            if ($result) {
                echo "Categoria excluida com sucesso!";
            } else {
                echo "Erro ao excluir categoria:" . mysqli_error($this->con);
            }
        } else {
            echo "categoria não existe";
        }
    }
}
