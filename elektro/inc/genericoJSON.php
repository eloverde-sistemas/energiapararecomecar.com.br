<?php

	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	ini_set('display_errors', false);

	ini_set("session.cookie_secure", 1);

	
	date_default_timezone_set('America/Sao_Paulo');
	
	session_start();
	
	include_once("config.php");
	include_once("conexao.php");
	include_once('../i18nZF2.php');
	include_once("funcoes.php");
	include_once("bancofuncoes.php");

	$acao = $_POST['acao'];
	if( isset($acao) ){
		switch($acao) {
			
			
			case'bannerClique':
				
				$id = intval($_POST['id']);

				$exe = executaSQL("SELECT * FROM banner WHERE id='".$id."'");
				if(nLinhas($exe)>0){
					$regBanner = objetoPHP($exe);

					$dados = array();
					$dados['id_banner']		= $id;
					$dados['ip']			= $_SERVER['REMOTE_ADDR'];
					
					$exe = inserirDados("banner_clique", $dados);

				}
					
				echo json_encode( array('status'=>true) );
			break;

			
			default:
				$message = 'Aзгo Indisponнvel';
				echo "{'status':'false', 'message':'$message'}";
		}
		
	}else{
		echo $translate->translate("msg_pagina_nao_encontrada");
	}
?>