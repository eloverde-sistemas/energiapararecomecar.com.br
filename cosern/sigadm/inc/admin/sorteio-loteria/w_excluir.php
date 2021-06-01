<?
	$id = intval($_GET['id']);

	$exe = executaSQL("SELECT * FROM sorteio_loteria WHERE id='".$id."'");
	if( nLinhas($exe)>0 ){

		$reg = objetoPHP($exe);

		if( verificaEventoSorteio($reg->sorteio_data) ){
			setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
			header("Location: /adm/admin/sorteio-loteria/listar");
			die();
		}

		$excluir = excluirDados("sorteio_loteria", "id = '".$id."'");

		if( $excluir ){
			setarMensagem(array($translate->translate("msg_exclusao_sucesso")), "success");
		}else {
			setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
		}

		
	}else{
		setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
	}

	header("Location: /adm/admin/sorteio-loteria/listar/");
?>