<?
	$campanha = executaSQL("SELECT * FROM evento WHERE id= '".$_SESSION['campanha_atual']."' AND id_situacao IN (1) ");
	if( nLinhas($campanha)>0 ){
		//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
		$campanha = objetoPHP($campanha);
?>


<?
		$encerrouCampanha = false;
		//SE ENCERROU A CAMPANHA
		if( date("Y-m-d") > $campanha->dt_termino ){
			$encerrouCampanha = true;
		}

		//TOTAL DE PARTICIPANTES
		$totalParticipantes = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM evento_participante WHERE id_evento = '".$campanha->id."' ") )->total;
		if($totalParticipantes>0){


			//PARTICIPANTES NOS ÚLTIMOS 15 DIAS
			for ($i=0; $i<=15; $i++) { 
		
				if( $encerrouCampanha==true ){
					$dia[$i] = subDayIntoDate(Date($campanha->dt_termino), 15-$i);
				}else{
					$dia[$i] = subDayIntoDate(Date('Y-m-d'), 15-$i);
				}
				
				$qtdeInscricoes = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM evento_participante WHERE id_evento = '".$campanha->id."' AND data_participacao BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59'") )->total;
				$inscricoes[$i]	= $qtdeInscricoes;

			}

		}else{
			echo "Nenhum Participante cadastrado na Campanha!";
		}

	} ?>


	
	
			<style>
				.legendLabel{
					font-size: 14px;
					padding: 5px;
				}
			</style>

<?	if( $campanha->id > 0 ){ ?>			

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><strong>Total Participantes</strong>: <?=$totalParticipantes?></h1>
					<br /><br />
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div id="qtdeInscricoes"></div>
				</div>
			</div>
			<br /><br />
			
<?	} ?>			


			<script src="/lib/highcharts4.2.5/js/highcharts.js"></script>
			<script src="/lib/highcharts4.2.5/js/modules/exporting.js"></script>
			<script>
				Highcharts.setOptions({
					lang: {
							months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
							shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
							weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
							loading: ['Atualizando o gráfico...aguarde'],
							contextButtonTitle: 'Exportar gráfico',
							decimalPoint: ',',
							thousandsSep: '.',
							downloadJPEG: 'Baixar imagem JPEG',
							downloadPDF: 'Baixar arquivo PDF',
							downloadPNG: 'Baixar imagem PNG',
							downloadSVG: 'Baixar vetor SVG',
							printChart: 'Imprimir gráfico',
							rangeSelectorFrom: 'De',
							rangeSelectorTo: 'Para',
							rangeSelectorZoom: 'Zoom',
							resetZoom: 'Limpar Zoom',
							resetZoomTitle: 'Voltar Zoom para nível 1:1',
					}
				});



<?	if( $campanha->id >0){ ?>			

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
							}]
				});
		

<?	} ?>

			</script>
