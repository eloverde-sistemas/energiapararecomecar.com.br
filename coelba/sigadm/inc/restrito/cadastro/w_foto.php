<?
	if($_FILES){
		
		$id			= $_SESSION["pessoaCorrenteIdRestrito"];
		
		if($mesmoIrmao){
		
			if($_POST['excluir_foto']){
				$fotoAnterior = objetoPHP(executaSQL("SELECT foto FROM pessoa WHERE id='".$id."'"))->foto;
				$fotoThumbAnterior = objetoPHP(executaSQL("SELECT thumb FROM pessoa WHERE id='".$id."'"))->thumb;
				if(is_file('../'.$fotoAnterior) && $fotoAnterior!='images/user.gif' && $fotoAnterior!='images/user-female.png' ){
					unlink('../'.$fotoAnterior);
					unlink('../'.$fotoThumbAnterior);					
				}
				alterarDados("pessoa", array("foto"=>"images/user.gif", "thumb"=>""), "id='".$id."'");
			}
			
			if($_FILES["foto"]['name'] != ""){				
				
				if(is_file('../'.$pessoa->foto) && $pessoa->foto!='images/user.gif' && $pessoa->foto!='images/user-female.png' ){
					unlink('../'.$pessoa->foto);
					unlink('../'.$pessoa->thumb);
				}
				
				$extensao = strtolower( strrchr($_FILES["foto"]['name'], ".") );
				$imagem_name = strtolower($id.$extensao);
				
				$caminho = criaDiretorios(array("uploads", "potencia", "pessoa", "foto"));
				$arquivo = $caminho.$imagem_name;
				
				$caminhoThumb = criaDiretorios(array("uploads", "potencia", "pessoa", "thumb"));
				$arquivoThumb = $caminhoThumb.$imagem_name;
				
				$upload = move_uploaded_file( $_FILES['foto']['tmp_name'], '../'.$arquivo );
				
				// Redimensiona a imagem e salva
				require('lib/WideImage/WideImage.php');
				wideImage::load( '../'.$arquivo )->resize(800, 800, 'inside', 'down')->saveToFile('../'.$arquivo);
				wideImage::load( '../'.$arquivo )->resize(100, 130, 'inside', 'down')->saveToFile('../'.$arquivoThumb);
				
				$exe = alterarDados("pessoa", array("foto" 			=> $arquivo,
													"thumb" 		=> $arquivoThumb), "id='".$id."'");
				
				if($exe){
					setarMensagem(array($translate->translate('foto_atualizada')), "success");
				}else {
					setarMensagem(array($translate->translate('foto_error')), "danger");
				}
			}
		}
		header('location: /adm/restrito/cadastro/dados');
		die();
	}
?>	
    
	<form enctype="multipart/form-data" id="formFoto" action="/adm/restrito/cadastro/dados" method="post">
		<input type="hidden" name="acao_irmao" id="acao_irmao" value="foto" >
        
        <div class="form-body">
                   
            <div class="row">                            	
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">
					<div class="form-group" align="center">
						<?  if(is_file('../'.$pessoa->foto)){ $temFoto = true; ?>
                                <img src="<?="../".$pessoa->foto?>" alt="" border="1" style="max-width: 80%;" />
                        <? } else{ ?>
                                <img src="images/user.gif" title="<?=$translate->translate('visualizar')?>" alt="<?=$translate->translate('visualizar')?>" border="n">
                        <? } ?>
						
                	</div>
                </div>
                <div class="col-md-4">&nbsp;</div>
            </div>		
		
		<?	if($mesmoIrmao){?>
        
                <div class="row">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-4">
                        <div class="form-group" align="center">
                            <br />
                            <strong style="color:#FF0000"><?=$translate->translate("foto_atualizar")?></strong>
                            
                            <? 	if(is_file('../'.$pessoa->foto) && $pessoa->foto!='images/user.gif'){?>
                                    <br /><br />
                                    <label for="excluir_foto" class="item"> <input type="checkbox" value="1" name="excluir_foto" id="excluir_foto" /> <?=$translate->translate("excluir_foto_atual")?></label>
    
                            <? 	}?>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
    
                <div class="row">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-4">
                        <div class="form-group" align="center">
                            <strong><?=$translate->translate("nova_foto")?></strong>: <input name="foto" type="file" class="campo"  id="foto" />
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
    
                <div class="row">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-4">
                        <div class="form-group" align="center">
                            <button type="button" class="btn default" onclick="window.location='/restrito/cadastro/dados'"><?=$translate->translate("bt_cancelar")?></button>
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate("bt_salvar")?></button>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
                
		<?	}?>
        
        </div>
    	<div class="clear"></div>
    </form>
    
    <script>
		jQuery(document).ready(function() {
			$("#formFoto").validate({
				rules: {
					foto: {
						required: {
							depends: function(element){								
							  	return $("#excluir_foto").is(":not(:checked)")
							}
						}
				    }					
				}
			});
		});
	</script>