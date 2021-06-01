<?
    if($_POST){
        
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
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate('ganhadores')?></small>
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
				<?=$translate->translate('ganhadores')?>
            </li>
        </ul>
    </div>
    
    <form id="form" action="/adm/admin/eventos/ganhadores" method="post">
        
        <input type="hidden" name="id" value="<?=$id?>">
        
		<div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">
                    <?=$reg->titulo?> > <?=$translate->translate('ganhadores')?>
                    </span>
                </div>
            </div>
            
            <div class="portlet-body form">
            <?
				$premios = executaSQL("SELECT * FROM evento_sorteio WHERE id_evento='".$reg->id."'");
				
				if( nLinhas($premios)>0 ){
					while( $premio = objetoPHP($premios) ){ ?>
				
						<div class="portlet box grey-cascade">
							<div class="portlet-title">
								<div class="caption">
									<strong>Prêmio</strong>: <?=$premio->titulo?>
								</div>
								<div class="tools"></div>
							</div>
							<div class="portlet-body">
							
								<div class="row">
									<div class="col-md-12">					
										<h4><strong>Quantidade</strong>: <?=$premio->qtde_premio?></h4>
										<h4><strong>Data do Sorteio da Loteria</strong>: <?=converte_data($premio->sorteio_data)?></h4>
									
									<?									
										$sorteio = executaSQL("SELECT * FROM sorteio_loteria WHERE sorteio_data = '".$premio->sorteio_data."'");
										
										if( nLinhas($sorteio)>0 ){
										
											$sorteio = arrayPHP($sorteio);
		
											$sorteio_regulamentos = executaSQL("SELECT * FROM sorteio_regulamento WHERE id_evento = '".$reg->id."' ORDER BY hierarquia");
										
											if( nLinhas($sorteio_regulamentos)>0 ){
												$elemento = "";
												while( $sorteioReg = objetoPHP($sorteio_regulamentos) ){
													$elemento .= substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1);
												} 
												
												if( $premio->sorteio_nr_extracao>0 ){ 
													if( $premio->sorteio_nr_extracao != $elemento ){ ?>
														<div class="alert alert-danger">Número da Sorte registrado (<?=$premio->sorteio_nr_extracao?>) está diferente do Regulamento. <a href="/adm/admin/eventos/elemento-sorteavel-registrar/<?=$premio->id?>">Clique aqui</a> para atualizar.</div>
										<?			}else{ ?>
														<h4><strong>Número da Sorte</strong>: <?=$premio->sorteio_nr_extracao?></h4>
														
														<?
															$sorteiosGerados = executaSQL("SELECT * FROM evento_ganhador eg WHERE eg.id_sorteio='".$premio->id."' ORDER BY id");
															if(nLinhas($sorteiosGerados)>0){ ?>
																<h4><strong>Ganhadores</strong>:</h4>
		
															<?	$ganhadoresAguardandoAprovados = objetoPHP(executaSQL("SELECT COUNT(*) as total FROM evento_ganhador eg WHERE eg.id_sorteio='".$premio->id."' AND id_situacao IN (1,2)"))->total;
																if($ganhadoresAguardandoAprovados<$premio->qtde_premio){ ?>
																	<div class="alert alert-danger">
																		Quantidade de Ganhador diferente da Quantidade de Prêmio. <a href="/adm/admin/eventos/ganhadores-registrar/<?=$premio->id?>">Clique aqui</a> para registrar o restante.
																	</div>
															<?	} ?>
															
															   <table class="dataTable table table-striped table-bordered table-hover list" >
																	<thead>
																		<tr>
																			<th class="text-center">Ordem</th>
																			<th class="text-center">Cupom</th>
																			<th class="text-center">Elemento</th>
																			<th>Participante</th>
																			<th class="text-center">Situação</th>
																			<th class="text-center">Opções</th>
																		</tr>
																	</thead>
														<?		$ordem=0;
																while( $ganhador = objetoPHP($sorteiosGerados) ){ 
																	$cupom = objetoPHP(getTableColumsByClause("participante_cupom", "cupom", "id='".$ganhador->id_participante_cupom."'"));
																	$elemento = objetoPHP(getTableColumsByClause("elemento_sorteavel", "elemento", "id='".$ganhador->id_elemento."'"));
																	$participante = objetoPHP(executaSQL("SELECT p.nome FROM participante p, participante_cupom pc WHERE pc.id='".$ganhador->id_participante_cupom."' AND pc.id_participante=p.id"));
																	$ordem++;
																	
																	switch($ganhador->id_situacao){
																		case 1: $classSituacao = 'warning'; break;
																		case 2: $classSituacao = 'success'; break;
																		case 3: $classSituacao = 'danger'; break;
																	}
														?>
																	<tr class="<?=$classSituacao?>">
																		<td class="text-center"><?=$ordem?></td>
																		<td class="text-center"><?=$cupom->cupom?></td>
																		<td class="text-center"><?=$elemento->elemento?></td>
																		<td><?=iconv("utf-8", "iso-8859-1", $participante->nome)?></td>
																		<td class="text-center"><?=objetoPHP(getTableColumsByClause("ganhador_situacao", "valor", "id='".$ganhador->id_situacao."'"))->valor?></td>
																		<td class="text-center">
																			<? if(in_array($ganhador->id_situacao, array(1,3))){ ?><button type="button" class="btn green" onclick="window.location='/adm/admin/eventos/ganhador-aprovar/<?=$ganhador->id?>'"><?=$translate->translate('aprovar')?></button><? } ?>
																			<? if(in_array($ganhador->id_situacao, array(1,2))){ ?><button type="button" class="btn red" onclick="window.location='/adm/admin/eventos/ganhador-reprovar/<?=$ganhador->id?>'"><?=$translate->translate('reprovar')?></button><? } ?>
																		</td>
																	</tr>
														<?		} ?>
																</table>
																
														<?	}else{ ?>
																<div class="alert alert-danger">
																		Nenhum Ganhador definido para este Sorteio. <a href="/adm/admin/eventos/ganhadores-registrar/<?=$premio->id?>">Clique aqui</a> para registrar.
																</div>	
														<?	}
															
														?>
														
														
										<?			}
												}else{ ?>
													<div class="alert alert-danger">Número da Sorte ainda não registrado. <a href="/adm/admin/eventos/elemento-sorteavel-registrar/<?=$premio->id?>">Clique aqui</a> para registrar.</div>	
										<?		}
												
												//ATUALIZA 
												//executaSQL("UPDATE evento_sorteio SET sorteio_nr_extracao = '".$elemento."'  WHERE id= '".$idSorteio."'");
								
												
											}else{ ?>
												<div class="alert alert-danger">Regulamento do Sorteio não disponível. <a href="/adm/admin/eventos/sorteio-regulamento/<?=$id?>">Clique aqui</a> para definir.</div>
										<?	}
											
										}else{ ?>
											<div class="alert alert-danger">Sorteio da Loteria Federal ainda não disponível.</div>
									<?	}
											
								/*
									if( $premio->sorteio_nr_extracao>0 ){ ?>
										<h4>Elemento Sorteavel da Loteria: <?=converte_data($premio->sorteio_data)?></h4>
								<?	}*/ ?>	
								
									</div>
								</div>
								
							</div>
						</div>	
						
			<?		}
				}else{ ?>
					<div class="row">
						<div class="col-md-12">
							Nenhum Prêmio definido para esta Campanha. <a href="/adm/admin/eventos/premios/<?=$id?>">Clique aqui</a> para cadastrar.
						</div>
					</div>
			<?	} ?>
			
				
				<div class="form-actions left">
					<button type="button" class="btn default" onclick="window.location='/adm/admin/eventos/listar'"><?=$translate->translate('voltar')?></button>
                </div>
                
                <div class="clear"></div>
			
        	</div>
		</div>
	</form>

    <script>
		$(function(){		
			
			$('.list').dataTable({
				"dom": '<"top">rt<"bottom"><"clear">',
				"order": [[ 0, "asc" ]]	
			});
	
			
		});
	</script>