<?
    $idEvento = getEventoAtual()->id;
?> 

    <script type="text/javascript" src="/adm/js/lmcbutton.js"></script>
    
    <h3 class="page-title">
        <?=$translate->translate('tt_midia')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('midias')?>
            </li>
        </ul>
    </div>
        
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
               
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="titulo"><?=$translate->translate("titulo")?></label>
                        <input type="text" name="titulo" id="titulo" class="form-control campoFiltro" value="" />
                    </div>
                </div>                
            </div>
            
            
            <div class="form-actions left">
                <button type="button" class="btn default" onclick="window.location='/adm/admin/midia/listar'"><?=$translate->translate('bt_limpar')?></button>
            </div>
        
            <div class="clear"></div>
            
        </div>
    </div>
    
<? 	if(temPermissao("ADMIN_MIDIAS_INSERIR")){ ?>
        <div class="row text-center jumper-20">
            <a href="/adm/admin/midia/editar" class="icon-btn">
                <i class="fa fa-plus"></i>
                <div> <?=$translate->translate('bt_nova')?> </div>
            </a>
        </div>
<? 	} ?>

	<div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption">
            </div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
        
            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>        
                    <tr>
                    	<th class="coluna-acao hidden-print">&nbsp;</th>
                        <th><?=$translate->translate("titulo")?></th>
                        <th><?=$translate->translate("visualizar")?></th>
                        <th><?=$translate->translate("formato")?></th>
                        <th><?=$translate->translate("copiar_link")?></th>
                        <? if( temPermissao("ADMIN_MIDIAS_ALTERAR") ){ ?>
                                <th class="coluna-acao hidden-print">&nbsp;</th>
                        <? }?>
                        <? if( temPermissao("ADMIN_MIDIAS_EXCLUIR") ){ ?>
                                <th class="coluna-acao hidden-print">&nbsp;</th>
                        <? }?>
                    </tr>
                </thead>                
            </table>
		</div>
    </div>
    
    <script>
		$(document).ready(function() {
			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/midia/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										"",
										$('#titulo').val()										
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"",
										"LIKEALL"
								  ];
					}
				},
				"columns": [
					{ "data": "id",			"orderable": false, "class":"hide" },
					{ "data": "titulo",		"orderable": true },
					{ "data": "visualizar",	"orderable": false },
					{ "data": "formato",	"orderable": true },
					{ "data": "copiar",		"orderable": false }
				<?	if( temPermissao("ADMIN_MIDIAS_ALTERAR") ){ ?>
						,{ "data": "btn_alterar", "orderable": false }
				<?	} ?>
				<?	if( temPermissao("ADMIN_MIDIAS_EXCLUIR") ){ ?>
						,{ "data": "btn_excluir", "orderable": false }
				<?	} ?>
				],
				"order": [[ 0, "desc" ]]
			});
			
			$("a.foto").fancybox();
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});
			
			
				
		});		
		
		
    </script>