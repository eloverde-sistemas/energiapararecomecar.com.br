<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	session_start();
	
	include_once('../../config.php');
	include_once('../../conexao.php');
	include_once('../../funcoes.php');
	include_once('../../bancofuncoes.php');
	
	$ext		= end(explode('.', $_FILES["files"]["name"][0]));
	$id 		= proximoId("midia");
	
	$caminho = criaDiretorios(array("uploads", "potencia", "midia")).$id.'.'.$ext;
	
	$titulo		= reset(explode('.', $_FILES["files"]["name"][0]));
	$descricao	= '';
	
	if( move_uploaded_file($_FILES["files"]["tmp_name"][0], '../../../../'.$caminho) ){
		
		$dados = array();
		$dados['id'] 			= $id;
		$dados['titulo'] 		= trim($titulo);
		$dados['formato'] 		= $ext;
		$dados['data_cadastro'] = date("Ymd");
		inserirDados("midia", $dados);
		
		$retorno = array("status"=>true);
		
	}else{
		$retorno = array("status"=>false);
	}
	
	echo json_encode($retorno);
?>