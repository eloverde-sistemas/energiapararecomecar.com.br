<?php 
	if($_POST){
			
		require('../lib/WideImage/WideImage.php');
		
		$id = ( $_POST['id'] > 0 ) ? intval($_POST['id']):'';
		$noticia = objetoPHP(executaSQLPadrao("noticia", "id = '".$id."'"));
		
		if($_POST['evento'] == '' || $_POST['destaque'] == '' || $_POST['titulo'] == '' ){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
			header("Location: /adm/admin/noticias/editar/".$id);
			die();
		}else{		
			
			$dados = array();
			$dados['id_evento'] 	= intval($_POST['evento']);
			$dados['tipo'] 			= intval($_POST['destaque']);
			$dados['titulo']		= trim($_POST['titulo']);
			$dados['link_url'] 		= formataUrl($_POST['url_campo']);
			$dados['link_texto'] 	= trim($_POST['txt_url']);
			$dados['validade']		= converte_data($_POST['validade']);
			$dados['noticia'] 		= trim($_POST['noticia']);
			//$dados['restrito'] 		= $_POST['restrito'];

			$excluir_arquivo		 = $_POST['excluirArquivo'];
			$excluir_imagem 		 = $_POST['excluirImagem'];
			
			if($id>0){
				$exeNot = alterarDados('noticia', $dados, 'id = "'.$id.'"');				
			}else{
				
				$dados['data']			= date('YmdHis');
				$id = $dados['id'] = proximoId('noticia');				
				$exeNot = inserirDados('noticia', $dados);
			}
			
			if($exeNot){
				
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
			
				if( $excluir_arquivo == true ){
					$arquivo_anterior = objetoPHP(executaSQLPadrao("noticia", " id = '".$id."'"))->file_dir;							
					if( is_file( '../'.$arquivo_anterior ) ){
						unlink( '../'.$arquivo_anterior );
						$arquivo['file_dir'] = NULL;
						alterarDados('noticia', $arquivo, 'id = "'.$id.'"');
					}
					if( $_FILES['file_dir']['tmp_name'] == '' ){
						$dadosAlt['file_dir'] = '';
					}
				}
					
				if( $_FILES['file_dir']['tmp_name'] != '' ){
					
					if( in_array( end(explode(".",$_FILES['file_dir']['name'])) , array("doc","docx","txt","pdf","xls","xlsx","png","gif","jpg","jpeg") ) ){
						
						$dadosAlt['file_dir'] = criaDiretorios( array("uploads", "noticias", "arquivo") ).$id.".".end(explode(".",$_FILES['file_dir']['name']));
						
						$upload = move_uploaded_file( $_FILES['file_dir']['tmp_name'], '../'.$dadosAlt['file_dir']);
						
					}else{
						setarMensagem(array($translate->translate("msg_formato_arquivo_invalido")), "error");
						header("Location: /adm/admin/noticias/editar/".$id);
						die();
					}
				}
				
				if( $excluir_imagem == true ){
					$arquivo_anterior = objetoPHP(executaSQLPadrao("noticia", " id = '".$id."'"))->image_dir;
					$arquivo_anterior_thumb = objetoPHP(executaSQLPadrao("noticia", " id = '".$id."'"))->image_dir_thumb;							
					
					if( is_file( '../'.$arquivo_anterior ) ){
						unlink( '../'.$arquivo_anterior );
						//echo $arquivo_anterior;
						$image['image_dir'] = '';
						alterarDados('noticia', $image, 'id = "'.$id.'"');							
					}
					if( $_FILES['image_dir']['tmp_name'] == '' ){
						$dadosAlt['image_dir'] = '';
						
						$dadosAlt['image_dir_thumb'] = '';
					}					
					if( is_file( '../'.$arquivo_anterior_thumb ) ){
						unlink( '../'.$arquivo_anterior_thumb );
						//echo $arquivo_anterior_thumb;
						$image_thumb['image_dir_thumb'] = '';
						alterarDados('noticia', $image_thumb, 'id = "'.$id.'"');							
					}					
				}
							
				if( $_FILES['image_dir']['tmp_name'] != '' ){	
					if( in_array( end(explode(".",$_FILES['image_dir']['name'])) , array("jpg","jpeg","png","gif") ) ){
						
						$dadosAlt['image_dir'] = criaDiretorios( array("uploads", "noticias", "foto") ).$id.".".end(explode(".",$_FILES['image_dir']['name']));
						
						$upload = move_uploaded_file( $_FILES['image_dir']['tmp_name'], '../'.$dadosAlt['image_dir'] );
						
						$img = WideImage::load('../'.$dadosAlt['image_dir'])-> resize(800, 600, 'outside', 'down')-> saveToFile('../'.$dadosAlt['image_dir']);
						
					
						//IMAGEM THUMB
						$dadosAlt['image_dir_thumb'] = criaDiretorios( array("uploads", "noticias", "foto-thumb") ).$id.".".end(explode(".",$dadosAlt['image_dir']));	
						
						$upload = copy( '../'.$dadosAlt['image_dir'], '../'.$dadosAlt['image_dir_thumb'] );
						
						$imgThumb = WideImage::load('../'.$dadosAlt['image_dir_thumb'])->resize(140, 140, 'outside', 'down')->crop('', '', 140, 140)->saveToFile('../'.$dadosAlt['image_dir_thumb']);
					
						
					}else{
						setarMensagem(array($translate->translate("msg_formatos_permitidos")), "error");
						header("Location: /adm/admin/noticias/editar/".$id);
						die();
					}
				}
			
				
				if( $excluir_imagem_destaque== true ){
					$dadosAlt['img_grande'] = 0;
					
					$imgDest= objetoPHP(executaSQLPadrao("noticia", " id = '".$id."'"));
				
					if( is_file( '../'.$imgDest->image_dir_thumb ) ){
						unlink( '../'.$imgDest->image_dir_thumb);

						$image_thumb['image_dir_thumb'] = '';
						alterarDados('noticia', $image_thumb, 'id = "'.$id.'"');
					}
					
					if( is_file( '../'.$imgDest->image_dir ) ){
						unlink( '../'.$imgDest->image_dir);

						$image['image_dir'] = '';
						alterarDados('noticia', $image, 'id = "'.$id.'"');
					}
				}
				

				if(count($dadosAlt)>0){
					alterarDados("noticia", $dadosAlt, "id='".$id."'");
				}
				header("Location: /adm/admin/noticias/listar/");

			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error");
				header("Location: /adm/admin/noticias/editar/".$id);
			}

		}
		
		exit;
	}
	
	$id = intval($_GET['id']);
	
	if( $id > 0 ){
		$exeNoticia = executaSQLPadrao("noticia", "id = '".$id."'");
		if(!nLinhas($exeNoticia)>0){
			header("Location: /adm/admin/noticias/listar");
		}else{
			$noticia = objetoPHP($exeNoticia);
		}
	}
?>
	<h3 class="page-title">
        <?=$translate->translate('tm_noticias')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="index.html">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/noticias/listar"><?=$translate->translate('tm_noticias')?></a>
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
								echo traducaoParams($translate->translate('_noticias'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form id="form" action="/adm/admin/noticias/editar" method="post" enctype="multipart/form-data" class="horizontal-form">
                    	<input type="hidden" name="id" value="<?=$id?>">
                        
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
														
													<option value="<?=$evento->id?>" <?=($noticia->id_evento == $evento->id) ? 'selected' : '' ?> > <?=$evento->titulo?></option>	
														
										<?		}
											}
										?>
										 </select>
									</div>
								</div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?=$translate->translate("tipo")?></label>
                                        <div class="radio-list">
                                            <label class="radio-inline" for="destaque1">
                                            	<input type="radio"  name="destaque" id="destaque1" class="required" value="1"  <?=($noticia->tipo == 1)?'checked="checked"':''?>> <?=$translate->translate("normal")?>
                                            </label>
                                            <label class="radio-inline" for="destaque2">
                                            	<input type="radio"  name="destaque" id="destaque2" class="required" value="2"  <?=($noticia->tipo == 2)?'checked="checked"':''?>> <?=$translate->translate("destaque")?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="validade"><?=$translate->translate("validade")?></label>
                                        <input type="text" name="validade" id="validade" class="form-control date-picker data required" value="<?=converte_data($noticia->validade)?>">
                                    </div>
                                </div>                                
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="titulo"><?=$translate->translate('titulo')?></label>
                                        <input type="text" id="titulo" name="titulo" class="form-control required" value="<?=$noticia->titulo?>">
                                    </div>
                                </div>
                                
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="url_campo"><?=$translate->translate('link_url')?></label>
                                        <input type="text" name="url_campo" id="url_campo" class="form-control" value="<?=$noticia->link_url?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label url" for="txt_url"><?=$translate->translate("texto_link_url")?></label>
                                        <input type="text" name="txt_url" id="txt_url" class="form-control" value="<?=$noticia->link_texto?>">
                                    </div>
                                </div>
                                
                            </div>                           
                            
                            
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="file_dir"><?=$translate->translate("arquivo")?></label>
                                        <input type="file" name="file_dir" id="file_dir" >
                                    </div>
								<?	if( is_file('../'.$noticia->file_dir)){	?>
                                        <a href="/baixarDocumento.php?id=<?=$id?>&tipo=2" class="icon-btn jumper-20" target="_blank">
                                            <i class="fa fa-download"></i>
                                            <div> <?=$translate->translate('baixar')?> </div>
                                        </a>
									<label class="radio-inline" for="excluirArquivo" style="margin-bottom: 25px;">
										<input type="checkbox"  name="excluirArquivo" id="excluirArquivo" value="1" class="excluir"> <?=$translate->translate("excluir_arquivo_atual")?>
									</label>
                                <?	}	?>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="image_dir"><?=$translate->translate("imagem")?></label>
                                        <span id="image_dir-2" class="image_dir" style="color:#C00; font-size:11px; <?=($noticia->tipo != 2)?'display:none;':''?>">
                                        <? 	
                                            echo traducaoParams($translate->translate("tamanho_recomendado"), array("700x250"));
                                        ?>
                                        </span>
                                        <span id="image_dir-1" class="image_dir" style="color:#C00; font-size:11px; <?=($noticia->tipo != 1)?'display:none;':''?>">
                                            <? 	
                                                echo traducaoParams($translate->translate("tamanho_recomendado"), array("140x140"));
                                            ?>
                                        </span>
                                        <input type="file" name="image_dir"  id="image_dir" >
                                    </div>
                                    						
                                <?	if(is_file('../'.$noticia->image_dir_thumb)){	?>
                                        <a href="<?='/'.$noticia->image_dir?>" class="jumper-20 fancybox-button" data-rel="fancybox-button">
                                            <img src="<?='/'.$noticia->image_dir_thumb?>" height="100">
                                        </a>
                                        <label class="radio-inline" for="excluirImagem">
                                            <input type="checkbox"  name="excluirImagem" id="excluirImagem" value="1" class="excluir"> <?=$translate->translate("excluir_imagem")?>
										</label>
                                <?	}	?>
                                	
                                </div>
                                
                            </div>
							<div class="row">
                                <div class="col-md-12">
                                    <div class="row"><label class="control-label col-md-2"><?=$translate->translate('noticia')?></label></div>
									<div class="row"><textarea name="noticia" id="noticia" class="ckeditor form-control" rows="6"><?=$noticia->noticia?></textarea></div>
								</div>
                            	
                            </div>
                            
                            
                        </div>
                        <div class="form-actions left">
                            <button type="button" class="btn red" onclick="window.location='/adm/admin/noticias/listar'"> <?=$translate->translate('bt_cancelar')?></button>
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
			
			$('input[type=radio][name=destaque]').change(function(){
				var valor = $('input[type=radio][name=destaque]:checked').val();
				$('.image_dir').hide();
				
				if( valor==1 || valor==2 ){
					$('#image_dir-'+valor).show();
				}
			});
			
		});
	</script>

<?
	include_once("inc/generic/midias.php");
?>