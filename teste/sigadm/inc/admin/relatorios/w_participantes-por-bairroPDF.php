	<style>
		.negcenter{
			text-align:center;
			font-weight:bold;
		}
		.centro{
			text-align:center;
		}
		.neg{
			font-weight:bold;
		}
		.direita{
			text-align:right;
			padding-right:5px;
		}
	</style>

<?

	$campanha = $_GET['id'];
	$estado   = $_GET['id2'];
	$cidade   = $_GET['id3'];


	$sqlTotalMasculino = "SELECT COUNT(*) AS total FROM participante p WHERE p.sexo='1' ";
	
	$sqlTotalFeminino  = "SELECT COUNT(*) AS total FROM participante p WHERE p.sexo='2' ";

	if($estado>0){
		$sqlTotalMasculino .= " AND EXISTS (SELECT 1 FROM municipio m WHERE p.id_cidade=m.id AND id_estado='".$estado."')";	
		$sqlTotalFeminino  .= " AND EXISTS (SELECT 1 FROM municipio m WHERE p.id_cidade=m.id AND id_estado='".$estado."')";	
	}

	if($cidade>0){
		$sql .= " AND p.id_cidade='".$cidade."'";
		$sqlTotalGeral .= " AND p.id_cidade='".$cidade."'";
	}
	
	if($campanha>0){

		$sqlTotalMasculino .= " AND EXISTS ( SELECT 1 FROM evento_participante ep WHERE ep.id_evento='".$campanha."' AND ep.id_participante=p.id ) ";
		
		$sqlTotalFeminino  .= " AND EXISTS ( SELECT 1 FROM evento_participante ep WHERE ep.id_evento='".$campanha."' AND ep.id_participante=p.id ) ";

	}

	$totalMasculino = objetoPHP(executaSQL($sqlTotalMasculino))->total;
	$totalFeminino = objetoPHP(executaSQL($sqlTotalFeminino))->total;
	
	$totalGeral = ($totalMasculino + $totalFeminino);

?>

	<h1 class="centro"><?=$translate->translate('participantes_por_bairro')?></h1>


		<h2><?=$translate->translate('totais')?></h2>
		<table width="690" border="1" cellpadding="2" cellspacing="0">
			<thead>
				<tr>
					<th class="negcenter" colspan="2">Masculino</th>
					<th class="negcenter" colspan="2">Feminino</th>
					<th class="negcenter">Total Geral</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="centro" id="totalMasculino"><?=$totalMasculino?></td>
					<td class="centro" id="percMasculino"><?=number_format($totalMasculino/$totalGeral*100, 2)?>%</td>
					<td class="centro" id="totalFeminino"><?=$totalFeminino?></td>
					<td class="centro" id="percFeminino"><?=number_format($totalFeminino/$totalGeral*100, 2)?>%</td>
					<td class="centro" id="totalGeral"><?=$totalGeral?></td>
				</tr>
			</tbody>
		</table>

		<h2><?=$translate->translate('bairros')?></h2>
	
<?		
	
		$sql = "SELECT DISTINCT(bairro) as bairro, id_cidade, COUNT(*) as total FROM participante p WHERE 1=1";
	
		if($campanha>0){
			$sql .= " AND EXISTS (SELECT 1 FROM evento_participante ep WHERE ep.id_evento='".$campanha."' AND p.id=ep.id_participante )";
		}
	
		if($cidade>0){
			$sql .= " AND p.id_cidade='".$cidade."'";
		}
	
		if($estado>0){
			$sql .= " AND EXISTS (SELECT 1 FROM municipio m WHERE p.id_cidade=m.id AND id_estado='".$estado."')";	
		}
	
		$sql .= " GROUP BY p.id_cidade, p.bairro ORDER BY TOTAL DESC, p.id_cidade, p.bairro";

		$exe = executaSQL($sql);
		if(nLinhas($exe)>0){
						
					$x=0;
					$primeira = false;
					
					while( $reg = objetoPHP($exe) ){ 
					
				
						if( ($primeira==false && $x%25==0) || ($primeira== true && $x%35==0) ){ 
						
						
							if( $primeira==false && $x>0 && $x%25==0 ){
								$x=35;
								$primeira = true;
							} 

							if( $primeira == true ){ ?>
								</table>
								<p style="page-break-before:always;"></p>
								<br>&nbsp;<br>
						<?	} ?>
						
						<table width="690" border="1" cellpadding="2" cellspacing="0">

		                    <tr>
								<th width="180" class="negcenter"><?=$translate->translate("cidade")?></th>
								<th width="180" class="negcenter"><?=$translate->translate("bairro")?></th>
								<th width="80" class="negcenter"><?=$translate->translate("total")?></th>
								<th width="80" class="negcenter"><?=$translate->translate("feminino")?></th>
								<th width="80" class="negcenter"><?=$translate->translate("masculino")?></th>
								<th width="90" class="negcenter">Porcentagem do Bairro</th>
							</tr>

<?						} 


						$sqlTotal		= "SELECT COUNT(*) AS total FROM evento_participante ep WHERE 1=1 ";
						$sqlTotalMasc	= "SELECT COUNT(*) AS total FROM evento_participante ep WHERE 1=1 ";
						$sqlTotalFem	= "SELECT COUNT(*) AS total FROM evento_participante ep WHERE 1=1 ";
	
						if($campanha>0){
							$sqlTotal 		.= " AND ep.id_evento='".$campanha."' ";
							$sqlTotalMasc 	.= " AND ep.id_evento='".$campanha."' ";
							$sqlTotalFem 	.= " AND ep.id_evento='".$campanha."' ";
						}

						$sqlTotal 		.= " AND EXISTS (SELECT 1 FROM participante p WHERE ep.id_participante=p.id AND p.id_cidade='".$reg->id_cidade."' AND p.bairro='".$reg->bairro."' )";
	
						$sqlTotalMasc 	.= " AND EXISTS (SELECT 1 FROM participante p WHERE ep.id_participante=p.id AND p.id_cidade='".$reg->id_cidade."' AND p.bairro='".$reg->bairro."' AND p.sexo='1' ) ";
						$sqlTotalFem 	.= " AND EXISTS (SELECT 1 FROM participante p WHERE ep.id_participante=p.id AND p.id_cidade='".$reg->id_cidade."' AND p.bairro='".$reg->bairro."' AND p.sexo='2' ) ";
	
						$totalParticipantes = objetoPHP( executaSQL($sqlTotal) )->total;
	
						$totalMasc 	= objetoPHP( executaSQL($sqlTotalMasc) )->total;
						$totalFem 	= objetoPHP( executaSQL($sqlTotalFem) )->total;
						
						$totalGeralMasc += $totalMasc;
						$totalGeralFem += $totalFem;
?>
					
						<tr>
							<td width="180"><?=getMunicipioNomeById($reg->id_cidade)?></td>
							<td width="180"><?=$reg->bairro?></td>
							<td class="centro" width="80"><?=$totalParticipantes?></td>
							<td class="centro" width="80"><?=$totalFem?></td>
							<td class="centro" width="80"><?=$totalMasc?></td>
							<td class="centro" width="90"><?=number_format(($totalParticipantes/$totalGeral)*100, 2)?>%</td>
						</tr>

			<?			$x++;
					} ?>
						
			
		</table>

<?	}else{ ?>
					
		<tr>
			<td colspan="5"><?=$translate->translate("msg_nenhum_menu_encontrado_evento")?></td>
		</tr>

<?	} ?>