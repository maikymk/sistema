<?php

/**
 * Exibe os lancamentos agrupados por categoria para o usuario
 * O relatorio so existe se tiver um array
 * com os dados da categoria com status = 1 e 
 * os dados do tipo de lancamento com status = 1 
 * e um array com os lancamentos.
 * Relatorios sao dependentes do cadastro de todos os outros dados.
 */

//Variavel contadora para colocar as linhas alternando o background  
$i = 0;

//Variavel que recebe o filtro que o usuario colocar
$tipo = isset($_GET['filtro']) ? (int) htmlentities($_GET['filtro']) : '';
?>
<div class="container">
    <div class="row">
        <div class="col-md-7 pull-left">
            <h1>Relat&oacute;rio por categoria</h1>
        </div>
        <div class="col-md-5 pull-right mk-mar-bot-10 mk-txt-ali-rig">
            <?php if (!empty($this->tipos)) {?>
            <div class="row mk-pad-bot-10 mk-mar-top-20 col-md-10">
                <form class="form-inline" method="GET" action="">
                    <div class="form-group">
                        <label>Tipo</label> <select
                            class="mk-wid-80-prc" name="filtro">
                            <option value="">Selecione uma categoria</option>
                            <?php //monta o filtro com os tipos de lancamentos disponiveis 
                            foreach ($this->tipos as $tp) {?>
                            <option value="<?php echo $tp['id'];?>"
                                <?php echo ($tp['id'] == $tipo) ? 'selected="selected"' : '';?>><?php echo $tp['nome'];?></option>
                            <?php }?>
                        </select>
                        <button type="submit"
                            class="btn btn-primary mk-mar-top-5-neg">Filtrar</button>
                    </div>
                </form>
            </div>
            <?php }?>
            <div class="row col-md-2 mk-cle-rig">
                <a href="<?php echo BASE.'relatorios';?>" class="btn btn-default mk-mar-top-20">limpar</a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="table-categorias"
            class="table table-bordered col-md-12">
            <tr>
                <th class="col-md-2">Categoria</th>
                <th class="col-md-2">Descri&ccedil;&atilde;o</th>
                <th class="col-md-2">Valor R$</th>
                <th class="col-md-2">Data</th>
                <th class="col-md-2">Tipo</th>
                <th class="col-md-2">Usu&aacute;rio</th>
            </tr>
            <?php
            if (! empty($this->dadosRelatorio)) {
                foreach ($this->dadosRelatorio as $key => $dados) { ?>
            <tr>
                <td class="mk-ver-ali-mid <?php echo ($i %2 == 0) ? 'mk-bac-eee' : '';?>" rowspan="<?php echo count($dados)+1;?>"><?php echo $key;?></td>
            </tr>
            <?php foreach ($dados as $dado) { ?>
            <tr id="lin-cat_<?php echo $dado['id'];?>">
                <td <?php echo ($i %2 == 0) ? 'class="mk-bac-eee"' : '';?>><?php echo $dado['descricao'];?></td>
                <td <?php echo ($i %2 == 0) ? 'class="mk-bac-eee"' : '';?>><?php echo $dado['valor'];?></td>
                <td <?php echo ($i %2 == 0) ? 'class="mk-bac-eee"' : '';?>><?php echo $dado['data'];?></td>
                <td <?php echo ($i %2 == 0) ? 'class="mk-bac-eee"' : '';?>><?php echo $dado['tipo'];?></td>
                <td <?php echo ($i %2 == 0) ? 'class="mk-bac-eee"' : '';?>><?php echo $dado['usuario'];?></td>
            </tr>
            <?php
                  }
                    $i++ ;
                }
            } else { ?>
            <tr>
                <td colspan="6" class="mk-txt-ali-cen"><h3>Nenhum registro encontrado</h3></td>
            </tr>
            <?php }?>
        </table>
    </div>
</div>