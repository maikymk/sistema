<?php
/**
 * Exibe a pagina inicial da categoria para o usuario
 */
?>
<script type="text/javascript">
    var link = "<?php echo DIR_RAIZ.DS.'categorias'.DS.DS;?>";//monta o link principal
    var erro = 'Erro! Tente novamente';
        
    $(document).ready(function () {
        $('#nova-cat').click(function() {
            $(this).attr('href', '');
            $('#myModalCategoria .modal-title').html('Adicionar nova categoria');
            
            $('#myModalCategoria .modal-body').html(
                '<div class="form-group">'+
                    '<input type="text" name="nomeCat" class="form-control" id="input-nova-cat" maxlength="45" placeholder="Nome da categoria">'+
                  '</div>'
            );

            $('#myModalCategoria .modal-footer').html(
                '<button type="button" id="concluir-nova-cat" class="btn btn-primary">Concluir</button>'+
                '<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>'
            );

            $('#concluir-nova-cat').click(function() {
                var nomeCat = '';
                if( $('#input-nova-cat').val() != '' ){
                    var linkAdicionar = link+"adicionar\/aj";
                    var nomeCategoria = $('#input-nova-cat').val();
                    $(this).attr('data-dismiss', 'modal');

                    $.post(linkAdicionar, {
                        nomeCat: nomeCategoria,
                        adicionarCat: 1
                    },
                    function(data){
                        if( data ){
                            var html = ''+
                            '<tr id="lin-cat_'+data.id+'">'+
                                '<td class="col-md-9 col-sm-7 col-xs-6 mk-ver-ali-mid" id="texto-categoria-'+data.id+'">'+data.nome+'</td>'+
                                '<td class="col-md-9 col-sm-7 col-xs-6">'+
                                    '<a href="'+link+'visualizar\\'+data.id+'" id="vz_'+data.id+'" class="btn btn-sm btn-info modal-vis-cat" data-toggle="modal" data-target="#myModalCategoria">Visualizar</a> '+
                                    '<a href="'+link+'editar\\'+data.id+'" id="ed_'+data.id+'" class="btn btn-sm btn-warning edi-cat">Editar</a> '+
                                    '<a href="'+link+'remover\\'+data.id+'" id="rm_'+data.id+'" class="btn btn-sm btn-danger rem-cat">Apagar</a> '+
                                '</td>'+
                            '</tr>';

                            $('.sem-registro').remove(0);
                            $('#table-categorias').append(html);
                            
                            $(".modal-vis-cat").click(function(){
                                $(this).attr('href', '');
                                visualizaModal(this);
                            });
                            
                            $('.edi-cat').click(function() {
                                editarCategoria(this);
                                return false;
                            });

                            $('.rem-cat').click(function() {
                                removerCategoria(this);
                                return false;
                            });
                        }
                        else{
                            alert(erro);
                        }
                    }, 
                    "json");
                } else{
                    alert('Erro! Preencha o campo antes de concluir.');
                }
            });
        });
        
        $(".modal-vis-cat").click(function(){
            $(this).attr('href', '');
            visualizaModal(this);
        });
        
        $('.edi-cat').click(function() {
            editarCategoria(this);
            return false;
        });

        $('.rem-cat').click(function() {
            removerCategoria(this);
            return false;
        });
    });

    function visualizaModal(tag){
        var id = $(tag).attr('id').split('_');
        id = id[1];
        
        var texto = $('#texto-categoria-'+id).html();
        $('#myModalCategoria .modal-title').html('Visualizar categoria');
        $('#myModalCategoria .modal-body').html('<p>'+texto+'</p>');
        $('#myModalCategoria .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>');
    }
    
    function editarCategoria(tag){
        var id = $(tag).attr('id').split('_');
        id = id[1];
        
        var texto = $('#texto-categoria-'+id).html();

        $('#texto-categoria-'+id).html('<input type="text" maxlength="45" class="form-control" id="edit-cat_'+id+'">').after(function() {
            $('#edit-cat_'+id).val(texto);
            $('#edit-cat_'+id).focus();
            $('#edit-cat_'+id).blur(function() {
                var texto1 = $(this).val();
                
                if( texto1 == '' ){
                    alert("Erro! Preencha o campo.");
                    erroEditar(id);
                } else if( texto == texto1 ){
                    $('#texto-categoria-'+id).html(texto);
                    $('#edit-cat_'+id).removeAttr('style');
                } else{
                    var linkEditar = link+"editar\/"+id+"\/aj";

                    $.post(linkEditar, {
                        editarCat: 1,
                        nomeCat: texto1
                    },
                    function(data){
                        if( data ){
                            $('#edit-cat_'+data.id).removeAttr('style');
                            $('#texto-categoria-'+data.id).html(data.nome);
                        } else{
                            erroEditar(data.id);
                            $('#texto-categoria-'+data.id).html();
                            alert(erro);
                        }
                    }, "json");
                }
            });
        });
    }

    function erroEditar(id){
        $('#edit-cat_'+id).focus();
        $('#edit-cat_'+id).css({
            'background': 'rgb(255, 228, 228)',
            'border': '1px solid rgb(162, 0, 0)'
        });
    }
    
    function removerCategoria(tag){
        var id = $(tag).attr('id').split('_');
        id = id[1];
        
        var linkRemover = link+"remover\/"+id+"\/aj";
        $.post(linkRemover, {
            removerCat: 1
        }, function(data){
            if( data ){
                $('#lin-cat_'+id).fadeOut(100);
            } else{
                alert(erro);
            }
        }, "json");
    }/**/
    
</script>
<div class="container">
    <div class="row">
        <div class="col-md-9 pull-left">
            <h1>Categorias</h1>
        </div>
        <div class="col-md-3 pull-right mk-mar-bot-10 mk-txt-ali-rig">
            <a href="<?php echo DIR_RAIZ.'categoria'.DS.'adicionar';?>" class="btn btn-primary mk-mar-top-20" id="nova-cat" data-toggle="modal" data-target="#myModalCategoria">Nova</a>
        </div>
    </div>
    <div class="table-responsive">
        <table id="table-categorias" class="table table-bordered col-md-12">
            <tr>
                <th class="col-md-9 col-sm-7 col-xs-6">Nome categoria</th>
                <th class="col-md-3 col-sm-5 col-xs-6">A&ccedil;&otilde;es</th>
            </tr>
            <?php
            //verifica se existe alguma categoria
            if (! empty($this->categorias)) {
                foreach ($this->categorias as $cat) {
                    ?>
            <tr id="lin-cat_<?php echo $cat['id'];?>">
                <td class="col-md-9 col-sm-7 col-xs-6 mk-ver-ali-mid" id="texto-categoria-<?php echo $cat['id'];?>"><?php
                    /**
                     * Decodifica o html e exibe para o usuario.
                     * Faz isso porque o usuario pode ter inserido alguma
                     * tag que e aceita quando cadastrou a categoria.
                     */
                    echo html_entity_decode($cat['nome']); 
                ?></td>
                <td class="col-md-3 col-sm-5 col-xs-6">
                    <a href="<?php echo BASE.'categoria'.DS.'visualizar'.DS.$cat['id'];?>" id="vz_<?php echo $cat['id'];?>" class="btn btn-sm btn-info modal-vis-cat" data-toggle="modal" data-target="#myModalCategoria">Visualizar</a>
                    <a href="<?php echo BASE.'categoria'.DS.'editar'.DS.$cat['id'];?>" id="ed_<?php echo $cat['id'];?>" class="btn btn-sm btn-warning edi-cat">Editar</a>
                    <a href="<?php echo BASE.'categoria'.DS.'remover'.DS.$cat['id'];?>" id="rm_<?php echo $cat['id'];?>" class="btn btn-sm btn-danger rem-cat">Apagar</a>
                </td>
            </tr>
            <?php
                }
            } else {
                ?>
            <tr class="sem-registro">
                <td colspan="6" class="mk-txt-ali-cen"><h3>Nenhum registro encontrado</h3></td>
            </tr>
            <?php }?>
        </table>
    </div>
    <!-- Modal visualizar -->
    <div class="modal fade" id="myModalCategoria" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>