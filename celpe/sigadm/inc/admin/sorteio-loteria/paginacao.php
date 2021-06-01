<?
	$aColumns    = array( 'sorteio_data', 'sorteio_1', 'sorteio_2', 'sorteio_3', 'sorteio_4', 'sorteio_5', 'id', 'id');
	$sTable = " sorteio_loteria";	
	
	$colunas = $_POST['columns'];
	
	$sQuery = "SELECT ".str_replace(" , ", " ", implode(", ", array_unique($aColumns)))." 
				FROM $sTable ";
	
 	$sQuery .= paginacaoAjaxQuery($_POST, $aColumns, $paramsExtras);
	
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
				
			$temSorteio = verificaEventoSorteio($aRow[$aColumns[0]]);

			if ( $colunas[$i]['data'] == "btn_excluir" ){
				
				if(!$temSorteio)
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(2, array("btn", "btn-sm", "red"), "/adm/admin/sorteio-loteria/excluir/".$aRow[$aColumns[$i]], "fa-times", $translate->translate("ttl_excluir"), $translate->translate("ttl_excluir"), "", array(), $translate->translate("msg_excluir"));
				
			}elseif($colunas[$i]['data'] == "btn_alterar"){
				
				if(!$temSorteio)	
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "green"), "/adm/admin/sorteio-loteria/editar/".$aRow[$aColumns[$i]], "fa-edit", $translate->translate("ttl_editar"), $translate->translate("ttl_editar"));
			
			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[$aColumns[$i]];
					
				if($i==0){
					$valor = converte_data($valor);	
				}
				
				$row[$colunas[$i]['data']] = converteMJSON($valor);		
			}
			
				
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>