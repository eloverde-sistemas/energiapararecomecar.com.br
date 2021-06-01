<?
	$id = intval($_GET['id']);
	
	$exe = executaSQL("SELECT eg.*, es.id_evento 
						FROM evento_ganhador eg, evento_sorteio es 
						WHERE eg.id='".$id."'
						AND eg.id_sorteio=es.id");
	if(nLinhas($exe)>0){
		
		$obj = objetoPHP($exe);

		if(alterarDados("evento_ganhador", array('id_situacao'=>2, 'avaliacao_pessoa'=>$_SESSION['usuarioId'], 'avaliacao_data'=>date('YmdHis')), "id='".$id."'")){
			setarMensagem(array($translate->translate('msg_ganhador_aprovado_sucesso')), "success"); 	
		}else{
			setarMensagem(array($translate->translate('msg_ganhador_aprovado_erro')), "error");
		}
	
		header('location: /adm/admin/eventos/ganhadores/'.$obj->id_evento);
		exit();
	}else{
		setarMensagem(array($translate->translate('msg_sem_registro')), "error");
		header('location: /adm/admin/eventos/listar');
		exit();
	}	
?>