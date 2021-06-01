
	<h3 class="page-title">
        <?=$translate->translate('tt_perfis')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('seguranca')?>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('tt_perfis')?>
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nome"><?=$translate->translate("nome")?></label>
                            <input type="text" name="nome" id="nome" class="form-control campoFiltro" value="" />
                        </div>
                    </div>
                </div>
                
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/perfis/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
    </div>

<?	if( temPermissao("ADMIN_PERFIS_INSERIR") ){ ?>
        <div class="row text-center jumper-20">
            <a href="/adm/admin/perfis/editar" class="icon-btn">
                <i class="fa fa-plus"></i>
                <div> <?=$translate->translate('bt_novo')?> </div>
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
						<th><?=$translate->translate("nome")?></th>
						<th><?=$translate->translate("descricao")?></th>
					<?	if( temPermissao("ADMIN_PERFIS_ALTERAR") ){ ?>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                    <?	}?>
                    <?	if( temPermissao("ADMIN_PERFIS_EXCLUIR") ){ ?>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                    <?	}?>
						<th class="hide">&nbsp;</th>
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
					"url": "paginacao.php?page=admin/perfis/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										"",
										$('#nome').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"",
										"LIKEALL"
								  ];
					}
				},
				"columns": [
					{ "data": "id",		 "orderable": false, "class":"hide" },
					{ "data": "nome", 		 "orderable": true },
					{ "data": "descricao", 	  	 "orderable": true },
				<?	if( temPermissao("ADMIN_PERFIS_ALTERAR") ){ ?>
						{ "data": "btn_alterar", "orderable": false },
				<?	} ?>
				<?	if( temPermissao("ADMIN_PERFIS_EXCLUIR") ){ ?>
						{ "data": "btn_excluir", "orderable": false },
				<?	} ?>
					{ "data": "excluir",		 "orderable": false, "class":"hide" }
				],
				"order": [[ 1, "asc" ]]
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});		
			
		});
    </script>