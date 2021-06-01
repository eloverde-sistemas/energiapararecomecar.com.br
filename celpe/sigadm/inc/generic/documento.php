<div id="visualizarDadosDocumento" class="modal fade" data-width="90px" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="width:75%;">
        <div class="modal-content">                
        </div>
    </div>
</div>

<script>
	visualizarDadosDocumento = function(id){
		$.ajax({
			url: 'inc/genericoJSON.php',
			type: 'post',
			data: {
				acao: 'buscaDadosDocumento',
				id: id
			},
			cache: false,
			async: false,
			success: function(data) {
				
				var variavel = "";
				
				if(data.status==true){
					
					variavel += '<div class="modal-header">';
					variavel += 	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>';
					variavel += 	'<h4 class="modal-title"><?=$translate->translate('documento')?></h4>';
					variavel += '</div>';						
						
					variavel += '<fieldset style="margin:10px;">';
					variavel += 	'<legend><?=$translate->translate("dados_documento")?></legend>';
					
					variavel += 	'<div class="row">';
					variavel += 		'<div class="col-md-4">';
					variavel += 			'<div class="form-group">';
					variavel += 				'<strong><?=$translate->translate("numero")?></strong>: '+data.numero;
					variavel += 			'</div>';
					variavel += 		'</div>';
					
					variavel += 		'<div class="col-md-4">';
					variavel += 			'<div class="form-group">';
					variavel += 				'<strong><?=$translate->translate("titulo")?></strong>: '+data.titulo;
					variavel += 			'</div>';
					variavel += 		'</div>';
					
					variavel += 		'<div class="col-md-4">';
					variavel += 			'<div class="form-group">';
					variavel += 				'<strong><?=$translate->translate("categoria")?></strong>: '+data.categoria;
					variavel += 			'</div>';
					variavel += 		'</div>';						
					
					variavel += 	'</div>';
					
					variavel += 	'<div class="row">';
					
					if(data.dt_publicacao!=null && data.dt_publicacao!=''){	
						variavel += 		'<div class="col-md-4">';
						variavel += 			'<div class="form-group">';
						variavel += 				'<strong><?=$translate->translate("data_publicacao")?></strong>: '+data.dt_publicacao;
						variavel += 			'</div>';
						variavel += 		'</div>';
					}
					
					if(data.temArquivo==true){	
						variavel += 		'<div class="col-md-4">';
						variavel += 			'<div class="form-group">';
						variavel += 				'<strong><a href="/baixarDocumento.php?id='+data.id+'&tipo=1" target="_blank"><?=$translate->translate("download_arquivo")?></a></strong>';
						variavel += 			'</div>';
						variavel += 		'</div>';
					}
						
					variavel += 	'</div>';
						
					variavel += 	'<div class="modal-footer">';
					variavel += 		'<button type="button" data-dismiss="modal" class="btn btn-primary red"><?=$translate->translate('fechar')?></button>';
					variavel +=		'</div>';
					
					variavel +=	'</fieldset>';
						
					$("#visualizarDadosDocumento .modal-content").html(variavel);
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest.responseText);
			},
			dataType: 'json'
			
		});			
		return false;		
	}

</script>