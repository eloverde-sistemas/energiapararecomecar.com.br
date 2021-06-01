<?
	//echo "<br>Evento: ";
	$idEvento = intval($_GET['id']);
	
	//echo "<br>Participante: ";
	$idParticipante = intval($_GET['id2']);
	
	if($idEvento>0 && $idParticipante>0){

		$campanha = executaSQL("SELECT * FROM evento WHERE id='".$idEvento."' ", true);
		if( nLinhas($campanha)>0 ){

			$campanha = objetoPHP($campanha);
						
			//PEGAS OS CUPONS DO PARTICIPANTE
			$cuponsParticipante = executaSQL("SELECT * FROM participante_cupom pc WHERE id_participante='".$idParticipante."' AND id_evento='".$idEvento."' ", true);
			if( nLinhas($cuponsParticipante)>0 ){
		
				while( $cupomParticipante = objetoPHP($cuponsParticipante) ){
					
					$idCupom = $cupomParticipante->id;
					
					//SE O CONTROLE DE VALOR DA CAMPANHA É MÚLTIPLO
					if( $campanha->id_controle_valor==2 ){
						
						//PEGA OS CUPONS MÚLTIPLOS DO PARTICIPANTE
						$cuponsMultiplos = executaSQL("SELECT * FROM cupom_multiplo pc WHERE id_part_cupom='".$idCupom."' ", true);
						if( nLinhas($cuponsMultiplos)>0 ){
					
							while( $cupomMultiplo = objetoPHP($cuponsMultiplos) ){
								
								//ZERA OS ELEMENTOS SORTEÁVEIS
								executaSQL("UPDATE elemento_sorteavel SET id_participante_cupom='0', dt_atribuicao=NULL WHERE id_evento='".$idEvento."' AND id='".$cupomMultiplo->id_elem_sorteavel."' ", true);
								
							}
						}

						executaSQL("DELETE FROM cupom_multiplo WHERE id_part_cupom='".$idCupom."' ", true);
					}else{
						//ZERA OS ELEMENTOS SORTEÁVEIS
						executaSQL("UPDATE elemento_sorteavel SET id_participante_cupom='0', dt_atribuicao=NULL WHERE id_evento='".$idEvento."' AND id_participante_cupom='".$idCupom."' ", true);
					}
					
				}
			}
			
			executaSQL("DELETE FROM participante_cupom WHERE id_evento='".$idEvento."' AND id_participante='".$idParticipante."' ", true);
		
			executaSQL("DELETE FROM evento_participante WHERE id_evento='".$idEvento."' AND id_participante='".$idParticipante."' ", true);
			
			
			
		}
		
	}
	
	header("Location: /adm/admin/eventos/participantes/".$idEvento);
	die();
?>