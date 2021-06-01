<?
	$aColumns    = array( 'p.nome', 'l.id_perfil', 'l.id');
	$sTable = " pessoa p, pessoa_perfil l";	
	
	$colunas = $_POST['columns'];
	
	$paramsExtras = array('p.id = l.id_pessoa');
	
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
			
			if($colunas[$i]['data'] == "btn_excluir"){
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "red"), "javascript:void(0)", "fa-times", $translate->translate("ttl_excluir"), $translate->translate("ttl_excluir"), "", array('onClick="excluirPermissao(\''.$aRow[id].'\')"'));

			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ substr($aColumns[$i], strpos($aColumns[$i], '.')+1, strlen($aColumns[$i]) ) ];
				if($i==1){
					
						$valor = getPerfilNome($valor);
					
				}
				$row[$colunas[$i]['data']] = converteMJSON($valor);
				
			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>