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
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate('lotes')?></small>
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
				<?=$translate->translate('lotes')?>
            </li>
        </ul>
    </div>
    
    <form id="form" action="/adm/admin/eventos/lotes" method="post">
        
        <input type="hidden" name="id" value="<?=$id?>">
	
	<div class="portlet light bg-inverse">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-equalizer font-red-sunglo"></i>
				<span class="caption-subject font-red-sunglo bold uppercase">
				<?=$reg->titulo?> > <?=$translate->translate('lotes')?>
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
                        <label for="codigo"><?=$translate->translate("codigo")?></label>
                        <input type="text" name="codigo" id="codigo" class="form-control campoFiltro" value="" />
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
            <div class="caption"></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
        
            <table id="list" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
						<th><?=$translate->translate("numero")?></th>
                    	<th><?=$translate->translate("data")?></th>
						<th><?=$translate->translate("situacao")?></th>
                      	<th class="coluna-acao hidden-print">&nbsp;</th>
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
					"url": "paginacao.php?page=admin/eventos/paginacao-lotes.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$('#codigo').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"="
								  ];
						d.paramsExtra = [<?=$id?>];
					}
				},
				"columns": [
					{ "data": "id",  		"orderable": true },
					{ "data": "dt_hr",	    "orderable": true },
					{ "data": "situacao",	"orderable": true },
					{ "data": "btn_acoes",  "orderable": false }
				],
				"order": [[ 0, "desc" ]],
				"fnDrawCallback": function( oSettings ) {
					arrumaOBotaoDeAcoes();
				}
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});			
			
		});

	</script>