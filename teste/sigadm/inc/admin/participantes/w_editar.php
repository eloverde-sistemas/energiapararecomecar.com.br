<?
	$id = intval($_GET['id']);
	
	if($id>0){
		$lista = getParticipanteById($id);
		
		if(!$lista)
			header('Location: /adm/admin/participantes/listar');
	}else{
		header('Location: /adm/admin/participantes/listar');
	}
?>


	<h3 class="page-title">
        <?=$translate->translate('participantes')?> <small><?=( $id > 0 )? $translate->translate("editar") : $translate->translate("adicionar")?></small>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/participantes/listar"><?=$translate->translate('participantes')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
				<?=( $id > 0 )? $translate->translate("editar") : $translate->translate("adicionar")?>
            </li>
        </ul>
    </div>

	<form id="form" action="/adm/admin/participantes/editar" method="post">
    	<input name="id" id="id" type="hidden" class="campo" value="<?=$lista->id?>" />
        
        
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">
                    <? 
						$params=array();
                        if($id > 0){
                            $params[] = $translate->translate("editar");
                        }
                        echo traducaoParams($translate->translate('_participante'), $params);
                    ?>
                    </span>
                </div>
            </div>
            <div class="portlet-body form">
            	
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nome"><?=$translate->translate("nome")?></label>
                            <input name="nome" id="nome" type="text" class="form-control required" value="<?=$lista->nome?>" />
                        </div>
                    </div>

					<div class="col-md-3">
						<div class="radio-list">
							<label><?=$translate->translate('sexo')?></label>
							<label class="radio-inline no-padding" for="sexo-1">
								<input type="radio" value="1" name="sexo" id="sexo-1" <?=($lista->sexo==1)?'checked':''?> > <?=$translate->translate('sexo_1')?>
							</label>
							<label class="radio-inline" for="sexo-2">
								<input type="radio" value="2" name="sexo" id="sexo-2" <?=($lista->sexo==2)?'checked':''?> > <?=$translate->translate('sexo_2')?>
							</label>
						</div>
						<label for="sexo" class="error"></label>
					</div>
                    
					<div class="col-md-3">
						<div class="form-group">
							<label for="dt_nascimento"><?=$translate->translate('data_nascimento')?></label>
							<input type="text" id="dt_nascimento" name="dt_nascimento" class="form-control data" value="<?=converte_data($lista->dt_nascimento)?>">
						</div>
					</div>
				</div>
				
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cpf"><?=$translate->translate("cpf")?></label>
                            <input name="cpf" id="cpf" type="text" class="form-control required" value="<?=$lista->cpf?>" />
                        </div>
                    </div>
					
				</div>

                <div class="row">
						<div class="col-md-6">
							<div class="form-group">                                        	
								<label for="email"><?=$translate->translate('email')?></label>
								<input type="text" name="email" id="email" class="form-control" value="<?=$lista->email?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="telefone"><?=$translate->translate('telefone')?></label>
								<input type="text" name="telefone" id="telefone" class="form-control fone" value="<?=$lista->telefone?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="celular"><?=$translate->translate('celular')?></label>
								<input type="text" name="celular" id="celular" class="form-control fone" value="<?=$lista->celular?>">
							</div>
						</div>
				</div>

                <div class="row">
					<div class="col-xs-12">
						<div class="radio-list">
							<label><?=$translate->translate('deseja_receber_email_promocoes_parceiros')?></label>
							<label for="receber_email-1" class="radio-inline no-padding">
								<input type="radio" value="1" name="receber_email" id="receber_email-1" <?=($lista->receber_email==1)?'checked':''?>> <?=$translate->translate('bool_1')?>
							</label>
							<label for="receber_email-2" class="radio-inline">
								<input type="radio" value="2" name="receber_email" id="receber_email-2" <?=($lista->receber_email==2)?'checked':''?>> <?=$translate->translate('bool_2')?>
							</label>
						</div>
						<label for="receber_email" class="error"></label>
					</div>
				</div>
					
                <div class="portlet box grey-cascade  spacer-20">
                    <div class="portlet-title">
                        <div class="caption"><?=$translate->translate("endereco")?></div>
                        <div class="tools"></div>
                    </div>
                    <div class="portlet-body">                    	

                        <div class="row">

							<div class="col-md-2">
								<div class="form-group">
									<label for="cep"><?=$translate->translate("cep")?></label>
									<input name="cep" type="text" class="form-control cep required" id="cep" value="<?=$lista->cep?>">
									<label for="cep" class="errorMsg errorCEP" style="display:none"></label>
								</div>
							</div>
                            <div class="col-md-8">
                                <div class="form-group">
                                	<label for="endereco"><?=$translate->translate("logradouro")?></label>
			                        <input name="endereco" type="text" class="form-control required" id="endereco" size="60"  value="<?=$lista->logradouro?>"> 
                        		</div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                	<label for="numero"><?=$translate->translate("numero")?></label>
			                        <input name="numero" type="text"  class="form-control required" id="numero" value="<?=$lista->numero?>">
                        		</div>
                            </div>                            
                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="complemento"><?=$translate->translate("complemento")?></label>
                                    <input name="complemento" type="text" class="form-control" id="complemento" value="<?=$lista->complemento?>">
                                </div>
                            </div>

                            
                            <div class="col-md-4">
                                <div class="form-group">
                                	<label for="bairro"><?=$translate->translate("bairro")?></label>
			                        <input name="bairro" type="text" class="form-control required" id="bairro" value="<?=$lista->bairro?>">
                        		</div>
                            </div>
                        </div>
                        
                        <? $id_estado = objetoPHP(executaSQL("SELECT id_estado FROM municipio WHERE id='".$lista->id_cidade."'"))->id_estado;?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                	<label for="estado"><?=$translate->translate("estado")?></label>
                                   	<select name="estado" id="estado" class="form-control required">
                                        <option value="" ><?=$translate->translate("sel_estado")?></option>
                                   	<?	$exeEstados = executaSQL("SELECT * FROM estado ORDER BY nome");
                                        while($uf = objetoPHP($exeEstados)){ ?>
                                            <option value="<?=$uf->id?>" <?=($id_estado==$uf->id)?"selected":""?> ><?=$uf->nome?></option>
                                   	<?	} ?>
                                   	</select>
                        		</div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                	<label for="cidade"><?=$translate->translate("cidade")?></label>
                                    <select name="cidade" id="cidade" class="form-control required">
                                    <? 
                                        if($lista->id_cidade>0){
                                            $exeCidades = executaSQL("SELECT * FROM municipio WHERE id_estado = '".$id_estado."'");
                                            if(nLinhas($exeCidades)){
                                                while($cidade = objetoPHP($exeCidades)){?>
                                                    <option value="<?=$cidade->id?>" <?=($lista->id_cidade==$cidade->id)?"selected":""?> ><?=$cidade->nome?></option>
                                    <?			}
                                            }
                                        } 
                                    ?>
                                    </select>
                        		</div>
                            </div>                            
                            
                        </div>
                        
                    </div>
                </div>                
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/participantes/listar'"><?=$translate->translate('bt_cancelar')?></button>
                    <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                </div>
                <div class="clear"></div>
                
            </div>
        </div>
	</form>
				
<? 
	if( $_POST ){	
	
		$id = intval($_POST['id']);
		
		if( $_POST['nome'] == '' || $_POST['cpf']=='' ){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error");
			header("Location: /adm/admin/participantes/editar".($id>0)?'/'.$id :'');
			die();
		}else{
		
			$dados=array();
			$dados['nome']					= trim($_POST['nome']);
			$dados['cpf']		  			= trim($_POST['cpf']);
			$dados['sexo']					= intval($_POST['sexo']);
			
			$dados['dt_nascimento']			= converte_data($_POST['dt_nascimento']);

			$dados['email']  				= trim($_POST['email']);
			$dados['telefone']  			= trim($_POST['telefone']);
			$dados['celular']	 			= trim($_POST['celular']);

			$dados['cep']		  			= trim($_POST['cep']);			
			$dados['logradouro']  			= trim($_POST['endereco']);
			$dados['numero']	 			= trim($_POST['numero']);
			$dados['complemento'] 			= trim($_POST['complemento']);
			$dados['bairro']	  			= trim($_POST['bairro']);
			$dados['id_cidade']	  			= intval($_POST['cidade']);
			
			$dados['receber_email']	  		= intval($_POST['receber_email']);
				
			if($id>0){
				$exe = alterarDados("participante", $dados, " id='".$id."'");			
			}
			
			if( $exe ) {
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), 'success');
				header("Location: /adm/admin/participantes/listar/");
			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "danger");
				header("Location: /adm/admin/participantes/listar/");
			}
		}
	}
?>

	<script>
	
		$(document).ready(function(){
			
			$("#form").validate();
			
			$('#telefone, #fax').bind("blur keyup", function() { formataDDDTelefone($(this)); });
			
			//CARREGAS AS CIDADES
			$("#estado").change(function(){
				if( $(this).val()>0 ){
					carregaCidadesPeloEstado( "#cidade", $(this).val(), "");
				}
			});

			
			//Quando o campo cep perde o foco.
			$("#cep").blur(function() {
				$(".errorCEP").html('').hide();
				//Nova variável "cep" somente com dígitos.
				var cep = $(this).val().replace(/\D/g, '');

				//Verifica se campo cep possui valor informado.
				if (cep != "") {

					//Expressão regular para validar o CEP.
					var validacep = /^[0-9]{8}$/;

					//Valida o formato do CEP.
					if(validacep.test(cep)) {

						//Consulta o webservice viacep.com.br/
						$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

							if (!("erro" in dados)) {
								//Atualiza os campos com os valores da consulta.
								if( $("#endereco").val() =='' ){
									$("#endereco").val(dados.logradouro);
								}
								if( $("#bairro").val() =='' ){
									$("#bairro").val(dados.bairro);
								}
								
								carregaCidadesPelaCidadeIBGE("#cidade", dados.ibge, '#estado');
								
							}else{
								//CEP pesquisado não foi encontrado.
								$(".errorCEP").html("CEP não encontrado.").show();
							}
						});
					} //end if.
					else {
						//cep é inválido.
						$(".errorCEP").html("Formato de CEP inválido.").show();

					}
				}
			});
		});
	</script>