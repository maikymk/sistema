<?php
$nomeNovaConta           = !empty($_POST["nomeNovaConta"]) ? htmlentities($_POST["nomeNovaConta"]) : '';
$sobrenomeNovaConta      = !empty($_POST["sobrenomeNovaConta"]) ? htmlentities($_POST["sobrenomeNovaConta"]) : '';
$dataNascimentoNovaConta = !empty($_POST["dataNascimentoNovaConta"]) ? htmlentities($_POST["dataNascimentoNovaConta"]) : '';
$emailNovaConta          = !empty($_POST["emailNovaConta"]) ? htmlentities($_POST["emailNovaConta"]) : '';
$senhaNovaConta          = !empty($_POST["senhaNovaConta"]) ? htmlentities($_POST["senhaNovaConta"]) : '';
$senha2NovaConta         = !empty($_POST["senha2NovaConta"]) ? htmlentities($_POST["senha2NovaConta"]) : '';

Css::addCss('tela-login-nova-conta');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Cadastre-se e faça parte do nosso novo site!!</h1>
            <div class="account-wall">
                <?php
		        //verifica se teve algum erro ao criar uma nova conta
		        if (!empty($this->erros)) {
		            //imprime a mensagem com os erros que aconteceram
		            foreach ($this->erros as $erro) { ?>
		            <p class="text-center text-danger"><?=$erro?></p>
		        <?php
		            }
		        }
		        ?>
                <form action="" method="post" class="form-signin">
                    <input type="text" id="nomeNovaConta" name="nomeNovaConta" class="text novaConta form-control" autofocus="" placeholder="Nome" value="<?=$nomeNovaConta;?>"/>
                    <input type="text" id="sobrenomeNovaConta" name="sobrenomeNovaConta" class="text novaConta form-control" placeholder="Sobrenome" value="<?=$sobrenomeNovaConta;?>"/>
                    <input type="date" id="dataNascimentoNovaConta" name="dataNascimentoNovaConta" class="text novaConta form-control" placeholder="Data de nascimento" value="<?=$dataNascimentoNovaConta;?>"/>
                    <input type="email" id="emailNovaConta" name="emailNovaConta" class="text novaConta form-control" placeholder="e-mail" value="<?=$emailNovaConta;?>"/>
                    <input type="password" id="senhaNovaConta" name="senhaNovaConta" class="text novaConta form-control" placeholder="Senha" value="<?=$senhaNovaConta;?>"/>
                    <!-- <input type="password" id="senha2NovaConta" name="senha2NovaConta" class="text novaConta form-control" placeholder="Repita a senha" value="<?=$senha2NovaConta;?>"/> -->
                    
                    <button class="btn btn-lg btn-primary btn-block" name="submitNovaConta" type="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>