<?
	ob_start();
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