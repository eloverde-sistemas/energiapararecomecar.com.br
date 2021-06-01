
	<h3 class="page-title">
        <?=$translate->translate('sorteios_loteria')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('sorteios_loteria')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form-filtro" action="/adm/admin/sorteio-loteria/listar" method="post">
                   
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
	                        <label for="data"><?=$translate->translate("data")?> </label>
	                        <input type="text" name="data" class="form-control campoFiltro data" id="data"  value="">
                        </div>
                    </div>
                    
                </div>
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/sorteio-loteria/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
    </div>
			
	<div class="row text-center jumper-20">
		<a href="/adm/admin/sorteio-loteria/editar" class="icon-btn">
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
        				<th><?=$translate->translate("data")?></th>
						<th><?=$translate->translate("numero")?> 1</th>
						<th><?=$translate->translate("numero")?> 2</th>
						<th><?=$translate->translate("numero")?> 3</th>
						<th><?=$translate->translate("numero")?> 4</th>
						<th><?=$translate->translate("numero")?> 5</th>
						<? if( temPermissao("ADMIN_SORTEIO-LOTERIA_ALTERAR") ){ ?>
								<th class="coluna-acao">&nbsp;</th>
						<? }?>
						<? if( temPermissao("ADMIN_SORTEIO-LOTERIA_EXCLUIR") ){ ?>
								<th class="coluna-acao">&nbsp;</th>
						<? }?>
					</tr>
        
				</thead>
			   
			</table>
		</div>
	</div>
    
  	<script>
		$(document).ready(function() {
			
			$('#data').datepicker({ endDate:'0d', autoclose: true});
			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/sorteio-loteria/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										formataDataParaBanco($('#data').val(), '<?=$_SESSION['idioma']?>')
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"="
								  ];
					}
				},
				"columns": [
					{ "data": "data",		 	"orderable": true },
					{ "data": "numero1", 		"orderable": false },
					{ "data": "numero2", 		"orderable": false },
					{ "data": "numero3", 		"orderable": false },
					{ "data": "numero4", 		"orderable": false },
					{ "data": "numero5", 		"orderable": false },
					{ "data": "btn_alterar", 	"orderable": false },
					{ "data": "btn_excluir", 	"orderable": false }
				],
				"order": [[ 0, "desc" ]]
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