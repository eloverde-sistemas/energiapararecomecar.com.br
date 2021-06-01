<?
	$empresa = empresaDados();
?>
<div style="width:700px">

<style>
	.legendLabel{
		font-size: 14px;
		padding: 5px;
	}
	
	*{
		font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif;	
	}
</style>
<?
	$idCampanha = $_GET['id'];

	$campanha = executaSQL("SELECT * FROM evento WHERE id= '".$idCampanha."' ");
	if( nLinhas($campanha)>0 ){
		//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
		$campanha = objetoPHP($campanha);



		$encerrouCampanha = false;
		//SE ENCERROU A CAMPANHA
		if( date("Y-m-d") > $campanha->dt_termino ){
			$encerrouCampanha = true;
			
			$dataInicioCampanha = $campanha->dt_inicio;
			
			$dataTerminoCampanha = $campanha->dt_termino;
		}
		


		$lojasCampanha = executaSQL("SELECT l.id, l.nome_fantasia, l.cnpj FROM loja l, evento_loja el WHERE el.id_loja=l.id AND el.id_evento='".$campanha->id."' ORDER BY nome_fantasia");
		
		while($loja = objetoPHP($lojasCampanha)){ ?>
			

			<p style="font-size:20px; font-weight:bold" align="center">Campanha - <?=$campanha->titulo?></p>
			<p style="font-size:16px; font-weight:bold" align="center">Relatório Por Loja</p>

			<p style="font-size:14px; font-weight:bold"><?=$loja->nome_fantasia." (".$loja->cnpj.")"?></p>
	

		
		<?

			$inscricoes = $cupons = $cuponsValidados = array();

			//PARTICIPANTES POR SEXO
			$totalParticipantes = objetoPHP( executaSQL("SELECT COUNT(*) AS total 
															FROM participante_cupom pc, loja l 
															WHERE pc.id_evento = '".$idCampanha."' 
															AND pc.id_situacao IN (2,90) 
															AND l.id='".$loja->id."'
															AND pc.cnpj=l.cnpj  ") )->total;
			
			$qtdeMasc 	= objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom pc, loja l WHERE pc.id_evento = '".$campanha->id."' AND pc.id_situacao IN (2,90) AND l.id='".$loja->id."' AND pc.cnpj=l.cnpj AND EXISTS (SELECT 1 FROM participante p WHERE pc.id_participante=p.id AND p.sexo='1' ) ") )->total;
			$qtdeFem 	= objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom pc, loja l WHERE pc.id_evento = '".$campanha->id."' AND pc.id_situacao IN (2,90) AND l.id='".$loja->id."' AND pc.cnpj=l.cnpj AND EXISTS (SELECT 1 FROM participante p WHERE pc.id_participante=p.id AND p.sexo='2' ) ") )->total;
	
?>

			<h3>Quantidade de Participantes por Sexo</h3>
			<br><br>
			<strong>Masculino</strong>: <?=$qtdeMasc?>
			<br><br>
			<strong>Feminino</strong>: <?=$qtdeFem?>
			<br><br>
			<strong>Total</strong>: <?=($qtdeMasc+$qtdeFem)?>
			<p></p>


<?				

			
			if($campanha->id_tipo_sorteio==2){
				
				//PARTICIPANTES NOS ÚLTIMOS 15 DIAS
				for ($i=0; $i<=15; $i++) { 
			
					if( $encerrouCampanha==true ){
						$dia[$i] = subDayIntoDate(Date($campanha->dt_termino), 15-$i);
					}else{
						$dia[$i] = subDayIntoDate(Date('Y-m-d'), 15-$i);
					}
			
					$qtdeInscricoes = objetoPHP( executaSQL("SELECT COUNT(*) AS total 
																FROM evento_participante ev
																WHERE ev.id_evento = '".$campanha->id."' 
																AND ev.data_participacao BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59'
																AND EXISTS (SELECT 1 
																			FROM participante_cupom pc, loja l 
																			WHERE pc.id_evento = '".$campanha->id."' 
																			AND pc.id_participante=ev.id_participante
																			AND pc.id_situacao IN (2,90) 
																			AND l.id='".$loja->id."'
																			AND pc.cnpj=l.cnpj
																			) ") 
												)->total;
					
					$inscricoes[$i]	= $qtdeInscricoes;
			
		
					$qtdeCupons = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom pc, loja l  
															WHERE pc.id_evento = '".$campanha->id."' 
															AND pc.id_situacao IN (2,90) 
															AND l.id='".$loja->id."'
															AND pc.cnpj=l.cnpj
															AND pc.dt_cadastro BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59' ") 
									)->total;
									
					$cupons[$i]	= $qtdeCupons;
			
					$qtdeCuponsValidos = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom pc, loja l  
																WHERE pc.id_evento = '".$campanha->id."' 
																AND pc.id_situacao IN (2,90) 
																AND l.id='".$loja->id."'
																AND pc.cnpj=l.cnpj
																AND pc.dt_cadastro BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59' ") 
											)->total;
		
					$cuponsValidados[$i]	= $qtdeCuponsValidos;
			
				}		
		
			}else{
		
				//Elementos sorteáveis
				$qtdeTotalES = objetoPHP( executaSQL("SELECT COUNT(*) as qtde FROM elemento_sorteavel WHERE id_evento = '".$campanha->id."'") )->qtde;
				//Elementos sorteáveis utilizados	
				if($campanha->id_tipo_campanha==3){
			
					$qtdeUtilizadaES = objetoPHP( executaSQL("SELECT COUNT(*) as qtde FROM elemento_sorteavel el WHERE el.id_evento = '".$campanha->id."' AND 
																EXISTS ( SELECT 1 FROM cupom_multiplo cm, participante_cupom pc 
																		WHERE cm.id_elem_sorteavel=el.id
																		AND pc.id=cm.id_part_cupom
																		AND pc.id_situacao IN (2,90) )") )->qtde;
				}else{
					$qtdeUtilizadaES = objetoPHP( executaSQL("SELECT COUNT(*) as qtde FROM elemento_sorteavel WHERE id_evento = '".$campanha->id."' AND id_participante_cupom>0") )->qtde;
				}
				

				
				//	Inscrições nos ultimos 15 dias
				for ($i=0; $i<=15; $i++) { 
			
					$dia[$i] = subDayIntoDate(Date('Y-m-d'), 15-$i);
			
					$qtdeInscricoes = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM evento_participante WHERE id_evento = '".$campanha->id."' AND data_participacao BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59'") )->total;
					$inscricoes[$i]	= $qtdeInscricoes;
			
					$qtdeCupons = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom WHERE id_evento = '".$campanha->id."' AND dt_cadastro BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59' ") )->total;
					$cupons[$i]	= $qtdeCupons;
			
					$qtdeCuponsValidos = objetoPHP( executaSQL("SELECT COUNT(*) AS total FROM participante_cupom WHERE id_evento = '".$campanha->id."' AND dt_cadastro BETWEEN '".$dia[$i]." 00:00:00' AND '".$dia[$i]." 23:59:59' AND id_situacao='2'") )->total;
					$cuponsValidados[$i]	= $qtdeCuponsValidos;
			
				}
				
				
		
			}
			
	
			$totalMasculino = $masculino = $totalFeminino = $feminino = 0; 
		
			//PARTICIPANTES POR BAIRRO(CIDADE)
			$bairros = executaSQL("SELECT DISTINCT(p.bairro) as bairro, p.id_cidade, COUNT(*) as TOTAL 
									FROM participante p, participante_cupom pc, loja l 
									WHERE pc.id_evento='".$campanha->id."' 
									AND p.id=pc.id_participante 
									AND pc.cnpj=l.cnpj
									AND l.id='".$loja->id."'
									GROUP BY p.id_cidade, p.bairro 
									ORDER BY TOTAL DESC, p.id_cidade, p.bairro");
			
			if( nLinhas($bairros)>0 ){ ?>
			
				<p>&nbsp;</p>
				<h3>Participantes por Bairro</h3>
			
				<table border="1" cellpadding="2" cellspacing="0">
					<tr>
						<th width="390" ><strong>Bairro</strong></th>
						<th width="100" align="center"><strong>Masculino</strong></th>
						<th width="100" align="center"><strong>Feminino</strong></th>
						<th width="100" align="center"><strong>Total</strong></th>
					</tr>
				
<?				while($bairro = objetoPHP($bairros)){
					
					$cidade = getMuniciopioById($bairro->id_cidade);
					
					$masculino = objetoPHP(executaSQL("SELECT COUNT(*) AS total FROM participante_cupom pc, loja l 
															WHERE pc.id_evento = '".$campanha->id."' 
															AND pc.cnpj=l.cnpj 
															AND l.id='".$loja->id."' 
															AND EXISTS (SELECT 1 FROM participante p WHERE pc.id_participante=p.id AND p.id_cidade='".$bairro->id_cidade."' AND p.bairro='".$bairro->bairro."'  AND p.sexo='2' ) ")
												)->total;
												
					$feminino = objetoPHP(executaSQL("SELECT COUNT(*) AS total FROM participante_cupom pc, loja l 
															WHERE pc.id_evento = '".$campanha->id."' 
															AND pc.cnpj=l.cnpj 
															AND l.id='".$loja->id."' 
															AND EXISTS (SELECT 1 FROM participante p WHERE pc.id_participante=p.id AND p.id_cidade='".$bairro->id_cidade."' AND p.bairro='".$bairro->bairro."'  AND p.sexo='1' ) ")
												)->total;

					$totalMasculino += $masculino; 
					$totalFeminino  += $feminino; 
?>			
					<tr>
						<td><?=$bairro->bairro." (".$cidade.")"?></td>
						<td align="center"><?=$masculino?></td>
						<td align="center"><?=$feminino?></td>
						<td align="center"><?=($masculino+$feminino)?></td>
					</tr>

<?				} ?>
					<tr>
						<td><strong>TOTAIS</strong></td>
						<td align="center"><strong><?=$totalMasculino?></strong></td>
						<td align="center"><strong><?=$totalFeminino?></strong></td>
						<td align="center"><strong><?=($totalMasculino+$totalFeminino)?></strong></td>
					</tr>
				</table>
<?			}




		//IDADE X CONSUMO POR SEXO
		$idadeRangeMasc2 = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$idadeRangeFem2 = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

		
		$partsMasc 	= executaSQL("SELECT TIMESTAMPDIFF(YEAR, p.dt_nascimento, CURDATE()) as idade, p.nome, p.id, p.dt_nascimento 
									FROM participante p 
									WHERE p.sexo='1' 
									AND EXISTS (SELECT 1 FROM participante_cupom pc, loja l  
														WHERE pc.id_evento = '".$campanha->id."' 
														AND pc.id_participante=p.id
														AND pc.id_situacao IN (2,90) 
														AND l.id='".$loja->id."'
														AND pc.cnpj=l.cnpj ) ");
		if( nLinhas($partsMasc)>0 ){
			while($partMasc = objetoPHP($partsMasc)){
				
				if( $partMasc->idade>0 ){
					$gastoPart = objetoPHP(executaSQL("SELECT SUM(pc.valor) as totalGasto FROM participante_cupom pc, loja l  
														WHERE pc.id_evento = '".$campanha->id."' 
														AND pc.id_situacao IN (2,90) 
														AND l.id='".$loja->id."'
														AND pc.cnpj=l.cnpj 
														AND id_participante='".$partMasc->id."'  "));
					
					//$idadeGastoMasc[] = array("idade"=>$partMasc->idade, "gasto"=>$gastoPart->totalGasto);
					
					
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
		}
		
		$partsFem 	= executaSQL("SELECT TIMESTAMPDIFF(YEAR, p.dt_nascimento, CURDATE()) as idade, p.nome, p.id, p.dt_nascimento 
									FROM participante p 
									WHERE p.sexo='2' 
									AND EXISTS (SELECT 1 FROM participante_cupom pc, loja l  
												WHERE pc.id_evento = '".$campanha->id."' 
												AND pc.id_participante=p.id
												AND pc.id_situacao IN (2,90) 
												AND l.id='".$loja->id."'
												AND pc.cnpj=l.cnpj ) ");
		if( nLinhas($partsFem)>0 ){
			while($partFem = objetoPHP($partsFem)){
				
				if( $partFem->idade>0 ){
					$gastoPart = objetoPHP(executaSQL("SELECT SUM(pc.valor) as totalGasto FROM participante_cupom pc, loja l  
														WHERE pc.id_evento = '".$campanha->id."' 
														AND pc.id_situacao IN (2,90) 
														AND l.id='".$loja->id."'
														AND pc.cnpj=l.cnpj 
														AND id_participante='".$partFem->id."' "));
					
					//$idadeGastoFem[] = array("idade"=>$partFem->idade, "gasto"=>$gastoPart->totalGasto);
					
				
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
		}
		
		
?>
		<p>&nbsp;</p>
		<h3>Alcance por Faixa Etária</h3>
	
		<table border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th width="390" ><strong>Faixa Etária</strong></th>
				<th width="100" align="center"><strong>Masculino</strong></th>
				<th width="100" align="center"><strong>Feminino</strong></th>
				<th width="100" align="center"><strong>Total</strong></th>
			</tr>

<?		
		$faixa = array('0-15','16-20', '21-25', '26-30', '31-35', '36-40', '41-45', '46-50', '51-55', '56-60', '61-65', '66-70', '71 + ');
		for($i=0; $i<=12; $i++){ ?>
			<tr>
				<td><?=$faixa[$i]?></td>
				<td align="center"><?=$idadeRangeMasc2[$i]?></td>
				<td align="center"><?=$idadeRangeFem2[$i]?></td>
				<td align="center"><?=($idadeRangeMasc2[$i]+$idadeRangeFem2[$i])?></td>
			</tr>
<?		} ?>
		</table>

			<p style="page-break-before:always;"></p>
		
<?
		}
		
	} ?>