<?
	$aColumns    = array( 'e.id_evento', 'e.data_participacao', 'p.nome', 'p.cpf', 'e.id_cupom_retirada', 'e.id_cupom_situacao', 'e.id_participante');
	$sTable = "evento_participante e, participante p";	
	
	$colunas = $_POST['columns'];
	
	$paramsExtras = array("p.id=e.id_participante");	
	
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

				$links[] = '<a href="javascript:void(0);" onclick="bootbox.confirm(\''.converteMJSON($translate->translate("msg_premio_retirado")).'\', function(result){ if(result){ window.location.href=\'/adm/admin/eventos/ganhadores-retirado/'.$aRow['id_evento']."/".$aRow['id_participante'].'\';} })">
								<i class="fa fa-check"></i> '.converteMJSON($translate->translate("retirado")).'
							</a>';

				$links[] = '<a href="javascript:void(0);" onclick="bootbox.confirm(\''.converteMJSON($translate->translate("msg_premio_nao_retirado")).'\', function(result){ if(result){ window.location.href=\'/adm/admin/eventos/ganhadores-nao-retirado/'.$aRow['id_evento']."/".$aRow['id_participante'].'\';} })">
								<i class="fa fa-times"></i> '.converteMJSON($translate->translate("nao-retirado")).'
							</a>';

				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(3, array(), "", "fa-cog", converteMJSON($translate->translate("acoes")), "", "", array(), "", $links);
				
			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ substr($aColumns[$i], strpos($aColumns[$i], '.')+1, strlen($aColumns[$i]) ) ];
				
				//$valor = $aRow[ $aColumns[$i] ];
				
				if($i==1){
					$valor = converteDataHora($valor);
				}elseif($i==4){
					$valor = base64_encode($aRow['id_evento'].formataNumeroComZeros($valor,8));
				}elseif($i==5){
					$valor = ($valor==2)?'Retirada' :'Aguardando Retirada';
				}
				
				$row[$colunas[$i]['data']] = converteMJSON($valor);

			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>