<?php
$nameNewAccount       = !empty($_POST["nameNewAccount"]) ? htmlentities($_POST["nameNewAccount"]) : '';
$secondNameNewAccount = !empty($_POST["secondNameNewAccount"]) ? htmlentities($_POST["secondNameNewAccount"]) : '';
$birthDateNewAccount  = !empty($_POST["birthDateNewAccount"]) ? htmlentities($_POST["birthDateNewAccount"]) : '';
$emailNewAccount      = !empty($_POST["emailNewAccount"]) ? htmlentities($_POST["emailNewAccount"]) : '';
$passwordNewAccount   = !empty($_POST["passwordNewAccount"]) ? htmlentities($_POST["passwordNewAccount"]) : '';
//$senha2NewAccount      = !empty($_POST["senha2NewAccount"]) ? htmlentities($_POST["senha2NewAccount"]) : '';

Css::addCss('login-nova-conta');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Cadastre-se e faça parte do nosso novo site!!</h1>
            <div class="account-wall">
                <?php
		        //verifica se teve algum erro ao criar uma nova conta
		        if (!empty($this->error)) {
		            //imprime a mensagem com os erros que aconteceram
		            foreach ($this->error as $erro) { ?>
		            <p class="text-center text-danger"><?=$erro?></p>
		        <?php
		            }
		        }
		        ?>
                <form action="" method="post" class="form-signin">
                    <input type="text" id="nameNewAccount" name="nameNewAccount" class="text newAccount form-control" autofocus placeholder="Nome" value="<?=$nameNewAccount;?>"/>
                    <input type="text" id="secondNameNewAccount" name="secondNameNewAccount" class="text newAccount form-control" placeholder="Sobrenome" value="<?=$secondNameNewAccount;?>"/>
                    <input type="date" id="birthDateNewAccount" name="birthDateNewAccount" class="text newAccount form-control" placeholder="Data de nascimento" value="<?=$birthDateNewAccount;?>"/>
                    <input type="email" id="emailNewAccount" name="emailNewAccount" class="text newAccount form-control" placeholder="e-mail" value="<?=$emailNewAccount;?>"/>
                    <input type="password" id="passwordNewAccount" name="passwordNewAccount" class="text newAccount form-control" placeholder="Senha" value="<?=$passwordNewAccount;?>"/>
                    <!-- <input type="password" id="senha2NewAccount" name="senha2NewAccount" class="text newAccount form-control" placeholder="Repita a senha" value="<?=$senha2NewAccount;?>"/> -->
                    
                    <button class="btn btn-lg btn-primary btn-block" name="submitNewAccount" type="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>