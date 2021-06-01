<?php

	$idUltimoElemento = objetoPHP(executaSQL("SELECT * FROM elemento_sorteavel e WHERE e.id_evento='5' AND e.codigo<>'' ORDER BY id DESC LIMIT 1 "))->id;
	
	//SETA ELEMENTOS SORTEÁVEIS PARA AS CAMPANHAS "CADASTRE & PARTICIPE"
	$elementos = executaSQL("SELECT * FROM elemento_sorteavel e WHERE e.id_evento='5' AND id>'".$idUltimoElemento."' ORDER BY id LIMIT 100000 ", true);
	if( nLinhas($elementos)>0 ){
		$x = 0;
		while( $elemento = objetoPHP($elementos) ){
			$x++;
			$cupomConverter = $elemento->id_evento.substr($elemento->elemento, 1);
			
			//echo "<br>Elemento: ".$elemento->elemento." - Código: ".$elemento->codigo." - Cupom: ".$cupomConverter." - Hexa: ".strtoupper(dechex($cupomConverter));
			
			$atualiza = executaSQL("UPDATE elemento_sorteavel SET codigo='".strtoupper(dechex($cupomConverter))."' WHERE id='".$elemento->id."' ");
				
			
			//if($x%50==0){ break; }
		}
		
	}	

/*
	echo strtoupper(dechex(50000000));
	echo "<br /><br />";
	echo strtoupper(dechex(50999999));
*/

?>