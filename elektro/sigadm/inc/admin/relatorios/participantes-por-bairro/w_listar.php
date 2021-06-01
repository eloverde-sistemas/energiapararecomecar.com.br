	<h3 class="page-title">
		<?=$translate->translate('participantes_por_bairro')?> <small><?=$translate->translate('listagem')?></small>
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
                <?=$translate->translate('participantes_por_bairro')?>
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

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="estado"><?=$translate->translate("estado")?></label>
							<select name="estado" id="estado" class="form-control required campoFiltro">
								<option value=""><?=$translate->translate("sel_estado")?></option>
							<?	$exeEstados = executaSQL("SELECT * FROM estado ORDER BY nome");
								while($uf = objetoPHP($exeEstados)){ ?>
									<option value="<?=$uf->id?>" <?=($_POST['estado']==$uf->id)?'selected':''?>><?=$uf->nome?></option>
							<?	} ?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="cidade"><?=$translate->translate("cidade")?></label>
							<select name="cidade" id="cidade" class="form-control required campoFiltro"></select>
						</div>
					</div>                            

				</div>
				
				<div class="form-actions clear left">
					<button type="button" class="btn default" onclick="window.location='/adm/admin/relatorios/participantes-por-bairro/listar'"><?=$translate->translate('bt_limpar')?></button>
					<button type="submit" class="btn green"><i class="fa fa-check"></i> Filtrar</button>
				</div>
			
				<div class="clear"></div>
                
			</div>
		</div>          
	</div>
</form>

    <div class="row text-center jumper-20">
        <a href="/adm/pdf/admin/relatorios/participantes-por-bairro/<?=($_POST['campanha']>0)?$_POST['campanha']:0?>/<?=($_POST['estado']>0)?$_POST['estado']:0?>/<?=($_POST['cidade']>0)?$_POST['cidade']:0?>" class="icon-btn" target="_blank">
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
		
<?
			$sqlTotalMasculino = "SELECT COUNT(*) AS total FROM participante p WHERE p.sexo='1' ";
	
			$sqlTotalFeminino  = "SELECT COUNT(*) AS total FROM participante p WHERE p.sexo='2' ";
		
			if($_POST['estado']>0){
				$sqlTotalMasculino .= " AND EXISTS (SELECT 1 FROM municipio m WHERE p.id_cidade=m.id AND id_estado='".$_POST['estado']."')";	
				$sqlTotalFeminino  .= " AND EXISTS (SELECT 1 FROM municipio m WHERE p.id_cidade=m.id AND id_estado='".$_POST['estado']."')";	
			}
		
			if($_POST['cidade']>0){
				$sql .= " AND p.id_cidade='".$_POST['cidade']."'";
				$sqlTotalGeral .= " AND p.id_cidade='".$_POST['cidade']."'";
			}
			
			if($_POST['campanha']>0){
		
				$sqlTotalMasculino .= " AND EXISTS ( SELECT 1 FROM evento_participante ep WHERE ep.id_evento='".$_POST['campanha']."' AND ep.id_participante=p.id ) ";
				
				$sqlTotalFeminino  .= " AND EXISTS ( SELECT 1 FROM evento_participante ep WHERE ep.id_evento='".$_POST['campanha']."' AND ep.id_participante=p.id ) ";
		
			}
		
			$totalMasculino = objetoPHP(executaSQL($sqlTotalMasculino))->total;
			$totalFeminino = objetoPHP(executaSQL($sqlTotalFeminino))->total;
			
			$totalGeral = ($totalMasculino + $totalFeminino);
?>		
            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>
                    <tr>
						<th class="text-center" colspan="2">Masculino</th>
						<th class="text-center" colspan="2">Feminino</th>
						<th class="text-center">Total Geral</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center" id="totalMasculino"><?=$totalMasculino?></td>
						<td class="text-center" id="percMasculino"><?=number_format($totalMasculino/$totalGeral*100, 2)?>%</td>
						<td class="text-center" id="totalFeminino"><?=$totalFeminino?></td>
						<td class="text-center" id="percFeminino"><?=number_format($totalFeminino/$totalGeral*100, 2)?>%</td>
						<td class="text-center" id="totalGeral"><?=$totalGeral?></td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
	
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
			<div class="caption"><?=$translate->translate('participantes')?></div>
			<div class="tools"></div>
        </div>
        <div class="portlet-body">

<?
			$sql 			= "SELECT DISTINCT(bairro) as bairro, id_cidade, COUNT(*) as total FROM participante p, evento_participante ep WHERE p.id=ep.id_participante ";
			$sqlTotalGeral	= "SELECT COUNT(*) AS total FROM evento_participante ep, participante p WHERE p.id=ep.id_participante ";

			if($_POST['campanha']>0){
				$sql .= " AND ep.id_evento='".$_POST['campanha']."' ";	
				$sqlTotalGeral .= " AND ep.id_evento='".$_POST['campanha']."' ";	
			}

			if($_POST['cidade']>0){
				$sql .= " AND p.id_cidade='".$_POST['cidade']."'";
				$sqlTotalGeral .= " AND p.id_cidade='".$_POST['cidade']."'";
			}

			if($_POST['estado']>0){
				$sql .= " AND EXISTS (SELECT 1 FROM municipio m WHERE p.id_cidade=m.id AND id_estado='".$_POST['estado']."')";	
				$sqlTotalGeral .= " AND EXISTS (SELECT 1 FROM municipio m WHERE p.id_cidade=m.id AND id_estado='".$_POST['estado']."')";	
			}

			$sql 			.= " GROUP BY p.id_cidade, p.bairro ORDER BY TOTAL DESC, p.id_cidade, p.bairro";
			
			//echo "<br>".$sqlTotalGeral;
			
			$totalGeral = objetoPHP(executaSQL($sqlTotalGeral))->total;
			
?>
			
            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>
                    <tr>
                        <th><?=$translate->translate("cidade")?></th>
						<th><?=$translate->translate("bairro")?></th>
                        <th><?=$translate->translate("total")?></th>
                        <th><?=$translate->translate("feminino")?></th>
                        <th><?=$translate->translate("masculino")?></th>
                        <th>Porcentagem do Bairro</th>
                    </tr>
                </thead>
				
				<tbody>
					
<?					
					$exe = executaSQL($sql);
					if(nLinhas($exe)>0){
						while($reg = objetoPHP($exe)){ 
						
							$sqlTotal		= "SELECT COUNT(*) AS total FROM evento_participante ep WHERE 1=1 ";
							$sqlTotalMasc	= "SELECT COUNT(*) AS total FROM evento_participante ep WHERE 1=1 ";
							$sqlTotalFem	= "SELECT COUNT(*) AS total FROM evento_participante ep WHERE 1=1 ";
		
							if($_POST['campanha']>0){
								$sqlTotal 		.= " AND ep.id_evento='".$_POST['campanha']."' ";
								$sqlTotalMasc 	.= " AND ep.id_evento='".$_POST['campanha']."' ";
								$sqlTotalFem 	.= " AND ep.id_evento='".$_POST['campanha']."' ";
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
								<td><?=getMunicipioNomeById($reg->id_cidade)?></td>
								<td><?=$reg->bairro?></td>
								<td><?=$totalParticipantes?></td>
								<td><?=$totalFem?></td>
								<td><?=$totalMasc?></td>
								<td><?=number_format(($totalParticipantes/$totalGeral)*100, 2)?>%</td>
							</tr>

				<?		}
					}else{ ?>
						
						<tr>
							<td colspan="5"><?=$translate->translate("msg_nenhum_menu_encontrado_evento")?></td>
						</tr>

				<?	} ?>

				</tbody>
				
            </table>
			
			
        </div>
    </div>
    <script>
	
		jQuery(document).ready(function() {

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