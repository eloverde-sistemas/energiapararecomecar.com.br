<?

	$sorteioData = executaSQL("SELECT es.*, e.id as idEvento, e.id_numeros_sorteaveis FROM evento e, evento_sorteio es 
									WHERE e.id= '".$_SESSION['campanha']->id."' 
									AND es.id_evento=e.id 
									AND es.sorteio_data <= '".date('Y-m-d')."'");
	
	if( nLinhas($sorteioData)>0 ){ ?>

		<br /><br />
		<h2>Sorteio(s)</h2>

<?
		$eventoSorteios = executaSQL("SELECT es.*, e.id as idEvento, e.id_numeros_sorteaveis FROM evento e, evento_sorteio es 
										WHERE e.id= '".$_SESSION['campanha']->id."' 
										AND es.id_evento=e.id 
										AND es.sorteio_nr_extracao>0 
										AND EXISTS (SELECT 1 FROM evento_ganhador eg WHERE eg.id_sorteio=es.id AND eg.id_situacao='2')
										ORDER BY sorteio_data");
		
		if( nLinhas($eventoSorteios)>0 ){
			while( $eventoSorteio = objetoPHP($eventoSorteios) ){
				
				echo "<hr>";
	
				echo "<h3>".$eventoSorteio->qtde_premio." ".$eventoSorteio->titulo."</h3>";
	
				echo "<h4>Data Sorteio da Loteria: ".converte_data($eventoSorteio->sorteio_data)."</h4>";
				echo "<strong>Números Sorteados da Loteria</strong>: ";
				
				$sorteio = executaSQL("SELECT * FROM sorteio_loteria WHERE sorteio_data = '".$eventoSorteio->sorteio_data."'");
				
				if( nLinhas($sorteio)>0 ){
				
					$sorteio = arrayPHP($sorteio);
					
					echo "<br><strong>Prêmio 1</strong>: ".$sorteio["sorteio_1"];
					echo " | <strong>Prêmio 2</strong>: ".$sorteio["sorteio_2"];
					echo " | <strong>Prêmio 3</strong>: ".$sorteio["sorteio_3"];
					echo " | <strong>Prêmio 4</strong>: ".$sorteio["sorteio_4"];
					echo " | <strong>Prêmio 5</strong>: ".$sorteio["sorteio_5"];
					
					
					$sorteio_regulamentos = executaSQL("SELECT * FROM sorteio_regulamento WHERE id_evento = '".$eventoSorteio->idEvento."' ORDER BY hierarquia");
				
					if( nLinhas($sorteio_regulamentos)>0 ){
						$elemento = "";
						
						echo "<br><br><strong>Composição do Número Sorteável conforme Regulamento</strong>: ";
						
						$qtdeEleSorteaveis = objetoPHP(executaSQL("SELECT * FROM evento_numeros_sorteaveis WHERE id = '".$eventoSorteio->id_numeros_sorteaveis."'"));
						
						$series = $qtdeEleSorteaveis->qtde_serie;
						
						while( $sorteioReg = objetoPHP($sorteio_regulamentos) ){
							
							echo "<br />".$sorteioReg->posicao."ª Posição do Prêmio ".$sorteioReg->sorteio.": ";
							
							echo substr($sorteio["sorteio_".$sorteioReg->sorteio], 0, ($sorteioReg->posicao-1) )."<strong>".substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1)."</strong>".substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao) );
							
							//echo "<br> Sorteio ".$sorteioReg->sorteio.": ".$sorteio["sorteio_".$sorteioReg->sorteio]." - ".$sorteioReg->posicao;
							
							$elemento .= substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1);
						}
						
						echo "<br><br><strong>Elemento Sorteável</strong>: ".$elemento;
						
						$ganhador = executaSQL("SELECT p.* FROM evento_ganhador eg, participante_cupom pc, elemento_sorteavel es, participante p 
												WHERE eg.id_elemento=es.id 
													AND es.id_evento='".$_SESSION['campanha']->id."' 
													AND eg.id_sorteio='".$eventoSorteio->id."'
													AND eg.id_situacao='2'
													AND eg.id_participante_cupom=pc.id  
													AND pc.id_participante=p.id");
						if( nLinhas($ganhador)>0 ){
							echo "<br><br>";
							echo "<strong>Ganhador(a)</strong>: ".objetoPHP($ganhador)->nome;
						}

						
					}else{ ?>
						<div class="alert alert-warning spacer-20">Regulamento do Sorteio não disponível</div>
	<?				}
					
				}else{ ?>
					<div class="alert alert-warning spacer-20">Sorteio da Loteria Federal ainda não disponível.</div>
	<?			}
				
			}
		}else{ ?>
			<div class="alert alert-warning spacer-20">Nenhum sorteio disponível!</div>
	<?	}
	
	}else{ ?>
		<div class="alert alert-warning spacer-20">Nenhum sorteio realizado até esta data!</div>
	<?	} ?>
<br /><br /><br /><br />