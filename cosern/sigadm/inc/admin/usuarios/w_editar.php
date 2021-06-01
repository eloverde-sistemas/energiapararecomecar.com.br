<?php 
	$id = intval($_GET['id']);
	if( $id > 0 ){
		$exePerfil = executaSQLPadrao("pessoa", "id = '".$id."'");
		if(!nLinhas($exePerfil)>0){
			header("Location: /adm/admin/usuarios/listar");
			die();
		}else{
			$perfil = objetoPHP($exePerfil);
			
			if($perfil->eh_admin==1){
				header("Location: /adm/admin/usuarios/listar");
				die();
			}
		}
	}
	
	if($_POST){
	
		$id = intval($_POST['id']);
		
		$alteracao = false;
		
		if($_POST['email'] == ''){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
			header("Location: /adm/admin/usuarios/editar/".$id);
		}else{
			
			$dados['login']	  	  = trim($_POST['login_']);
			$dados['email']	  	  = trim($_POST['email']);
			
			if($id>0){
				$usuario = executaSQLPadrao("pessoa", "id = '".$id."'");
				if( nLinhas($usuario)>0 ){
					$usuario = objetoPHP($usuario);	
					if( $usuario->id_tipo!=1 ){
						$dados['nome'] = trim($_POST['nome']);
					}
				}
				
				$pessoa = objetoPHP( executaSQLPadrao('pessoa', "id = '".$id."'") );
				
				$alteracao=true;
				$exe = alterarDados('pessoa', $dados, 'id = "'.$id.'"');
				
			}else{
				$dados['id'] = $id = proximoId("pessoa");
				$dados['nome'] 			= trim($_POST['nome']);
				$dados['id_situacao']	= $_POST['situacao'];
				$dados['id_tipo'] 	  	= 2;
				
				$exe = inserirDados('pessoa', $dados);
			}
			
			if(temPermissao('ADMIN_USUARIOS_PERFIL')){
				$perfil = $_POST['perfil'];
				excluirDados("pessoa_perfil", "id_pessoa = '".$id."' AND id_perfil>0");
				excluirDados("loja_acesso", "id_pessoa = '".$id."'");
				if(count($perfil)>0){
					foreach($perfil as $value){
						$adicionaPerfil = inserirDados('pessoa_perfil', array('id_perfil'=>$value, 'id_pessoa'=>$id));
						
						if($value==2){
							inserirDados('loja_acesso', array('id_pessoa'=>$id, 'loja'=>$pessoa->id_loja));
						}
					}
				}
			}
		
			require_once("lib/phpMailer/PHPMailerAutoload.php");			
			
			$usuario = executaSQLPadrao("pessoa", "id='".$id."'");
			if( nLinhas($usuario)>0 ){				
				$usuario = objetoPHP($usuario);
				
				if($_POST['senha']==1){
					
					$senhaEnvio = geraSenha(6);
					$senhaMd5 = md5($senhaEnvio);
					alterarDados("pessoa", array("senha"=>$senhaMd5, 'esqueceu_senha'=>true), "id='".$id."'");
					
					// 1 - Nome Usuário
					$params[] = $usuario->nome;
					// 2 - Nome da Loja
					$params[] = $_CONFIG['po_sigla'];
					// 3 - Link
					if($pessoa->id_tipo==1){//IRMÃO
						$params[] = $_CONFIG['http_s'].$_SERVER['SERVER_NAME'];
					}else{
						$params[] = $_CONFIG['http_s'].$_SERVER['SERVER_NAME']."/adm";
					}
					// 4 - Senha Nova
					$params[] = $senhaEnvio;
					// 5 - Login
					if($pessoa->id_tipo==1){//IRMÃO
						$params[] = $usuario->cim;
					}else{
						$params[] = $usuario->login;
					}

					$destinatario = $usuario->email;
					
					if($alteracao){
						$msg 	 = traducaoParams( toHTML( $translate->translate("email_recuperacao_senha_colaborador") ), $params );
						if($pessoa->id_tipo==1){//IRMÃO
							$msg 	 = traducaoParams( toHTML( $translate->translate("email_recuperacao_senha") ), $params );
						}
						$assunto = $translate->translate("recuperacao_senha");
						$mail 	 = enviaEmail($assunto, $msg, $destinatario);								
						
					}else{
						$msg 	 = traducaoParams( toHTML( $translate->translate("email_novo_usuario") ), $params );
						$assunto = traducaoParams($translate->translate("assunto_email_novo_usuario"), $params);
						$mail 	 = enviaEmail($assunto, $msg, $destinatario);
						
					}
					
				}
			}
			
			if(!$exe){
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error"); 	
				header("Location: /adm/admin/usuarios/editar/".$id);
			
			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success"); 	
				header("Location: /adm/admin/usuarios/listar/");
			}
		}		
	}
?>
	<h3 class="page-title">
        <?=$translate->translate('tt_usuarios')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
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
                <a href="/adm/admin/usuarios/listar"><?=$translate->translate('tt_usuarios')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
            	<?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?>
            </li>
		</ul>
    </div>
    
    <div class="row">
        <div class="col-md-12">
        	
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
								echo traducaoParams($translate->translate('_usuario'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form id="form" action="/adm/admin/usuarios/editar" method="post" enctype="multipart/form-data" class="horizontal-form">
                    	<?
							$usuario = executaSQLPadrao("pessoa", "id='".$id."'");		
					
							$usuario = objetoPHP($usuario);
						?>
        				<input type="hidden" name="id" class="form-control" id="id" value="<?=$id?>">
       					<input type="hidden" name="id_tipo" class="form-control" value="<?=$usuario->id_tipo?>">
                        
                        <div class="form-body">
                            
                            <div class="row">
                                
                                <!--<div class="col-md-2">
                                    <div class="form-group">
										<label for="tipo_membro"><?=$translate->translate("tipo_membro")?></label>
										<input type="text" id="tipo_membro" class="form-control" name="tipo_membro" value="<?=($id>0 ? $translate->translate("tipo_pessoa_".$usuario->id_tipo) : $translate->translate("tipo_pessoa_2") )?>" disabled="disabled">
                                    </div>
                                </div>-->

                                <div class="col-md-2">
									<div class="form-group">
                                            <label for="situacao"><?=$translate->translate("situacao")?></label>								
									<?	if(!$id>0 || $usuario->id_tipo==2){?>
                                    		<select class="form-control required" id="situacao" name="situacao">
                                            	<option value=""></option>
                                            <?
                                            	$regs = executaSQLPadrao('pessoa_situacao', "1=1 ORDER BY id");
												while($reg = objetoPHP($regs)){
											?>
                                                	<option value="<?=$reg->id?>" <?=$usuario->id_situacao==$reg->id ? 'selected="selected"' : ''?>><?=getSituacaoById($reg->id)?></option>
                                            <?	} ?>
                                            </select>
                                    <?	}else{ ?>
											<input type="text" class="form-control" value="<?=getSituacaoById($usuario->id_situacao)?>" disabled="disabled">
									<?	} ?>
                                    </div>
								</div>
                           
                                <div class="col-md-8">
									<div class="form-group">
										<label for="nome"><?=$translate->translate("nome")?></label>
										<input type="text" class="form-control" id="nome" name="nome" value="<?=$usuario->nome?>" <?=(isset($usuario) && $usuario->id_tipo==1)?'disabled="disabled"' :''?> >
									</div>
								</div>
                            </div>
							
							<div class="row">
                                <div class="col-md-4">
									<div class="form-group">
										<?	if($usuario->id_tipo == 1){?>
												<label for="cim"><?=$translate->translate("cim")?></label>
												<input type="text" id="cim" class="form-control" name="cim" value="<?=$usuario->cim?>" disabled>
										<? 	}else{?>
												<label for="login_"><?=$translate->translate("login")?></label>
												<input type="text" id="login_" class="form-control" name="login_" value="<?=$usuario->login?>">
												<label class="msgValidate hide" id="codInvalido"><?=$translate->translate("msg_usuario_existe")?></label>
										<?	}?>
									</div>
								</div>
                           
                                <div class="col-md-6">
									<div class="form-group">
										<label for="email"><?=$translate->translate("email")?></label>
                						<input type="text" id="email" class="form-control" name="email" value="<?=$usuario->email?>">
									</div>
								</div>
                            
                                <div class="col-md-2">
									<div class="form-group">
										<div class="row"><label for="sim"><?=($id>0 ? $translate->translate("gerar_nova_senha") : $translate->translate("enviar_senha_por_email") )?></label></div>
										<label for="sim" class="item"><input type="radio" id="sim" class="form-control" name="senha" value="1" <?=($id==0 ? 'checked' : '')?> /> <?=$translate->translate("sim")?></label>
										<label for="nao" class="item omega"><input type="radio" class="form-control" id="nao" name="senha" value="0" <?=($id>0 ? 'checked' : '')?> /> <?=$translate->translate("nao")?></label>
									</div>
								</div>
                            </div>
							
							
							
						<?	if(temPermissao('ADMIN_USUARIOS_PERFIL')){ ?>  
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="perfil"><?=$translate->translate("perfil")?></label>
											<div class="checkbox-list">
											<?	
												$perfisPessoa = consultaPerfisIdByIdPessoa($id);
												$perfis = executaSQLPadrao("perfil", "id NOT IN (-99) ORDER BY nome");
												
												while( $perfil = objetoPHP( $perfis ) ){
											?>
													<label for="perfil-<?=$perfil->id?>" class="ml-25">
														<input type="checkbox" class="form-control" name="perfil[]" id="perfil-<?=$perfil->id?>" value="<?=$perfil->id?>" <?=in_array($perfil->id, $perfisPessoa) ? 'checked="checked"' : ''?>> <?=$perfil->nome?>
													</label>
											<?
												}
											?>
											</div>
										</div>
									</div>
								</div>
                       <?	} ?>     
                        </div>
                        <div class="form-actions left">
                            <button type="button" class="btn red" onclick="window.location='/adm/admin/usuarios/listar'"> <?=$translate->translate('bt_cancelar')?></button>
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                        </div>
                    </form>
                    <!-- END FORM-->
                    <div class="clear"></div>
                </div>
            </div>
			            
		</div>
	</div>
	
	<script>
       $(function(){
			
            $("#form").validate({
                rules: {
                    email: {
                        required:true,
                        email : true
                    },
					nome:"required",
					login_:'required'
                }
            });
			
		<?	if(!$id>0 || $usuario->id_tipo == 2){?>
				
				$("#form").submit(function(){
					
					if($("#form").valid() == true){
						return validaNome('<?=$id?>');					
					}
				});
				
				$("#login_").blur(function(){
					validaNome('<?=$id?>');
				});
				
				validaNome = function(id){
					
					var retorno;
					$.ajax({
						url: 'inc/genericoJSON.php',
						type: 'post',
						data: {
							acao: 'validaLoginUsuario',
							nome: $('#login_').val(),
							id: id
						},
						cache: false,
						async: false,
						success: function(data) {
							
							if(data.status==false){
								$("#codInvalido").addClass('show').removeClass('hide');
								retorno = false;
							}else{
								retorno = true;
								$("#codInvalido").addClass('hide').removeClass('show');			
							}
							
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							alert(XMLHttpRequest.responseText);
						},
						dataType: 'json'
					});
					return retorno;
				}
		<?	} ?>		
		 
        });
	</script>