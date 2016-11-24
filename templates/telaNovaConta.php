<?php
    $nomeNovaConta           = !empty($_POST["nomeNovaConta"]) ? htmlentities($_POST["nomeNovaConta"]) : '';
    $sobrenomeNovaConta      = !empty($_POST["sobrenomeNovaConta"]) ? htmlentities($_POST["sobrenomeNovaConta"]) : '';
    $dataNascimentoNovaConta = !empty($_POST["dataNascimentoNovaConta"]) ? htmlentities($_POST["dataNascimentoNovaConta"]) : '';
    $emailNovaConta          = !empty($_POST["emailNovaConta"]) ? htmlentities($_POST["emailNovaConta"]) : '';
    $senhaNovaConta          = !empty($_POST["senhaNovaConta"]) ? htmlentities($_POST["senhaNovaConta"]) : '';
    $senha2NovaConta         = !empty($_POST["senha2NovaConta"]) ? htmlentities($_POST["senha2NovaConta"]) : '';
?>

<div id="novaConta">
    <div class="telaNovaConta">
        <div class="row">
            <h2 class="textoMedio center">Cadastre-se e fa&ccedil;a parte do nosso novo site!!</h2>
        </div>
        <div class="row msgNovaConta">
        <?php
        //verifica se teve algum erro ao criar uma nova conta
        if (!empty($this->erros)) {
            //imprime a mensagem com os erros que aconteceram
            foreach ($this->erros as $erro) { ?>
            <p class="msgLogin erroLogin bg-danger"><?=$erro;?></p>
        <?php
            }
        }
        ?>
        </div>
        <form action="" method="post" class="formLogin navbar-form navbar-right" id="formTelaLogin">
            <div class="row row-login form-group" id="nometNovaConta">
                <input type="text" id="nomeNovaConta" name="nomeNovaConta" class="text novaConta form-control" placeholder="Nome" value="<?=$nomeNovaConta;?>"/>
            </div>
            <div class="row row-login form-group">
                <input type="text" id="sobrenomeNovaConta" name="sobrenomeNovaConta" class="text novaConta form-control" placeholder="Sobrenome" value="<?=$sobrenomeNovaConta;?>"/>
            </div>
            <div class="row row-login form-group" id="divDataNascimentoNovaConta">
                <input type="date" id="dataNascimentoNovaConta" name="dataNascimentoNovaConta" class="text novaConta form-control" placeholder="Data de nascimento" value="<?=$dataNascimentoNovaConta;?>"/>
            </div>
            <div class="row row-login form-group">
                <input type="email" id="emailNovaConta" name="emailNovaConta" class="text novaConta form-control" placeholder="e-mail" value="<?=$emailNovaConta;?>"/>
            </div>
            <div class="row row-login form-group" id="divSenhaNovaConta">
                <input type="password" id="senhaNovaConta" name="senhaNovaConta" class="text novaConta form-control" placeholder="Senha" value="<?=$senhaNovaConta;?>"/>
            </div>
            <div class="row row-login form-group" id="divSenha2NovaConta">
                <input type="password" id="senha2NovaConta" name="senha2NovaConta" class="text novaConta form-control" placeholder="Repita a senha" value="<?=$senha2NovaConta;?>"/>
            </div>
            <div class="row row-login form-group" id="submitNovaConta">
                <a href="<?=BASE;?>" class="btn" id="link-normal">cancelar</a>
                <input type="submit" name="submitNovaConta" class="submit submitConcluir btn btn-success" value="Cadastrar" />
            </div>
        </form>
    </div>
</div>