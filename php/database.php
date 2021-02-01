<?php
    date_default_timezone_set('America/Sao_Paulo');
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "recantinho";
    $connection = mysqli_connect($server, $username, $password, $database);
    if(!$connection){
        die("Ocorreu um erro na conexão com a base de dados!");
    }