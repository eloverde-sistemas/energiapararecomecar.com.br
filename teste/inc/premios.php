<?

	if($_SESSION['campanha']->id_numeros_sorteaveis>0){
		$eventoSorteios = executaSQL("SELECT es.*, e.id as idEvento, e.id_numeros_sorteaveis FROM evento e, evento_sorteio es WHERE e.id= '".$_SESSION['campanha']->id."' AND es.id_evento=e.id ORDER BY sorteio_data");
		
		if( nLinhas($eventoSorteios)>0 ){
			while( $eventoSorteio = objetoPHP($eventoSorteios) ){
				
				echo "<hr>";
	
				echo "<h3>Data Sorteio da Loteria: ".converte_data($eventoSorteio->sorteio_data)."</h3>";
				
				echo "<h4>".$eventoSorteio->qtde_premio." ".$eventoSorteio->titulo."</h4>";
				echo "<strong>Descrição: "."</strong>";
	
				echo "<p align='justify'>".$eventoSorteio->descricao."</p>";
				
				
			}
		}else{ ?>
			<div class="alert alert-warning spacer-20">Nenhum sorteio disponível!</div>
	<?	} 
	
	} ?>