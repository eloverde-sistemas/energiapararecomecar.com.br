
	<div id="carregaEnderecoModal" class="modal fade" data-width="800px" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title"><?=$translate->translate('carregar_endereco')?></h4>
				</div>
                <div class="modal-body">
                	
                    <div class="row">
                    	<div class="col-xs-6">
                        	<div class="form-group">
                                <label><?=$translate->translate('endereco_de')?></label>
                                <div class="radio-list">
                                	<label class="radio-inline" for="tipo_carrega_endereco1">
                                        <input type="radio" value="1" name="tipo_carrega_endereco" id="tipo_carrega_endereco1" /> <?=$translate->translate('loja')?>
                                	</label>
                                	<label class="radio-inline" for="tipo_carrega_endereco2">
                                        <input type="radio" value="2" name="tipo_carrega_endereco" id="tipo_carrega_endereco2" /> <?=$translate->translate('irmao')?>
                                	</label>
								</div>
							</div>
                        </div>
                        <div class="col-xs-6">
                        	<div class="form-group" style="display: none">
                                <label for="irmaoCarregaEndereco"><?=$translate->translate('irmao')?></label>
								<select id="irmaoCarregaEndereco" name="irmaoCarregaEndereco" class="form-control"></select>
                            </div>  
                        </div>
                    </div>
                    
                    <div class="radio-list ml-25 jumper-20" id="enderecosIrmaoCarregar"></div>
                    
                    <div class="row">
                    	<div class="col-xs-12">
                        	<div class="alert alert-danger"></div>
                        </div>
                    </div>
                    
                    <div class="previewEndereco">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logradouro"><?=$translate->translate('logradouro')?></label>
                                    <div class="logradouro"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-2  col-xs-4">
                                <div class="form-group">
                                    <label for="numero"><?=$translate->translate('numero')?></label>
                                    <div class="numero"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4  col-xs-8">
                                <div class="form-group">
                                    <label for="complemento"><?=$translate->translate('complemento')?></label>
                                    <div class="complemento"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">                                
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bairro"><?=$translate->translate('bairro')?></label>
                                    <div class="bairro"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-2 col-xs-4">
                                <div class="form-group">
                                    <label for="cep"><?=$translate->translate('cep')?></label>
                                    <div class="cep"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-xs-8">
                                <div class="form-group">
                                    <label for="destinatario"><?=$translate->translate('destinatario')?></label>
                                    <div class="destinatario"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">                                
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pais"><?=$translate->translate('pais')?></label>
                                    <div class="pais"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-2 col-xs-4">
                                <div class="form-group">
                                    <label for="estado"><?=$translate->translate('estado')?></label>
                                    <div class="estado"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-xs-8">
                                <div class="form-group">
                                    <label for="cidade"><?=$translate->translate('cidade')?></label>
                                    <div class="cidade"></div>
                                </div>
                            </div>
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelaCarregaEndereco" data-dismiss="modal" class="btn btn-default"><?=$translate->translate('bt_cancelar')?></button>
                    <button type="button" id="confirmaCarregaEndereco" class="btn btn-default"><?=$translate->translate('bt_confirmar')?></button>
                    <input type="hidden" name="id">
                </div>
            </div>
        </div>
    </div>
    
    <script>
		$(function(){
			var loja = null, prefix = null;
			var endCarregar = [];
			
			$( document ).on( "click", ".carregaEndereco", function() {
				$('.previewEndereco, #carregaEnderecoModal .alert, #enderecosIrmaoCarregar').hide();
				$('#enderecosIrmaoCarregar').html('');
				$('#irmaoCarregaEndereco').closest('.form-group').hide();
				
				$('[name="tipo_carrega_endereco"]:checked').attr('checked', false).closest('span').removeClass('checked');
				prefix	= $(this).attr('data-prefix');
				loja	= $(this).attr('data-loja');
				
			//	Se aponta pra outro campo, pega o valor do mesmo
				if( loja.indexOf("#")==0 ){
					loja = $(loja).val();
				}
			});
			
			$( document ).on( "change", "[name='tipo_carrega_endereco']", function() {
				$('.previewEndereco, #carregaEnderecoModal .alert, #enderecosIrmaoCarregar').hide();
				
				valor = $("[name='tipo_carrega_endereco']:checked").val();
				if(valor==1){
					$('#irmaoCarregaEndereco').closest('.form-group').hide();
					
					endCarregar = carregaEnderecoLoja(loja);
					$('.previewEndereco').show();
					
				}else if(valor==2){
					carregaGerericOption('#irmaoCarregaEndereco', 'carregaIrmaosAtivos', new Array(loja), null);
					$('#irmaoCarregaEndereco').closest('.form-group').show();
				}
			});
			
			$( document ).on( "change", "#irmaoCarregaEndereco", function() {
				$('.previewEndereco, #carregaEnderecoModal .alert, #enderecosIrmaoCarregar').hide();
				$('#enderecosIrmaoCarregar').html('');
				
				if( this.value>0 ){
					if( verificaEnderecosIrmao(this.value)!=false ){
						endCarregar = carregaEnderecoIrmao(this.value);
						$('.previewEndereco').show();
						
					}else{
						$('#carregaEnderecoModal .alert').html('<?=$translate->translate('irmao_nao_possui_endereco')?>').show();
					}
				}
			});
			
			$( document ).on( "change", "[name='enderecoIrmao']", function() {
				endCarregar = carregaEnderecoIrmao( $('#irmaoCarregaEndereco').val(), $("[name='enderecoIrmao']:checked").val() );
			});
			
			$( document ).on( "click", "#confirmaCarregaEndereco", function() {
				tipo = $("[name='tipo_carrega_endereco']:checked").val();
				
				if(tipo==1 || (tipo==2 && $('#irmaoCarregaEndereco').val()>0)){
					confirmaCarregaEndereco(prefix, endCarregar);
					$('#cancelaCarregaEndereco').click();
				}else{
					$('#carregaEnderecoModal .alert').html('<?=$translate->translate('selecione_endereco')?>').show();
				}
			});
		});
		
		carregaEnderecoLoja = function(loja){
			retorno = '';
			
			$.ajax({
				url: 'inc/genericoJSON.php',
				type: 'post',
				data: {
					acao: 'mostraDetalhesLoja',
					id_loja: loja
				},
				cache: false,
				async: false,
				success: function(data) {
					if(data.status==true){
						retorno = data;
						populaPreviewEndereco(retorno);
					}else{
						retorno = false;
						alert('Deu treta');
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert(XMLHttpRequest.responseText);
				},
				dataType: 'json'
				
			});
			
			return retorno;
		}
		
		carregaEnderecoIrmao = function(id, endereco){
			retorno = '';
			
			$.ajax({
				url: 'inc/genericoJSON.php',
				type: 'post',
				data: {
					acao: 'buscaEnderecoIrmao',
					id: id,
					endereco: endereco
				},
				cache: false,
				async: false,
				success: function(data) {
					if(data.status==true){
						retorno = data;
						populaPreviewEndereco(retorno);
					}else{
						retorno = false;
						alert('Deu treta');
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert(XMLHttpRequest.responseText);
				},
				dataType: 'json'
				
			});
			
			return retorno;
		}
		
		verificaEnderecosIrmao = function(id){
			retorno = true;
			$.ajax({
				url: 'inc/genericoJSON.php',
				type: 'post',
				data: {
					acao: 'verificaEnderecosIrmao',
					id: id
				},
				cache: false,
				async: false,
				success: function(data) {
					if(data.status==true){
						for(x=1; x<=data.total; x++){
							$('#enderecosIrmaoCarregar').append(
								$('<label/>').addClass('radio-inline').attr('for', 'enderecoIrmao'+x).append(
									$('<input/>').attr('type', 'radio').attr('name', 'enderecoIrmao').attr('id', 'enderecoIrmao'+x).attr('checked', x==1?true:false).val(data.itens[x])
								).append(
									x==1 ? '<?=$translate->translate('endereco_principal')?>' : '<?=$translate->translate('endereco_alternativo')?>'
								)
							);
						}
						$('#enderecosIrmaoCarregar').show();
					}else{
						retorno = false;
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert(XMLHttpRequest.responseText);
				},
				dataType: 'json'
				
			});
			return retorno;
		}
		
		populaPreviewEndereco = function(end){
			modal = '#carregaEnderecoModal';
			$(modal + ' .logradouro').html(end.logradouro);
			$(modal + ' .numero').html(end.numero);
			$(modal + ' .complemento').html(end.complemento);
			$(modal + ' .bairro').html(end.bairro);
			$(modal + ' .cep').html(end.cep);
			$(modal + ' .destinatario').html(end.destinatario);
			$(modal + ' .pais').html(end.pais);
			$(modal + ' .estado').html(end.estado);
			$(modal + ' .cidade').html(end.cidade);
		}
		
		confirmaCarregaEndereco = function(prefix, end){
			$('#' + prefix + 'logradouro').val(end.logradouro);
			$('#' + prefix + 'numero').val(end.numero);
			$('#' + prefix + 'complemento').val(end.complemento);
			$('#' + prefix + 'bairro').val(end.bairro);
			$('#' + prefix + 'cep').val(end.cep);
			$('#' + prefix + 'destinatario').val(end.destinatario);
			
			$('#' + prefix + 'pais option').attr('selected', false);
			$("#" + prefix + "pais option[value='" + end.id_pais + "']").attr('selected', true);
			
			carregaCidadesPelaCidade('#' + prefix + 'cidade', end.id_cidade, '#' + prefix + 'estado');
		}
	</script>