<?
	$aColumns    = array( 'id_evento', 'pergunta', 'resposta', 'id', 'id', 'id', 'ordem', 'ativo');
	$sTable = " perguntas_respostas";	
	
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
				
			if ( $colunas[$i]['data'] == "btn_excluir" ){
				
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(2, array("btn", "btn-sm", "red"), "/adm/admin/faq/excluir/".$aRow[$aColumns[$i]], "fa-times", $translate->translate("ttl_excluir"), $translate->translate("ttl_excluir"), "", array(), $translate->translate("msg_excluir"));
				
			}elseif($colunas[$i]['data'] == "btn_alterar"){
					
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "green"), "/adm/admin/faq/editar/".$aRow[$aColumns[$i]], "fa-edit", $translate->translate("ttl_editar"), $translate->translate("ttl_editar"));

			}elseif($colunas[$i]['data'] == "btn_inativar"){
				
				if($aRow[$aColumns[7]] ==1 ){
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(2, array("btn", "btn-sm", "yellow"), "/adm/admin/faq/inativar/".$aRow[$aColumns[$i]], "fa-ban", $translate->translate("inativar"), $translate->translate("inativar"), "", array(), $translate->translate("msg_inativar"));
				}else{
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "green"), "/adm/admin/faq/inativar/".$aRow[$aColumns[$i]], "fa-check", $translate->translate("ativar"), $translate->translate("ativar"));
				}
					
			
			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ $aColumns[$i] ];
					
				if($i==0){
					$valor = getEventoById($valor)->titulo;	
				}
				
				if($i<7)
					$row[$colunas[$i]['data']] = converteMJSON($valor);		
			}
			
				
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>