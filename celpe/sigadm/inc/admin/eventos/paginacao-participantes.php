<?
	$aColumns   = array('e.data_participacao', 'p.nome', 'p.cpf', 'p.dt_nascimento', 'p.email', 'p.celular', 'e.matricula', 'p.id');
	$sTable 	= "participante p, evento_participante e";	
	
	$colunas = $_POST['columns'];
	
	$idCampanha = $_POST['paramsExtra'][0];
	
	$campanha = executaSQL("SELECT * FROM evento WHERE id='".$idCampanha."' ");
	if( nLinhas($campanha)>0 ){
		$campanha = objetoPHP($campanha);
	}
	
	$paramsExtras = array("p.id=e.id_participante", "e.id_evento='".$idCampanha."'");	
	
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
	
	$aColumns[0] = 'data_participacao';
	$aColumns[1] = 'nome';
	$aColumns[2] = 'cpf';
	$aColumns[3] = 'dt_nascimento';
	$aColumns[4] = 'email';
	$aColumns[5] = 'celular';
	$aColumns[6] = 'matricula';
	$aColumns[7] = 'id';

	
	while ( $aRow = mysql_fetch_array( $rResult ) ){
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ){
			
			$row[$colunas[$i]['data']] ="";

			if ( $colunas[$i]['data'] == "btn_acoes" ){
				
				$links=array();

				$links[] = '<a href="/adm/admin/eventos/cupons-participante/'.$idCampanha.'/'.$aRow[7].'" target="_blank">
								<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("cupons_por_participante")).'
							</a>';
				if( ehMason() ){
					$links[] = '<a href="javascript:void(0);" onclick="bootbox.confirm(\''.converteMJSON($translate->translate("msg_excluir_participante_campanha")).'\', function(result){ if(result){ window.location.href=\'/adm/admin/eventos/elimina-participante-campanha/'.$idCampanha."/".$aRow[5].'\';} })">
									<i class="fa fa-times"></i> '.converteMJSON($translate->translate("excluir_da_campanha")).'
								</a>';
				}

				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(3, array(), "", "fa-cog", converteMJSON($translate->translate("acoes")), "", "", array(), "", $links);

				
			}elseif( $aColumns[$i] != ''){

					$valor = $aRow[ $aColumns[$i] ];

					if($i==0){
						$valor = converteDataHora($valor);
					}elseif($i==1){
						$valor = converteMJSON($valor);
					}elseif($i==3){
						$valor = converteDataHora($valor);
					}

					if($i<7)
						$row[$colunas[$i]['data']] = $valor;
			}

			/*
			if ( $colunas[$i]['data'] == "btn_acoes" ){
				$row[$colunas[$i]['data']] ="";
				
				$links=array();

				$links[] = '<a href="/adm/admin/eventos/cupons-participante/'.$idCampanha.'/'.$aRow[5].'" target="_blank">
								<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("cupons_por_participante")).'
							</a>';
				if( ehMason() ){
					$links[] = '<a href="javascript:void(0);" onclick="bootbox.confirm(\''.converteMJSON($translate->translate("msg_excluir_participante_campanha")).'\', function(result){ if(result){ window.location.href=\'/adm/admin/eventos/elimina-participante-campanha/'.$idCampanha."/".$aRow[5].'\';} })">
									<i class="fa fa-times"></i> '.converteMJSON($translate->translate("excluir_da_campanha")).'
								</a>';
				}
				
				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(3, array(), "", "fa-cog", converteMJSON($translate->translate("acoes")), "", "", array(), "", $links);

				
			}elseif( $aColumns[$i] != ''){
				
				//$valor = $aRow[ substr($aColumns[$i], strpos($aColumns[$i], '.')+1, strlen($aColumns[$i]) ) ];
				
				$valor = $aRow[ $aColumns[$i] ];
				
				if($i==0){
					$valor = converteDataHora($valor);
				}elseif($i==1){
					$valor = $valor."<br>(".$aRow[ $aColumns[7] ].")";
				}elseif($i==2){
					$valor = ($aRow[ $aColumns[2] ]==1)?'Masculino' :'Feminino';
				}elseif($i==3){
					$valor = getMuniciopioById($valor);
				}elseif($i==5){
					$elementosMostrar = array();
					if($campanha->id_tipo_campanha==3){
						
						$elementos  = executaSQL("SELECT DISTINCT(el.elemento) FROM elemento_sorteavel el, cupom_multiplo cm, participante_cupom pc, participante p 
													WHERE el.id_evento='".$campanha->id."' 
													AND el.id_participante_cupom=cm.id_part_cupom 
													AND cm.id_part_cupom=pc.id 
													AND pc.id_participante=p.id 
													AND p.id='".$valor."' ");
						if( nLinhas($elementos)>0){
							while( $elemento = objetoPHP($elementos) ){
								$elementosMostrar[] = $elemento->elemento;
							}
						
							$valor = implode($elementosMostrar, ", ");
						}else{
							$valor = '';
						}
						
					}else{
						$elementos  = executaSQL("SELECT DISTINCT(el.elemento) FROM elemento_sorteavel el, participante_cupom pc 
													WHERE el.id_evento='".$campanha->id."' 
													AND el.id_participante_cupom = pc.id 
													AND pc.id_participante='".$valor."' ");
						if( nLinhas($elementos)>0){
							while( $elemento = objetoPHP($elementos) ){
								$elementosMostrar[] = $elemento->elemento;
							}
						
							$valor = implode($elementosMostrar, ", ");
						}else{
							$valor = '';
						}
					}
				}
				
				if($i<8)
					$row[$colunas[$i]['data']] = $valor;

			}*/
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>