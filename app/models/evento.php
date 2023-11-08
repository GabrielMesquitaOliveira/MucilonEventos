<?php

class Evento
{
    private $con;

    public function __construct()
    {
        include 'conexao.php';
        $this->con = $con;
    }

    public function criarEvento($nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador)
    {
        $stmt = $this->con->prepare("INSERT INTO evento (nome_evento, data_evento, local, descricao, artista_id, categoria_id, evento_id, local_id, contador) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiiii", $nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador);

        if ($stmt->execute()) {
            return "Evento cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar o evento: " . $stmt->error;
        }
    }

    public function obterEvento($id)
    {
        $stmt = $this->con->prepare("SELECT * FROM evento WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $evento = $result->fetch_assoc();

        return $evento;
    }

    public function atualizarEvento($id, $nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador)
    {
        $stmt = $this->con->prepare("UPDATE evento SET nome_evento = ?, data_evento = ?, local = ?, descricao = ?, artista_id = ?, categoria_id = ?, evento_id = ?, local_id = ?, contador = ? WHERE id = ?");
        $stmt->bind_param("ssssiiiiii", $nomeEvento, $dataEvento, $local, $descricao, $artistaId, $categoriaId, $eventoId, $localId, $contador, $id);

        if ($stmt->execute()) {
            return "Evento atualizado com sucesso!";
        } else {
            return "Erro ao atualizar o evento: " . $stmt->error;
        }
    }

    public function excluirEvento($id)
    {
        $stmt = $this->con->prepare("DELETE FROM evento WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return "Evento excluído com sucesso!";
        } else {
            return "Erro ao excluir o evento: " . $stmt->error;
        }
    }

    public function listarEventos()
    {
        $result = $this->con->query("SELECT * FROM evento");

        $eventos = array();
        while ($evento = $result->fetch_assoc()) {
            $eventos[] = $evento;
        }

        return $eventos;
    }
}
