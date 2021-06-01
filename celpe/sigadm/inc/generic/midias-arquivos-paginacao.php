<?
	$aColumns    = array( 'id', 'titulo', 'caminho', 'formato', 'caminho');
	$sTable = "midia";	
	
	$colunas = $_POST['columns'];
	
	$paramsExtras = array("formato NOT IN ('jpg','jpeg','gif','png')");
	
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
			
			if( $aColumns[$i] != ''){
				
				$valor = $aRow[ $aColumns[$i] ];
				if($i==2){
					$valor = '<a class="btn btn-sm blue" href="/baixarDocumento.php?tipo=22&id='.$aRow[$aColumns[0]].'" target="_blank">
										<i class="fa fa-download"></i> '.converteMJSON($translate->translate("download_arquivo")).'
									</a>';
				
				}elseif($i==4){
                	$valor = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "blue"), "javascript:void(0)", "fa-copy", $translate->translate("inserir"), $translate->translate("inserir"), "", array("data-caminho='".$_SESSION['http_s'].$_SERVER['HTTP_HOST'].'/'.$valor."' onclick=\"insertArquivo('".$aRow[$aColumns[0]]."', '".$aRow[$aColumns[1]]."')\""));
					
				}
				$row[$colunas[$i]['data']] = converteMJSON($valor);
				
			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>