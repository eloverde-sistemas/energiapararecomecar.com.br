	<h3 class="page-title">
        <?=$translate->translate('segmentos_lojistas')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('lojas')?>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('segmentos')?>
            </li>
		</ul>
    </div>
    
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption">
                <?=$translate->translate("filtro_avancado")?>
            </div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
        
            <div class="form-body">            
               
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="codigo"><?=$translate->translate('codigo')?></label>
                            <input type="text" name="codigo" id="codigo" class="form-control campoFiltro">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="nome"><?=$translate->translate('nome')?></label>
                            <input type="text" name="nome" id="nome" class="form-control campoFiltro">
                        </div>
                    </div>
                    
          		</div>
                
			</div>
		</div>          
	</div>
    
   
<?	if( temPermissao(array("ADMIN_CARGOS_ALTERAR","ADMIN_CARGOS-RITO_VISUALIZAR")) ){ ?>
		<div class="row text-center jumper-20">
        
		<?	if( temPermissao("ADMIN_CARGOS_ALTERAR") ){ ?>        
                <a href="/adm/admin/segmentos/editar" class="icon-btn">
                    <i class="fa fa-plus"></i>
                    <div> <?=$translate->translate('bt_novo')?> </div>
                </a>
        <?	}?>
        <?	if( temPermissao("ADMIN_CARGOS-RITO_VISUALIZAR") ){ ?>
        		<a href="/adm/admin/lojas/listar" class="icon-btn" target="_blank">
                    <i class="fa fa-gears"></i>
                    <div> <?=$translate->translate('gerenciar_lojas')?> </div>
                </a>
        <?	}?>
        
		</div>
<?	} ?>

    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption">
                &nbsp;
            </div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>
                    <tr>
                        <th><?=$translate->translate("codigo")?></th>
                        <th><?=$translate->translate("nome")?></th>
                        <?	if( temPermissao("ADMIN_CARGOS_ALTERAR") ){ ?>
                                <th class="coluna-acao hidden-print">&nbsp;</th>
                        <?	}?>
                        <?	if( temPermissao("ADMIN_CARGOS_EXCLUIR") ){ ?>
                                <th class="coluna-acao hidden-print">&nbsp;</th>
                        <?	}?>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    
    <script>
		jQuery(document).ready(function() {
			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/segmentos/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$("#codigo").val(),
                                        $("#nome").val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
                                        "=",
										"LIKEALL"
								  ];
					}
				},
				"columns": [
					{ "data": "codigo", 	  	   	"orderable": true },
					{ "data": "nome",	   	"orderable": true }
				<?	if( temPermissao("ADMIN_CARGOS_ALTERAR") ){?>
						,{ "data": "btn_alterar", "orderable": false }
				<?	} ?>
				<?	if( temPermissao("ADMIN_CARGOS_EXCLUIR") ){?>
						,{ "data": "btn_excluir", "orderable": false }
				<?	} ?>
				],
				"order": [[ 1, "asc" ]],
				"fnDrawCallback": function( oSettings ) {
					arrumaOBotaoDeAcoes();
				}
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function(){
				table.draw();
			});			

		});
	</script>