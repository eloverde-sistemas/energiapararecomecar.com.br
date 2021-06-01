<?php

	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	ini_set('display_errors', false);

	ini_set("session.cookie_secure", 1);

	
	date_default_timezone_set('America/Sao_Paulo');
	
	session_start();

	include_once("inc/config.php");
	include_once("inc/conexao.php");
//	include_once('inc/i18nZF2.php');
	include_once('i18nZF2.php');
	include_once("inc/funcoes.php");
	include_once("inc/bancofuncoes.php");
	include_once('inc/sessao.php');
	
	if($active){
		include('inc/'.$_GET['page']);
	}
?>