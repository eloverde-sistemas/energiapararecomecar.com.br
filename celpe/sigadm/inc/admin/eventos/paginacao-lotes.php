<?
	$aColumns   = array('id', 'dt_hr', 'id_situacao', 'id');
	$sTable 	= "lote";	
	
	$colunas = $_POST['columns'];
	
	$idCampanha = $_POST['paramsExtra'][0];
	
	$paramsExtras = array("id_evento='".$idCampanha."'");	
	
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
			
			if ( $colunas[$i]['data'] == "btn_acoes" ){
				$row[$colunas[$i]['data']] ="";
				
				$links=array();

				$links[] = '<a href="/adm/admin/eventos/lote-visualizar/'.$idCampanha.'/'.$aRow[0].'" target="_blank">
								<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("lote-visualizar")).'
							</a>';
				
				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(3, array(), "", "fa-cog", converteMJSON($translate->translate("acoes")), "", "", array(), "", $links);

				
			}elseif( $aColumns[$i] != ''){
				
				//$valor = $aRow[ substr($aColumns[$i], strpos($aColumns[$i], '.')+1, strlen($aColumns[$i]) ) ];
				
				$valor = $aRow[ $aColumns[$i] ];
				
				if($i==1){
					$valor = converteDataHora($valor);
				}elseif($i==2){
					$valor = ($aRow[ $aColumns[2] ]==1)?'Criado' :'Impresso';
				}
				
				$row[$colunas[$i]['data']] = converteMJSON($valor);

			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>