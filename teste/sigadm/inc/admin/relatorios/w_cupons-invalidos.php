	<h3 class="page-title">
		<?=$translate->translate('cupons_invalidos')?> <small><?=$translate->translate('listagem')?></small>
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
                <?=$translate->translate('cupons_invalidos')?>
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
						<button type="button" class="btn default" onclick="window.location='/adm/admin/relatorios/codigos-invalidos'"><?=$translate->translate('bt_limpar')?></button>
						<button type="submit" class="btn green"><i class="fa fa-check"></i> Filtrar</button>
					</div>
				
					<div class="clear"></div>
	                
				</div>
			</div>          
		</div>
	</form>

	<div class="clear">&nbsp;</div>


    

    <div class="portlet box grey-cascade">
        <div class="portlet-title">
			<div class="caption"><?=$translate->translate('participantes')?></div>
			<div class="tools"></div>
        </div>
        <div class="portlet-body">
		
<?
	if($_POST['campanha']>0){


		$sql = "SELECT * FROM participante p, evento_participante e
				WHERE e.id_participante=p.id 
				AND e.id_evento='".$_POST['campanha']."'
				AND EXISTS (
								SELECT 1 FROM participante_cupom c 
								WHERE c.id_situacao='3'
								AND p.id=c.id_participante)
					ORDER BY id DESC";
		

		$participantes = executaSQL($sql);

		if(nLinhas($participantes)>0){ 

				$total = nLinhas($participantes);
?>
	            <table class="table table-striped table-bordered table-hover" id="list">
	                <thead>
	                    <tr>
							<th><?=$translate->translate('seq')?></th>
							<th><?=$translate->translate('participante')?></th>
							<th><?=$translate->translate('cpf')?></th>
							<th><?=$translate->translate('contatos')?></th>
							<th><?=$translate->translate('cupons_invalidos')?></th>
						</tr>
					</thead>
					<tbody>
			<?	while( $participante = objetoPHP($participantes) ){ 
					$cuponsParcipante = array();
			?>
						<tr>
							<td><?=$total--?></td>
							<td><?=$participante->nome?></td>
							<td nowrap="nowrap"><?=$participante->cpf?></td>
							<td nowrap="nowrap">
								<?=$participante->email?>
								<br />
								<?=$participante->celular?>
							</td>
							<td>
							<?
								$cupons = executaSQL("SELECT cupom FROM participante_cupom c 
																		WHERE c.id_situacao='3'
																		AND c.id_participante='".$participante->id."'");
								while( $cupom = objetoPHP($cupons) ){
									$cuponsParcipante [] = $cupom->cupom;
								}

								echo implode(', ', $cuponsParcipante);
							?>
							</td>
						</tr>
			<?	} ?>
					</tbody>
				</table>

<?			
		}else{ ?>
            <table class="table table-striped table-bordered table-hover" id="list">
                <tr>
					<th>Nenhum Cupom Inválido</th>
				</tr>
			</table>
<? 		} 

	}else{
		echo "<h2>".$translate->translate("sel_evento")."</h2>";
	} ?>		
			
		</div>
	</div>
	