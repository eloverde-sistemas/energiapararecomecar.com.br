<?
	$id = $_GET['id'];
	
	$exe = executaSQL("SELECT * FROM noticia WHERE id='".$id."'");
	if(nLinhas($exe)>0){
		
		$reg = objetoPHP($exe);
		
		if(is_file($reg->image_dir)){
			unlink( '../'.$reg->image_dir );
		}
		if(is_file($reg->image_dir_thumb)){
			unlink( '../'.$reg->image_dir_thumb );
		}
		if(is_file($reg->file_dir)){
			unlink( '../'.$reg->file_dir );
		}
		
		$exc 	= excluirDados("noticia", "id='".$id."'");
		if($exc){
			setarMensagem(array($translate->translate('msg_exclusao_sucesso')), "success"); 	
		}else{
			setarMensagem(array($translate->translate('msg_exclusao_erro')), "error");
		}
		
	}else{
		setarMensagem(array($translate->translate('msg_sem_registro')), "error");
	}
	header('location: /adm/admin/noticias/listar');
?>