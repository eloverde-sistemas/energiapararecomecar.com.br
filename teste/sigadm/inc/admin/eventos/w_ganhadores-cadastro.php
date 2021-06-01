<?
	$id = intval($_GET['id']);
	
	if($id>0){
		$exe = executaSQL("SELECT * FROM evento WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			$reg=objetoPHP($exe);
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	}
?>	

	<h3 class="page-title">
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate('ganhadores_cadastrados')?></small>
    </h3>
    
    <div class="page-bar">
    	<ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/eventos/listar"><?=$translate->translate('eventos')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('ganhadores_cadastrados')?>
            </li>
        </ul>
    </div>

	<div class="portlet light bg-inverse">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-equalizer font-red-sunglo"></i>
				<span class="caption-subject font-red-sunglo bold uppercase">
				<?=$reg->titulo?>
				</span>
			</div>
		</div>
	</div>		    
	
	
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body"> 			
            
            <div class="row">
            	<div class="col-md-4">
                    <div class="form-group">
                        <label for="ganhador"><?=$translate->translate("ganhador")?></label>
                        <input type="text" name="ganhador" id="ganhador" class="form-control campoFiltro" value="">
                    </div>
                </div>
				
				<div class="col-md-4">
                    <div class="form-group">
                        <label for="documento"><?=$translate->translate("documento")?></label>
                        <input type="text" name="documento" id="documento" class="form-control campoFiltro cpf" value="">
                    </div>
                </div>

				<div class="col-md-4">
					<div class="form-group">
						<label for=""><?=$translate->translate("situacao")?></label>
						<div class="radio-list">
							<label for="situacao1" class="radio-inline">
								<input type="checkbox" name="situacao1" class="campo situacao" id="situacao1" value="1" checked="checked" /> <?=$translate->translate("aguardando_retirada")?>
							</label>
							<label for="situacao2" class="radio-inline">
								<input type="checkbox" name="situacao2" class="campo situacao" id="situacao2" value="2" checked="checked" /> <?=$translate->translate("retirada")?>
							</label>
						</div>
						<input type="hidden" id="situacao" class="campoFiltro" value="">
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
    
 
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate("ganhadores")?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
        
            <table id="list" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                    	<th class="hidden-print">&nbsp;</th>
						<th><?=$translate->translate("data")?></th>
                        <th><?=$translate->translate("ganhador")?></th>
						<th><?=$translate->translate("documento")?></th>
						<th><?=$translate->translate("cupom")?></th>
						<th><?=$translate->translate("situacao")?></th>
                      	<th class="coluna-acao">&nbsp;</th>
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
					"url": "paginacao.php?page=admin/eventos/paginacao-ganhadores.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										"<?=$reg->id?>",
										null,
										$('#ganhador').val(),
										$('#documento').val(),
										null,
										$('#situacao').val()
										
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"=",
										null,
                                        "LIKEALL",
										"=",
										null,
										"IN"
								    	
								  ];
					}
				},
				"columns": [
					{ "data": "evento",		    "orderable": false, "class":"hide" },	
					{ "data": "data",		    "orderable": true },	
					{ "data": "ganhador", 	 	"orderable": true },
					{ "data": "documento", 	 	"orderable": true },
					{ "data": "cupom", 		 	"orderable": true },
					{ "data": "situacao", 	    "orderable": true },
					{ "data": "btn_acoes", 		"orderable": false }
				],
				"order": [[ 1, "desc" ]],
				"fnDrawCallback": function( oSettings ) {
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