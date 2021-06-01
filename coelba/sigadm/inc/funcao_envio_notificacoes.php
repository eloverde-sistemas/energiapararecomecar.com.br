<? //FUNÇÕES QUE ENVIAM EMAILS DAS NOTIFICAÇÕES
	include_once('bancofuncoes.php');
	
	function emailBoletim($id, $msg, $emails=NULL, $translate){
		
		require_once("lib/phpMailer/PHPMailerAutoload.php");
		
		if($reg->bol_newsletter==NULL && $emails==NULL){
			$dados['bol_newsletter'] = date('Y-m-d H:i:s');
		}
		
		if($reg->publicado==NULL && $emails==NULL){
			$dados['publicado'] = 1;
			$dados['data_publicado_portal']	= date('Y-m-d H:i:s');
			$dados['id_user_publicacao'] =  $_SESSION["pessoaId"];
		}
		
		if(count($dados)>0){
			alterarDados('boletim', $dados, "id = '".$id."'");
		}
		
		$reg = objetoPHP( executaSQLPadrao('boletim', "id = '".$id."'") );
		
		$assunto = $translate->translate("notif_boletim_assunto");	
		
		$emailsIrmaos=array();
		if($emails==NULL){
			
			$destinatario = $_SESSION['mail-nao-resp'];
			
			$irmaos = consultaIrmaosAtivos();
			while($irmao=objetoPHP($irmaos)){
				if($irmao->email!='' && $irmao->email!=NULL){
					$emailsIrmaos[] = $irmao->email;					
				}
			}
			
		}else{
			$destinatario = $emails;
		}		
		
		$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", $emailsIrmaos);
	}
	
	function emailCandidatoAprovacao($msg, $emails, $translate){
			
		require_once("lib/phpMailer/PHPMailerAutoload.php");
		
		$assunto = $translate->translate("avaliacao_candidato");
		
		if(count($emails)>0){
			
			$destinatario = $emails[0];
			
			if(count($emails)>1){
				$cco = array();
				foreach($emails as $email){
					if($email!='' && $email!=NULL && $email!=$destinatario){
						$cco[] = $email;
					}
				}
			}
			//$destinatario = "lucas@masonweb.inf.br";
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", $cco);
		}		
		
	}
	
	function emailCandidatoPropostaAdmissaoProponente($idCand, $translate){
		
		if($idCand>0){
			
			$cand = objetoPHP( executaSQLPadrao('candidatos', 'id = "'.$idCand.'"') );
			
			require_once("lib/phpMailer/PHPMailerAutoload.php");
			
			$assunto = $translate->translate("proposta_admissao");
			
		//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = $cand->nome;
			
			$msg = toHTML(traducaoParams($translate->translate('email_proposta_admissao_proponente'), $params));
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "");
		}
	}
	
	function emailCandidatoEditalSindicancia($idCand, $translate, $json=0){
		
		if($idCand>0){
			
			$cand = objetoPHP( executaSQLPadrao('candidatos', 'id = "'.$idCand.'"') );
			$loja = consultaLojaByCod($cand->id_loja);
			
			if($json)
				include_once("../lib/phpMailer/PHPMailerAutoload.php");
			else
				include_once("lib/phpMailer/PHPMailerAutoload.php");
			
			$assunto = $translate->translate("edital_sindicancia");
			
		//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = formataNumeroComZeros($loja->cod, 4).' - '.$loja->nome;
			$params[] = $cand->nome;
			
			$msg = toHTML(traducaoParams($translate->translate('email_candidato_edital_sindicancia'), $params));
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		}
		
	}
	
	function emailCandidatoEditalSindicanciaBoletim($idCand, $translate, $json=0){
		
		if($idCand>0){
			
			$cand	= objetoPHP( executaSQLPadrao('candidatos', 'id = "'.$idCand.'"') );
			$bol	= getBoletimById($cand->boletim_edital);
			
			if($json)
				include_once("../lib/phpMailer/PHPMailerAutoload.php");
			else
				include_once("lib/phpMailer/PHPMailerAutoload.php");
			
			$assunto = $translate->translate("edital_sindicancia");
			
		//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = $cand->nome;
			$params[] = $bol->cod;
			$params[] = converte_data($bol->data_publicacao);
			
			$msg = toHTML(traducaoParams($translate->translate('email_candidato_edital_sindicancia_boletim'), $params));
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		}
		
	}
	
	function emailSolicitacaoIniciacao($idInic, $translate, $json=0){
		
		if($idInic>0){
			
			$inic = objetoPHP( executaSQLPadrao('iniciacao', 'id = "'.$idInic.'"') );
			$loja = consultaLojaByCod($inic->id_loja);
			
			$itens = executaSQL("SELECT c.nome FROM item_iniciacao i, candidatos c
									WHERE i.id_iniciacao = '".$inic->id."'
										AND i.id_candidato = c.id");
			while($item = objetoPHP($itens)){
				$cands[] = $item->nome;
			}
			
			if($json)
				include_once("../lib/phpMailer/PHPMailerAutoload.php");
			else
				include_once("lib/phpMailer/PHPMailerAutoload.php");
			
			$assunto = $translate->translate("solicitacao_iniciacao");
			
			//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = formataNumeroComZeros($loja->cod, 4).' - '.$loja->nome;
			$params[] = implode(', ', $cands);
			
			$msg = toHTML(traducaoParams($translate->translate('email_solicitacao_iniciacao'), $params));
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
			
		}
		
	}
	
	function emailSolicitacaoInstalacao($idInic, $translate, $json=0){
		
		if($idInic>0){
			
			$reg = objetoPHP( executaSQLPadrao('instalacao', 'id = "'.$idInic.'"') );
			$loja = consultaLojaByCod($reg->id_loja);
			
			if($json)
				include_once("../lib/phpMailer/PHPMailerAutoload.php");
			else
				include_once("lib/phpMailer/PHPMailerAutoload.php");
			
			$assunto = $translate->translate("solicitacao_iniciacao");
			
			//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = formataNumeroComZeros($loja->cod, 4).' - '.$loja->nome;
			$params[] = consultaPessoaNomeById($reg->id_irmao);
			
			$msg = toHTML(traducaoParams($translate->translate('email_solicitacao_iniciacao'), $params));
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
			
		}
		
	}
	
	function emailConfirmacaoPagamento($idPag, $translate, $json=0){
		$regs = executaSQL('SELECT n.id, n.obs, n.id_processo_tipo FROM pagamento_loja_notas_debito p, notas_debito n
								WHERE p.id_pagamento = "'.$idPag.'"
									AND n.id = p.id_nota');
		while($reg = objetoPHP($regs)){
			$notas[] = '<br /><br /> - '.$translate->translate('numero').' '.$reg->id.', '.($reg->id_processo_tipo==19 ? $translate->translate('solicitacao_materiais') : $reg->obs);
		}
		
		if($json)
			include_once("../lib/phpMailer/PHPMailerAutoload.php");
		else
			include_once("lib/phpMailer/PHPMailerAutoload.php");
			
		$assunto = $translate->translate("lancamento_pagamento");
		
	//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
		$destinatario = 'angelo@masonweb.inf.br';
		
		$params = array();
		$params[] = implode('', $notas);
		
		$msg = toHTML( traducaoParams($translate->translate('email_lancamento_pagamento'), $params) );
		
		$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
	}
	
	function emailExpedicaoLoja($idExp, $translate, $json=0){
		
		$regs = executaSQL("SELECT em.nome, n.id_nota_debito, ex.qtde FROM expedicao_item ex, notas_debito_item n, emolumentos em
								WHERE ex.id_expedicao = '".$idExp."'
									AND ex.id_item = n.id
									AND n.id_emolumento = em.id");
		
		$idNota = NULL;
		while($reg = objetoPHP($regs)){
			$item[] = '<br /><br /> - '.$reg->qtde.' '.$reg->nome;
			if(!$idNota)
				$idNota = $reg->id_nota_debito;
		}
		
		if($json)
			include_once("../lib/phpMailer/PHPMailerAutoload.php");
		else
			include_once("lib/phpMailer/PHPMailerAutoload.php");
			
		$assunto = $translate->translate("expedicao_nota");
		
	//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
		$destinatario = 'angelo@masonweb.inf.br';
		
		$params = array();
		$params[] = $idNota;
		$params[] = count($item)>1 ? implode('', $item) : $item[0];
		
		$msg = toHTML( traducaoParams($translate->translate('email_expedicao_nota'), $params) );
		
		$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
	}
	
	function emailIniciacaoBoletim($id, $bol, $translate, $json=0){
		
		if($id>0){
			$regs = executaSQL("SELECT c.nome FROM item_iniciacao i, candidatos c
									WHERE i.id_iniciacao = '".$id."'
										AND i.id_candidato = c.id");
			
			while($reg = objetoPHP($regs)){
				$item[] = $reg->nome;
			}
			
			if($json)
				include_once("../lib/phpMailer/PHPMailerAutoload.php");
			else
				include_once("lib/phpMailer/PHPMailerAutoload.php");
				
			$assunto = $translate->translate("iniciacao_inserida_boletim");
			
		//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = $bol;
			$params[] = implode(', ', $item);
			
			$msg = toHTML( traducaoParams($translate->translate('email_iniciacao_boletim'), $params) );
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		}
	}
	
	function emailProducaoIniciacao($idPessoa, $translate, $json=0){
		
			$pessoa = objetoPHP( executaSQL("SELECT nome, cim FROM pessoa WHERE id = '".$idPessoa."'") );
		
			if($json)
				include_once("../lib/phpMailer/PHPMailerAutoload.php");
			else
				include_once("lib/phpMailer/PHPMailerAutoload.php");
				
			$assunto = $translate->translate("iniciacao_confirmada");
			
		//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = $pessoa->nome;
			$params[] = $_SESSION['POTENCIA']->sigla;
			$params[] = $pessoa->cim;
			
			$msg = toHTML( traducaoParams($translate->translate('email_producao_iniciacao_loja'), $params) );
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		
	}
	
	function emailAumentoSalarioBoletim($id, $bol, $translate, $json=0){
		
		if($id>0){
			$aumSal = objetoPHP( executaSQL("SELECT id_tipo FROM aumento_salario WHERE id = '".$id."'") );
			
			$regs = executaSQL("SELECT p.nome FROM aumento_salario_item a, pessoa p
									WHERE a.id_aumento_salario = '".$id."'
										AND a.id_pessoa = p.id");
			
			while($reg = objetoPHP($regs)){
				$item[] = $reg->nome;
			}
			
			if($json)
				include_once("../lib/phpMailer/PHPMailerAutoload.php");
			else
				include_once("lib/phpMailer/PHPMailerAutoload.php");
				
			$assunto = $translate->translate("aumento_salario_inserido_boletim");
			
		//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
			$destinatario = 'angelo@masonweb.inf.br';
			
			$params = array();
			$params[] = $bol;
			$params[] = implode(', ', $item);
			$params[] = $aumSal->id_tipo;
			
			$msg = toHTML( traducaoParams($translate->translate('email_aumento_salario_boletim'), $params) );
			
			$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		}
	}
	
	function emailProducaoAumentoSalario($nome, $grau, $translate, $json=0){
		
		if($json)
			include_once("../lib/phpMailer/PHPMailerAutoload.php");
		else
			include_once("lib/phpMailer/PHPMailerAutoload.php");
			
		$assunto = $translate->translate("aumento_salario_confirmada");
		
	//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
		$destinatario = 'angelo@masonweb.inf.br';
		
		$params = array();
		$params[] = $nome;
		$params[] = $_SESSION['POTENCIA']->sigla;
		$params[] = $grau;
		
		$msg = toHTML( traducaoParams($translate->translate('email_producao_aumento_salario_loja'), $params) );
		
		$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		
	}
	
	function emailReadmitirIrmao($nome, $tipo, $translate, $json=0){
		
		if($json)
			include_once("../lib/phpMailer/PHPMailerAutoload.php");
		else
			include_once("lib/phpMailer/PHPMailerAutoload.php");
			
		$assunto = traducaoParams($translate->translate("_confirmada"), array($translate->translate('regularizacao_'.$tipo)));
		
	//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
		$destinatario = 'angelo@masonweb.inf.br';
		
		$params = array();
		$params[] = $translate->translate('regularizacao_'.$tipo);
		$params[] = $nome;
		$params[] = $_SESSION['POTENCIA']->sigla;
		
		$msg = toHTML( traducaoParams($translate->translate('email_producao_readmitir_loja'), $params) );
		
		$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		
	}
	
	function emailAfastarIrmao($nome, $tipo, $translate, $json=0){
		
		if($json)
			include_once("../lib/phpMailer/PHPMailerAutoload.php");
		else
			include_once("lib/phpMailer/PHPMailerAutoload.php");
			
		$assunto = traducaoParams($translate->translate("_confirmado_a"), array(getAfastamentoTipoById($tipo)));
		
	//	$destinatario = getEmailVeneravelByIdLoja($cand->id_loja);
		$destinatario = 'angelo@masonweb.inf.br';
		
		$params = array();
		$params[] = getAfastamentoTipoById($tipo);
		$params[] = $nome;
		$params[] = $_SESSION['POTENCIA']->sigla;
		
		$msg = toHTML( traducaoParams($translate->translate('email_producao_afastar_loja'), $params) );
		
		$enviaEmail = enviaEmail($assunto, $msg, $destinatario, "", NULL, NULL, NULL, $json);
		
	}
?>