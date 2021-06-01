<?php

	$id = intval($_GET['id']);

	if( $id>0 ){

		$exeImg = executaSQLPadrao("midia", " id = '".$id."'");
		if(nLinhas($exeImg)>0){
		
			$regImg = objetoPHP($exeImg);			
			if( is_file("../".$regImg->caminho) ){
				unlink("../".$regImg->caminho);	
			}

			$excluir = excluirDados("midia", "id = '".$id."'");
		
			if( $excluir ){
				setarMensagem(array($translate->translate("msg_exclusao_sucesso")), "success");
			}else{
				setarMensagem(array($translate->translate("msg_exclusao_erro")), "error"); 		
			}
			
		}else{
			setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
		}
	}else{
		setarMensagem(array($translate->translate("msg_exclusao_erro")), "error");
	}
	header("Location: /adm/admin/midia/listar/");

?>