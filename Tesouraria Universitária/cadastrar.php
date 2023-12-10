
<?php
require('config/conexao.php');
//Verifica se a postagem exite de acordo com os campos
if(isset($_POST['nome_completo'])  && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
//Verifica todos os campos se foram preenchidos
  if(empty($_POST['nome_completo']) or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha']) or empty($_POST['termos'])){
    $erro_geral="Todos os campos são obrigatórios";
  }
  else{
// Receber valores do post e limpar

    $nome = limparPost($_POST['nome_completo']);
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $senha_cript = sha1($senha);
    $repete_senha = limparPost($_POST['repete_senha']);
    $checkbox =limparPost($_POST['termos']);
//Verificar se nome éapenas letra e espaço   
if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
    $erro_nome = "Somente permitido letras e espaços em branco!";
  }
  
  // Verificar se email é valido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro_email = "Formato de email invalido";
  }

  // Verificar se a senha tem mais de 6 digitos
if(strlen($senha) < 6){
    $erro_senha= "senha deve ter 6 caracteres ou mais";
}

// Verificar se a senha é igual a senha
if($senha !== $repete_senha){
    $erro_repete_senha = "Senha e repetição de senha diferente";
}

 
//Verificar se checkbox foi marcado
if($checkbox!=="ok"){
    $erro_checkbox ="Desativado";
}

if(!isset($erro_geral)&& !isset($erro_nome) && !isset($erro_email) && !isset($senha) && !isset($repete_senha) && !isset($erro_checkbox )){
    //Verificar se o usuario ja esta cadastro no banco
    $sql =$pdo->prepare("SELECT * FROM usuarios where email=? AND  LIMIT 1");
    $sql->execute(array($email));
    $usuario= $sql->fetch();
    // Se não existir usuario adicionar no banco
    if(!$usuario){
        $recupera_senha="";
        $token="";
        $status="novo";
        $data_cadastro= date('d/m/y');
        $sql=$pdo->prepare("INSERT INTO usuarios VALUES(null,?,?,?,?,?,?,?)");
        if($sql->execute(array($nome,$email,$senha_cript,$recupera_senha,$token,$status))){
            header('location:index.php?result-ok');
        }
    }else{
        
        // Já existe usuario apresentar erro!
        $erro_geral="usuário ja cadastrado!";
    }
}
 
}
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-s cale=1.0">
    <link href="css/estilo.css"rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <title>Cadastrar</title>
</head>
<body>
    <form method="post">
        <h1>cadastrar</h1>

        <?php if(isset($erro_geral)){?>
    <div class="erro-geral animate__animated animate__rubberBand">
        <?php echo "$erro_geral"; ?>
      </div>
       <?php } ?>
       

        <div class="input-group">
            <img class="input-icon" src="img/nomecompleto.png">
            <input  <?php if(isset($erro_geral) or isset($erro_nome)){echo 'class="erro-input"';}?>name="nome_completo" placeholder="Nome completo" <?php if(isset($_POST['nome_completo'])){echo"value='".$_POST['nome_completo']."'"; }?>
             required >
           <?php if(isset($erro_nome)){ ?>
           <div class="erro"><?php echo $erro_nome; ?></div>
            <?php } ?>
        </div>

            <div class="input-group">
                <img class="input-icon" src="img/email.png">
                <input <?php if(isset($erro_geral) or isset($erro_email)){echo 'class="erro-input"';}?> type="email" name="email" placeholder="Digite seu melhor e-mail" <?php if(isset($email)){echo"value='$email'"; }?> <?php if(isset($_POST['email'])){echo"value='".$_POST['email']."'"; }?> required>
                <?php if(isset($erro_email)){ ?>
           <div class="erro"><?php echo $erro_email; ?></div>
            <?php } ?>
                </div>

                <div class="input-group">
                    <img class="input-icon" src="img/senha2.png">
                    <input type="password" <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input"';}?> name="senha" placeholder="Senha de minimo 6 digitos" <?php if(isset($senha)){echo"value='$senha'"; }?>  <?php if(isset($_POST['senha'])){echo"value='".$_POST['senha']."'"; }?>required>
                    <?php if(isset($erro_senha)){ ?>
           <div class="erro"><?php echo $erro_senha; ?></div>
            <?php } ?>
                    </div>
                    <div class="input-group">
                        <img class="input-icon" src="img/senha2.png">
                        <input type="password" <?php if(isset($erro_geral) or isset($erro_repete_senha)){echo 'class="erro-input"';}?> name="repete_senha" placeholder=" Repita a Senha"  <?php if(isset($repete_senha)){echo"value='$repete_senha'"; }?>  <?php if(isset($_POST['repete_senha'])){echo"value='".$_POST['repete_senha']."'"; }?>required>
                        <?php if(isset($erro_repete_senha)){ ?>
           <div class="erro"><?php echo $erro_repete_senha; ?></div>
            <?php } ?>
                        </div>
          
                <div <?php if(isset($erro_geral) or isset($erro_checkbox)){echo 'class="  input-group erro-input"';}else{echo 'class="  input-group"';}?> >
                  <input type="checkbox" id="termos" name="termos" value="ok" >  
                  <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de Privacidade </a> e os <a class="link" href="#">Termos de Uso </a>

                </div>
                    

             

           
       
        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="Index.php">Já tenho uma conta</a>

    </form> 
        
</body>
           </php>