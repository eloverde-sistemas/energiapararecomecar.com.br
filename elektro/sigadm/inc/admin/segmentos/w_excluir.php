<?
	$id = intval($_GET['id']);
	
	if($id>0){		

		if(!verificaSegmentoLojista($id)){
			
			if(excluirDados("loja_segmento", "id='".$id."'")){
				setarMensagem(array($translate->translate('msg_exclusao_sucesso')), "success"); 	
			}else{
				setarMensagem(array($translate->translate('msg_exclusao_erro')), "danger");
			}
			
		}else{
			setarMensagem(array($translate->translate('msg_segmento_lojista_relacionada')), "danger");
		}
	}else{
		setarMensagem(array($translate->translate('msg_exclusao_erro')), "danger");
	}
	header("Location: /adm/admin/segmentos/listar");
?>