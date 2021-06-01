<?
	date_default_timezone_set('America/Sao_Paulo');

	//Valida a Sessão do Usuário
	if (!isset($_SESSION['sessaoid'])){
		$active = false;
	}else{
		$sessao_id	= $_SESSION['sessaoid'];
		
		if ($sessao_id != session_id()){
			$active = false;
		}else{
			include_once("funcoes.php");
			
			$tempoExpira = ehMason() || ehAdminGeral() ? 7200 : 3600;
			
			if( converteHoraSegundos(Difer_horas(date("H:i:s"), $_SESSION["sessaoexpira"])) > $tempoExpira){
				session_destroy();
		//		header("Location: /inicio");
		//		exit;
			}else{
				$_SESSION['active'] = $active = true;
				$_SESSION["sessaoexpira"] = date("H:i:s");
			}
		}
	}
?>