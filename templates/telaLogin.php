<div id="telaLogin">
	<div class="telaLogin">
		<div class="row">
			<h2 class="textoMedio center">Fa&ccedil;a login para acessar essa &aacute;rea</h2>
		</div>
		<?php 
		if( !empty($this->erroLogin) ){ ?>
		<div class="row">
			<p class="erroLogin bg-danger"><?php echo $this->erroLogin;?></p>
		</div>
		<?php }?>
		<form action="" method="post" class="formLogin navbar-form navbar-right" id="formTelaLogin">
			<div class="row row-login form-group">
				<!-- <label for="login" class="labelLogin">Login:</label> -->
				<input type="email" id="login" name="emailTelaLogin" class="text loginUser form-control" required="required" placeholder="E-mail"/>
			</div>
			<div class="row row-login form-group">
				<!-- <label for="password" class="labelLogin">Senha:</label> -->
				<input type="password" id="password" name="passwordTelaLogin" class="text loginUser form-control" required="required" placeholder="Senha"/>
			</div>
			<div class="row row-login form-group">
				<a href="<?php echo SITE.'new-account'?>" class="btn" id="link-normal">criar conta</a>
				<input type="submit" name="submitTelaLogin" class="submit submitConcluir btn btn-success" value="Entrar"/>
			</div>
		</form>
	</div>
</div>