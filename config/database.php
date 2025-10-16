<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "wattsup";

$conn = new mysqli($host, $username, $password, $database);

if($conn->connect_error){
    die("Erro na conexão". $conn->connect_error);
}else{
    echo"Conexão bem sucedida";
}

$conn->set_charset("utf8");

