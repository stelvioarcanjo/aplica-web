<?php 
require('config/conexao.php');
if(isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])){
// Receber os dados vindo do post e limpar
$email= limparPost($_POST['email']);
$senha= limparPost($_POST['senha']);
 $senha_script= sha1($senha);

 //Verificar se existe este usuario
 $sql = $pdo->prepare("SELECT *FROM usuarios WHERE emal=? AND senha=? LIMIT 1");
 $sql->execute(array($email,$senha_script));
 $usuario=$sql->fetch(PDO::FETCH_ASSOC);
 if($usuario){
   //exite um usuario
    //criar um token
    $token = sha1(uniqid().date('d-m-y-H-i-s'));

    //Atualia o token deste usuario no banco
    $sql=$pdo->prepare("UPDATE usuarios SET token=? WHERE email=?  AND  senha=?");
    if($sql->execute(array($token,$email, $senha_script))){
        //Armezenar este token na sessão
        $_SESSION['TOKEN']=$token;
        header('location:restrita.php');

    }
 }else{
    $erro_login="Usuário ou senha incorretos!";
 }
}

?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css"rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <title>login</title>
</head>
<body>
    <form method="post">
        <h2>Login</h2>
        
<?php if (isset($_GET['result']) && ($_GET['result']=="ok" )){ ?>
    <div class="sucesso animate__animated animate__bounce">
    cadastrado com sucesso!
    </div>
    <?php }?>

    
    <?php if(isset($erro_login)){?>
    <div class="erro-geral animate__animated animate__rubberBand">
        <?php echo "$erro_login"; ?>
      </div>
       <?php } ?>
       
   
    

        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input type="email" placeholder="Digite seu email">
            </div>

            <div class="input-group">
                <img class="input-icon" src="img/senha2.png">
                <input type="email" placeholder="Digite seu email">
                </div>
            
           
       
        <button class="btn-blue" type="submit">Fazer Login</button>
        <a href="cadastrar.php">Ainda não tenho cadstro</a>

    </form> 
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        setTimeout(() => {
            $('.sucesso').addClass('oculto');
        },300);
    </script> 
            
            

            
</body>
</html>