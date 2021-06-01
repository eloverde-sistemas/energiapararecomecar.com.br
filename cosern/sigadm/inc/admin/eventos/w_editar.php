<?
    if($_POST){
        
        $id = intval($_POST['id']);
        
		//var_dump($_POST);
        if(
			$_POST['titulo'] == '' 			|| $_POST['urlEvento']=='' 				||
			$_POST['dtInicio'] == '' 		|| $_POST['hrInicio'] == '' 			||
			$_POST['dtFim'] == '' 			|| $_POST['hrFim'] == '' 				|| 
			$_POST['tipoCampanha'] == '' 	|| $_POST['coParticipante'] == '' 		|| 
			$_POST['qtdeCupons'] == '' 		|| $_POST['qtdeRegsConsumidor'] == ''
		  ){
				
            setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "danger");   
            header("Location: /adm/admin/eventos/editar/".$id);
            die();
        }else{  
            
            $dados = array();

            $dados['titulo']                    = trim($_POST['titulo']);
            $dados['url_padrao']                = $_POST['urlEvento'];

            $dados['id_situacao']               = $_POST['situacao'];
			
			$dados['autorizacao']               = $_POST['autorizacao'];

			$dados['script1']            	   = $_POST['script1'];

            $dados['dt_inicio']                 = converte_data($_POST['dtInicio']);
            $dados['dt_termino']                = converte_data($_POST['dtFim']);
            $dados['hr_inicio']                 = trim($_POST['hrInicio']);
            $dados['hr_termino']                = trim($_POST['hrFim']);
			
            $dados['id_tipo_campanha']			= intval($_POST['tipoCampanha']);
			if( $dados['id_tipo_campanha']==3 ){ //SE CNPJ + CUPOM
	            $dados['requer_num_caixa']          = intval($_POST['numeroCaixa']);

				$dados['id_tipo_sorteio']	= intval($_POST['tipoSorteio']);
				
				$dados['id_controle_valor']	= intval($_POST['controlaValor']);
				$dados['valor']				= formataValorParaBanco($_POST['valor']);

			}
			
			if( in_array($dados['id_tipo_campanha'], array(3,4)) ){ //SE CNPJ + CUPOM OU CÓDIGO
				
				$dados['cupom_acumulativo']	= $_POST['acumulativo'];
				
				$dados['lote_controle']	= intval($_POST['controlaLote']);
				if( $dados['lote_controle']==1 ){
					$dados['lote_qtde']	= intval($_POST['cuponsLote']);
				}
			}
            
			$dados['requer_co_participante']    = intval($_POST['coParticipante']);
            $dados['requer_co_part_nascimento'] = intval($_POST['requerDtNasc']);
			if( $dados['requer_co_part_nascimento']==1 ){
				$dados['co_part_faixa_inicial']     = intval($_POST['idadeInicial']);
				$dados['co_part_faixa_final']       = intval($_POST['idadeFinal']);
			}
			
            $dados['qtde_cupons']               = intval($_POST['qtdeCupons']);
            $dados['qtde_regs_consumidor']      = intval($_POST['qtdeRegsConsumidor']);
            
			if( $_POST['perguntaSel']==1 ){
				$dados['pergunta']     = $_POST['pergunta'];
				$dados['resposta1']     = $_POST['resposta1'];
				$dados['resposta2']     = $_POST['resposta2'];
			}else{
				$dados['pergunta']     	= '';
				$dados['resposta1']     = '';
				$dados['resposta2']     = '';
			}

            $excluir_imagem = $_POST['excluirCabecalho'];

            if( $excluir_imagem == true ){
                $arquivo_anterior = objetoPHP(executaSQLPadrao("evento", " id = '".$id."'"))->img_cabecalho;
                $arquivo_anterior_thumb = objetoPHP(executaSQLPadrao("evento", " id = '".$id."'"))->img_cabecalho_thumb;
                
                if( is_file( '../'.$arquivo_anterior ) ){
                    unlink( '../'.$arquivo_anterior );
                    //echo $arquivo_anterior;
                    $image['img_cabecalho'] = '';
                    alterarDados('evento', $image, 'id = "'.$id.'"');                          
                }
                if( is_file( '../'.$arquivo_anterior_thumb ) ){
                    unlink( '../'.$arquivo_anterior_thumb );
                    //echo $arquivo_anterior_thumb;
                    $image_thumb['img_cabecalho_thumb'] = '';
                    alterarDados('evento', $image_thumb, 'id = "'.$id.'"');                            
                }                   
            }
			
            if( $_FILES['img_cabecalho']['tmp_name'] != '' ){

                require_once('../lib/WideImage/WideImage.php');
				$extImg = explode(".",$_FILES['img_cabecalho']['name']);
				$extImg = end($extImg);
                if( in_array($extImg, array("jpg","jpeg","png","gif") ) ){
                    
                    $dados['img_cabecalho'] = criaDiretorios( array("uploads", "eventos", "foto") ).$id.".".$extImg;
                    
                    $upload = move_uploaded_file( $_FILES['img_cabecalho']['tmp_name'], '../'.$dados['img_cabecalho'] );
                    
                    $img = WideImage::load('../'.$dados['img_cabecalho'])-> resize(1170, 800, 'outside', 'down')-> saveToFile('../'.$dados['img_cabecalho']);
                    
                
                    //IMAGEM THUMB
                    $dados['img_cabecalho_thumb'] = criaDiretorios( array("uploads", "eventos", "foto-thumb") ).$id.".".$extImg;
                    
                    $upload = copy( '../'.$dados['img_cabecalho'], '../'.$dados['img_cabecalho_thumb'] );
                    
                    $imgThumb = WideImage::load('../'.$dados['img_cabecalho_thumb'])->resize(140, 140, 'outside', 'down')->crop('', '', 140, 140)->saveToFile('../'.$dados['img_cabecalho_thumb']);
                
                    
                }else{
                    setarMensagem(array($translate->translate("msg_formatos_permitidos")), "error");
                    header("Location: /adm/admin/eventos/editar/".$id);
                    die();
                }
            }



			$excluir_imagem2 = $_POST['excluirCabecalho2'];

            if( $excluir_imagem2 == true ){
                $arquivo_anterior = objetoPHP(executaSQLPadrao("evento", " id = '".$id."'"))->img2_cabecalho;
                $arquivo_anterior_thumb = objetoPHP(executaSQLPadrao("evento", " id = '".$id."'"))->img2_cabecalho_thumb;
                
                if( is_file( '../'.$arquivo_anterior ) ){
                    unlink( '../'.$arquivo_anterior );
                    //echo $arquivo_anterior;
                    $image['img2_cabecalho'] = '';
                    alterarDados('evento', $image, 'id = "'.$id.'"');                          
                }
                if( is_file( '../'.$arquivo_anterior_thumb ) ){
                    unlink( '../'.$arquivo_anterior_thumb );
                    //echo $arquivo_anterior_thumb;
                    $image_thumb['img2_cabecalho_thumb'] = '';
                    alterarDados('evento', $image_thumb, 'id = "'.$id.'"');                            
                }                   
            }


			if( $_FILES['img_cabecalho2']['tmp_name'] != '' ){

                require_once('../lib/WideImage/WideImage.php');

				$extImg = explode(".",$_FILES['img_cabecalho2']['name']);
				$extImg = end($extImg);
                if( in_array($extImg, array("jpg","jpeg","png","gif") ) ){
                    
                    $dados['img2_cabecalho'] = criaDiretorios( array("uploads", "eventos", "foto") ).$id."_mobile.".$extImg;
                    
                    $upload = move_uploaded_file( $_FILES['img_cabecalho2']['tmp_name'], '../'.$dados['img2_cabecalho'] );
                    
                    $img2 = WideImage::load('../'.$dados['img2_cabecalho'])-> resize(1170, 800, 'outside', 'down')-> saveToFile('../'.$dados['img2_cabecalho']);
                    
                
                    //IMAGEM THUMB
                    $dados['img2_cabecalho_thumb'] = criaDiretorios( array("uploads", "eventos", "foto-thumb") ).$id."_mobile.".$extImg;
                    
                    $upload = copy( '../'.$dados['img2_cabecalho'], '../'.$dados['img2_cabecalho_thumb'] );
                    
                    $img2Thumb = WideImage::load('../'.$dados['img2_cabecalho_thumb'])->resize(140, 140, 'outside', 'down')->crop('', '', 140, 140)->saveToFile('../'.$dados['img2_cabecalho_thumb']);
                
                    
                }else{
                    setarMensagem(array($translate->translate("msg_formatos_permitidos")), "error");
                    header("Location: /adm/admin/eventos/editar/".$id);
                    die();
                }
            }


            if($id>0){

                //VERIFICA SE EXISTE NR SORTEAVEL E VALIDA A QTDE DE CUPONS COM RELAÇÃO A QUANTIDADE DE NR SORTEAVEIS
                $exeNrSorteaveis = executaSQL("SELECT ens.quantidade FROM evento e, evento_numeros_sorteaveis ens 
                                                WHERE e.id='".$id."'
                                                AND ens.id = e.id_numeros_sorteaveis");
                if(nLinhas($exeNrSorteaveis)>0){
                    
                    $numeros_sorteaveis = objetoPHP($exeNrSorteaveis);

                    if($numeros_sorteaveis->quantidade < $dados['qtde_cupons']){
                        setarMensagem(array($translate->translate("msg_quantidade_cupons_maior_permitido")), "error");
                        header("Location: /adm/admin/eventos/editar/".$id);
                        die();                        
                    }

                }else{
                    setarMensagem(array($translate->translate("msg_nenhum_numero_sorteavel_encontrado")), "error");
                    header("Location: /adm/admin/eventos/editar/".$id);
                    die();
                }


                $exe = alterarDados('evento', $dados, 'id = "'.$id.'"');

            }else{
				
                $dados['id']                    = $id = proximoId('evento');
				
				if( in_array($dados['id_tipo_campanha'], array(1,3,4)) ){ //SE CADASTRE E PARTICIPE | CNPJ + CUPOM | CÓDIGO
					$dados['id_numeros_sorteaveis'] = intval($_POST['qtdeNumerosSorteaveis']);
					
					//VERIFICA SE EXISTE NR SORTEAVEL E VALIDA A QTDE DE CUPONS COM RELAÇÃO A QUANTIDADE DE NR SORTEAVEIS
					$exeNrSorteaveis = executaSQL("SELECT * FROM evento_numeros_sorteaveis WHERE id = '".$dados['id_numeros_sorteaveis']."'");
					if(nLinhas($exeNrSorteaveis)>0){
						$numeros_sorteaveis = objetoPHP($exeNrSorteaveis);
	
						if($numeros_sorteaveis->quantidade < $dados['qtde_cupons']){
							setarMensagem(array($translate->translate("msg_quantidade_cupons_maior_permitido")), "error");
							header("Location: /adm/admin/eventos/editar/".$id);
							die();                        
						}
	
					}
				}
				
                $exe = inserirDados('evento', $dados);
                
                if($exe){
                    criaMenuEvento($id);

                    //SE CAMPANHA DE 100.000 OU 1.000.000
                    if($numeros_sorteaveis->quantidade <= 100000){
                        alterarDados('evento', array('elementos_gerados'=>1), "id='".$id."'");
                        
                        for($x=0; $x<$numeros_sorteaveis->quantidade; $x++){
                            $dadosES = array();
                            $dadosES['id_evento']   = $id;
                            $dadosES['elemento']    = $x;
                            inserirDados("elemento_sorteavel", $dadosES);
                        }
                    }else{
                        alterarDados('evento', array('elementos_gerados'=>0), "id='".$id."'");
                    }
                
                }
				
            }

                
            if($exe){

                excluirDados("evento_loja", "id_evento='".$id."'");

                $lojas = $_POST['loja']; 
                if(count($lojas)>0){
                    foreach($lojas as $idLoja){

                        $dados = array();
                        $dados['id']            = proximoId('evento_loja');
                        $dados['id_evento']     = $id;
                        $dados['id_loja']       = $idLoja;
                        inserirDados("evento_loja", $dados);

                    }
                }


                setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
                header("Location: /adm/admin/eventos/listar/");
            }else{
                setarMensagem(array($translate->translate("msg_salvo_com_erro")), "danger");
                header("Location: /adm/admin/eventos/editar/".$id);
            }

        }

        die();
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
    	<?=$translate->translate('eventos')?> <small><?=( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar")?></small>
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
				<?=( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar")?>
            </li>
        </ul>
    </div>
    
    <form id="form" action="/adm/admin/eventos/editar" method="post" enctype="multipart/form-data">
        
        <input type="hidden" name="id" value="<?=$id?>">
        
		<div class="portlet light bg-inverse">
        	<div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">
                    <? 	$params=array();
                        if($id > 0){
                        	$params[] = $translate->translate("ttl_editar");
                        }else{
                        	$params[] = $translate->translate("tt_adicionar");
                        }
                    	echo traducaoParams($translate->translate('_evento'), $params);
                    ?>
                    </span>
                </div>
			</div>
			
            <div class="portlet-body form">

				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption"><?=$translate->translate('dados_campanha')?></div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">  


						<div class="row">
							
							<div class="col-md-12">
								<div class="form-group">
									<label for=""><?=$translate->translate("situacao")?></label>
									<div class="radio-list">
										<label class="radio-inline" for="situacao1">
											<input type="radio" name="situacao" id="situacao1" value="1" class="required" <?=($reg->id_situacao==1 ? 'checked' : '')?>> <?=$translate->translate("ativa")?>
										</label>
										<label class="radio-inline" for="situacao2">
											<input type="radio" name="situacao" id="situacao2" value="99" class="required" <?=($reg->id_situacao==99 ? 'checked' : '')?>> <?=$translate->translate("teste")?>
										</label>
										<label class="radio-inline" for="situacao3">
											<input type="radio" name="situacao" id="situacao3" value="3" class="required" <?=($reg->id_situacao==3 ? 'checked' : '')?>> <?=$translate->translate("cancelada")?>
										</label>
									</div>
								</div>
							</div>

						</div>
											
						<div class="row">
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="titulo"><?=$translate->translate("titulo")?></label>
									<input type="text" name="titulo" id="titulo" class="form-control required" value="<?=$reg->titulo?>" />
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="urlEvento"><?=$translate->translate("url_evento")?></label>
									<input type="text" name="urlEvento" id="urlEvento" class="form-control required" value="<?=$reg->url_padrao?>" />
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="autorizacao"><?=$translate->translate("autorizacao")?></label>
									<input type="text" name="autorizacao" id="autorizacao" class="form-control required" value="<?=$reg->autorizacao?>" />
								</div>
							</div>
							
						</div>
						
						<div class="row clear">	
							<div class="col-md-4">
								<div class="form-group">
									<label for="dtInicio"><?=$translate->translate("data_inicio")?></label>
									<input type="text" name="dtInicio" id="dtInicio" class="form-control data date-picker required" value="<?=converte_data($reg->dt_inicio)?>" />
								</div>
							</div>
		
							<div class="col-md-2">
								<div class="form-group">
									<label for="hrInicio"><?=$translate->translate("hora_inicio")?></label>
									<input type="text" name="hrInicio" id="hrInicio" class="form-control required hora" value="<?=substr($reg->hr_inicio,0,5)?>" />
								</div>
							</div>
						
							<div class="col-md-4">
								<div class="form-group">
									<label for="dtFim"><?=$translate->translate("data_termino")?></label>
									<input type="text" name="dtFim" id="dtFim" class="form-control data date-picker required" value="<?=converte_data($reg->dt_termino)?>" />
								</div>
							</div>
		
							<div class="col-md-2">
								<div class="form-group">
									<label for="hrFim"><?=$translate->translate("hora_termino")?></label>
									<input type="text" name="hrFim" id="hrFim" class="form-control required hora" value="<?=substr($reg->hr_termino,0,5)?>" />
								</div>
							</div>
						</div>
						
					</div>
				</div>


				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption"><?=$translate->translate('definicoes')?></div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">  

						<div class="row">		
							<div class="col-md-6">
								<div class="form-group">
									<label for=""><?=$translate->translate("tipo_de_campanha")?></label>
									<div class="radio-list">
										<label class="radio-inline" for="tipoCampanha1">
											<input type="radio" name="tipoCampanha" id="tipoCampanha1" value="1" class="required" <?=($reg->id_tipo_campanha==1 ? 'checked' : '')?>> <?=$translate->translate("cadastre_e_participe")?>
										</label>
										<label class="radio-inline" for="tipoCampanha2">
											<input type="radio" name="tipoCampanha" id="tipoCampanha2" value="2" class="required" <?=($reg->id_tipo_campanha==2 ? 'checked' : '')?>> <?=$translate->translate("cadastre_e_ganhe")?>
										</label>
										<label class="radio-inline" for="tipoCampanha3">
											<input type="radio" name="tipoCampanha" id="tipoCampanha3" value="3" class="required" <?=($reg->id_tipo_campanha==3 ? 'checked' : '')?>> <?=$translate->translate("cnpj_cupom")?>
										</label>
										<label class="radio-inline" for="tipoCampanha4">
											<input type="radio" name="tipoCampanha" id="tipoCampanha4" value="4" class="required" <?=($reg->id_tipo_campanha==4 ? 'checked' : '')?>> <?=$translate->translate("codigo")?>
										</label>
									</div>
								</div>
							</div>
		
							<div class="col-md-3 hide" id="divNumeroCaixa">
								<div class="form-group">
									<label for=""><?=$translate->translate("requer_numero_caixa")?>?</label>
									<div class="radio-list">
										<label class="radio-inline" for="numeroCaixa1">
											<input type="radio" name="numeroCaixa" id="numeroCaixa1" value="1" class="required" <?=($reg->requer_num_caixa==1 ? 'checked' : '')?>> <?=$translate->translate("sim")?>
										</label>
										<label class="radio-inline" for="numeroCaixa2">
											<input type="radio" name="numeroCaixa" id="numeroCaixa2" value="2" class="required" <?=($reg->requer_num_caixa==2 ? 'checked' : '')?>> <?=$translate->translate("nao")?>
										</label>
									</div>
								</div>
							</div>


							<div class="col-md-3 hide divTipoSorteio">
								<div class="form-group">
									<label for=""><?=$translate->translate("tipo_sorteio")?>?</label>
									<div class="radio-list">
										<label class="radio-inline" for="tipoSorteio1">
											<input type="radio" name="tipoSorteio" id="tipoSorteio1" value="1" class="required" <?=($reg->id_tipo_sorteio==1 ? 'checked' : '')?>> <?=$translate->translate("loteria_federal")?>
										</label>
										<label class="radio-inline" for="tipoSorteio2">
											<input type="radio" name="tipoSorteio" id="tipoSorteio2" value="2" class="required" <?=($reg->id_tipo_sorteio==2 ? 'checked' : '')?>> <?=$translate->translate("vale_brinde")?>
										</label>
									</div>
								</div>
							</div>

							<div class="col-md-3 hide divControlePorLote">
								<div class="form-group">
									<label for=""><?=$translate->translate("controle_por_lote")?>?</label>
									<div class="radio-list">
										<label class="radio-inline" for="controlaLote1">
											<input type="radio" name="controlaLote" id="controlaLote1" value="1" class="required" <?=($reg->lote_controle==1 ? 'checked' : '')?>> <?=$translate->translate("sim")?>
										</label>
										<label class="radio-inline" for="controlaLote2">
											<input type="radio" name="controlaLote" id="controlaLote2" value="2" class="required" <?=($reg->lote_controle==2 ? 'checked' : '')?>> <?=$translate->translate("nao")?>
										</label>
									</div>
								</div>
							</div>
		
						
							<div class="col-md-3 hide divControlePorLote divCuponsPorLote">
								<div class="form-group">
									<label for="cuponsLote"><?=$translate->translate("quantos_cupons_por_lote")?></label>
									<input type="text" name="cuponsLote" id="cuponsLote" class="form-control numero required" value="<?=$reg->lote_qtde?>" />
								</div>
							</div>

						</div>
		
						<div class="row">
		
							<div class="col-md-3">
								<div class="form-group">
									<label for=""><?=$translate->translate("requer_co_participante")?>?</label>
									<div class="radio-list">
										<label class="radio-inline" for="coParticipante1">
											<input type="radio" name="coParticipante" id="coParticipante1" value="1" class="required" <?=($reg->requer_co_participante==1 ? 'checked' : '')?>> <?=$translate->translate("sim")?>
										</label>
										<label class="radio-inline" for="coParticipante2">
											<input type="radio" name="coParticipante" id="coParticipante2" value="2" class="required" <?=($reg->requer_co_participante==2 ? 'checked' : '')?>> <?=$translate->translate("nao")?>
										</label>
									</div>
								</div>
							</div>
		
							<div class="col-md-3 hide" id="divDtNascimento">
								<div class="form-group">
									<label for=""><?=$translate->translate("requer_data_nascimento")?>?</label>
									<div class="radio-list">
										<label class="radio-inline" for="requerDtNasc1">
											<input type="radio" name="requerDtNasc" id="requerDtNasc1" value="1" class="required" <?=($reg->requer_co_part_nascimento==1 ? 'checked' : '')?>> <?=$translate->translate("sim")?>
										</label>
										<label class="radio-inline" for="requerDtNasc2">
											<input type="radio" name="requerDtNasc" id="requerDtNasc2" value="2" class="required" <?=($reg->requer_co_part_nascimento==2 ? 'checked' : '')?>> <?=$translate->translate("nao")?>
										</label>
									</div>
								</div>
							</div>
		
							<div class="col-md-6 hide" id="divFaixaEtaria">
								<div class="form-group">
									<label for="idadeInicial" class="left">
										<?=$translate->translate("idade_inicial")?>
										<input type="text" name="idadeInicial" id="idadeInicial" class="form-control required numero" style="width:95%;" value="<?=$reg->co_part_faixa_inicial?>" />
									</label>
									<label for="idadeFinal" class="left">
										<?=$translate->translate("idade_final")?>
										<input type="text" name="idadeFinal" id="idadeFinal" class="form-control required numero" style="width:95%;" value="<?=$reg->co_part_faixa_final?>" />
									</label>
								</div>
								<div class="clear">&nbsp;</div>
		
							</div>
		
						</div>
							
						<div class="row">
			
							<div class="col-md-3 hide controleValor">
								<div class="form-group">
									<label for=""><?=$translate->translate("como_eh_controle_valor")?>?</label>
									<div class="radio-list">
										<label class="radio-inline" for="controlaValor1">
											<input type="radio" name="controlaValor" id="controlaValor1" value="1" class="form-control required" <?=($reg->id_controle_valor==1 ? 'checked' : '')?>> <?=$translate->translate("maior_ou_igual")?>
										</label>
										<label class="radio-inline" for="controlaValor2">
											<input type="radio" name="controlaValor" id="controlaValor2" value="2" class="form-control required" <?=($reg->id_controle_valor==2 ? 'checked' : '')?>> <?=$translate->translate("multiplos")?>
										</label>
									</div>
								</div>
							</div>
		
							<div class="col-md-3 hide controleValor">
								<div class="form-group">
									<label for="acumulativo"><?=$translate->translate("valor_acumulativo")?>?</label>
									<div class="radio-list">
										<label class="radio-inline" for="acumulativo1">
											<input type="radio" name="acumulativo" id="acumulativo1" value="1" class="form-control required" <?=($reg->cupom_acumulativo==1 ? 'checked' : '')?>> <?=$translate->translate("sim")?>
										</label>
										<label class="radio-inline" for="acumulativo2">
											<input type="radio" name="acumulativo" id="acumulativo2" value="2" class="form-control required" <?=($reg->cupom_acumulativo==2 ? 'checked' : '')?>> <?=$translate->translate("nao")?>
										</label>
									</div>

								</div>
							</div>
							
							<div class="col-md-3 hide controleValor">
								<div class="form-group">
									<label for="valor"><?=$translate->translate("valor_rs")?></label>
									<input type="text" name="valor" id="valor" class="form-control money required" value="<?=formatarDinheiro($reg->valor,false)?>" />
								</div>
							</div>
						</div>
					
					</div>
					
				</div>
			
			
				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption"><?=$translate->translate('elementos_sorteaveis')?></div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">  

						<div class="row">
							<div class="col-md-4 hide NumerosSorteaveis">
								<div class="form-group">
									<label for="qtdeNumerosSorteaveis"><?=$translate->translate("quantidade_numero_sorteaveis")?></label>
									<select name="qtdeNumerosSorteaveis" id="qtdeNumerosSorteaveis" class="form-control required" <?=($reg->id_numeros_sorteaveis>0)?'disabled' :''?>>
										<option value=""></option>
									<?
										$numsorts = executaSQLPadrao("evento_numeros_sorteaveis");
										while( $numsort = objetoPHP($numsorts) ){
									?>
											<option value="<?=$numsort->id?>" <?=$reg->id_numeros_sorteaveis==$numsort->id ? 'selected="selected"' : ''?>> <?=$numsort->descricao?> </option>
									<?
										}
									?>
									</select>
								</div>
							</div>
		
							<div class="col-md-3">
								<div class="form-group">
									<label for="qtdeCupons"><?=$translate->translate("quantidade_cupons_campanha")?></label>
									<input type="text" name="qtdeCupons" id="qtdeCupons" class="form-control numero required" value="<?=$reg->qtde_cupons?>" />
									<label for="qtdeCupons" class="error" id="qtdeCuponsError"></label>
								</div>
							</div>
		
							<div class="col-md-3">
								<div class="form-group">
									<label for="qtdeRegsConsumidor"><?=$translate->translate("quantidade_maxima_registros_participante")?></label>
									<input type="text" name="qtdeRegsConsumidor" id="qtdeRegsConsumidor" class="form-control numero required" value="<?=$reg->qtde_regs_consumidor?>" />
								</div>
							</div>
						
						</div>
						
					</div>
					
				</div>



				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption"><?=$translate->translate('pergunta_respostas')?></div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">  


						<div class="row">
							<div class="col-md-12">
							
								<div class="form-group">
									<label for=""><?=$translate->translate("pergunta_respostas_txt")?></label>
									<div class="radio-list">
										<label class="radio-inline" for="PergResp1">
											<input type="radio" name="perguntaSel" id="PergResp1" value="1" class="form-control required" <?=($reg->pergunta!='' ? 'checked' : '')?>> <?=$translate->translate("sim")?>
										</label>
										<label class="radio-inline" for="PergResp2">
											<input type="radio" name="perguntaSel" id="PergResp2" value="2" class="form-control required" <?=($reg->pergunta=='' ? 'checked' : '')?>> <?=$translate->translate("nao")?>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="row pergunta_resposta hide">
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="titulo"><?=$translate->translate("pergunta")?></label>
									<input type="text" name="pergunta" id="pergunta" class="form-control required" value="<?=$reg->pergunta?>" />
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="resposta1"><?=$translate->translate("resposta_correta")?></label>
									<input type="text" name="resposta1" id="resposta1" class="form-control required" value="<?=$reg->resposta1?>" />
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="resposta2"><?=$translate->translate("resposta_errada")?></label>
									<input type="text" name="resposta2" id="resposta2" class="form-control required" value="<?=$reg->resposta2?>" />
								</div>
							</div>
							
						</div>
					</div>
				</div>

				<div class="portlet box grey-cascade">
					<div class="portlet-title">
						<div class="caption"><?=$translate->translate('personalizacao')?></div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">  

						<div class="row">
		
							<div class="col-md-6 jumper-20">
								<div class="form-group">
									<label class="control-label" for="img_cabecalho"><?=$translate->translate("cabecalho")?></label>
									<span id="img_cabecalho" class="img_cabecalho" style="color:#C00; font-size:11px;">
										<?=traducaoParams($translate->translate("tamanho_recomendado"), array("1170"));?>
									</span>
									<input type="file" name="img_cabecalho"  id="img_cabecalho" >
								</div>
														
							<?  if(is_file('../'.$reg->img_cabecalho)){   ?>
									<a href="<?='/'.$reg->img_cabecalho?>" class="jumper-20 fancybox-button" data-rel="fancybox-button">
										<img src="<?='/'.$reg->img_cabecalho_thumb?>" height="100">
									</a>
									<label class="radio-inline" for="excluirCabecalho">
										<input type="checkbox"  name="excluirCabecalho" id="excluirCabecalho" value="1" class="excluir"> <?=$translate->translate("excluir_imagem")?>
									</label>
							<?  }   ?>
								
							</div>
		
						</div>

						<div class="row">
		
							<div class="col-md-6 jumper-20">
								<div class="form-group">
									<label class="control-label" for="img_cabecalho"><?=$translate->translate("cabecalho2")?></label>
									<span id="img_cabecalho2" class="img_cabecalho" style="color:#C00; font-size:11px;">
										<?=traducaoParams($translate->translate("tamanho_recomendado"), array("600"));?>
									</span>
									<input type="file" name="img_cabecalho2"  id="img_cabecalho2" >
								</div>
														
							<?  if(is_file('../'.$reg->img2_cabecalho)){   ?>
									<a href="<?='/'.$reg->img2_cabecalho?>" class="jumper-20 fancybox-button" data-rel="fancybox-button">
										<img src="<?='/'.$reg->img2_cabecalho_thumb?>" height="100">
									</a>
									<label class="radio-inline" for="excluirCabecalho2">
										<input type="checkbox"  name="excluirCabecalho2" id="excluirCabecalho2" value="1" class="excluir"> <?=$translate->translate("excluir_imagem")?>
									</label>
							<?  }   ?>
								
							</div>
		
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="script1"><?=$translate->translate("script1")?></label>
									<textarea name="script1" id="script1" rows="6" class="form-control "><?=$reg->script1?></textarea>
								</div>
							</div>
						</div>
						
						
					</div>
				
				</div>
				
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption"><?=$translate->translate('lojas_participantes')?></div>
                        <div class="tools"></div>
                    </div>
                    <div class="portlet-body">  


                    <?
                        $exe = executaSQL("SELECT * FROM loja ORDER BY nome_fantasia");
                        if(nLinhas($exe)>0){ ?>

                            <div class="row text-center">
                                <a href="javascript:void(0)" id="marcaTodos" class="btn btn-sm blue"> <i class="fa fa-check-square"></i> <?=$translate->translate("marcar_todas")?> </a> 
                                &nbsp;&nbsp;
                                <a href="javascript:void(0)" id="desmarcaTodos" class="btn btn-sm blue"> <i class="fa fa-minus-square"></i> <?=$translate->translate("desmarcar_todas")?> </a>
                            </div>

                            <div class="clear">&nbsp;</div>

                            <div class="row">                            

                        <?      while($reg2=objetoPHP($exe)){ ?>
                                    
                                    <div class="col-md-6">
                                        <div class="radio-list">
                                            <label class="radio-inline" for="loja<?=$reg2->id?>">
                                                <input type="checkbox" name="loja[]" id="loja<?=$reg2->id?>" value="<?=$reg2->id?>" class="loja" <?=(verificaLojaParticipanteByIdLoja($id, $reg2->id) ? 'checked' : '')?>> <?=$reg2->nome_fantasia?>
                                            </label>
                                        </div>
                                    </div>

                        <?      } ?>
                            
                            </div>

                    <?  }else{ ?>

                            <div class="alert alert-danger"><?=$translate->translate('nenhuma_loja_encontrada')?></div>

                    <?  }
                    ?>
                        
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

            $("#form").submit(function(){

                if( $(this).valid()==true ){
                    return validaNrCupons();      
                }

            });

            verificaTipoCampanha();
            verificaCoParticipantes();
            verificaDtNascimento();
            verificaControlePorLote();
            verificaPergunta();
			
			$("input[name='perguntaSel']").change(function(){
                verificaPergunta();
            });

            $("input[name='tipoCampanha']").change(function(){
                verificaTipoCampanha();
                verificaControlePorLote();
            });

            $("input[name='coParticipante']").change(function(){
                verificaCoParticipantes();
            });

            $("input[name='requerDtNasc']").change(function(){
                verificaDtNascimento();
            });

            $("input[name='controlaLote']").change(function(){
                verificaControlePorLote();
            });
			
			$("input[name='controlaValor']").change(function(){
                verificaMultiploAcumulativo();
            });

            $("#titulo").bind("blur", function(){
                converteTituloPagina();
            }); 
            
            $("#qtdeCupons").bind("blur", function(){
                validaNrCupons();
            }); 

            $('#marcaTodos').click(function(){
                $(".loja").each(function(){
                    $(this).attr("checked", true);
                    $(this).closest("span").addClass("checked");
                });
            });
            
            $('#desmarcaTodos').click(function(){
                $(".loja").each(function(){
                    $(this).attr("checked", false);
                    $(this).closest("span").removeClass("checked");
                });
            });

            $("#urlEvento").bind("blur keyup", function() { formataUrlConteudoPersonalizado($(this)); });
			
		});

        verificaTipoCampanha = function(){
			//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
            var valor = $("input[name='tipoCampanha']:checked").val();
			
            $("#divNumeroCaixa").addClass("hide");
			$(".divControlePorLote").addClass("hide");
			$(".divTipoSorteio").addClass("hide");
			$(".controleValor").addClass("hide");
			$(".NumerosSorteaveis").addClass("hide");
            if(valor==1 || valor==3 || valor==4){
                $(".NumerosSorteaveis").removeClass("hide");
				if(valor==3 || valor==4){
					
					if(valor==3){
						$("#divNumeroCaixa").removeClass("hide");
						$(".divTipoSorteio").removeClass("hide");
						$(".controleValor").removeClass("hide");
						verificaMultiploAcumulativo();
					}
					
					if(valor==4){
						$(".divControlePorLote").removeClass("hide");
					}
				}
				
            }
        }

		verificaPergunta = function(){
            var valor = $("input[name='perguntaSel']:checked").val();

            $(".pergunta_resposta").addClass("hide");
            if(valor==1){
                $(".pergunta_resposta").removeClass("hide");
				$("#pergunta").focus();
            }
        }

        verificaCoParticipantes = function(){
            var valor = $("input[name='coParticipante']:checked").val();

            $("#divDtNascimento").addClass("hide");
            if(valor==1){
                $("#divDtNascimento").removeClass("hide");
            }

            verificaDtNascimento();
        }

        verificaDtNascimento = function(){
            var valor   = $("input[name='requerDtNasc']:checked").val();
            var valorCo = $("input[name='coParticipante']:checked").val();

            $("#divFaixaEtaria").addClass("hide");
            if(valor==1 && valorCo==1){
                $("#divFaixaEtaria").removeClass("hide");
            }
        }

        verificaControlePorLote = function(){
            var valor   = $("input[name='controlaLote']:checked").val();
            var valor2  = $("input[name='tipoCampanha']:checked").val();

            $(".divCuponsPorLote").addClass("hide");
            if(valor==3 && valor2==1){
                $(".divCuponsPorLote").removeClass("hide");
            }
        }
		
		verificaMultiploAcumulativo = function(){
            var controlaValor = $("input[name='controlaValor']:checked").val();

			/*if( controlaValor==2 ){
				$(".controleMultiploCNPJCupom").removeClass("hide");
			}else{
				$(".controleMultiploCNPJCupom").addClass("hide");
			}*/
        }

        validaNrCupons = function(){
            var tipo  = $("input[name='tipoCampanha']:checked").val();
			
			if(tipo==1 || tipo==3 || tipo==4 ){
				var nrCupons   = $("#qtdeCupons").val();
				var idNrSort   = $("#qtdeNumerosSorteaveis").val();
				retorno = true;
	
				$.ajax({
					url: 'inc/genericoJSON.php',
					type: 'post',
					data: {
							acao:       'consultaQtdeNumerosSorteaveis',
							id:         idNrSort,
							qtdeCupons: nrCupons
					},
					cache: false,
					async: false,
					success: function(data) {
						$("#qtdeCuponsError").hide();
						if(data.status == false){
							$("#qtdeCuponsError").html(data.msg);
							$("#qtdeCuponsError").show();
							retorno = false;
						}
	
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.responseText);
					},
					dataType: 'json'
				});
			}
			return retorno;
        }

        converteTituloPagina = function(){
            
            var titulo   = $("#titulo").val();
            var nomePage = $("#urlEvento").val();
            
            if(titulo!='' && nomePage==''){
            
                $.ajax({
                    url: 'inc/genericoJSON.php',
                    type: 'post',
                    data: {
                            acao: 'setaNomePagina',
                            titulo: titulo
                    },
                    cache: false,
                    async: false,
                    success: function(data) {
                        
                        if(data.status == true){
                            $('#urlEvento').val(data.tituloNovo);                         
                        }else{
                            //console.dir("erro");
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.responseText);
                    },
                    dataType: 'json'
                });
                
                //verificaLinkNome();
            }
        }

	</script>