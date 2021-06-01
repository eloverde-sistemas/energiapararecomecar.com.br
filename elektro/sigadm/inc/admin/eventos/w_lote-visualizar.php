<?
        //var_dump($_GET);
		
        $id = intval($_GET['id']);

        $id2 = intval($_GET['id2']);

		$elems = executaSQL("SELECT e.codigo, e.id_evento FROM elemento_sorteavel e, lote_elemento_sorteavel l WHERE l.id_elemento_sorteavel=e.id AND l.id_evento='".$id."' AND l.id_lote='".$id2."' ORDER BY RAND()");
								
		while( $elem = objetoPHP($elems) ){
		
			echo "<br>".$elem->codigo;
		}
?>