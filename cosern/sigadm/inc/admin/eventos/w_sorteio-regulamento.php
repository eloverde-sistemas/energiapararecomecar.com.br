<?
    if($_POST){
        
		$exe = executaSQL("SELECT * FROM evento WHERE id = '".$_POST['id']."'");
		if(nLinhas($exe)>0){
			$reg=objetoPHP($exe);


			excluirDados("sorteio_regulamento", "id_evento='".$reg->id."'");
			
			$numeros_sorteaveis = objetoPHP(executaSQL("SELECT * FROM evento_numeros_sorteaveis WHERE id = '".$reg->id_numeros_sorteaveis."'"));

			$qtdeSerie = $numeros_sorteaveis->qtde_serie;
			
			$ordemNumeros = 1;
			
			if($qtdeSerie>0){ 
				for($serie = 1; $serie<=$qtdeSerie; $serie++ ){ 
					inserirDados("sorteio_regulamento", array('sorteio'=>$_POST['sorteio'.$ordemNumeros], 'posicao'=>$_POST['posicao'.$ordemNumeros], 'hierarquia'=>$ordemNumeros, 'id_evento'=>$reg->id));
					$ordemNumeros++;
				}
			}
			
			for($sorteio = 1; $sorteio<=5; $sorteio++ ){ 
				inserirDados("sorteio_regulamento", array('sorteio'=>$_POST['sorteio'.$ordemNumeros], 'posicao'=>$_POST['posicao'.$ordemNumeros], 'hierarquia'=>$ordemNumeros, 'id_evento'=>$reg->id));
				$ordemNumeros++;
			}

            setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");

			header("Location: /adm/admin/eventos/listar");
			die();
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}

    }
?>

<?
	$id = intval($_GET['id']);
	
	if($id>0){
		$exe = executaSQL("SELECT * FROM evento WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			$reg=objetoPHP($exe);
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	}
?>	

	<h3 class="page-title">
    	<?=$translate->translate('eventos')?> <small>Regulamento do Sorteio</small>
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
				Regulamento do Sorteio
            </li>
        </ul>
    </div>
    
    <form id="form" action="/adm/admin/eventos/sorteio-regulamento" method="post">
        
        <input type="hidden" name="id" value="<?=$id?>">
        
		<div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">
                    <?=$reg->titulo?> > Composição do Elemento Sorteável
                    </span>
                </div>
            </div>
            
            <div class="portlet-body form">
            <?
			
				$numeros_sorteaveis = objetoPHP(executaSQL("SELECT * FROM evento_numeros_sorteaveis WHERE id = '".$reg->id_numeros_sorteaveis."'"));

				$qtdeSerie = $numeros_sorteaveis->qtde_serie;
				
				$ordemNumeros = 1;
				
				if($qtdeSerie>0){ ?>
				
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<strong>SÉRIE</strong>
							</div>
							<div class="tools"></div>
						</div>
						<div class="portlet-body">
					
						<?				
							for($serie = 1; $serie<=$qtdeSerie; $serie++ ){ 
							
								$regulamento = objetoPHP(executaSQL("SELECT * FROM sorteio_regulamento WHERE id_evento='".$reg->id."' AND hierarquia='".$ordemNumeros."' "));
						?>
						
								<div class="row">
									
									<div class="col-md-1">
										<div class="form-group">
											<label>&nbsp;</label>
											<strong>Sequência <?=$ordemNumeros?></strong>
										</div>
									</div>
									
									<div class="col-md-5">
										<div class="form-group">
											<label for="sorteio<?=$ordemNumeros?>"><?=$translate->translate("premio")?></label>
											<select name="sorteio<?=$ordemNumeros?>" id="sorteio<?=$ordemNumeros?>" class="form-control required">
												<option value=""></option>
											<?	for($i=1; $i<=5; $i++){ ?>
													<option value="<?=$i?>" <?=($i==$regulamento->sorteio)?'selected' :''?> ><?=$i?></option>
											<?	} ?>
											</select>
										</div>
									</div>
				
									<div class="col-md-5">
										<div class="form-group">
											<label for="posicao"><?=$translate->translate("posicao")?></label>
											<select name="posicao<?=$ordemNumeros?>" id="posicao<?=$ordemNumeros?>" class="form-control required">
												<option value=""></option>
											<?	for($i=1; $i<=5; $i++){ ?>
													<option value="<?=$i?>" <?=($i==$regulamento->posicao)?'selected' :''?> ><?=$i?></option>
											<?	} ?>
											</select>
										</div>
									</div>
								
								</div>        				
						
						<?		$ordemNumeros++;
							} ?>
						</div>
					</div>			
			<?	} ?>
				
				
				
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption">
							<strong>ELEMENTO</strong>
						</div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">
					<?
						for($sorteio = 1; $sorteio<=5; $sorteio++ ){ 
						
							$regulamento = objetoPHP(executaSQL("SELECT * FROM sorteio_regulamento WHERE id_evento='".$reg->id."' AND hierarquia='".$ordemNumeros."' "));
					?>
							<div class="row">
								
								<div class="col-md-1">
									<div class="form-group">
										<strong>Sequência <?=$ordemNumeros?></strong>
									</div>
								</div>
								
								<div class="col-md-5">
									<div class="form-group">
										<label for="sorteio<?=$ordemNumeros?>"><?=$translate->translate("premio")?></label>
										<select name="sorteio<?=$ordemNumeros?>" id="sorteio<?=$ordemNumeros?>" class="form-control required">
											<option value=""></option>
										<?	for($i=1; $i<=5; $i++){ ?>
												<option value="<?=$i?>" <?=($i==$regulamento->sorteio)?'selected' :''?> ><?=$i?></option>
										<?	} ?>
										</select>
									</div>
								</div>
			
								<div class="col-md-5">
									<div class="form-group">
										<label for="posicao"><?=$translate->translate("posicao")?></label>
										<select name="posicao<?=$ordemNumeros?>" id="posicao<?=$ordemNumeros?>" class="form-control required">
											<option value=""></option>
										<?	for($i=1; $i<=5; $i++){ ?>
												<option value="<?=$i?>" <?=($i==$regulamento->posicao)?'selected' :''?> ><?=$i?></option>
										<?	} ?>
										</select>
									</div>
								</div>
							
							</div>        				
					
					<?		$ordemNumeros++;
						} ?>
					</div>
				</div>

                <div class="form-actions left">
                	<button type="button" class="btn red" onclick="window.location='/adm/admin/eventos/listar'"><?=$translate->translate('bt_cancelar')?></button>
                	<button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                </div>
                
                <div class="clear"></div>
            
        	</div>
		</div>
	</form>

    <script>
		
		$(function(){		
			
			$("#form").validate();	

			
		});



	</script>