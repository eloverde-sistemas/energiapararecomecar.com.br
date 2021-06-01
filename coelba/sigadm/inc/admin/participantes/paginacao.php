<?
	$aColumns = array('nome', 'cpf', 'email', 'celular', 'id');
	$sTable   = "participante";
	
	$colunas = $_POST['columns'];
	
	$sQuery = "SELECT ".str_replace(" , ", " ", implode(", ", array_unique($aColumns)))." 
				FROM $sTable ";

	
	$paramsExtras = array();
	
	//SE FILTROU PELO ESTADO
	if($_POST['filterExtra'][0]>0){
		$paramsExtras[] = " EXISTS (SELECT 1 FROM municipio WHERE municipio.id=participante.id_cidade AND municipio.id_estado='".$_POST['filterExtra'][0]."') ";
	}
	
	//SE FILTROU PELA CIDADE
	if($_POST['filterExtra'][1]>0){
		$paramsExtras[] = " id_cidade ='".$_POST['filterExtra'][1]."' ";
	}

	//SE FILTROU PELO BAIRRO
	if($_POST['filterExtra'][2]!=''){
		$paramsExtras[] = " bairro LIKE '".$_POST['filterExtra'][2]."' ";
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
			
			if ( $colunas[$i]['data'] == "btn_alterar" ){
			
				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "green"), "/adm/admin/participantes/editar/".$aRow[$aColumns[$i]], "fa-edit", $translate->translate("ttl_editar"), $translate->translate("ttl_editar"));
			
			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ $aColumns[$i] ];				
				
				$row[$colunas[$i]['data']] = converteMJSON($valor);

			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
?>