<?php
    date_default_timezone_set('America/Sao_Paulo');
    mb_internal_encoding('UTF-8');
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "recantinho";
    $connection = mysqli_connect($server, $username, $password, $database);
    if(!$connection){
        die("Ocorreu um erro na conexão com a base de dados!");
    }
    mysqli_set_charset($connection, "utf8");