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
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate('gerar_elemento_sorteavel')?></small>
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
				<?=$translate->translate('gerar_elemento_sorteavel')?>
            </li>
        </ul>
    </div>
    
	<div class="portlet light bg-inverse">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-equalizer font-red-sunglo"></i>
				<span class="caption-subject font-red-sunglo bold uppercase">
				<?=$reg->titulo?> > <?=$translate->translate('gerar_elemento_sorteavel')?>
				</span>
			</div>
		</div>
		
		<div class="portlet-body form">
		<?
			$premios = executaSQL("SELECT * FROM evento_sorteio WHERE id_evento='".$reg->id."' ORDER BY sorteio_data DESC");
			
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
									<br />
									<h4><strong>Data do Sorteio da Loteria</strong>: <?=converte_data($premio->sorteio_data)?></h4>
								
								<?									
									$sorteio = executaSQL("SELECT * FROM sorteio_loteria WHERE sorteio_data = '".$premio->sorteio_data."'");
									
									if( nLinhas($sorteio)>0 ){
									
										$sorteio = arrayPHP($sorteio);
								?>
										<h4><strong>Prêmios</strong>:
	
											<strong>1</strong>: <?=$sorteio["sorteio_1"]?>
											 |
											<strong>2</strong>: <?=$sorteio["sorteio_2"]?>
											 |
											<strong>3</strong>: <?=$sorteio["sorteio_3"]?>
											 |
											<strong>4</strong>: <?=$sorteio["sorteio_4"]?>
											 |
											<strong>5</strong>: <?=$sorteio["sorteio_5"]?>
										</h4>
										<br />
								<?
										$sorteio_regulamentos = executaSQL("SELECT * FROM sorteio_regulamento WHERE id_evento = '".$reg->id."' ORDER BY hierarquia");
									
										if( nLinhas($sorteio_regulamentos)>0 ){
											$elemento = "";
								?>
											<h4><strong>Composição do Número da Sorte conforme Regulamento</strong>:</h4>
											
								<?			$seq = 1;
											while( $sorteioReg = objetoPHP($sorteio_regulamentos) ){ ?>
												
												<h4><strong>Sequência <?=$seq?></strong>: <?=substr($sorteio["sorteio_".$sorteioReg->sorteio], 0, ($sorteioReg->posicao-1) )."<span class='alert-danger'><strong>".substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1)."</strong></span>".substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao) )?></4>
								<?				
												$elemento .= substr($sorteio["sorteio_".$sorteioReg->sorteio], ($sorteioReg->posicao-1), 1);
												$seq++;
											} ?>
											
											<h4><strong>Número da Sorte conforme Regulamento</strong>: <span class='alert-danger'><strong><?=$elemento?></strong></span>
											
									<?		if( $premio->sorteio_nr_extracao>0 ){ 
												if( $premio->sorteio_nr_extracao != $elemento ){ ?>
													<div class="alert alert-danger">Número da Sorte registrado (<?=$premio->sorteio_nr_extracao?>) está diferente do Regulamento. <a href="/adm/admin/eventos/elemento-sorteavel-registrar/<?=$premio->id?>">Clique aqui</a> para atualizar.</div>	
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
				<div class="row alert alert-danger">
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
