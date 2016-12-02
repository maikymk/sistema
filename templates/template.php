<?php
//recupera o nome do usuario logado
$nomeUsuario = (! empty(Usuario::getNome()) ? Usuario::getNome() : 'Visitante');
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
		
		<?php foreach (Css::getCss() as $css) { ?>
		<link rel="stylesheet" href="<?=$css;?>" type="text/css">
		<?php }?>
		
	</head>
	<body>
	    <nav class="navbar navbar-inverse navbar-fixed-top" id="menu" role="navigation">
	        <div class="container">
	            <!-- Brand and toggle get grouped for better mobile display -->
	            <div class="navbar-header">
	                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	                    <span class="sr-only">Toggle navigation</span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                </button>
	                <a class="navbar-brand" href="#">
	                    <img alt="logo" src="http://shopblob.blob.core.windows.net/1281-produtoimagem/zoom-XF041L%20(3).jpg" width="50">
	                </a>
	            </div>
	            <!-- Collect the nav links, forms, and other content for toggling -->
	            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	                <ul class="nav navbar-nav">
	                    <li>
	                        <a href="#">About</a>
	                    </li>
	                    <li>
	                        <a href="#">Services</a>
	                    </li>
	                    <li>
	                        <a href="#">Contact</a>
	                    </li>
	                </ul>
	
	                <ul class="nav navbar-nav pull-right">
	                    <li><p class="navbar-text">Bem vindo, <?=$nomeUsuario;?></p></li>
	                    <?php if (Sessao::verificaTempoSessao()) {?>
	                    <li><a role="button" href="<?=BASE.'logout'?>">Sair</a></li>
	                    <?php }?>
	                </ul>
	            </div>
	        </div>
	    </nav>
	
	    <div class="container-fluid">
	        <div>
	            <?=$this->container; ?>
	        </div>
	    </div>
	    
	    <div id="back-modal"></div>
	</body>
</html>