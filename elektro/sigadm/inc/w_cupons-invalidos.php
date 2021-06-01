<?	

/*
	function geraElemento($campanha, $cuponsReceberElemento, $cuponsMultiplos=false){
		foreach( $cuponsReceberElemento as $cupomId ){
			
			$cupomParticipante = objetoPHP(executaSQL("SELECT * FROM participante_cupom WHERE id='".$cupomId."' "));
			
			$elSQL= "SELECT e.* FROM elemento_sorteavel e WHERE e.id_evento='".$campanha->id."' AND e.codigo='".strtoupper(str_replace('.', '', $cupomParticipante->cupom))."'";
			
			if($campanha->lote_controle==1){
				$elSQL	.= " AND EXISTS (SELECT 1 FROM lote_elemento_sorteavel l WHERE l.id_elemento_sorteavel=e.id) ";
			}
			
			//echo '<br>'.$elSQL;
			$elemento_sorteavel= executaSQL($elSQL);
			
			if(nLinhas($elemento_sorteavel)>0){
				
				$elemento_sorteavel = objetoPHP($elemento_sorteavel);
				
				if($elemento_sorteavel->id_participante_cupom>0){
					echo "<br />Já utilizado: ".$cupomParticipante->cupom;
					alterarDados("participante_cupom", array("id_situacao"=>4), "id='".$cupomParticipante->id."'");
				}else{
					//echo "<br>Válido-OK";
					
					alterarDados("elemento_sorteavel", array("id_participante_cupom"=>$cupomParticipante->id, "dt_atribuicao"=>date('YmdHis')), "id='".$elemento_sorteavel->id."'");
					
					alterarDados("participante_cupom", array("id_situacao"=>2), "id='".$cupomParticipante->id."'");
					
				}
				
			}else{
				echo "<br />Inválido: ".$cupomParticipante->cupom;
				alterarDados("participante_cupom", array("id_situacao"=>3), "id='".$cupomParticipante->id."'");
			}
		}
	}

	//SETA ELEMENTOS SORTEÁVEIS PARA AS CAMPANHAS "CADASTRE & PARTICIPE"
	$campanhas = executaSQL("SELECT * FROM evento e WHERE EXISTS(SELECT 1 FROM participante_cupom pc WHERE pc.id_evento=e.id AND pc.id_situacao='1') ");
	if( nLinhas($campanhas)>0 ){
		
		while( $campanha = objetoPHP($campanhas) ){
			
			//1-Cadastre e Participe, 2-Cadastre e Ganhe, 3-CNPJ + Cupom, 4-Código/Rasgadinha

			if($campanha->id_tipo_campanha==4){
				
				$cuponsParticipantes = executaSQL("SELECT pc.* FROM participante_cupom pc, evento e WHERE e.id='".$campanha->id."' AND pc.id_evento=e.id AND e.id_tipo_campanha='4' AND pc.id_situacao='1' ");
				if( nLinhas($cuponsParticipantes)>0 ){
					while( $cupom = objetoPHP($cuponsParticipantes) ){
						//echo '<br /><br />'.$cupom->cupom;
						geraElemento($campanha, array($cupom->id), false);
					}
				}
				
			}
			
			
		}
		
	}
*/
?>