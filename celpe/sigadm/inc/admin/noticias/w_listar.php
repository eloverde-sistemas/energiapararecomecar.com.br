
	<h3 class="page-title">
        <?=$translate->translate('tm_noticias')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('tm_noticias')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form-filtro" action="/adm/admin/noticias/listar" method="post">
                   
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="titulo"><?=$translate->translate("titulo")?></label>
                            <input type="text" name="titulo" id="titulo" class="form-control campoFiltro" value="" />
                        </div>
                    </div>
                    
                    <div class="col-md-6">
						<div class="form-group">
							<label><?=$translate->translate("tipo")?></label>
							<div class="radio-list">
								<label class="radio-inline" for="tipo1">
									<input type="checkbox"  name="tipo" id="tipo1" value="1" class="tipo" checked> <?=$translate->translate("normal")?>
								</label>
								<label class="radio-inline" for="tipo2">
									<input type="checkbox"  name="tipo" id="tipo2" value="2" class="tipo" checked> <?=$translate->translate("destaque")?>
								</label>
								<input type="hidden" id="tipo" class="campoFiltro" value="">
							</div>
						</div>
					</div>
                </div>
                
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/noticias/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
    </div>

<?	if( temPermissao("ADMIN_NOTICIAS_INSERIR") ){ ?>	
        <div class="row text-center jumper-20">
            <a href="/adm/admin/noticias/editar" class="icon-btn">
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
						<th class="hide">&nbsp;</th>
						<th><?=$translate->translate("titulo")?></th>
						<th><?=$translate->translate("validade")?></th>
						<th><?=$translate->translate("tipo")?></th>
						<?	if( temPermissao("ADMIN_NOTICIAS_ALTERAR") ){ ?>
								<th class="coluna-acao hidden-print">&nbsp;</th>
						<?	}?>
						<?	if( temPermissao("ADMIN_NOTICIAS_EXCLUIR") ){ ?>
								<th class="coluna-acao hidden-print">&nbsp;</th>
						<?	}?>
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
					"url": "paginacao.php?page=admin/noticias/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										"",
										$('#titulo').val(),
										"",	
										$('#tipo').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"",
										"LIKEALL",
										"",
										"IN"
								  ];
					}
				},
				"columns": [
					{ "data": "id",			"orderable": false, "class":"hide" },
					{ "data": "titulo",		"orderable": true },
					{ "data": "validade",	"orderable": true },
					{ "data": "tipo",		"orderable": true }
				<?	if( temPermissao("ADMIN_NOTICIAS_ALTERAR") ){ ?>
						,{ "data": "btn_alterar", "orderable": false }
				<?	} ?>
				<?	if( temPermissao("ADMIN_NOTICIAS_EXCLUIR") ){ ?>
						,{ "data": "btn_excluir", "orderable": false }
				<?	} ?>
				],
				"order": [[ 0, "desc" ]]
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
			
			$("#tipo").val("1,2,0");
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