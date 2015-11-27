<?php 
	$categorias  = ((empty($_GET['page']) || strcasecmp($_GET['page'], 'categorias') == 0) ? 'class="active"' : "" );
	$lancamentos = ((!empty($_GET['page']) && strcasecmp($_GET['page'], 'lancamentos') == 0) ? 'class="active"' : "" );
	$relatorios  = ((!empty($_GET['page']) && strcasecmp($_GET['page'], 'relatorios') == 0) ? 'class="active"' : "" );
	
	$nomeUsuario = (!empty(Usuario::getNome()) ? Usuario::getNome()[0]['nome'] : '');
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<link rel="stylesheet" href="<?php echo CSS.'css.css';?>" type="text/css">
		<link rel="stylesheet" href="<?php echo CSS.'bootstrap.css';?>" type="text/css">
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
			          <a class="navbar-brand" href="<?php echo BASE;?>">Sistema</a>
			        </div>
			        <div id="navbar" class="collapse navbar-collapse">
			          	<?php if( Sessao::buscaSessao('email') ) {?>
			          	<ul class="nav navbar-nav">
			            	<li <?php echo $categorias;?>><a href="<?php echo BASE.'categorias'?>">Categorias</a></li>
			            	<li <?php echo $lancamentos;?>><a href="<?php echo BASE.'lancamentos'?>">Lan&ccedil;amentos</a></li>
			            	<li <?php echo $relatorios;?>><a href="<?php echo BASE.'relatorios'?>">Relat&oacute;rios</a></li>
			          	</ul>
			          	<ul id="ul-logout" class="nav navbar-nav pull-right">
			          		<li><label id="nomeUser" class="mk-mar-top-15 color-fff">Bem vindo, <?php echo $nomeUsuario;?></label></li>
				        	<li><a id="logout" role="button" href="<?php echo BASE.'logout'?>">Logout</a></li>
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