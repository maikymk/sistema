<div class="container">
	<div class="row">
		<div class="col-md-9 pull-left">
			<h1>Lan&ccedil;amentos</h1>
		</div>
		<div class="col-md-3 pull-right mk-mar-bot-10 mk-txt-ali-rig">
			<a href="<?php echo BASE.'lancamentos'.DS.'adicionar';?>" class="btn btn-primary mk-mar-top-20">Novo</a>
		</div>
	</div>
	<div class="table-responsive">
		<table id="table-categorias" class="table table-bordered col-md-12">
			<tr>
				<th class="col-md-2">Descri&ccedil;&atilde;o</th>
				<th class="col-md-2">Valor R$</th>
				<th class="col-md-2">Data</th>
				<th class="col-md-2">Categoria</th>
				<th class="col-md-2">Tipo</th>
				<th class="col-md-2">Usu&aacute;rio</th>
			</tr>
			<?php  if( !empty($this->lancamentos) ){ 
				foreach( $this->lancamentos as $lanc ){ ?>
			<tr id="lin-cat_<?php echo $lanc['id'];?>">
				<td id="texto-categoria-<?php echo $lanc['id'];?>"><?php echo $lanc['descricao'];?></td>
				<td><?php echo $lanc['valor'];?></td>
				<td><?php echo $lanc['data'];?></td>
				<td><?php echo $lanc['categoria'];?></td>
				<td><?php echo $lanc['tipo'];?></td>
				<td><?php echo $lanc['usuario'];?></td>
			</tr>
			<?php }
				} else {?>
			<tr>
				<td colspan="6" class="mk-txt-ali-cen"><h3>Nenhum registro encontrado</h3></td>
			</tr>
			<?php }?>
		</table>
	</div>
</div>