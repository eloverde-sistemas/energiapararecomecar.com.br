<?
	$id = intval($_GET['id']);
	
	if($id>0){
		$exe = executaSQL("SELECT * FROM evento WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			$reg=objetoPHP($exe);
			
			$elementos = executaSQL("SELECT * FROM elemento_sorteavel WHERE id_evento = '".$id."' AND id_participante_cupom>0 ORDER BY elemento*1");
			
			while( $elemento = objetoPHP($elementos) ){ 
				
				echo "<br>Elemento ".$elemento->elemento;
				
				if( $elemento->id_participante_cupom>0 ){
					$participante = objetoPHP(executaSQL("SELECT p.nome, p.cpf, pc.dt_cadastro FROM participante_cupom pc, participante p
													WHERE pc.id='".$elemento->id_participante_cupom."' 
													AND pc.id_participante=p.id "));

					echo "	|	Participante: ".iconv('utf-8', 'iso-8859-1', $participante->nome)."-".$participante->cpf."	|	Cupom Registrado em: ".$participante->dt_cadastro;	
				}
			}
			
			
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	}
?>