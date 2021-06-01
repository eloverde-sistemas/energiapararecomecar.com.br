<?

	$id = intval($_GET['id']);

	$exe = executaSQL("SELECT * FROM perguntas_respostas WHERE id='".$id."'");
	if( nLinhas($exe)>0 ){

		$excluir = excluirDados("perguntas_respostas", "id = '".$id."'");

		if($excluir){
			setarMensagem(array($translate->translate("msg_exclusao_sucesso")), "success");
		}else {
			setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
		}
		
	}else{
		setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
	}
	
	header("Location: /adm/admin/faq/listar/");
?>