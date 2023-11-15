<?php

namespace App\models;

class Conexao
{
    protected static $con;

    public static function conectar()
    {
        self::$con = new \mysqli("localhost", "root", "", "mucilon");
        if (mysqli_connect_error()) {
            trigger_error("Falha ao conectar: " . mysqli_connect_error());
        }
    }

    public static function getConexao()
    {
        if (!self::$con) {
            self::conectar();
        }
        return self::$con;
    }
}
