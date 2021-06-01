<?
	//Valida a Sessão do Usuário
/*	if (!isset($_SESSION['id_campanha'])){		
		$exe = executaSQL("SELECT id FROM evento ORDER BY dt_termino DESC LIMIT 1");
		if(nLinhas($exe)>0){
			$_SESSION['id_campanha'] = objetoPHP($exe)->id;
		}
	}
*/
	$campanha = reset($_GET);

	if( $campanha!='' ){
    	$urlEvento = $campanha;

    	$eventos = executaSQL("SELECT * FROM evento WHERE url_padrao LIKE '".$urlEvento."' AND id_situacao IN (1, 99) ");

    	if( nLinhas($eventos)>0 ){
			$evento = objetoPHP($eventos);

			if( $_SESSION['campanha']!=$evento->id ){
				$_SESSION['campanha'] = $evento;
			}

		}else{
			$_SESSION['campanha'] = 0;
			header("Location: /");
		}
	}
?>