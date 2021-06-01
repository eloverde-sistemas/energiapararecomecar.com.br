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
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate('participantes')?></small>
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
				<?=$translate->translate('participantes')?>
            </li>
        </ul>
    </div>
    
    <form id="form" action="/adm/admin/eventos/participantes" method="post">
        
        <input type="hidden" name="id" value="<?=$id?>">
	
	<div class="portlet light bg-inverse">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-equalizer font-red-sunglo"></i>
				<span class="caption-subject font-red-sunglo bold uppercase">
				<?=$reg->titulo?> > <?=$translate->translate('participantes')?>
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

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dt_inicio"><?=$translate->translate("data")?></label><div class="clear"></div>
                        <input type="text" name="dt_inicio" id="dt_inicio" style="width:43.5%" class="form-control date-picker left campoFiltro" value="<?=converteDataHora(subDayIntoDate(date('Y-m-d'), 7))?>">
                        <span class="left" style="margin:7px;">&nbsp;<?=$translate->translate("ate")?>&nbsp;</span>
                        <input type="text" name="dt_fim" id="dt_fim" style="width:43.5%" class="form-control date-picker left campoFiltro" value="<?=date('d/m/Y')?>">
                        <div class="clear"></div>
                    </div>
                </div>

            	<div class="col-md-3">
                    <div class="form-group">
                        <label for="participante"><?=$translate->translate("nome")?></label>
                        <input type="text" name="participante" id="participante" class="form-control campoFiltro" value="" />
                    </div>
                </div>
				
				<div class="col-md-3">
                    <div class="form-group">
                        <label for="cpf"><?=$translate->translate("cpf")?></label>
                        <input type="text" name="cpf" id="cpf" class="form-control campoFiltro" value="" />
                    </div>
                </div>

			</div>
                   
            <div class="clear">&nbsp;</div>
            
            <div class="form-actions left">
                <button type="button" class="btn default" onclick="window.location='/adm/admin/eventos/participantes/<?=$id?>'"><?=$translate->translate('bt_limpar')?></button>
            </div>
        
            <div class="clear"></div>
            
        </div>
    </div>
    
    <div class="row text-center jumper-20">
        <a href="javascript:void(0)" id="excel" class="icon-btn void excel">
            <i class="fa fa-file-excel-o"></i>
            <div> <?=$translate->translate('gerar_planilha')?></div>
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
                    	<th><?=$translate->translate("data")?></th>
						<th><?=$translate->translate("participante")?></th>
						<th><?=$translate->translate("cpf")?></th>
                        <th><?=$translate->translate("data_nascimento")?></th>
                        <th><?=$translate->translate("email")?></th>
                        <th><?=$translate->translate("celular")?></th>
						<th><?=$translate->translate("matricula")?></th>
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
					"url": "paginacao.php?page=admin/eventos/paginacao-participantes.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										formataDataParaBanco($('#dt_inicio').val(), '<?=$_SESSION['idioma']?>')+"||"+formataDataParaBanco($('#dt_fim').val(), '<?=$_SESSION['idioma']?>'),
										$('#participante').val(),
                                        $('#cpf').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"BETWEEN",
										"LIKEALL",
										"LIKE"
								  ];
						d.paramsExtra = [<?=$id?>];
					}
				},
				"columns": [
					{ "data": "data_participacao",  "orderable": true },
					{ "data": "nome",	    		"orderable": true },
					{ "data": "cpf",	  			"orderable": true },	
					{ "data": "data_nascimento", 	"orderable": true },
					{ "data": "email", 				"orderable": true },
					{ "data": "celular", 	 	    "orderable": true },
					{ "data": "matricula", 		    "orderable": true },
					{ "data": "btn_acoes",     		"orderable": false }
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


			$("#excel, #pdf").click( function(){

		        var params = "evento=<?=$id?>";

		        if($("#nome").val() != ''){
		            params+= "&nome="+$("#participante").val();
		        }

		        if($("#cpf").val() != ''){
		            params+= "&cpf="+$("#cpf").val();
		        }

		        if($("#dt_inicio").val() != ''){
		            params+= "&dt_inicio="+$("#dt_inicio").val();
		        }

				if($("#dt_fim").val() != ''){
		            params+= "&dt_fim="+$("#dt_fim").val();
		        }

		        if($(this).hasClass("excel")){
		            window.open("/adm/excel/admin/eventos/participantes?" + params, '_blank');
		        }

		        if($(this).hasClass("pdf")){
		            window.open("/adm/excel/admin/eventos/participantes?" + params, '_blank');
		        }
		    });
			

		});
		
	</script>