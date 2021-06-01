	<h3 class="page-title">
		<?=$translate->translate('participantes')?> <small><?=$translate->translate('listagem')?></small>
	</h3>
	<div class="page-bar">
		<ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('participantes')?>
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
                            <label class="control-label" for="nome"><?=$translate->translate('nome')?></label>
                            <input type="text" name="nome" id="nome" class="form-control campoFiltro">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="cpf"><?=$translate->translate('cpf')?></label>
                            <input type="text" name="cpf" id="cpf" class="form-control campoFiltro">
                        </div>
                    </div>
          		</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="estado"><?=$translate->translate("estado")?></label>
							<select name="estado" id="estado" class="form-control required campoFiltro">
								<option value="" ><?=$translate->translate("sel_estado")?></option>
							<?	$exeEstados = executaSQL("SELECT * FROM estado ORDER BY nome");
								while($uf = objetoPHP($exeEstados)){ ?>
									<option value="<?=$uf->id?>"><?=$uf->nome?></option>
							<?	} ?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="cidade"><?=$translate->translate("cidade")?></label>
							<select name="cidade" id="cidade" class="form-control required campoFiltro"></select>
						</div>
					</div>                            

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="bairro"><?=$translate->translate('bairro')?></label>
                            <input type="text" name="bairro" id="bairro" class="form-control campoFiltro">
                        </div>
                    </div>

				</div>
				
				<div class="form-actions clear left">
					<button type="button" class="btn default" onclick="window.location='/adm/admin/eventos/listar'"><?=$translate->translate('bt_limpar')?></button>
				</div>
			
				<div class="clear"></div>
                
			</div>
		</div>          
	</div>

	<div class="row text-center jumper-20">

        <a href="/adm/excel/admin/participantes/cadastros" class="icon-btn excel" target="_blank">
            <i class="fa fa-file-excel-o" alt="<?=$translate->translate('exportar')?>" title="<?=$translate->translate('exportar')?>"></i>
            <div> <?=$translate->translate('exportar')?> </div>
        </a>
	
	</div>
		    
	
    <div class="clear">&nbsp;</div>
	
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
			<div class="caption"><?=$translate->translate('participantes')?></div>
			<div class="tools"></div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>
                    <tr>
                        <th><?=$translate->translate("nome")?></th>
						<th><?=$translate->translate("cpf")?></th>
                        <th><?=$translate->translate("email")?></th>
                        <th><?=$translate->translate("celular")?></th>
					<?	if( temPermissao("ADMIN_PARTICIPANTES_ALTERAR") ){ ?>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                    <?	}?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <script>
	
		jQuery(document).ready(function() {

			//CARREGAS AS CIDADES
			$("#estado").change(function(){
				if( $(this).val()>0 ){
					carregaCidadesPeloEstado( "#cidade", $(this).val(), "");
				}
			});

			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/participantes/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$("#nome").val(),
										$("#cpf").val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"LIKEALL",
										"LIKEALL"
								  ];
								  
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterExtra = [
										$("#estado").val(),
										$('#cidade').val(),
										$('#bairro').val()
								  ];

					}
				},
				"columns": [
					{ "data": "nome", 	  	    "orderable": true },
					{ "data": "cpf",    		"orderable": true },
					{ "data": "email",			"orderable": false },
					{ "data": "celular",		"orderable": false }
				<?	if( temPermissao("ADMIN_PARTICIPANTES_ALTERAR") ){?>
						,{ "data": "btn_alterar", "orderable": false }
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
				table.draw();
			});
		});
		
	</script>