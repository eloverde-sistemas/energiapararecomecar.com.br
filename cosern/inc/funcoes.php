<?php

	function isLogado($admin = NULL){
		$usuarioLogado = false;
		
		if(isset($_SESSION['sessaoid']) && $_SESSION['sessaoid'] == session_id()){	
				
			$usuarioLogado = true;
			
			if($admin != NULL){
				
				if($_SESSION['usuarioLoja'] == $admin){
					
					$usuarioLogado = true;
				}else{
					
					$usuarioLogado = false;	
				}
			}else{
				
				$usuarioLogado = true;
			}
			
		}
		
		return $usuarioLogado;
	}
	
	function geraSenha($numchar){  
	   $letras_numeros = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,";
	   $numeros = "1,2,3,4,5,6,7,8,9";
	   $array = explode(",", $numeros);  
	   shuffle($array);  
	   $senha = implode($array, ""); 
	   $senha = strtolower($senha);
	   return substr($senha, 0, $numchar);  
	}
	
	//Adiciona dia(s) na Data informada
	//Parâmetro 1: Data no formato yyyy-mm-dd
	//Parâmentro 2: quantidade de dia que será adicionado na data
	function addDayIntoDate($date, $days){
		$thisyear 	= substr($date, 0, 4);  //Ano
		$thismonth 	= substr($date, 5, 2); //Mês 
		$thisday 	= substr($date, 8, 2);  //Dia
		$nextdate 	= mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );  
		return strftime("%Y-%m-%d", $nextdate);  
	}
	
	function subDayIntoDate($date,$days) {
		$thisyear = substr ( $date, 0, 4 );  
		$thismonth = substr ( $date, 5, 2 );  
		$thisday =  substr ( $date, 8, 2 );  
		$nextdate = mktime ( 0, 0, 0, $thismonth, $thisday - $days, $thisyear );  
		return strftime("%Y-%m-%d", $nextdate);  
	}

	//Retorna o Dia da Semana conforme a data informada
	//Parâmetro 1: Data no formato yyyy-mm-dd
	function diaSemana($data, $idioma="") {
		
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, 2);
		$dia =  substr("$data", 8, 2);
		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
		
		switch($diasemana){
			case"0": 				
				switch($idi) {
					case "pt": $diasemana = "Domingo"; break;
					case "en": $diasemana = "Sunday";  break;
					case "es": $diasemana = "Domingo"; break;
				}
			break;
			case"1": 
				switch($idi) {
					case "pt": $diasemana = "Segunda-Feira"; break;
					case "en": $diasemana = "Monday";		 break;
					case "es": $diasemana = "Lunes";		 break;
				}
			break;
			case"2": 
				switch($idi) {
					case "pt": $diasemana = "Terça-Feira"; break;
					case "en": $diasemana = "Tuesday";	   break;
					case "es": $diasemana = "Martes";	   break;
				}
			break;
			case"3": 
				switch($idi) {
					case "pt": $diasemana = "Quarta-Feira"; break;
					case "en": $diasemana = "Wednesday";	break;
					case "es": $diasemana = "Miércoles";	break;
				}
			break;
			case"4": 
				switch($idi) {
					case "pt": $diasemana = "Quinta-Feira"; break;
					case "en": $diasemana = "Thursday";	    break;
					case "es": $diasemana = "Jueves";	    break;
				}
			break;
			case"5": 
				switch($idi) {
					case "pt": $diasemana = "Sexta-Feira"; break;
					case "en": $diasemana = "Friday";	   break;
					case "es": $diasemana = "Viernes";	   break;
				}
			break;
			case"6": 
				switch($idi) {
					case "pt": $diasemana = "Sábado";   break;
					case "en": $diasemana = "Saturday";	break;
					case "es": $diasemana = "Sábado";	break;
				}
			break;
		}
		return $diasemana;
	}

	function formatarDinheiro($valor, $comSimbolo = true) {		
		
		/*****
			CONSULTAR O TIPO DE MOEDA QUE A LOJA SELECIONOU E TERMINAR ESSA ZICA
		******/
		
		$dinheiro = number_format($valor, 2, ",", ".");
		return ($comSimbolo) 
					? "R$ " . $dinheiro
					: $dinheiro;

	}
	
	function formataValorParaBanco($valor, $idioma=""){
		
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		
		switch($idi) {
			case "pt":
				return str_replace(",",".", str_replace(".","",$valor));
			break;
			case "en":
				return str_replace(",",".", str_replace(",","",$valor));
			break;
			case "es":
				return str_replace(",",".", str_replace(".","",$valor));
			break;
		}

	}
	
	function formataUrl($url){
		if($url!=''){
			$parse = parse_url($url);
			if($parse['scheme'] == ''){
				return trim("http://".$url);
			}else{
				return trim($url);
			}
		}else{
			return "";
		}
	}

	// Função que converte a data informada no padrão português para o inglês
	// o do inglês para o português.
	function converte_data($data, $idioma=""){
		
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		
		if(!isset($data) || $data == '0000-00-00'){
			return NULL;
		}else{
		
			if (strstr($data, "/")){
				$data = explode("/", $data);
				$separador = '-';
				
				switch($idi) {
					case "pt":
						return $data[2].$separador.$data[1].$separador.$data[0];
					break;
					case "en":
						return $data[2].$separador.$data[0].$separador.$data[1];
					break;
					case "es":
						return $data[2].$separador.$data[1].$separador.$data[0];
					break;
				}

			}else{
				$data = explode("-", substr($data, 0, 10));
				$separador = '/';
			
				switch($idi) {
					case "pt":
						return $data[2].$separador.$data[1].$separador.$data[0];
					break;
					case "en":
						return $data[1].$separador.$data[2].$separador.$data[0];
					break;
					case "es":
						return $data[2].$separador.$data[1].$separador.$data[0];
					break;
				}
			}
			return $V_data;
		}
	}

	//Converte a data informada com hífen e formato yyyy-mm-dd, 
	//no formato dd?mm?yyyy e o separador informado
	//Caso não seja informado um separador, será retornado 
	//barra(/) por padrão dd/mm/yyyy
	function converteData($data, $idioma="", $separador='/'){
		
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}

	//	echo "Idioma: ".$idioma." Sessão: ".$_SESSION['idioma'];

		if(!isset($data) || $data == '0000-00-00'){
			return NULL;
		}else{
				
			$data = explode("-", substr($data, 0, 10));
			
			switch($idi) {
				case "pt":
					return $data[2].$separador.$data[1].$separador.$data[0];
				break;
				case "en":
					return $data[1].$separador.$data[2].$separador.$data[0];
				break;
				case "es":
					return $data[2].$separador.$data[1].$separador.$data[0];
				break;
			}
		}
	}	
	
	function converteDataHora($data, $idioma="", $separador="/"){
		
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		
		$dataHora = explode(" ", $data);
		return converteData($dataHora[0], $idi, $separador)." ".$dataHora[1];
	}
	
	function converteDataBancoSHoras($data, $idioma="", $separador='/'){

		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		
		if(!isset($data) || $data == '0000-00-00'){
			return NULL;
		}else{			
			return converteData($data, $idi, $separador);
		}
	}
	
	function converteDataParaBanco($data, $idioma="", $separador='-'){
		
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		
		$data = explode("/", $data);
		
		switch($idi) {
			case "pt":
				return $data[2].$separador.$data[1].$separador.$data[0];
			break;
			case "en":
				return $data[2].$separador.$data[0].$separador.$data[1];
			break;
			case "es":
				return $data[2].$separador.$data[1].$separador.$data[0];
			break;
		}
	}
	
	function converteArquivoNome($str){
		return strtr(strtoupper($str),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ!@#$%¨&*() /,.","AAAAAAACEEEEIIIIINOOOOO_OUUUBY___________-__");		
	}
	
	function converteUrlNome($str){	
		$str = strtolower($str);
		$map = array(
			'á' => 'a', 'à' => 'a',	'ã' => 'a',	'â' => 'a',
			'é' => 'e',	'ê' => 'e',	
			'í' => 'i',			
			'ó' => 'o',	'ô' => 'o', 'õ' => 'o',
			'ú' => 'u',	'ü' => 'u',
			'ç' => 'c',
			'Á' => 'A',	'À' => 'A',	'Ã' => 'A',	'Â' => 'A',
			'É' => 'E',	'Ê' => 'E',
			'Í' => 'I',	'Ó' => 'O',	'Ô' => 'O',	'Õ' => 'O',
			'Ú' => 'U',	'Ü' => 'U',
			'Ç' => 'C',
			'ÿ' => 'y', 'ñ' => 'n', 'Ñ' => 'N',
			'!' => '_',	'@' => '_', '#' => '_', '$' => '_', '%' => '_', '¨' => '_', '&' => '_', '*' => '_', 
			'(' => '_', ')' => '_', '/' => '_', ',' => '_','.' => '_', '"' => '_', "'" => '_',
			' ' => '-'
		);		
		return strtr($str, $map);
	}
	
	function converteEmail($str){
		return strtr($str,	"àáâãäåæçèéêëìíîïðñòóôõöøùüúþÿ",
							"aaaaaaaceeeeiiiionoooooouuuby");
	}

	function dataAtual(){
	  $s = date("D"); /* Mostra 3 primeiras letras do dia da semana em ingles */
	  $a = date("Y");
	  $meses = date("m"); /* Mostra o Mês em números */
	  $dia = date("d");
	  $semana = array("Sun" => "Domingo", "Mon" => "Segunda Feira", "Tue" => "Terça Feira", 
					  "Wed" => "Quarta Feira", "Thu" => "Quinta Feira ", "Fri" => "Sexta Feira ", 
					  "Sat" => "Sábado"); /* Dias da Semana. */
	  return $data = " $semana[$s] - $dia/$meses/$a";
	}
	
	//Diferença de dias entre a data passada e a data atual - padrão dd/mm/yyyy
	function diasAteHoje($to, $idioma="", $separador='/') { 
		
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		
		switch($idi) {
			case "pt":
				list($to_day, $to_month, $to_year) = explode($separador, $to);
			break;
			case "en":
				list($to_month, $to_day, $to_year) = explode($separador, $to);
			break;
			case "es":
				list($to_day, $to_month, $to_year) = explode($separador, $to);
			break;
		}		
		
		$from = date("m-d-Y");//Hoje
		list($from_month, $from_day, $from_year) = explode("-", $from);
					 
		$from_date = mktime(0,0,0,$from_month,$from_day,$from_year);
		$to_date   = mktime(0,0,0,$to_month,$to_day,$to_year);
					 
		$days = ($to_date - $from_date)/86400;
		
		//return $to_month;
		return ceil($days);
	}
	
	//Diferença entre horas
	function converteHoraSegundos($hora){
		 $hora1 = explode(":",$hora);
		 return ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
	}
	
	function converteHoraSSegundos($hora){
		 $hora1 = substr($hora, 0,5);
		 return $hora1;
	}
	
	//Diferença entre horas
	function Difer_horas($saida,$entrada){
		 $hora1 = explode(":",$entrada);
		 $hora2 = explode(":",$saida);
		 $acumulador1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
		 $acumulador2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
		 $resultado = $acumulador2 - $acumulador1;
		 $hora_ponto = floor($resultado / 3600);
		 $resultado = $resultado - ($hora_ponto * 3600);
		 $min_ponto = floor($resultado / 60);
		 $resultado = $resultado - ($min_ponto * 60);
		 $secs_ponto = $resultado;
		 
		 if(strlen($hora_ponto)< 2 ){ $hora_ponto = "0".$hora_ponto; }
		 if(strlen($min_ponto)< 2 ){ $min_ponto = "0".$min_ponto; }
		 if(strlen($secs_ponto)< 2 ){ $secs_ponto = "0".$secs_ponto; }
		 return $hora_ponto.":".$min_ponto.":".$secs_ponto; 
	 }
	 
	 //MES POR EXTENSO
	 function mesExtenso($mes, $idioma="", $completo=1){
		 
		$idi = $_SESSION['idioma'];
		if($idioma!=''){$idi=$idioma;}
		 
		switch($mes){
			case"01": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Janeiro" :"Jan"; break;
					case "en": $mes = ($completo==1)?"January" :"Jan"; break;
					case "es": $mes = ($completo==1)?"Enero"   :"Ene"; break;
				}
			break;
			case"02": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Fevereiro" :"Fev"; break;
					case "en": $mes = ($completo==1)?"February"  :"Feb"; break;
					case "es": $mes = ($completo==1)?"Febrero"   :"Feb"; break;
				}
			break;
			case"03": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Março" :"Mar"; break;
					case "en": $mes = ($completo==1)?"March" :"Mar"; break;
					case "es": $mes = ($completo==1)?"Marzo" :"Mar"; break;
				}
			break;
			case"04": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Abril" :"Abr"; break;
					case "en": $mes = ($completo==1)?"April" :"Apr"; break;
					case "es": $mes = ($completo==1)?"Abril" :"Abr"; break;
				}
			break;
			case"05": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Maio" :"Mai"; break;
					case "en": $mes = ($completo==1)?"May"  :"May"; break;
					case "es": $mes = ($completo==1)?"Mayo" :"May"; break;
				}
			break;
			case"06": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Junho" :"Jun"; break;
					case "en": $mes = ($completo==1)?"June"  :"Jun"; break;
					case "es": $mes = ($completo==1)?"Junio" :"Jun"; break;
				}
			break;
			case"07": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Julho" :"Jul"; break;
					case "en": $mes = ($completo==1)?"July"  :"Jul"; break;
					case "es": $mes = ($completo==1)?"Julio" :"Jul"; break;
				}
			break;
			case"08": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Agosto" :"Ago"; break;
					case "en": $mes = ($completo==1)?"August" :"Aug"; break;
					case "es": $mes = ($completo==1)?"Agosto" :"Ago"; break;
				}
			break;
			case"09": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Setembro"   :"Set"; break;
					case "en": $mes = ($completo==1)?"September"  :"Sep"; break;
					case "es": $mes = ($completo==1)?"Septiembre" :"Sep"; break;
				}
			break;
			case"10": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Outubro" :"Out"; break;
					case "en": $mes = ($completo==1)?"October" :"Oct"; break;
					case "es": $mes = ($completo==1)?"Octubre" :"Oct"; break;
				}
			break;
			case"11": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Novembro" :"Nov"; break;
					case "en": $mes = ($completo==1)?"November" :"Nov"; break;
					case "es": $mes = ($completo==1)?"Noviembre" :"Nov"; break;
				}
			break;
			case"12": 
				switch($idi) {
					case "pt": $mes = ($completo==1)?"Dezembro" :"Dez"; break;
					case "en": $mes = ($completo==1)?"December" :"Dec"; break;
					case "es": $mes = ($completo==1)?"Diciembre" :"Dic"; break;
				}
			break;
		}
		return $mes;
	}
	
	/********************************* GOSC FUNCOES **************************************/
	
	//Função para criar diretórios
	function criaDiretorios( $diretorios ){
		
		foreach( $diretorios as $diretorio ){
			
			$caminho .= $diretorio."/";
		}
		
		if( !is_dir( '/'.$caminho ) ){
			
			$caminho = '';
			
			foreach( $diretorios as $diretorio ){
			
			$caminho .= $diretorio."/";
				
				if( !is_dir( $caminho ) ){
					//echo "<br />".'../'.$caminho;
					mkdir($caminho, 0777);
					chmod($caminho, 0777);					
				}
				
			}
				
		}
		
		return $caminho;	
		
	}
	
	function setarMensagem($mensagens, $tipo){
			$_SESSION['mensagem'] = array(
										"descricao" => implode("<br />",$mensagens),
										"tipo" => $tipo
									);
	}
	
	function mostrarMensagem(){
		if($_SESSION['mensagem']){ 
			if($_SESSION['mensagem']['tipo']=='error')
				$_SESSION['mensagem']['tipo'] = 'danger';
?>
			<script>
				$(function(){
					var msg =  '<div class="alert alert-<?=$_SESSION['mensagem']['tipo']?>">';
						msg += 		'<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button> <div class="container"><?=$_SESSION['mensagem']['descricao']?></div>';
						msg += '</div>';
					$("#main .contendo").prepend(msg);
				});
			</script>
	<?	}	
		unset($_SESSION['mensagem']);
	}
	
	function converteMJSON($mensagem){
		if($mensagem!=''){
			$message = iconv('iso-8859-1', 'utf-8', $mensagem);
			return $message;
		}
		
	}
	
	function converteBancoJSON($mensagem){
		if($mensagem!=''){
			$message = iconv('utf-8', 'iso-8859-1', $mensagem);
			return $message;
		}
		
	}
	
	function formataNumeroComZeros($numero, $qtdeZeros){
		$str = "000000000000000000000000000000000000000000000000000000000000000";
		return substr($str.$numero, strlen($str.$numero)-$qtdeZeros, $qtdeZeros);
	}
	
	function formatarBytes($bytes){
		if($bytes < 1024) {
			$return = $bytes;
			$unidade = ' Bytes';
		} else if($bytes < 1048576) {
			$return = round($bytes / 1024, 2);
			$unidade = ' Kb';
		} else if($bytes < 1073741824) {
			$return = round($bytes / 1048576, 2);
			$unidade = ' Mb';
		} else if($bytes < 1099511627776) {
			$return = round($bytes / 1073741824, 2);
			$unidade = ' Gb';
		} else if($bytes < 1125899906842624) {
			$return = round($bytes / 1099511627776, 2);
			$unidade = ' Tb';
		} else if($bytes < 1152921504606846976) {
			$return = round($bytes / 1125899906842624, 2);
			$unidade = ' Pb';
		} else if($bytes < 1180591620717411303424) {
			$return = round($bytes / 1152921504606846976, 2);
			$unidade = ' Eb';
		} else if($bytes < 1208925819614629174706176) {
			$return = round($bytes / 1180591620717411303424, 2);
			$unidade = ' Zb';
		} else {
			$return = round($bytes / 1208925819614629174706176, 2);
			$unidade = ' Yb';
		}
		
		return ($return > 0) 
					? number_format($return, 2, ",", "") . $unidade
					: 0 . $unidade;
	}
	
	function formatarBytesInteiro($bytes){
		if($bytes < 1024) {
			$return = $bytes;
			$unidade = ' Bytes';
		} else if($bytes < 1048576) {
			$return = round($bytes / 1024, 2);
			$unidade = ' Kb';
		} else if($bytes < 1073741824) {
			$return = round($bytes / 1048576, 2);
			$unidade = ' Mb';
		} else if($bytes < 1099511627776) {
			$return = round($bytes / 1073741824, 2);
			$unidade = ' Gb';
		} else if($bytes < 1125899906842624) {
			$return = round($bytes / 1099511627776, 2);
			$unidade = ' Tb';
		} else if($bytes < 1152921504606846976) {
			$return = round($bytes / 1125899906842624, 2);
			$unidade = ' Pb';
		} else if($bytes < 1180591620717411303424) {
			$return = round($bytes / 1152921504606846976, 2);
			$unidade = ' Eb';
		} else if($bytes < 1208925819614629174706176) {
			$return = round($bytes / 1180591620717411303424, 2);
			$unidade = ' Zb';
		} else {
			$return = round($bytes / 1208925819614629174706176, 2);
			$unidade = ' Yb';
		}
		
		return ($return > 0) 
					? number_format($return, 0, ",", "") . $unidade
					: 0 . $unidade;
	}

	//FUNCAO PADRÃO QUE MANDA EMAIL
	/*
		Caso não seja enviado nenhum remetente, pega o email e o nome da loja para remetente
		cc = com copia
		cco = com copia oculta
	*/
	function enviaEmail($assunto, $msg, $destinatario, $cc="", $cco="", $remetenteEmail="", $remetenteNome="", $json=false){

		$mail = new PHPMailer();

		$mail->CharSet = 'UTF-8';		
		//Tell PHPMailer to use SMTP
//		$mail->isSMTP();		
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;		
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';		
		//Set the hostname of the mail server
		$mail->Host = 'mail.sorteios.srv.br';		
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 25;		
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication
		$mail->Username = 'naoresponder@sorteios.srv.br';
		//Password to use for SMTP authentication
		$mail->Password = 'b)@~%5qRgZVyI+!~fk=aQfu+';
	
		$mail->Sender = $_SESSION['mail-account'];
		
		//Seta os Dados do Remetente
		if($remetenteEmail!=''){
			$addressEmail = $remetenteEmail;

			if($remetenteNome!=''){
				$addressNome = $remetenteNome;
			}else{
				$addressNome = "'".$_SESSION['mail-name']."'";
			}
		}else{
			$addressEmail = $_SESSION['mail-account'];
			$addressNome = $_SESSION['mail-name'];
		}
		//Set who the message is to be sent from
		$mail->From = $addressEmail; // Endereço do remetente
		if($addressNome!=''){
			$mail->FromName = utf8_encode($addressNome); // Nome do remetente
		}

		$mail->addAddress($destinatario);

		$mail->addBCC("msceverton@gmail.com");
		
		//ADICIONA COMO CÓPIA O(S) DESTINATARIO(S)
		if($cc!=''){
			if(is_array($cc)){
				foreach($cc as $ccEmail){
					$mail->addCC($ccEmail);
				}
			}else{
				$mail->addCC($cc);
			}
		}
		
		//ADICIONA COMO CÓPIA OCULTA O(S) DESTINATARIO(S)
		if($cco!=''){
			if(is_array($cco)){
				foreach($cco as $ccoEmail){
					$mail->addBCC($ccoEmail);
				}
			}else{
				$mail->addBCC($cco);
			}
		}
		
		//MANDA UMA CÓPIA OCULTA PRO EVERTON DE CADA EMAIL DO SISTEMA
		//$mail->addBCC("msceverton@gmail.com");		
		
		//Set the subject line
		$mail->Subject = utf8_encode($assunto);
		
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->MsgHTML(utf8_encode(geraEmailPadrao($msg,$json)));
		
		//send the message, check for errors
		if(!$mail->send()){			
			$msg = "Mailer Error: " . $mail->ErrorInfo;
			return array("status"=>false, "msg"=>$msg);
		}else{
			return array("status"=>true);
		} 
		
	}


	function resumo($texto, $qtde=200) {  
		if (strlen($texto) > $qtde) {  
			while( !in_array( mb_substr($texto, $qtde, 1), array(' ', ',', '.') ) && ($qtde < strlen($texto))){  
				 $qtde++;  
			};  
		};  
	  	return mb_substr(strip_tags($texto),0,$qtde).' [..]';  
	}
	
	function diffDate($d2, $d1, $type='', $sep='-'){
		$d1 = explode($sep, $d1);
		$d2 = explode($sep, $d2);
		switch ($type){
			//anos
			case 'A':
				$X = 31536000;
			break;
			//meses
			case 'M':
				$X = 2592000;
			break;
			//dias
			case 'D':
				$X = 86400;
			break;
			//horas
			case 'H':
				$X = 3600;
			break;
			//minutos
			case 'MI':
				$X = 60;
			break;
			//segundos
			default:
				$X = 1;
		}
		
		$d2 = mktime(0, 0, 0, $d2[1], $d2[2], $d2[0]);
		$d1 = mktime(0, 0, 0, $d1[1], $d1[2], $d1[0]);
		$dif = $d2 - $d1;
	 	return floor( $dif / $X );
	}
	
	function toHTML($valor){
		
		$valor = str_replace("[br /]", "<br />", $valor);
		$valor = str_replace("[strong]", "<strong>", $valor);
		$valor = str_replace("[/strong]", "</strong>", $valor);
		
		//Link <a></a>
		$valor = str_replace("[a", "<a", $valor);
		$valor = str_replace("' ]", "'>", $valor);
		$valor = str_replace("']", "'>", $valor);
		$valor = str_replace("[/a]", "</a>", $valor);
		
		//Image <img />
		$valor = str_replace("[img", "<img", $valor);
		$valor = str_replace("' /]", "' />", $valor);
		$valor = str_replace("'/]", "' />", $valor);
		
		//Span <span></span>
		$valor = str_replace("[span", "<span", $valor);
		$valor = str_replace('" ]', '" >', $valor);
		$valor = str_replace('"]', '" >', $valor);
		$valor = str_replace("[/span]", "</span>", $valor);
		
		return $valor;
	}
	
	function traducaoParams($valor, $params){
		$x = 0;
		foreach($params as $param){
			$x++;
			$valor = str_replace("%".$x."%", $param, $valor);
		}
		return $valor;
	}
										
	function geraEmailPadrao($conteudo, $json){

		$dirLogo = "images/logo.png";
		$sitePortal = $_SESSION['http_s'].$_SESSION['url_site'];
		$brasao = "<a href='".$sitePortal."'><img src='".$sitePortal."/".$dirLogo."' width='100px' target='_blank' /></a>";
		
		if($json){
			$msg = file_get_contents('../inc/email/email-padrao.html');
		}else{
			$msg = file_get_contents('inc/email/email-padrao.html');
		}
		
		$msg = str_replace("[conteudo_email]", $conteudo, $msg);
		$msg = str_replace("[logo]", $brasao, $msg);
		$msg = str_replace("[info]", $_SESSION['EMPRESA']->nome."<br />".$_SESSION['EMPRESA']->site, $msg);
		$msg = str_replace("[rodape_1]", $_SESSION['empresa-endereco'], $msg);
		$msg = str_replace("[rodape_2]", $_SESSION['EMPRESA']->telefone." - ".$_SESSION['EMPRESA']->email, $msg);
		
		return $msg;
	}
		
	function validaEmail($email) {
		$email = trim($email);
		if (preg_match ("/^[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*\\.[A-Za-z0-9]{2,4}$/", $email)) {
			return true;		
		}else{		
			return false;
		}

	}
	
//	function getMesAnoAnterior($date,$days) {
	function getMesAnoAnterior($meses, $data=NULL) {
		if($data==NULL)
			$nextdate = mktime ( 0, 0, 0, date('m') - $meses, 1, date('Y') );
		else{
			$thisyear = substr ( $data, 0, 4 );  
			$thismonth = substr ( $data, 5, 2 );  
			$thisday =  substr ( $data, 8, 2 );  
			$nextdate = mktime ( 0, 0, 0, $thismonth - $meses, $thisday, $thisyear );  
		}

		$dados ['mes'] = strftime("%m", $nextdate);
		$dados ['ano'] = strftime("%Y", $nextdate);
		return $dados;
	//	return array('ano' => strftime("%Y", $nextdate), 'mes' => strftime("%m", $nextdate));
	}
	
	function formatarNumeroCadastro($valor) {
		return $valor;
	}
	
	function paginacaoAjaxQuery($POST, $aColumns, $paramsExtras=array(), $groupBy=""){		
		
		//WHERE
		$sWhere = " WHERE 1=1 ";
		if(count($paramsExtras)>0){
			$sWhere .= " AND ".implode(" AND ", $paramsExtras);
		}
		for ( $i=0 ; $i<count($POST['columns']) ; $i++ ){
			$busca = $POST['filterValue'][$i];
			
			$regex = $POST['filterRegex'][$i];
			
			if ( isset($busca) && !in_array($busca, array('', NULL)) ){

				if($regex=='LIKEALL'){
					$palavras = explode(" ", $busca);
					foreach($palavras as $palavra){
						$sWhere .= " AND ".addslashes($aColumns[$i])." LIKE '%".addslashes($palavra)."%' ";
					}
				}else{
					
					if($regex=='LIKE'){
						$sWhere .= " AND ".addslashes($aColumns[$i]);
						$sWhere .= " LIKE '%".addslashes($busca)."%' ";
					}elseif($regex=='BETWEEN'){
						$datas = explode("||", $busca);
						$dataIni = addslashes(trim($datas[0]));
						$dataFim = addslashes(trim($datas[1]));
						
						if($dataIni!='' && $dataIni!=NULL && $dataIni!='undefined'){
							$sWhere .= " AND ".addslashes($aColumns[$i]);
							$sWhere .= " >= '".$dataIni."'";							
						}
							
						if($dataFim!='' && $dataFim!=NULL && $dataFim!='undefined'){
							$sWhere .= " AND ".addslashes($aColumns[$i]);
							$sWhere .= " <= '".$dataFim."'";
						}
							
						//$sWhere .= " BETWEEN ".$busca." ";
					}elseif($regex=='IN'){
						$sWhere .= " AND ".addslashes($aColumns[$i]);
						$sWhere .= " IN (".addslashes($busca).") ";
						
					}else{
						$sWhere .= " AND ".addslashes($aColumns[$i]);
						$sWhere .= " ".$regex." '".addslashes($busca)."'";
					}
				}				
			}
		}
		
		//GROUP BY
		$sOrder = "";
		if($groupBy!=''){
			$sOrder .= " ".$groupBy." "; 
		}		
		
		//ORDER
		if(isset($POST['order']) ){
			$sOrder .= " ORDER BY ";
			
			//COLUNA
			if( in_array($aColumns[ $POST['order'][0]['column'] ], $aColumns) ){
				$sOrder .= $aColumns[ $POST['order'][0]['column'] ];
			}else{
				$sOrder .= $aColumns[0];
			}
			
			//DESC / ASC
			if( in_array( strtoupper($POST['order'][0]['dir']), array("ASC","DESC")) ){
				$sOrder .= " ".$POST['order'][0]['dir'];
			}
		}
		
		//LIMIT
		$sLimit = "";
		if ( isset( $POST['start'] ) && $POST['length'] != '-1' ){
			$sLimit = "LIMIT ". intval($POST['start']) .", ". intval($POST['length']) ;
		}
		
		return " ".utf8_decode($sWhere)." ".$sOrder." ".$sLimit;
		
	}
	
	function paginacaoAjaxParams($POST, $aColumns, $paramsExtras=array(), $groupBy=""){		
		
				//WHERE
		$sWhere = " WHERE 1=1 ";
		if(count($paramsExtras)>0){
			$sWhere .= " AND ".implode(" AND ", $paramsExtras);
		}
		for ( $i=0 ; $i<count($POST['columns']) ; $i++ ){
			$busca = $POST['filterValue'][$i];
					
			$regex = $POST['filterRegex'][$i];
			
			if ( isset($busca) && !in_array($busca, array('', NULL)) ){

				if($regex=='LIKEALL'){
					$palavras = explode(" ", $busca);
					foreach($palavras as $palavra){
						$sWhere .= " AND ".addslashes($aColumns[$i])." LIKE '%".addslashes($palavra)."%' ";
					}
				}else{
					
					if($regex=='LIKE'){
						$sWhere .= " AND ".addslashes($aColumns[$i]);
						$sWhere .= " LIKE '%".addslashes($busca)."%' ";
					}elseif($regex=='BETWEEN'){
						$datas = explode("||", $busca);
						$dataIni = addslashes(trim($datas[0]));
						$dataFim = addslashes(trim($datas[1]));
						
						if($dataIni!='' && $dataIni!=NULL && $dataIni!='undefined'){
							$sWhere .= " AND ".addslashes($aColumns[$i]);
							$sWhere .= " >= '".$dataIni."'";							
						}
							
						if($dataFim!='' && $dataFim!=NULL && $dataFim!='undefined'){
							$sWhere .= " AND ".addslashes($aColumns[$i]);
							$sWhere .= " <= '".$dataFim."'";
						}
							
						//$sWhere .= " BETWEEN ".$busca." ";
					}elseif($regex=='IN'){
						$sWhere .= " AND ".addslashes($aColumns[$i]);
						$sWhere .= " IN (".addslashes($busca).") ";
						
					}else{
						$sWhere .= " AND ".addslashes($aColumns[$i]);
						$sWhere .= " ".addslashes($regex)." '".addslashes($busca)."'";
					}
					
				}
				
			}
		}
		
		//GROUP BY
		if($groupBy!=''){
			$sWhere .= " ".$groupBy." "; 
		}	
			
		return utf8_decode($sWhere);
		
	}
	
	function paginacaoAjaxTotalResults($paramsQuery, $sTable){
		
		/* Total data set length */
		$sQuery = "	SELECT 1 FROM ".$sTable." ".$paramsQuery;		
		$exeQuery = mysql_query( $sQuery ) or die(mysql_error());
		
		return mysql_num_rows($exeQuery);
	}
	
	/*
		TIPO = 1=PADRÃO 2=EXCLUIR
		CLASSESBOTAO = Passar todas as classes do botão por array
		HREF = href do <a>		
		ICONE = classe do icone. Ex: fa-times, fa-plus...		
		NOME = Nome do botão
		TITLE = Atributo "title" do botão
		ATTRS = Atributos extras
		TARGET = _blank / _new ....
	*/
	function paginacaoAjaxBotoes($tipo, $classesBotao, $href, $icone="", $nome="", $title="", $target="", $attrs=array(), $msgExcluir="", $linksAcoes=array()){
		
		if(count($classesBotao)>0){
			$classesBotao = implode(" ", $classesBotao);
		}
		
		if(count($attrs)>0){
			$attrs = implode(" ", $attrs);
		}
		
		if($tipo==2){//EXCLUIR			
			
			$botao =   '<a href="javascript:void(0);" onclick="bootbox.confirm(\''.$msgExcluir.'\', function(result){ if(result){ window.location.href=\''.$href.'\';} })" class="'.$classesBotao.'" '.$attrs.' title="'.$title.'" alt="'.$title.'">
							<i class="fa '.$icone.'"></i> '.$nome.'
						</a>';
						
		}elseif($tipo==3){//AÇÕES 
		
			if(count($linksAcoes)>0){
				$botao = '<div class="btn-group">
							  <button class="btn btn-sm blue dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
									<i class="fa '.$icone.'"></i> '.$nome.' &nbsp; <i class="fa fa-angle-down"></i>
							  </button>
							  <ul class="dropdown-menu pull-right" role="menu">';								
									foreach($linksAcoes as $link){
										$botao .= "<li>".$link."</li>";
									}			
				$botao.= 	 '</ul>';
			}
		
		}else{//PADRÃO
		
       		$botao =   '<a href="'.$href.'" class="'.$classesBotao.'" '.$attrs.' target="'.$target.'" title="'.$title.'" alt="'.$title.'">
							<i class="fa '.$icone.'"></i> '.$nome.'
						</a>';
						
        }
		
		return $botao;
		
	}
?>
