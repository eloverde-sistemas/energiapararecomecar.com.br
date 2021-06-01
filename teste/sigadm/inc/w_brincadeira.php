<?	

	//UPDATE `elemento_sorteavel` SET id_participante_cupom=0, dt_atribuicao=NULL
	
	//UPDATE `importacao` SET importado=0, id_elemento_sorteavel=0, numero_sorte=0


	function conectaBanco(){
		$banco_server  = 'localhost'; // Se é local fica local host, senão vai o link ( ou ip ) do servidor
		$banco_usuario = 'neoenerg_sis'; // Usuario do servidor que está sendo usado
		$banco_nome    = 'neoenerg_sis';
		$banco_senha   = 'oHqx6bZV_4nWv8#wP^35{HAH'; // Senha do servidor que está sendo usado

		//LINK COM O MYSQL
		$conexao = mysql_connect($banco_server, $banco_usuario, $banco_senha); 
		//CASO NÃO CONSIGA FAZER A CONEXÃO
		if(!$conexao) {
			//MATA A EXECUÇÃO DO PHP E MOSTRA O ERRO GERADO NO MYSQL
			return "oi";
			die();
		}

		//SELECIONA O BANCO DE DADOS QUE SERÁ UTILIZADO
		$dbSelect = mysql_select_db($banco_nome, $conexao);
		//CASO NÃO CONSIGA SELECIONAR
		if (!$dbSelect){
			//MATA A EXECUÇÃO E MOSTRA O ERRO GERADO NO MYSQL
			return "oi2";
			die();
		}
	}

	function conectaBancoAPI(){
		$banco_server  = 'localhost'; // Se é local fica local host, senão vai o link ( ou ip ) do servidor
		$banco_usuario = 'neoenerg_sis'; // Usuario do servidor que está sendo usado
		$banco_nome    = 'neoenerg_api';
		$banco_senha   = 'oHqx6bZV_4nWv8#wP^35{HAH'; // Senha do servidor que está sendo usado
		//LINK COM O MYSQL
		$conexao = mysql_connect($banco_server, $banco_usuario, $banco_senha); 
		//CASO NÃO CONSIGA FAZER A CONEXÃO
		if(!$conexao) {
			//MATA A EXECUÇÃO DO PHP E MOSTRA O ERRO GERADO NO MYSQL
			echo "Teste1";
			return "oi";
			die();
		}

		//SELECIONA O BANCO DE DADOS QUE SERÁ UTILIZADO
		$dbSelect = mysql_select_db($banco_nome, $conexao);
		//CASO NÃO CONSIGA SELECIONAR
		if (!$dbSelect){
			//MATA A EXECUÇÃO E MOSTRA O ERRO GERADO NO MYSQL
			return "oi2";
			die();
		}
	}


	//CONSULTA PARTICIPANTE PELA UNIDADE OU CADASTRA CASO NÃO TENHA
	function consultaParticipanteCampanhaPelaUnidade($campanha, $unidade, $cliente){
		
		$idPart = 0;

		conectaBanco();
		$part = executaSQL("SELECT id FROM participante WHERE unidade='".$unidade."' ");
		if(nLinhas($part)>0){
			$idPart = objetoPHP($part)->id;
		}else{
			$idPart = proximoId('participante');

			$dados = array();
			$dados['id'] = $idPart;
			$dados['unidade'] = $unidade;
			$dados['nome'] = $cliente;

			if( inserirDados('participante', $dados) ){
				
				$dadosEventoParticipante = array();
				$dadosEventoParticipante['id_evento'] = $campanha;
				$dadosEventoParticipante['id_participante'] = $idPart;

 				inserirDados('evento_participante', $dadosEventoParticipante);
			}
		}
		return $idPart;
	}


	//CONSULTA TIPO DE PARTICIPAÇÃO PELO NOME
	function consultaTipoPeloNome($tipoNome){
		conectaBanco();
		return objetoPHP(executaSQL("SELECT * FROM participacao_tipo WHERE valor='".$tipoNome."' "))->id;
	}


	//INSERE CUPOM PARA O PARTICIPANTE SE ENCONTRAR O TIPO DE PARTICIPAÇÃO
	function insereParticipanteCupom($campanha, $cliente, $tipoNome){
		conectaBanco();
		//echo "<br>Tipo: ".
		$tipo = consultaTipoPeloNome($tipoNome);

		if($tipo>0){
			$idPartCupom = proximoId('participante_cupom');

			$dados = array();
			$dados['id'] = $idPartCupom;
			$dados['id_evento'] = $campanha;
			$dados['id_participante'] = $cliente;
			$dados['id_tipo_participacao'] = $tipo;
			$dados['id_situacao'] = 1;
			$dados['ano_mes'] = date("Y_m");

			inserirDados('participante_cupom', $dados);

			return $idPartCupom;
		}else{
			return 0;
		}
	}

	function atribuiElementoSorteavel($campanha, $idCupom){
		conectaBanco();
		$achou = false;
		while(!$achou){
			$numeroAletorio = array();
			$numeroAletorio[] = rand(0, 100000000);
			$numeroAletorio[] = rand(0, 100000000);
			$numeroAletorio[] = rand(0, 100000000);
			$numeroAletorio[] = rand(0, 100000000);
			$numeroAletorio[] = rand(0, 100000000);
			//echo "<br /><br />";
			$exeNumero = executaSQL("SELECT id, elemento FROM elemento_sorteavel WHERE id_evento='".$campanha."' AND id_participante_cupom='0' AND id IN (".implode(',', $numeroAletorio).") ");
			if( nLinhas($exeNumero)>0 ){

				$achou = true;

				$elemento_sorteavel = objetoPHP($exeNumero);
				//echo "<br>Id Achado:".
				$idAchado = $elemento_sorteavel->id;
				//echo "<br>Numero Achado:".
				$numeroAchado = $elemento_sorteavel->elemento;
				
			}
		}

		return array('id'=>$idAchado, 'numero'=>$numeroAchado);
	}


	$campanha = 1; //seta a campanha

	conectaBancoAPI();
	$importacoes = executaSQL("SELECT * FROM importacao WHERE importado=0 LIMIT 1000 ");
	if( nLinhas($importacoes)>0 ){
		
		while( $importacao = objetoPHP($importacoes) ){
			
			//echo "<br><br>Importação";

			conectaBanco();

			$cliente = consultaParticipanteCampanhaPelaUnidade($campanha, $importacao->unidade, $importacao->cliente);
			
			if($cliente>0){
				//echo "<br>Cliente: ".$cliente;
				$cupomPart = insereParticipanteCupom($campanha, $cliente, trim($importacao->tipo));
				if($cupomPart>0){

					//echo "<br>Cupom: ".$cupomPart;

					$elemSorteavel = atribuiElementoSorteavel($campanha, $cupomPart);

					if($elemSorteavel>0){

						//echo "<br>Id Achado:".
						$idAchado = $elemento_sorteavel->id;
						//echo "<br>Numero Achado:".
						$numeroAchado = $elemento_sorteavel->elemento;

						$dadosCupom = array();
						$dadosCupom['id_evento'] = $campanha;
						$dadosCupom['id_participante_cupom'] = $cupomPart;
						$dadosCupom['dt_atribuicao'] = date('YmdHis');

						if( alterarDados('elemento_sorteavel', $dadosCupom, "id='".$elemSorteavel['id']."'") ){

							conectaBancoAPI();
							alterarDados('importacao', array('importado'=>1, 'id_elemento_sorteavel'=>$elemSorteavel['id'], 'numero_sorte'=>$elemSorteavel['numero']), "id='".$importacao->id."'");

						}
					}
				}

			}
			
		}
		
	}

?>