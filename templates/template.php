<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<link rel="stylesheet" href="<?php echo CSS.'css.css'?>">
		<link rel="stylesheet" href="<?php echo CSS.'bootstrap.css'?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<script type="text/javascript" src="<?php echo JS.'jquery-2.1.4.min.js'?>"></script>
		<script type="text/javascript" src="<?php echo JS.'bootstrap.js'?>"></script>
		<title>MK - Sistema 1</title>
	</head>
	<body>
		<div class="conteudoFrame container-fluid">
			<!---->
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container">
			        <div class="navbar-header">
			          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			            <span class="sr-only">Sistema</span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			          </button>
			          <a class="navbar-brand" href="<?php echo DIR_RAIZ;?>">Sistema</a>
			        </div>
			        <div id="navbar" class="collapse navbar-collapse">
			          	<?php if( Sessao::buscaSessao('email') ) {?>
			          	<ul class="nav navbar-nav">
			            	<li class="active"><a href="<?php echo DIR_RAIZ.'categorias'?>">Categorias</a></li>
			            	<li><a href="<?php echo DIR_RAIZ.'lancamentos'?>">Lan&ccedil;amentos</a></li>
			          	</ul>
			          	<ul class="nav navbar-nav pull-right">
				        	<li><a id="logout" role="button" href="<?php echo DIR_RAIZ.'logout'?>">Logout</a></li>
				     	</ul>
				     	<?php }?>
			        </div>
			    </div>
			</nav>
			  
			<div class="container-mk">
			    <?php echo $this->container; ?>
			</div>
		</div>
		<div class="rodapeFrame">
		</div>
	</body>
</html>