<?
	$id = intval($_GET['id']);
	
	if( $id > 0 ){
		$exe = executaSQLPadrao("loja_segmento", "id = '".$id."'");
		if(!nLinhas($exe)>0){
			header("Location: /adm/admin/segmentos/listar");
			die();
		}else{
			$reg = objetoPHP($exe);
		}
	}
?>
	<script>
        jQuery(document).ready(function() {
            $("#form").validate();
			
			$("#form").submit(function(){
				if( $("#form").valid() ){
					return validaCargo();
				}
			});
			
			$("#nome").blur(function(){
				return validaCargo();		
			});
			
		});
			
		validaNome = function(){
			var nome = $("#nome").val();
			var passou;

			if(nome!='' && nome!=null){
				
				$.ajax({
					url: 'inc/genericoJSON.php',
					type: 'post',
					data: { 
						acao: 'validaNomeSegmento',
						nome: nome,
						id: '<?=$id?>'
					},
					cache: false,
					async: false,
					success: function(data) {
						if(data.status==false){
							$("#alertCargo").removeClass('hide');
							passou=false;
						}else{
							$("#alertCargo").addClass('hide');
							passou=true;								
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.responseText);
					},
					dataType: 'json'
				});				
				return passou;
			}
		}
			
    </script>

	<h3 class="page-title">
    	<?=$translate->translate('segmentos_lojistas')?> <small><?=( $id > 0 )? $translate->translate("ttl_editar") : $translate->translate("tt_adicionar")?></small>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('lojas')?>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('segmentos')?>
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
							if($id > 0){
								$params[] = $translate->translate("ttl_editar");
							}else{
								$params[] = $translate->translate("tt_adicionar");
							}
							echo traducaoParams($translate->translate('_segmentos_lojistas'), $params);
						?>
                        </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form action="/adm/admin/segmentos/editar" method="post" id="form" class="horizontal-form">
                    	<input type="hidden" name="id" value="<?=$id?>">
                        
                        <div class="form-body">
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="nome"><?=$translate->translate('nome')?></label>
                                        <input type="text" name="nome" id="nome" class="form-control required" value="<?=$reg->nome?>">
                                        <span id="alertCargo" class="hide msgValidate"><?=$translate->translate('msg_segmento_lojista_ja_existe')?></span>
                                    </div>
                                </div>                                
                            </div>
                        
                        </div>
                        <div class="form-actions left">
                            <button type="button" class="btn default" onclick="window.location='/adm/admin/segmentos/listar'"><?=$translate->translate('bt_cancelar')?></button>
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                        </div>
                    </form>
                    <!-- END FORM-->
                    <div class="clear"></div>
                </div>
            </div>
		</div>
	</div>    

<?
	if($_POST){

		if($_POST['nome'] == ''){
			setarMensagem(array($translate->translate("msg_campos_obrigatorios")), "error");
			header("Location: /adm/admin/segmentos/editar/".$id);
		}else{
			
			$id 	= intval($_POST['id']);			
			
			$dados=array();			
			$dados['nome'] 			= trim($_POST['nome']);
			
			if($id>0){
				$exe = alterarDados("loja_segmento", $dados, "id='".$id."'");
			}else{
				$dados['id'] = $id = proximoId("loja_segmento");
				$exe = inserirDados("loja_segmento", $dados);
			}
			
			if($exe){
				setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success"); 	
				header("Location: /adm/admin/segmentos/listar");
			}else{
				setarMensagem(array($translate->translate("msg_salvo_com_erro")), "danger"); 	
				header("Location: /adm/admin/segmentos/editar/".$id);				
			}
		}
	}
?>    
    