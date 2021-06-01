<?php
	
	function registrarLog( $acao, $sql, $tabela ){
		
		$idLog = proximoId('historico');
		
		$useragent = $_SERVER['HTTP_USER_AGENT'];
	 
		if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser = 'IE '.$matched[1];
		} elseif (preg_match("/Trident\/7.0;(.*)rv:11.0/",$useragent,$matched)) {
			$browser = 'IE 11';
		} elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser = 'Opera '.$matched[1];
		} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
			$browser = 'Firefox '.$matched[1];
		} elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
			$browser = 'Chrome '.$matched[1];
		} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
			$browser = 'Safari '.$matched[1];
		} else {
			$browser = $useragent;
		}
		

		$sql_log = mysql_query('INSERT INTO historico
								 (id, acao, str_sql, id_pessoa, tabela, url, navegador, ip ) values
								("'.$idLog.'", 
								"'.$acao.'", 
								"'.mysql_real_escape_string(inj($sql)).'", 
								'.$_SESSION['usuarioId'].', 
								"'.$tabela.'", 
								"'.$_SERVER['QUERY_STRING'].'", 
								"'.mysql_real_escape_string(inj($browser)).'", 
								"'.$_SERVER["REMOTE_ADDR"].'")'); 
		
		return $idLog;
	}

	function confirmaLog($idLog){
		mysql_query("UPDATE historico SET resposta = 1 WHERE id = '".$idLog."'");
	}
	
	function errorLog($idLog){
		mysql_query("UPDATE historico SET erro = '".inj(mysql_error())."' WHERE id = '".$idLog."'");
	}

	//Protege contra SQL Injection
	function inj($str){
		if ( get_magic_quotes_gpc() ){
			return $str;
		}else{
			return addslashes($str);
		}
	}
	
	//Inclui Dados
	function inserirDados($tabela, $dados, $mostrar=false){
		$campos = $valores = array();
		foreach($dados as $chave => $valor){
			$campos[]  = $chave;
			$valores[] = inj($valor);
		}
		$campos  = join(',', $campos);
		$valores = join("','", $valores);
		$sql = "INSERT INTO $tabela ($campos) values ('$valores');";
		
		if($mostrar) echo "<br>".$sql;
		
		if(function_exists('registrarLog')){
			$idLog = registrarLog('inserir', $sql, $tabela);
		}
		
		$exe = mysql_query($sql);
		
		if($exe){
			if( function_exists('confirmaLog') )
				confirmaLog($idLog);
		}else{
			if( function_exists('errorLog') )
				errorLog($idLog);
		}
		
		return $exe;
	}
	
	
	//Altera Dados
	function alterarDados($tabela, $dados, $clausula, $mostrar=false){
	//	if($mostrar)
	//		var_dump($dados);
		
		foreach ($dados as $chave=>$valor){
			$alts[] = $chave." = '".inj($valor)."' ";
		}
		$lista_alts = join(",", $alts);
		
		$sql = "UPDATE $tabela SET $lista_alts WHERE $clausula ";
		
		if($mostrar) echo "<br>".$sql;
		
		if(function_exists('registrarLog')){
			$idLog = registrarLog('alterar', $sql, $tabela);
		}
		
		$exe =  mysql_query($sql);
		
		if($exe){
			if( function_exists('confirmaLog') )
				confirmaLog($idLog);
		}else{
			if( function_exists('errorLog') )
				errorLog($idLog);
		}
		
		return $exe;
	
	}
	
	function proximoId ($tabela){
		$exe = executaSQL("SELECT MAX(id) as id FROM $tabela ");
		if(nLinhas($exe)>0){
			$proxId = objetoPHP($exe);
			return ($proxId->id + 1);
		}else
			return 1;
	}

	//Função que pega o próximo OID da tabela informada. 
	function proximoOid ($tabela){
		$exe = executaSQL("SELECT MAX(oid) as id FROM $tabela ");
		if(nLinhas($exe)>0){
			$proxId = objetoPHP($exe);
			return ($proxId->id + 1);
		}else
			return 1;
	}
	
	//Exclui Dados
	function excluirDados($tabela, $clausula, $mostrar=false){

		$sql = "DELETE FROM $tabela WHERE $clausula";
		
		if($mostrar) echo $sql;
		
		if(function_exists('registrarLog')){
			$idLog = registrarLog('excluir', $sql, $tabela);
		}
		
		$exe =  mysql_query($sql);
		
		if($exe){
			if( function_exists('confirmaLog') )
				confirmaLog($idLog);
		}else{
			if( function_exists('errorLog') )
				errorLog($idLog);
		}
		
		return $exe;
	}

	//Retorna todos os campos de uma Consulta Simples, informando Tabela e Cláusula 
	function executaSQLPadrao($tabela, $clausula="1", $mostrar=false){
		
		$sql = "SELECT * FROM $tabela WHERE $clausula";
		//echo $sql;
		if($mostrar==true) echo "<br>".$sql;
			
		return mysql_query($sql);
		//echo "SELECT * FROM $tabela WHERE $clausula ";
	}

	//Retorna a execução do SQL completo no Banco de Dados 
	function executaSQL($sql, $mostrar=false){
		
		if($mostrar==true) echo "<br>".$sql;
		return mysql_query($sql);
	}
	
	//Retorna um Objeto do Registro retornado na consulta
	function objetoPHP($exe){
		return mysql_fetch_object($exe);
	}
	
	function arrayPHP($exe){
		return mysql_fetch_array($exe);
	}
	
	//Retorna o Número de Registros da consulta
	function nLinhas($exe){
		return mysql_num_rows($exe);
	}

	function getName($ID,$tabela){
		$resultado = objetoPHP(executaSQLPadrao($tabela,"id = $ID"));
		return $resultado->nome;
	}
	
	function maximoId($tabela){
		$query = mysql_query("SELECT MAX(id) FROM $tabela");
		$id = mysql_fetch_array($query);
		return $id[0];
	}

	function ehColaborador(){
		if($_SESSION['id_tipo'] == 2)
			return true;
		else
			return false;
	}
	
	function ehMason(){
		if($_SESSION['id_tipo'] == 3)
			return true;
		else
			return false;
	}
	
	
	
	//FUNÇÃO QUE PEGA AS INFORMAÇÕES DA EMPRESA
	function empresaDados(){
		return objetoPHP(executaSQLPadrao('config'));	
	}	
	$_SESSION['EMPRESA'] = $_EMPRESA = empresaDados();
	
	
	
	function excluirDadosImagens($id, $tabela, $diretorio='', $diretorioTumb=''){
		
		$dados = objetoPHP(executaSQLPadrao($tabela,"id = $id"));
		
		if(is_file("img/$diretorio/".$dados->imagem)){
			
			unlink("img/$diretorio/".$dados->imagem);
				
				if(is_file("img/$diretorioTumb/".$dados->imagem)){
					unlink("img/$diretorioTumb/".$dados->imagem);
				}
		}
		
		return excluirDados($tabela,"id = $id");
	
	}
	
	function excluirDadosUpload($id, $tabela, $diretorio='', $diretorioTumb=''){
		
		$dados = objetoPHP(executaSQLPadrao($tabela,"id = $id"));
		
		if(is_file($dados->foto)){
			unlink("uploads/$diretorio/".$dados->foto);
				
				if(is_file("uploads/".$_SESSION["loja_sge"]."/".$diretorioTumb."/".$dados->foto))
					unlink("uploads/$diretorioTumb/".$dados->foto);
				
		}
		
		return excluirDados($tabela,"d = $id");
	
	}
	
																			//POR EXEMPLO ../
	function excluirArquivo($id, $tabela, $diretorio='', $diretorioTumb='', $retornoDeDiretorio=''){
		
		$dados = objetoPHP(executaSQLPadrao($tabela,"id = '".$id."'"));
		
		if(is_file($dados->foto)){
			unlink($retornoDeDiretorio.$diretorio.$dados->foto);
				
				if(is_file($dados->thumb))
					unlink($retornoDeDiretorio.$diretorioTumb.$dados->thumb);
			
			return true;
		}else{
			return false;
		}
	
	}
	
	function getMembroTipo($id){
		$exe = executaSQLPadrao("pessoa_tipo", "id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function consultaCargoFuncaoById($oid){
		$cargo = mysql_query("SELECT * FROM tipocargofuncao WHERE oid='$oid' ");
		if (mysql_num_rows($cargo)>0){
			return mysql_fetch_object($cargo);
		}else{
			return false;
		} 
	}
	
	function consultaLojaByCod($loja, $campos='*'){
		if($campos==NULL){
			$campos = "*";
		}
		$exe = executaSQL("SELECT ".$campos." FROM loja WHERE id='".$loja."'");
		if(nLinhas($exe)>0){
			$reg = objetoPHP($exe);
			$reg->cod_formatado = formataNumeroComZeros($reg->cod, 4);

			return $reg;

		}else{
			return false;
		}
	}
	
	function consultaLojasAtivas(){
		return executaSQLPadrao('loja',"id_ativa = 1 ORDER BY cod");
	}
	
	function consultaLojaTipoValorById($id){
		$regs = executaSQLPadrao('loja_tipo',"id = ".$id);
		if(nLinhas($regs)>0){
			return objetoPHP($regs)->sigla;
		}else{
			return false;
		}
	}
	
	function consultaLojaTipoByCod($id){
		$regs = executaSQLPadrao('loja_tipo',"id = ".$id);
		if(nLinhas($regs)>0){
			return objetoPHP($regs);
		}else{
			return NULL;
		}
	}

	function consultaLojaLayoutByCod($loja){
		$reg = executaSQLPadrao("loja_config"," id_loja = '".$loja."' ");
		if(nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return NULL;
		}
	}
		
	function consultaTema($id){
		$regs = executaSQLPadrao('temas', "id = '".$id."'");
		
		if(nLinhas($regs)>0){
			return objetoPHP($regs);
		}else{
			return false;
		}
	}
	
	function getTemaPersonalizado($loja){
		
		$regs = executaSQL("SELECT * FROM layout
									WHERE loja = '".$loja."'");
		
		$valores = array();
		
		while($reg=objetoPHP($regs)){
			$nome = objetoPHP( executaSQL("SELECT nome FROM layout_options WHERE id = '".$reg->id_option."'") )->nome;
			
			$valores[$nome] = $reg->valor;
		}
		
		return $valores;
	}
	
	function get_option_category($id_cat, $translate){
		$regs = executaSQLPadrao("categoria_options", "id = '".$id_cat."'");
		
		if(nLinhas($regs)>0){
			//return objetoPHP($regs)->valor;
			return $translate->translate("categoria_options_".objetoPHP($regs)->id);
		}else{
			return false;
		}
	}
	
	function get_option_value($idOption, $loja='', $idioma="pt"){
		if($loja==''){
			$loja=$_SESSION['loja_sge'];
		}
		$regs = executaSQL("SELECT valor FROM layout
								WHERE loja = '".$loja."'
									AND id_option = '".$idOption."'");
		if(nLinhas($regs)>0){
			return objetoPHP($regs)->valor;
		}else{
			$regs = executaSQL("SELECT id, padrao FROM layout_options
									WHERE id = '".$idOption."'");	
				
			if(nLinhas($regs)>0){				
				$obj = objetoPHP( $regs );
				$padrao = $obj->padrao;
				if($obj->id==152){
					switch($idioma){
						case "es": $padrao = "Por gentileza, preencher o formulário abaixo:"; break;
						case "en": $padrao = "Please, fill out the form below:"; break;
						default:   $padrao = "Por gentileza, preencher o formulário abaixo:"; break;
					}

				}				
				return $padrao;
			}else{
				return NULL;
			}
		}
	}
	
	function get_option_value_sem_padrao($idOption, $loja=""){
		
		if($loja==''){
			$loja=$_SESSION['loja_sge'];
		}		
		$regs = executaSQL("SELECT valor FROM layout
								WHERE loja = '".$loja."'
									AND id_option = '".$idOption."'");
		if(nLinhas($regs)>0){
			return objetoPHP($regs)->valor;
		}else{
			return false;
		}
	}
	
	function getOptionById($idOption){
		$regs = executaSQL("SELECT titulo FROM layout_options
							WHERE id = '".$idOption."'");							
		if(nLinhas($regs)>0){
			return objetoPHP($regs)->titulo;
		}else{
			return false;
		}
	}
	
	function getLayoutOptionById($id){
		$regs = executaSQL("SELECT * FROM layout_options
							WHERE id = '".$id."'");							
		if(nLinhas($regs)>0){
			return objetoPHP($regs);
		}else{
			return false;
		}
	}
	
	/*
		admin/ 		- Todas as páginas de acesso exclusivo do(s) administrador(es);
				 	- É necessário estar logado para acessá-las;
					- É necessário ter o registro no banco para acessá-las;
					
		restrito/ 	- Páginas de acesso exclusivo de irmão;
					- É necessário estar logado para acessá-las;
					
		inc/		- Acesso aberto;
		
	*/
	function precissaLoja(){
		//echo "SEC:".$_GET['sec'];
		//echo "MOD:".$_GET['modulo'];
		return !$_SESSION['loja_sge']>0 && ($_GET['sec']=='loja' || ($_GET['sec']=='restrito' && in_array($_GET['modulo'], array('candidatos', 'calendario')) ));
	}
	
	function temPermissao($nomeTarefa="", $idPerfil=NULL){

		//Valida quando for uma tarefa
		if( $nomeTarefa != "" ){
			
			$temAdmin = $temLoja = array();
			if(is_array($nomeTarefa)){
				
				foreach($nomeTarefa as $valor){
					if(strpos($valor, "LOJA")===0){
						$temLoja[]=1;
					}else{
						$temLoja[]=0;
					}
					if(strpos($valor, "ADMIN")===0){
						$temAdmin[]=1;
					}else{
						$temAdmin[]=0;
					}
				}				
			}else{
				if(strpos($nomeTarefa, "LOJA")===0){
					$temLoja[]=1;
				}else{
					$temLoja[]=0;
				}
				if(strpos($nomeTarefa, "ADMIN")===0){
					$temAdmin[]=1;
				}else{
					$temAdmin[]=0;
				}
			}
			
																	//se a loja é adimplente e todas as permissões começarem com "LOJA_" ou todas as permissões começarem com "ADMIN_"
			if( $idPerfil==NULL && (ehMason() || ehAdminGeral()) && ( (ehAdimplente() && !in_array(0, $temLoja)) || !in_array(0, $temAdmin)) ){

				return true;
				
			}else{
				
				$paramAdm = $paramLoja = "";
				//Se for passada mais de uma tarefa
				if(is_array($nomeTarefa)){
					
					$tarefas = array();
					foreach($nomeTarefa as $valor){
						$tarefas[] = "'".$valor."'";
					}
					
					$paramAdm = $paramLoja = " AND t.nome IN( ".implode(', ', $tarefas).")";
						
				}else{
					$paramAdm = $paramLoja = " AND t.nome = '".$nomeTarefa."'";
				}

				if($idPerfil!=NULL)
					$paramAdm.= " AND p.id_perfil = '".$idPerfil."'";
					
				else{
					$paramAdm.= " AND pp.id_pessoa = '".$_SESSION['pessoaId']."' AND pp.id_perfil = p.id_perfil";
					$tabelaAdm = ", pessoa_perfil pp";
				}
				
				$sqlAdm  = "SELECT 1 FROM perfil_tarefa p, tarefa t ".$tabelaAdm."
								WHERE p.id_tarefa = t.id ".$paramAdm;
				
				$sqlLoja = "SELECT 1 FROM tarefa t WHERE t.tipo=2 AND t.adimplencia=0 ".$paramLoja;
				
				$nLinhasAdm  = nLinhas(executaSQL($sqlAdm));
				$nLinhasLoja = nLinhas(executaSQL($sqlLoja));
								
								 //ou tem alguma permissão começando com "LOJA_", e, a loja é adimplente, ou, a loja não é adimplente e existem permissões para acesso de não adimplentes
				if( $nLinhasAdm>0 || ( in_array(1, $temLoja) && (ehAdimplente() || (!ehAdimplente() && $nLinhasLoja>0)) ) ){
					return true;
				}else{
					return false;
				}
			}
		
		//Valida quando for uma página
		}else{
			
			//Verifica se é admin mason ou geral, se a loja é adimplente e se não é necesssário ter uma loja para o módulo
			if( (ehMason() || ehAdminGeral()) && ehAdimplente() && !precissaLoja() ){
				return true;
				
			}else{
				//	Monta o caminho da página
				$arquivo .= ( $_GET['sec'] )? $_GET['sec']."_" : "";
				
				$arquivo .= ( $_GET['modulo'] )? $_GET['modulo']."_" : "";
				
				$arquivo .= ( $_GET['folder'] )? $_GET['folder']."_" : "";
				
				$arquivo .= ( $_GET['folder2'] )? $_GET['folder2']."_" : "";
				
				$arquivo .= ( $_GET['page'] )? $_GET['page'] : "";				
			
			//	VERIFICA SE O USUÁRIO ESTÁ LOGADO
				if( $_GET['sec']=='restrito' ){
					
					//Se está logado e se não é necesssário ter uma loja para o módulo
					if($_SESSION['active'] && !precissaLoja())
						return true;
					else
						return false;
				
			//	VERIFICA SE O USUÁRIO É ADMIN DA LOJA DE ACESSO ou ADMIN GERAL
				}else if($_GET['sec']=='loja'){					
					
					if( ehAdminLoja() || (temPermissao('ADMIN_LOJAS_TROCAR-LOJA') && $_SESSION['loja_sge']>0) ){
						
						if( verificaAdimplenciaTarefa($arquivo) ){
							return true;
						}else{
							//echo "teste";
							return false;							
						}
						
					}else{						
						return false;
					}
				
			//	VERIFICA SE O USUÁRIO TEM A PERMISSÃO DA PÁGINA
				}else if($_GET['sec']=='admin'){
					
					if( ehMason() || ehAdminGeral() ){
						return true;
					}else{
					
						if($_GET['is_pdf']==true) $arquivo = substr($arquivo, 0, -3);					
					
						//Se for no Cadastro de Irmãos
						if( strpos($arquivo, 'admin_cadastro_')===0 ){
							$tarefas = executaSQLPadrao("tarefa", "arquivo = ''");
							
							$regs = executaSQL("SELECT 1 FROM pessoa_perfil p, perfil_tarefa pt, tarefa t
													WHERE p.id_pessoa = '".$_SESSION['pessoaId']."'
														AND t.arquivo LIKE 'admin_cadastro_%'
														AND pt.id_tarefa = t.id
														AND p.id_perfil = pt.id_perfil");
							
							if( nLinhas( $regs ) > 0 ){
								return true;

							}else{
								return false;
							}
						
						}else{
							
							$tarefas = executaSQLPadrao("tarefa", "arquivo = '".strtolower($arquivo)."'");
							
							//Se tiver mais de uma permissão para a mesma página (Inserir/Alterar)
							if( nLinhas( $tarefas ) > 1 ){
							
								//Se tiver oid (Alteração)
								if( $_GET['id'] > 0 || $_POST['id'] ){
									$tarefas = executaSQLPadrao("tarefa", "arquivo = '".$arquivo."' AND (nome like '%ALTERAR' || nome like '%EDITAR')");
								
								//Senão (Inserção)
								}else{
									$tarefas = executaSQLPadrao("tarefa", "arquivo = '".$arquivo."' AND nome like '%INSERIR' ");
								}
								
							}
						
							//Se não houver nenhum registro
							if( !nLinhas( $tarefas )>0 ){
								return false;
							
							//Se houver 1 registro
							}else{
								$tarefa = objetoPHP( $tarefas );
								//echo executaSQLPadrao("pessoa_perfil p, perfil_tarefa t", "p.oid_pessoa = '".$_SESSION['pessoaId']."' AND t.id_tarefa = '".$tarefa->id."' AND p.id_perfil = t.id_perfil", false); 
								
								//Verifica se tem a tarefa/permissão
								if( nLinhas( executaSQLPadrao("pessoa_perfil p, perfil_tarefa t", "p.id_pessoa = '".$_SESSION['pessoaId']."' AND t.id_tarefa = '".$tarefa->id."' AND p.id_perfil = t.id_perfil") ) > 0 ){
									return true;
								}else{
									return false;
								}
							
							}
						
						}
					
					}
				}else{
					return true;
				}
						
			}
		
		}
		
	}
	
	function verificaAdimplenciaTarefa($arquivo){
		
		$tarefas = executaSQLPadrao("tarefa", "arquivo = '".$arquivo."'");
		if(nLinhas($tarefas)>0){
			
			$reg=objetoPHP($tarefas);
			if($reg->adimplencia==0 || ($reg->adimplencia==1 && ehAdimplente()) ){
				return true;
			}else{
				return false;
			}
			
		}else{
			return true;
		}
		
	}
	
	//Verifica se a Loja é adimplente
	function ehAdimplente(){
		
		if( !$_SESSION['loja_sge']>0 ){
			return true;
		}else{
		
			$regs = executaSQL("SELECT 1 FROM loja WHERE id= '".$_SESSION['loja_sge']."' AND adimplente = 1");	
			if(nLinhas($regs)>0)
				return true;
			else
				return false;
		}
		
	}
	
	//Verifica se o usuário é admin geral
	function ehAdminGeral(){
		$regs = executaSQL("SELECT 1 FROM pessoa_perfil WHERE id_pessoa = '".$_SESSION['pessoaId']."' AND id_perfil = 1");
	
		if(nLinhas($regs)>0)
			return true;
		else
			return false;
	}
	
	//Verifica se o irmão é admin de loja
	function ehAdminLoja($loja=NULL, $pessoa=NULL){
		
		if($loja==NULL)
			$loja = $_SESSION['loja_sge'];
			
		if($pessoa==NULL)
			$pessoa = $_SESSION['pessoaId'];
	/*	
		$regs = executaSQL("SELECT 1 FROM perfil p, pessoa_perfil pp
								WHERE pp.id_pessoa = '".$_SESSION['pessoaId']."'
									AND pp.id_perfil = p.id
									AND p.loja_cod IN (0, '".$loja."')
									AND p.eh_admin=1");
	*/
		if( ehVeneravel($pessoa, $loja) ){
			return true;
		
		}else{
			$regs = executaSQL("SELECT 1 FROM pessoa_perfil pp, loja_acesso la
									WHERE pp.id_pessoa = '".$pessoa."'
										AND pp.id_pessoa = la.id_pessoa
										AND pp.id_perfil = 2
										AND la.loja = '".$loja."'");
			
			if(nLinhas($regs)>0)
				return true;
			else
				return false;
		}
		
	}
	
	//Função para paginação
	function paginacao( $tabela, $condicoes, $registrosPagina=NULL, $order="", $campos="*", $mostrar=false ){
		
		$condicoes[] = "1=1";
		
		/*if( !is_int($registrosPagina) ){
			$registrosPagina = 10;
		}*/
		
		if($campos==NULL)
			$campos = "*";
		
		$paginaAtual = 1;
		if($_GET['pagina'] >0){
			$paginaAtual = $_GET['pagina'];
		}
		
		$sql = "SELECT ".$campos." FROM ".$tabela ." WHERE ". implode(" AND ", $condicoes)." ".$order;
				
	    $qtdeRegistros = nLinhas(executaSQL($sql));
		
		if($registrosPagina>0){
			
			if( $paginaAtual > 1 ){
				$final = $registrosPagina * $paginaAtual;
				$inicial = ( ($paginaAtual-1) * $registrosPagina);	
				$limit = " LIMIT ".$inicial.",".$registrosPagina;		
			}else{
				$limit = " LIMIT ".$registrosPagina;
			}
			
			$sql.= " ". $limit;
		}
				
		if($mostrar) echo $sql;
		
		$registros = executaSQL($sql);
		
		return array( 
						$registros,
						($registrosPagina!=NULL ?  ceil( $qtdeRegistros / $registrosPagina ) : 1 ),
						$qtdeRegistros
					);
	}
	
	function get_paginacao( $qtdePaginas ){
		
		if($_GET['pagina'] == ''){
			$paginaAtual = 1;	
		}else{
			$paginaAtual = $_GET['pagina'];
		}
		
		$sec = $_GET['sec'];
		$modulo = $_GET['modulo'];
		$folder = $_GET['folder'];
		$folder2 = $_GET['folder2'];
		$page = $_GET['page'];
		$id = $_GET['id'];
		
		if($sec != ""){
			$url .= "/".$sec;	
		}
		
		if($modulo != ""){
			$url .= "/".$modulo;	
		}
		
		if($folder != ""){
			$url .= "/".$folder;	
		}
		
		if($folder2 != ""){
			$url .= "/".$folder2;	
		}
		
		if($page != ""){
			$url .= "/".$page;	
		}
		
		if($id != ""){
			$url .= "/".$id;	
		}
		
		?>
        <script>
			$(function(){
				$('#paginacao .void').bind('click', function(){
					var form = $(this).closest("form").attr('id');
					$('#'+form).attr('action', this.href).submit();
				});
			});
		</script>
        
		<div id="paginacao">
        <? ($paginaAtual==1 || $qtdePaginas==0)? $mostraPrimeira = false :$mostraPrimeira = true ?>
        <? if($mostraPrimeira){ ?><a href="<?=$url."/pagina/1"?>" class="void <?=($paginaAtual==1)? 'inativo':''?>" ><? }else{ ?><span class="inativo"><? } ?>
            	<? 
					if($_SESSION['idioma']=='es'){
						echo "Primeira Página";
					}elseif($_SESSION['idioma']=='en'){
						echo "First Page";
					}else{
						echo "Primeira Página";
					}
				?>
        <? if($mostraPrimeira){ ?></a><? }else{ ?></span><? } ?>
                    
		<? ($paginaAtual==1 || $qtdePaginas==0)? $mostraAnterior = false :$mostraAnterior = true ?>
        <? if($mostraAnterior){ ?><a href="<?=$url."/pagina/".($paginaAtual-1)?>" class="void <?=($paginaAtual==1)? 'inativo':''?>" ><? }else{ ?><span class="inativo"><? } ?>
            	<? 
					if($_SESSION['idioma']=='es'){
						echo "Página Anterior";
					}elseif($_SESSION['idioma']=='en'){
						echo "Previous Page";
					}else{
						echo "Página Anterior";
					}
				?>
        <? if($mostraAnterior){ ?></a><? }else{ ?></span><? } ?>
        	         
		 <?
		 
			$pagina_inicial = $paginaAtual - 2;
			if($pagina_inicial<1){
				$pagina_inicial = 1;
			}

			$pagina_final = $paginaAtual + 2;
			if($pagina_final > $qtdePaginas ){
					$pagina_final = $qtdePaginas;
			}

			if ( ($pagina_final - $pagina_inicial) < 4 && $qtdePaginas > 3 && $paginaAtual > 2) {
					$pagina_inicial -= 4 - ( $pagina_final - $pagina_inicial );
					
					if($pagina_inicial<1) $pagina_inicial = 1;
			}

			if ( ($pagina_final - $pagina_inicial) < 4 && $qtdePaginas > 3  && $qtdePaginas != $paginaAtual ) {
					$pagina_final += 4 - ( $pagina_final - $pagina_inicial );
					
					if($pagina_final > $qtdePaginas) $pagina_final = $qtdePaginas;
			}
		
		    for($i = $pagina_inicial; $i <= $pagina_final; $i++){
		?>
            	
            	 <a href="<?=( $paginaAtual == $i )? 'javascript:void(0)': $url."/pagina/".$i?>"  class="void <?=($paginaAtual==$i)? 'ativo':''?>" > <?=$i ?></a> 
				   
		<? }  ?>  
             
            <? ($paginaAtual==$qtdePaginas || $qtdePaginas==0)? $mostraProxima = false :$mostraProxima = true ?>  
            <? if($mostraProxima){ ?><a href="<?=$url."/pagina/".($paginaAtual+1)?>" class="void <?=($paginaAtual==$qtdePaginas)? 'inativo':''?>" ><? }else{?><span class="inativo"><? }?>
             	<? 
					if($_SESSION['idioma']=='es'){
						echo "Próxima Página";
					}elseif($_SESSION['idioma']=='en'){
						echo "Next Page";
					}else{
						echo "Próxima Página";
					}
				?>
         	<? if($mostraProxima){ ?></a><? }else{ ?></span><? }?>
       		
            <? ($paginaAtual==$qtdePaginas || $qtdePaginas==0)? $mostraUltima = false :$mostraUltima = true ?>  	     
            <? if($mostraUltima){ ?><a href="<?=$url."/pagina/".$qtdePaginas?>" class="void  <?=($paginaAtual==$qtdePaginas)? 'inativo':''?>" ><? }else{?><span class="inativo"><? }?>
              		<? 
						if($_SESSION['idioma']=='es'){
							echo "Última Página";
						}elseif($_SESSION['idioma']=='en'){
							echo "Last Page";
						}else{
							echo "Última Página";
						}
					?>
           	<? if($mostraUltima){ ?></a><? }else{ ?></span><? }?>
            
        </div>
        	
         <?
	}
	
	
	function consultaLojaById($idLoja){
		$loja = mysql_query("SELECT * FROM loja WHERE id='".$idLoja."' ");
		if (mysql_num_rows($loja)>0){
			return mysql_fetch_object($loja);
		}else{
			return false;
		} 
	}
	
	
	function consultaLojaTipoById($lojaTipo){
		$tipo = mysql_query("SELECT valor FROM tipoloja WHERE oid='".$lojaTipo."' ");
		if (mysql_num_rows($tipo)>0){
			$tipo = mysql_fetch_object($tipo);
			return $tipo->valor;
		}else{
			return false;
		} 
	}
	
	function consultaLojaDominioByIdLoja($loja){
		$reg = executaSQLPadrao('loja_dominio',"id_loja = ".$loja);
		if(nLinhas($reg)>0){
			return objetoPHP($reg)->dominio;
		}else{
			return NULL;
		}
	}
	
	function getUsuarioById($IdUsu){
		$exe = executaSQL("SELECT * FROM pessoa WHERE id ='".$IdUsu."' ");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getTipoSanguineoById($id){
		$exe = executaSQL("SELECT * FROM pessoa_sangue WHERE id ='".$id."' ");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getNoticiaById($idNot){
		$exe = executaSQL("SELECT * FROM manchete WHERE id ='".$IdNot."' ");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	
	function getNoticiasDestaqueDaLoja($loja){
		$sql = "SELECT destaque FROM manchete WHERE loja = '".$loja."' AND destaque = 1";
		$exe = executaSQL($sql);
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
		
		
	function getNoticiasDaLoja($loja){
		$sql = "SELECT * FROM manchete WHERE loja = '".$loja."' AND destaque = 0";
		$exe = executaSQL($sql);
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaPessoaNomeById($id){
		$reg = executaSQL("SELECT nome FROM pessoa WHERE id='".$id."' ");
		if (nLinhas($reg)>0){
			$reg = objetoPHP($reg);
			return $reg->nome;
		}else{
			return false;
		} 
	}
	
	function consultaPessoaPrimeiroNomeById($id){		
		$reg = executaSQL("SELECT nome FROM pessoa WHERE id='".$id."' ");
		if (nLinhas($reg)>0){
			$reg = objetoPHP($reg);
			$nome = explode(' ', $reg->nome);
			return $nome[0];
		}else{
			return false;
		} 
	}
	
	function consultaPessoaEnderecoByPessoaId($idPessoa){
		$reg = executaSQL("SELECT * FROM endereco WHERE id_pessoa='".$idPessoa."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		} 
	}
	
	function consultaPessoaEnderecoPrincipalByPessoaId($idPessoa){
		$reg = executaSQL("SELECT * FROM endereco WHERE id_pessoa='".$idPessoa."' AND end_principal=1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		} 
	}
	
	function consultaTipoSessaoById($id){
		$reg = executaSQL("SELECT valor FROM presenca_tipo WHERE id='".$id."' ");
		if (nLinhas($reg)>0){
			$reg = objetoPHP($reg);
			return $reg->valor;
		}else{
			return false;
		} 
	}
	
	function consultaPessoaGrauById($id=NULL){
		if($id==NULL) $id = $_SESSION['pessoaId'];
		
		$reg = executaSQL("SELECT id_grau FROM pessoa WHERE id='".$id."' ");
		if (nLinhas($reg)>0){
			$reg = objetoPHP($reg);
			return $reg->id_grau;
		}else{
			return false;
		} 
	}
	
	function consultaPessoaEmailById($id){
		$reg = executaSQL("SELECT email FROM pessoa WHERE id='".$id."' ");
		if (nLinhas($reg)>0){
			$reg = objetoPHP($reg);
			return $reg->email;
		}else{
			return false;
		}
	}
	
	function consultaPessoaCIMById($id){
		$reg = executaSQL("SELECT cim FROM pessoa WHERE id='".$id."' ");
		if (nLinhas($reg)>0){
			$reg = objetoPHP($reg);
			return $reg->cim;
		}else{
			return false;
		} 
	}

	function consultaPessoaByCim($cim){
		$irmao = executaSQLPadrao("pessoa", "cim = '".$cim."'");
		if (nLinhas($irmao)>0){
			return objetoPHP($irmao);
		}else{
			return false;
		} 
	}
	
	function consultaPessoaById($id, $campos=NULL){		
		if(count($campos)>0){
			$pessoa = executaSQL("SELECT ".implode(",", $campos)." FROM pessoa WHERE id='".$id."' ");
		}else{
			$pessoa = executaSQL("SELECT * FROM pessoa WHERE id='".$id."' ");
		}
		if (nLinhas($pessoa)>0){
			return objetoPHP($pessoa);
		}else{
			return false;
		} 
	}
	
	function consultaPessoaTelefoneByIdPessoa($id){
		$pessoaTel = executaSQL("SELECT * FROM pessoa_telefone WHERE id_pessoa='".$id."' ");		
		return $pessoaTel;
	}
	
	function consultaPessoaContatoByIdPessoa($id){
		$pessoaContato = executaSQL("SELECT * FROM pessoa_contato WHERE id_pessoa='".$id."' ORDER BY id_tipo_contato ");		
		return $pessoaContato;
	}
	
	function consultaPessoaContatoByIdPessoaSemEmail($id){
		$pessoaContato = executaSQL("SELECT * FROM pessoa_contato WHERE id_tipo_contato<>1 AND id_pessoa='".$id."' ");		
		return $pessoaContato;
	}
	
	function consultaPessoaDadosProfissionaisByIdPessoa($id){
		$pessoaProf = executaSQL("SELECT * FROM vinculo_profissional WHERE id_pessoa='".$id."' ");		
		return $pessoaProf;
	}
	
	function getTipoContatoById($id){
		$exe = executaSQL("SELECT valor FROM pessoa_tipo_contato WHERE id='".$id."' ");		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		} 
	}
		
	function getSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM pessoa_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getCandidatoById($id, $campos=NULL){		
		if(count($campos)>0){
			$cand = executaSQL("SELECT ".(count($campos)>0 ? implode(",", $campos) : $campos[0])." FROM candidatos WHERE id='".$id."' ");
		}else{
			$cand = executaSQL("SELECT * FROM candidatos WHERE id='".$id."' ");
		}
		if (nLinhas($cand)>0){
			return objetoPHP($cand);
		}else{
			return false;
		} 
	}
	
	function getCandidatoNomeById($id){
		$exe = executaSQL("SELECT nome FROM candidatos WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}
	
	function getCandidatoSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM candidato_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getCandidatoSituacaoDescById($id){
		$exe = executaSQL("SELECT valor FROM candidato_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->desc;
		}else{
			return false;
		}
	}
	
	function getRegularizacaoSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM regularizacao_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function consultaIrmaosFiliadosAtivosDaLoja($loja){
		return executaSQL("SELECT DISTINCT(p.id) AS id, p.nome, i.id_obediencia FROM irmao i, pessoa p
								WHERE i.id_irmao = p.id
									AND i.id_loja <> p.id_loja
									AND (i.data_final IS NULL || i.data_final = '0000-00-00')
									AND p.id_situacao IN(".$_SESSION['POTENCIA']->sit_ativo.")
									AND i.ativo = 1
									AND p.id_tipo = 1
									AND i.id_loja = '".$loja."'
									AND i.id_tipo IN(
														SELECT id FROM irmao_tipo WHERE eh_filiacao = 1 AND cadastro = 1
													)
								ORDER BY p.nome");
	}	
	
	function consultaIrmaosEFiliadosDaLoja($loja, $params="", $situacoes=NULL){
		if($situacoes!=NULL)
			$situacoes = ', '. $situacoes;
		
		return executaSQL("SELECT DISTINCT(p.id) AS id, p.* FROM pessoa p
							WHERE p.id_situacao IN (".$_SESSION['POTENCIA']->sit_ativo.", 21 ".$situacoes.")
								AND p.id_tipo=1
								".$params."
								AND 
								(
									p.id_loja = '".$loja."'
								||
									EXISTS(SELECT 1 FROM irmao i, irmao_tipo t
										 WHERE i.id_irmao = p.id
											AND i.id_loja = '".$loja."'
											AND (i.data_final IS NULL || i.data_final = '0000-00-00')
											AND i.id_tipo = t.id
											AND t.eh_filiacao = 1
											AND t.cadastro = 1
											AND i.ativo = 1
									)
								)
							ORDER BY p.nome");
	}
	
	function consultaIrmaosAtivosDaLojaPrincipal($loja=NULL){
		if($loja==NULL) $loja = $_SESSION['loja_sge'];
		return executaSQL("SELECT * FROM pessoa 
							WHERE id_loja = '".$loja."'
							AND id_situacao IN (".$_SESSION['POTENCIA']->sit_ativo.")
							AND id_tipo = 1								
							ORDER BY nome");
	}
	
	function consultaIrmaosAtivosDaLoja($loja, $campos=NULL, $paramExtra=""){
		if(count($campos)>0){
			$campos = (count($campos)>1 ? implode(",", $campos) : $campos[0]);
		}else{
			$campos = "*";
		}
		
		return executaSQL("SELECT ".$campos." FROM pessoa 
							WHERE id_loja = '".$loja."'
							AND id_situacao IN (".$_SESSION['POTENCIA']->sit_ativo.")
							AND id_tipo = 1
							".$paramExtra."
							ORDER BY nome");
		
	}	
	
	function consultaIrmaosInativosDaLoja($loja){
		return executaSQL("SELECT * FROM pessoa 
							WHERE id_loja = '".$loja."'
							AND id_situacao = 2
							AND id_tipo IN(1,2)								
							ORDER BY nome");
	}
	
	function consultaIrmaosDaLoja($loja){//ativos, inativos, filiados e irmãos
		return executaSQL("SELECT * FROM pessoa 
							WHERE id_loja = '".$loja."'
							AND id_tipo IN(1)
							ORDER BY nome");
	}
	
	function consultaIrmaosAtivos(){
		return executaSQL("SELECT * FROM pessoa 
							WHERE id_situacao IN (".$_SESSION['POTENCIA']->sit_ativo.")
								AND id_tipo = 1								
							ORDER BY nome");
	}
	
	function consultaIrmaosPagamCapitacao(){
		return executaSQL("SELECT * FROM pessoa p, loja l
							WHERE p.id_situacao IN (".$_SESSION['POTENCIA']->sit_ativo.")
								AND p.id_tipo = 1								
								AND p.captacao = 1
								AND p.id_loja > 0
								AND p.id_loja = l.id
								AND l.id_ativa = 1	
							ORDER BY p.nome");
	}
	
	function consultaIrmaosPagamCapitacaoByLoja($loja=NULL){
		if($loja==NULL) $loja = $_SESSION['loja_sge'];
		return executaSQL("SELECT * FROM pessoa 
							WHERE id_situacao IN (".$_SESSION['POTENCIA']->sit_ativo.")
								AND id_loja = '".$loja."'
								AND id_tipo = 1								
								AND captacao = 1
							ORDER BY nome");
	}
	
	function consultaNumIrmaosAtivosRecadastradosDaLoja($loja){
		return executaSQL("SELECT * FROM pessoa 
							WHERE id_loja = '".$loja."'
							AND id_situacao IN (".$_SESSION['POTENCIA']->sit_ativo.")
							AND id_tipo = 1
							AND recadastrado <> ''");
	}
	
	function consultaMesExtensoByNumero($num){
		$exe = executaSQLPadrao('mes', " id='".ceil($num)."' ");	
		if (nLinhas($exe)>0){
			
			return objetoPHP($exe)->valor;
		}else{
			return '';
		} 
	}
	
	function getIrmaoTipoMaconicoSituacao($id){
		$exe = executaSQLPadrao('irmao_tipo', "id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return getSituacaoById(objetoPHP($exe)->id_situacao);
		
		}else{
			return '';
		}
	}
	
	function getIrmaoTipoMaconico($id){
		$exe = executaSQLPadrao('irmao_tipo', "id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return '';
		}
	}
	
	function getLicencaMotivoById($id){
		$exe = executaSQLPadrao('licenca_motivo', "id='".$id."'");	
		if (nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return '';
		}
	}
	
	function getCondecoracaoById($id){
		$exe = executaSQLPadrao('condecoracao_item', "id='".$id."'");	
		if (nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return '';
		}
	}
	
	function getGrauById($grau){
		$exe = executaSQLPadrao('grau', " id ='".$grau."' ");	
		if (nLinhas($exe)>0){
			
			return objetoPHP($exe)->valor;
		}else{
			return '';
		}
	}
	
	function getGrauById2($grau){
		$exe = executaSQLPadrao('grau', " id ='".$grau."' ");	
		if (nLinhas($exe)>0){
			
			return objetoPHP($exe);
		}else{
			return '';
		}
	}
	
	function consultaPrimeiroNomeByOid ($userOid){
		$reg = mysql_query("SELECT * FROM pessoa WHERE oid='$userOid' ");
		if (mysql_num_rows($reg)>0){
			$reg = mysql_fetch_object($reg);
			$reg = explode(' ', $reg->nome);
			return $reg[0]; 
		}else{
			return false;
		} 
	}
	
	
	function getSolicitacaoSituacao($id){
		$exe = executaSQL("SELECT * FROM solicitacao_situacao WHERE id ='".$id."' ");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	
	function verificaAcessoFilhos($idPai){
		
		$regs = executaSQL("SELECT id, id_tipo, grau, id_conteudo FROM menu_item 
								WHERE id_pai='".$idPai."'
								ORDER BY ordem");
		
		if(nLinhas($regs)>0){
			while($reg=objetoPHP($regs)){
				
				//Se for Link externo
				if( $reg->id_tipo==1 ){
					
					if($reg->grau<=$_SESSION['usuarioGrau']){
						return true;
					}
				
				//Página criada na módulo de conteúdo	
				}elseif($reg->id_tipo==2){
					
					//Verifica as permissões o conteúdo
					if(verificaAcessoConteudo($reg->id_conteudo)){
						return true;
					}
				
				}elseif($reg->id_tipo==3){
					//Verificar se tem algum 'Filho visível'
					if( verificaAcessoFilhos($reg->id) ){
						return true;
					}
					
				}
			}
			
		}
		
		return false;
		
	}
	
	function temFilhos($idPai){
		$regs = executaSQL("SELECT 1 FROM menu_item WHERE id_pai='".$idPai."'");
		
		if(nLinhas($regs)>0){
			return true;
		}else
			return false;
	}
	
	function verificaAcessoConteudo($idConteudo){
		$regs = executaSQL("SELECT id_visibilidade, grau, ativo FROM conteudo WHERE id = '".$idConteudo."'");
		
		if(nLinhas($regs)>0){
			$reg = objetoPHP($regs);
			
			if( $reg->ativo==1
				&& ( 
						in_array($reg->id_visibilidade, array(1, 3)) 
					|| 
						($_SESSION['active'] && $reg->id_visibilidade==2 && $reg->grau<=$_SESSION['usuarioGrau'] )
					)
			){
				return true;
			}
		}
		
		return false;
	}
	
	function verificaPaginaLinkPublico(){
		
		//VERIFICA SE TEM PÁGINA PERSONALIZADA PÚBLICA
		$regs = executaSQL("SELECT 1 FROM conteudo c, menu_item mi 
								WHERE c.loja_cod = '".$_SESSION['loja_sge']."' 
								AND c.id_visibilidade IN(1,3)
								AND c.ativo=1
								AND mi.id_tipo=2
								AND mi.id_menu=2
								AND mi.id_conteudo=c.id");
		
		if(nLinhas($regs)>0){
			return true;			
		}
		
		//VERIFICA SE TEM LINK EXTERNO
		$regs = executaSQL("SELECT 1 FROM menu_item
								WHERE loja_cod = '".$_SESSION['loja_sge']."' 
								AND id_tipo=1
								AND grau<='".$_SESSION['usuarioGrau']."'");
		
		if(nLinhas($regs)>0){
			return true;
		}
		
		return false;
		
	}
	
	function getPaginaById($id){
		$regs = executaSQLPadrao("conteudo", "id = '".$id."' AND ativo=1");
		
		if(nLinhas($regs)>0){
			return objetoPHP($regs);
		}else{
			return false;
		}
	}
	
	function getTextoTamanho($string){
		if($string!=''){
			return mb_strlen($string, 'ASCII');
		}else{
			return 0;
		}
	}
	
	function getTamanhoFotosAlbum($idAlbum){		
		$exe = executaSQL("SELECT SUM(tamanho) as total FROM album_fotos WHERE id_album = '".$idAlbum."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->total;
		}else{
			return 0;
		}
	}
	
	function getTamanhoComentariosSolicitacao($idSol){		
		$exe = executaSQL("SELECT SUM(size_conteudo) as total FROM solicitacao_comentario WHERE id_solicitacao = '".$idSol."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->total;
		}else{
			return 0;
		}
	}
	
	function getTamanhoConfirmacaoEventos($idEve){
		$exe = executaSQL("SELECT SUM(size_conteudo) as total FROM evento_confirmacao WHERE id_evento = '".$idEve."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->total;
		}else{
			return 0;
		}
	}
	
	function getPaginaByNome($nome){	
		$regs = executaSQLPadrao("conteudo", "pagina_nome = '".$nome."' AND ativo=1 AND loja_cod='".$_SESSION['loja_sge']."'");
		
		if(nLinhas($regs)>0){
			return objetoPHP($regs);
		}else{
			return false;
		}
	}
	
	function getTipoDocDiversos($id){
		$exe = executaSQL("SELECT * FROM documento_diversos_tipo WHERE id = '".$id."' AND id_loja = '".$_SESSION['loja_sge']."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getPosicaoPublicidade($id){
		$exe = executaSQL("SELECT * FROM banner_posicao WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getImagemTamanho($caminho){		
		if(is_file($caminho)){
			return filesize($caminho);
		}else{
			return 0;
		}
	}
	
	function getPerfilNome($id){
		$exe = executaSQL("SELECT nome FROM perfil WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}
	
	function consultaPerfisByIdPessoa($id){
		$exe = executaSQL("SELECT pe.nome FROM pessoa p, perfil pe, pessoa_perfil l
								WHERE p.id = '".$id."'
									AND p.id = l.id_pessoa
									AND l.id_perfil  = pe.id");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaPerfisNomeByIdPessoa($id){
		$exe = executaSQL("SELECT pe.nome FROM pessoa p, perfil pe, pessoa_perfil l
								WHERE p.id = '".$id."'
									AND p.id = l.id_pessoa
									AND l.id_perfil  = pe.id
								ORDER BY pe.nome");
		
		if(nLinhas($exe)>0){
			$dados = array();
			while($reg = objetoPHP($exe)){
				$dados[] = $reg->nome;
			}
			
			return $dados;
		}else{
			return array(NULL);
		}
	}
	
	function consultaPerfisIdByIdPessoa($id){
		$exe = executaSQL("SELECT pe.id FROM pessoa p, perfil pe, pessoa_perfil l
								WHERE p.id = '".$id."'
									AND p.id = l.id_pessoa
									AND l.id_perfil  = pe.id");
		
		if(nLinhas($exe)>0){
			$dados = array();
			while($reg = objetoPHP($exe)){
				$dados[] = $reg->id;
			}
			
			return $dados;
		}else{
			return array(NULL);
		}
	}
	
	function getVeneravelByGestaoId($id){
		$exe = executaSQL("SELECT p.nome FROM pessoa p, gestao_loja_cargos g, cargos c
								WHERE id_gestao = '".$id."'
									AND g.id_cargo = c.id
									AND c.exclui = 0 
									AND p.id = g.irmao
								ORDER BY g.ativo");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}
	
	function ehVeneravel($idIrmao=NULL, $idLoja=NULL){
		if($idLoja==NULL)
			$idLoja = $_SESSION['loja_sge'];
			
		if($idIrmao==NULL)
			$idIrmao = $_SESSION['pessoaId'];
		
		$regs = executaSQL("SELECT 1 FROM gestao_loja_cargos lc, gestao_loja g
								WHERE lc.id_irmao = '".$idIrmao."'
									AND lc.id_gestao = g.id
									AND '".date('Y-m-d')."' BETWEEN g.dt_inicio AND g.dt_fim
									AND g.id_loja = '".$idLoja."'
									AND lc.id_cargo = 1
									AND lc.ativo = 1");
		if(nLinhas($regs)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getVeneravelCargoNome(){
		$exe = executaSQL("SELECT valor FROM cargos c WHERE exclui = 0 AND loja = '".$_SESSION['loja_sge']."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	/* ----------- FINANCEIRO -------------- */
	
	function getNomeBanco($id){
		$exe = executaSQL("SELECT valor FROM banco WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getSituacaoBoleto($id){
		$reg = mysql_query("SELECT * FROM boleto_situacao WHERE id='".$id."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg); 
		}else{
			return false;
		} 
	}
	
	function getContaBancaria($id){
		$reg = mysql_query("SELECT * FROM conta_bancaria WHERE id='".$id."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg); 
		}else{
			return false;
		}
	}
	
	function getBoleto($id){
		$reg = mysql_query("SELECT * FROM boleto WHERE id='".$id."'");		
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg); 
		}else{
			return false;
		}
	}
	
	function getNumeroBoletoById($id, $loja){
		$reg = mysql_query("SELECT numero FROM boleto WHERE id='".$id."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->numero; 
		}else{
			return false;
		}
	}
	
	function getIrmaoNomeByIrmaoOid($id){
		$reg = mysql_query("SELECT p.nome FROM tab_irmao i, pessoa p WHERE irmao_oid='".$id."' AND p.cim = i.irmao_cim");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->nome; 
		}else{
			return false;
		}
	}
	
	function getEmailByPessoaId($id){
		$reg = mysql_query("SELECT p.email FROM pessoa p WHERE id='".$id."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->email; 
		}else{
			return false;
		}
	}

	
	function getIrmaoEnderecoByIrmaoCim($cim){
		$reg = mysql_query("SELECT e.* FROM tab_irmao i, pessoa p, endereco e
							WHERE i.irmao_cim='".$cim."'
							AND p.cim = i.irmao_cim
							AND p.oid_endereco = e.oid");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg); 
		}else{
			return false;
		}
	}
	
	function getMunicipioNomeById($id){
		$reg = mysql_query("SELECT nome FROM municipio WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->nome; 
		}else{
			return false;
		}
	}
	
	function getEstadoNomeByIdCidade($id){
		$reg = mysql_query("SELECT e.nome FROM estado e, municipio m 
							WHERE m.id='".$id."' 
							AND m.id_estado = e.id");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function getEstadoSiglaByIdCidade($id){
		$reg = mysql_query("SELECT e.sigla FROM estado e, municipio m 
							WHERE m.id='".$id."' 
							AND m.id_estado = e.id");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->sigla;
		}else{
			return false;
		}
	}
	
	function getEstadoSiglaById($id){
		$reg = mysql_query("SELECT sigla FROM estado WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->sigla; 
		}else{
			return false;
		}
	}
	
	function getEstadoNomeById($id){
		$reg = mysql_query("SELECT nome FROM estado WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->nome; 
		}else{
			return false;
		}
	}
	
	function getSituacaoLancamentoById($id){
		$reg = mysql_query("SELECT valor FROM lancamento_situacao WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getTipoDebitoPagtoById($id){
		$reg = mysql_query("SELECT valor FROM debito_tipo_pagto WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getTipoCreditoPagtoById($id){
		$reg = mysql_query("SELECT valor FROM credito_tipo_pagto WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getProvedorById($id){
		$reg = mysql_query("SELECT * FROM provedor WHERE id='".$id."'");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}
	
	function getProvedorNomeById($id){
		$reg = mysql_query("SELECT nome FROM provedor WHERE id='".$id."'");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function getHistoricoOperacaoNomeById($id){
		$reg = mysql_query("SELECT valor FROM conta_corrente_historico_operacao WHERE id='".$id."'");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getCodLancamento($idConta){
		
		$exeConta = executaSQL("SELECT cod_lancamento FROM conta_bancaria WHERE id = '".$idConta."'");
		if(nLinhas($exeConta)>0){
			
			$codLanc = objetoPHP($exeConta)->cod_lancamento + 1;
			
			//ALTERA O CODIGO DO LANÇAMENTO NA CONTA BANCARIA
			alterarDados("conta_bancaria", array('cod_lancamento'=>$codLanc), "id = '".$idConta."'");
			return $codLanc;
		}

	}
	
	function getInfoContaBancariaBoleto(){
		
		$exeConta = executaSQL("SELECT * FROM conta_bancaria WHERE tem_boleto=1 AND ativo=1 LIMIT 1");
		if(nLinhas($exeConta)>0){
			$reg=objetoPHP($exeConta);
			$numero = ($reg->boleto_numero + 1);
			
			//ALTERA O NUMERO DO BOLETO NA CONTA BANCARIA
			alterarDados("conta_bancaria", array('boleto_numero'=>$numero), "id = '".$reg->id."'");
			return $reg;
		}else{
			return false;
		}
	}
	
	function getSituacaoContaCorrenteById($id){
		$reg = mysql_query("SELECT valor FROM conta_corrente_situacao WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getSiglaSituacaoContaCorrenteById($id){
		$reg = mysql_query("SELECT sigla FROM conta_corrente_situacao WHERE id='".$id."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->sigla;
		}else{
			return false;
		}
	}
	
	function getCorrentistaSaldoInicial( $dataIni, $idCorrentista, $tipoCorrentista ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente
							WHERE id_correntista = '".$idCorrentista."'
							AND id_situacao NOT IN(1)
							AND id_situacao_lancamento IN(1,2)
							AND id_tipo='".$tipoCorrentista."'
							AND tipo_cc=1
							AND dt_pagamento < '".$dataIni."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getIrmaoSaldoInicial( $dataIni, $idCorrentista ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente
							WHERE id_correntista = '".$idCorrentista."'
							AND id_situacao NOT IN(1)
							AND id_situacao_lancamento IN(1,2)
							AND id_tipo=2
							AND tipo_cc=1
							AND dt_pagamento < '".$dataIni."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getIrmaoSaldoAteHoje($irmao ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente 
							WHERE id_irmao = '".$irmao."'
							AND id_situacao_lancamento NOT IN(3)
							AND id_tipo=2
							AND tipo_cc=1
							AND dt_pagamento <= '".date('Y-m-d')."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getLojaSaldoInicial( $dataIni, $idLoja ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente 
							WHERE id_correntista = '".$idLoja."'
							AND id_situacao NOT IN(1)
							AND id_situacao_lancamento IN(1,2)
							AND id_tipo = 1
							AND tipo_cc=1
							AND dt_pagamento < '".$dataIni."'");		
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getLojaSaldoAteData( $data, $idLoja ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente 
							WHERE id_correntista = '".$idLoja."'
							AND id_situacao NOT IN(1)
							AND id_situacao_lancamento IN(1,2)
							AND id_tipo = 1
							AND tipo_cc=1
							AND dt_pagamento <= '".$data."'");		
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getProvedorSaldoInicial( $dataIni, $idCorrentista ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente
							WHERE id_correntista = '".$idCorrentista."'
							AND id_situacao NOT IN(1)
							AND id_situacao_lancamento IN(1,2)
							AND id_tipo=3
							AND tipo_cc=1
							AND dt_pagamento < '".$dataIni."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getProvedorSaldoAteHoje($irmao ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente 
							WHERE id_irmao = '".$irmao."'
							AND id_situacao_lancamento NOT IN(3)
							AND id_tipo=3
							AND tipo_cc=1
							AND dt_pagamento <= '".date('Y-m-d')."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	//PEGA AS ENTRADAS (CRÉDITOS) PARA A LOJS DENTRO DE UM PERIODO
	function getLojaEntradasPeriodos( $idLoja, $data_i, $data_f ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente 
							WHERE id_correntista = '".$idLoja."'
							AND id_tipo=1
							AND id_situacao = 2
							AND id_situacao_lancamento = 2
							AND dt_pagamento BETWEEN '".$data_i."' AND '".$data_f."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	//PEGA AS SAÍDAS (DÉBITOS) PARA A LOJS DENTRO DE UM PERIODO
	function getLojaSaidasPeriodos( $idLoja, $data_i, $data_f ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto) as saldo FROM conta_corrente
							WHERE id_correntista = '".$idLoja."'
							AND id_tipo=1
							AND id_situacao = 3
							AND id_situacao_lancamento = 2
							AND dt_pagamento BETWEEN '".$data_i."' AND '".$data_f."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getRecFinanSaldoInicial( $dataIni, $idRecFinan ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto+multa_juros) as saldo FROM conta_corrente 
							WHERE id_conta = '".$idRecFinan."'
							AND id_situacao_lancamento NOT IN(3)
							AND id_situacao IN(2,3)
							AND dt_pagamento < '".$dataIni."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getRecFinanSaldoAteData( $data, $id){
				
		$reg = executaSQL("SELECT SUM(valor_pagto+multa_juros) as saldo FROM conta_corrente 
							WHERE id_conta = '".$id."'
							AND id_situacao NOT IN(1)
							AND id_situacao_lancamento IN(1,2)
							AND dt_pagamento <= '".$data."'");		
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	//PEGA AS ENTRADAS (CRÉDITOS) PARA A LOJS DENTRO DE UM PERIODO
	function getRecFinanEntradasPeriodos( $id, $data_i, $data_f ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto+multa_juros) as saldo FROM conta_corrente 
							WHERE id_conta = '".$id."'
							AND id_situacao = 2
							AND id_situacao_lancamento = 2
							AND dt_pagamento BETWEEN '".$data_i."' AND '".$data_f."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	//PEGA AS SAÍDAS (DÉBITOS) PARA A LOJS DENTRO DE UM PERIODO
	function getRecFinanSaidasPeriodos( $id, $data_i, $data_f ){
				
		$reg = executaSQL("SELECT SUM(valor_pagto+multa_juros) as saldo FROM conta_corrente
							WHERE id_conta = '".$id."'
							AND id_situacao=3
							AND id_situacao_lancamento = 2
							AND dt_pagamento BETWEEN '".$data_i."' AND '".$data_f."'");
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getTotalContasPagar( $condicoes="1=1" ){//VALOR TOTAL DE TODAS AS CONTAS A PAGAR
				
		$reg = executaSQL("SELECT SUM(valor) as saldo FROM conta_corrente WHERE ".implode(" AND ", $condicoes));
		if (mysql_num_rows($reg)>0){
			return -1*objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getTotalContasPagarAberto( $condicoes="1=1" ){//CONTAS A PAGAR QUE ESTÃO EM ABERTO

		$reg = executaSQL("SELECT SUM(valor) as saldo FROM conta_corrente WHERE ".implode(" AND ", $condicoes)." AND id_situacao_lancamento='1'");
		if (mysql_num_rows($reg)>0){
			return -1*objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function getTotalContasPagarPago( $condicoes="1=1" ){//CONTAS A PAGAR QUE JA FORAM PAGOS
	
		$reg = executaSQL("SELECT SUM(valor_pagto + multa_juros) as saldo FROM conta_corrente WHERE ".implode(" AND ", $condicoes)." AND id_situacao_lancamento='2'");
		if (mysql_num_rows($reg)>0){
			return -1*objetoPHP($reg)->saldo;
		}else{
			return false;
		}
	}
	
	function temBoletoRecursoFinanLoja(){
		$reg = executaSQL("SELECT 1 FROM conta_bancaria WHERE loja_cod='".$_SESSION['loja_sge']."' AND tem_boleto='1' AND ativo='1'");
		if (nLinhas($reg)>0){
			return true;
		}else{
			$reg = executaSQL("SELECT 1 FROM boleto WHERE loja_cod = '".$_SESSION['loja_sge']."'");
			if (nLinhas($reg)>0){
				return true; 
			}else{
				return false;
			}
		}
	}
	
	function temBoletoRecursoFinanById($idRecurso){
		$reg = executaSQL("SELECT * FROM conta_bancaria WHERE id='".$idRecurso."' AND tem_boleto='1' AND ativo='1'");
		if (nLinhas($reg)>0){
			return true; 
		}else{
			return false;
		}
	}
	
	function isRecursoFinanceiroAtivo($idRecurso){
		$reg = executaSQL("SELECT 1 FROM conta_bancaria WHERE id='".$idRecurso."' AND ativo='1'");
		if (mysql_num_rows($reg)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getCodInternoRecurso($idRecurso){
		$reg = mysql_query("SELECT cod_interno FROM conta_bancaria WHERE id='".$idRecurso."' ");
							
		if (mysql_num_rows($reg)>0){
			return objetoPHP($reg)->cod_interno;
		}else{
			return false;
		}
	}
	
	function getRecursoCodInternoLoja(){
		
		$exe= executaSQL("SELECT id, recurso_cod_interno FROM loja_config WHERE id_loja= '".$_SESSION['loja_sge']."'");
		if(nLinhas($exe)>0){
			
			$reg = objetoPHP($exe);
			
			$dados['recurso_cod_interno'] = $reg->recurso_cod_interno + 1;
			alterarDados("loja_config", $dados, "id = '".$reg->id."'");
			return $dados['recurso_cod_interno'];
		}

	}
	
	

	function isIrmaoPermissaoDiscussao($id_lista){
		$reg = executaSQL("SELECT * FROM lista WHERE id='".$id_lista."' AND loja_cod='".$_SESSION['loja_sge']."'");
		if (nLinhas($reg)>0){
			
			$lista = objetoPHP($reg);
			
			if($lista->id_pessoa_abertura==$_SESSION['usuarioId']){//VERIFICA SE O IRMÃO ABRIU A DISCUSSÃO
				return true;
			}else{
				
				if($lista->id_tipo==1){//GRAU
				
					$coluna = "grau".$_SESSION["usuarioGrau"];
					if($lista->$coluna==1){//VERIFICA SE O GRAU DO IRMÃO TEM PERMISSÃO PARA VER A DISCUSSAO
						return true;
					}else{
						return false;
					}
				
				}else{//IRMÃO ESPECIFICO
					$exeIrmao = executaSQL("SELECT 1 FROM lista_irmao WHERE id_lista='".$lista->id."' AND id_pessoa='".$_SESSION['usuarioId']."'");
					if(nLinhas($exeIrmao)>0){//VERIFICA SE O IRMÃO TEM PERMISSAO PARA VER A DISCUSSÃO
						return true;
					}else{
						return false;
					}
				}
					
			}
			
		}else{
			return false;
		}
	}
	
	function isListaAtiva($idLista){
		$reg = executaSQL("SELECT 1 FROM lista WHERE id='".$idLista."' AND ativa='1' ");
		if (nLinhas($reg)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getIdUltimoComentarioLista($idLista, $idPai){
		$reg = executaSQL("SELECT id FROM lista_discussao WHERE id_lista='".$idLista."' AND id_pai='".$idPai."' ORDER BY id DESC LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->id;
		}else{
			return 0;
		}
	}
	
	//FUNÇÃO UTILIZADA PARA VERIFICAR SE HÁ NOVOS COMENTARIOS QUE NÃO SÃO DO IRMÃO
	function verificaComentarioIrmao($idLista, $idPai, $idComentario, $idPessoa){
		$reg = executaSQL("SELECT 1 FROM lista_discussao WHERE id>'".$idComentario."' AND id_pai='".$idPai."' AND id_lista='".$idLista."' AND id_pessoa!='".$idPessoa."'");
		if (nLinhas($reg)>0){
			return true;
		}else{
			return false;
		}
	}
	function getIdPessoaUltimoComentarioLista($idLista, $idPai){
		$reg = executaSQL("SELECT id_pessoa FROM lista_discussao WHERE id_lista='".$idLista."' AND id_pai='".$idPai."' ORDER BY id DESC LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->id_pessoa;
		}else{
			return 0;
		}
	}
	
	
/*---------------*/
	
	function getEmailPrimeiroAdmin($id_loja){
		$reg = executaSQL("SELECT p.email FROM pessoa p, pessoa_perfil pp, perfil ppp 
							WHERE p.id_loja='".$id_loja."'
							AND p.id = pp.id_pessoa
							AND pp.id_perfil = ppp.id
							AND ppp.eh_admin = 1
							LIMIT 1");
		if(nLinhas($reg)>0){			
			return objetoPHP($reg)->email;
		}else{
			return false;
		}
	}
	
	function getEmailAdmin($id_loja){
		$reg = executaSQL("SELECT p.email FROM pessoa p, pessoa_perfil pp, perfil ppp 
							WHERE p.id_loja='".$id_loja."'
							AND p.id = pp.id_pessoa
							AND pp.id_perfil = ppp.id
							AND ppp.eh_admin = 1");
		if(nLinhas($reg)>0){
			if(nLinhas($reg)>1){
				$email = array();
				while($emailPessoa = objetoPHP($reg)){
					$email[] = $emailPessoa->email;
				}
			}else{
				$emailPessoa = objetoPHP($reg);
				$email = $emailPessoa->email;
			}
			return $email;
		}else{
			return false;
		}
	}
	
	function getDestinatarioPadrao(){
		if($_SESSION['lojaDados']->email!=''){
			$destinatario = $_SESSION['lojaDados']->email;
			
		}elseif(getEmailAdmin($_SESSION['loja_sge'])){
			
			$emailAdmin = getEmailAdmin($_SESSION['loja_sge']);
			
			$destinatario = $emailAdmin;
			if(is_array($emailAdmin)){
				$destinatario = $emailAdmin[0];	
			}
			
		}else{
			$destinatario = "'".$_SESSION['sig-mail-nao-resp']."'";
		}
		return $destinatario;
	}
	
	function getPotenciaById($id){
		$reg = executaSQL("SELECT * FROM potencia WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}	
	}
	
	function getPotenciaNomeById($id){
		$reg = executaSQL("SELECT nome FROM potencia WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}	
	}
	
	function getPotenciaSiglaById($id){
		$reg = executaSQL("SELECT sigla FROM potencia WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->sigla;
		}else{
			return false;
		}	
	}
	
	function getPotenciaLojaById($id){
		$reg = executaSQL("SELECT valor FROM potencia_loja WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}	
	}
	
	function getPotenciaSiglaLojaById($id){
		$reg = executaSQL("SELECT sigla FROM potencia WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->sigla;
		}else{
			return false;
		}	
	}
	
	function getLojaForaById($id){
		$reg = executaSQL("SELECT * FROM loja_fora WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}
	
	function getLojaFromLojaById($id){
		$reg = executaSQL("SELECT nome FROM loja_fora WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function getRitoById($id){
	
		$reg = executaSQL("SELECT nome FROM rito WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}	
	}
	
	function getRitoAbreviaturaById($id){
	
		$reg = executaSQL("SELECT abrevia FROM rito WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->abrevia;
		}else{
			return false;
		}	
	}
	
	function getpaisById($id){			
		$reg = executaSQL("SELECT nome FROM pais WHERE id='".$id."' ORDER BY nome");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function getMuniciopioById($id){			
		$reg = executaSQL("SELECT nome FROM municipio WHERE id='".$id."' ORDER BY nome");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function getMuniciopioDadosById($id){
		$reg = executaSQL("SELECT * FROM municipio WHERE id='".$id."' ORDER BY nome");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}
	
	function getEstadoSiglaByMuniciopioId($id){			
		$reg = executaSQL("SELECT e.sigla FROM municipio m, estado e 
							WHERE m.id='".$id."'
								AND m.id_estado = e.id");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->sigla;
		}else{
			return false;
		}
	}
	
	function getEstadoIdByMuniciopioId($id){			
		$reg = executaSQL("SELECT e.id FROM municipio m, estado e 
							WHERE m.id='".$id."'
								AND m.id_estado = e.id");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->id;
		}else{
			return false;
		}
	}
	
	function getDiaSemanaById($id){	
		$reg = executaSQL("SELECT valor FROM dia_semana WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getPeriodicidadeById($id){	
		$reg = executaSQL("SELECT valor FROM loja_periodicidade WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getGrauInstrucaoById($id){
		$reg = executaSQL("SELECT valor FROM grau_instrucao WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getParentescoById($id){
		$reg = executaSQL("SELECT valor FROM relacao_tipo WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getTipoTelefoneoById($id){
		$reg = executaSQL("SELECT valor FROM pessoa_tipo_telefone WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getOperadoraById($id){
		$reg = executaSQL("SELECT valor FROM operadora WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getPessoaSituacaoById($id){
		$reg = executaSQL("SELECT valor FROM pessoa_situacao WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function isIrmaoDaLoja($idIrmao=NULL, $active=1){
		
		if($idIrmao==NULL || $idIrmao==""){
			$idIrmao = $_SESSION['usuarioId'];
		}
		if($active){
			
			//BUSCA A PESSOA ATIVA E QUE SEJA IRMAO DA LOJA OU DE OUTRA LOJA/OBEDIENCIA
			$reg = executaSQL("SELECT 1 FROM pessoa WHERE id='".$idIrmao."' AND id_loja='".$_SESSION['loja_sge']."' AND id_situacao=1 AND id_tipo IN(1,2)");
			if(nLinhas($reg)>0){
				return true;
			}else{
				return false;
			}
			
		}else{
			return true;
		}
	}
	
	function getEbibliotecaArea($id){
		$reg = executaSQL("SELECT valor FROM artigo_area WHERE id='".$id."' AND id_loja='".$_SESSION['loja_sge']."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getCargoVinculoProfissional($id){
		$reg = executaSQL("SELECT valor FROM vinculo_profissional_cargos WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function consultaTelefoneById($id){
		$reg = executaSQL("SELECT * FROM pessoa_telefone WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}
	
	function getOperadoraTelefoneById($id){
		$reg = executaSQL("SELECT valor FROM operadora WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getTipoTelefoneById($id){
		$reg = executaSQL("SELECT valor FROM pessoa_tipo_telefone WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getTipoDocumentoById($id){
		$reg = executaSQL("SELECT valor FROM pessoa_tipo_documento WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getPessoaDocumentoPadraoByIdPessoa($id){
		$reg = executaSQL("SELECT numero FROM pessoa_documento WHERE id_pessoa='".$id."' AND id_tipo='".get_option_value(176)."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->numero;
		}else{
			return false;
		}
	}
	
	function getPaisById2($id){
		$reg = executaSQL("SELECT nome FROM pais WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	//VERIFICA SE TEM DOCUMENTOS REGISTRADOS PARA A LOJA E SE O IRMÃO É AUTORIZADO A VISUALIZAR AS CATEGORIAS
	function temDocumentos(){		
	
		$exe = executaSQL("SELECT * FROM documento_diversos
								WHERE id_loja = '".$_SESSION['loja_sge']."'");
		if(nLinhas($exe)>0){
			
			if( ehAdminLoja() ){
				return true;
			}
			
			while($reg=objetoPHP($exe)){
				
				//BUSCA A CATEGORIA DO DOCUMENTO
				$exed = executaSQL("SELECT * FROM documento_diversos_tipo 
									WHERE id = '".$reg->id_tipo."'
									AND id_loja = '".$_SESSION['loja_sge']."'");
				if(nLinhas($exed)>0){
					$tipo = objetoPHP($exed);
					
					$coluna = "grau".$_SESSION["usuarioGrau"];
					if($tipo->tipo==1 && $tipo->$coluna==1){//SE FOR POR GRAU E O GRAU DO IRMAO ESTÁ INCLUSO
						return true;
					}
					
					if($tipo->tipo==2){//SE FOR POR IRMÃO ESPECIFICO, CONSULTA A TABELA E VERIFICA SE O IRMAO ESTÁ INCLUSO
						$exeIr=executaSQL("SELECT 1 FROM documento_diversos_tipo_irmao 
											WHERE id_documento_tipo='".$tipo->id."'
											AND id_pessoa = '".$_SESSION['pessoaId']."'");
						if(nLinhas($exeIr)>0){
							return true;
						}
					}
				}
			}
		}
		
		return false;
		
	}
	
	//VERIFICA SE O IRMÃO ESTÁ AUTORIZADO A VISUALIZAR OS DOCUMENTOS DA CATEGORIA
	function verficaCategoriaDocumentosIrmao($id){
		$retorno = false;
		//BUSCA A CATEGORIA DO DOCUMENTO
		$exed = executaSQL("SELECT * FROM documento_diversos_tipo 
							WHERE id = '".$id."'
							AND id_loja = '".$_SESSION['loja_sge']."'");
		if(nLinhas($exed)>0){
			if( ehAdminLoja() ){
				$retorno = true;
			}
			
			$tipo = objetoPHP($exed);
			
			$coluna = "grau".$_SESSION["usuarioGrau"];
			if($tipo->tipo==1 && $tipo->$coluna==1){//SE FOR POR GRAU E O GRAU DO IRMAO ESTÁ INCLUSO
				$retorno =  true;
			}
			
			if($tipo->tipo==2){//SE FOR POR IRMÃO ESPECIFICO, CONSULTA A TABELA E VERIFICA SE O IRMAO ESTÁ INCLUSO
				$exeIr=executaSQL("SELECT 1 FROM documento_diversos_tipo_irmao 
									WHERE id_documento_tipo='".$tipo->id."'
									AND id_pessoa = '".$_SESSION['pessoaId']."'");
				if(nLinhas($exeIr)>0){
					$retorno =  true;
				}
			}
		}
		return $retorno;
	}
	
	function getJornalNome($id){
		$reg = executaSQL("SELECT nome FROM jornal WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function temJornalEdicao(){
		$reg = executaSQL("SELECT 1 FROM jornal_edicao");
		if (nLinhas($reg)>0){
			return true;
		}else{
			return false;
		}
	}	
	
	function temMensagemGrao($active){
		$param = (!$active ? ' WHERE restrito=2 ' : '' );
		//return "SELECT 1 FROM mensagem_grao ".$param;
		$reg = executaSQL("SELECT 1 FROM mensagem_grao ".$param);
		if (nLinhas($reg)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function temConvite(){
		$reg = executaSQL("SELECT 1 FROM convite WHERE dt_fim >= '".date('Y-m-d')."'");
		if (nLinhas($reg)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getPrimeiraFotoThumbAlbum($id){
		$reg = executaSQL("SELECT thumb FROM album_fotos WHERE id_album='".$id."' ORDER BY id LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->thumb;
		}else{
			return false;
		}
	}
	
	function getNrAprendizLoja($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao IN(1,9)
							AND id_grau=1");
		return nLinhas($reg);
	}
	
	function getNrCompanheiroLoja($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao IN(1,9)
							AND id_grau=2");
		return nLinhas($reg);
	}
	
	function getNrMestreLoja($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao IN(1,9)
							AND id_grau=3");
		return nLinhas($reg);
	}
	
	function getNrMestreInstaladoLoja($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao IN(1,9)
							AND id_grau=4");		
		return nLinhas($reg);		
	}
	
	function getNrAprendizLojaNRegulares($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao NOT IN(1,9)
							AND id_grau=1");
		return nLinhas($reg);
	}
	
	function getNrCompanheiroLojaNRegulares($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao NOT IN(1,9)
							AND id_grau=2");
		return nLinhas($reg);
	}
	
	function getNrMestreLojaNRegulares($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao NOT IN(1,9)
							AND id_grau=3");
		return nLinhas($reg);
	}
	
	function getNrMestreInstaladoLojaNRegulares($idLoja){
		$reg = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$idLoja."' 							
							AND id_tipo=1
							AND id_situacao NOT IN(1,9)
							AND id_grau=4");		
		return nLinhas($reg);		
	}
	
	function getNomePaiByIdPessoa($idPessoa){
		$reg = executaSQL("SELECT f.nome FROM pessoa f, relacao r
								WHERE r.id_pessoa_pai = '".$idPessoa."'
								  AND r.id_tipo=1
								  AND f.id_sexo=1
								  AND r.id_pessoa_filho = f.id
								  LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function getNomeMaeByIdPessoa($idPessoa){
		$reg = executaSQL("SELECT f.nome FROM pessoa f, relacao r
								WHERE r.id_pessoa_pai = '".$idPessoa."'
								  AND r.id_tipo=1
								  AND f.id_sexo=2
								  AND r.id_pessoa_filho = f.id
								  LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->nome;
		}else{
			return false;
		}
	}
	
	function getPaiByIdPessoa($idPessoa){
		$reg = executaSQL("SELECT f.* FROM pessoa f, relacao r
								WHERE r.id_pessoa_pai = '".$idPessoa."'
								  AND r.id_tipo=1
								  AND f.id_sexo=1
								  AND r.id_pessoa_filho = f.id
								  LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}
	
	function getMaeByIdPessoa($idPessoa){
		$reg = executaSQL("SELECT f.* FROM pessoa f, relacao r
								WHERE r.id_pessoa_pai = '".$idPessoa."'
								  AND r.id_tipo=1
								  AND f.id_sexo=2
								  AND r.id_pessoa_filho = f.id
								  LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}
	
	function getEstadoCivilById($id){
		$reg = executaSQL("SELECT valor FROM estado_civil
							WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getCelularPessoaByIdPessoa($idPessoa){
		$reg = executaSQL("SELECT num FROM pessoa_telefone
							WHERE id_pessoa='".$idPessoa."'
							AND id_tipo_telefone=3 
							LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->num;
		}else{
			return false;
		}
	}
	
	function getTelefonePrincipalPessoaByIdPessoa($idPessoa){
		$reg = executaSQL("SELECT num FROM pessoa_telefone
							WHERE id_pessoa='".$idPessoa."'
							AND id_tipo_telefone=1 
							LIMIT 1");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->num;
		}else{
			return false;
		}
	}	
	
	function getIrmaoTipoMaconicoById($id){
		$exe = executaSQL("SELECT valor FROM irmao_tipo WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getPessoaIdentidadeByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM pessoa_documento WHERE id_pessoa='".$id."' AND id_tipo=2 LIMIT 1");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getPessoaCPFByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM pessoa_documento WHERE id_pessoa='".$id."' AND id_tipo=1 LIMIT 1");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getPessoaTituloByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM pessoa_documento WHERE id_pessoa='".$id."' AND id_tipo=5 LIMIT 1");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getCertResByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM pessoa_documento WHERE id_pessoa='".$id."' AND id_tipo=6 LIMIT 1");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getNumeroObreirosCapitacaoByIdLoja($id){
		$exe = executaSQL("SELECT 1 FROM pessoa WHERE id_loja_capitacao='".$id."' AND id_tipo=1 AND id_situacao IN(1,9)");		
		return nLinhas($exe);
	}
	
	function getProcessoById($id){
		$exe = executaSQL("SELECT nome FROM processos WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}
	
	function getProcessoDataById($id, $tipo){
		if($tipo>0){
			if($tipo==1){
				$regs = executaSQL("SELECT data FROM iniciacao WHERE id = '".$id."'");
				
			}elseif(in_array($tipo, array(2,3))){
				$regs = executaSQL("SELECT data FROM aumento_salario WHERE id = '".$id."'");
				
			}elseif(in_array($tipo, array(6, 7, 8, 9, 10))){
				$regs = executaSQL("SELECT data FROM regularizacao WHERE id = '".$id."'");
				
			}elseif(in_array($tipo, array(15, 16))){
				$regs = executaSQL("SELECT data FROM afastamento WHERE id = '".$id."'");
				
			}elseif(in_array($tipo, array(12, 13, 21, 22, 23))){
				$regs = executaSQL("SELECT data FROM rituais_especiais WHERE id = '".$id."'");
				
			}elseif(in_array($tipo, array(24))){
				$regs = executaSQL("SELECT dt_user_criacao AS data FROM lei_002 WHERE id = '".$id."'");
				
			}elseif(in_array($tipo, array(25))){
				$regs = executaSQL("SELECT data FROM instalacao WHERE id = '".$id."'");
				
			}elseif(in_array($tipo, array(26))){
				$regs = executaSQL("SELECT dt_user_criacao AS data FROM passaporte WHERE id = '".$id."'");
				
			}
			
			if(isset($regs) && nLinhas($regs)>0){
				return converte_data(objetoPHP($regs)->data);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	function getProcessoNomeIrmaosById($id, $tipo, $translate){
		
		if($tipo==1){
			$regs = executaSQL("SELECT c.nome FROM item_iniciacao i, candidatos c WHERE i.id_candidato = c.id AND i.id_iniciacao = '".$id."'");
		
			while($reg = objetoPHP($regs)){
				$nomes[] = $reg->nome;
			}
			
			$cont = $translate->translate('candidato_s').': '.(count($nomes)>0 ? implode(', ', $nomes) : $nomes[0]);
			
		}elseif(in_array($tipo, array(2, 3))){
			$regs = executaSQL("SELECT p.nome, p.cim FROM aumento_salario_item i, pessoa p WHERE i.id_pessoa = p.id AND i.id_aumento_salario = '".$id."'");
			while($reg = objetoPHP($regs)){
				$nomes[] = $reg->nome.' ('.$reg->cim.')';
			}
			
			$cont = $translate->translate('irmao_s').': '.(count($nomes)>0 ? implode(', ', $nomes) : $nomes[0]);
		
		}elseif(in_array($tipo, array(6, 7, 8, 9, 10))){
			$regs = executaSQL("SELECT id_pessoa, nome FROM regularizacao WHERE id = '".$id."'");
			if($tipo==6){
				$reg = objetoPHP($regs);
				$cont = $translate->translate('irmao').': '.$reg->nome;
			}else{
				$irmao = consultaPessoaById( objetoPHP($regs)->id_pessoa, array('nome', 'cim') );
				$cont = $translate->translate('irmao').': '.$irmao->nome.' ('.$irmao->cim.')';
			}
		}elseif(in_array($tipo, array(15, 16))){
			$regs = executaSQL("SELECT id_pessoa FROM afastamento WHERE id = '".$id."'");
			$irmao = consultaPessoaById( objetoPHP($regs)->id_pessoa, array('nome', 'cim') );
			$cont = $translate->translate('irmao').': '.$irmao->nome.' ('.$irmao->cim.')';
			
		}elseif(in_array($tipo, array(12, 13, 21, 22, 23))){
			$regs = executaSQL("SELECT id_irmao FROM rituais_especiais WHERE id = '".$id."'");
			$irmao = consultaPessoaById( objetoPHP($regs)->id_irmao, array('nome', 'cim') );
			$cont = $translate->translate('irmao').': '.$irmao->nome.' ('.$irmao->cim.')';
			
		}
	
	//	var_dump($nomes);
		return $cont;
	}
/*-------------EMOLUMENTOS------------*/
	
	function getemolumentoById($id){
		$exe = executaSQL("SELECT * FROM emolumentos WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getemolumentoCategoriaById($id){
		$exe = executaSQL("SELECT nome FROM emolumentos_categoria WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}

	function getQtdeEmolumentosProcessoByIdProcesso($id){
		$exe = executaSQL("SELECT 1 FROM emolumentos_processo WHERE id_processo='".$id."'");
		return nLinhas($exe);
	}
	
	function getEmolumentoValorById($id){
		$exe = executaSQL("SELECT valor FROM emolumentos WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getEmolumentoNomeeById($id){
		$exe = executaSQL("SELECT nome FROM emolumentos WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}
	
	function getNotaValorById($id){
		$exe = executaSQL("SELECT valor, quantidade FROM notas_debito_item
								WHERE id_nota_debito='".$id."'");
		
		$total = 0;
		while($reg = objetoPHP($exe)){
			$total+= $reg->valor*$reg->quantidade;
		}
		
		if(nLinhas($exe)>0){
			return $total;
		}else{
			return false;
		}
	}

/*-------------EMOLUMENTOS------------*/
	
	function getNotaDebitoSituacaoById($id){
		$exe = executaSQL("SELECT valor	FROM notas_debito_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}

	function getIdContaBancariaAtiva(){
		$exe = executaSQL("SELECT id FROM conta_bancaria WHERE ativo=1");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id;
		}else{
			return false;
		}
	}
	
	function getCargoNomeById($id){		
		$exe = executaSQL("SELECT valor FROM cargo WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
		
	function qtdeIrmaosAcessaramPortal(){
		return nLinhas( executaSQL("SELECT p.id FROM historico h INNER JOIN pessoa p ON h.id_pessoa=p.id WHERE h.str_sql LIKE 'UPDATE pessoa SET acesso = %' GROUP BY h.id_pessoa") );
	}
	
	function getIdMilitarLocalByIdMilitarPosto($id){
		$exe = executaSQL("SELECT id_local FROM militar_posto WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id_local;
		}else{
			return false;
		}
	}
	
	function getNomeMilitarLocalByIdMilitarPosto($id){
		$exe = executaSQL("SELECT l.valor FROM militar_posto p, militar_local l
								WHERE p.id='".$id."'
									AND p.id_local = l.id");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getPostoMlilitarById($id){
		$exe = executaSQL("SELECT valor FROM militar_posto WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getNomePostoById($id){
		$exe = executaSQL("SELECT valor FROM militar_posto WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getIdProficienciaPessoaByIdIdiomaAndIdCompetencia($idPessoa, $idIdioma, $idCompetencia){
		$exe = executaSQL("SELECT id_idioma_proficiencia FROM pessoa_idioma
							WHERE id_pessoa='".$idPessoa."' AND id_idioma='".$idIdioma."' AND id_idioma_competencia='".$idCompetencia."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id_idioma_proficiencia;
		}else{
			return false;
		}
	}
	
	//VERIFICA SE O RITO ESTÁ ASSOCIADO HA ALGUMA NOMINATA DE LOJA
	function verificaRitoNominataLoja($idRito){
		$exe = executaSQL("SELECT 1 FROM cargo_rito cr, gestao_loja gl, gestao_loja_cargos glc
							WHERE cr.id_rito = '".$idRito."'
							AND glc.id_cargo=cr.id_cargo
							AND gl.id=glc.id_gestao
							AND EXISTS(SELECT 1 FROM loja l WHERE l.id=gl.id_loja AND l.id_rito=cr.id_rito)");		
		if(nLinhas($exe)>0){			
			return false;
		}else{
			return true;
		}
		
	}
	
	//VERIFICA SE O CARGO ESTÁ ASSOCIADO A ALGUM RITO
	function verificaCargoRito($id){
		$exe = executaSQL("SELECT 1 FROM cargo_rito WHERE id_cargo='".$id."'");
		if(nLinhas($exe)>0){
			return false;
		}else{
			return true;
		}
		
	}
	
	function getExpedicaoSituacao($id){
		$exe = executaSQL("SELECT valor FROM expedicao_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function temProcessoNaNotaByIdNota($id){
		$exe = executaSQL("SELECT 1 FROM notas_debito WHERE id='".$id."' AND id_processo_tipo>0");
		
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getIdEmolumentoByIdNotaItem($id){
		$exe = executaSQL("SELECT id_emolumento FROM notas_debito_item WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id_emolumento;
		}else{
			return false;
		}
	}
	
	function getNotaDebitoById($id){
		$exe = executaSQL("SELECT * FROM notas_debito WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getAfastamentoTipoById($id){
		$exe = executaSQL("SELECT valor FROM afastamento_tipo WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getAfastamentoSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM afastamento_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getAumentoSalarioItemById($id){
		$exe = executaSQL("SELECT * FROM aumento_salario_item WHERE id_aumento_salario='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getAumentoSalarioValorSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM aumento_salario_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getBoletimById($id){
		$exe = executaSQL("SELECT * FROM boletim WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}

	function getBoletimByData($data=NULL){
		if($data==NULL){
			$data = date('y-m-d');
		}
		$exe = executaSQL("SELECT * FROM boletim WHERE '".$data."' BETWEEN data_inicial AND data_final ORDER BY data_final DESC ");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}

	function getIniciacaoByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM irmao WHERE id_irmao='".$id."' AND id_tipo = 1 AND ativo = 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getRegFilByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM irmao WHERE id_irmao='".$id."' AND id_tipo IN(7, 8, 9, 10, 11) AND ativo = 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}

	function getElevacaoByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM irmao WHERE id_irmao='".$id."' AND id_tipo = 2 AND ativo = 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}

	function getExaltacaoByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM irmao WHERE id_irmao='".$id."' AND id_tipo IN (3,122) AND ativo = 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getInstalacaoByIdPessoa($id){
		$exe = executaSQL("SELECT * FROM irmao WHERE id_irmao='".$id."' AND id_tipo IN (4, 34) AND ativo = 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getGraduacaoValor($graduacao, $rito, $translate){
		$regs = executaSQL("SELECT valor FROM irmao_tipo_rito WHERE id_tipo = '".$graduacao."' AND id_rito = '".$rito."'");
		
		if( nLinhas($regs)>0 ){
			return objetoPHP($regs)->valor;
		}else{
			return $translate->translate('tipo_maconico_'.$graduacao);
		}
	}
	
	//VERIFICA SE A CONTA CORRENTE POSSUI UMA NOTA DE DEBITO DE UM PROCESSO
	function verificaNotaDebitoProcessoByIdContaCorrente($id){
		$exe = executaSQL("SELECT 1 FROM conta_corrente cc, notas_debito nd 
							WHERE cc.id='".$id."'
							AND cc.id_nota=nd.id
							AND nd.id_processo>0");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getValorSituacaoPagtoById($id){
		$exe = executaSQL("SELECT valor FROM pagamento_loja_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getPagamentoValorById($id){
		$exe = executaSQL("SELECT valor FROM pagamento_loja_item
								WHERE id_pagamento='".$id."'");
		
		$total = 0;
		while($reg = objetoPHP($exe)){
			$total+= $reg->valor;
		}
		
		if(nLinhas($exe)>0){
			return $total;
		}else{
			return false;
		}
	}
	
	
	function verificaFilhosCategoria($id){
		$exe = executaSQL("SELECT 1 FROM categoria_despesa_receita WHERE id_pai='".$id."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getIdPaiByIdFilho($id){
		$exe = executaSQL("SELECT id_pai FROM categoria_despesa_receita WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id_pai;
		}else{
			return false;
		}
	}
	
	function getNegociacaoSituacaoValorById($id){
		$exe = executaSQL("SELECT valor FROM negociacao_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getQtdeParcelasPagasNegociacaoByIdNegociacao($id){
		$exe = executaSQL("SELECT 1 FROM negociacao_parcela WHERE id_negociacao='".$id."'");
		return nLinhas($exe);
	}
	
	function getSexoById($id){
		$regs = executaSQL("SELECT valor FROM pessoa_sexo WHERE id = '".$id."'");
		if(nLinhas($regs)>0){
			return objetoPHP($regs)->valor;
		}else{
			return false;
		}
	}
	
	function verificaCategoriasReceitasFilhos($id){
		$regs  = executaSQL("SELECT 1 FROM categoria_despesa_receita WHERE id_pai = '".$id."'");
		if(nLinhas($regs)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function verificaCategoriasEmolumentosRelacionados($id){	
		$regs2 = executaSQL("SELECT 1 FROM emolumentos WHERE id_cat_receita = '".$id."'");
		if(nLinhas($regs2)>0){
			return true;
		}else{
			return false;
		}
	}
	
	//VERIFICA SE A NOTA ESTÁ RELACIONADA A UM PAGAMENTO
	function verificaRelacaoPagamentoByIdNota($id){
		$exe = executaSQL("SELECT 1 FROM pagamento_loja pl, pagamento_loja_notas_debito plnd
							WHERE plnd.id_nota='".$id."'
							AND pl.id = plnd.id_pagamento
							AND pl.id_situacao NOT IN(3)");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	//VERIFICA SE A NOTA ESTÁ RELACIONADA A UMA NEGOCIACAO
	function verificaRelacaoNegociacaoByIdNota($id){
		$exe = executaSQL("SELECT 1 FROM negociacao n, negociacao_notas_debito nnd
							WHERE nnd.id_nota='".$id."'
							AND n.id=nnd.id_negociacao
							AND n.id_situacao NOT IN(3)");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}

	//VERIFICA SE A NOTA ESTÁ RELACIONADA A UM PAGAMENTO DIFERENTE
	function verificaRelacaoPagamentoDiferenteByIdNota($idNota, $idPagto){
		$exe = executaSQL("SELECT 1 FROM pagamento_loja_notas_debito WHERE id_nota='".$idNota."' AND id_pagamento<>'".$idPagto."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}

	//RETORNA OS ORIENTES EM QUE TEM LOJA
	function getOrientesByLoja(){
		return executaSQL("SELECT m.id, m.nome FROM municipio m WHERE EXISTS ( SELECT 1 FROM loja l WHERE l.id_oriente=m.id ) ORDER BY nome");
	}	
	
	function getChequeSituacaoValorById($id){
		$exe = executaSQL("SELECT valor FROM cheque_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getLancamentoByIdChequeById($id){
		$exe = executaSQL("SELECT * FROM conta_corrente WHERE id_cheque='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getTableColumsByClause($table, $colums="*", $clause, $mostrar=false){
		if($colums==NULL)
				$colums = "*";
		
		$sql = "SELECT ".$colums." FROM ".$table." WHERE ".$clause;

		return executaSQL($sql, $mostrar);
	}
	
	function getIniciacaoSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM iniciacao_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getAumSalSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM aumento_salario_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function proximoNumeroPLAM(){
		$exe = executaSQL("SELECT MAX(numero_plam) as id FROM pessoa");
		if(nLinhas($exe)>0){
			$proxId = objetoPHP($exe);
			return ($proxId->id + 1);
		}else
			return 1;
	}	
	
	
	function getEmailVeneravelByIdLoja($idLoja){
		$regs = executaSQL("SELECT p.email FROM pessoa p, gestao_loja_cargos lc, gestao_loja g
								WHERE p.id = lc.id_irmao
									AND lc.id_gestao = g.id
									AND '".date('Y-m-d')."' BETWEEN g.dt_inicio AND g.dt_fim
									AND g.id_loja = '".$idLoja."'
									AND lc.id_cargo = 1");
		if(nLinhas($regs)>0){
			return objetoPHP($regs)->email;
		}else{
			return false;
		}
	}

	function getVeneravelAtualByIdLoja($idLoja){
		$regs = executaSQL("SELECT p.id, p.email, p.nome FROM pessoa p, gestao_loja_cargos lc, gestao_loja g
								WHERE p.id = lc.id_irmao
									AND lc.id_gestao = g.id
									AND '".date('Y-m-d')."' BETWEEN g.dt_inicio AND g.dt_fim
									AND g.id_loja = '".$idLoja."'
									AND lc.id_cargo = 1");
		if(nLinhas($regs)>0){
			return objetoPHP($regs);
		}else{
			return false;
		}
	}
	
	function getEmailCargoByIdLoja($idLoja, $idCargo){
		$regs = executaSQL("SELECT p.email FROM pessoa p, gestao_loja_cargos lc, gestao_loja g
								WHERE p.id = lc.id_irmao
									AND lc.id_gestao = g.id
									AND '".date('Y-m-d')."' BETWEEN g.dt_inicio AND g.dt_fim
									AND g.id_loja = '".$idLoja."'
									AND lc.id_cargo = '".$idCargo."'");
		if(nLinhas($regs)>0){
			return objetoPHP($regs)->email;
		}else{
			return false;
		}
	}
	
	function verificaLojaLiberacao(){
		
		if(!(ehMason() || ehAdminGeral() /*|| ehColaborador()*/)){
		/*	$regs = executaSQL("SELECT 1 FROM pessoa_perfil p, loja_acesso a
									WHERE a.id_pessoa = p.id_pessoa
										AND p.id_pessoa = '".$_SESSION['pessoaId']."'
										AND p.id_perfil = 2");
		
			if(nLinhas($regs)>0){
		*/		$regs = executaSQL("SELECT 1 FROM pessoa_perfil p, loja_acesso a, loja l
										WHERE a.id_pessoa = p.id_pessoa
											AND p.id_pessoa = '".$_SESSION['pessoaId']."'
											AND p.id_perfil = 2
											AND l.id = a.loja
											AND l.capacitacao = 1");
				
				if(!nLinhas($regs)>0)
					return false;
				else
					return true;
		/*			
			}else
				return true;
		*/
		}else
			return true;
	}
	
	function verificaRelacaoCategoriaContaCorrente($id){
		$exe = executaSQL("SELECT 1 FROM conta_corrente WHERE id_categoria='".$id."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}	
	
	function temRelacaoCategoriaPaiFilhosCC($id, $retorno=array()){
		$exe = executaSQL("SELECT 1 FROM conta_corrente WHERE id_categoria='".$id."'");
		if(nLinhas($exe)>0){
			$retorno[] =1;			
		}else{
			$retorno[] =2;
			//VERIFICA SE TEM FILHOS
			$exeFilhos = executaSQL("SELECT id FROM categoria_despesa_receita WHERE id_pai='".$id."' ORDER BY id");
			if(nLinhas($exeFilhos)>0){
				while($regFilho=objetoPHP($exeFilhos)){
					$retorno = temRelacaoCategoriaPaiFilhosCC($regFilho->id, $retorno);
				}
			}
		}
		return $retorno;
	}
	
	function consultaFiliacoesByIdPessoa($id=NULL){
		if($id==NULL)
			$id = $_SESSION["pessoaCorrenteIdAdm"];
		
		$sql = "SELECT DISTINCT(l.id) AS id, l.nome, l.cod, i.id_obediencia FROM loja l, irmao i
					WHERE l.id = i.id_loja
						AND i.id_irmao = '".$id."'
						AND (i.data_final IS NULL || i.data_final = '0000-00-00')
						AND i.ativo = 1
						AND 
							(
								i.id_tipo IN(
												SELECT id FROM irmao_tipo
													WHERE eh_filiacao = 1
														AND cadastro = 1
											)
							AND
								i.id_loja <> (SELECT id_loja FROM pessoa WHERE id = '".$id."')
							)
				ORDER BY cod";
		return executaSQL($sql);
	}
	
	function consultaFiliacoesLojaEstudoByIdPessoa($id=NULL){
		if($id==NULL)
			$id = $_SESSION["pessoaCorrenteIdAdm"];
		
		$sql = "SELECT DISTINCT(l.id) AS id, l.nome, l.cod, i.id_obediencia FROM loja l, irmao i
					WHERE l.id = i.id_loja
						AND i.id_irmao = '".$id."'
						AND l.eh_pesquisa_estudo = 1
						AND (i.data_final IS NULL || i.data_final = '0000-00-00')
						AND i.ativo = 1
						AND 
							(
								i.id_tipo IN(
												SELECT id FROM irmao_tipo
													WHERE eh_filiacao = 1
														AND cadastro = 1
											)
							AND
								i.id_loja <> (SELECT id_loja FROM pessoa WHERE id = '".$id."')
							)
				ORDER BY cod";
		return executaSQL($sql);
	}
	
	function geraAjuda($translate, $url){
		$regs = executaSQL("SELECT id FROM ajuda WHERE pagina = '".$url."'");
		
	//	Se haver ajuda para este módulo/página
		if( nLinhas($regs)>0 ){
			$reg = objetoPHP($regs)->id;
			
		$c	=	'<div class="page-toolbar">';
		$c	.=		'<a href="/adm/ajuda/registro/'.$reg.'" target="_blank" class="pull-right tooltips btn btn-fit-height blue">';
	//	$c	.=			'<i class="icon-calendar"></i> <span class="thin uppercase visible-lg-inline-block">'.$tranlate->tranlate('ajuda')."</span>";
		$c	.=			'<i class="fa fa-question-circle"></i>&nbsp; <span class="uppercase">'.$translate->translate('ajuda')."</span>";
		$c	.=		'</a>';
		$c	.=	'</div>';
?>
			<script>
				$(function(){
					$('.page-bar').append('<?=$c?>');
				});
			</script>
<?
		}else{
		
		//	Se há '/' no neste módulo/página, chama de novo a função, procurando pela pasta (anterior)
		//	echo	'<br />Posição: '.
			$pos = strrpos($url, '/');
			if($pos){
			//	echo substr($url, 0, $pos);
				geraAjuda($translate, substr($url, 0, $pos) );
			}
		}
	}

	function getAreaSolicitacao($id){
		$exe = executaSQL("SELECT valor FROM solicitacao_area WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;			
		}else{
			return false;
		}
	}
	
	function temAreaSolicitacao($area=NULL){
		if( ehMason() || ehAdminGeral() )
			return true;
			
	//	Se passada a área
		if($area!=NULL){
			
			if(is_array($area)){
			
				foreach($area as $valor){
					$areas[] = "'".$valor."'";
				}
				$param = " AND id_area IN( ".implode(', ', $areas).")";
				
			}else{
				$param = " AND id_area ='".$area."'";
			}
			
		}
		
		$exe = executaSQL("SELECT 1 FROM solicitacao_area_pessoa WHERE id_pessoa = '".$_SESSION['usuarioId']."'".$param);
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function verificaAumentoSalarioLiberacao($pessoa, $tipo){
		$regs = executaSQL("SELECT 1 FROM liberacao_intersticio WHERE id_pessoa = '".$pessoa."' AND tipo = '".$tipo."' AND dt_usuario_exclusao IS NULL");
		if(nLinhas($regs)>0){
			return true;
		}else{
			return false;
		}
	}
	
	//----------- MODULO DOCUMENTOS --------------
	
	function getNomeDocumentoCategoriaById($id){
		$exe = executaSQL("SELECT valor FROM documento_categoria WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function temDocumentoCategoriaByCategoriaTipo($tipo){
		
		$retorno = false;
		
		$exe = executaSQL("SELECT id FROM documento_categoria dc 
							WHERE dc.tipo='".$tipo."'
							AND EXISTS(SELECT 1 FROM documento d WHERE d.id_categoria=dc.id)");
		if(nLinhas($exe)>0){
			if(ehAdminGeral()){
				$retorno = true;
			}else{
			
				while($reg=objetoPHP($exe)){
					
					if($tipo==2){//LOJA				
						//VERIFICA SE A LOJA DO CARA ESTÁ NA LISTA DE LOJAS DA CATEGORIA
						$exe2 = executaSQL("SELECT 1 FROM documento_categoria_loja 
											WHERE id_categoria = '".$reg->id."'
											AND id_loja='".$_SESSION['loja_sge']."'");
											
					}else{//GRAU
						//VERIFICA SE O GRAU DO CARA ESTÁ NA LISTA DE GRAUS DA CATEGORIA
						$exe2 = executaSQL("SELECT 1 FROM documento_categoria_grau
											WHERE id_categoria = '".$reg->id."'
											AND id_grau='".$_SESSION['usuarioGrau']."'");
					}
					
					if(nLinhas($exe2)>0){
						$retorno = true;
					}
					
				}
			}
		}
		
		return $retorno;
		
	}
	
	function permiteAcessoDocumentoByIdCategoria($id){
		
		$retorno = false;
		
		$exe = executaSQL("SELECT * FROM documento_categoria WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			
			$reg=objetoPHP($exe);
				
			if($reg->tipo==2){//LOJA				
				//VERIFICA SE A LOJA DO CARA ESTÁ NA LISTA DE LOJAS DA CATEGORIA
				$exe2 = executaSQL("SELECT 1 FROM documento_categoria_loja 
									WHERE id_categoria = '".$reg->id."'
									AND id_loja='".$_SESSION['loja_sge']."'");
									
			}else{//GRAU
								
				//VERIFICA SE O GRAU DO CARA ESTÁ NA LISTA DE GRAUS DA CATEGORIA
				$exe2 = executaSQL("SELECT 1 FROM documento_categoria_grau
									WHERE id_categoria = '".$reg->id."'
									AND id_grau='".$_SESSION['usuarioGrau']."'");
			}
			
			if(nLinhas($exe2)>0){
				$retorno = true;
			}

		}
		
		return $retorno;		
	}
	
//----------- MODULO DOCUMENTOS --------------

	function getLojasAutorizacao($pessoa=NULL){
		
		if($pessoa==NULL)
			$pessoa = $_SESSION['pessoaId'];
			
		return executaSQL("SELECT DISTINCT(l.id), l.* FROM pessoa_perfil pp, loja_acesso la, loja l
								WHERE pp.id_pessoa = la.id_pessoa
									AND la.loja = l.id
									AND pp.id_pessoa = '".$pessoa."'
									AND pp.id_perfil = 2
									AND l.id_ativa = 1");
	}
	
	function verificaRelacaoNotaDebitoPagamento($idNota){
		//VERIFICA SE EXISTE ALGUM PAGAMENTO PARA ESTA NOTA
		$exe = executaSQL("SELECT 1 FROM fatura p, fatura_notas_debito pnd
										WHERE pnd.id_nota='".$idNota."'
										AND pnd.id_fatura = p.id
										AND p.id_situacao NOT IN(3)");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getNomeFamiliaresPessoaByIdTipo($idPessoa, $idTipo){		
		return executaSQL("SELECT DISTINCT(id_pessoa_filho) FROM relacao WHERE id_pessoa_pai='".$idPessoa."' AND id_tipo='".$idTipo."'");
	}
	
	function getTotalItemNotaDebitoByIdNota($idNota, $idEmol){
		if(is_array($idEmol)){
			$params = " AND id_emolumento NOT IN (".implode(",", $idEmol).")";
		}else{
			$params = " AND id_emolumento='".$idEmol."'";
		}
		$exe = executaSQL("SELECT valor, quantidade FROM notas_debito_item
								WHERE id_nota_debito='".$idNota."'".$params);
		
		if(nLinhas($exe)>0){
			$total=0;
			while($reg = objetoPHP($exe)){
				$total += $reg->valor*$reg->quantidade;
			}			
			return $total;
		}else{
			return 0;
		}
	}
	
	function getDelegaciaNomeById($id){
		$exe = executaSQL("SELECT nome FROM delegacia WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}
	
	function getDelegaciaLojaNomeByIdLoja($idLoja){
		$exe = executaSQL("SELECT d.nome FROM delegacia d, delegacia_loja dl 
								WHERE dl.id_delegacia = d.id 
								AND dl.id_loja='".$idLoja."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->nome;
		}else{
			return false;
		}
	}
	
	function getNotaDebitoTipoById($id){
		$exe = executaSQL("SELECT valor FROM notas_debito_tipo WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getNotaValorPagoById($id){
		
		$total=0;
		
		$exe = executaSQL("SELECT 1 FROM notas_debito
							WHERE id='".$id."'
							AND id_situacao=4");
		if(nLinhas($exe)>0){
			
			//VERIFICA SE A NOTA FOI PAGA POR UM PAGAMENTO
			$exePagto = executaSQL("SELECT DISTINCT(p.id) AS id FROM pagamento_loja p, pagamento_loja_notas_debito pn
										WHERE pn.id_nota = '".$id."'
										AND pn.id_pagamento = p.id
										AND p.id_situacao=2");
										
			if(nLinhas($exePagto)>0){
				
				//BUSCA OS ITENS DO PAGAMENTO PARA SOMAR O VALOR TOTAL DO PAGTO
				$exeItens = executaSQL("SELECT SUM(valor) as valor FROM pagamento_loja_item
											WHERE id_pagamento='".objetoPHP($exePagto)->id."'");
				if(nLinhas($exeItens)>0){
					$total = objetoPHP($exeItens)->valor;
				}
				
			}else{
				
				//VERIFICA SE A NOTA FOI PAGA POR BOLETO
				$exeBol = executaSQL("SELECT valor_pgto as valor FROM boleto
										WHERE id_nota ='".$id."'
										AND id_situacao=2");
				if(nLinhas($exeBol)>0){
					$total = objetoPHP($exeBol)->valor;
				}
				
			}			
			
		}
		
		return $total;
	}
	
	//BUSCA A FORMA QUE A NOTA FOI PAGA, OU POR BOLETO OU POR PAGAMENTO
	function getNotaMeioPagamentoById($id){		
		
		$exe = executaSQL("SELECT 1 FROM notas_debito
							WHERE id='".$id."'
							AND id_situacao=4");
		if(nLinhas($exe)>0){
			
			//VERIFICA SE A NOTA FOI PAGA POR UM PAGAMENTO
			$exePagto = executaSQL("SELECT DISTINCT(p.id) AS id FROM pagamento_loja p, pagamento_loja_notas_debito pn
										WHERE pn.id_nota = '".$id."'
										AND pn.id_pagamento = p.id
										AND p.id_situacao=2");
										
			if(nLinhas($exePagto)>0){
				$numeroPagto = objetoPHP($exePagto)->id;
				return array(1, $numeroPagto);				
				
			}else{
				
				//VERIFICA SE A NOTA FOI PAGA POR BOLETO
				$exeBol = executaSQL("SELECT numero FROM boleto
										WHERE id_nota ='".$id."'
										AND id_situacao=2");
				if(nLinhas($exeBol)>0){
					$numeroBoleto = objetoPHP($exeBol)->numero;
					return array(2, $numeroBoleto);
				}
			}
			
		}else{
			return false;
		}
	}
	
	//VERIFICA SE TODAS AS NOTAS DE DÉBITO RELACIONADAS AO PAGAMENTO, ESTÃO OKAY PARA SEREM PAGAS
	function validaNotasPagamentoByIdPagamento($id){
		
		$retorno = true;
		$exe = executaSQL("SELECT id_tipo FROM pagamento_loja WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			
			$tipoPagto = objetoPHP($exe)->id_tipo;
			
			$exeND = executaSQL("SELECT id_nota FROM pagamento_loja_notas_debito WHERE id_pagamento='".$id."'");
			if(nLinhas($exeND)>0){
				
				while($regND=objetoPHP($exeND)){
								
					$notaDeb = getNotaDebitoById($regND->id_nota);
					
					if($notaDeb->id_tipo!=$tipoPagto ){
						$retorno = false;
					}
					
					if($notaDeb->id_situacao!=2){//FATURADA
						$retorno = false;
					}
					
				}
					
			}else{
				$retorno = false;
			}
			
		}else{
			$retorno = false;
		}
		
		return $retorno;
	}
	
	/*
		tipoFaturado = 1-loja 2-irmao 3-provedor
		tipoNota = 1-nota de debito 2-ajuste financeiro 3-nota de devolução
	*/
	function validaNotasProntasParaPagamentoByIdNota($idNotas, $tipoFaturado, $tipoNota){		
		$retorno = true;
		
		$exeND = executaSQL("SELECT id_situacao, id_tipo FROM notas_debito 
								WHERE id IN(".implode(",", $idNotas).") 
								AND id_tipo='".$tipoFaturado."' 
								AND tipo_nota = '".$tipoNota."'");		
		if(nLinhas($exeND)==0){
			$retorno = false;
		}
		
		return $retorno;
		
	}
	
	function verificaRelacaoEntrePagamentoENotaDevolucao($idNota){
		$exe = executaSQL("SELECT 1 FROM pagamento_loja pl, pagamento_loja_item pli 
							WHERE pli.id_nota_devolucao='".$idNota."'
							AND pl.id = pli.id_pagamento
							AND pl.id_situacao NOT IN(3)");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getTransferenciaRecolhimentoSituacao($id){
		$exe = executaSQL("SELECT valor FROM transferencia_recolhimento_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}	
	
	function getIdItemAjusteFinanceiro(){
		$exe = executaSQL("SELECT id FROM emolumentos WHERE ajuste_financeiro=1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id;
		}else{
			return 0;
		}
	}
	
	function getIdCategoriaDespesaNotaDevolucao(){
		$exe = executaSQL("SELECT id FROM categoria_despesa_receita WHERE nota_devolucao=1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id;
		}else{
			return 0;
		}
	}
	
	function getLei002Situacao($id){
		$exe = executaSQL("SELECT valor FROM lei_002_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getRitualEspecialSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM rituais_especiais_situacao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getRitualEspecialTipoById($id){
		$exe = executaSQL("SELECT valor FROM rituais_especiais_tipo WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function consultaSolicitacaoCategoriaNomeById($id){
		$exe = executaSQL("SELECT valor FROM solicitacao_categoria WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getPABySolicitacaoId($id){
		$exe = executaSQL("SELECT * FROM processo_administrativo WHERE id_solicitacao_diversa='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function temPAArea($area){
		$exe = executaSQL("SELECT 1 FROM solicitacao_area WHERE id='".$area."' AND pa = 1");
		
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function consultaGestaoById($id){
		$exe = executaSQL("SELECT * FROM gestao WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaPagamentoValorById($id){
		$valorTotal=0;
		$exeNDS = executaSQL("SELECT id_nota FROM pagamento_loja_notas_debito WHERE id_pagamento='".$id."'");					
		if(nLinhas($exeNDS)>0){
			while($regND=objetoPHP($exeNDS)){
				$valorTotal += getNotaValorById($regND->id_nota);
			}
		}
		
		return $valorTotal;
		
	}
	
	function consultaGestaoAtual(){
		$exe = executaSQL("SELECT * FROM gestao 
							WHERE '".date('Y-m-d')."' BETWEEN dt_inicio AND dt_fim");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getValorTotalPagamentoById($id){
		
		$valorTotal=0;
		$exeNDS = executaSQL("SELECT id_nota FROM pagamento_notas_debito WHERE id_pagamento='".$id."'");
		if(nLinhas($exeNDS)>0){
			while($regND=objetoPHP($exeNDS)){
				$valorTotal += getNotaValorById($regND->id_nota);
			}
		}
		
		return $valorTotal;
	
	}
	
	function verificaExpedicaoNotasPagamentoById($id){
		
		$retorno = true;
		$exeNDS = executaSQL("SELECT id_nota FROM pagamento_loja_notas_debito WHERE id_pagamento='".$id."'");
		if(nLinhas($exeNDS)>0){
			while($regND=objetoPHP($exeNDS)){
				
				$nota = getNotaDebitoById($regND->id_nota);
				
				$controlaEstoque = false;
				
				//BUSCA OS ITENS DA NOTA E VERIFICA SE POSSUEM ALGUM QUE CONTROLA ESTOQUE
				$exeItem = executaSQL("SELECT id_emolumento FROM notas_debito_item WHERE id_nota_debito='".$regND->id_nota."'");
				if(nLinhas($exeItem)>0){
					while($regItem=objetoPHP($exeItem)){
						$emol = getemolumentoById($regItem->id_emolumento);
						if($emol->tem_estoque==1)
							$controlaEstoque = true;
					}
					
				}
				
				
				if( in_array($nota->id_situacao_expedicao, array(2,3)) && $controlaEstoque ){
					$retorno = false;
				}
				
			}
		}
		
		return $retorno;
	
	}
	
	function consultaGestaoLojaById($id){
		$exe = executaSQL("SELECT * FROM gestao_loja WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaGestaoLojaAtualByIdLoja($idLoja){
		$exe = executaSQL("SELECT * FROM gestao_loja 
								WHERE id_loja='".$idLoja."'
								AND '".date('Y-m-d')."' BETWEEN dt_inicio AND dt_fim");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}	
	
	function consultaUltimaGestaoLojaByIdLoja($idLoja){
		$exe = executaSQL("SELECT * FROM gestao_loja 
							WHERE id_loja='".$idLoja."'
							ORDER BY dt_inicio DESC LIMIT 1");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaGestoesLoja($idLoja){
		return executaSQL("SELECT * FROM gestao_loja 
							WHERE id_loja='".$idLoja."'
							ORDER BY dt_inicio DESC");
	}	
	
	function consultaIdCargoByRitoHierarquia($idRito){
		$exe = executaSQL("SELECT 1 FROM cargo_rito cr, gestao_loja gl, gestao_loja_cargos glc
							WHERE cr.id_rito = '".$idRito."'
							AND glc.id_cargo=cr.id_cargo
							AND gl.id=glc.id_gestao
							AND EXISTS(SELECT 1 FROM loja l WHERE l.id=gl.id_loja AND l.id_rito=cr.id_rito)");		
		if(nLinhas($exe)>0){			
			return false;
		}else{
			return true;
		}
		
	}
	
	function atualizaProcessoPLAM($id){
		$reg = consultaPessoaById($id, array('id_situacao', 'tipo_plam_solicitacao'));
		
		if($reg->id_situacao==21){
			if( $reg->tipo_plam_solicitacao==1 ){
				$reg = objetoPHP( executaSQLPadrao('item_iniciacao', "id_pessoa = '".$id."' AND id_situacao=2") );
				alterarDados('item_iniciacao', array('id_situacao'=>3), "id_pessoa = '".$id."' AND id_situacao=2");
				
				$exe = executaSQLPadrao('item_iniciacao', "id_iniciacao = '".$reg->id_iniciacao."' AND id_situacao<>3");
				if(!nLinhas($exe)>0)
					alterarDados('iniciacao', array('id_situacao'=>6), "id = '".$reg->id_iniciacao."' AND id_situacao=5");
				
				alterarDados('pessoa', array('id_situacao'=>$_SESSION['POTENCIA']->sit_ativo), 'id = "'.$id.'"');
				
			}elseif($reg->tipo_plam_solicitacao==2){
				$exe = executaSQLPadrao('regularizacao', "id_pessoa = '".$id."' AND id_situacao=10");
				
				if(nLinhas($exe)>0)
					alterarDados('regularizacao', array('id_situacao'=>11),"id_pessoa = '".$id."' AND id_situacao=10");
				
				alterarDados('pessoa', array('id_situacao'=>$_SESSION['POTENCIA']->sit_ativo), 'id = "'.$id.'"');
			}
		}
	}
	
	function isIrmaosFiliadoDaLojaByIdIrmao($irmao, $loja){
		$exe = executaSQL("SELECT 1 FROM pessoa p
							WHERE p.id = '".$irmao."'
								AND p.id_loja <> '".$loja."' 
								AND EXISTS(SELECT 1 FROM irmao i, irmao_tipo t
										 WHERE i.id_irmao = p.id
											AND i.id_loja = '".$loja."'
											AND (i.data_final IS NULL || i.data_final = '0000-00-00')
											AND i.id_tipo = t.id
											AND t.eh_filiacao = 1
											AND t.cadastro = 1
											AND i.ativo = 1
									)
								");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function verificaExpedicaoByIdNota($idNota){
		$arr = array();
		$hists = executaSQL("SELECT * FROM expedicao WHERE id_nota = '".$idNota."'");
		if(nLinhas($hists)>0){
			while($hist = objetoPHP($hists)){
				
				$exeItens = executaSQL("SELECT * FROM expedicao_item WHERE id_expedicao='".$hist->id."'");
				if(nLinhas($exeItens)>0){
					while($regItens = objetoPHP($exeItens)){ 
					
						$qtdeItens = objetoPHP(executaSQL("SELECT SUM(qtde) as qtde FROM expedicao_item
															WHERE id_expedicao='".$regItens->id_expedicao."' 
															AND id_item='".$regItens->id_item."'"))->qtde;
						if($qtdeItens>0){
							$arr[] = 1;
						}
					}
				}
				
			}
		}
		
		if(in_array(1, $arr)){
			return false;
		}else{
			return true;
		}

	}
	
	function verificaEventosConfirmacaoLoja($loja=""){
		
		if($loja==""){
			$loja=$_SESSION['loja_sge'];
		}
		
		$inics		= executaSQLPadrao("iniciacao", "id_situacao = 3 AND id_loja = '".$loja."' AND data<'".date("Y-m-d")."'");
		$aumSal		= executaSQLPadrao("aumento_salario", "id_situacao = 3 AND id_loja = '".$loja."' AND data<'".date("Y-m-d")."'");
		$insts		= executaSQLPadrao("instalacao", "id_situacao = 5 AND id_loja = '".$loja."' AND data<'".date("Y-m-d")."'");
		$regs		= executaSQLPadrao("regularizacao", "id_situacao = 8 AND id_loja = '".$loja."' AND data<'".date("Y-m-d")."'");
		
		$nInics		= nLinhas($inics);
		$nAumSal	= nLinhas($aumSal);
		$nInst		= nLinhas($insts);
		$nReg		= nLinhas($regs);
		
		if($nInics>0 || $nAumSal>0 || $nInst>0 || $nReg>0){
			return true;
		}else{
			return false;
		}
	}	
	
	function consultaChequeById($id){
		$exe = executaSQL("SELECT numero FROM cheque WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->numero;
		}else{
			return false;
		}
	}
	
	function getLojaProcessamentoMensalSaldoInicial( $dataIni, $idLoja ){
	
		//CAPITAÇÃO, ACOMI E PLAM
		$idEmols = array(6,5,4);
		$valorTotal = $valorCap = $valorPlam = $valorAcomi = $valorCapRealizado = $valorPlamRealizado = $valorAcomiRealizado = $valorTotalRealizado = $valorTotalPrevisto = 0;
		
		//*****RECEITA PREVISTA******
		//NOTAS FATURADAS
		$exeRecNF = executaSQL("SELECT nd.id FROM notas_debito nd
								WHERE nd.id_faturado='".$idLoja."'
								AND nd.tipo_nota=1
								AND nd.id_situacao IN (2,4)
								AND nd.dt_faturamento < '".$dataIni."'
								AND EXISTS(SELECT 1 FROM notas_debito_item ndi WHERE ndi.id_nota_debito=nd.id AND ndi.id_emolumento IN(".implode(",", $idEmols)."))");
		if(nLinhas($exeRecNF)>0){
			while($regRecNF=objetoPHP($exeRecNF)){
				
				$exeItemNF = executaSQL("SELECT * FROM notas_debito_item WHERE id_nota_debito='".$regRecNF->id."'");
				if(nLinhas($exeItemNF)>0){
					while($regItemNF = objetoPHP($exeItemNF)){
						
						//CAPITACAO
						if($regItemNF->id_emolumento==4){
							$valorCap += $regItemNF->quantidade*$regItemNF->valor;
						}
						//PLAM
						if($regItemNF->id_emolumento==5){
							$valorPlam += $regItemNF->quantidade*$regItemNF->valor;
						}
						//ACOMI
						if($regItemNF->id_emolumento==6){
							$valorAcomi += $regItemNF->quantidade*$regItemNF->valor;
						}
						
					}
				}

				
			}
		}
		
		//NOTAS PAGAS
		$exeRecNF = executaSQL("SELECT nd.id FROM notas_debito nd
								WHERE nd.id_faturado='".$idLoja."'
								AND nd.tipo_nota=1
								AND nd.id_situacao=4
								AND nd.dt_pagamento < '".$dataIni."'
								AND EXISTS(SELECT 1 FROM notas_debito_item ndi WHERE ndi.id_nota_debito=nd.id AND ndi.id_emolumento IN(".implode(",", $idEmols)."))");
		if(nLinhas($exeRecNF)>0){			
			while($regRecNF=objetoPHP($exeRecNF)){
				
				$exeItemNF = executaSQL("SELECT * FROM notas_debito_item WHERE id_nota_debito='".$regRecNF->id."'");
				if(nLinhas($exeItemNF)>0){
					while($regItemNF = objetoPHP($exeItemNF)){

						//CAPITACAO
						if($regItemNF->id_emolumento==4){
							$valorCapRealizado += $regItemNF->quantidade*$regItemNF->valor;
						}
						//PLAM
						if($regItemNF->id_emolumento==5){
							$valorPlamRealizado += $regItemNF->quantidade*$regItemNF->valor;
						}
						//ACOMI
						if($regItemNF->id_emolumento==6){
							$valorAcomiRealizado += $regItemNF->quantidade*$regItemNF->valor;
						}
						
					}
				}
				
			}
		}
		$valorTotalPrevisto = $valorCap+$valorAcomi+$valorPlam;
		$valorTotalRealizado = $valorCapRealizado + $valorPlamRealizado + $valorAcomiRealizado;
		$valorTotal += $valorTotalRealizado-$valorTotalPrevisto;		
		
		return $valorTotal;
		
	}
	
	function temRelacaoDivisaoGestaoCargo($idDivisao){
		$exe = executaSQL("SELECT 1 FROM gestao_divisao WHERE id_divisao='".$idDivisao."'");
		$exe2 = executaSQL("SELECT 1 FROM gestao_divisao_cargo WHERE id_divisao='".$idDivisao."'");
		if(nLinhas($exe)>0 || nLinhas($exe2)>0){
			return true;
		}else{
			return false;
		}		
	}
	
	function temRelacaoGestaoCargo($id){
		$exe = executaSQL("SELECT 1 FROM gestao_divisao_cargo WHERE id_cargo='".$id."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}		
	}
	
	function temRelacaoGestaoDivisao($id){
		$exe = executaSQL("SELECT 1 FROM gestao_divisao WHERE id_divisao='".$id."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}		
	}
	
	function consultaCargoGestaoNomeById($id){
		$exe = executaSQL("SELECT valor FROM divisao_cargo WHERE id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function temRelacaoDivisaoGestaoCargoFilhos($idDivisao, $retorno=array()){
		
		$exe  = executaSQL("SELECT 1 FROM gestao_divisao WHERE id_divisao='".$idDivisao."'");
		$exe2 = executaSQL("SELECT 1 FROM gestao_divisao_cargo WHERE id_divisao='".$idDivisao."'");
		if(nLinhas($exe)>0 || nLinhas($exe2)>0){
			$retorno[] = 1;
		}else{
			$retorno[] = 2;
			//VERIFICA SE TEM FILHOS
			$exeFilhos = executaSQL("SELECT id FROM divisao WHERE id_pai='".$idDivisao."' ORDER BY id");
			if(nLinhas($exeFilhos)>0){
				while($regFilho = objetoPHP($exeFilhos)){
					$retorno = temRelacaoDivisaoGestaoCargoFilhos($regFilho->id, $retorno);
				}
			}
			
		}
		
		return $retorno;
		
	}
	
	function verificaGestaoCargoById($id){
		$exe = executaSQL("SELECT 1 FROM gestao_divisao_cargo WHERE id_gestao_divisao = '".$id."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}		
	}
	
	function consultaDivisaoById($id){
		$exe = executaSQL("SELECT * FROM divisao WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function verificaIrmaoCarteiraLoteAberto($idIrmao){
		$regs = executaSQL("SELECT 1 FROM carteira_lote cl, carteira_lote_irmao cli
							WHERE cli.id_irmao='".$idIrmao."'
							AND cl.id_situacao=1
							AND cl.id = cli.id_carteira_lote");
		if(nLinhas($regs)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getIdItemAcordo(){
		$exe = executaSQL("SELECT id FROM emolumentos WHERE acordo=1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id;
		}else{
			return 0;
		}
	}
	
	function consultaProcessamentoMensalById($id){
		$exe = executaSQL("SELECT * FROM capitacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaProcessamentoMensalNomeByIdCapLoja($id){
		$exe = executaSQL("SELECT id_cap FROM capitacao_loja WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return consultaProcessamentoMensalById(objetoPHP($exe)->id_cap)->titulo;
		}else{
			return false;
		}
	}
	
	function consultaNegociacaoParcelaById($id){
		$exe = executaSQL("SELECT * FROM negociacao_parcela WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaNegociacaoById($id){
		$exe = executaSQL("SELECT * FROM negociacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}

	function salvaNotaDebito($param, $translate){
		$nota['id']	= $param['nota']['id']>0 ? intval($param['nota']['id']) : NULL;
		
	//	1 - LOJA, 2 - IRMÃO
		if( isset($param['nota']['id_tipo']) && in_array($param['nota']['id_tipo'], array(1 ,2)) )
			$nota['id_tipo'] 	  		= intval($param['nota']['id_tipo']);
			
		if( isset($param['nota']['id_faturado']) )
			$nota['id_faturado'] 		= intval($param['nota']['id_faturado']);
			
		if( isset($param['nota']['obs']) )
			$nota['obs'] 		  		= trim($param['nota']['obs']);
	/*
		1 - Aberta, 2 - Faturada/Aguardando Pagamento, 3 - Cancelada, 4 - Paga
		Vide tabela "notas_debito_situacao"
	 */
		if( isset($param['nota']['id_situacao']) )
			$nota['id_situacao'] 		= intval($param['nota']['id_situacao']);
	//	1 - Entrega, 2 - Retirada
		if( isset($param['nota']['id_tipo_expedicao']) )
			$nota['id_tipo_expedicao']	= intval($param['nota']['id_tipo_expedicao']);
	/*	
		Tipo do processo
		Ex.: Iniciação, Regularização, Filiação...
		Vide table "processos"
	*/
		if( isset($param['nota']['id_processo_tipo']) )
			$nota['id_processo_tipo']	= intval($param['nota']['id_processo_tipo']);
	/*
		Id do processo
		Vide tabela do processo
	 */
		if( isset($param['nota']['id_processo']) )
			$nota['id_processo']		= intval($param['nota']['id_processo']);
		
		if( isset($param['nota']['id_tipo_expedicao']) )
			$nota['id_tipo_expedicao']	= intval($param['nota']['id_tipo_expedicao']);
		
		if( isset($param['nota']['dt_expedido']) )
			$nota['dt_expedido']		= converte_data($param['nota']['dt_expedido']);
		
		if( isset($param['nota']['mostra_relatorio']) )
			$nota['mostra_relatorio']	= intval($param['nota']['mostra_relatorio']);

		if( isset($param['nota']['dt_faturamento']) )
			$nota['dt_faturamento']		= converte_data($param['nota']['dt_faturamento']);

		if( isset($param['nota']['id_situacao_expedicao']) )
			$nota['id_situacao_expedicao']	= converte_data($param['nota']['id_situacao_expedicao']);
		
		if($nota['id']>0){
		//	Nota antes da alteração
			$notaOld = getNotaDebitoById($nota['id']);
		
			$nota['id_user_alteracao']	= $_SESSION['usuarioId'];
			$nota['dt_user_alteracao']	= date("YmdHis");
			
			$exe = alterarDados("notas_debito", $nota, "id='".$nota['id']."'");
			
		}else{
		//	Nota antes da alteração
			$reg = false;
			
			$nota['id']					= proximoId("notas_debito");
			$nota['id_user_criacao']	= $_SESSION['usuarioId'];
			$nota['dt_user_criacao']	= date("YmdHis");
			
			$exe = inserirDados("notas_debito", $nota);
		}
	
	//	Se Inserir/Alterou Nota de Débito
		if($exe){
		//	Inseri Endereço de entrega
			if( $nota['id_tipo_expedicao']==1 ){
				$notaEndereco['id_nota']		= $nota['id'];
				$notaEndereco['logradouro'] 	= trim($param['nota']['logradouro']);
				$notaEndereco['numero']			= trim($param['nota']['numero']);
				$notaEndereco['complemento']	= trim($param['nota']['complemento']);
				$notaEndereco['bairro']			= trim($param['nota']['bairro']);
				$notaEndereco['cep']			= trim($param['nota']['cep']);
				$notaEndereco['id_pais']		= intval($param['nota']['id_pais']);
				
				if($notaEndereco['id_pais']==30){	//BRASIL
					$notaEndereco['id_cidade']		= intval($param['nota']['id_cidade']);
				}else{
					$notaEndereco['cidade_fora']	= trim($param['nota']['cidade_fora']);
				}
				
				excluirDados("notas_debito_endereco", "id_nota = '".$nota['id']."'");
				$exe = inserirDados('notas_debito_endereco', $notaEndereco);
			}
			
			$itens = $param['nota']['item'];
			
			if( count($itens)>0 ){
				$NDitens = executaSQLPadrao("notas_debito_item", "id_nota_debito = '".$param['nota']['id']."'");
				while( $NDitem=objetoPHP($NDitens) ){
					excluirDados('notas_debito_item', "id='".$NDitem->id."'");
					excluirDados('notas_debito_item_nominal', "id_nota_debito_item='".$NDitem->id."'");
				}

				foreach($itens as $i=>$value){
					
					$idItem		= intval($itens[$i]['id']);
					$idEmol		= intval($itens[$i]['id_emolumento']);
					$qtde		= intval($itens[$i]['quantidade']);
					$valor		= floatval($itens[$i]['valor']);
					$situacao	= intval($itens[$i]['id_situacao']);
					$excluir	= $itens[$i]['excluir'];
					
					if($idEmol!='' && $qtde!='' && !$excluir>0){
						
					//	Se existir o emolumento
						$exe = executaSQLPadrao('emolumentos', "id = '".$idEmol."'");
						if(nLinhas($exe)>0){
							$reg = objetoPHP($exe);
							
						//	Se controla o estoque
							if($reg->tem_estoque==1){
								$totalAberto = objetoPHP( executaSQL('SELECT (SUM(quantidade) - SUM(qtde_entrega)) AS total FROM notas_debito_item i, notas_debito n
																		WHERE i.id_emolumento = "'.$idEmol.'"
																			AND n.id = i.id_nota_debito
																			AND i.id_situacao = 1
																			AND n.tipo_nota = 1
																			AND i.id_nota_debito != "'.$nota['id'].'"') )->total;
								$total = $reg->qtde_estoque - $totalAberto;
							}
							
							if( $reg->tem_estoque==2 || ($reg->tem_estoque==1 && $total>=$qtde) ){
								$dados = array();
								$dados['id']			 = $idItemNovo = proximoId("notas_debito_item");
								$dados['id_emolumento']	 = $idEmol;
								$dados['quantidade']	 = $qtde;
								$dados['id_nota_debito'] = $nota['id'];
								$dados['valor']			 = ($valor>0 ? $valor : getEmolumentoValorById($idEmol));
								$dados['id_situacao']	 = ($situacao>0 ? $situacao : $reg->expedir);
								inserirDados('notas_debito_item', $dados);
								
								//	Se for nominal ao(s) Irmão(s)
								if( count($itens[$i]['nominal'])>0 ){
									foreach($itens[$i]['nominal'] as $key=>$value){
										$dados = array();
										$dados['id_nota_debito_item']	= $idItemNovo;
										$dados['id_tipo']				= $tipo;
										$dados['nominal_irmao']			= $itens[$i]['nominal'][$key]['nominal_irmao'];
										$dados['nominal_loja']			= $itens[$i]['nominal'][$key]['nominal_loja'];
										inserirDados('notas_debito_item_nominal', $dados);
									}
									
								}
								
							}
							
						}
						
					}
					
				}
				
				//	( Se for inserção OU uma nota aberta ) E a nota foi faturada
				if( (!$notaOld || $notaOld->id_situacao==1 ) && $nota['id_situacao']==2){
					$CC = $param['contaCorrente'];					

					$CC['id_nota']			= $nota['id'];
					$CC['id_correntista']	= $nota['id_faturado'];
					$CC['tipo_cc']			= 1;
					$CC['credito_debito']	= 2;
					$CC['descricao']		= ( isset($CC['descricao']) ? $CC['descricao'] : $translate->translate('referente_a_nota_debito_nr')." ".$nota['id']);
					$CC['valor']			= getNotaValorById($nota['id']);
					$CC['valor_pagto']		= getNotaValorById($nota['id']);
					$CC['dt_pagamento']		= ( isset($CC['dt_pagamento']) ? $CC['dt_pagamento'] : date("Ymd") );
					
					inserirCC($CC, $translate);
					
					/*$boleto = $param['boleto'];
					$boleto['id_nota']		= $nota['id'];
					$boleto['id_sacado']	= $nota['id_faturado'];
					
					inserirBoleto($boleto, $translate);*/
				}
			}
			
			$retorno = array('status' => true, 'id' => $nota['id']);
			
	//	Se NÃO Inserir/Alterou Nota de Débito		
		}else{
			$retorno = array('status' => false);
		}
		
		return $retorno;
	}
	
	function inserirCC($CC, $translate){
		if( isset($CC['id_fatura']) )
			$dadosCC['id_fatura']		= intval($CC['id_fatura']);
			
		if( isset($CC['id_negociacao']) )
			$dadosCC['id_negociacao']		= intval($CC['id_negociacao']);
			
		if( isset($CC['id_provedor']) )
			$dadosCC['id_provedor']			= intval($CC['id_provedor']);
			
		if( isset($CC['id_categoria']) )
			$dadosCC['id_categoria']		= intval($CC['id_categoria']);
			
		if( isset($CC['id_transferencia']) )
			$dadosCC['id_transferencia']	= intval($CC['id_transferencia']);
			
		if( isset($CC['id_nota']) )	
			$dadosCC['id_nota'] 			= intval($CC['id_nota']);
			
		if( isset($CC['multa_juros']) )	
			$dadosCC['multa_juros'] 		= floatval($CC['multa_juros']);
			
		if( isset($CC['id_conta']) )	
			$dadosCC['id_conta'] 			= intval($CC['id_conta']);
		
		if( isset($CC['id_conta_pagar']) )	
			$dadosCC['id_conta_pagar'] 		= intval($CC['id_conta_pagar']);
		
		$dadosCC['id']						= proximoId("conta_corrente");
		$dadosCC['credito_debito'] 			= $CC['credito_debito'];
		$dadosCC['id_situacao'] 			= $CC['id_situacao'];
		$dadosCC['id_situacao_lancamento']  = $CC['id_situacao_lancamento'];
		$dadosCC['id_tipo'] 				= $CC['id_tipo'];
		$dadosCC['id_tipo_pagto']			= $CC['id_tipo_pagto'];
		$dadosCC['tipo_cc'] 				= $CC['tipo_cc'];
		$dadosCC['valor'] 					= $CC['credito_debito']==1 ? $CC['valor'] : -1*$CC['valor'];
		$dadosCC['valor_pagto']				= $CC['credito_debito']==1 ? $CC['valor_pagto'] : -1*$CC['valor_pagto'];
		$dadosCC['descricao'] 				= $CC['descricao'];
		$dadosCC['dt_pagamento']			= date("Ymd");
		
		$dadosCC['id_correntista'] 			= $CC['id_correntista'];
		$dadosCC['id_user_criacao']			= $_SESSION['usuarioId'];
		
		if( isset($CC['id_nota']) )
			alterarDados("notas_debito", array( ($CC['credito_debito']==1 ? "id_lanc_credito" :  "id_lanc_debito") =>$dadosCC['id']), "id='".$CC['id_nota']."'");
		
		$exe = inserirDados('conta_corrente', $dadosCC);
		
		$descricao = "<strong>".$translate->translate('acao').":</strong> ".$translate->translate('criacao_lancamento')."<br /> 
					  <strong>".$translate->translate('descricao').":</strong> ".$dadosCC['descricao']."
					| <strong>".$translate->translate('situacao').":</strong> ".$translate->translate('lancamento_situacao_'.$dadosCC['id_situacao_lancamento']).".
					| <strong>".$translate->translate('valor').":</strong> ".formatarDinheiro($CC['valor'], false).".";
		gravaHistoricoContaCorrente($dadosCC['id'], 1, $descricao, $_SESSION['usuarioId'], date('YmdHis'));

		if($exe)
			return true;
		else
			return false;
	}
	
	function inserirBoleto($boleto, $translate){
		
		$conta	= getInfoContaBancariaBoleto();
		$loja	= consultaLojaByCod($boleto['id_loja'], "cod, nome");
		
		if( isset($boleto['multa_juros']) )
			$dadosBoleto['multa_juros']		= floatval($boleto['multa_juros']);
		
		if( isset($boleto['id_baixa']) )
			$dadosBoleto['id_baixa']		= floatval($boleto['id_baixa']);
		
		$dadosBoleto['id_situacao'] 		= 1;
		$dadosBoleto['demonstrativo1'] 		= traducaoParams($translate->translate('boleto_referente_fatura'), array($boleto['id_fatura']));
		$dadosBoleto['demonstrativo2'] 		= trim($conta->demonstrativo2);
		$dadosBoleto['demonstrativo3']		= trim($conta->demonstrativo3);
		$dadosBoleto['instrucao1']			= trim($conta->instrucao1);
		$dadosBoleto['instrucao2'] 			= trim($conta->instrucao2);
		$dadosBoleto['instrucao3'] 			= trim($conta->instrucao3);
		$dadosBoleto['instrucao4'] 			= trim($conta->instrucao4);
		$dadosBoleto['numero']				= $conta->boleto_numero+1;
		$dadosBoleto['id_conta_bancaria']	= $conta->id;
		$dadosBoleto['valor'] 				= getFaturaValorById($boleto['id_fatura']);
		$dadosBoleto['dt_vencimento']		= addDayIntoDate(date("Y-m-d"), 14);
		
		$dadosBoleto['id_fatura'] 			= $boleto['id_fatura'];
		$dadosBoleto['id_loja'] 			= $boleto['id_loja'];
		$dadosBoleto['id_irmao_criacao']	= $_SESSION["usuarioId"];
		$dadosBoleto['dt_irmao_criacao']	= date("YmdHis");
		$dadosBoleto['id'] = proximoId('boleto');
		
		if(inserirDados('boleto', $dadosBoleto)){

			$descricao = "<strong>".$translate->translate("acao")."</strong>: ".$translate->translate("criacao_boleto")." <br />
							<strong>".$translate->translate("recurso_financeiro").": </strong> ".$conta->nome." 
							  | <strong>".$translate->translate("valor").":</strong> ".formatarDinheiro(getFaturaValorById($boleto['id_fatura']), true)." 
							  | <strong>".$translate->translate("data_vencimento").": </strong>".converte_data($dadosBoleto['dt_vencimento'])." 
							  | <strong>".$translate->translate("loja").": </strong>". formataNumeroComZeros($loja->cod, 4)." - ".$loja->nome;
			gravaHistoricoBoleto($dadosBoleto['id'], '1', $descricao, $_SESSION['usuarioId'], date('YmdHis'));
			
		}
		
		
	}
	

	function verificaIrmaoAguardandoPlamByLoja($id){
		$exe = executaSQL("SELECT 1 FROM pessoa 
							WHERE id_loja='".$id."'
							AND id_tipo=1
							AND id_situacao=21");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function consultaPagamentoItemById($id){
		$exe = executaSQL("SELECT * FROM pagamento_loja_item WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaPagamentoById($id){
		$exe = executaSQL("SELECT * FROM pagamento_loja WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	//VERIFICA SE A NOTA ESTÁ RELACIONADA A UM PAGAMENTO
	function verificaPagamentoChequeDevolvidoByNota($idNota){
	
		$exe = executaSQL("SELECT id_pagamento FROM pagamento_loja_notas_debito WHERE id_nota='".$idNota."'");
		if(nLinhas($exe)>0){
			
			$reg  = objetoPHP($exe);
			$exeP = executaSQL("SELECT 1 FROM pagamento_loja 
								WHERE id='".$reg->id_pagamento."'
								AND tem_cheque_devolvido=1");
			if(nLinhas($exeP)>0)
				return true;
			else
				return false;
		}else{
			return false;
		}
	}
	
	function consultaIdPagamentoByIdNota($idNota){
	
		$exe = executaSQL("SELECT id_pagamento FROM pagamento_loja_notas_debito WHERE id_nota='".$idNota."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->id_pagamento;			
		}else{
			return false;
		}
	}
	
	function getItensRecebidosPagamentoValorById($id){
		$exe = executaSQL("SELECT valor FROM pagamento_loja_item
							WHERE id_pagamento='".$id."'
							AND id_situacao_cheque=1");
		if(nLinhas($exe)>0){
			$total = 0;
			while($reg = objetoPHP($exe)){
				$total+= $reg->valor;
			}
			return $total;
		}else{
			return 0;
		}
	}
	
	function setItensPagamentoSituacao($idPagto, $sitAtual, $sitNova){
		$exe = executaSQL("SELECT id FROM pagamento_loja_item
							WHERE id_pagamento='".$idPagto."'
							AND id_situacao_cheque='".$sitAtual."'");
		while($reg = objetoPHP($exe)){
			alterarDados("pagamento_loja_item", array("id_situacao_cheque"=>$sitNova), "id='".$reg->id."'");
		}		
	}
	
	function cancelarLancamento($id, $motivo, $translate){
		$exe = executaSQLPadrao("notas_debito", "id = '".$id."'");
		
		if(nLinhas($exe)>0){
			$reg = objetoPHP($exe);
		
			if(verificaRelacaoNotaDebitoPagamento($reg->id))
				return array('status'=>false, 'msg'=>$translate->translate("msg_pagamento_relacionado"));
			
			if( temProcessoNaNotaByIdNota($reg->id) && !ehMason() )
				return array('status'=>false, 'msg'=>$translate->translate("msg_nota_relacionada_processo"));
			
			
			$ehNotaDevolucao = false;
			//SE AS SITUAÇÃO ESTIVER PAGA E FOR UMA NOTA DE DEVOLUÇÃO, VERIFICA SE EXISTE UM CONTAS A PAGAR RELACIONADO
			//SE NÃO EXISTIR NÃO PERMITE ACESSO
			if( $reg->id_situacao==4 && $reg->tipo_nota==3 ){
				
				$foo = executaSQL("SELECT 1 FROM conta_corrente WHERE id_nota='".$id."' AND tipo_cc=2 AND id_situacao_lancamento=2");
				if(nLinhas($foo)==0){
					return array('status'=>false, 'msg'=>$translate->translate("msg_nota_relacionada_processo"));
					
				}else{
					$ehNotaDevolucao = true;
				}
				
			}elseif($reg->id_situacao!=2){//FATURADA
				return array('status'=>false, 'msg'=>$translate->translate("msg_sem_registro"));
			}
			
			
			if(!$ehNotaDevolucao){
				$dados['id_user_distribuicao_exclusao'] = $_SESSION['usuarioId'];
				$dados['dt_user_distribuicao_exclusao'] = date("YmdHis");
				$dados['motivo_distribuicao_exclusao']  = trim($motivo);
				$dados['id_situacao'] 	   				= 1;//ABERTA
				
			}else{
				$dados['id_user_exclusao'] 	= $_SESSION['usuarioId'];
				$dados['dt_user_exclusao'] 	= date("YmdHis");
				$dados['motivo_exclusao']  	= trim($motivo);
				$dados['dt_pagamento']  	= "0000-00-00";				
				$dados['id_situacao'] 	   	= 3;//CANCELADA
			}
			
			if( alterarDados("notas_debito", $dados, "id='".$id."'") ){
				
				//EXCLUI O LANÇAMENTO RELACIONADO A NOTA
				$exeCC = executaSQL("SELECT id FROM conta_corrente WHERE id_nota='".$id."' AND id_situacao_lancamento NOT IN(3) ");
				if(nLinhas($exeCC)>0){
					while($regCC = objetoPHP($exeCC)){					
						$dados=array();
						$dados['id_user_exclusao'] 		 = $_SESSION['usuarioId'];
						$dados['dt_user_exclusao'] 		 = date("YmdHis");
						$dados['exclusao_motivo'] 		 = $translate->translate("cancelamento_lancamento_nd_nr").$id;
						$dados['id_situacao_lancamento'] = 3;
						
						alterarDados("conta_corrente", $dados, "id='".$regCC->id."'");
					}
				}
				
				//EXCLUI O BOLETO RELACIONADO A NOTA
				$exeBoleto = executaSQL("SELECT id FROM boleto WHERE id_nota='".$id."' AND id_situacao NOT IN(3)");
				if(nLinhas($exeBoleto)>0){
					$regBol = objetoPHP($exeBoleto);
					
					$dados=array();
					$dados['id_irmao_exclusao'] = $_SESSION['usuarioId'];
					$dados['dt_irmao_exclusao'] = date("YmdHis");
					$dados['exclusao_motivo']   = $translate->translate("cancelamento_lancamento_nd_nr").$id;
					$dados['id_situacao'] 	    = 3;
					
					if(alterarDados("boleto", $dados, "id='".$regBol->id."'")){
						$descricao = "<strong>".$translate->translate('acao').":</strong> ".$translate->translate('exclusao_boleto')."<br /> 
									  <strong>".$translate->translate('motivo').":</strong> ".$translate->translate('deivido_cancelamento_lancamento_cc_nota_debito_nr')." ".$id;
						gravaHistoricoBoleto($regBol->id, '5', $descricao, $_SESSION['usuarioId'], date('YmdHis'));
					}
				}
				
				return array('status'=>true, 'msg'=>$translate->translate("success"));
			}else {
				return array('status'=>false, 'msg'=>$translate->translate("msg_exclusao_erro"));
			}
			
		}else{
			return array('status'=>false, 'msg'=>$translate->translate("msg_sem_registro"));
		}
	}
	
	function excluirND($id, $motivo, $translate){
		$exe = executaSQLPadrao("notas_debito", "id = '".$id."'");
		if(nLinhas($exe)>0){
			$reg = objetoPHP($exe);
			
			if( ($reg->tipo_nota==1 && $reg->id_situacao!= 1) ||
				($reg->tipo_nota==2 && $reg->id_situacao!= 4) ||
				($reg->tipo_nota==3 && $reg->id_situacao!= 2) /*||
				verificaRelacaoEntrePagamentoENotaDevolucao($id)*/ ){
				
				return array('status'=>false, 'msg'=>$translate->translate("msg_sem_registro"));
			}
			
			$dados=array();
			$dados['id_user_exclusao'] = $_SESSION['usuarioId'];
			$dados['dt_user_exclusao'] = date("YmdHis");
			$dados['motivo_exclusao']  = $motivo;
			$dados['id_situacao'] 	   = 3;//CANCELADA
//			
			if( alterarDados("notas_debito", $dados, "id='".$id."'") ){
			/*	
				//EXCLUI O BOLETO RELACIONADO A NOTA
				$exeBoleto = executaSQL("SELECT id FROM boleto WHERE id_nota='".$id."' AND id_situacao NOT IN(3)");
				if(nLinhas($exeBoleto)>0){
					$regBol = objetoPHP($exeBoleto);
					
					$dados=array();
					$dados['id_irmao_exclusao'] = $_SESSION['usuarioId'];
					$dados['dt_irmao_exclusao'] = date("YmdHis");
					$dados['exclusao_motivo']   = $translate->translate("cancelamento_nd_nr").$id;
					$dados['id_situacao'] 	    = 3;
					
					if(alterarDados("boleto", $dados, "id='".$regBol->id."'")){
						
						$descricao = "<strong>".$translate->translate('acao').":</strong> ".$translate->translate('exclusao_boleto')."<br /> 
									  <strong>".$translate->translate('motivo').":</strong> ".$translate->translate('deivido_cancelamento_nota_debito_nr')." ".$id;
						gravaHistoricoBoleto($regBol->id, '5', $descricao, $_SESSION['usuarioId'], date('YmdHis'));
						
					}
				}
				
				//SE FOR UM AJUSTE FINANCEIRO, EXCLUI O LANÇAMENTO NA CC RELACIONADO
				if($reg->tipo_nota==2){
					
					$exeCC = executaSQL("SELECT id FROM conta_corrente WHERE id_nota='".$id."' AND id_situacao_lancamento = 2");
					if(nLinhas($exeCC)>0){
						$regCC=objetoPHP($exeCC);
						
						alterarDados("conta_corrente", array("id_situacao_lancamento"=>3, "exclusao_motivo"=>$motivo, "id_user_exclusao"=>$_SESSION['usuarioId'], "dt_user_exclusao"=>date("YmdHis")), "id='".$regCC->id."'");
						$descricao = "<strong>".$translate->translate('acao').":</strong> ".$translate->translate('cancelamento_pagamento')."<br /> 
									  <strong>".$translate->translate('motivo')."</strong> ".$motivo;
						gravaHistoricoContaCorrente($regCC->id, 1, $descricao, $_SESSION['usuarioId'], date('YmdHis'));
					
					}
				}
			*/
				return array('status'=>true, 'msg'=>$translate->translate("msg_exclusao_sucesso"));
			}else {
				return array('status'=>false, 'msg'=>$translate->translate("msg_exclusao_erro"));
			}
			
		}else{
			return array('status'=>false, 'msg'=>$translate->translate("msg_sem_registro"));
		}
	}
	
	function retornaChequeRegistradoById($idCheque){
		
		if($idCheque>0){
			alterarDados("cheque", array("id_situacao"=>1), "id='".$idCheque."'");
		}
		
	}
	
	function verificaItemPresencaByIdPresenca($id){
		$exe = executaSQL("SELECT 1 FROM presenca_item WHERE id_presenca='".$id."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function consultaSessoesByLoja($loja, $paramExtra=""){		
		return executaSQL("SELECT * FROM presenca
							WHERE loja='".$loja."'
							".$paramExtra."
							ORDER BY data, id DESC");
	}
	
	function getTipoSessaoNomeById($id){
		$exe = executaSQL("SELECT valor FROM presenca_tipo WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function consultaGraus(){
		return executaSQL("SELECT * FROM grau ORDER BY id");
	}
	
	function consultaVisitantesByLoja($loja, $campos=NULL, $paramExtra=""){
		if(count($campos)>0){
			$campos = (count($campos)>1 ? implode(",", $campos) : $campos[0]);
		}else{
			$campos = "*";
		}
		return executaSQL("SELECT ".$campos." FROM visitante
							WHERE id_loja='".$loja."'
							".$paramExtra."
							ORDER BY nome");
	}
	
	function consultaCertificadosPresencaByLoja($loja, $params=""){
		
		$exe = executaSQL("SELECT * FROM certificado
							WHERE loja_cod='".$loja."' 
							".$params."
							ORDER BY data DESC");
		return $exe;
	}
	
	function consultaPotenciaLojaByLoja($loja, $params=""){
		
		$exe = executaSQL("SELECT * FROM potencia_loja
							WHERE loja='".$loja."'
							".$params."
							ORDER BY nome");
		return $exe;
	}
	
	function consultaLojaLojaByLoja($loja, $params=""){
		
		$exe = executaSQL("SELECT * FROM loja_loja
							WHERE loja='".$loja."'
							".$params."
							ORDER BY nome");
		return $exe;
	}
	
	function getPotenciaLojaaById($id){
		$exe = executaSQL("SELECT * FROM potencia_loja WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getLojaLojaById($id){
		$exe = executaSQL("SELECT * FROM loja_loja WHERE id = '".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaInstrucoesByGrau($grau, $idVersao){
		return executaSQL("SELECT * FROM instrucao WHERE id_grau = '".$grau."' AND id_versao='".$idVersao."' ORDER BY ordem");
	}	
	
	function verificaInstrucaoPresenca($numeroInstrucao, $idPresenca){
		
		if($idPresenca>0){		
			$exe = executaSQL("SELECT 1 FROM presenca_instrucao WHERE id_instrucao = '".$numeroInstrucao."' AND id_presenca='".$idPresenca."'");
			if(nLinhas($exe)>0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	
	function consultaIrmaosPresenca($idPresenca){
		return executaSQL("SELECT p.id, p.nome, p.cim, p.id_grau as irmao_grau, p.id_versao,
								  pr.id_grau, 
								  i.id_tipo_presenc, i.presenca, i.observacoes , i.eh_gm
								FROM pessoa p, presenca_item i, presenca pr
								WHERE p.id = i.id_pessoa
									AND pr.id = i.id_presenca
									AND pr.id = '".$idPresenca."'
									AND i.id_tipo_presenc = 1
								ORDER BY i.eh_gm, p.nome");
	}
	
	function verificaIrmaoInstrucao($idIrmao, $idInstrucao){
		$exe = executaSQL("SELECT * FROM instrucao_irmao WHERE id_instrucao = '".$idInstrucao."' AND id_irmao='".$idIrmao."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
		
	}
	
	function verificaIrmaoInstrucaoByOrdem($idIrmao, $numeroInstrucao, $idGrau, $idVersao){
		$exe = executaSQL("SELECT * FROM instrucao_irmao 
							WHERE id_irmao='".$idIrmao."' 
							AND ordem = '".$idInstrucao."'
							AND id_grau='".$idGrau."'
							AND id_versao='".$idVersao."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
		
	}
	
	function verificaIrmaoInstrucaoByIdPresenca($idPresenca){
		$exe = executaSQL("SELECT * FROM instrucao_irmao WHERE id_presenca = '".$idPresenca."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function getInstrucaoByOrdem($idVersao, $ordem){
		$exe = executaSQL("SELECT * FROM instrucao 
							WHERE id_versao = '".$idVersao."'
							AND ordem = '".$ordem."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getInstrucaoById($id){
		$exe = executaSQL("SELECT * FROM instrucao WHERE id = '".$id."'");		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function getInstrucaoByNumeroGrau($numero, $grau){
		$exe = executaSQL("SELECT * FROM instrucao WHERE ordem = '".$numero."' AND id_grau='".$grau."'");		
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}	
	
	function getPresencaById($id){
	
		$exe = executaSQL("SELECT * FROM presenca WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
		
	}
	
	function consultaPresencaTipo(){
		return executaSQL("SELECT * FROM presenca_tipo");
	}
	
	function consultaPresencaCargo(){
		return executaSQL("SELECT * FROM cargo ORDER BY id");
	}
	
	function getPotenciaLojaByIdLoja($idLoja){
		$exe = executaSQL("SELECT pl.* FROM potencia_loja pl, loja_loja ll
							WHERE ll.id = '".$idLoja."'
							AND ll.id_potencia = pl.id");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}	
	
	function consultaPresencaInstrucao($idPresenca){
		return executaSQL("SELECT * FROM presenca_instrucao WHERE id_presenca='".$idPresenca."' ORDER BY id_instrucao");		
	}
	
	function getHierarquiaCargoByIdCargo($idCargo){
		$exe = executaSQL("SELECT hierarquia FROM cargo_rito WHERE id_cargo = '".$idCargo."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->hierarquia;
		}else{
			return false;
		}
	}
	
	function getOutroTipoCondecoracao($id){
		$exe = executaSQL("SELECT * FROM condecoracao_outro_tipo WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getTipoCondecoracao($id){
		$exe = executaSQL("SELECT * FROM condecoracao_tipo WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getLicencaByIdIrmaoData($idIrmao, $data){
		$exe = executaSQL("SELECT id FROM licenca 
							WHERE id_irmao = '".$idIrmao."' 
							AND '".$data."' BETWEEN dt_inicio AND dt_fim");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function verificaDispensaLicenca($idIrmao, $data){
		$exe = executaSQL("SELECT 1 FROM licenca WHERE id_irmao = '".$idIrmao."' AND '".$data."' BETWEEN dt_inicio AND dt_fim");
		if(nLinhas($exe)>0){
			return 1;
		}else{
			return false;
		}
	}
	
	function consultaIdadePessoaById($id){
		$idade=0;
		$exe = executaSQL("SELECT dt_nascimento FROM pessoa WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			
			$nascimento = objetoPHP($exe)->dt_nascimento;
			if($nascimento!='' && $nascimento!=NULL && $nascimento!='0000-00-00'){
				$idade =  intval(diasAteHoje(converte_data($nascimento))/365)*-1;
			}
			
		}
		
		return $idade;
	}
	
	function verificaDispensaIdade($idIrmao, $idLoja){
		$idadeDispensa 	= consultaLojaByCod($idLoja)->idade_dispensa_frequencia;
		$idadeIrmao 	= consultaIdadePessoaById($idIrmao);
		
		if($idadeDispensa>0 && $idadeIrmao>0){
			if($idadeIrmao >= $idadeDispensa)
				return true;
		}
		return false;
	}
	
	function getLicencaMotivoNomeById($id){
		$exe = executaSQL("SELECT valor FROM licenca_motivo WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function consultaLicencaMotivos(){
		return executaSQL("SELECT * FROM licenca_motivo 
							WHERE ativo = 1								
							ORDER BY valor");
	}
	
	function consultaColaboradores(){
		return executaSQL("SELECT * FROM pessoa 
							WHERE id_tipo=2
							AND id_situacao=1
							ORDER BY nome");
	}
	
	function getGraoMestreAtual(){
		$idGestaoAtual = consultaGestaoAtual()->id;
		if($idGestaoAtual>0){
			
			$exe = executaSQL("SELECT id FROM gestao_divisao WHERE id_gestao='".$idGestaoAtual."'");
			if(nLinhas($exe)>0){
				$idGestDivisao = objetoPHP($exe)->id;
				
				$exe2 = executaSQL("SELECT id_pessoa FROM gestao_divisao_cargo 
									WHERE id_gestao_divisao='".$idGestDivisao."'
									AND id_cargo=1
									AND (dt_termino='0000-00-00' OR dt_termino >= '".date("Y-m-d")."')
									LIMIT 1");
				if(nLinhas($exe2)>0){
					$reg2 = objetoPHP($exe2);
					return consultaPessoaById($reg2->id_pessoa);
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}else{
			return false;
		}
	}
	
	function getGmPresencaByIdPresenca($idPresenca){
		$exe = executaSQL("SELECT * FROM presenca_item WHERE id_presenca = '".$idPresenca."' AND eh_gm=1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	function consultaInstrucaoVersao(){
		return executaSQL("SELECT * FROM instrucao_versao ORDER BY id");
	}
	
	function consultaInstrucaoByIdVersaoIdGrau($idVersao, $idGrau){
		return executaSQL("SELECT * FROM instrucao WHERE id_versao='".$idVersao."' AND id_grau='".$idGrau."' ORDER BY ordem");
	}
	
	function getInstrucaoVersaoAtiva(){
		$exe = executaSQL("SELECT * FROM instrucao_versao WHERE ativo = 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
	}
	
	//VERIFICA SE O IRMÃO POSSUI ALGUMA INSTRUÇÃO PARA A VERSÃO PASSADA
	function verificaIrmaoInstrucaoByVersao($idVersao, $idIrmao){
		$exe = executaSQL("SELECT 1 FROM instrucao i , instrucao_irmao ii 
							WHERE i.id_versao = '".$idVersao."'
							AND ii.id_irmao='".$idIrmao."'
							AND i.id=ii.id_instrucao
							LIMIT 1");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function consultaIrmaoInstrucaoByIdPresencaIdIrmao($idIrmao, $idPresenca){
		return executaSQL("SELECT * FROM instrucao_irmao 
							WHERE id_presenca='".$idPresenca."'
							 AND id_irmao='".$idIrmao."'"); 
	}
	
	function consultaDocumentoCategoriaDaLoja(){
		return executaSQL("SELECT * FROM documento_categoria WHERE tipo=3 AND ativo=1 ORDER BY valor"); 
	}
	
	function getAgendaTipo($id){
		$exe = executaSQLPadrao("agenda_tipo", "id='".$id."'");
		
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getNomeReligiaoById($id){
		$exe = executaSQL("SELECT * FROM religiao WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getValorIdiomaById($id){
		$exe = executaSQL("SELECT valor FROM idioma WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}
	
	function getValorIdiomaProficienciaById($id){
		$exe = executaSQL("SELECT valor FROM idioma_proficiencia WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}	
	
	function consultaDocumentoCategoriaGL(){
		return executaSQL("SELECT * FROM documento_categoria WHERE tipo NOT IN(3) ORDER BY valor");
	}
	
	function consultaDocumentoCategoriaLoja(){
		return executaSQL("SELECT * FROM documento_categoria WHERE tipo IN(3) ORDER BY valor");
	}
	
	function consultaGestoes(){
		return executaSQL("SELECT * FROM gestao ORDER BY dt_inicio DESC");
	}
	
	function consultaComissoesRegimentares(){
		return executaSQL("SELECT * FROM comissao_regimentar ORDER BY ordem");
	}
	
	function consultaComissoesEspeciaisByLoja($idLoja){
		return executaSQL("SELECT * FROM comissao_especial WHERE id_loja='".$idLoja."' ORDER BY valor");
	}

	function getFaturaValorById($id){
		
		$valorTotal=0;
		$exeNDS = executaSQL("SELECT id_nota FROM fatura_notas_debito WHERE id_fatura='".$id."'");
		if(nLinhas($exeNDS)>0){
			while($regND=objetoPHP($exeNDS)){
				$valorTotal += getNotaValorById($regND->id_nota);
			}
		}
		
		return $valorTotal;
	
	}

	function getFaturaSituacaoValorById($id){
		$exe = executaSQL("SELECT valor FROM fatura_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else{
			return false;
		}
	}

	function verificaNotaDebitoFaturaById($idFechamento, $idNota, $idLoja){

		$tem = false;

		$exe = executaSQL("SELECT id_fatura FROM fechamento_mensal_fatura WHERE id_fechamento = '".$idFechamento."'");
		if(nLinhas($exe)>0){
			while($reg = objetoPHP($exe)){

				$exeFatura = executaSQL("SELECT 1 FROM fatura f, fatura_notas_debito fnd 
											WHERE f.id='".$reg->id_fatura."'
											AND f.id_situacao NOT IN(3)											
											AND f.id = fnd.id_fatura
											AND fnd.id_nota = '".$idNota."'");
				if(nLinhas($exeFatura)>0){
					$tem=true;
				}

			}
		}else{
			$tem = true;
		}

		return $tem;

	}

	function getFaturaLojaByIdFechamento($idFechamento, $idLoja){

		$exeFatura = executaSQL("SELECT f.* FROM fatura f, fechamento_mensal_fatura fmf 
                                    WHERE fmf.id_fechamento='".$idFechamento."'
                                    AND f.id_lancado='".$idLoja."'
                                    AND f.id = fmf.id_fatura
                                    AND f.id_situacao NOT IN (3)");
		if(nLinhas($exeFatura)>0){
			return objetoPHP($exeFatura);
		}else{
			return false;
		}
	}

	//VERIFICA SE A FATURA FOI PAGA POR BOLETO OU APENAS PELA FATURA MESMO
	function verificaFaturaTipoPagamento($idFatura){

		$exeBoleto = executaSQL("SELECT id, numero FROM boleto 
                                    WHERE id_fatura='".$idFatura."'
                                    AND id_situacao =2");
		if(nLinhas($exeBoleto)>0){
			$bol = objetoPHP($exeBoleto);
			return array("tipo"=>1, "idBoleto"=>$bol->id , "numero"=>$bol->numero);
		}else{
			return array("tipo"=>2);
		}
	}

	//POSSÍVEIS SITUAÇÕES DA NOTA DE DÉBITO
    //  1 - NÃO RELACIONADA A NENHUM FECHAMENTO
    //  2 - RELACIONADA A ESTE FECHAMENTO
    //  3 - RELACIONADA A OUTRO FECHAMENTO
	function verificaNotaDebitoFechamentoById($idFechamento, $idNota){

		$situacao = 1;

		//VERIFICA SE A NOTA ESTÁ RELACIONADA A UMA FATURA DO MESMO FECHAMENTO
		if($idFechamento>0){
			
			$exeFechamento = executaSQL("SELECT 1 FROM fechamento_mensal_fatura fmf, fatura f, fatura_notas_debito fnd
											WHERE fmf.id_fechamento = '".$idFechamento."'
											AND fmf.id_fatura = f.id 
											AND f.id = fnd.id_fatura
											AND fnd.id_nota = '".$idNota."'
											AND f.id_situacao NOT IN (3) ");
			if(nLinhas($exeFechamento)>0){
				$situacao=2;
			}

		}
		
		//VERIFICA SE A NOTA ESTÁ RELACIONADA A OUTRO FECHAMENTO
		if($situacao == 1){

			$exeFechamento = executaSQL("SELECT 1 FROM fechamento_mensal_fatura fmf, fatura f, fatura_notas_debito fnd
											WHERE fnd.id_nota = '".$idNota."'
											AND fmf.id_fatura = f.id 
											AND f.id = fnd.id_fatura
											AND f.id_situacao NOT IN (3) ");
			if(nLinhas($exeFechamento)>0){
				$situacao=3;
			}

		}

		return $situacao;
	}

	//VERIFICA SE EXISTE ALGUMA FATURA PAGA PARA O FECHAMENTO
	function verificaFaturaPagamentoByIdFechamento($idFechamento){

		$exeBoleto = executaSQL("SELECT 1 FROM fechamento_mensal_fatura fmf, fatura f 
                                    WHERE fmf.id_fechamento='".$idFechamento."'
                                    AND fmf.id_fatura = f.id
                                    AND f.id_situacao=2");
		if(nLinhas($exeBoleto)>0){
			return true;
		}else{
			return false;
		}
	}

	function lojaBloqueadaParaSolicitarProcessos($loja=NULL){
		if($loja==NULL)
			$loja = $_SESSION['loja_sge'];

		$inics = executaSQL("SELECT 1 FROM iniciacao WHERE id_loja = '".$loja."' AND id_situacao = 2 AND data < '".subDayIntoDate(date('Y-m-d'), 5)."'");
		if( nLinhas($inics)>0 ){
			return true;
		}

		return false;
	}

	function consultaQtdeNotasDisponiveisFaturamento($idLoja, $dataBase){

		$valor = $qtde = 0;

		$exe = executaSQL("SELECT SUM(ndi.valor*ndi.quantidade) as valorTotal
						   	FROM notas_debito nd, notas_debito_item ndi
							WHERE nd.id_faturado = '".$idLoja."'							
							AND nd.dt_faturamento <= '".$dataBase."'
							AND nd.id_situacao=2
							AND nd.id = ndi.id_nota_debito
							AND NOT EXISTS(
											SELECT 1 FROM fatura f, fatura_notas_debito fnd 
											WHERE f.id_situacao NOT IN(3)
											AND f.id = fnd.id_fatura
											AND fnd.id_nota = nd.id
											)");
		if(nLinhas($exe)>0){
			$valor = objetoPHP($exe)->valorTotal;
		}

		$exe = executaSQL("SELECT COUNT(*) as quantidade
						   	FROM notas_debito nd
							WHERE nd.id_faturado = '".$idLoja."'							
							AND nd.dt_faturamento <= '".$dataBase."'
							AND nd.id_situacao=2
							AND NOT EXISTS(
											SELECT 1 FROM fatura f, fatura_notas_debito fnd 
											WHERE f.id_situacao NOT IN(3)
											AND f.id = fnd.id_fatura
											AND fnd.id_nota = nd.id
											)");
		if(nLinhas($exe)>0){
			$qtde = objetoPHP($exe)->quantidade;
		}

		return array($qtde, $valor);

	}

	function consultaNotasDisponiveisFaturamentoByIdFaturamento($idFechamento, $idLoja){

		$valor = $qtde = 0;

		$exe = executaSQL("SELECT f.*
							FROM fechamento_mensal_fatura fmf, fatura f
							WHERE fmf.id_fechamento = '".$idFechamento."'
							AND f.id_lancado  = '".$idLoja."'
							AND fmf.id_fatura = f.id
							AND f.id_situacao NOT IN(3)");
		if(nLinhas($exe)>0){
			while($reg = objetoPHP($exe)){
				
				$exe2 = executaSQL("SELECT SUM(ndi.valor*ndi.quantidade) as valorTotal
									FROM fatura_notas_debito fnd, notas_debito_item ndi
									WHERE fnd.id_fatura = '".$reg->id."'
									AND fnd.id_nota = ndi.id_nota_debito");
				if(nLinhas($exe2)>0){
					$valor 	+= objetoPHP($exe2)->valorTotal;
				}

				$exe2 = executaSQL("SELECT COUNT(*) as quantidade
									FROM fatura_notas_debito fnd
									WHERE fnd.id_fatura = '".$reg->id."'");
				if(nLinhas($exe2)>0){
					$qtde 	+= objetoPHP($exe2)->quantidade;
				}

			}
		}

		return array($qtde, $valor);

	}

	function getBoletoByIdFatura($id){
		$exe = executaSQL("SELECT * FROM boleto WHERE id_fatura='".$id."' AND id_situacao NOT IN (3)");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else
			return false;
	}

	function getCCByIdFatura($idFatura){
		$exe = executaSQL("SELECT * FROM conta_corrente WHERE id_fatura='".$id."' AND id_situacao_lancamento NOT IN (3)");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else
			return false;
	}


	function verificaSegmentoLojista($id){	
		$regs2 = executaSQL("SELECT 1 FROM loja WHERE id_loja_segmento = '".$id."'");
		if(nLinhas($regs2)>0){
			return true;
		}else{
			return false;
		}
	}

	function getLojaSegmentoById($id){
		$exe = executaSQL("SELECT * FROM loja_segmento WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else
			return false;
	}

	function getLojaSituacaoById($id){
		$exe = executaSQL("SELECT valor FROM loja_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->valor;
		}else
			return false;
	}

	function getEventoSituacaoById($id){
		$exe = executaSQL("SELECT * FROM evento_situacao WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else
			return false;
	}

	function verificaLojaParticipanteByIdLoja($idEvento, $idLoja){
		$exe = executaSQL("SELECT 1 FROM evento_loja WHERE id_evento='".$idEvento."' AND id_loja='".$idLoja."'");
		if(nLinhas($exe)>0){
			return true;
		}else
			return false;
	}
	
	function getEventoById($idEvento){
		$exe = executaSQL("SELECT * FROM evento WHERE id ='".$idEvento."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
			
	}

	function getParticipanteById($id){
		$exe = executaSQL("SELECT * FROM participante WHERE id='".$id."'");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
			
	}

	function getEventoAtual(){
		$exe = executaSQL("SELECT * FROM evento WHERE '".date("Y-m-d")."' BETWEEN dt_inicio AND dt_termino ");
		if(nLinhas($exe)>0){
			return objetoPHP($exe);
		}else{
			return false;
		}
			
	}

	function getProximoNumeroOrdemMenu($idEvento){
		$exe = executaSQL("SELECT ordem FROM menu WHERE id_evento='".$idEvento."' ORDER BY ordem DESC LIMIT 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->ordem+1;
		}else{
			return 0;
		}
			
	}

	function getProximoNumeroOrdemFAQ($idEvento){
		$exe = executaSQL("SELECT ordem FROM perguntas_respostas WHERE id_evento='".$idEvento."' ORDER BY ordem DESC LIMIT 1");
		if(nLinhas($exe)>0){
			return objetoPHP($exe)->ordem+1;
		}else{
			return 0;
		}
			
	}

	function verificaEventoSorteio($dataSorteio){
		$exe = executaSQL("SELECT 1 FROM evento_sorteio WHERE sorteio_data = '".$dataSorteio."' AND sorteio_nr_extracao IS NOT NULL");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
			
	}

	function verificaSorteioLoteriaData($dataSorteio){
		$exe = executaSQL("SELECT 1 FROM sorteio_loteria WHERE sorteio_data = '".$dataSorteio."'");
		if(nLinhas($exe)>0){
			return true;
		}else{
			return false;
		}
			
	}

	function consultaLojaByCampanhaCNPJ($campanha, $cnpj, $campos="*"){
		$reg = executaSQL("SELECT $campos FROM evento_loja e, loja l WHERE id_evento='".$campanha."' AND e.id_loja=l.id AND REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$cnpj."', '.', ''), '/', ''), '-', '') ");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}	
	
	function getCupomSituacao($id){			
		$reg = executaSQL("SELECT * FROM cupom_situacao WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}	


	function getElementosByCampanhaTipoCupom($campanhaTipo, $id){

		$regs = executaSQL("SELECT elemento FROM elemento_sorteavel WHERE id_participante_cupom='".$id."'");
		if (nLinhas($regs)>0){
			$elems = '';
			while( $reg = objetoPHP($regs) ){
				$elems .= $reg->elemento."<br>";
			}
			return $elems;
		}else{
			return "";
		}

	}	


	function consultaTipoPeloNome($tipoNome){
		return objetoPHP(executaSQL("SELECT * FROM participacao_tipo WHERE valor='".$tipoNome."' "))->id;
	}
?>