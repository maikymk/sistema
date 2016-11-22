<?php
//recupera o nome do usuario logado
$nomeUsuario = (! empty(Usuario::getNome()) ? Usuario::getNome() : '');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="stylesheet" href="<?=CSS.'css.css';?>" type="text/css">
<link rel="stylesheet" href="<?=CSS.'bootstrap.css';?>" type="text/css">
<link rel="icon" href="<?=BASE.'favicon.ico';?>">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<script type="text/javascript" src="<?=JS.'jquery-2.1.4.min.js'?>"></script>
<script type="text/javascript" src="<?=JS.'bootstrap.js'?>"></script>
<title>MK - Sistema 1</title>
</head>
<body>
    <div class="conteudoFrame container-fluid">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Sistema</span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?=BASE;?>">Sistema</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <?php if (Sessao::verificaTempoSessao()) {?>
                    <ul class="nav navbar-nav">
                    </ul>
                    <ul id="ul-logout" class="nav navbar-nav pull-right">
                        <li><label id="nomeUser" class="mk-mar-top-15 color-fff">Bem vindo, <?=$nomeUsuario;?></label></li>
                        <li><a id="logout" role="button" href="<?=BASE.'logout'?>">Logout</a></li>
                    </ul>
                    <?php }?>
                </div>
            </div>
        </nav>
        <div class="container-mk">
            <?=$this->container; ?>
        </div>
    </div>
    <div class="rodapeFrame"></div>
</body>
</html>