<?

	$id = intval($_GET['id']);

	$exe = executaSQL("SELECT * FROM menu WHERE id='".$id."' AND id_menu_padrao=0");
	if( nLinhas($exe)>0 ){

		$excluir = excluirDados("menu", "id = '".$id."'");

		if($excluir){
			setarMensagem(array($translate->translate("msg_exclusao_sucesso")), "success");
		}else {
			setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
		}
		
	}else{
		setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
	}
	
	header("Location: /adm/admin/menu/listar/");
?>