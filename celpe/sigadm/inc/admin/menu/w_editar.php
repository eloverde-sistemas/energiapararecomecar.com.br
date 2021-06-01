<? 
	if($_POST){

		$id = intval($_POST['id']);

		$idEvento 			= intval($_POST['evento']);
		$idTipo 			= intval($_POST['tipo']);
		$titulo 			= trim($_POST['titulo']);
		$url				= trim($_POST['url']);
		$conteudoPagina		= trim($_POST['conteudo']);
		$linkExterno		= trim($_POST['linkExterno']);
		$ativo				= intval($_POST['ativo']);

		if($id>0){

			if( $titulo == '' || $ativo == '' ){
				setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
				header("Location: /adm/admin/menu/editar/".$id);
			}else{

				$exeMenu = executaSQL("SELECT * FROM menu WHERE id='".$id."'");
				if(nLinhas($exeMenu)>0){
					$regMenu = objetoPHP($exeMenu);

					$dados = array();
					$dados['titulo'] 			= $titulo;
					$dados['ativo']				= $ativo;

					if($regMenu->id_tipo==2){//PAGINA
						$dados['url']				= $url;
						$dados['conteudo_pagina']	= $conteudoPagina;	
					}else{
						$dados['url']				= $linkExterno;
					}					
					
					
					$exe = alterarDados('menu', $dados, 'id = "'.$id.'"');
					
					if($exe){
						setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
						header("Location: /adm/admin/menu/listar/");

					}else{
						setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error"); 	
						header("Location: /adm/admin/menu/editar/".$id);
					}

				}else{
					setarMensagem(array($translate->translate("msg_sem_registro")), "error"); 	
					header("Location: /adm/admin/menu/listar");
				}
				
			}

		}else{

			if( $titulo == '' || $idTipo == 0 || $idEvento == 0 || $ativo == '' ){
				setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
				header("Location: /adm/admin/menu/editar/".$id);
			}else{

				$dados = array();
				$dados['id'] 				= proximoId("menu");
				$dados['id_evento']			= $idEvento;
				$dados['id_tipo'] 			= $idTipo;
				$dados['titulo'] 			= $titulo;

				if($idTipo==2){//PAGINA
					$dados['url']				= $url;
					$dados['conteudo_pagina']	= $conteudoPagina;	
				}else{
					$dados['url']				= $linkExterno;
				}

				$dados['ordem']				= getProximoNumeroOrdemMenu($idEvento);
				$dados['ativo']				= $ativo;
				
				$exe = inserirDados('menu', $dados);
				
				if($exe){
					setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
					header("Location: /adm/admin/menu/listar/");
				}else{
					setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error"); 	
					header("Location: /adm/admin/menu/editar/".$id);
				}

			}

		}


		
	}


	$id = intval($_GET['id']);
	
	if( $id > 0 ){
		$exe = executaSQLPadrao("menu", "id = '".$id."'");
		if(nLinhas($exe)>0){
			$reg = objetoPHP($exe);
		}else{			
			header("Location: /adm/admin/menu/listar");
			die();
		}
	}	
	
?>

	<h3 class="page-title">
        <?=$translate->translate('menu')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/menu/listar"><?=$translate->translate('menu')?></a>
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
								echo traducaoParams($translate->translate('_menu'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    
                    <form id="form" action="/adm/admin/menu/editar" method="post" class="horizontal-form">
                    	
        				<input type="hidden" name="id" class="form-control" id="id" value="<?=$id?>">
                        
                        <div class="form-body">
                            
                            <div class="row">
                                
								<div class="col-md-4">
									<div class="form-group">
										<label for="evento"><?=$translate->translate("evento")?></label>
										<select id="evento" class="form-control required" name="evento" <?=($id>0 ? 'disabled' : '')?>>
											<option value=""><?=$translate->translate("sel_evento")?></option>
											<?
												$exeEv = executaSQL("SELECT * FROM evento ORDER BY dt_inicio DESC");
												if(nLinhas($exeEv)>0){
												
													while($evento = objetoPHP($exeEv)){ ?>
															
														<option value="<?=$evento->id?>" <?=($reg->id_evento == $evento->id) ? 'selected' : '' ?> > <?=$evento->titulo?></option>	
															
											<?		}
												}
											?>
											
										 </select>			
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="tipo"><?=$translate->translate("tipo")?></label>
										<select id="tipo" class="form-control required" name="tipo" <?=($id>0 ? 'disabled' : '')?>>
											<option value=""><?=$translate->translate("sel_tipo")?></option>
											<option value="2" <?=($reg->id_tipo == 2) ? 'selected' : '' ?> > <?=$translate->translate("tipo_menu_2")?></option>
											<option value="1" <?=($reg->id_tipo == 1) ? 'selected' : '' ?> > <?=$translate->translate("tipo_menu_1")?></option>
									    </select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="titulo"><?=$translate->translate("titulo")?></label>
										<input type="text" name="titulo" class="form-control required" id="titulo" value="<?=$reg->titulo?>">
									</div>
								</div>

							</div>
							
							<div class="row">

								<div class="col-md-6 hide" id="divURL">
									<div class="form-group">
										<label for="url"><?=$translate->translate("url")?></label>
										<input type="text" name="url" id="url" class="form-control required" value="<?=$reg->url?>">
									</div>
								</div>

								<div class="col-md-6 hide" id="divLinkExt">
									<div class="form-group">
										<label for="linkExterno"><?=$translate->translate("link_externo")?></label>
										<input type="text" name="linkExterno" id="linkExterno" class="form-control required" value="<?=$reg->url?>">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label><?=$translate->translate("ativo")?></label>									
										<div class="radio-list">
											<label for="ativo1" class="radio-inline"> 
												<input type="radio" class="required" name="ativo" id="ativo1" value="1" <?=($reg->ativo == 1)?'checked="checked"':''?> > <?=$translate->translate("sim")?>
											</label>
											<label for="ativo2" class="radio-inline"> 
												<input type="radio" class="required" name="ativo" id="ativo2" value="2" <?=($reg->ativo == 2)?'checked="checked"':''?> > <?=$translate->translate("nao")?>
											</label>
										</div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-12 hide" id="divConteudoPag">
									<div class="form-group">
										<label for="conteudo"><?=$translate->translate("conteudo_pagina")?></label>
										<textarea name="conteudo" id="conteudo" class="ckeditor form-control"><?=$reg->conteudo_pagina?></textarea>                            
									</div>
								</div>

							</div>
				   
					   
						    <div class="form-actions left">
	                            <button type="button" class="btn red" onclick="window.location='/adm/admin/menu/listar'"> <?=$translate->translate('bt_cancelar')?></button>
	                            <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
	                        </div>

	                    </div>

                    </form>

                    <div class="clear"></div>
                </div>
            </div>
			            
		</div>
	</div>
	
	<script>
       $(function(){

			verificaTipo();

			$("#form").validate();

			$("#url").bind("blur keyup", function() { formataUrlConteudoPersonalizado($(this)); });

			$("#tipo").change(function(){
				verificaTipo();
			});
			
		});
		
		verificaTipo = function(){
			var tipo = $("#tipo").val();

			if(tipo==2){
				$("#divURL, #divConteudoPag").removeClass("hide");
				$("#divLinkExt").addClass("hide");
			}else{
				$("#divURL, #divConteudoPag").addClass("hide");
				$("#divLinkExt").removeClass("hide");
			}
		}
	</script>
<?
	include_once("inc/generic/midias.php");
?>