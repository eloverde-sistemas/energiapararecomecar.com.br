<?php
	
	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);
	
	ob_start();

	session_start();
	
	ini_set('display_errors', false);

	ini_set("session.cookie_secure", 1);

	
	date_default_timezone_set('America/Sao_Paulo');
	
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

	$parts  = executaSQLPadrao('participante', " REPLACE(REPLACE(cpf, '.', ''), '-', '')=REPLACE(REPLACE('".$cpf."', '.', ''), '-', '') ");


	if( nLinhas($parts)>0 ){
		$part = objetoPHP($parts);

		$cupons = executaSQL("SELECT pc.*, pu.unidade FROM participante_unidade pu, participante_cupom pc WHERE pc.id_unidade=pu.id AND pu.id_participante='".$part->id."' AND pu.id_evento='".$evento->id."' ORDER BY pu.unidade");
		
		$nCupons = nLinhas($cupons);
		if( $nCupons>0 ){
			while($cupom = objetoPHP($cupons)){

				$dados['cupom']['unidade'][]		= $cupom->unidade;
				$dados['cupom']['participacao'][]	= converteMJSON(consultaTipoPeloId($cupom->id_tipo_participacao)->valor);
				$dados['cupom']['elementos'][]		= getElementosByCampanhaTipoCupom($evento->id_tipo_campanha, $cupom->id);
				
				
			}
		}
		$dados['qtde'] 		= $nCupons;
		$dados['status'] 	= true;
	}else{
		$dados = array("status"=>false, "msg"=>converteMJSON($translate->translate('participante_nao_encontrado') ));
	}

	//$dados = array("status"=>true);
	
	echo json_encode( $dados );
?>