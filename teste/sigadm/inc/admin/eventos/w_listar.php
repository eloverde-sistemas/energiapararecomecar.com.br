	<h3 class="page-title">
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    
    <div class="page-bar">
    	<ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('eventos')?>
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
            	<div class="col-md-6">
                    <div class="form-group">
                        <label for="titulo"><?=$translate->translate("evento")?></label>
                        <input type="text" name="titulo" id="titulo" class="form-control campoFiltro" value="">
                    </div>
                </div>

				<div class="col-md-6">
					<div class="form-group">
						<label for=""><?=$translate->translate("situacao")?></label>
						<div class="radio-list">                            
							<label for="situacao1" class="radio-inline">
								<input type="checkbox" name="situacao1" class="campo situacao" id="situacao1" value="1" checked /> <?=$translate->translate("ativa")?>
							</label>
							<label for="situacao2" class="radio-inline">
								<input type="checkbox" name="situacao2" class="campo situacao" id="situacao2" value="99" checked /> <?=$translate->translate("teste")?>
							</label>
							<label for="situacao3" class="radio-inline">
								<input type="checkbox" name="situacao3" class="campo situacao" id="situacao3" value="3" /> <?=$translate->translate("cancelada")?>
							</label>

						</div>
						<input type="hidden" id="situacao" class="campoFiltro" value="">
					</div>
				</div>				
				
            </div>
                   
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dt_inicio"><?=$translate->translate("data_inicio")?></label><div class="clear"></div>
                        <input type="text" name="dt_inicio" id="dt_inicio" style="width:43.5%" class="form-control date-picker data left campoFiltro" value="">
                        <span class="left" style="margin:7px;">&nbsp;<?=$translate->translate("ate")?>&nbsp;</span>
                        <input type="text" name="dt_fim" id="dt_fim" style="width:43.5%" class="form-control date-picker data left campoFiltro" value="">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dt_inicio"><?=$translate->translate("data_termino")?></label><div class="clear"></div>
                        <input type="text" name="dt_inicioVal" id="dt_inicioVal" style="width:43.5%" class="form-control date-picker data left campoFiltro" value="">
                        <span class="left" style="margin:7px;">&nbsp;<?=$translate->translate("ate")?>&nbsp;</span>
                        <input type="text" name="dt_fimVal" id="dt_fimVal" style="width:43.5%" class="form-control date-picker data left campoFiltro" value="">
                    </div>
                </div>                
            </div>
            
            <div class="clear">&nbsp;</div>
            
            <div class="form-actions left">
                <button type="button" class="btn default" onclick="window.location='/adm/admin/eventos/listar'"><?=$translate->translate('bt_limpar')?></button>
            </div>
        
            <div class="clear"></div>
            
        </div>
    </div>
    
 
    <div class="row text-center jumper-20">
        <a href="/adm/admin/eventos/editar" class="icon-btn">
            <i class="fa fa-plus" alt="<?=$translate->translate('bt_nova')?>" title="<?=$translate->translate('bt_nova')?>"></i>
            <div> <?=$translate->translate('bt_nova')?> </div>
        </a>
    </div>

    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
        
            <table id="list" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                    	<th><?=$translate->translate("evento")?></th>
                        <th><?=$translate->translate("data_inicio")?></th>
                        <th><?=$translate->translate("data_termino")?></th>
						<th><?=$translate->translate("elementos_sorteaveis")?></th>
						<th><?=$translate->translate("situacao")?></th>
						<th class="coluna-situacao hidden-print">&nbsp;</th>
                      	<th class="coluna-acao hidden-print">&nbsp;</th>
                    </tr>
				</thead>
            </table>
        
        </div>
    </div>    

    <script>
		$(document).ready(function() {
			
			atualizaSituacao();
			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/eventos/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$('#titulo').val(),
                                        formataDataParaBanco($('#dt_inicio').val(), '<?=$_SESSION['idioma']?>')+"||"+formataDataParaBanco($('#dt_fim').val(), '<?=$_SESSION['idioma']?>'),
										formataDataParaBanco($('#dt_inicioVal').val(), '<?=$_SESSION['idioma']?>')+"||"+formataDataParaBanco($('#dt_fimVal').val(), '<?=$_SESSION['idioma']?>'),
										"",
										$('#situacao').val()
										
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"LIKEALL",
                                        "BETWEEN",
										"BETWEEN",
										"",
								    	"IN"
								  ];
					}
				},
				"columns": [
					{ "data": "titulo",		 	    "orderable": true },	
					{ "data": "dtInicio", 		 	"orderable": true },
					{ "data": "dtFim", 	 		    "orderable": true },
					{ "data": "eleSort", 		    "orderable": false },
					{ "data": "situacao", 		    "orderable": false },
					{ "data": "idSituacao",    		"orderable": false, "class":"hide idSituacao" },
					{ "data": "btn_acoes",     		"orderable": false }
				],
				"order": [[ 0, "asc" ]],
				"fnDrawCallback": function( oSettings ) {
					colocaClasseTR(".idSituacao", "danger", 3);
					colocaClasseTR(".idSituacao", "warn", 99);
					colocaClasseTR(".idSituacao", "success", 1);
					arrumaOBotaoDeAcoes();
				}
				
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});
			

			$(".situacao").click(function(){
				atualizaSituacao();
				table.draw();
			});			
		});
		
		
		atualizaSituacao = function(){
			
			$("#situacao").val("0");
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