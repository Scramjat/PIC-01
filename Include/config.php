<?php
$servername = "localhost"; //endereço do servidor WEB ou IP
$username = "root"; //usuario do SGBD - MySQL
$password = ""; //senha do SGBD - MySQL
$dbname = "projeto01"; //nome do banco de dados

//Cria uma conexão com o banco de dados
$conexao = new mysqli($servername,$username,$password,$dbname);

if($conexao->connect_error){
    die("Conexão falhou: " . $conexao->connect_error);
    echo "conexao deu erro";
}
else{
    echo "";
}

?>