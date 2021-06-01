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

$idCampanha = $_GET['id'];

$campanha = executaSQL("SELECT * FROM evento WHERE id= '".$idCampanha."' ");
if( nLinhas($campanha)>0 ){
	//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
	$campanha = objetoPHP($campanha);


	$lojasPart		= executaSQL("SELECT l.id, l.nome_fantasia, l.cnpj FROM loja l, evento_loja el WHERE el.id_loja=l.id AND el.id_evento='".$campanha->id."' ORDER BY nome_fantasia");

	$totalPart		= nLinhas(executaSQL("SELECT DISTINCT(id_participante) FROM participante_cupom WHERE id_evento='".$campanha->id."' AND id_situacao IN (2,90)"));
	
	$totais			= objetoPHP(executaSQL("SELECT COUNT(*) as totalCupons, SUM(valor) as totalConsumo FROM participante_cupom WHERE id_evento='".$campanha->id."' AND id_situacao IN (2,90)"));
	
	$totalCupons	= $totais->totalCupons;
	$totalConsumo	= $totais->totalConsumo;

?>

	<h1 class="centro"><?=$campanha->titulo?> - <?=$translate->translate('participantes_por_loja')?></h1>


		<h2><?=$translate->translate('totais')?></h2>
		<table width="690" border="1" cellpadding="2" cellspacing="0">
				<tr>
					<th class="negcenter">Qtde Lojas</th>
					<th class="negcenter">Qtde Participantes</th>
					<th class="negcenter">Qtde Cupons</th>
					<th class="negcenter">Total Consumo</th>
					<th class="negcenter">Ticket Médio</th>
				</tr>
			<tbody>
				<tr>
					<td class="centro"><?=nLinhas($lojasPart)?></td>
					<td class="centro"><?=$totalPart?></td>
					<td class="centro"><?=$totalCupons?></td>
					<td class="centro"><?=formatarDinheiro($totalConsumo)?></td>
					<td class="centro"><?=formatarDinheiro($totalConsumo/$totalCupons)?></td>
				</tr>
			</tbody>
		</table>
	
		<h2><?=$translate->translate('lojas')?></h2>

<?					
				if(nLinhas($lojasPart)>0){
					$x=0;
					$primeira = false;
					
					while($loja = objetoPHP($lojasPart)){ 
					
						$cuponsLoja = objetoPHP(executaSQL("SELECT COUNT(*) as totalCupons, SUM(c.valor) as totalConsumo FROM participante_cupom c, loja l WHERE c.id_evento='".$campanha->id."' AND REPLACE(REPLACE(REPLACE(c.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '') AND c.cnpj =l.cnpj AND c.id_situacao IN (2,90)"));
						
						$partPorLoja = nLinhas(executaSQL("SELECT DISTINCT(id_participante) FROM participante_cupom c, loja l WHERE c.id_evento='".$campanha->id."' AND REPLACE(REPLACE(REPLACE(c.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '') AND c.cnpj =l.cnpj AND c.id_situacao IN (2,90)"));
						

						
						if( ($primeira==false && $x%16==0) || ($primeira== true && $x%20==0) ){ 
						
						
							if( $primeira==false && $x>0 && $x%16==0 ){
								$x=20;
								$primeira = true;
							} 

							if( $primeira == true ){ ?>
								</table>
								<p style="page-break-before:always;"></p>
								<br>&nbsp;<br>
						<?	} ?>
						
						<table width="690" border="1" cellpadding="2" cellspacing="0">

							<tr>
								<th width="240" class="negcenter"  rowspan="2"><?=$translate->translate("loja")?></th>
								<th width="150" class="negcenter"  colspan="2"><?=$translate->translate("cupons")?></th>
								<th width="150" class="negcenter"  colspan="2"><?=$translate->translate("consumo")?></th>
								<th width="150" class="negcenter"  colspan="2"><?=$translate->translate("participantes")?></th>
							</tr>
							<tr>
								<th width="80" class="negcenter" ><?=$translate->translate("total")?></th>
								<th width="70" class="negcenter" ><?=$translate->translate("porcent")?></th>
								<th width="80" class="negcenter" ><?=$translate->translate("total")?></th>
								<th width="70" class="negcenter" ><?=$translate->translate("porcent")?></th>
								<th width="80" class="negcenter" ><?=$translate->translate("total")?></th>
								<th width="70" class="negcenter" ><?=$translate->translate("porcent")?></th>
							</tr>
<?						} ?>
					
						<tr>
							<td width="240"><span class="neg"><?=$loja->nome_fantasia?></span><br>(<?=$loja->cnpj?>)</td>
							<td class="centro" width="80"><?=$cuponsLoja->totalCupons?></td>
							<td class="centro" width="70"><?=formatarDinheiro(($cuponsLoja->totalCupons/$totalCupons)*100, false)?> %</td>
							<td class="direita" width="80"><?=formatarDinheiro($cuponsLoja->totalConsumo, false)?></td>
							<td class="centro" width="70"><?=formatarDinheiro(($cuponsLoja->totalConsumo/$totalConsumo)*100, false)?> %</td>
							<td class="centro" width="80"><?=$partPorLoja?></td>
							<td class="centro" width="70"><?=formatarDinheiro(($partPorLoja/$totalPart)*100, false)?> %</td>
						</tr>

			<?			$x++;
					} ?>
						
<?				}else{ ?>
					
					<tr>
						<td colspan="5"><?=$translate->translate("msg_nenhum_menu_encontrado_evento")?></td>
					</tr>

			<?	} ?>

			
		</table>

<?	} ?>