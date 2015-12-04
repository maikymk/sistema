<?php 
//imprime a tela para criar uma nova conta para o usuario
?>
<script type="text/javascript">
    var link = "<?php echo DIR_RAIZ.DS;?>";//monta o link
    
    $(document).ready(function () {
        $('#formTelaLogin').submit(function() {
            var nome = '';
            var sobreNome = '';
            var nascimento = '';
            var email = '';
            var senha = '';
            var senha2 = '';

            if( $('#nomeNovaConta').val() )
                nome = $('#nomeNovaConta').val();
            if( $('#sobrenomeNovaConta').val() )
                sobreNome = $('#sobrenomeNovaConta').val();
            if( $('#dataNascimentoNovaConta').val() )
                nascimento = $('#dataNascimentoNovaConta').val();
            if( $('#emailNovaConta').val() )
                email = $('#emailNovaConta').val();
            if( $('#senhaNovaConta').val() )
                senha = $('#senhaNovaConta').val();
            if( $('#senha2NovaConta').val() )
                senha2 = $('#senha2NovaConta').val();

            $.post(link+'new-account', {
                nomeNovaConta: nome,
                sobrenomeNovaConta: sobreNome,
                dataNascimentoNovaConta: nascimento,
                emailNovaConta: email,
                senhaNovaConta: senha,
                senha2NovaConta: senha2,
                validaNovaConta: 1
            }, function(data) {
                var html = '';
                
                if( data == 1 ){
                    html = '<p class="msgLogin sucessoLogin bg-success">Tudo certo por aqui!! :)</p>';
                    window.location.href = link;
                } else if( data == 0 ){
                    html += '<p class="erroLogin bg-danger">Erro ao realizar o cadastro, tente novamente.</p>';
                } else{
                    $.each(data, function(ind, val) {
                        html += '<p class="erroLogin bg-danger">'+val+'</p>';
                    });
                }

                $('.msgNovaConta').html(html);
            }, "json");
            
            return false;
        });
    });
</script>
<div id="novaConta">
    <div class="telaNovaConta">
        <div class="row">
            <h2 class="textoMedio center">Cadastre-se e fa&ccedil;a parte do nosso novo site!!</h2>
        </div>
        <div class="row msgNovaConta">
        <?php
        //verifica se teve algum erro ao criar uma nova conta
        if (!empty($this->erroNovaConta)) {
            //imprime a mensagem com os erros que aconteceram
            foreach ($this->erroNovaConta as $erro) { ?>
            <p class="msgLogin erroLogin bg-danger"><?php echo $erro;?></p>
        <?php
            }
        }
        ?>
        </div>
        <form action="" method="post" class="formLogin navbar-form navbar-right" id="formTelaLogin">
            <div class="row row-login form-group" id="nometNovaConta">
                <input type="text" id="nomeNovaConta" name="nomeNovaConta" class="text novaConta form-control" placeholder="Nome" />
            </div>
            <div class="row row-login form-group">
                <input type="text" id="sobrenomeNovaConta" name="sobrenomeNovaConta" class="text novaConta form-control" placeholder="Sobrenome" />
            </div>
            <div class="row row-login form-group" id="divDataNascimentoNovaConta">
                <input type="date" id="dataNascimentoNovaConta" name="dataNascimentoNovaConta" class="text novaConta form-control" placeholder="Data de nascimento" />
            </div>
            <div class="row row-login form-group">
                <input type="email" id="emailNovaConta" name="emailNovaConta" class="text novaConta form-control" placeholder="e-mail" />
            </div>
            <div class="row row-login form-group" id="divSenhaNovaConta">
                <input type="password" id="senhaNovaConta" name="senhaNovaConta" class="text novaConta form-control" placeholder="Senha" />
            </div>
            <div class="row row-login form-group" id="divSenha2NovaConta">
                <input type="password" id="senha2NovaConta" name="senha2NovaConta" class="text novaConta form-control" placeholder="Repita a senha" />
            </div>
            <div class="row row-login form-group" id="submitNovaConta">
                <a href="<?php echo BASE;?>" class="btn" id="link-normal">cancelar</a>
                <input type="submit" name="submitNovaConta" class="submit submitConcluir btn btn-success" value="Cadastrar" />
            </div>
        </form>
    </div>
</div>