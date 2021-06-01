<?

	$id = intval($_GET['id']);
	
	if($id>0){
		$exe = executaSQL("SELECT * FROM evento WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			$evento=objetoPHP($exe);
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	}
	
	$idPart = intval($_GET['id2']);
	
	if($idPart>0){
		$exe = executaSQL("SELECT * FROM participante WHERE id = '".$idPart."'");
		if(nLinhas($exe)>0){
			$participante=objetoPHP($exe);
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	
	}
?>


	<h3 class="page-title">
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate('cupons_por_participante')?></small>
    </h3>
    
    <div class="page-bar">
    	<ul class="page-breadcrumb">
        	<li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/eventos/listar"><?=$translate->translate('eventos')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
				<?=$translate->translate('cupons_por_participante')?>
            </li>
        </ul>
    </div>
    

	<div class="portlet light bg-inverse">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-equalizer font-red-sunglo"></i>
				<span class="caption-subject font-red-sunglo bold uppercase">
				<?=$evento->titulo?>
				</span>
			</div>
		</div>
	</div>
	
	
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption uppercase"><?=$participante->nome?> - <?=$participante->cpf?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
        
				
<?
		$cupons = executaSQLPadrao('participante_cupom', "id_participante = '".$participante->id."' AND id_evento='".$evento->id."' ORDER BY id");
		$nCupons = nLinhas($cupons);
		if( $nCupons>0 ){ ?>

	            <table id="list" class="table table-striped table-bordered table-hover" >
					<tr>
						<th>Cupom(ns)</th>
						<th>Situação</th>
						<th><?=($evento->id_tipo_sorteio==1)?'Elemento(s) Sorteável(is)' :'Cupom Retirada'?></th>
					</tr>

<?			while($cupom = objetoPHP($cupons)){ ?>
				

			<?	if($evento->id_tipo_campanha==4){ //CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo 

					$classeSit = '';
					switch($cupom->id_situacao){
						case 2: $classeSit = 'success'; break;
						case 90: $classeSit = 'warning'; break;
						case 91: $classeSit = 'danger'; break;
						case 93: $classeSit = 'danger'; break;
					}
			?>
					<tr class="<?=$classeSit?>">
						<td><?=$cupom->cupom?></td>
						<td><?=getCupomSituacao($cupom->id_situacao)?></td>
						<td><?=getElementosByCampanhaTipoCupom($evento->id_tipo_campanha, $cupom->id);?></td>
					</tr>
<?
				}elseif($evento->id_tipo_campanha==3){ //CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo 
			
					$classeSit = '';
					switch($cupom->id_situacao){
						case 2: $classeSit = 'success'; break;
						case 90: $classeSit = 'warning'; break;
						case 91: $classeSit = 'danger'; break;
						case 93: $classeSit = 'danger'; break;
					}
						
						
			?>
					<tr class="<?=$classeSit?>">
						<td>
							<?=$translate->translate('cnpj').": ".$cupom->cnpj;?>
							<?="<br>".$translate->translate('loja').": ".consultaLojaByCampanhaCNPJ($evento->id, $cupom->cnpj, "l.nome_fantasia")->nome_fantasia?>
							<?="<br>".$translate->translate('data').": ".converte_data($cupom->data);?>
							<?="<br>".$translate->translate('coo').": ".$cupom->coo;?>
							<?
								if( $evento->requer_num_caixa==1 ){
									echo " - ".$translate->translate('caixa').": ".$cupom->caixa;
								}
							?>
							<?="<br>".$translate->translate('valor').": ".formatarDinheiro($cupom->valor);?>							
						</td>
						<td>
						<?
							echo getCupomSituacao($cupom->id_situacao);
							
							if($evento->id_controle_valor==2 && $evento->cupom_acumulativo==1 && in_array($cupom->id_situacao, array(1) ) ){
								echo " / Múltiplo de R$".formatarDinheiro($evento->valor, false);
							}else{
								echo " / Acumulativo até R$".formatarDinheiro($evento->valor, false);
							}

						?>
						</td>
						<td>
						<?	
							//if( in_array($cupom->id_situacao, array(2,90)) ){
								echo getElementosByCampanhaTipoCupom($evento->id_tipo_campanha, $cupom->id);
							//}
						?>
						</td>
					</tr>
						
<? 				}
			} ?>
			</table>
<?		} ?>

		</div>
	</div>
	
    <script>
		$(document).ready(function() {
			
			$('#list').dataTable();
		});
	</script>