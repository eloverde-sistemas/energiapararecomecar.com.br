<?
	$aColumns    = array( 'id_evento', 'dt_hr', 'id_tipo', 'id');
	$sTable = "atualizacao";	
	
	$colunas = $_POST['columns'];
	
	$sQuery = "SELECT ".str_replace(" , ", " ", implode(", ", array_unique($aColumns)))." FROM $sTable ";
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
			
			if($colunas[$i]['data'] == "btn_visualizar"){
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn btn-sm ", "blue"), "/baixarDocumento.php?tipo=3&id=".$aRow[$aColumns[3]], "fa-download", $translate->translate("download"), $translate->translate("ttl_editar"));
			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ $aColumns[$i] ];
				if($i==0){
					$valor = getEventoById($valor)->titulo;
				}elseif($i==1){
					$valor = converteDataHora($valor);
				}elseif($i==2){
					switch ($valor) {
						case 1:
							$valor = $translate->translate("insercao");
							break;
						case 2:
							$valor = $translate->translate("atualizacao");
							break;
						case 3:
							$valor = $translate->translate("exclusao");
							break;
					}
				}
				$row[$colunas[$i]['data']] = converteMJSON($valor);
				
			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>