<?
	$id = intval($_GET['id']);
	
	$exe = executaSQL("SELECT 1 FROM loja WHERE id='".$id."'");
	if(nLinhas($exe)>0){
		
		$exc = excluirDados("loja", "id='".$id."'");
		excluirDados("loja_contato", "id_loja='".$id."'");
		excluirDados("loja_telefone", "id_loja='".$id."'");

		if($exc){
			setarMensagem(array($translate->translate('msg_exclusao_sucesso')), "success"); 	
		}else{
			setarMensagem(array($translate->translate('msg_exclusao_erro')), "error");
		}
		
	}else{
		setarMensagem(array($translate->translate('msg_sem_registro')), "error");
	}
	header('location: /adm/admin/lojas/listar');
?>