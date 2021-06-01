<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");	
	
	
	
	$ext = end(explode('.', $_FILES["files"]["name"]));
	$id  = proximoId("midia");
	
	$targetDir = criaDiretorios(array("uploads", "potencia", "midia"));
	
	$filePath = "../../".$targetDir.$id.'.'.$ext;
	
	$dados['titulo'] = $_FILES["files"]["name"];
	inserirDados("midia", $dados);	
	
	if( move_uploaded_file($_FILES["files"]["tmp_name"], $filePath) ){
	
		$dados = array();
		$dados['id'] 			= $id;
		$dados['titulo'] 		= trim($_POST['titulo']);
		$dados['descricao'] 	= trim($_POST['descricao']);
		$dados['formato'] 		= $ext;
		$dados['data_cadastro'] = date("Ymd");
		inserirDados("midia", $dados);	
		
		die('{"OK": 1}');
	}else{
		die('{"OK": 0}');
	}
?>