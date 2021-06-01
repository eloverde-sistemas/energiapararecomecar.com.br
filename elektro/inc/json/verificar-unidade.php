<?
	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);
	
	ob_start();
	session_start();
	
	include_once("../config.php");
	include_once("../conexao.php");
	include_once('../../i18nZF2.php');
	include_once("../funcoes.php");
	include_once("../bancofuncoes.php");

	$unidade = $_POST['unidade'];

	$cpf = $_POST['cpf'];

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

	$regs = executaSQL("SELECT * FROM base_cliente WHERE unidade = '".$unidade."'");
	if( nLinhas($regs)>0 ){
		
		$reg = objetoPHP($regs);

		if( $reg->cpf==$cpfFormatado ){
			$dados = array("status"=>true);
		}else{
			if( in_array( formataNumeroComZeros($reg->cpf,11), $cpfMascaras) ){
				$dados = array("status"=>true);
			}else{
				$dados = array("status"=>false, "msg"=>converteMJSON($translate->translate('unidade_nao_vinculada')));
			}
		}

	}else{
		$dados = array("status"=>false, "msg"=>converteMJSON($translate->translate('unidade_nao_cadastrada')));
	}
	
	echo json_encode( $dados );
?>