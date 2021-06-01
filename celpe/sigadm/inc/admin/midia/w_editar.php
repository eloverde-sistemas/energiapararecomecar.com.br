<?php 
	$id = intval($_GET['id']);
	
	if( $id > 0 ) {
		$exeMidia = executaSQLPadrao("midia", "id = '".$id."'");
		if(nLinhas($exeMidia)>0){
			$midia = objetoPHP($exeMidia);			
		}else{
			header("Location: /adm/admin/midia/listar");
		}
	}
?>
    
	<h3 class="page-title">
        <?=$translate->translate('midias')?> <small><?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?></small>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/midia/listar"><?=$translate->translate('midias')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?= ( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar"); ?>
            </li>
        </ul>
    </div>

	<form id="form" method="post" action="/adm/admin/midia/editar" enctype="multipart/form-data">
    
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
                            echo traducaoParams($translate->translate('_midia'), $params);								
                        ?>
                    </span>
                </div>
            </div>
            <div class="portlet-body form">
      			
                <div class="form-body">

        		<? 	if($id > 0){
						$imgTypes = array('jpg','jpeg','gif','png');?>
        
                        <input type="hidden" name="id" id="id" value="<?=$midia->id?>"> 
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo"><?=$translate->translate('titulo')?></label>
                                    <input type="text" name="titulo" id="titulo" class="form-control" value="<?=$midia->titulo?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="image_dir"><?=$translate->translate('novo_arquivo')?></label>
                                    <input type="file" name="image_dir" id="image_dir">
                                </div>
                            </div>
                            
                    <?  if(is_file("../".$midia->caminho)){ ?>
						
                        	<div class="col-md-6">
                            	<div class="form-group">
                        			<label for=""><?=$translate->translate('arquivo_atual')?></label><br />

					<?		if( in_array($midia->formato, $imgTypes) ){//IMAGEM	?>
                                
                                <a href="<?='/'.$midia->caminho?>" class="jumper-20 fancybox-button" data-rel="fancybox-button">
                                    <img src="<?='/'.$midia->caminho?>" height="100">
                                </a>                                    
                                
                    <?		}else{ ?>									
                                
                                <a class="btn btn-sm blue" href="/baixarDocumento.php?tipo=22&id=<?=$id?>" target="_blank">
                                    <i class="fa fa-download"></i> <?=$translate->translate("download_arquivo")?>
                                </a>
                                    
					<?		} ?>
                    		
                            	</div>
                            </div>
                    
					<?	} ?>
                        
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descricao"><?=$translate->translate('descricao')?></label>
                                    <textarea name="descricao" id="descricao" class="form-control" rows="5"><?=$midia->descricao?></textarea>
                                </div>
                            </div>
                        </div>
        
				<? 	}else{ ?>
						

        				<div class="row text-center">
                            <a href="javascript:void(0);" id="add_foto" class="btn btn-sm blue" title="<?=$translate->translate("adicionar_midia")?>" alt="<?=$translate->translate("adicionar_midia")?>">
                                <i class="fa fa-plus"></i> <?=$translate->translate("adicionar_midia")?>
                            </a>		                        	
                		</div>

                        <div id="galeria_hist"></div>

        		<? 	}?>
        			
                    <div class="clear">&nbsp;</div>
                    
                    <div class="form-actions left">
                        <button type="button" class="btn red" onclick="window.location='/adm/admin/midia/listar'"> <?=$translate->translate('bt_cancelar')?></button>
                        <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                    </div>
                    
                    <div class="clear"></div>
                    
        		</div>
            </div>
        </div>
    </form>

    <script>
		$(function() {
			
			var x = 0;
			var variavel;
			
			$("#form").validate();
			
			$('#add_foto').click(function(){
				x++;							
				variavel  =		'<div class="row" id="row-'+x+'">';
				variavel += 			'<hr class="clear">';
				variavel += 		'<div class="col-md-3">';
				variavel += 			'<div class="form-group">';
				variavel += 				'<label for="foto'+x+'"><?=$translate->translate('titulo')?></label>';
				variavel += 				'<input type="file" name="foto'+x+'" id="foto' + x +'" class="required">';
				variavel += 			'</div>';
				variavel += 		'</div>';
				
				variavel += 		'<div class="col-md-4">';
				variavel += 			'<div class="form-group">';
				variavel += 				'<label for="titulo'+x+'"><?=$translate->translate('titulo')?></label>';
				variavel += 				'<input type="text" name="titulo[]" id="titulo' + x +'" class="form-control required" />';
				variavel += 			'</div>';
				variavel += 		'</div>';
				
				variavel += 		'<div class="col-md-4">';
				variavel += 			'<div class="form-group">';
				variavel += 				'<label for="descricao' + x +'"><?=$translate->translate('descricao')?></label>';
				variavel += 				'<input type="text" name="descricao[]" id="descricao' + x +'" class="form-control" />';
				variavel += 			'</div>';
				variavel += 		'</div>';
				
				variavel += 		'<div class="col-md-1">';
				variavel +=				'<div class="form-group">';
				variavel +=					'<br /><a href="javascript:void(0);" data-number="' + x + '" class="btn btn-sm red excluir-midia" title="<?=$translate->translate("ttl_excluir")?>" alt="<?=$translate->translate("ttl_excluir")?>">';
                variavel +=        				'<i class="fa fa-times"></i>';
                variavel +=					'</a>';
				variavel += 			'</div>';
				variavel += 		'</div>';
				variavel += 	'</div>';
				
				$('#galeria_hist').append(variavel);
				
				$('.excluir-midia').click(function(){
					number = $(this).attr('data-number');
					$('#row-' + number).remove();
				});
				
			});
			
		});		
	</script>
    
<?
	if($_POST){
		
		$id = intval($_POST['id']);
		
		require('../lib/WideImage/WideImage.php');
		
		if($id>0){//ALTERAÇÃO
			
			$midia = objetoPHP(executaSQL("SELECT * FROM midia WHERE id='".$id."'"));
			
			$imagemAnterior = $midia->caminho;
			
			$dados=array();
			
			if( $_FILES['image_dir']['tmp_name'] != '' ){
				
				$ext = strtolower(end(explode(".",$_FILES['image_dir']['name'])));
				
				if(is_file("../".$imagemAnterior)){					
					unlink("../".$imagemAnterior);
				}
				
				$caminho = criaDiretorios( array("uploads", "midia") ).$id.'.'.$ext;

				$upload = move_uploaded_file( $_FILES['image_dir']['tmp_name'], "../".$caminho );	
				
				if($upload)
					$img = WideImage::load('../'.$caminho)-> resize(800, 600, 'outside', 'down')-> saveToFile('../'.$caminho);
				
				$dados['formato'] = $ext;
	
			}
			
			$dados['titulo'] 			= trim($_POST['titulo']);
			$dados['descricao'] 		= trim($_POST['descricao']);
			$dados['data_alteracao'] 	= date("YmdHis");
			
			if(alterarDados('midia', $dados, 'id = "'.$id.'"')){
				setarMensagem(array("Registro salvo com sucesso!"), "success"); 	
				header("Location: /adm/admin/midia/listar/");	
			}else{
				setarMensagem(array("Erro ao salvar o registro! Erro: "), "danger");
				header("Location: /adm/admin/midia/editar/".$id);
			}
		
		}else{//INSERÇÃO
			
			

			$x=0;
			//Passa por todas as images
			foreach ($_FILES as $key=>$arquivo){					
				
				$nomeArquivo = explode(".", $arquivo['name']);
				$ext 		 = strtolower(end($nomeArquivo));
				
				$titulo 	= $_POST['titulo'][$x];
				$descricao 	= $_POST['descricao'][$x];
				
				//Se tiver arquivo
				if($arquivo['name']!=''){
					
					//Extensões padrões - image
					$imgTypes = array('jpg','jpeg','gif','png');
						
					$id = proximoId("midia");
					
					//Pega o caminho padrão
					$caminho = criaDiretorios( array("uploads", "midia") ).$id.'.'.$ext;
					
					//Grava o arquivo
					if( move_uploaded_file($arquivo['tmp_name'], "../".$caminho) ){
						
						if( in_array($ext, $imgTypes) ){
							$img = WideImage::load('../'.$caminho)->resize(800, 600, 'outside', 'down')-> saveToFile('../'.$caminho);
						}
						
						$dados=array();
						$dados['id'] 				= $id;
						//$dados['id_evento']			= $idEvento;
						$dados['titulo'] 			= trim($titulo);
						$dados['descricao'] 		= trim($descricao);
						$dados['caminho'] 			= $caminho;
						$dados['formato'] 			= $ext;
						$dados['data_cadastro'] 	= date("Ymd");
						inserirDados("midia", $dados);
		
					}
				}
				$x++;		
			}
			
			setarMensagem(array("Registro salvo com sucesso!"), "success");
			header("Location: /adm/admin/midia/listar/");
			

		}
	}
?>