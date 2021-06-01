<?
	$idEvento = intval($_GET['id']);
	$idParticipante = intval($_GET['id2']);
	
	if($idEvento>0 && $idParticipante>0){
		
		executaSQL("UPDATE evento_participante SET id_cupom_situacao='1' WHERE id_evento='".$idEvento."' AND id_participante='".$idParticipante."' ");
		
	}
	
	header("Location: /adm/admin/eventos/ganhadores-cadastro/".$idEvento);
	die();
?>