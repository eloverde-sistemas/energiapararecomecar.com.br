	<h3 class="page-title">
		<?=$translate->translate('participantes_por_loja')?> <small><?=$translate->translate('listagem')?></small>
	</h3>
	<div class="page-bar">
		<ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('relatorios')?>
                <i class="fa fa-angle-right"></i>
            </li>

            <li>
                <?=$translate->translate('participantes_por_loja')?>
            </li>
        </ul>
    </div>
	
<form id="filtro" method="post">
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption">
                <?=$translate->translate("filtro_avancado")?>
            </div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
        
            <div class="form-body">            
               
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
							<label for="campanha"><?=$translate->translate("evento")?></label>
							<select name="campanha" id="campanha" class="form-control required campoFiltro">
								<option value="" ><?=$translate->translate("sel_evento")?></option>
							<?	$exeEventos = executaSQL("SELECT * FROM evento ORDER BY titulo");
								while($evento = objetoPHP($exeEventos)){ ?>
									<option value="<?=$evento->id?>" <?=($_POST['campanha']==$evento->id)?'selected':''?>><?=$evento->titulo?></option>
							<?	} ?>
							</select>
                        </div>
                    </div>
          		</div>

				<div class="form-actions clear left">
					<button type="button" class="btn default" onclick="window.location='/adm/admin/relatorios/participantes-por-loja/listar'"><?=$translate->translate('bt_limpar')?></button>
					<button type="submit" class="btn green"><i class="fa fa-check"></i> Filtrar</button>
				</div>
			
				<div class="clear"></div>
                
			</div>
		</div>          
	</div>
</form>


<?	if($_POST){ 

		$lojasPart		= executaSQL("SELECT l.id, l.nome_fantasia, l.cnpj FROM loja l, evento_loja el WHERE el.id_loja=l.id AND el.id_evento='".$_POST['campanha']."' ORDER BY nome_fantasia");

		$totalPart		= nLinhas(executaSQL("SELECT DISTINCT(id_participante) FROM participante_cupom WHERE id_evento='".$_POST['campanha']."' AND id_situacao IN (2,90)"));
		
		$totais			= objetoPHP(executaSQL("SELECT COUNT(*) as totalCupons, SUM(valor) as totalConsumo FROM participante_cupom WHERE id_evento='".$_POST['campanha']."' AND id_situacao IN (2,90)"));
		
		$totalCupons	= $totais->totalCupons;
		$totalConsumo	= $totais->totalConsumo;

?>

    <div class="row text-center jumper-20">
        <a href="/adm/pdf/admin/relatorios/participantes-por-loja/<?=$_POST['campanha']?>" class="icon-btn" target="_blank">
            <i class="fa fa-print"></i>
            <div> <?=$translate->translate('imprimir')?> </div>
        </a>
    </div>

    <div class="clear">&nbsp;</div>

    <div class="portlet box grey-cascade">
        <div class="portlet-title">
			<div class="caption"><?=$translate->translate('totais')?></div>
			<div class="tools"></div>
        </div>
        <div class="portlet-body">
		
            <table class="table table-striped table-bordered table-hover" id="listTotal">
                <thead>
                    <tr>
						<th class="text-center">Qtde Lojas</th>
						<th class="text-center">Qtde Participantes</th>
						<th class="text-center">Qtde Cupons</th>
						<th class="text-center">Total Consumo</th>
						<th class="text-center">Ticket Médio</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center"><?=nLinhas($lojasPart)?></td>
						<td class="text-center"><?=$totalPart?></td>
						<td class="text-center"><?=$totalCupons?></td>
						<td class="text-center"><?=formatarDinheiro($totalConsumo)?></td>
						<td class="text-center"><?=formatarDinheiro($totalConsumo/$totalCupons)?></td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
	
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
			<div class="caption"><?=$translate->translate('lojas')?></div>
			<div class="tools"></div>
        </div>
        <div class="portlet-body">

            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>
                    <tr>
                        <th class="text-center"  rowspan="2"><?=$translate->translate("loja")?></th>
                        <th class="text-center"  colspan="2"><?=$translate->translate("cupons")?></th>
                        <th class="text-center"  colspan="2"><?=$translate->translate("consumo")?></th>
                        <th class="text-center"  colspan="2"><?=$translate->translate("participantes")?></th>
                    </tr>
                    <tr>
                        <th class="text-center" ><?=$translate->translate("total")?></th>
                        <th class="text-center" ><?=$translate->translate("porcentagem")?></th>
                        <th class="text-center" ><?=$translate->translate("total")?></th>
                        <th class="text-center" ><?=$translate->translate("porcentagem")?></th>
                        <th class="text-center" ><?=$translate->translate("total")?></th>
                        <th class="text-center" ><?=$translate->translate("porcentagem")?></th>
                    </tr>
                </thead>
				
				<tbody>
					
<?					
					if(nLinhas($lojasPart)>0){
						while($loja = objetoPHP($lojasPart)){ 
						
							$cuponsLoja = objetoPHP(executaSQL("SELECT COUNT(*) as totalCupons, SUM(c.valor) as totalConsumo FROM participante_cupom c, loja l WHERE c.id_evento='".$_POST['campanha']."' AND REPLACE(REPLACE(REPLACE(c.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '') AND c.cnpj =l.cnpj AND c.id_situacao IN (2,90)"));
							
							$partPorLoja = nLinhas(executaSQL("SELECT DISTINCT(id_participante) FROM participante_cupom c, loja l WHERE c.id_evento='".$_POST['campanha']."' AND REPLACE(REPLACE(REPLACE(c.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '') AND c.cnpj =l.cnpj AND c.id_situacao IN (2,90)"));
?>
						
							<tr>
								<td><?=($partPorLoja>0)?'<a href="/adm/admin/relatorios/dados-por-loja/'.$_POST['campanha'].'/'.$loja->id.'" target="_blank">':''?><?=$loja->nome_fantasia?><?=($partPorLoja>0)?'</a>':''?> (<?=$loja->cnpj?>)</td>
								<td class="text-center" ><?=$cuponsLoja->totalCupons?></td>
								<td class="text-center" ><?=formatarDinheiro(($cuponsLoja->totalCupons/$totalCupons)*100, false)?> %</td>
								<td class="text-right" ><span class="hide"><?=formataNumeroComZeros(number_format($cuponsLoja->totalConsumo,0),10)?></span> <?=formatarDinheiro($cuponsLoja->totalConsumo, false)?></td>
								<td class="text-center" ><?=formatarDinheiro(($cuponsLoja->totalConsumo/$totalConsumo)*100, false)?> %</td>
								<td class="text-center" ><?=$partPorLoja?></td>
								<td class="text-center" ><?=formatarDinheiro(($partPorLoja/$totalPart)*100, false)?> %</td>
							</tr>

				<?		}
					}else{ ?>
						
						<tr>
							<td colspan="5"><?=$translate->translate("msg_nenhum_menu_encontrado_evento")?></td>
						</tr>

				<?	} ?>

				</tbody>
				
            </table>
			
			<span class="hide" id="totalCalcMasc"><?=$totalGeralMasc?></span>
			<span class="hide" id="perCalcMasc"><?=number_format(($totalGeralMasc/$totalGeral)*100, 2)?>%</span>
			
			<span class="hide" id="totalCalcFem"><?=$totalGeralFem?></span>
			<span class="hide" id="perCalcFem"><?=number_format(($totalGeralFem/$totalGeral)*100, 2)?>%</span>
			
			<span class="hide" id="totalCalcGeral"><?=$totalGeral?></span>

			
        </div>
    </div>
    <script>
	
		jQuery(document).ready(function() {
			
			var table = $('#list');
            var oTable = table.dataTable({
                "dom": "<'row hide'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row hide'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // datatable layout without  horizobtal scroll
                "scrollY": "70%",
                "columns": [
                                null,
                                null,   
                                null,   
                                null,   
                                null,   
                                null,   
                                null
                            ],
                "order": [
                    [0, 'asc']
                ],
                "pageLength": -1 // set the initial value            
            });
			
			$("#totalMasculino").html($("#totalCalcMasc").html());
			$("#percMasculino").html($("#perCalcMasc").html());
			
			$("#totalFeminino").html($("#totalCalcFem").html());
			$("#percFeminino").html($("#perCalcFem").html());
			
			$("#totalGeral").html($("#totalCalcGeral").html());
			
			if( $("#estado").val()>0 ){
				carregaCidadesPeloEstado( "#cidade", $("#estado").val(), <?=($_POST['cidade'])?"'".$_POST['cidade']."'":''?>);
			}
				
			//CARREGAS AS CIDADES
			$("#estado").change(function(){
				if( $(this).val()>0 ){
					carregaCidadesPeloEstado( "#cidade", $(this).val(), "");
				}
			});
			
		});
		
	</script>
<?	} ?>