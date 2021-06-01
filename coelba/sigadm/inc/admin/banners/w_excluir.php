<?

	$id = intval($_GET['id']);

	if( $id ){

		$foto = objetoPHP(executaSQLPadrao("banner", " id = '".$id."'"))->image_dir;
		
		if( $foto != "" && file_exists( $foto ) ){
			unlink( $foto );
		}

		$excluir = excluirDados("banner_clique", "id_banner = '".$id."'");
		$excluir = excluirDados("banner", "id = '".$id."'");

		if( $excluir ){
			setarMensagem(array($translate->translate("msg_exclusao_sucesso")), "success");
		}else {
			setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
		}

		header("Location: /adm/admin/banners/listar/");
	}
?>