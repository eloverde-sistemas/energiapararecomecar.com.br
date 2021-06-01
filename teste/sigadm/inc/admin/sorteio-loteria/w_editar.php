<?php 
	$id = intval($_GET['id']);
	
	if( $id > 0 ){
		
		$exe = executaSQLPadrao("sorteio_loteria", "id = '".$id."'");
		if(nLinhas($exe)>0){
			$reg = objetoPHP($exe);

			if( verificaEventoSorteio($reg->sorteio_data) ){
				header("Location: /adm/admin/sorteio-loteria/listar");
				die();
			}

		}else{
			header("Location: /adm/admin/sorteio-loteria/listar");
			die();
		}

	}	
	
?>
	<h3 class="page-title">
        <?=$translate->translate('sorteios_loteria')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="index.html">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/sorteio-loteria/listar"><?=$translate->translate('sorteios_loteria')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
            	<?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?>
            </li>
		</ul>
    </div>
    
    <div class="row">
        <div class="col-md-12">
        	
            <div class="portlet light bg-inverse">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-equalizer font-red-sunglo"></i>
                        <span class="caption-subject font-red-sunglo bold uppercase">
							<? 	
								$params=array();
								if($id > 0){
									$params[] = $translate->translate("ttl_editar");
								}else{
									$params[] = $translate->translate("tt_adicionar");
								}
								echo traducaoParams($translate->translate('_sorteio_loteria'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form id="form" action="/adm/admin/sorteio-loteria/editar/<?=$id?>" method="post" class="horizontal-form">
                    	
        				<input type="hidden" name="id" class="form-control" id="id" value="<?=$id?>">
                        
                        <div class="form-body">
							
							<div class="row">

			                    <div class="col-md-4">
			                        <div class="form-group">
				                        <label for="data"><?=$translate->translate("data")?> </label>
				                        <input type="text" name="data" class="form-control required data" id="data"  value="<?=converte_data($reg->sorteio_data)?>">
			                        </div>
			                    </div>

			                    <div class="col-md-4">
			                        <div class="form-group">
				                        <label for="numero1"><?=$translate->translate("numero")?> 1</label>
				                        <input type="text" name="numero1" class="form-control required numero" maxlength="5" id="numero1" value="<?=$reg->sorteio_1?>">
			                        </div>
			                    </div>

			                    <div class="col-md-4">
			                        <div class="form-group">
				                        <label for="numero2"><?=$translate->translate("numero")?> 2</label>
				                        <input type="text" name="numero2" class="form-control required numero" maxlength="5" id="numero2" value="<?=$reg->sorteio_2?>">
			                        </div>
			                    </div>
			                    
			                </div>

			                <div class="row">
								<div class="col-md-4">
			                        <div class="form-group">
				                        <label for="numero3"><?=$translate->translate("numero")?> 3</label>
				                        <input type="text" name="numero3" class="form-control required numero" maxlength="5" id="numero3" value="<?=$reg->sorteio_3?>">
			                        </div>
			                    </div>

			                    <div class="col-md-4">
			                        <div class="form-group">
				                        <label for="numero4"><?=$translate->translate("numero")?> 4</label>
				                        <input type="text" name="numero4" class="form-control required numero" maxlength="5" id="numero4" value="<?=$reg->sorteio_4?>">
			                        </div>
			                    </div>

			                    <div class="col-md-4">
			                        <div class="form-group">
				                        <label for="numero5"><?=$translate->translate("numero")?> 5</label>
				                        <input type="text" name="numero5" class="form-control required numero" maxlength="5" id="numero5" value="<?=$reg->sorteio_5?>">
			                        </div>
			                    </div>

			                </div>
					   
					   
						    <div class="form-actions left">
	                            <button type="button" class="btn red" onclick="window.location='/adm/admin/sorteio-loteria/listar'"> <?=$translate->translate('bt_cancelar')?></button>
	                            <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
	                        </div>
	                    
	                    </div>

                    </form>

                    <div class="clear"></div>
                </div>
            </div>
			            
		</div>
	</div>
	
	<script>
       $(function(){
				
			$("#form").validate();

			$('#data').datepicker({ endDate:'0d', autoclose: true});
			
		});		
	</script>

<?
	if($_POST){

		$id = intval($_POST['id']);
		
		$data 		= converte_data($_POST['data']);
		$numero1 	= $_POST['numero1'];
		$numero2 	= $_POST['numero2'];
		$numero3 	= $_POST['numero3'];
		$numero4 	= $_POST['numero4'];
		$numero5 	= $_POST['numero5'];
		

		if($_POST['data'] == '' || $_POST['numero1'] == '' || $_POST['numero2'] == '' ||  $_POST['numero3'] == '' ||  $_POST['numero4'] == '' ||  $_POST['numero5'] == ''){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
			header("Location: /adm/admin/sorteio-loteria/editar/".$id);
		}else{
			
			//VERIFICA SE JA EXISTE SORTEIO DA LOTERIA PARA A DATA SELECIONADA
			if( verificaSorteioLoteriaData($data) ){

				setarMensagem(array($translate->translate("msg_existe_sorteio_loteria_data")), "error"); 	
				header("Location: /adm/admin/sorteio-loteria/editar/".$id);	
				die();
				
			}

			$dados=array();
			$dados['sorteio_data'] 			= $data;				
			$dados['sorteio_1']				= $numero1;
			$dados['sorteio_2']				= $numero2;
			$dados['sorteio_3'] 			= $numero3;
			$dados['sorteio_4'] 			= $numero4;
			$dados['sorteio_5']				= $numero5;

			if($id>0){
				$exe = alterarDados('sorteio_loteria', $dados, 'id = "'.$id.'"');
			}else{
				$dados['id'] = $id = proximoId("sorteio_loteria");
				$exe = inserirDados('sorteio_loteria', $dados);
			}


			if($exe){
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success"); 	
				header("Location: /adm/admin/sorteio-loteria/listar/");
			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error"); 	
				header("Location: /adm/admin/sorteio-loteria/editar/".$id);
			}
			
		}
	}
?>