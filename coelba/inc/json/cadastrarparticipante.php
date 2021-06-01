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

	$data 	= $_POST;

	$cpf 	= trim($data['cpf']);
	$unidade = intval($data['unidade']);

	$idCampanha = ($_SESSION['campanha']->id>0)?$_SESSION['campanha']->id :1;

	$unidadeFormatada = formataNumeroComZeros($unidade,12);
	$cpfFormatado = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $cpf), 11);

	$cpfMascaras = array(
						'00000000000',
						'11111111111',
						'22222222222',
						'33333333333',
						'44444444444',
						'55555555555',
						'66666666666',
						'77777777777',
						'88888888888',
						'99999999999'
					);

	if($cpf!='' && $unidade!='' && !in_array( formataNumeroComZeros($cpf,11), $cpfMascaras)){

		$dados = array(	'dt_nascimento' 		=> converte_data($data['dt_nascimento']),
						
						'telefone' 				=> trim($data['telefone']),
						'celular' 				=> trim($data['celular']),
						'email' 				=> trim($data['email']),

						'nome_mae' 				=> converteBancoJSON(trim($data['nome_mae']))
					);

		$regs = executaSQL("SELECT id, nome FROM participante WHERE REPLACE(REPLACE(cpf, '.', ''), '-', '')=REPLACE(REPLACE('".$cpf."', '.', ''), '-', '') ");
		if( nLinhas($regs)>0 ){
			$reg = objetoPHP($regs);
			$idParticipante 	= $reg->id;
			$nomeParticipante	= converteBancoJSON($reg->nome);

			$exe = alterarDados("participante", $dados, "id = '".$reg->id."'");
			
		}else{
			$dados['id'] 	= $idParticipante = proximoId('participante');
			$dados['cpf'] 	= $cpf;
			$dados['nome'] 	= $nomeParticipante = converteBancoJSON(trim($data['nome_completo']));
			
			$exe = inserirDados("participante", $dados);
		}

		if($exe){

				//Vincula a Unidade do Cadastro ao CPF
				$exeUnidadePrincipal = executaSQL("SELECT * FROM base_cliente WHERE unidade = '".$unidadeFormatada."' AND id_situacao='1' ");
				if( nLinhas($exeUnidadePrincipal)>0 ){
					$regUni = objetoPHP($exeUnidadePrincipal);
					if( in_array( formataNumeroComZeros($regUni->cpf,11), $cpfMascaras) ){
						inserirDados("participante_unidade", array(	"id_evento" 				=> $idCampanha,
																	"id_participante" 			=> $idParticipante,
																	"unidade" 					=> $unidadeFormatada,
																	"id_situacao"				=> 2 //Não vinculado ao CPF
																	));
					}
				}

				//Verifica se tem outras Unidades com o CPF informado e vinculado ao Participante
				$exeOutrasUnidades = executaSQL("SELECT * FROM base_cliente WHERE cpf = '".$cpfFormatado."' AND id_situacao='1' ");
				if( nLinhas($exeOutrasUnidades)>0 ){
					while($unidadeOutra = objetoPHP($exeOutrasUnidades)){
						inserirDados("participante_unidade", array(	"id_evento" 				=> $idCampanha,
																	"id_participante" 			=> $idParticipante,
																	"unidade" 					=> formataNumeroComZeros($unidadeOutra->unidade,12),
																	"id_situacao"				=> 1
																	));
					}
				}

			
				$dadosPart = array(	"id_evento" 				=> $idCampanha,
									"id_participante" 			=> $idParticipante,
									"matricula"					=> substr(trim($data['matricula']), 0, 20),
									"ip"						=> $_SERVER["REMOTE_ADDR"]
								);
				
				inserirDados("evento_participante", $dadosPart);

			echo json_encode( array("status"=>true, "nome"=>converteMJSON($nomeParticipante), "msg"=> converteMJSON($translate->translate('msg_sucesso_cadastrar_participante')) ) );
				
		}else{
			echo json_encode( array("status"=>false, "msg"=> converteMJSON($translate->translate('msg_erro_cadastrar_participante')) ) );
		}
	}else{
		echo json_encode( array("status"=>false, "msg"=> converteMJSON($translate->translate('msg_erro_informe_corretamente')) ) );
	}
?>