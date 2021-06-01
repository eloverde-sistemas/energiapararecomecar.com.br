<?php

	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	ini_set('display_errors', false);

	ini_set("session.cookie_secure", 1);

	
	date_default_timezone_set('America/Sao_Paulo');
	
	session_start();
	
	include_once("../config.php");
	include_once("../conexao.php");
	include_once('../../i18nZF2.php');
	include_once("../funcoes.php");
	include_once("../bancofuncoes.php");

	$evento = executaSQL("SELECT * FROM evento WHERE id= '".$_SESSION['campanha']->id."' ");
	if( nLinhas($evento)>0 ){

		//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
		$evento = objetoPHP($evento);
		
	}
	
	$cpf = $_POST['cpf'];

	$cpfFormatado = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $cpf), 11);

	$parts  = executaSQLPadrao('participante', "cpf = '".$cpf."'");

//	Se o participante existe
	if( nLinhas($parts)>0 ){
		$part = objetoPHP($parts);

		$regs = executaSQLPadrao('participante_unidade', "id_participante = '".$part->id."' AND id_evento='".$evento->id."' ORDER BY id");
		
		$nRegs = nLinhas($regs);
		if( $nRegs>0 ){
			while($reg = objetoPHP($regs)){

				$dados['unidade'][]	= formataNumeroComZeros($reg->unidade,12);
				
			}
		}
		$dados['qtde'] 		= $nRegs;
		$dados['status'] 	= true;

	}else{
		$dados = array("status"=>false, "msg"=>$translate->translate('participante_nao_encontrado'));
	}

	echo json_encode( $dados );
?>