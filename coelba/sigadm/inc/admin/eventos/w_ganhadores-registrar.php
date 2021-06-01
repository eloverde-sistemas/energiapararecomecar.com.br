<?
	$id = intval($_GET['id']);
	
	if($id>0){
		$exe = executaSQL("SELECT * FROM evento_sorteio WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			$premio = objetoPHP($exe);
			
			//VERIFICA SE TEM O NÚMERO DA SORTE REGISTRADO CONFORME O REGULAMENTO
			if( $premio->sorteio_nr_extracao>0 ){
				
				$qtdeGanhadores = 0;
				
				$qtdePremio = $premio->qtde_premio;
				
				$qtdeGanhadoresNaoReprovados = executaSQL("SELECT COUNT(*) as total FROM evento_ganhador eg WHERE eg.id_sorteio='".$premio->id."' AND eg.id_situacao<>'3'");
				
				if(nLinhas($qtdeGanhadoresNaoReprovados)>0){
					//echo "Qtde Não Reprovado: ".
					$qtdePremio = $qtdePremio - objetoPHP($qtdeGanhadoresNaoReprovados)->total;
				}
				
				//Verifica se precisa buscar mais ganhadores
				if( $qtdePremio >0 ){
					
					$ganhadores = executaSQL("SELECT es.id as idElemento, pc.id as idCupom 
												FROM elemento_sorteavel es, participante_cupom pc 
												WHERE es.id_evento = '".$premio->id_evento."' 
												AND es.id_participante_cupom>0
												AND es.id_participante_cupom=pc.id
												AND pc.dt_cadastro <= '".$premio->sorteio_data." 00:00:00'
												AND es.elemento>='".$premio->sorteio_nr_extracao."' 
												AND NOT EXISTS( SELECT 1 FROM evento_ganhador eg, evento_sorteio s 
																	WHERE eg.id_sorteio='".$premio->id."' 
																	AND eg.id_sorteio=s.id
																	AND s.id_evento=es.id_evento
																	AND eg.id_elemento=es.id
																  )
												ORDER BY elemento LIMIT $qtdePremio");
					if( nLinhas($ganhadores)>0 ){
						while( $ganhador = objetoPHP($ganhadores) ){
							$qtdeGanhadores++;
							inserirDados("evento_ganhador", array('id_sorteio'=>$premio->id, 'id_participante_cupom'=>$ganhador->idCupom, 'id_elemento'=>$ganhador->idElemento));
						}
					}
					
					//Verifica se não achou pra cima e volta do zero
					//echo "Menor?: ".$qtdeGanhadores<$qtdePremio;
					if($qtdeGanhadores<$qtdePremio){
						
						//echo "Restante: ".
						$qtdeRestante = ($qtdePremio - $qtdeGanhadores);
						
						$ganhadores = executaSQL("SELECT es.id as idElemento, pc.id as idCupom FROM elemento_sorteavel es, participante_cupom pc 
													WHERE es.id_evento = '".$premio->id_evento."' 
													AND es.id_participante_cupom>0
													AND es.id_participante_cupom=pc.id
													AND pc.dt_cadastro <= '".$premio->sorteio_data." 00:00:00'
													AND es.elemento>='0' 
													AND NOT EXISTS( SELECT 1 FROM evento_ganhador eg, evento_sorteio s 
																	WHERE eg.id_sorteio='".$premio->id."' 
																	AND eg.id_sorteio=s.id
																	AND s.id_evento=es.id_evento
																	AND eg.id_elemento=es.id
																  )
													ORDER BY elemento LIMIT $qtdeRestante");
						if( nLinhas($ganhadores)>0 ){
							while( $ganhador = objetoPHP($ganhadores) ){
								$qtdeGanhadores++;
								inserirDados("evento_ganhador", array('id_sorteio'=>$premio->id, 'id_participante_cupom'=>$ganhador->idCupom, 'id_elemento'=>$ganhador->idElemento));
							}
						}
					}
					
				}
				
				
				if($qtdeGanhadores==$qtdePremio){
					setarMensagem(array($translate->translate('msg_ganhador_sucesso')), "success");
				}else{
					setarMensagem(array($translate->translate('msg_ganhador_nao_encontrado')), "error");
				}
			}else{
				setarMensagem(array($translate->translate('msg_sem_numero_sorte_definido')), "error");
			}

			header("Location: /adm/admin/eventos/ganhadores/".$premio->id_evento);
			die();
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	}
?>	