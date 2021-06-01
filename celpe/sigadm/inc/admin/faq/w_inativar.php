<?
	$id = intval($_GET['id']);

	$exe = executaSQL("SELECT ativo FROM perguntas_respostas WHERE id='".$id."'");
	if( nLinhas($exe)>0 ){

		$reg = objetoPHP($exe);

		if($reg->ativo==1){
			
			$inativa = alterarDados("perguntas_respostas", array("ativo"=>2), "id = '".$id."'");	
			if($inativa){
				setarMensagem(array($translate->translate("msg_inativado_sucesso")), "success");
			}else {
				setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
			}

		}else{
			
			$inativa = alterarDados("perguntas_respostas", array("ativo"=>1), "id = '".$id."'");	
			if($inativa){
				setarMensagem(array($translate->translate("msg_ativado_sucesso")), "success");
			}else {
				setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
			}

		}
		
	}else{
		setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
	}
	
	header("Location: /adm/admin/faq/listar/");
?>