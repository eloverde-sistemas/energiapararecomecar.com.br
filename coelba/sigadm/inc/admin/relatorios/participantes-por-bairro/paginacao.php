<?
	$aColumns = array('id_cidade', 'bairro', 'id', 'id', 'id', 'id');
	$sTable   = "participante";
	
	$colunas = $_POST['columns'];
	
	$sQuery = "SELECT ".str_replace(" , ", " ", implode(", ", array_unique($aColumns)))." 
				FROM $sTable ";

	
	$paramsExtras = array();

	//SE FILTROU PELA CIDADE
	if($_POST['filterExtra'][0]>0){
		$paramsExtras[] = " EXISTS (SELECT 1 FROM participante_evento WHERE id_evento='".$_POST['filterExtra'][0]."' AND participante_evento.id_participante=participante.id ";
	}
	
	//SE FILTROU PELO ESTADO
	if($_POST['filterExtra'][1]>0){
		$paramsExtras[] = " EXISTS (SELECT 1 FROM municipio WHERE municipio.id=participante.id_cidade AND municipio.id_estado='".$_POST['filterExtra'][1]."') ";
	}
	
	//$paramsExtras = implode(" AND ", $paramsExtras);	
	
	$sQuery .= paginacaoAjaxQuery($_POST, $aColumns, $paramsExtras);
	
	//echo $sQuery;
	
	$rResult = mysql_query( $sQuery ) or die(mysql_error());
	
	$paramsQuery = paginacaoAjaxParams($_POST, $aColumns, $paramsExtras);
	$totalResults = paginacaoAjaxTotalResults($paramsQuery, $sTable);
	
	$output = array(
		"draw" => intval($_POST['sEcho']),
		"recordsTotal" => $totalResults,
		"recordsFiltered" => $totalResults,
		"data" => array()
	);
	
	//print_r($colunas);
	while ( $aRow = mysql_fetch_array( $rResult ) ){
		$row = array();
		
		for ( $i=0 ; $i<count($aColumns) ; $i++ ){
			
				$valor = $aRow[ $aColumns[$i] ];				
				
				$row[$colunas[$i]['data']] = converteMJSON($valor);
			
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
?>