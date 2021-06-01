<?
    if($_POST){
        
        $id = intval($_POST['id']);

        foreach( $_POST['titulo'] as $key => $titulo) {
            $dados = array( "sorteio_data"      => converte_data($_POST['data'][$key]),
                            "qtde_premio"       => $_POST['qtde'][$key],
                            "descricao"         => $_POST['descricao'][$key],
                            "titulo"            => $titulo
                        );

            if($_POST['idPremio'][$key]>0){
            
                if($_POST['excluir'][$key]>0){
                    excluirDados("evento_sorteio", "id = '".$_POST['idPremio'][$key]."'");

                }else{
                    alterarDados("evento_sorteio", $dados, "id = '".$_POST['idPremio'][$key]."'");
                }
            
            }else{
                $dados['id_evento'] = $_POST['id'];
                inserirDados("evento_sorteio", $dados, true);
            }
                
        }
        
        setarMensagem(array($translate->translate("msg_salvo_com_sucesso")), "success");
        header("Location: /adm/admin/eventos/listar");

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
    	<?=$translate->translate('eventos')?> <small><?=$translate->translate("premios")?></small>
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
				<?=$translate->translate("premios")?>
            </li>
        </ul>
    </div>
    
    <form id="form" action="/adm/admin/eventos/premios" method="post" enctype="multipart/form-data">
        
        <input type="hidden" name="id" value="<?=$id?>">
        
		<div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">
                    <? 	$params=array();
                        $params[] = $translate->translate("gerenciar");
                    	echo traducaoParams($translate->translate('_premios'), $params);
                    ?>
                    </span>
                </div>
            </div>
            
            <div class="portlet-body form">
            <?
                $totalPremios = 0;
                $premios = executaSQLPadrao("evento_sorteio", "id_evento='".$id."'");
                while( $premio=objetoPHP($premios) ){
                    $totalPremios++;
            ?>
			
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">Prêmio</div>
							<div class="tools"></div>
						</div>
						<div class="portlet-body">

							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label for="titulo-<?=$totalPremios?>"><?=$translate->translate("titulo")?></label>
										<input type="text" name="titulo[<?=$totalPremios?>]" id="titulo-<?=$totalPremios?>" class="form-control required" value="<?=$premio->titulo?>" />
										<input type="hidden" name="idPremio[<?=$totalPremios?>]" value="<?=$premio->id?>">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="data-<?=$totalPremios?>"><?=$translate->translate("data")?></label>
										<input type="text" name="data[<?=$totalPremios?>]" id="data-<?=$totalPremios?>" class="form-control data date-picker required valid" value="<?=converte_data($premio->sorteio_data)?>" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="qtde-<?=$totalPremios?>"><?=$translate->translate("qtde_premio")?></label>
										<input type="text" name="qtde[<?=$totalPremios?>]" id="qtde-<?=$totalPremios?>" class="form-control required numero" value="<?=$premio->qtde_premio?>" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="block"> &nbsp; </label>
										<label for="excluir-<?=$totalPremios?>">
											<input type="checkbox" name="excluir[<?=$totalPremios?>]" id="excluir-<?=$totalPremios?>" class="form-control" value="<?=$premio->id?>" />
											<?=$translate->translate("excluir")?>
										</label>
									</div>
								</div>
							</div>
		
							<div class="row">
								<div class="col-md-12">
									<label for="descricao-<?=$totalPremios?>"><?=$translate->translate("descricao")?></label>
									<textarea name="descricao[<?=$totalPremios?>]" id="descricao-<?=$totalPremios?>" class="wysihtml5 form-control" rows="6"><?=$premio->descricao?></textarea>
								</div>
							</div>
						
						</div>
					</div>

            <?
                }
            ?>
                <div id="novosPremios"></div>

                <div class="row jumper-20">
                    <div class="col-md-12">
                        <button type="button" id="addCupom" class="btn blue"><i class="fa fa-plus"></i> <?=$translate->translate('add_premio')?></button>
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

            nCupom = <?=$totalPremios?>;
			$("#addCupom").click(function(){
                nCupom++;
                var c = '';

				c+= '	<div class="portlet box grey-cascade premio">';
				c+= '			<div class="portlet-title">';
				c+= '				<div class="caption">Prêmio</div>';
				c+= '				<div class="tools"></div>';
				c+= '			</div>';
				c+= '			<div class="portlet-body">';
							
                c+= '<div class="row">';
                c+=     '<div class="col-md-5">';
                c+=         '<div class="form-group">';
                c+=         '<label for="titulo-' + nCupom + '"><?=$translate->translate("titulo")?></label>';
                c+=             '<input type="text" name="titulo[' + nCupom + ']" id="titulo-' + nCupom + '" class="form-control required" value="<?=$premio->titulo?>" />';
                c+=         '</div>';
                c+=     '</div>';
                c+=     '<div class="col-md-3">';
                c+=             '<div class="form-group">';
                c+=             '<label for="data-' + nCupom + '"><?=$translate->translate("data")?></label>';
                c+=             '<input type="text" name="data[' + nCupom + ']" id="data-' + nCupom + '" class="form-control data date-picker required valid" value="<?=converte_data($premio->sorteio_data)?>" />';
                c+=         '</div>';
                c+=     '</div>';
                c+=     '<div class="col-md-2">';
                c+=         '<div class="form-group">';
                c+=             '<label for="qtde-' + nCupom + '"><?=$translate->translate("qtde_premio")?></label>';
                c+=             '<input type="text" name="qtde[' + nCupom + ']" id="qtde-' + nCupom + '" class="form-control required numero" value="<?=$premio->qtde_premio?>" />';
                c+=         '</div>';
                c+=     '</div>';
                c+=     '<div class="col-md-2">';
                c+=         '<div class="form-group">';
                c+=             '<label class="block"> &nbsp; </label>';
                c+=             '<label for="excluir-' + nCupom + '">';
                c+=                 '<button type="button" class="btn red excluirPremio"><i class="fa fa-times"></i></button>';
                c+=             '</label>';
                c+=         '</div>';
                c+=     '</div>';
                c+=     '<div class="col-md-12">';
                c+=         '<label for="descricao-' + nCupom + '"><?=$translate->translate("descricao")?></label>';
                c+=         '<textarea name="descricao[' + nCupom + ']" id="descricao-' + nCupom + '" class="wysihtml5 form-control" rows="6"><?=$premio->descricao?></textarea>';
                c+=     '</div>';
                c+= '</div>';

				c+=     '</div>';
                c+= '</div>';

                $('#novosPremios').append(c);
				
				$('#data-'+ nCupom).datepicker();
            });
            
            $( document ).on( "click", ".excluirPremio", function() {
                $(this).closest('.premio').remove();
            });
		});
	</script>