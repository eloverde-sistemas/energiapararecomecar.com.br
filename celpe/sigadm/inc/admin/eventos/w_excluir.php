<?
	$id = intval($_GET['id']);
	$exe = executaSQL("SELECT 1 FROM evento WHERE id='".$id."'");
	if(nLinhas($exe)>0){
	/*
		excluirDados("banner", "id_evento='".$id."'");
		
		excluirDados("elemento_sorteavel", "id_evento='".$id."'");
		
		excluirDados("evento_cupom", "id_evento='".$id."'");
		
		excluirDados("evento_ganhador", "id_evento='".$id."'");
		
		excluirDados("evento_loja", "id_evento='".$id."'");
		
		excluirDados("evento_participante", "id_evento='".$id."'");
		
		excluirDados("evento_premio", "id_evento='".$id."'");
		
		excluirDados("evento_sorteio", "id_evento='".$id."'");
		
		excluirDados("menu", "id_evento='".$id."'");
		
		excluirDados("midia", "id_evento='".$id."'");
		
		excluirDados("noticia", "id_evento='".$id."'");
		
		excluirDados("pessoa_cupom", "id_evento='".$id."'");
		
		excluirDados("pessoa_cupom_elemento", "id_evento='".$id."'");
		
		excluirDados("sorteio_regulamento", "id_evento='".$id."'");
		
		
		if(excluirDados("evento", "id='".$id."'")){
			setarMensagem(array($translate->translate('msg_exclusao_sucesso')), "success"); 	
		}else{
			setarMensagem(array($translate->translate('msg_exclusao_erro')), "error");
		}
	*/	
		alterarDados("evento", array('id_situacao'=>3), "id='".$id."'");
	}else{
		setarMensagem(array($translate->translate('msg_sem_registro')), "error");
	}
	header('location: /adm/admin/eventos/listar');
?>