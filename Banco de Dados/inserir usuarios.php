<?php
require '../include/config.php';

//Função para gerar Token (pode ser usada para recuperação de senha)
function gerarToken(){
    return bin2hex(random_bytes(16));//128bits
}
echo gerarToken(); //para chamar a função

//Dados dos usuários
$usuarios = [
    ['nome'=>'1', 'email'=> '1@gmail.com','senha'=>'1']
];

//Preparar para uma consulta SQL para inserir usuários
$parametros = $conexao->prepare("INSERT INTO usuarios(nome,email,senha,token) values(?,?,?,?)");

foreach($usuarios as $userlinha){
    $nome = $userlinha['nome'];
    $email = $userlinha['email'];
    $senha = $userlinha['senha'];
    $token = gerarToken();

    $parametros->bind_param("ssss",$nome,$email,$senha,$token);//string ou texto ou varchar
    $parametros->execute();
}
echo "Usuários cadastrados com sucesso!";

?>