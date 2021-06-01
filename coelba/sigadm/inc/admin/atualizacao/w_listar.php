
	<h3 class="page-title">
        <?=$translate->translate('atualizacao')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('atualizacao')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form-filtro" action="/adm/admin/atualizacao/listar" method="post">
                   
                <div class="row">
                    <div class="col-md-4">
						<div class="form-group">
							<label for="evento"><?=$translate->translate("evento")?></label>
							<select id="evento" class="form-control required campoFiltro" name="evento">
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
                    
                    <div class="col-md-6">
						<div class="form-group">
							<label><?=$translate->translate("tipo")?></label>
							<div class="radio-list">
								<label class="radio-inline" for="tipo1">
									<input type="checkbox"  name="tipo" id="tipo1" value="1" class="tipo" checked> <?=$translate->translate("insercao")?>
								</label>
								<label class="radio-inline" for="tipo2">
									<input type="checkbox"  name="tipo" id="tipo2" value="2" class="tipo" checked> <?=$translate->translate("atualizacao")?>
								</label>
								<label class="radio-inline" for="tipo2">
									<input type="checkbox"  name="tipo" id="tipo3" value="3" class="tipo" checked> <?=$translate->translate("exclusao")?>
								</label>
								<input type="hidden" id="tipo" class="campoFiltro" value="">
							</div>
						</div>
					</div>
                </div>
                
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/atualizacao/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
    </div>

<?	if( temPermissao("ADMIN_ATUALIZACAO_INSERIR") ){ ?>	
        <div class="row text-center jumper-20">
            <a href="/adm/admin/atualizacao/editar" class="icon-btn">
                <i class="fa fa-plus"></i>
                <div> <?=$translate->translate('bt_nova')?> </div>
            </a>
        </div>
<?	} ?>

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
						<th><?=$translate->translate("data")?></th>
						<th><?=$translate->translate("tipo")?></th>
						<th class="coluna-acao hidden-print">&nbsp;</th>
					</tr>
				</thead>
			   
			</table>
		</div>
	</div>
    
  	<script>
		$(document).ready(function() {
			
			atualizaTipo();
			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/atualizacao/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$('#evento').val(),
										"",
										$('#tipo').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"=",
										"",
										"IN"
								  ];
					}
				},
				"columns": [
					{ "data": "evento",			"orderable": true},
					{ "data": "data",			"orderable": true},
					{ "data": "tipo",			"orderable": true},
					{ "data": "btn_visualizar", "orderable": false}
				],
				"order": [[ 1, "desc" ]]
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});		
			
			$(".tipo").click(function(){
				atualizaTipo();
				table.draw();
			});
			
		});
		
		atualizaTipo = function(){
			
			$("#tipo").val("1,2,3");
			var qtdeCheckeds = $(".tipo:checked").length;
			
			if(qtdeCheckeds>0){
				var arr = [];
				$(".tipo:checked").each(function(){
					arr.push($(this).val());
				});
				$("#tipo").val(arr.join());
			}
		}
    </script>