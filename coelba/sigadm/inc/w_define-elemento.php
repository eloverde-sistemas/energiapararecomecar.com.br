<?	
	$idSorteio = 2;
	
	$eventoSorteio = objetoPHP(executaSQL("SELECT es.id as idSorteio, e.id as idEvento, e.titulo, es.sorteio_data FROM evento e, evento_sorteio es WHERE es.id= '".$idSorteio."' AND es.id_evento=e.id"));
	
	echo "<br /><br /><strong>Campanha</strong>: ".$eventoSorteio->titulo;
	
	echo "<br /><br /><strong>Data Sorteio da Loteria</strong>: ".converte_data($eventoSorteio->sorteio_data);

	if( $eventoSorteio->sorteio_data!='' ){
	
		$sorteio = executaSQL("SELECT * FROM sorteio_loteria WHERE sorteio_data = '".$eventoSorteio->sorteio_data."'");
		
		if( nLinhas($sorteio)>0 ){
		
			$sorteio = arrayPHP($sorteio);
			
			echo "<br><br>Sorteio 1: ".$sorteio1 = $sorteio["sorteio_1"];
			echo "<br>Sorteio 2: ".$sorteio2 = $sorteio["sorteio_2"];
			echo "<br>Sorteio 3: ".$sorteio3 = $sorteio["sorteio_3"];
			echo "<br>Sorteio 4: ".$sorteio4 = $sorteio["sorteio_4"];
			echo "<br>Sorteio 5: ".$sorteio5 = $sorteio["sorteio_5"];
			
			
			$sorteio_regulamentos = executaSQL("SELECT * FROM sorteio_regulamento WHERE id_evento = '".$eventoSorteio->idEvento."' ORDER BY hierarquia");
		
			if( nLinhas($sorteio_regulamentos)>0 ){
				$elemento = "";
				
				echo "<br><br>COMPOSIÇÃO: ";
				while( $sorteioReg = objetoPHP($sorteio_regulamentos) ){
					//echo "<br>".$sorteioReg->sorteio;
					
					echo "<br>".substr($sorteio["sorteio_".$sorteioReg->sorteio], 0, ($sorteioReg->posicao) )."<strong>".substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1)."</strong>".substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao) );
					
					//echo "<br> Sorteio ".$sorteioReg->sorteio.": ".$sorteio["sorteio_".$sorteioReg->sorteio]." - ".$sorteioReg->posicao;
					
					$elemento .= substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1);
				}
				
				echo "<br><br><strong>Elemento Sorteável</strong>: ".$elemento;
				
				//ATUALIZA 
				executaSQL("UPDATE evento_sorteio SET sorteio_nr_extracao = '".$elemento."'  WHERE id= '".$idSorteio."'");

				
			}else{
				echo "<br><br>Regulamento do Sorteio não disponível.";
			}
			
		}else{
			echo "<br><br>Sorteio da Loteria Federal ainda não disponível.";
		}
		
	}else{
		echo "<br><br>Não foi definida a Data do Sorteio da Loteria Federal.";	
	}
?>