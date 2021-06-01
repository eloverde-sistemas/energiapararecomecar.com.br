<?
	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 3600);

	ob_start();
	
	ini_set('display_errors', false);
	
	date_default_timezone_set('America/Sao_Paulo');
	
	session_start();

	include_once('inc/config.php');
	include_once('inc/conexao.php');
	
	include_once('i18nZF2.php');
	
	include_once('inc/funcoes.php');
	include_once('inc/bancofuncoes.php');
	
	include_once('inc/sessao.php');
	
	if($active)
		include_once('inc/index-content.php');
	else
		include_once('inc/index-login.php');
?>