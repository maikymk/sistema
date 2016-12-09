<?php
Css::addCss('login-nova-conta');
?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Faça login para acessar essa área</h1>
            <div class="account-wall">
                <?php
                //se tiver algum erro ao tentar fazer login, imprime ele
                if (!empty($this->error)) { ?>
                <div class="row">
                    <p class="text-center text-danger"><?=$this->error;?></p>
                </div>
                <?php }?>
                <form action="" method="post" class="form-signin">
                    <input type="email" name="emailLogin" class="form-control" required placeholder="E-mail" autofocus="" />
                    <input type="password" name="passwordLogin" class="form-control" required placeholder="Senha" />

                    <button class="btn btn-lg btn-primary btn-block" name="submitLogin" type="submit">Entrar</button>

                    <label class="checkbox pull-left">
                        <input type="checkbox" value="remember-me">
                        Lembrar meus dados
                    </label>
                    
                    <a href="#" class="pull-right need-help">Esqueci minha senha </a><span class="clearfix"></span>
                </form>
            </div>
            <a href="<?=BASE.'new-account'?>" class="text-center new-account">Criar conta</a> 
        </div>
    </div>
</div>