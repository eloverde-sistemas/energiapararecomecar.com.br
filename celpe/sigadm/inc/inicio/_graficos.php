<?
	$campanha = executaSQL("SELECT * FROM evento WHERE id= '".$_SESSION['campanha_atual']."' AND id_situacao IN (1) ");
	if( nLinhas($campanha)>0 ){
		//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
		$campanha = objetoPHP($campanha);
		
		$encerrouCampanha = false;
		//SE ENCERROU A CAMPANHA
		if( date("Y-m-d") > $campanha->dt_termino ){
			$encerrouCampanha = true;
		}

		//TOTAL DE PARTICIPANTES
		$totalParticipantes = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM evento_participante WHERE id_evento = '".$campanha->id."' ") )->total;
		if($totalParticipantes>0){

			//PARTICIPANTES POR SEXO
			$qtdeMasc 	= objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM evento_participante ep WHERE ep.id_evento = '".$campanha->id."' AND EXISTS (SELECT 1 FROM participante p WHERE ep.id_participante=p.id AND p.sexo='1' ) ") )->total;
			$qtdeFem 	= objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM evento_participante ep WHERE ep.id_evento = '".$campanha->id."' AND EXISTS (SELECT 1 FROM participante p WHERE ep.id_participante=p.id AND p.sexo='2' ) ") )->total;


			//PARTICIPANTES POR BAIRRO(CIDADE)
			$bairros = executaSQL("SELECT DISTINCT(bairro) as bairro, id_cidade, COUNT(*) as TOTAL FROM participante p, evento_participante ep WHERE ep.id_evento='".$campanha->id."' AND p.id=ep.id_participante GROUP BY p.id_cidade, p.bairro ORDER BY TOTAL DESC, p.id_cidade, p.bairro LIMIT 40");
			while($bairro = objetoPHP($bairros)){
				
				$cidade = getMuniciopioById($bairro->id_cidade);
				
				$bairroNomes[] = $bairro->bairro." (".$cidade.")";
				
				$quantFemininoBairro[]  = objetoPHP(executaSQL("SELECT COUNT(*) AS total FROM evento_participante ep WHERE ep.id_evento = '".$campanha->id."' AND EXISTS (SELECT 1 FROM participante p WHERE ep.id_participante=p.id AND p.id_cidade='".$bairro->id_cidade."' AND p.bairro='".$bairro->bairro."'  AND p.sexo='2' ) "))->total;
				$quantMasculinoBairro[] = objetoPHP(executaSQL("SELECT COUNT(*) AS total FROM evento_participante ep WHERE ep.id_evento = '".$campanha->id."' AND EXISTS (SELECT 1 FROM participante p WHERE ep.id_participante=p.id AND p.id_cidade='".$bairro->id_cidade."' AND p.bairro='".$bairro->bairro."'  AND p.sexo='1' ) "))->total;
		
			}	

			//PARTICIPANTES NOS ??LTIMOS 15 DIAS
			for ($i=0; $i<=15; $i++) { 
		
				if( $encerrouCampanha==true ){
					$dia[$i] = subDayIntoDate(Date($campanha->dt_termino), 15-$i);
				}else{
					$dia[$i] = subDayIntoDate(Date('Y-m-d'), 15-$i);
				}
				
				$qtdeInscricoes = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM evento_participante WHERE id_evento = '".$campanha->id."' AND data_participacao BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59'") )->total;
				$inscricoes[$i]	= $qtdeInscricoes;
		
				$qtdeCupons = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom WHERE id_evento = '".$campanha->id."' AND dt_cadastro BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59' ") )->total;
				$cupons[$i]	= $qtdeCupons;
		
				$qtdeCuponsValidos = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom WHERE id_evento = '".$campanha->id."' AND dt_cadastro BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59' AND id_situacao IN (2,90)") )->total;
				$cuponsValidados[$i]	= $qtdeCuponsValidos;
		
			}		


			//IDADE X CONSUMO POR SEXO
			$idadeRangeMasc2 = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			$idadeRangeFem2 = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
	
			
			$totalGastoMasc = 0;
			
			$partsMasc 	= executaSQL("SELECT TIMESTAMPDIFF(YEAR, p.dt_nascimento, CURDATE()) as idade, p.nome, p.id, p.dt_nascimento FROM participante p WHERE p.sexo='1' AND EXISTS (SELECT 1 FROM evento_participante ep WHERE ep.id_participante=p.id AND ep.id_evento='".$campanha->id."' ) ");
			if( nLinhas($partsMasc)>0 ){
				while($partMasc = objetoPHP($partsMasc)){
					
					$gastoPart = objetoPHP(executaSQL("SELECT SUM(valor) as totalGasto FROM participante_cupom WHERE id_evento='".$campanha->id."' AND id_participante='".$partMasc->id."' AND id_situacao IN (2,90) "));
					
					$idadeGastoMasc[] = array("idade"=>$partMasc->idade, "gasto"=>$gastoPart->totalGasto);
					
					$totalGastoMasc += $gastoPart->totalGasto;
					
					//['0-15','16-20', '21-25', '26-30', '31-35', '36-40', '41-45', '46-50', '51-55', '56-60', '61-65', '66-70', '71 + ']
					switch(true) {
					   case in_array($partMasc->idade, range(0,15)):
						  $idadeRangeMasc2[0] += 1;
					   break;
					   case in_array($partMasc->idade, range(16,20)):
						  $idadeRangeMasc2[1] += 1;
					   break;
					   case in_array($partMasc->idade, range(21,25)):
						  $idadeRangeMasc2[2] += 1;
					   break;
					   case in_array($partMasc->idade, range(26,30)):
						  $idadeRangeMasc2[3] += 1;
					   break;
					   case in_array($partMasc->idade, range(31,35)):
						  $idadeRangeMasc2[4] += 1;
					   break;
					   case in_array($partMasc->idade, range(36,40)):
						  $idadeRangeMasc2[5] += 1;
					   break;
					   case in_array($partMasc->idade, range(41,45)):
						  $idadeRangeMasc2[6] += 1;
					   break;
					   case in_array($partMasc->idade, range(46,50)):
						  $idadeRangeMasc2[7] += 1;
					   break;
					   case in_array($partMasc->idade, range(51,55)):
						  $idadeRangeMasc2[8] += 1;
					   break;
					   case in_array($partMasc->idade, range(56,60)):
						  $idadeRangeMasc2[9] += 1;
					   break;
					   case in_array($partMasc->idade, range(61,65)):
						  $idadeRangeMasc2[10] += 1;
					   break;
					   case in_array($partMasc->idade, range(66,70)):
						  $idadeRangeMasc2[11] += 1;
					   break;
					   case in_array($partMasc->idade, range(71,150)):
						  $idadeRangeMasc2[12] += 1;
					   break;
					}
					
				}
			}
			
			$totalGastoFem = 0;
			
			$partsFem 	= executaSQL("SELECT TIMESTAMPDIFF(YEAR, p.dt_nascimento, CURDATE()) as idade, p.nome, p.id, p.dt_nascimento FROM participante p WHERE p.sexo='2' AND EXISTS (SELECT 1 FROM evento_participante ep WHERE ep.id_participante=p.id AND ep.id_evento='".$campanha->id."' ) ");
			if( nLinhas($partsFem)>0 ){
				while($partFem = objetoPHP($partsFem)){
					
					$gastoPart = objetoPHP(executaSQL("SELECT SUM(valor) as totalGasto FROM participante_cupom WHERE id_evento='".$campanha->id."' AND id_participante='".$partFem->id."' AND id_situacao IN (2,90) "));
					
					$idadeGastoFem[] = array("idade"=>$partFem->idade, "gasto"=>$gastoPart->totalGasto);
					
					$totalGastoFem += $gastoPart->totalGasto;
				
					//['0-15','16-20', '21-25', '26-30', '31-35', '36-40', '41-45', '46-50', '51-55', '56-60', '61-65', '66-70', '71 + ']
					switch(true) {
					   case in_array($partFem->idade, range(0,15)):
						  $idadeRangeFem2[0] += 1;
					   break;
					   case in_array($partFem->idade, range(16,20)):
						  $idadeRangeFem2[1] += 1;
					   break;
					   case in_array($partFem->idade, range(21,25)):
						  $idadeRangeFem2[2] += 1;
					   break;
					   case in_array($partFem->idade, range(26,30)):
						  $idadeRangeFem2[3] += 1;
					   break;
					   case in_array($partFem->idade, range(31,35)):
						  $idadeRangeFem2[4] += 1;
					   break;
					   case in_array($partFem->idade, range(36,40)):
						  $idadeRangeFem2[5] += 1;
					   break;
					   case in_array($partFem->idade, range(41,45)):
						  $idadeRangeFem2[6] += 1;
					   break;
					   case in_array($partFem->idade, range(46,50)):
						  $idadeRangeFem2[7] += 1;
					   break;
					   case in_array($partFem->idade, range(51,55)):
						  $idadeRangeFem2[8] += 1;
					   break;
					   case in_array($partFem->idade, range(56,60)):
						  $idadeRangeFem2[9] += 1;
					   break;
					   case in_array($partFem->idade, range(61,65)):
						  $idadeRangeFem2[10] += 1;
					   break;
					   case in_array($partFem->idade, range(66,70)):
						  $idadeRangeFem2[11] += 1;
					   break;
					   case in_array($partFem->idade, range(71,150)):
						  $idadeRangeFem2[12] += 1;
					   break;
					}
					
				}
			}


			if($campanha->id_tipo_sorteio==1){
		
				//Elementos sorte??veis
				$qtdeTotalES = objetoPHP( executaSQL("SELECT COUNT(*) as qtde FROM elemento_sorteavel WHERE id_evento = '".$campanha->id."'") )->qtde;

				//Elementos sorte??veis utilizados	
				$qtdeUtilizadaES = objetoPHP( executaSQL("SELECT COUNT(*) as qtde FROM elemento_sorteavel WHERE id_evento = '".$campanha->id."' AND id_participante_cupom>0") )->qtde;
		
			}


?>
			<style>
				.legendLabel{
					font-size: 14px;
					padding: 5px;
				}
			</style>
			
			<div class="row">
				<div class="col-md-4">
					<div id="participantesCampanha"></div>
				</div>

				<div class="col-md-8">
					<div id="qtdeInscricoes"></div>
				</div>
			
			</div>
			
			<br /><br />

		<?	if($campanha->id_tipo_campanha==3){ ?>	
			
				<div class="row">
			<?	if($qtdeTotalES){ ?>
					<div class="col-md-3">
						<div id="elementosSorteaveis"></div>
					</div>
			
					<div class="col-md-8">
						<div id="rangeIdade"></div>
					</div>
			
			<?	}else{ ?>
					<div class="col-md-12">
						<div id="rangeIdade"></div>
					</div>
			<?	} ?>
				</div>
			
			<br /><br />

			<div class="row">
				<div class="col-md-12">
					<div id="participantesPorBairro" style="min-height: 1000px; margin: 0 auto"></div>
				</div>
			</div>

		<?	}elseif( $campanha->id_tipo_campanha==4 ){ ?>
			
			<div class="row">
		<?	if($qtdeTotalES){ ?>
				<div class="col-md-4">
					<div id="elementosSorteaveis"></div>
				</div>
				<div class="col-md-8">
					<div id="participantesPorBairro" style="min-height: 1000px; margin: 0 auto"></div>
				</div>
		<?	}else{ ?>
				<div class="col-md-12">
					<div id="participantesPorBairro" style="min-height: 1000px; margin: 0 auto"></div>
				</div>
		<?	} ?>
			</div>
			
		<?	} ?>

			<script src="/lib/highcharts4.2.5/js/highcharts.js"></script>
			<script src="/lib/highcharts4.2.5/js/modules/exporting.js"></script>
			<script>
				Highcharts.setOptions({
					lang: {
							months: ['Janeiro', 'Fevereiro', 'Mar??o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
							shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
							weekdays: ['Domingo', 'Segunda', 'Ter??a', 'Quarta', 'Quinta', 'Sexta', 'S??bado'],
							loading: ['Atualizando o gr??fico...aguarde'],
							contextButtonTitle: 'Exportar gr??fico',
							decimalPoint: ',',
							thousandsSep: '.',
							downloadJPEG: 'Baixar imagem JPEG',
							downloadPDF: 'Baixar arquivo PDF',
							downloadPNG: 'Baixar imagem PNG',
							downloadSVG: 'Baixar vetor SVG',
							printChart: 'Imprimir gr??fico',
							rangeSelectorFrom: 'De',
							rangeSelectorTo: 'Para',
							rangeSelectorZoom: 'Zoom',
							resetZoom: 'Limpar Zoom',
							resetZoomTitle: 'Voltar Zoom para n??vel 1:1',
					}
				});


			
				$('#participantesCampanha').highcharts({
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: '<?=$translate->translate('participantes')?>'
					},
					subtitle: {
						text: '<?=$translate->translate('qtde_participantes')?>: <?=$totalParticipantes?>'
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b> ({point.y:f})'
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						name: '',
						colorByPoint: true,
						data: [{
									name: '<?=$translate->translate('masculino').": ".($qtdeMasc)?>',
									y: <?=($qtdeMasc/$totalParticipantes)*100?>
								}, {
									name: '<?=$translate->translate('feminino').": ".($qtdeFem)?>',
									y: <?=($qtdeFem/$totalParticipantes)*100?>,
									sliced: true,
									selected: true
							}]
					}]
				});		


				$('#qtdeInscricoes').highcharts({
					title: {
						text: '<?=$translate->translate('qtde_inscricoes')?>',
						x: -20 //center
					},
					subtitle: {
						enable: false,
						text: '',
						x: -20
					},
					xAxis: {
						categories: [<? foreach ($dia as $key => $value) { echo ($key>0 ? ', ' : '').'"'.converte_data($value).'"'; } ?>]
					},
					yAxis: {
						title: {
							text: 'Quantidade'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						valueSuffix: ''
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						borderWidth: 0
					},
					credits: {
						enabled: false
					},
					tooltip: {
						valueSuffix: ' <?=$translate->translate("inscricoes")?>'
					},
					series: [{
								name: '<?=$translate->translate("inscricoes")?>',
								data: [<? foreach ($inscricoes as $key => $value) { echo ($key>0 ? ', ' : '').($value>0 ? $value : 0); } ?>]
							},{
								name: 'Cupons Cadastrados',
								data: [<? foreach ($cupons as $key => $value) { echo ($key>0 ? ', ' : '').($value>0 ? $value : 0); } ?>]
							},{
								name: 'Cupons Validados',
								data: [<? foreach ($cuponsValidados as $key => $value) { echo ($key>0 ? ', ' : '').($value>0 ? $value : 0); } ?>]
							}]
				});
				
				

		<?	if($qtdeTotalES){ ?>
				
				$('#elementosSorteaveis').highcharts({
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: '<?=$translate->translate('elementos_sorteaveis')?>'
					},
					subtitle: {
						text: '<?=$translate->translate('qtde_elementos_sorteaveis')?>: <?=$qtdeTotalES?>'
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b> ({point.y:f})'
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						name: '',
						colorByPoint: true,
						data: [{
							name: '<?=$translate->translate('qtde_elementos_sorteaveis_disponivies').": ".($qtdeTotalES-$qtdeUtilizadaES)?>',
							y: <?=(round(($qtdeTotalES-$qtdeUtilizadaES)/$qtdeTotalES)*100)?>
						}, {
							name: '<?=$translate->translate('qtde_elementos_sorteaveis_utilizados').": ".$qtdeUtilizadaES?>',
							y: <?=(round($qtdeUtilizadaES/$qtdeTotalES)*100)?>,
							sliced: true,
							selected: true
						}]
					}]
				});	
				
		<?	} ?>

		
		<?	if($totalGastoMasc>0 || $totalGastoFem>0){ ?>

				//var categoriesAge = ['0-17', '18-24', '25-34', '35-44', '45-54', '55-64', '65 + '];
				$('#rangeIdade').highcharts({
				   chart: {
						type: 'column'
					},
					credits: {
						enabled: false
					},
					title: {
						text: 'Alcance por Faixa Et??ria'
					},
					subtitle: {
						text: ''
					},
					xAxis: {
						categories: ['0-15','16-20', '21-25', '26-30', '31-35', '36-40', '41-45', '46-50', '51-55', '56-60', '61-65', '66-70', '71 + '],
						crosshair: true
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Total'
						}
					},
					tooltip: {
						headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
						pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
							'<td style="padding:0"><b>{point.y:1f}</b></td></tr>',
						footerFormat: '</table>',
						shared: true,
						useHTML: true
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [{
						name: 'Masculino',
						data: [<? $x=0; foreach ($idadeRangeMasc2 as $qtdeIdadeMasc) { $x++; echo ($x>1 ? ', ' : '').$qtdeIdadeMasc; } ?>]
				
					},{
						name: 'Feminino',
						data: [<? $x=0; foreach ($idadeRangeFem2 as $qtdeIdadeFem) { $x++; echo ($x>1 ? ', ' : '').$qtdeIdadeFem; } ?>]
				
					}]
				});
		<?	} ?>
			
					

				$('#participantesPorBairro').highcharts({
						chart: {
							type: 'bar'
						},
						credits: {
							enabled: false
						},
						title: {
							text: 'Participantes por Bairro (Cidade)'
						},
						xAxis: {
							categories: [<? foreach ($bairroNomes as $key => $value) { echo ($key>0 ? ', ' : '').'"'.$value.'"'; } ?>]
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Total de Participantes'
							}
						},
						legend: {
							reversed: true
						},
						plotOptions: {
							series: {
								stacking: 'normal'
							}
						},
						series: [{
							name: 'Masculino',
							data: [<? foreach ($quantMasculinoBairro as $key => $value) { echo ($key>0 ? ', ' : '').($value>0 ? $value : 0); } ?>]
						}, {
							name: 'Feminino',
							data: [<? foreach ($quantFemininoBairro as $key => $value) { echo ($key>0 ? ', ' : '').($value>0 ? $value : 0); } ?>]
						}]
				});



				$('#idade_gasto').highcharts({
					chart: {
						type: 'scatter',
						zoomType: 'xy'
					},
					credits: {
						enabled: false
					},
					title: {
						text: 'Idade versus Consumo'
					},
					xAxis: {
						title: {
							enabled: true,
							text: 'Idade'
						},
						startOnTick: true,
						endOnTick: true,
						showLastLabel: true
					},
					yAxis: {
						title: {
							text: 'Consumo'
						}
					},
					legend: {
						layout: 'vertical',
						align: 'left',
						verticalAlign: 'top',
						x: 100,
						y: 70,
						floating: true,
						borderWidth: 1
					},
					plotOptions: {
						scatter: {
							marker: {
								radius: 5,
								states: {
									hover: {
										enabled: true,
										lineColor: 'rgb(100,100,100)'
									}
								}
							},
							states: {
								hover: {
									marker: {
										enabled: false
									}
								}
							},
							tooltip: {
								headerFormat: '<b>{series.name}</b><br>',
								pointFormat: '{point.x} anos, R${point.y} '
							}
						}
					},
					series: [{
						name: 'Feminino',
						color: 'rgba(223, 83, 83, .5)',
						data: [<? $x=0; foreach ($idadeGastoFem as $dados) { $x++; echo ($x>1 ? ', ' : '')."[".$dados['idade'].",".$dados['gasto']."]"; } ?>]
				
					}, {
						name: 'Masculino',
						color: 'rgba(119, 152, 191, .5)',
						data: [<? $x=0; foreach ($idadeGastoMasc as $dados) { $x++; echo ($x>1 ? ', ' : '')."[".$dados['idade'].",".$dados['gasto']."]"; } ?>]
					}]
				});
			
			
			
			</script>

<?	
		}else{
			echo "Nenhum Participante cadastrado na Campanha!";
		}
	}else{
		echo "Campanha n??o est?? Ativa!";
	} ?>