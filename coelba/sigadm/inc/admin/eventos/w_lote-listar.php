<?
        //var_dump($_GET);
		
        $idEvento = intval($_GET['id']);

		$lote = intval($_GET['id2']);

		$elems = executaSQL("SELECT les.id, e.codigo FROM elemento_sorteavel e, lote_elemento_sorteavel les WHERE les.id_evento='".$idEvento."' AND les.id_lote='".$lote."' AND les.id_elemento_sorteavel=e.id ORDER BY les.id ", true);
?>
		<table border="1">
<?
		$x=0;
		while( $elem = objetoPHP($elems) ){
			$x++;
?>
			<tr>
				<td><?=$elem->id?></td>
				<td><?=$elem->codigo?></td>
			</tr>
<?		} ?>
		</table>