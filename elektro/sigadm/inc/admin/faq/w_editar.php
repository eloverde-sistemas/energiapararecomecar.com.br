<?php 
	if($_POST){

		$id = intval($_POST['id']);

		$idEvento 			= intval($_POST['evento']);
		$pergunta 			= trim($_POST['pergunta'][0]);
		$resposta 			= trim($_POST['resposta'][0]);
		$ativo				= intval($_POST['ativo'][0]);

		if($id>0){

			if( $pergunta == '' || $resposta == '' || $ativo==0 ){
				setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
				header("Location: /adm/admin/faq/editar/".$id);
			}else{

				$exeFAQ = executaSQL("SELECT * FROM perguntas_respostas WHERE id='".$id."'");
				if(nLinhas($exeFAQ)>0){
					$regFAQ = objetoPHP($exeFAQ);

					$dados = array();
					$dados['pergunta'] 			= $pergunta;
					$dados['resposta'] 			= $resposta;
					$dados['ativo']				= $ativo;
					
					$exe = alterarDados('perguntas_respostas', $dados, 'id = "'.$id.'"');
					
					if($exe){
						setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
						header("Location: /adm/admin/faq/listar/");

					}else{
						setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error"); 	
						header("Location: /adm/admin/faq/editar/".$id);
					}

				}else{
					setarMensagem(array($translate->translate("msg_sem_registro")), "error"); 	
					header("Location: /adm/admin/faq/listar");
				}
				
			}

		}else{

			if( $pergunta == '' || $resposta == '' || $idEvento == 0 || $ativo == 0 ){
				setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
				header("Location: /adm/admin/faq/editar");
			}else{

				$perguntas = $_POST['pergunta'];
				if(count($perguntas)>0){

					foreach($perguntas as $key=>$pergunta){

						$pergunta 			= trim($_POST['pergunta'][$key]);
						$resposta 			= trim($_POST['resposta'][$key]);
						$ativo				= intval($_POST['ativo'][$key]);

						if($pergunta!='' && $resposta!=''){
							$dados = array();
							$dados['id']				= proximoId("perguntas_respostas");
							$dados['id_evento']			= $idEvento;
							$dados['pergunta'] 			= $pergunta;
							$dados['resposta'] 			= $resposta;
							$dados['ativo']				= $ativo;
							$dados['ordem']				= getProximoNumeroOrdemFAQ($idEvento);
							inserirDados('perguntas_respostas', $dados);	
						}
						
					}

					setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
					header("Location: /adm/admin/faq/listar/");				

				}else{
					setarMensagem(array($translate->translate("msg_salvo_com_erro")), "error");
					header("Location: /adm/admin/faq/editar/".$id);				
				}
				
				
			}
		}
		
	}

	$id = intval($_GET['id']);
	
	if( $id > 0 ){
		$exe = executaSQLPadrao("perguntas_respostas", "id = '".$id."'");
		if(nLinhas($exe)>0){			
			$reg = objetoPHP($exe);
		}else{
			header("Location: /adm/admin/faq/listar");
			die();
		}
	}	
	
?>
	<h3 class="page-title">
        <?=$translate->translate('faq')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/faq/listar"><?=$translate->translate('faq')?></a>
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
								echo traducaoParams($translate->translate('_faq'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">

                    <form id="form" action="/adm/admin/faq/editar" method="post" class="horizontal-form">
                    	
        				<input type="hidden" name="id" class="form-control" id="id" value="<?=$id?>">
                        
                        <div class="row">
                            
							<div class="col-md-4">
								<div class="form-group">
									<label for="evento"><?=$translate->translate("evento")?></label>
									<select id="evento" class="form-control required" name="evento" <?=($id>0 ? 'disabled' : '')?>>
										<option value=""><?=$translate->translate("sel_evento")?></option>
										<?
											$exeEv = executaSQL("SELECT * FROM evento ORDER BY dt_inicio DESC");
											if(nLinhas($exeEv)>0){
											
												while($evento = objetoPHP($exeEv)){ ?>
														
													<option value="<?=$evento->id?>" <?=($reg->id_evento == $evento->id) ? 'selected' : '' ?> > <?=$evento->titulo?></option>	
														
										<?		}
											}
										?>
										
									 </select>
														
								</div>
							</div>

						</div>
						
						<?
							$x=0;
						?>

						<div class="row">
							
							<div class="col-md-8">
								<div class="form-group">
									<label for="pergunta1"><?=$translate->translate("pergunta")?></label>
									<input type="text" name="pergunta[<?=$x?>]" id="pergunta1" class="form-control required" value="<?=$reg->pergunta?>">
								</div>
							</div>
						
							<div class="col-md-4">
								<div class="form-group">
									<label><?=$translate->translate("ativa")?></label>
									<div class="radio-list">
										<label for="ativo<?=$x?>1" class="radio-inline"> 
											<input type="radio" class="required" name="ativo[<?=$x?>]" id="ativo<?=$x?>1" value="1" <?=($reg->ativo == 1)?'checked="checked"':''?> > <?=$translate->translate("sim")?>
										</label>
										<label for="ativo<?=$x?>2" class="radio-inline"> 
											<input type="radio" class="required" name="ativo[<?=$x?>]" id="ativo<?=$x?>2" value="2" <?=($reg->ativo == 2)?'checked="checked"':''?> > <?=$translate->translate("nao")?>
										</label>
									</div>
								</div>
							</div>

						</div>
						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="resposta1"><?=$translate->translate("resposta")?></label>
									<textarea name="resposta[<?=$x?>]" id="resposta1" class="form-control"><?=$reg->resposta?></textarea>
								</div>
							</div>
						</div>            
    					
					<?	if($id==0){ ?>
							
							<div class="portlet box grey-cascade">
						        <div class="portlet-title">
						            <div class="caption"><?=$translate->translate('adicionar_mais_perguntas_respostas')?></div>
						            <div class="tools"></div>
						        </div>
						        <div class="portlet-body">

									<div class="row text-center">
		                                <a href="javascript:void(0)" id="addFAQ" class="btn btn-sm blue"> <i class="fa fa-plus"></i> <?=$translate->translate("adicionar")?> </a> 
		                            </div>
									
									<div id="appendFAQ"></div>

						        </div>
						    </div>

					<?	} ?>
					   
					    <div class="form-actions left">
                            <button type="button" class="btn red" onclick="window.location='/adm/admin/banners/listar'"> <?=$translate->translate('bt_cancelar')?></button>
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
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
			
			var num = <?=$x?>;

			$( document ).on( "click", ".excluirFAQ", function() { 
				var num = $(this).attr("data-number");
				$("#novo_faq_"+num).remove();
			});

			$('#addFAQ').click(function(){
				num++;
				
				reg='';		

				reg+= 	'<div id="novo_faq_'+num+'">';
				
				reg+=	'<div class="row">';
				reg+=		'<div class="col-md-7">';
				reg+=			'<div class="form-group">';
				reg+=				'<label for="pergunta'+num+'"><?=$translate->translate('pergunta')?></label>';
				reg+= 				'<input type="text" name="pergunta[' + num + ']" id="pergunta'+num+'" class="form-control required" value="" data-number="'+num+'">';
				reg+=			'</div>';
				reg+=		'</div>';
				
				reg+=		'<div class="col-md-4">';			
				reg+=			'<div class="form-group">';				
				reg+=				'<label for=""><?=$translate->translate('ativa')?></label>';
				reg+=				'<div class="radio-list">';
				reg+=					'<label for="ativo'+num+'1" class="radio-inline"> ';
				reg+=						'<input type="radio" name="ativo['+num+']" id="ativo'+num+'1" value="1" checked /> <?=$translate->translate("sim")?>';
				reg+=					'</label>';
				reg+=					'<label for="ativo'+num+'2" class="radio-inline">';
				reg+=						'<input type="radio" name="ativo['+num+']" id="ativo'+num+'2" value="2" /> <?=$translate->translate("nao")?>';
				reg+=					'</label>';
				reg+=				'</div>';
				reg+=			'</div>';
				reg+=		'</div>';
				
				reg+=		'<div class="col-md-1">';			
				reg+=			'<div class="form-group">';
				reg+=				'<br />';
				reg+=				'<a href="javascript:void(0);" data-number="' + num + '" class="btn btn-sm red excluirFAQ" title="<?=$translate->translate("ttl_excluir")?>" alt="<?=$translate->translate("ttl_excluir")?>">';
				reg+=        			'<i class="fa fa-times"></i>';
				reg+=				'</a>';
				reg+=			'</div>';
				reg+=		'</div>';
				
				reg+=	'</div>';

				reg+=	'<div class="row">';
				
				reg+=		'<div class="col-md-12">';
				reg+=			'<div class="form-group">';
				reg+=				'<label for="resposta'+num+'"><?=$translate->translate('resposta')?></label>';
				reg+= 				'<textarea name="resposta['+num+']" id="resposta'+num+'" class="form-control required"></textarea>';
				reg+=			'</div>';
				reg+=		'</div>';

				reg+=	'</div>';

				reg+=	'<hr />';

				reg+= 	'</div>';
				
				$("#appendFAQ").append(reg);
				
			});
	
		});

	</script>