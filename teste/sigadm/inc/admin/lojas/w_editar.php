<?
	$id = intval($_GET['id']);
	
	if($id>0){
		$lista = consultaLojaByNr($id);
		
		if(!$lista)
			header('Location: /adm/admin/lojas/listar');
	}
?>


	<h3 class="page-title">
        <?=$translate->translate('lojas')?> <small><?=( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar")?></small>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/lojas/listar"><?=$translate->translate('lojas')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
				<?=( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar")?>
            </li>
        </ul>
    </div>

	<form id="form" action="/adm/admin/lojas/editar" method="post">
    	<input name="id" id="id" type="hidden" class="campo" value="<?=$lista->id?>" />
        
        
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">
                    <? 
						$params=array();
                        if($id > 0){
                            $params[] = $translate->translate("ttl_editar");
                        }else{
                            $params[] = $translate->translate("tt_adicionar");
                        }
                        echo traducaoParams($translate->translate('_loja'), $params);
                    ?>
                    </span>
                </div>
            </div>
            <div class="portlet-body form">
            	
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nomeFantasia"><?=$translate->translate("nome_fantasia")?></label>
                            <input name="nomeFantasia" id="nomeFantasia" type="text" class="form-control required" value="<?=$lista->nome_fantasia?>" />
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="razaoSocial"><?=$translate->translate("razao_social")?></label>
                            <input name="razaoSocial" id="razaoSocial" type="text" class="form-control required" value="<?=$lista->razao_social?>" />
                        </div>
                    </div>
                    
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cnpj"><?=$translate->translate("cnpj")?></label>
                            <input name="cnpj" id="cnpj" type="text" class="form-control required cnpj" value="<?=$lista->cnpj?>" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><?=$translate->translate("situacao")?></label>
                            <div class="radio-list">
                                <label class="radio-inline" for="ativa">
                                    <input type="radio" name="ativa" id="ativa" value="1" class="required" <?=($lista->id_situacao==1 ? 'checked' : '')?>> <?=$translate->translate("ativa")?>
                                </label>
                                <label class="radio-inline" for="inativa">
                                    <input type="radio" name="ativa" id="inativa" value="2" class="required" <?=($lista->id_situacao==2 ? 'checked' : '')?>> <?=$translate->translate("inativa")?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>                 
            
	            <div class="portlet box grey-cascade">
	                <div class="portlet-title">
	                    <div class="caption"><?=$translate->translate('contato_s')?></div>
	                    <div class="tools"></div>
	                </div>
	                <div class="portlet-body">
	                	
	                    <div class="form-body">                            	
	                    <?	if( temPermissao("ADMIN_CADASTRO_ALTERAR")){ ?>
	                            <a href="javascript:void(0);" id="add_contato" class="btn btn-sm blue" title="<?=$translate->translate("add_contato")?>" alt="<?=$translate->translate("add_contato")?>">
	                                <i class="fa fa-plus"></i>
	                                <?=$translate->translate("add_contato")?>
	                            </a>                        
	                            
	                            <div class="clear">&nbsp;</div>                        
	                            <div id="append_contato"></div>
						<?	} ?>
	                        
						<?
							$z = 0;

							$contatos	= executaSQLPadrao('loja_contato', "id_loja='".$lista->id."' ORDER BY id_contato_tipo");
							$nContatos	= nLinhas($contatos);
							if($nContatos>0){
	                        
	                            $temContato = 1;
	                   
	                            while($contato = objetoPHP($contatos)){
	                                $z++;
	                    ?>
	                                <div class="row">
	                                	<div class="col-md-5">
	                                    	<div class="form-group">
	                                        	<select name="tipo-contato[]" id="tipo-contato-<?=$z?>" class="form-control">
	                                                <option value=""></option>
	                                            <?
	                                                $tipoContatos = executaSQLPadrao('contato_tipo');
	                                                while($tipoContato = objetoPHP($tipoContatos)){
	                                            ?>
	                                                    <option value="<?=$tipoContato->id?>" <?=$tipoContato->id==$contato->id_contato_tipo ? 'selected="selected"' : ''?>><?=$translate->translate('pessoa_tipo_contato_'.$tipoContato->id)?></option>
	                                            <?
	                                                }
	                                            ?>
	                                        	</select>
	                                    	</div>
	                                	</div>
	                                    
	                                    <div class="col-md-6">
	                                    	<div class="form-group">
	                                    		<input type="text" name="valor-contato[]" id="valor-contato-<?=$z?>" value="<?=$contato->valor?>" class="form-control">
	                                        </div>
	                                    </div>
	                                    
	                                    <div class="col-md-1">
	                                    	<div class="form-group">
	                                    		<label for="excluir-contato-<?=$z?>">
	                                                <input type="checkbox" name="excluir-contato[<?=$z-1?>]" id="excluir-contato-<?=$z?>" class="form-control" value="1"> <?=$translate->translate('ttl_excluir')?>
	                                            </label>    
	                                        </div>
	                                    </div>    
	                                </div>
	                    <?		}
	                        }else{
	                            $temContato = 0;
	                        }
	                    ?>
	                        
	                    </div>
	                </div>
	            </div>
			
	            <div class="portlet box grey-cascade">
	                <div class="portlet-title">
	                    <div class="caption"><?=$translate->translate('telefone_s')?></div>
	                    <div class="tools"></div>
	                </div>
	                <div class="portlet-body">
	                	
	                    <div class="form-body"> 
	                    <?	if( temPermissao("ADMIN_CADASTRO_ALTERAR")){ ?>
	                            <a href="javascript:void(0);" id="add_telefone" class="btn btn-sm blue" title="<?=$translate->translate("add_telefone")?>" alt="<?=$translate->translate("add_telefone")?>">
	                                <i class="fa fa-plus"></i>
	                                <?=$translate->translate("add_telefone")?>
	                            </a>                        
	                            
	                            <div class="clear">&nbsp;</div>
	                            <div id="append_telefone"></div>
	                    <?	} ?>
	                    
						<?  $t = 0;
	                    	
	                    	$contatos	= executaSQLPadrao('loja_telefone', "id_loja='".$lista->id."'");
					    	if( nLinhas($contatos)>0){
	                            $temTel = 1;

	                            while($contato = objetoPHP($contatos)){
	                                $t++;
	                                //pessoa_tipo_telefone
	                    ?>
	                                
	                                <div class="row">
	                                	<div class="col-md-4">
	                                    	<div class="form-group">
	                                            <select name="tipo-telefone[]" id="tipo-telefone-<?=$t?>" class="form-control">
	                                                <option value=""></option>
	                                            <?
	                                                $operadoras = executaSQLPadrao('telefone_tipo');
	                                                while($operadora = objetoPHP($operadoras)){
	                                            ?>
	                                                    <option value="<?=$operadora->id?>" <?=$operadora->id==$contato->id_tipo_telefone ? 'selected="selected"' : ''?>><?=$operadora->valor?></option>
	                                            <?
	                                                }
	                                            ?>
	                                            </select>
	                                    	</div>
	                                	</div>
	                                    
	                                    <div class="col-md-4">
	                                    	<div class="form-group">
	                                        	<input type="text" name="valor-telefone[]" id="valor-telefone-<?=$t?>" value="<?=$contato->num?>" class="form-control fone">
	                                        </div>
	                                    </div>
	                                    
	                                    <div class="col-md-3">
	                                    	<div class="form-group">
	                                        	<select name="operadora-telefone[]" id="operadora-telefone-<?=$t?>" class="form-control">
	                                                <option value=""></option>
	                                            <?
	                                                $operadoras = executaSQLPadrao('operadora', '1 ORDER BY valor');
	                                                while($operadora = objetoPHP($operadoras)){
	                                            ?>
	                                                    <option value="<?=$operadora->id?>" <?=$operadora->id==$contato->operadora ? 'selected="selected"' : ''?>><?=$operadora->valor?></option>
	                                            <?
	                                                }
	                                            ?>
	                                            </select>
	                                        </div>
	                                    </div>
	                                    
	                                    <div class="col-md-1">
	                                    	<div class="form-group">
	                                        	<label for="excluir-telefone-<?=$t?>">
	                                                <input type="checkbox" name="excluir-telefone[<?=$t-1?>]" id="excluir-telefone-<?=$t?>" value="1"> <?=$translate->translate('ttl_excluir')?>
	                                            </label>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="clear"></div>
	                    <?		}
	                        }else{
	                            $temContato = 0;
	                        }
	                    ?>
	                    
	                    </div>
	                </div>
	            </div>

                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption"><?=$translate->translate("endereco")?></div>
                        <div class="tools"></div>
                    </div>
                    <div class="portlet-body">                    	
                        
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                	<label for="endereco"><?=$translate->translate("logradouro")?></label>
			                        <input name="endereco" type="text" class="form-control required" id="endereco" size="60"  value="<?=$lista->logradouro?>"> 
                        		</div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                	<label for="nr"><?=$translate->translate("numero")?></label>
			                        <input name="nr" type="text"  class="form-control required" id="nr" value="<?=$lista->numero?>">
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
                                	<label for="cep"><?=$translate->translate("cep")?></label>
			                        <input name="cep" type="text" class="form-control required" id="cep" value="<?=$lista->cep?>">
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
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/lojas/listar'"><?=$translate->translate('bt_cancelar')?></button>
                    <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                </div>
                <div class="clear"></div>
                
            </div>
        </div>
	</form>
				
<? 
	if( $_POST ){	
	
		$id = intval($_POST['id']);
		
		if($_POST['nomeFantasia'] == '' || $_POST['razaoSocial'] == '' || $_POST['cnpj'] == '' || $_POST['ativa'] == '' ){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error");
			header("Location: /adm/admin/lojas/editar");
			die();
		}else{
		
			$dados=array();
			$dados['nome_fantasia']					= trim($_POST['nomeFantasia']);
			$dados['razao_social']		  			= trim($_POST['razaoSocial']);
			$dados['cnpj']			 				= trim($_POST['cnpj']);
			$dados['id_situacao']  					= intval($_POST['ativa']);
			$dados['logradouro']  					= trim($_POST['endereco']);
			$dados['numero']	  					= trim($_POST['nr']);
			$dados['complemento'] 					= trim($_POST['complemento']);
			$dados['cep']		  					= trim($_POST['cep']);			
			$dados['bairro']	  					= trim($_POST['bairro']);
			$dados['id_cidade']	  					= intval($_POST['cidade']);
				
			if($id>0){
				$exe = alterarDados("loja", $dados, " id='".$id."'");			
			}else{
				$dados['id'] = $id = proximoId("loja");
				$exe = inserirDados("loja", $dados);
			}
			
			if( $exe ) {
				
				//Grava novos contatos
				if( count($_POST['valor-contato'])>0 ){
					
					excluirDados('loja_contato', "id_loja='".$id."'");
					
					$dados = array();
					for($i=-1; $i<count($_POST['valor-contato']); $i++){
						
						if($_POST['valor-contato'][$i]!='' && !$_POST['excluir-contato'][$i]>0){
							$dados['id_loja']			= $id;
							$dados['id_contato_tipo']	= intval($_POST['tipo-contato'][$i]);
							$dados['valor']				= $_POST['valor-contato'][$i];
							
							inserirDados('loja_contato', $dados);
						}
					}
				}
				
				

				//Grava novos telefones
				if( count($_POST['valor-telefone'])>0 ){
					
					excluirDados('loja_telefone', "id_loja='".$id."'");
					
					foreach($_POST['valor-telefone'] as $i => $num){
						
						$idTipo 		= intval($_POST['tipo-telefone'][$i]);
						$idOperadora 	= intval($_POST['operadora-telefone'][$i]);

						if($num!='' && !$_POST['excluir-telefone'][$i]>0){

							$dados = array();
							$dados['id_loja']			= $id;
							$dados['id_tipo_telefone']	= $idTipo;
							$dados['num']				= $num;
							$dados['operadora']			= $idOperadora;
							
							if(inserirDados('loja_telefone', $dados)){
								//echo "INSERIU";
							}

						}
					}
				}
				
				
				
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), 'success');
				header("Location: /adm/admin/lojas/listar/");
			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "danger");
				header("Location: /adm/admin/lojas/listar/");
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

			var numContato = '<?=$z?>';
			var temContato = '<?=$temContato?>';
			
			$('#add_contato').click(function(){
				numContato++;
				reg = '';
				
				reg+= 	'<div id="novo_contato_' + numContato +'" class="form-body">';				
				
				reg+=		'<div class="row">';
				reg+= 			'<div class="col-md-5">';
				reg+=				'<div class="form-group">';
				reg+= 					'<select name="tipo-contato[]" id="tipo-contato-' + numContato + '" class="form-control">';
                reg+= 						'<option value=""><?=$translate->translate('sel_contato')?></option>';
										<?
											$tipoContatos = executaSQLPadrao('contato_tipo');
											while($tipoContato = objetoPHP($tipoContatos)){
										?>
				reg+= 							'<option value="<?=$tipoContato->id?>"><?=$translate->translate('pessoa_tipo_contato_'.$tipoContato->id)?></option>';
										<?	} ?>
                reg+= 					'</select>';
				reg+= 				'</div>';
				reg+= 			'</div>';
				
				reg+= 			'<div class="col-md-6">';
				reg+=				'<div class="form-group">';
				reg+= 					'<input type="text" name="valor-contato[]" id="valor-contato-' + numContato + '" placeholder="<?=$translate->translate('contato')?>" class="form-control" value="">';
				reg+= 				'</div>';
				reg+= 			'</div>';
				
				reg+= 			'<div class="col-md-1">';
				reg+=				'<div class="form-group">';
				reg+=					'<a href="javascript:void(0);" data-number="' + numContato + '" class="btn btn-sm red excluir-contato" title="<?=$translate->translate("ttl_excluir")?>" alt="<?=$translate->translate("ttl_excluir")?>">';
                reg+=        				'<i class="fa fa-times"></i>';
                reg+=					'</a>';
				reg+= 				'</div>';
				reg+= 			'</div>';
				reg+= 		'</div>';
				reg+= 	'</div>';
				
				$('#append_contato').append(reg);
			
				$('.excluir-contato').click(function(){
					number = $(this).attr('data-number');
					$('#novo_contato_' + number).remove();
				});
			});
			
			var numTel = '<?=$t?>';
			var temTel = '<?=$temTelefone?>';
			
			$('#add_telefone').click(function(){
				numTel++;
				reg = '';
				
				reg+= 	'<div id="novo_telefone_' + numTel +'" class="form-body">';
				
				reg+=		'<div class="row">';
				reg+= 			'<div class="col-md-4">';
				reg+=				'<div class="form-group">';
				reg+= 					'<select name="tipo-telefone[]" id="tipo-telefone-' + numTel + '" class="form-control">';
                reg+= 						'<option value=""><?=$translate->translate('sel_tipo_telefone')?></option>';
										<?
											$operadoras = executaSQLPadrao('telefone_tipo');
											while($operadora = objetoPHP($operadoras)){
										?>
				reg+= 							'<option value="<?=$operadora->id?>"><?=$operadora->valor?></option>';
										<?
											}
										?>
                reg+= 					'</select>';
				reg+= 				'</div>';
				reg+= 			'</div>';
				
				reg+= 			'<div class="col-md-4">';
				reg+=				'<div class="form-group">';
				reg+= 					'<input type="text" name="valor-telefone[]" id="valor-telefone-' + numTel + '" placeholder="<?=$translate->translate('telefone')?>" class="form-control">';
				reg+= 				'</div>';
				reg+= 			'</div>';
				
				reg+= 			'<div class="col-md-3">';
				reg+=				'<div class="form-group">';
				reg+= 					'<select name="operadora-telefone[]" id="operadora-telefone-' + numTel + '" class="form-control">';
                reg+= 						'<option value=""><?=$translate->translate('sel_operadora')?></option>';
										<?
											$operadoras = executaSQLPadrao('operadora', '1 ORDER BY valor');
											while($operadora = objetoPHP($operadoras)){
										?>
				reg+= 							'<option value="<?=$operadora->id?>"><?=$operadora->valor?></option>';
										<?
											}
										?>
                reg+= 					'</select>';
				reg+= 				'</div>';
				reg+= 			'</div>';
				
				reg+= 			'<div class="col-md-1">';
				reg+=				'<div class="form-group">';
				reg+=					'<a href="javascript:void(0);" data-number="' + numTel + '" class="btn btn-sm red excluir-telefone" title="<?=$translate->translate("ttl_excluir")?>" alt="<?=$translate->translate("ttl_excluir")?>">';
                reg+=        				'<i class="fa fa-times"></i>';
                reg+=					'</a>';
				reg+= 				'</div>';
				reg+= 			'</div>';
				reg+= 		'</div>';
				reg+= 	'</div>';
				
				$('#append_telefone').append(reg);
			
				$('#valor-telefone-' + numTel).bind("blur keyup", function() { formataDDDTelefone($(this)); });
			
				$('.excluir-telefone').click(function(){
					number = $(this).attr('data-number');
					$('#novo_telefone_' + number).remove();
				});
			});
			
		});
	</script>