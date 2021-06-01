<?
	$id = $_GET['id'];
	
	$exe = executaSQL("SELECT * FROM pessoa WHERE id='".$id."'  AND id_tipo=2");
	if(nLinhas($exe)>0){
		
		$reg = objetoPHP($exe);
		
		$exc = alterarDados("pessoa", array("id_situacao"=>2), "id='".$id."'");
		if($exc){
			setarMensagem(array($translate->translate('msg_exclusao_sucesso')), "success"); 	
		}else{
			setarMensagem(array($translate->translate('msg_exclusao_erro')), "error");
		}
		
	}else{
		setarMensagem(array($translate->translate('msg_sem_registro')), "error");
	}
	header('location: /adm/admin/usuarios/listar');
?>