<?
	$id = intval($_GET['id']);
	
	$exe = executaSQL("SELECT * FROM perfil WHERE id='".$id."'");
	if(nLinhas($exe)>0){
		
		$reg = objetoPHP($exe);
		//EXCLUI AS TAREFAS DO PERFIL
		excluirDados("perfil_tarefa", "id_perfil='".$id."'");
		//EXCLUI A RELAวรO DO PERFIL COM A PESSOA
		excluirDados("pessoa_perfil", "id_perfil='".$id."'");
		$exc = excluirDados("perfil", "id='".$id."'");
		if($exc){
			setarMensagem(array($translate->translate('msg_exclusao_sucesso')), "success"); 	
		}else{
			setarMensagem(array($translate->translate('msg_exclusao_erro')), "error");
		}
		
	}else{
		setarMensagem(array($translate->translate('msg_sem_registro')), "error");
	}
	header('location: /adm/admin/perfis/listar');
?>