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

	$cpf = $_POST['cpf'];

	$regs = executaSQL("SELECT * FROM participante WHERE cpf = '".$cpf."'");
	if( nLinhas($regs)>0 ){
		$reg = objetoPHP($regs);

		$campos = executaSQL("SELECT * FROM evento_participante WHERE id_participante = '".$reg->id."' AND id_evento = '".$_SESSION['campanha']->id."'");
		
		if( nLinhas($campos)>0 ){
			$participacao = objetoPHP($campos);
			$dados = array("status"=>true, "nome"=>converteMJSON($reg->nome), "dt_hr_participacao"=>converteDataHora($participacao->data_participacao), 'ehparticipante'=>true);
		}else{
			
			$dadosPart = array();

			$dadosPart['nome'] 		= converteMJSON($reg->nome);
			$dadosPart['email'] 	= $reg->email;

			$dadosPart['nome_mae'] 	= converteMJSON($reg->nome_mae);

			$dadosPart['cpf'] 		= $reg->cpf;

			$dadosPart['telefone']	= $reg->telefone;
			$dadosPart['celular'] 	= $reg->celular;

			$dadosPart['dt_nascimento'] 	= converte_data($reg->dt_nascimento);

			$dados = array("status"=>true, 'dados'=>$dadosPart);
		}

	}else{
		$dados = array("status"=>false);
	}
	
	echo json_encode( $dados );
?>