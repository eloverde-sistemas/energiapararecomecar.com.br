<?
	$idEvento = getEventoAtual()->id;
?>
	<h3 class="page-title">
        <?=$translate->translate('faq')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('faq')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form-filtro" action="/adm/admin/banners/listar" method="post">
                   
                <div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="pergunta"><?=$translate->translate("pergunta")?></label>
							<input type="text" name="pergunta" class="form-control campoFiltro" id="pergunta" value="">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="resposta"><?=$translate->translate("resposta")?></label>
							<input type="text" name="resposta" class="form-control campoFiltro" id="resposta" value="">
						</div>
					</div>
                </div>

                <div class="row">
                    
                    <div class="col-md-4">
						<div class="form-group">
							<label for="evento"><?=$translate->translate("evento")?></label>
							<select id="evento" class="form-control campoFiltro" name="evento">
								<option value=""><?=$translate->translate("sel_evento")?></option>
							<?
								$exeEv = executaSQL("SELECT * FROM evento ORDER BY dt_inicio DESC");
								if(nLinhas($exeEv)>0){
								
									while($evento = objetoPHP($exeEv)){ ?>
										<option value="<?=$evento->id?>" <?=($idEvento == $evento->id ? 'selected' : '')?> > <?=$evento->titulo?></option>
							<?		}
							
								}
							?>
								
							</select>
						</div>
					</div>
				</div>
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/faq/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
    </div>
			
	<div class="row text-center jumper-20">
		<a href="/adm/admin/faq/editar" class="icon-btn">
			<i class="fa fa-plus"></i>
			<div> <?=$translate->translate('bt_novo')?> </div>
		</a>
	</div>
           
	<div class="portlet box grey-cascade">
		<div class="portlet-title">
			<div class="caption"></div>
			<div class="tools"></div>
		</div>
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="list">
				<thead>
				
					<tr>
        				<th><?=$translate->translate("evento")?></th>
        				<th><?=$translate->translate("pergunta")?></th>
						<th><?=$translate->translate("resposta")?></th>
						<th class="coluna-acao">&nbsp;</th>
						<th class="coluna-acao">&nbsp;</th>
						<th class="coluna-acao">&nbsp;</th>
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
					"url": "paginacao.php?page=admin/faq/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$('#evento').val(),
										$('#pergunta').val(),
										$('#resposta').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"=",
										"LIKEALL",
										"LIKEALL"
								  ];
					}
				},
				"columns": [
					{ "data": "evento",		 		"orderable": true },
					{ "data": "pergunta", 			"orderable": true },
					{ "data": "resposta", 			"orderable": true },
					{ "data": "btn_alterar", 		"orderable": false },
					{ "data": "btn_inativar", 		"orderable": false },
					{ "data": "btn_excluir", 		"orderable": false },
					{ "data": "ordem", 				"orderable": false, "class":"hide" }
				],
				"order": [[ 6, "asc" ]]
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});		
			
			$(".campoFiltro").click(function(){
				table.draw();
			});
			
		});
		
    </script>