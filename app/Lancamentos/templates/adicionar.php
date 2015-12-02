<script type="text/javascript" src="<?php echo JS.'mask-money.js';?>"></script>
<script type="text/javascript" src="<?php echo JS.'masked-input.js';?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
	    $("#valor").maskMoney();
	    $("#data").mask('99/99/9999');
	});
</script>

<?php 
$campos['descricao'] = isset($_POST['descLancamento']) ? htmlentities($_POST['descLancamento']) : '';
$campos['valor'] 	 = isset($_POST['valorLancamento']) ? htmlentities($_POST['valorLancamento']) : '';
$campos['data']  	 = isset($_POST['dataLancamento']) ? htmlentities($_POST['dataLancamento']) : '';
$campos['categoria'] = isset($_POST['categLancamento']) ? htmlentities($_POST['categLancamento']) : '';
$campos['tipo']  	 = isset($_POST['tipoLancamento']) ? htmlentities($_POST['tipoLancamento']) : '';

$selected = 'selected="selected"';
?>

<div class="container">
	<div class="row">
		<div class="col-md-11">
			<h1>Adicionar novo lan&ccedil;amento</h1>
		</div>
		<div class="col-md-1">
			<a href="<?php echo BASE.'lancamentos';?>" class="btn btn-default mk-mar-top-20">voltar</a>
		</div>
	</div>
	<?php if(!empty($this->erros)){ ?>
	<div class="row col-md-12 mk-mar-top-20">
	<?php foreach( $this->erros as $erro ){?>
		<p class="mk-mar-aut mk-wid-50-prc bg-danger msgNovaConta mk-erro mk-mar-bot-10"><?php echo $erro;?></p>
	<?php }?>
	</div>
	<?php }?>
	<?php if(!empty($this->sucessos)){?>
	<div class="row col-md-12 mk-mar-top-20">
	<?php foreach( $this->sucessos as $sucesso ){?>
		<p class="mk-mar-aut mk-wid-50-prc bg-success msgNovaConta mk-sucesso mk-mar-bot-10"><?php echo $sucesso;?></p>
	<?php }?>
	</div>
	<?php }
	if( !empty($this->categorias) ){?>
	<form class="mk-pad-bot-10" method="POST" action="">
		<div class="row mk-mar-bot-10 mk-pad-rig-lef-15">
			<label for="descricao">Descri&ccedil;&atilde;o</label>
			<textarea maxlength="<?php echo TAM_MAX_DESC;?>" class="form-control" id="descricao" name="descLancamento" rows="3" placeholder="Descri&ccedil;&atilde;o" required="required"><?php echo $campos['descricao']?></textarea>
  		</div>
  		<div class="row mk-mar-bot-10 mk-pad-0">
  			<div class="col-md-6">
  				<label for="valor">Valor</label>
    			<input type="text" id="valor" placeholder="R$ " name="valorLancamento" class="form-control" value="<?php echo $campos['valor']?>" data-thousands="." data-decimal="," required="required"/>
  			</div>
			<div class="col-md-6">
				<label for="data">Data</label>
				<input type="text" id="data" name="dataLancamento" class="form-control" placeholder="Data" value="<?php echo $campos['data']?>" required="required" min="<?php echo date('Y-m-d', strtotime('today'))?>">
			</div>
  		</div>
  		<div class="row mk-mar-bot-10 mk-pad-0">
  			<div class="col-md-6">
  				<label for="categoria">Categoria</label>
    			<select id="categoria" class="mk-wid-100-prc" name="categLancamento">
	    			<option value="">Selecione uma categoria</option>
	    			<?php foreach( $this->categorias as $cat ){?>
	    			<option value="<?php echo $cat['id'];?>" <?php echo ($cat['id'] == $campos['categoria']) ? $selected : '';?>><?php echo $cat['nome'];?></option>
    			<?php }?>
    			</select>
  			</div>
  			<div class="col-md-6">
  				<label for="tipo">Tipo</label>
    			<select id="tipo" class="mk-wid-100-prc" name="tipoLancamento">
	    			<option value="">Selecione um tipo de receita</option>
	    			<?php foreach( $this->receitas as $rec ){?>
	    			<option value="<?php echo $rec['id'];?>" <?php echo ($rec['id'] == $campos['tipo']) ? $selected : '';?>><?php echo $rec['nome'];?></option>
    			<?php }?>
    			</select>
  			</div>
  		</div>
  		<div class="row mk-pad-rig-lef-15 mk-txt-ali-rig">
  			<button type="submit" name="adicionarLancamento" class="btn btn-primary">Cadastrar</button>
		</div>
	</form>
	<?php } else {?>
	<div class="row">
		<div class="col-md-12">
			<h4 class="mk-mar-aut mk-wid-50-prc bg-danger msgNovaConta mk-erro mk-mar-top-20">Cadastre primeiro uma categoria.</h4>
		</div>
	</div>
	<?php }?>
</div>