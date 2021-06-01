<?
	$id = intval($_GET['id']);
	
	if($id>0){
		$exe = executaSQL("SELECT * FROM evento_sorteio WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			$premio = objetoPHP($exe);
			
			$sorteio = executaSQL("SELECT * FROM sorteio_loteria WHERE sorteio_data = '".$premio->sorteio_data."'");
								
			if( nLinhas($sorteio)>0 ){
			
				$sorteio = arrayPHP($sorteio);

				$sorteio_regulamentos = executaSQL("SELECT * FROM sorteio_regulamento WHERE id_evento = '".$premio->id_evento."' ORDER BY hierarquia");
			
				if( nLinhas($sorteio_regulamentos)>0 ){
					$elemento = "";
			
					while( $sorteioReg = objetoPHP($sorteio_regulamentos) ){ 
						$elemento .= substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1);
					}
					
					executaSQL("UPDATE evento_sorteio SET sorteio_nr_extracao = '".$elemento."'  WHERE id= '".$id."'");
					
		            setarMensagem(array($translate->translate("numero_registrado")), "sucdess");   
					header("Location: /adm/admin/eventos/elemento-sorteavel/".$premio->id_evento);
					die();

				}else{
					setarMensagem(array($translate->translate("regulamento_nao_definido")), "danger");   
					header("Location: /adm/admin/eventos/elemento-sorteavel/".$premio->id_evento);
					die();
				}
			}else{
	            setarMensagem(array($translate->translate("sorteio_loteria_federal_nao_disponivel")), "danger");   
				header("Location: /adm/admin/eventos/elemento-sorteavel/".$premio->id_evento);
				die();
			}
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	}
?>	