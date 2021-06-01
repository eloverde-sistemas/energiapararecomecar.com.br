	<h3 class="page-title">
        <?=$translate->translate('atualizacao')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="index.html">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/atualizacao/listar"><?=$translate->translate('atualizacao')?></a>
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
								echo traducaoParams($translate->translate('_atualizacao'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form id="form" action="/adm/admin/atualizacao/editar" method="post" enctype="multipart/form-data" class="horizontal-form">
                        
                        <div class="form-body">
                            
                            <div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="evento"><?=$translate->translate("evento")?></label>
										<select id="evento" class="form-control required" name="evento">
											<option value=""><?=$translate->translate("sel_evento")?></option>
										<?
											$exeEv = executaSQL("SELECT * FROM evento ORDER BY dt_inicio DESC");
											if(nLinhas($exeEv)>0){											
												while($evento = objetoPHP($exeEv)){ ?>
														
													<option value="<?=$evento->id?>" <?=($noticia->id_evento == $evento->id) ? 'selected' : '' ?> > <?=$evento->titulo?></option>	
														
										<?		}
											}
										?>
										 </select>
									</div>
								</div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?=$translate->translate("tipo")?></label>
                                        <div class="radio-list">
                                            <label class="radio-inline" for="tipo1">
                                            	<input type="radio"  name="tipo" id="tipo1" class="required" value="1"  <?=($noticia->tipo == 1)?'checked="checked"':''?>> <?=$translate->translate("insercao")?>
                                            </label>
                                            <label class="radio-inline" for="tipo2">
                                            	<input type="radio"  name="tipo" id="tipo2" class="required" value="2"  <?=($noticia->tipo == 2)?'checked="checked"':''?>> <?=$translate->translate("substituicao")?>
                                            </label>
                                            <label class="radio-inline" for="tipo3">
                                            	<input type="radio"  name="tipo" id="tipo3" class="required" value="3"  <?=($noticia->tipo == 3)?'checked="checked"':''?>> <?=$translate->translate("exclusao")?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="file_dir"><?=$translate->translate("arquivo")?></label>
                                        <input type="file" name="file_dir" id="file_dir" class="required">
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        <div class="form-actions left">
                            <button type="button" class="btn red" onclick="window.location='/adm/admin/atualizacao/listar'"> <?=$translate->translate('bt_cancelar')?></button>
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                        </div>
                    </form>
                    <!-- END FORM-->
                    <div class="clear"></div>
                </div>
            </div>
			            
		</div>
	</div>
	<script>
		$(function(){
			$("#form").validate();
		});
	</script>

<?php 
	if($_POST){
			
		if($_POST['evento'] == '' || $_POST['tipo'] == ''){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
			header("Location: /adm/admin/atualizacao/editar/".$id);
			die();
		}else{		
			
			$dados = array();
			$dados['id_evento'] 	= intval($_POST['evento']);
			$dados['id_tipo'] 		= intval($_POST['tipo']);
			$dados['dt_hr']			= date('YmdHis');

			$dados['id_usuario'] 	= $_SESSION["usuarioId"];

			$id = $dados['id'] = proximoId('atualizacao');				
			$exe = inserirDados('atualizacao', $dados);
			
			if($exe){
				
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
			
				if( $_FILES['file_dir']['tmp_name'] != '' ){
					
					$fileName = explode(".",$_FILES['file_dir']['name']);

					if( in_array( end($fileName) , array("txt") ) ){
						
						$dadosAlt['arquivo'] = criaDiretorios( array("uploads", "atualizacao") ).$id.'_'.date('YmdHis').".".end($fileName);
						
						$upload = move_uploaded_file( $_FILES['file_dir']['tmp_name'], '../'.$dadosAlt['arquivo']);
						
						if( $upload ){
							alterarDados("atualizacao", $dadosAlt, "id='".$id."'");

							//abre o arquivo somente para leitura (fopen "r")
						    $lendo = @fopen('../'.$dadosAlt['arquivo'], "r");
						    if (!$lendo){
						        echo "<br /><span class='obsNaoBaixa'>Erro ao ler o Arquivo.</span>";
						    }else{ 
						        
						        
						        //SE INCLUSÃO
						        if( $dados['id_tipo']==1 ){

									echo "<br /><span class='obsNaoBaixa'>INCLUSÃO</span>";
						        	$x = 0;
							        while(!feof($lendo)){
							            $x++;

							            $linha = fgets($lendo);
							            $dados = explode(";", $linha);

							            if($dados[0]>0 && $dados[1]>0){

								            $unidadeFor     = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[0]), 12);
								            $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[1]), 11);

								            if( strlen($unidadeFor)==12 && $unidadeFor!='000000000000' && strlen($cpfFor)==11 ){

								            	$regs = executaSQL("SELECT * FROM base_cliente WHERE unidade = '".$unidadeFor."'");
								          		if( nLinhas($regs)>0 ){
								          			$reg = objetoPHP($regs);
								          			echo "<br>CC ".$unidadeFor." já existe na base com o CPF ".$reg->cpf." Atualizado com o CPF ".$cpfFor." e situação Ativa";

								          			alterarDados("base_cliente", array("cpf"=>$cpfFor, "id_situacao"=>1, "dt_hr_substituicao"=>date('YmdHis')), "unidade='".$unidadeFor."'");
								          		}else{
								          			inserirDados("base_cliente", array("cpf"=>$cpfFor, "unidade"=>$unidadeFor, "id_situacao"=>1));
								          		}
								          	}else{
								            	echo "<br>Não inserido: CC ".$unidadeFor." - CPF ".$cpfFor;	
								            }

							            }

							        }

						        //SE SUBSTITUIÇÃO
						        }elseif( $dados['id_tipo']==2 ){

						        	echo "<br /><span class='obsNaoBaixa'>SUBSTITUIÇÃO</span>";

						        	$x = 0;
							        while(!feof($lendo)){
							            $x++;

							            $linha = fgets($lendo);
							            $dados = explode(";", $linha);

							            if($dados[0]>0 && $dados[1]>0){

								            $unidadeFor     = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[0]), 12);
								            $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[1]), 11);

								            if( strlen($unidadeFor)==12 && $unidadeFor!='000000000000' && strlen($cpfFor)==11 ){
							            	
								            	$regs = executaSQL("SELECT * FROM base_cliente WHERE unidade = '".$unidadeFor."'");
								          		if( nLinhas($regs)>0 ){
								          			$reg = objetoPHP($regs);
								          			
								          			if( $cpfFor == $reg->cpf){
														echo "<br>Já era o CPF ".$cpfFor." para a CC".$unidadeFor.". Apenas atualizada a situação para Ativa";
														alterarDados("base_cliente", array("id_situacao"=>1, "dt_hr_substituicao"=>date('YmdHis')), "unidade='".$unidadeFor."'");
								          			}else{	
									          			alterarDados("base_cliente", array("cpf"=>$cpfFor, "dt_hr_substituicao"=>date('YmdHis'), "id_situacao"=>1), "unidade='".$unidadeFor."'");

									          			echo "<br>CPF Substituído de ".$reg->cpf." para ".$cpfFor." na CC ".$unidadeFor;
									          		}
								          		}else{
								          			echo "<br>CC ".$unidadeFor." não existia, foi incluída com o CPF ".$cpfFor;
								          			inserirDados("base_cliente", array("cpf"=>$cpfFor, "unidade"=>$unidadeFor, "id_situacao"=>1, "dt_hr_substituicao"=>date('YmdHis')));
								          		}

								          	}else{
								            	echo "<br>Não substituído: CC ".$unidadeFor." - CPF ".$cpfFor;	
								            }
							            }

							        }

						        //SE EXCLUSÃO
						        }elseif( $dados['id_tipo']==3 ){

						        	echo "<br /><span class='obsNaoBaixa'>EXCLUSÃO</span>";

						        	$x = 0;
							        while(!feof($lendo)){
							            $x++;

							            $linha = fgets($lendo);
							            $dados = explode(";", $linha);

							            if($dados[0]>0 && $dados[1]>0){

								            $unidadeFor     = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[0]), 12);
								            $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[1]), 11);

								            if( strlen($unidadeFor)==12 && $unidadeFor!='000000000000' && strlen($cpfFor)==11 ){

								            	$regs = executaSQL("SELECT * FROM base_cliente WHERE unidade = '".$unidadeFor."'");
								          		if( nLinhas($regs)>0 ){
								          			$reg = objetoPHP($regs);
								          			
								          			alterarDados("base_cliente", array("id_situacao"=>2, "dt_hr_exclusao"=>date('YmdHis')), "unidade='".$unidadeFor."'");

								          			echo "<br>CC ".$unidadeFor." Inativada";
								          		}else{
													echo "<br>Não existia CC ".$unidadeFor." Foi inserida como Inativa.";
								          			inserirDados("base_cliente", array("cpf"=>$cpfFor, "unidade"=>$unidadeFor, "id_situacao"=>2, "dt_hr_exclusao"=>date('YmdHis')));
								          		}

								            }else{
								            	echo "<br>Não excluído: CC ".$unidadeFor." - CPF ".$cpfFor;	
								            }
								        }
							        }

						        }


						    }
						}		
					}else{
						setarMensagem(array($translate->translate("msg_formato_arquivo_invalido")), "error");
						header("Location: /adm/admin/atualizacao/editar");
						exit();
					}
				}
				
				/*header("Location: /adm/admin/atualizacao/listar");
				exit();*/
			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error");
				header("Location: /adm/admin/atualizacao/editar");
			}

		}

	}
	
?>