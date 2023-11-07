<?php

require 'models/ingresso.php';

$ingresso = new Ingresso;

echo '<pre>';
var_dump($ingresso->buscarIngressos(8));
echo '</pre>';
echo 'teste';