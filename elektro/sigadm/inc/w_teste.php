<?
	$idCampanha = 1;

	$participantes = executaSQL("SELECT * FROM participante p 
								WHERE EXISTS (
										SELECT 1 FROM participante_unidade pu 
										WHERE pu.id_evento='".$idCampanha."'
										AND pu.id_participante=p.id 
										AND NOT EXISTS (
														SELECT 1 FROM participante_cupom pc
														WHERE pc.id_evento='".$idCampanha."'
														AND pc.id_participante=p.id
														AND pc.id_unidade=pu.id
														AND ano_mes LIKE '".date("Y_m")."'
														)
										)
								ORDER BY p.id 
								LIMIT 5");
	echo "<br>TOTAL: ".nLinhas($participantes);

	if(nLinhas($participantes)){

		while($participante = objetoPHP($participantes) ){

			echo "<br><br>".$participante->id." - ".$participante->nome;

			$unidades = executaSQL("SELECT * FROM participante_unidade pu 
										WHERE pu.id_evento='".$idCampanha."'
										AND pu.id_participante='".$participante->id."'
										AND NOT EXISTS (
														SELECT 1 FROM participante_cupom pc
														WHERE pc.id_evento='".$idCampanha."'
														AND pc.id_participante='".$participante->id."'
														AND pc.id_unidade=pu.id
														AND ano_mes LIKE '".date("Y_m")."'
														)
										");
			while($unidade = objetoPHP($unidades) ){
				echo "<br>".$unidade->unidade;


			}
		}

	}
?>