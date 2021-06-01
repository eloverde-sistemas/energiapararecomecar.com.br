<?php 

	if($_POST){

		$id = intval($_POST['id']);
		
		if($_POST['evento'] == '' || $_POST['titulo'] == '' || $_POST['posicao'] == '' ||  $_POST['nova_pagina'] == ''){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
			header("Location: /adm/admin/banners/editar/".$id);
		}else{
			
			$dados=array();
			$dados['titulo'] 			= trim($_POST['titulo']);				
			$dados['id_evento']			= intval($_POST['evento']);
			$dados['id_posicao']		= intval($_POST['posicao']);
			$dados['link'] 				= trim($_POST['link']);
			$dados['link_texto'] 			= trim($_POST['textoLink']);
			$dados['nova_pagina	']		= intval($_POST['nova_pagina']);
			
			$excluir_imagem 			= $_POST['excluir_image'];

			if($id>0){
				$exe = alterarDados('banner', $dados, 'id = "'.$id.'"');
			}else{
				$dados['id'] = $id = proximoId("banner");
				$exe = inserirDados('banner', $dados);
			}


			if($exe){

				if( $excluir_imagem == 1 ){					
					$arquivo_anterior = objetoPHP(executaSQLPadrao("banner", "id = '".$id."'"))->image_dir;							
					if( is_file( "../".$arquivo_anterior ) ){
						unlink( "../".$arquivo_anterior );
						alterarDados('banner', array("image_dir"=>''), 'id = "'.$id.'"');
					}
				}

				$exePosicao = executaSQL("SELECT * FROM banner_posicao WHERE id = '".$_POST['posicao']."'");
				if(nLinhas($exePosicao)>0){
					$posicaoImg = objetoPHP($exePosicao);
				}
				
				
				if( $_FILES['image_dir']['tmp_name'] != '' ){
					
					//TAMANHO DA IMAGEM INSERIDA
					$tamanho = getimagesize($_FILES['image_dir']['tmp_name']);
					$largura = $tamanho[0];
					$altura = $tamanho[1];
					
				//	var_dump($posicaoImg);
				//	var_dump($tamanho);

					if( ($largura == $posicaoImg->largura) && ($altura == $posicaoImg->altura) ){

						$ext = end(explode(".",$_FILES['image_dir']['name']));

						if( in_array( $ext , array("jpg","jpeg","png") ) ){						
							
							$image_dir = criaDiretorios( array("uploads", "banner", "imagem") ).$id.".".$ext;
							
							$upload = move_uploaded_file( $_FILES['image_dir']['tmp_name'], '../'.$image_dir );
							
							if($upload){
								alterarDados("banner", array("image_dir"=>$image_dir), "id='".$id."'");
							}else{
								echo "Não enviou a imagem: ".'/'.$image_dir;
							}
							
						}else{
							setarMensagem(array($translate->translate("msg_formato_arquivo_invalido")), "error");
							header("Location: /adm/admin/banners/editar/".$id);
							die();
						}
						
					}else{
						$params[] = $posicaoImg->largura;
						$params[] = $posicaoImg->altura;
						setarMensagem(array(traducaoParams( $translate->translate("msg_tamanho_imagem"), $params )), "error");
						header("Location: /adm/admin/banners/editar/".$id);
						die();
					}

				}

				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success"); 	
				header("Location: /adm/admin/banners/listar/");

			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error"); 	
				header("Location: /adm/admin/banners/editar/".$id);
			}
			
		}
	}


	$id = intval($_GET['id']);
	
	if( $id > 0 ){
		$exepPublicidade = executaSQLPadrao("banner", "id = '".$id."'");
		if(!nLinhas($exepPublicidade)>0){
			header("Location: /adm/admin/banners/listar");
		}else{
			$publicidade = objetoPHP($exepPublicidade);
		}
	}	
	
?>
	<h3 class="page-title">
        <?=$translate->translate('banners')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="index.html">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/banners/listar"><?=$translate->translate('banners')?></a>
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
								echo traducaoParams($translate->translate('_banner'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form id="form" action="/adm/admin/banners/editar/" method="post" enctype="multipart/form-data" class="horizontal-form">
                    	
        				<input type="hidden" name="id" class="form-control" id="id" value="<?=$id?>">
                        
                        <div class="form-body">
                            
                            <div class="row">
                                
								<div class="col-md-4">
									<div class="form-group">
										<label for="evento"><?=$translate->translate("evento")?></label>
										<select id="evento" class="form-control required" name="evento">
											<option value=""><?=$translate->translate("sel_evento")?></option>
											<?
												$exeEv = executaSQL("SELECT * FROM evento ORDER BY dt_inicio DESC");
												if(nLinhas($exeEv)>0){
												
													while($evento = objetoPHP($exeEv)){ ?>
															
														<option value="<?=$evento->id?>" <?=($publicidade->id_evento == $evento->id) ? 'selected' : '' ?> > <?=$evento->titulo?></option>	
															
											<?		}
												}
											?>
											
										 </select>
															
									</div>
								</div>

                                <div class="col-md-4">
									<div class="form-group">
										<label for="posicao"><?=$translate->translate("posicao")?></label>
										<select id="posicao" class="form-control required" name="posicao">
											<option value=""><?=$translate->translate("sel_posicao")?></option>
											<?
												$exePos = executaSQL("SELECT * FROM banner_posicao WHERE 1=1 ORDER BY ordem");
												if(nLinhas($exePos)>0){
												
													while($posicao = objetoPHP($exePos)){ ?>
															
														<option value="<?=$posicao->id?>" <?=($publicidade->id_posicao == $posicao->id) ? 'selected' : '' ?> > <?=$posicao->largura?>x<?=$posicao->altura?> - <?=$posicao->titulo?> </option>	
															
											<?		}
												}
											?>
											
										 </select>
															
									</div>
								</div>                                                           
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="titulo"><?=$translate->translate("titulo")?></label>
										<input type="text" name="titulo" class="form-control required" id="titulo" value="<?=$publicidade->titulo?>">
									</div>
								</div>

							</div>
							
							<div class="row">
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="link"><?=$translate->translate("link_url")?></label>
										<input type="text" name="link" class="form-control" id="link" maxlength="100" value="<?=$publicidade->link?>">
									</div>
								</div>
							
								<div class="col-md-6">
									<div class="form-group">
										<label for="textoLink"><?=$translate->translate("texto_link_url")?></label>
										<input type="text" name="textoLink" class="form-control" id="textoLink" maxlength="50" value="<?=$publicidade->link_texto?>">
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label><?=$translate->translate("imagem")?></label>
										<input type="file" name="image_dir" id="image_dir" size="40">
									</div>
								</div>
								
								<div class="col-md-6">
									<label><?=$translate->translate("abrir_nova_aba")?></label>
									<div class="form-group">
										<div class="radio-list">
											<label for="nova_pagina1" class="radio-inline"> 
												<input type="radio" class="required" name="nova_pagina" id="nova_pagina1" value="1" <?=($publicidade->nova_pagina == 1)?'checked="checked"':''?> > <?=$translate->translate("sim")?>
											</label>
											<label for="nova_pagina2" class="radio-inline"> 
												<input type="radio" class="required" name="nova_pagina" id="nova_pagina2" value="2" <?=($publicidade->nova_pagina == 2)?'checked="checked"':''?> > <?=$translate->translate("nao")?>
											</label>
										</div>
									</div>
								</div>
							</div>
								
					
						<? if( is_file("../".$publicidade->image_dir) ){ ?>
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label><?=$translate->translate("imagem_atual")?></label>
										<br />
										<a href="<?="../".$publicidade->image_dir?>" rel="shadowbox"><img src="<?="../".$publicidade->image_dir?>" width="300" border="0" /></a>
										<br />
										<label for="excluir_image" class="item"> <input type="checkbox" value="1"  class="form-control" name="excluir_image" id="excluir_image" /> <?=$translate->translate("excluir_imagem_atual")?></label>
									</div>
								</div>
							</div>
						<? } ?>                  
    
					   
					   
					    <div class="form-actions left">
                            <button type="button" class="btn red" onclick="window.location='/adm/admin/banners/listar'"> <?=$translate->translate('bt_cancelar')?></button>
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
				
			$("#form").validate();
			
		});
		Shadowbox.init({
			handleOversize: "resize",
			modal: false
		});
	</script>