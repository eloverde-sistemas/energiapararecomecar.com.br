	<h3 class="page-title">
		<?=$translate->translate('loja_correlacao')?> <small><?=$translate->translate('listagem')?></small>
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
                <?=$translate->translate('loja_correlacao')?>
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
					<button type="button" class="btn default" onclick="window.location='/adm/admin/relatorios/loja-correlacao/listar'"><?=$translate->translate('bt_limpar')?></button>
					<button type="submit" class="btn green"><i class="fa fa-check"></i> Filtrar</button>
				</div>
			
				<div class="clear"></div>
                
			</div>
		</div>          
	</div>
</form>

<?	if($_POST){ 

		$lojasPart		= executaSQL("SELECT l.id, l.nome_fantasia, l.cnpj FROM loja l, evento_loja el WHERE el.id_loja=l.id AND el.id_evento='".$_POST['campanha']."' ORDER BY nome_fantasia");
?>
    <div class="row text-center jumper-20">
        <a href="/adm/excel/admin/relatorios/correlacao-loja/<?=$_POST['campanha']?>" class="icon-btn" target="_blank">
            <i class="fa fa-file-excel-o"></i>
            <div> <?=$translate->translate('excel')?> </div>
        </a>
    </div>

    <div class="clear">&nbsp;</div>

    <div class="portlet box grey-cascade">
        <div class="portlet-title">
			<div class="caption"><?=$translate->translate('lojas')?></div>
			<div class="tools"></div>
        </div>
        <div class="portlet-body">

            <table class="table table-striped table-bordered table-hover" id="list">
                <thead>
                    <tr>
                        <th class="text-center"><?=$translate->translate("loja")?></th>
                        <th class="text-center"><?=$translate->translate("correlacoes")?></th>
                    </tr>
                </thead>
				
				<tbody>
					
<?					
					if(nLinhas($lojasPart)>0){
						while($loja = objetoPHP($lojasPart)){ 
?>
							<tr>
								<td><?=$loja->nome_fantasia?> (<?=$loja->cnpj?>)</td>
								<td>
								<?
									$cuponsAdd = 0;
									
									$partCorrelacionados = executaSQL("SELECT DISTINCT(c.id_participante) as idPart
																			FROM participante_cupom c, loja l
																			WHERE c.id_evento='".$_POST['campanha']."'
																			AND c.cnpj = l.cnpj 
																			AND REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '')
																			AND c.id_situacao IN (2,90)");
									if(nLinhas($partCorrelacionados)>0){ 
										$correlacao = "";
										$arrayExiste = array();
										while($partCorrelacionado = objetoPHP($partCorrelacionados)){ 
										
											$cuponsCorrelacionados = executaSQL("SELECT l.nome_fantasia, l.cnpj, REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '') as cnpjFormatado
																				FROM participante_cupom c, loja l
																				WHERE c.id_evento='".$_POST['campanha']."'
																				AND c.cnpj = l.cnpj 
																				AND REPLACE(REPLACE(REPLACE(c.cnpj, '.', ''), '/', ''), '-', '')<>REPLACE(REPLACE(REPLACE('".$loja->cnpj."', '.', ''), '/', ''), '-', '')
																				AND c.id_participante='".$partCorrelacionado->idPart."'
																				AND c.id_situacao IN (2,90)");
																			
											if(nLinhas($cuponsCorrelacionados)>0){ ?>
													
<?													while($cupomCorrelacionado = objetoPHP($cuponsCorrelacionados)){ 
														
														//var_dump($arrayExiste);

														if( !in_array($cupomCorrelacionado->cnpjFormatado, $arrayExiste) ){
															$arrayExiste[] = $cupomCorrelacionado->cnpjFormatado;
															//echo "<BR><strong>ACHO</strong>: ".$cupomCorrelacionado->cnpj;
															$correlacao .= $cupomCorrelacionado->nome_fantasia." (".$cupomCorrelacionado->cnpj.") <br />";
														}
													} ?>

<?													

											} ?>
										
<?										}

										echo $correlacao;
									} ?>
								</td>
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
			
		});
		
	</script>
<?	} ?>