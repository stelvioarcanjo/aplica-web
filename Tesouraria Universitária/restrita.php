<?php
require('config/conexao.php');

//VERIFICAR SE TEM AUTORIZAÇÃO

$sql=$pdo->prepare("SELECT * FROM usuarios WHERE  token=? LIMIT 1"); 
$sql->execute(array($_SESSION['TOKEN']));
$usuario=$sql->fetch(PDO::FETCH_ASSOC);
//SE NÃO ENCONTRAR O USUARIO

if(!$usuario){
    header('location: index.php');
}else{
echo "<h1> SEJA BEM VINDO <b style ='color:red'>".$usuario['nome']. "!</b></h1>";
echo"<pt><pt><a style='backgroun:green;color:white; text-decoration:none; padding:20px; border_radius:5px;' href='logout.php'> Sair do sistema</a>";
}
