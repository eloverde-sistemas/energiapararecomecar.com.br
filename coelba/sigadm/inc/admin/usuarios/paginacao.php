<?
	$aColumns    = array( 'p.nome', 'p.id_situacao', 'p.id', 'p.id', 'p.id');
	$sTable = " pessoa p";	
	
	$colunas = $_POST['columns'];
	
	$exists = "";
	if( $_POST["filterValue"][3] >0 ){
		$exists = " AND EXISTS (SELECT 1 FROM pessoa_perfil pp WHERE p.id=pp.id_pessoa AND pp.id_perfil='".$_POST["filterValue"][3]."') ";
	}
	unset($_POST["filterValue"][3]);
	
	$paramsExtras = array('p.id_situacao>0'.$exists);
	
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
		$sit	= $aRow[ substr($aColumns[2], strpos($aColumns[2], '.')+1, strlen($aColumns[2]) ) ];
		
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				
			if ( $colunas[$i]['data'] == "btn_excluir" ){				
				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(2, array("btn", "btn-sm", "red"), "/adm/admin/usuarios/excluir/".$aRow[id], "fa-times", $translate->translate("inativar"), $translate->translate("inativar"), "", array(), converteMJSON($translate->translate("msg_inativar") ));
				
				
			}elseif($colunas[$i]['data'] == "btn_alterar"){
					$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "green"), "/adm/admin/usuarios/editar/".$aRow[id], "fa-edit", $translate->translate("ttl_editar"), $translate->translate("ttl_editar"));

			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ substr($aColumns[$i], strpos($aColumns[$i], '.')+1, strlen($aColumns[$i]) ) ];
				
				if($i==1){
					$valor = getSituacaoById($valor);
				}
				
				if($i==2){
				
					$valor = implode(', ', consultaPerfisNomeByIdPessoa($valor));
					
				}
				
				$row[$colunas[$i]['data']] = converteMJSON($valor);					
			}
			
				
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>