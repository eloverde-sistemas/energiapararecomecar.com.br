<?php 
	$id = intval($_GET['id']);
	if( $id > 0 ){
		$exePerfil = executaSQLPadrao("perfil", "id = '".$id."'");
		if(!nLinhas($exePerfil)>0){
			header("Location: /adm/admin/perfis/listar");
			die();
		}else{
			$perfil = objetoPHP($exePerfil);
			
			if($perfil->eh_admin==1){
				header("Location: /adm/admin/perfis/listar");
				die();
			}
		}
	}
	if($_POST){
		
		$id = intval($_POST['id']);
		
		$dados['nome']		= trim($_POST['nome']);
		$dados['descricao']	= trim($_POST['descricao']);
			
		if($_POST['nome'] == ''){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error"); 	
			header("Location: /adm/admin/perfis/editar/");
		}else{
			
			if( $id > 0 ){
			
				$dados['id'] = $id;
			
				excluirDados("perfil_tarefa","id_perfil = '".$id."'");
			
				$exe = alterarDados('perfil', $dados, 'id = "'.$id.'"');
			
			}else{
				$dados['id'] = proximoId('perfil');
					
				$exe = inserirDados('perfil', $dados);
			}
		
			if(count($_POST['tarefas_habilitadas']) > 0){
			
				foreach($_POST['tarefas_habilitadas'] as $valor){
						
					$exe = inserirDados('perfil_tarefa', array("id_perfil"=>$dados['id'], "id_tarefa"=>$valor ));
					
					//BUSCA O NOME DA PERMISSÃO
					$nome = objetoPHP(executaSQL("SELECT nome FROM tarefa WHERE id='".$valor."' "))->nome;
					
					//VERIFICA SE EXISTE OUTRA TAREFA COM O MESMO ARQUIVO
					$selec = executaSQL("SELECT id FROM tarefa WHERE nome = '".$nome."' AND id!='".$valor."'");
					if(nlinhas($selec)>0){
						while($reg = objetoPHP($selec)){
							//SE TIVER, INSERE A TAREFA
							inserirDados('perfil_tarefa', array("id_perfil"=>$dados['id'], "id_tarefa"=>$reg->id ));
						}
					}
				}							
			}
			
			if(!$exe){
				setarMensagem( array($translate->translate("msg_salvo_com_erro")) , "error"); 	
				header("Location: /adm/admin/perfis/editar/".$id);
			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success"); 	
				header("Location: /adm/admin/perfis/listar/");
			}
			
		}						
	}	
?>
	<h3 class="page-title">
        <?=$translate->translate('tt_perfis')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('seguranca')?>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/perfis/listar"><?=$translate->translate('tt_perfis')?></a>
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
								echo traducaoParams($translate->translate('_perfil'), $params);								
							?>
						</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form id="form" action="/adm/admin/perfis/editar" method="post" enctype="multipart/form-data" class="horizontal-form">
                    	<input type="hidden" name="id" value="<?=$id?>">
                        
                        <div class="form-body">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="nome"><?=$translate->translate('nome')?></label>
                                        <input type="text" id="nome" name="nome" class="form-control" placeholder="<?=$translate->translate('nome')?>" value="<?=$perfil->nome?>">
                                    </div>
                                </div>
                            </div>
                          
							<div class="row">
                                <div class="col-md-12">
									<div class="form-group">
										<div class="row"><label class="control-label col-md-2"><?=$translate->translate('descricao')?></label></div>
										<div class="row">
											<div class="col-md-12">
												<textarea name="descricao" id="descricao" class="wysihtml5 form-control" rows="6"><?=$perfil->descricao?></textarea>
											</div>
										</div>
									</div>
								</div>
                            </div>

							<div class="row">
                                <div class="col-md-6">
									<div class="form-group">
    	                                <h2><?=$translate->translate("permissoes")?></h2>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
    	                                <label class="control-label" for="buscaModulo"><?=$translate->translate("buscar_modulo")?></label>: <input type="text" id="buscaModulo" name="buscaModulo" class="form-control" placeholder="<?=$translate->translate('buscar_modulo')?>">
                                    </div>
								</div>
                            </div>
							
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
										<a href="javascript:void(0);" id="marcar_todos"><?=$translate->translate("marcar_todos")?></a> &nbsp;/&nbsp;
                                        <a href="javascript:void(0);" id="desmarcar_todos"><?=$translate->translate("desmarcar_todos")?></a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" align="right">
                                        <a href="javascript:void(0);" id="mostra_tudo"><?=$translate->translate("mostrar_tudo")?></a>
                                        <a href="javascript:void(0);" id="esconder_tudo" style="display: none;"><?=$translate->translate("esconder_tudo")?></a>
                                    </div>
                                </div>
							</div>
                                                        
							<div class="row">
                                <div class="col-md-12">
								<?php
									$categorias = executaSQLPadrao('tarefa_categoria', 'tipo=1 ORDER BY descricao');
									$x=0;
									while($categoria = objetoPHP($categorias)){
										$x++;
										$tarefas = executaSQLPadrao('tarefa', 'id_categoria = "'.$categoria->id.'" AND mostrar=1 ORDER BY id');
										
										if(nLinhas($tarefas)>0){
								?>
											<div class="portlet box blue-steel">
												<div class="portlet-title">
													<div class="caption busca">
														<i class="fa fa-tag"></i><?=$translate->translate("tarefa_categoria_permissao_".$categoria->id)?> <span class="badge badge-danger"></span>
													</div>
													<div class="tools">
														<a href="javascript:;" class="expand"></a>
													</div>
												</div>
												<div class="portlet-body portlet-cont" style="display: none">
												<?
													while($tarefa = objetoPHP($tarefas)){
														
														$checked="";
														
														if( $id > 0 ){
							
															if(temPermissao($tarefa->nome, $id)){
																$checked='checked="checked"';

															}
															
														}
												?>
														<ul class="list-unstyled">
															<li>
																<label class="checkbox-label" for="tarefa_<?=$tarefa->id?>">
																	<input type="checkbox" <?=$checked?> id="tarefa_<?=$tarefa->id?>" name="tarefas_habilitadas[]" class="tarefas" value="<?=$tarefa->id?>">
																	<?=$translate->translate("tarefa_permissao_".$tarefa->id)?>
																</label>
															</li>
														</ul>
												<?	
													}
												?>
												</div>
											</div>
								<?	
										}
									}
								?>
								</div>
                            </div>
                            
                        </div>
                        <div class="form-actions left">
                            <button type="button" class="btn red" onclick="window.location='/adm/admin/perfis/listar'"> <?=$translate->translate('bt_cancelar')?></button>
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
		function contaSelecionados(elem){
			qtde	= elem.find('input:checked').length;
			badger	= elem.find('.busca .badge');
			
			badger.html(qtde);
			
			if( qtde>0 )
				badger.show();
			else
				badger.hide();
		}
	
        $(document).ready(function(){
			$('.portlet.box.blue-steel').each(function(){
				contaSelecionados( $(this) );
			});
			
			$('.portlet.box.blue-steel input').change(function(){
				contaSelecionados( $(this).closest('.portlet.box.blue-steel') );
			});
			
            $("#form").validate({
                rules: {
                    nome: "required",
                    descricao: "required",
                },
                 submitHandler: function(form) { 
                    
                        if($('.tarefas:checked').length > 0){
                            form.submit();
                        }else{
                            alert("Selecione ao menos uma permissão!");
                        }
                 
                  }
            });
            
			$('#buscaModulo').bind("blur keyup", function(){
				busca = this.value;
				elem = $('.caption.busca');
				
				if(busca.length>0){
				//	Esconde todas as categorias
					elem.closest('.portlet').addClass('hide');
				
				//	Passa por todas as categorias
					elem.each(function(){
					//	Mostra as categorias que contm o termo da busca
						if( $(this).html().toLowerCase().indexOf( busca.toLowerCase() )>0 ){
							$(this).closest('.portlet').removeClass('hide');
						}
						
					});
					
				}else{
					elem.closest('.portlet').removeClass('hide');
				}
			});
			
			$('#mostra_tudo').click(function(){
				
				$('.portlet-cont, #esconder_tudo').show();
				$('#mostra_tudo').hide();
				$('.portlet .tools .expand').attr('class', 'collapse');
				
			});
			
			$('#esconder_tudo').click(function(){
				
				$('.portlet-cont, #esconder_tudo').hide();
				$('#mostra_tudo').show();
				$('.portlet .tools .collapse').attr('class', 'expand');
				
			});
			
			$('#marcar_todos').click(function(){
				 
				$(".tarefas").each(function(){
					$(this).attr("checked", true);
					$(this).closest("span").addClass("checked");
				});
				
			});
			   
			$('#desmarcar_todos').click(function(){
				 				 
				$(".tarefas").each(function(){
					$(this).attr("checked", false);
					$(this).closest("span").removeClass("checked");
				});
			
			});
				
		});
	</script>