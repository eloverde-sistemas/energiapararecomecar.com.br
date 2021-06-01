<?
	
	$evento = executaSQL("SELECT * FROM evento WHERE id= '".$_SESSION['campanha']->id."' AND NOW() >= CONCAT( dt_inicio, ' ', hr_inicio ) ");

	if( nLinhas($evento)>0 ){
			$evento = objetoPHP($evento);

			$eventoJa = executaSQL("SELECT * FROM evento WHERE id= '".$_SESSION['campanha']->id."' AND NOW() <= dt_termino");
			if(nLinhas($eventoJa)>0){
				$eventoJaPassou = 0;
			}else{
				$eventoJaPassou = 1;
			}
			
?>	
		

		<form class="form spacer-20" id="verificarCPF" method="post">
			<div class="form-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="cpf_participante"><?=$translate->translate('cpf')?></label>
							<input type="text" id="cpf_participante" name="cpf_participante" class="form-control cpf required">
						</div>
					</div>
					<div class="col-md-3">
						<label>&nbsp;</label>
						<button id="buttonVerificarCPF" type="submit" class="btn btn-primary block"><i class="fa fa-check"></i> <?=$translate->translate('continuar')?></button>
					</div>
					<div class="col-md-12 alert alert-danger" id="cpfError" style="display: none"></div>		
				</div>
			</div>
		</form>

		
		
		<form class="form spacer-20" id="cadastrarParticipante" style="display: none" method="post">
		
			<div class="form-body">
		
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="cpf_participante"><?=$translate->translate('cpf')?></label>
							<input type="text" class="form-control getcpf" disabled="disabled">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="unidade"><?=$translate->translate('unidade')?></label>
							<input type="text" id="unidade" name="unidade" class="form-control unidadeFormata">
						</div>
						
					</div>
		
					<div class="col-md-4">
						<div class="form-group">
							<label for="nome_completo"><?=$translate->translate('nome_completo')?></label>
							<input type="text" id="nome_completo" name="nome_completo" class="form-control">
						</div>
					</div>
					
					<div class="col-md-12 alert alert-danger" id="unidadeError" style="display: none"></div>
				</div>
		
				<div class="cadastrarParticipante">

					<div class="row">
						
						<div class="col-md-4">
							<div class="form-group">
								<label for="dt_nascimento"><?=$translate->translate('data_nascimento')?></label>
								<input type="text" id="dt_nascimento" name="dt_nascimento" class="form-control data placeholder">
							</div>
						</div>

						<div class="col-md-8">
							<div class="form-group">
								<label for="nome_mae"><?=$translate->translate('nome_mae')?></label>
								<input type="text" id="nome_mae" name="nome_mae" class="form-control">
							</div>
						</div>
					</div>
				
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="celular"><?=$translate->translate('celular')?></label>
								<input type="text" name="celular" id="celular" class="form-control fone required">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="telefone"><?=$translate->translate('telefone')?></label>
								<input type="text" name="telefone" id="telefone" class="form-control fone">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">                                        	
								<label for="email"><?=$translate->translate('email')?></label>
								<input type="text" name="email" id="email" class="form-control">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">                                        	
								<label for="matricula"><?=$translate->translate('matricula')?></label>
								<input type="text" name="matricula" id="matricula" class="form-control" maxlength="20">
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-12 spacer-10">
							<div class="radio-list">
								<label for="aceite_obrigacoes" class="radio-inline no-padding">
									<input type="checkbox" value="1" name="aceite_obrigacoes" id="aceite_obrigacoes"> <?=$translate->translate("msg_informacoes_obrigacoes")?>
								</label>
							</div>
							<label for="aceite_obrigacoes" class="error"></label>
						</div>

						<div class="col-md-12 spacer-10">
							<div class="radio-list">
								<label for="aceite_dados_pessoais" class="radio-inline no-padding">
									<input type="checkbox" value="1" name="aceite_dados_pessoais" id="aceite_dados_pessoais"> <?=$translate->translate("msg_dados_pessoais")?>
								</label>
							</div>
							<label for="aceite_dados_pessoais" class="error"></label>
						</div>


						<div class="col-md-12 spacer-10">
							<div class="radio-list">
							<?
								$linkRegulamento = objetoPHP(executaSQL("SELECT url_padrao FROM menu_padrao p, menu m WHERE p.id=m.id_menu_padrao AND m.id_menu_padrao='2' AND m.id_evento='1'"))->url_padrao;
								$params = array();
								$params[] = ($linkRegulamento!='')?'<a href="'.$_SESSION['campanha']->url_padrao.'/'.$linkRegulamento.'" target="_blank">'.$translate->translate('regulamento').'</a>' :$translate->translate('regulamento');

							?>
								<label for="aceite_regulamento" class="radio-inline no-padding">
									<input type="checkbox" value="1" name="aceite_regulamento" id="aceite_regulamento"> <?=traducaoParams( toHTML( $translate->translate("msg_regulamento") ), $params )?>
								</label>
							</div>
							<label for="aceite_regulamento" class="error"></label>
						</div>
					</div>


				</div>
		
				<div class="alert alert-warning spacer-20"><?=tohtml($translate->translate('msg_cadastrar_participante'))?></div>
		
				<div class="row">
					<div class="col-md-12 spacer-10">
						<button type="submit" class="btn btn-primary block"><i class="fa fa-check"></i> <?=$translate->translate("bt_salvar_informacoes")?></button>
					</div>
				</div>
		
			</div>
		
		</form>

		<div class="form-body alert alert-warning" id="parceiroCampanha" style="display: none; background: #F5A83C">
		
			<div class="row" style="padding: 10px">
				<div class="col-md-7" style="color: #FFF">

					<strong>Descontos exclusivos em compras on-line, com a nossa parceira Magazine Luiza.</strong>
					<br />10% na categoria utilidades domésticas, 3% na categoria informática e 5% nas demais categorias.
					<br /><br />*Clique na imagem e acesse ao site do nosso parceiro.<br /><br />
				</div>
				<div class="col-md-3 text-center">
					<a href="http://clube.magazineluiza.com.br/energiapararecomecar" target="_blank"><img src="images/parceria-magazineluiza.png" width="img-responsive" /></a>
				</div>
			</div>
		</div>

		<div class="alert alert-info ehCadastrado" id="alertas" style="display: none">
			<?=$translate->translate('msg_ja_possui_cadastro_confirme_informacoes')?>
		</div>
		
		<div id="numerosDaSorte" style="display: none">

			<div id="alertaCupons"></div>
		
			<div class="form-body">
		
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="cpf_participante"><?=$translate->translate('cpf')?></label>
							<input type="text" class="form-control getcpf" disabled="disabled">
						</div>
					</div>
		
					<div class="col-md-6">
						<div class="form-group">
							<label for="nome_participante"><?=$translate->translate('nome')?></label>
							<input type="text" class="form-control" id="nome_participante" disabled="disabled">
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="dt_participacao"><?=$translate->translate('dt_participacao')?></label>
							<input type="text" class="form-control" id="dt_participacao" disabled="disabled">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<table class="table table-striped table-bordered table-hover" id="listunidades">
							<thead>
								<tr>
									<th><?=$translate->translate("unidades_vinculadas")?></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<h3><?=$translate->translate('numero_s_da_sorte')?></h3>

						<div class="alert alert-warning">
							<?=$translate->translate('msg_geracao_numeros_sorte')?>
						</div>

						<table class="table table-striped table-bordered table-hover" id="listcupons">
							<thead>
								<tr>
									<th><?=$translate->translate("unidade")?></th>
									<th><?=$translate->translate("referencia")?></th>
									<th><?=$translate->translate("tipo_participacao")?></th>
									<th><?=$translate->translate("numero_da_sorte")?></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>

			</div>
		</form>

	    <script type="text/javascript">

			validaCPF = function(){
				cpf = $('#cpf_participante').val();
		
				if( isCpf(cpf) ){
					return true;
				}else{
					return false;
				}
			}

			validaUnidade = function(){
				
				var valida = false;

				unidadeVal = $('#unidade').val();

				cpfVal = $('#cpf_participante').val();
				
				$("#unidadeError").hide().html('');

				$.ajax({
					url: 'inc/json/verificar-unidade.php',
					type: 'post',
					data: {
							unidade: unidadeVal,
							cpf: cpfVal

					},
					async: false,
					cache: false,
					success: function(data){

						if( data.status ){
							$("#unidadeError").hide().html('');
							valida = true;
						}else{
							$("#unidadeError").show().html(data.msg);
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						msgInfo(XMLHttpRequest.responseText);
						//console.dir(XMLHttpRequest.responseText);
					},
					dataType: 'json'
				});	

				return valida;
			}
			
			consultaUnidadesParticipante = function(){

				$.ajax({
					url: "inc/json/consulta-unidades.php",
					type: 'post',
					async: false,
					cache: false,
					dataType: 'json',
					data: { cpf: $('#cpf_participante').val() }
				})
				.done(function( data ) {
					
					if( data.status ){
							
						var c = '';
	
						if( data['qtde']>0 ){

							for(x=0;x<data['qtde'];x++){
								
								c+= '<tr>';
								c+=     '<td>' + data['unidade'][x]+'</td>';
								c+= '</tr>';
							}
	
							$('#listunidades tbody').html(c);
							$('#listunidades').show();

						}
	
					}else{
						msgInfo(data.msg);
					}

				})
				.fail(function( jqXHR, textStatus ) {
				  alert( "Request failed: " + textStatus );
				});

				return false;
			}


			consultaCuponsParticipante = function(){

				$.ajax({
					url: "inc/json/consultacupons.php",
					type: 'post',
					async: false,
					cache: false,
					dataType: 'json',
					data: { cpf: $('#cpf_participante').val() }
				})
				.done(function( data ) {
					if( data.status ){
						
						var c = '';
	
						$('#qtdeCupons').html(data['qtde']);
			
						if( data['qtde']>0 ){

							for(x=0;x<data['qtde'];x++){
								
								c+= '<tr>';
									
								c+=     '<td>' + data['cupom']['unidade'][x]+'</td>';
								c+=     '<td>' + data['cupom']['referencia'][x]+'</td>';
								c+=     '<td>' + data['cupom']['participacao'][x]+'</td>';
								c+=     '<td>' + data['cupom']['elementos'][x]+'</td>';

								c+= '</tr>';
							}

	
							$('#listcupons tbody').html(c);
							$('#listcupons').show();

						}
	
						$('#numerosDaSorte').focus();

					}else{
						msgInfo(data.msg);
					}

				})
				.fail(function( jqXHR, textStatus ) {
				  alert( "Request failed: " + textStatus );
				});

				return false;
			}
	
			$(function(){

				$('#cpf_participante').focus();
						

				$('#cpf_participante').bind("keyup change",function(){
					validaCPF();
				});


				$('#unidade').bind("blur",function(){
					validaUnidade();
				});
		
				$('#verificarCPF').validate({
					rules: {
						cpf: "required"
					}
				});

				$('#cadastrarParticipante').validate({
					rules: {
						nome_completo: "required",

						dt_nascimento: {
							required : true,
							dateBR : true
						},

						unidade: "required",
						
						nome_mae: "required",

						email: {
							email : true
						},

						leu_regulamento: "required",
						aceite_obrigacoes: "required",
						aceite_dados_pessoais: "required",
						aceite_regulamento: "required"

					}
				});
		
									
				$('#verificarCPF').submit(function(){
					$("#cpfError").hide().html('');
					if( validaCPF() ){
						var cpf = $('#cpf_participante').val();
						
						$.ajax({
							url: "inc/json/verificarcpf.php",
							type: 'post',
							async: false,
							cache: false,
							dataType: 'json',
							data: { cpf: cpf }
						})
						.done(function( data ) {
							
							if( data.status ){
									
								if( data.ehparticipante ){
									$('#numerosDaSorte').show();
									$('#verificarCPF').hide();
	
									$('#nome_participante').val(data.nome);
									$('#dt_participacao').val(data.dt_hr_participacao);
									
									$('#numerosDaSorte .getcpf').val(cpf);
	
									consultaUnidadesParticipante();
									consultaCuponsParticipante();

									$('#parceiroCampanha').show();

									
								}else{
									$('.ehCadastrado').show();

									$('#cadastrarParticipante').show();
									$('#verificarCPF').hide();


									$('.getcpf').val(cpf);
									$('#nome_completo').val(data.dados.nome).attr('disabled', true);
									$('#dt_nascimento').val(data.dados.dt_nascimento);

									$('#nome_mae').val(data.dados.nome_mae);
	
									$('#telefone').val(data.dados.telefone);
									$('#celular').val(data.dados.celular);
									$('#email').val(data.dados.email);

								}
	
							}else{
								<?	if(!$eventoJaPassou){ ?>
										$('#cadastrarParticipante').show();
										$('#verificarCPF').hide();
			
										$('.getcpf').val(cpf);

										$("#unidade").focus();
								<?	}else{ ?>
										msgInfo('Campanha Encerrada!');
								<?	} ?>
								
							}

						})
						.fail(function( jqXHR, textStatus ) {
						  alert( "Request failed: " + textStatus );
						});

					}else{
						$("#cpfError").show().html('CPF Inválido!');
					}
		
					return false;
				});
		
				$('#cadastrarParticipante').submit(function(){
					//var dados = $(this).serialize();

					if( $(this).valid() ){

						if( validaUnidade() ){
							
							$.ajax({
								url: 'inc/json/cadastrarparticipante.php',
								type: 'post',
								data: {
									cpf: 			$('#cpf_participante').val(),
									unidade: 		$('#unidade').val(),
									
									telefone: 		$('#telefone').val(),
									celular: 		$('#celular').val(),
									email: 			$('#email').val(),

									nome_completo: 	$('#nome_completo').val(),
									dt_nascimento: 	$('#dt_nascimento').val(),
									nome_mae: 		$('#nome_mae').val(),

									matricula: 		$('#matricula').val()

								},
								async: false,
								cache: false,
								success: function(data){
								//console.log('data ' , data);
									
									$('.ehCadastrado').hide();

									if( data.status ){
										$('#cadastrarParticipante').hide();

										$('#numerosDaSorte').show();

										$('#parceiroCampanha').show();
			
										$('#nome_participante').val(data.nome);

										$('#dt_participacao').val(data.dt_hr_participacao);

										$('#numerosDaSorte .getcpf').val($('#cpf_participante').val());
										
										if(data.msg!=''){ msgInfo(data.msg); }

										consultaUnidadesParticipante();
										consultaCuponsParticipante();
																			
									}else{
										msgInfo(data.msg);
									}
								},
								error: function (XMLHttpRequest, textStatus, errorThrown) {
									msgInfo(XMLHttpRequest.responseText);
									//console.dir(XMLHttpRequest.responseText);
								},
								dataType: 'json'
							});

							return false;

						}
		
					}
					
					return false;
				});
								
			});

	    </script>
		
<?	}else{ ?>
		<div class="alert alert-warning spacer-20">Participação não disponível!</div>
<?	}?>