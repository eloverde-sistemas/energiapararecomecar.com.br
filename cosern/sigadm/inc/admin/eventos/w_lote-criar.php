<?
        //var_dump($_GET);
		
        $id = intval($_GET['id']);

		$qtdeCodigos = intval($_GET['id2']);

		$idLote = proximoId('lote');

		$insLote = inserirDados("lote", array('id'=>$idLote, 'id_evento'=>$id, 'id_situacao'=>1));
		
		if($insLote){
			echo "gerando...";
			$elems = executaSQL("SELECT e.id FROM elemento_sorteavel e 
									WHERE e.id_evento='".$id."'
									AND NOT EXISTS (
													SELECT 1 FROM lote_elemento_sorteavel l 
													WHERE e.id=l.id_elemento_sorteavel
													AND l.id_evento='".$id."'
												) 
									LIMIT ".$qtdeCodigos."", true);
									
			while( $elem = objetoPHP($elems) ){
			
				$dados = array();
				$dados['id_evento']				= $id;
				$dados['id_lote']				= $idLote;
				$dados['id_elemento_sorteavel']	= $elem->id;

				inserirDados("lote_elemento_sorteavel", $dados);
			
			}
		}

		echo "finalizou";
?>