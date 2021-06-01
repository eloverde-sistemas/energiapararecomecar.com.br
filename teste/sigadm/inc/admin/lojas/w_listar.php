	<h3 class="page-title">
		<?=$translate->translate('lojas')?> <small><?=$translate->translate('listagem')?></small>
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
                <?=$translate->translate('gerenciar')?>
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
                            <label class="control-label" for="fantasia"><?=$translate->translate('nome_fantasia')?></label>
                            <input type="text" name="fantasia" id="fantasia" class="form-control campoFiltro">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="razaoSocial"><?=$translate->translate('razao_social')?></label>
                            <input type="text" name="razaoSocial" id="razaoSocial" class="form-control campoFiltro">
                        </div>
                    </div>
          		</div>

          		<div class="row">
          			
          			<div class="col-md-6">
                        <div class="form-group">
                    		<label for=""><?=$translate->translate("situacao")?></label>
                            <div class="radio-list">
                            <?
                                $exe = executaSQL("SELECT * FROM loja_situacao ORDER BY id");
                                if(nLinhas($exe)>0){
                                    while($reg=objetoPHP($exe)){ ?>
                                        <label for="situacao<?=$reg->id?>" class="radio-inline">
                                            <input type="checkbox" name="situacao<?=$reg->id?>" class="campo situacao" id="situacao<?=$reg->id?>" value="<?=$reg->id?>" checked="checked" /> <?=$reg->valor?>
                                        </label>
                            <?      }
                                }
                            ?>
                            </div>
                            <input type="hidden" id="situacao" class="campoFiltro" value="">
						</div>
					</div>
          			
          		</div>
                
			</div>
		</div>          
	</div>
    
<? if(temPermissao("ADMIN_LOJAS_INSERIR")){ ?>
        <div class="row text-center spacer-20">
            <a href="/adm/admin/lojas/editar" class="icon-btn">
                <i class="fa fa-plus"></i>
                <div> <?=$translate->translate('bt_nova')?> </div>
            </a>
        </div>
<?	} ?>

    <div class="clear">&nbsp;</div>
	
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
			<div class="caption"><?=$translate->translate('lojas')?></div>
			<div class="tools"></div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>
                    <tr>
                        <th><?=$translate->translate("nome_fantasia")?></th>
                        <th><?=$translate->translate("razao_social")?></th>
                        <th><?=$translate->translate("situacao")?></th>
					<?	if( temPermissao("ADMIN_CARGOS_ALTERAR") ){ ?>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                    <?	}?>
                    <?	if( temPermissao("ADMIN_CARGOS_EXCLUIR") ){ ?>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                    <?	}?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <script>
	
		jQuery(document).ready(function() {
			
			atualizaSituacao();
			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/lojas/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$("#fantasia").val(),
										$("#razaoSocial").val(),
										$('#situacao').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"LIKEALL",
										"LIKEALL",
										"IN"
								  ];
					}
				},
				"columns": [
					{ "data": "fantasia", 	  	    "orderable": true },
					{ "data": "razaoSocial",    	"orderable": true },
					{ "data": "situacao",			"orderable": false }
				<?	if( temPermissao("ADMIN_CARGOS_ALTERAR") ){?>
						,{ "data": "btn_alterar", "orderable": false }
				<?	} ?>
				<?	if( temPermissao("ADMIN_CARGOS_EXCLUIR") ){?>
						,{ "data": "btn_excluir", "orderable": false }
				<?	} ?>
				],
				"order": [[ 0, "asc" ]],
				"fnDrawCallback": function( oSettings ) {
					arrumaOBotaoDeAcoes();
				}
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function(){
				table.draw();
			});
			
			$(".situacao").click(function(){
				atualizaSituacao();
				table.draw();
			});
		});
		
		atualizaSituacao = function(){
			
			$("#situacao").val("1,2");
			var qtdeCheckeds = $(".situacao:checked").length;
			
			if(qtdeCheckeds>0){
				var arr = [];
				$(".situacao:checked").each(function(){
					arr.push($(this).val());
				});
				$("#situacao").val(arr.join());
			}
		}
	</script>