<div id="dialogMidias" class="modal fade" data-width="90px" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width:75%;">
        <div class="modal-content">
        	
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?=$translate->translate('midias')?></h4>
            </div>
        	
            <div style="margin:20px;">
            
                <table class="table table-striped table-bordered table-hover" id="listMidias">
                    <thead>        
                        <tr>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                            <th><?=$translate->translate("titulo")?></th>
                            <th><?=$translate->translate("visualizar")?></th>
                            <th><?=$translate->translate("formato")?></th>
                            <th><?=$translate->translate("copiar_link")?></th>
                        </tr>
                    </thead>                
                </table>
            
            </div>
            
            <div class="modal-footer">
            	<button type="button" data-dismiss="modal" class="btn btn-primary red"><?=$translate->translate('fechar')?></button>
            </div>
            
        </div>
    </div>
</div>

<div id="dialogMidiasArquivos" class="modal fade" data-width="90px" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width:75%;">
        <div class="modal-content">
        	
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?=$translate->translate('midias_arquivos')?></h4>
            </div>
        	
            <div style="margin:20px;">
            
                <table class="table table-striped table-bordered table-hover" id="listMidiasArquivos">
                    <thead>        
                        <tr>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                            <th><?=$translate->translate("titulo")?></th>
                            <th><?=$translate->translate("visualizar")?></th>
                            <th><?=$translate->translate("formato")?></th>
                            <th><?=$translate->translate("copiar_link")?></th>
                        </tr>
                    </thead>                
                </table>
            
            </div>
            
            <div class="modal-footer">
            	<button type="button" data-dismiss="modal" class="btn btn-primary red"><?=$translate->translate('fechar')?></button>
            </div>
            
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {
		
		$('#listMidias').dataTable( {
			"filter": false,
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "paginacao.php?page=generic/midias-paginacao.php",
				"type": "POST",
				"data": function ( d ) {
					//VALORES DOS FILTROS
					d.filterValue = [
									""
							  ];
					//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
					d.filterRegex = [
									""
							  ];
				}
			},
			"columns": [
				{ "data": "id",			"orderable": false, "class":"hide" },
				{ "data": "titulo",		"orderable": true },
				{ "data": "visualizar",	"orderable": false },
				{ "data": "formato",	"orderable": true },
				{ "data": "copiar",		"orderable": false }
			],
			"order": [[ 0, "desc" ]]
		});
		
		$("a.foto").fancybox();
		
		var table = $('#listMidias').DataTable();
		$( '.campoFiltro' ).on( 'keyup change', function () {
			table.draw();
		});
		
		
		
		$('#listMidiasArquivos').dataTable( {
			"filter": false,
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "paginacao.php?page=generic/midias-arquivos-paginacao.php",
				"type": "POST",
				"data": function ( d ) {
					//VALORES DOS FILTROS
					d.filterValue = [
									""
							  ];
					//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
					d.filterRegex = [
									""
							  ];
				}
			},
			"columns": [
				{ "data": "id",			"orderable": false, "class":"hide" },
				{ "data": "titulo",		"orderable": true },
				{ "data": "visualizar",	"orderable": false },
				{ "data": "formato",	"orderable": true },
				{ "data": "copiar",		"orderable": false }
			],
			"order": [[ 0, "desc" ]]
		});
		
		$("a.foto").fancybox();
		
		var table = $('#listMidiasArquivos').DataTable();
		$( '.campoFiltro' ).on( 'keyup change', function () {
			table.draw();
		});
		
	});
</script>