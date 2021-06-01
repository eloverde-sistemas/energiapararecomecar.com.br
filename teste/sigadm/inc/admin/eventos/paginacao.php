<?
	$aColumns    = array( 'titulo', 'dt_inicio', 'dt_termino', 'id', 'id_situacao', 'id_situacao', 'hr_inicio', 'hr_termino', 'id_numeros_sorteaveis', 'url_padrao', 'id_tipo_sorteio');
	$sTable = "evento";	
	
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
			
			if ( $colunas[$i]['data'] == "btn_acoes" ){
				$row[$colunas[$i]['data']] ="";
				
				$links=array();

				//SE ESTÁ ATIVA OU EM TESTE
				if( in_array($aRow[$aColumns[4]], array(1,99)) ){

					//SE LOTERIA FEDERAL
					if( $aRow[$aColumns[10]]==1 ){

						if( temPermissao( array('ADMIN_EVENTOS_BRINDE') ) || temPermissao( array('ADMIN_EVENTOS_BRINDE_RETIRADO') ) || temPermissao( array('ADMIN_EVENTOS_BRINDE_NAO-RETIRADO') )   ){
							$links[] = '<a href="/adm/admin/eventos/participantes/'.$aRow[$aColumns[3]].'">
											<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("participantes")).'
										</a>';
						}
						
						if( temPermissao( array('ADMIN_EVENTOS_SORTEIO-REGULAMENTO') )  ){
							$links[] = '<a href="/adm/admin/eventos/sorteio-regulamento/'.$aRow[$aColumns[3]].'">
											<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("regulamento_sorteio")).'
										</a>';
						}
					}else{
						if( temPermissao( array('ADMIN_EVENTOS_BRINDE') ) || temPermissao( array('ADMIN_EVENTOS_BRINDE_RETIRADO') ) || temPermissao( array('ADMIN_EVENTO_BRINDE_NAO-RETIRADO') )   ){
							$links[] = '<a href="/adm/admin/eventos/brinde/'.$aRow[$aColumns[3]].'">
											<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("brinde")).'
										</a>';
						}
					}
					
					if( temPermissao( array('ADMIN_EVENTOS_PREMIOS') )  ){
						$links[] = '<a href="/adm/admin/eventos/premios/'.$aRow[$aColumns[3]].'">
										<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("premios")).'
									</a>';
					}
					/*
					if( temPermissao( array('ADMIN_EVENTOS_PREMIOS') )  ){
						$links[] = '<a href="/adm/excel/admin/eventos/participantes/'.$aRow[$aColumns[3]].'" target="_blank">
										<i class="fa fa-file-excel-o"></i> '.converteMJSON($translate->translate("exportar_participantes")).'
									</a>';
					}
					
					//SE LOTERIA FEDERAL
					if( $aRow[$aColumns[10]]==1 ){
						if( temPermissao( array('ADMIN_EVENTOS_ELEMENTO-SORTEAVEL') )  ){
							$links[] = '<a href="/adm/admin/eventos/elemento-sorteavel/'.$aRow[$aColumns[3]].'">
											<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("gerar_elemento_sorteavel")).'
										</a>';
						}
						if( temPermissao( array('ADMIN_EVENTOS_ELEMENTOS_PARTICIPANTES') )  ){
							$links[] = '<a href="/adm/admin/eventos/elementos-participantes/'.$aRow[$aColumns[3]].'" target="_blank">
											<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("elementos_participantes_todos")).'
										</a>';
							$links[] = '<a href="/adm/admin/eventos/elementos-participantes-cupom/'.$aRow[$aColumns[3]].'" target="_blank">
											<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("elementos_participantes_cupom")).'
										</a>';
						}
						if( temPermissao( array('ADMIN_EVENTOS_GANHADORES') )  ){
							$links[] = '<a href="/adm/admin/eventos/ganhadores/'.$aRow[$aColumns[3]].'">
											<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("ganhadores")).'
										</a>';
						}
					}
	*/
					if( temPermissao( array('ADMIN_EVENTOS_EDITAR') )  ){
						$links[] = '<a href="/adm/admin/eventos/editar/'.$aRow[$aColumns[3]].'">
										<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("ttl_editar")).'
									</a>';
					}

					if( temPermissao( array('ADMIN_EVENTOS_CANCELAR') )  ){
						$links[] = '<a href="javascript:void(0);" onclick="bootbox.confirm(\''.converteMJSON($translate->translate("msg_cancelar")).'\', function(result){ if(result){ window.location.href=\'/adm/admin/eventos/excluir/'.$aRow[$aColumns[3]].'\';} })">
										<i class="fa fa-times"></i> '.converteMJSON($translate->translate("cancelar")).'
									</a>';
					}
				}else{
					
					if( temPermissao( array('ADMIN_EVENTOS_EDITAR') )  ){
						$links[] = '<a href="/adm/admin/eventos/editar/'.$aRow[$aColumns[3]].'">
										<i class="fa fa-edit"></i> '.converteMJSON($translate->translate("ttl_editar")).'
									</a>';
					}
				}

				$row[$colunas[$i]['data']] = paginacaoAjaxBotoes(3, array(), "", "fa-cog", converteMJSON($translate->translate("acoes")), "", "", array(), "", $links);
				
			}elseif( $aColumns[$i] != ''){
				
				$valor = $aRow[ $aColumns[$i] ];
				
				if( $i==0 ){
					$valor = "<a href='".$_SESSION['http_s'].$_SESSION['url_site']."/".$aRow[$aColumns[9]]."' target='blank'>".$valor."</a>";
				}
				
				if( $i==1 ){
					$valor = converte_data($valor)." ".$translate->translate("as_crase")." ".substr($aRow[$aColumns[6]], 0, 5);
				}
				if( $i==2 ){
					$valor = converte_data($valor)." ".$translate->translate("as_crase")." ".substr($aRow[$aColumns[7]], 0, 5);
				}
				
				if( $i==3 ){

					$evento = getEventoById($aRow[ $aColumns[3] ]);
					if( $evento->elementos_gerados==0 ){
						if($aRow[ $aColumns[8] ]>0){
							$numeros_sorteaveis = objetoPHP(executaSQL("SELECT * FROM evento_numeros_sorteaveis WHERE id = '".$aRow[ $aColumns[8] ]."'"));
		
							$numeros_gerados = objetoPHP(executaSQL("SELECT COUNT(*) as total FROM elemento_sorteavel WHERE id_evento = '".$aRow[ $aColumns[3] ]."'"));
		
							$valor = number_format( ($numeros_gerados->total/$numeros_sorteaveis->quantidade*100), 0)."%";
						}else{
							$valor = '-';
						}
					}else{
						$valor = '100%';
					}
				}
				
				if( $i==4 ){
					switch($valor){
						case 1:  $valor = 'Ativa'; break;
						case 99: $valor = 'Teste'; break;
						case 3:  $valor = 'Cancelada'; break;
					}
				}
								
				if($i<8)
					$row[$colunas[$i]['data']] = converteMJSON($valor);

			}
		}
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	
?>