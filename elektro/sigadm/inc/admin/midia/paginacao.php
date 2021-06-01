<?
	$aColumns    = array( 'id', 'titulo', 'caminho', 'formato', 'caminho', 'id', 'id');
	$sTable = "midia";	
	
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
				
				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(2, array("btn", "btn-sm", "red"), "/adm/admin/midia/excluir/".$aRow[$aColumns[$i]], "fa-times", $translate->translate("ttl_excluir"), $translate->translate("ttl_excluir"), "", array(), $translate->translate("msg_excluir"));
				
			}elseif($colunas[$i]['data'] == "btn_alterar"){
								
				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(1, array("btn", "btn-sm", "green"), "/adm/admin/midia/editar/".$aRow[$aColumns[$i]], "fa-edit", $translate->translate("ttl_editar"), $translate->translate("ttl_editar"));
				
			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ $aColumns[$i] ];
				if($i==2){
				
					$imgTypes = array('jpg','jpeg','gif','png');
					
					if( in_array($aRow[$aColumns[3]], $imgTypes) ){//IMAGEM
						$valor = '<a href="/'.$valor.'" class="foto">
										<img src="/'.$valor.'" height="100" />
								  </a>';
					}else{
						$valor = '<a class="btn btn-sm blue" href="/baixarDocumento.php?tipo=22&id='.$aRow[$aColumns[6]].'" target="_blank">
										<i class="fa fa-download"></i> '.converteMJSON($translate->translate("download_arquivo")).'
									</a>';
					}
					
					
				}elseif($i==4){
					$valor = '<object width="50s" height="30"> <param name="movie" value="/js/lmcbutton.swf"> <param name="FlashVars" value="txt='.$_SESSION['http_s'].$_SERVER['HTTP_HOST'].'/'.$valor.'&amp;capt='.$translate->translate("copiar").'"> <embed src="/js/lmcbutton.swf" flashvars="txt=teste&amp;capt=teste" width="40" height="20"></object>';
				}
				$row[$colunas[$i]['data']] = converteMJSON($valor);
				
			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>