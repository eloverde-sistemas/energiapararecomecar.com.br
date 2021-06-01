<?
	$idEvento = (intval($_POST['evento']) > 0 ? intval($_POST['evento']) : getEventoAtual()->id );
?>
	<h3 class="page-title">
        <?=$translate->translate('menu')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('menu')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form" action="/adm/admin/menu/listar" method="post" class="horizontal-form">

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
										<option value="<?=$evento->id?>" <?=($idEvento == $evento->id ? 'selected' : '')?> > <?=$evento->titulo?></option>
							<?		}
							
								}
							?>
								
							</select>
						</div>
					</div>

	            </div>

	            <div class="form-actions left">
	                <button type="button" class="btn default" onclick="window.location='/adm/admin/menu/listar'"><?=$translate->translate('bt_limpar')?></button>
	                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_filtrar')?></button>
	            </div>

			</form>

       		<div class="clear"></div>
            
        </div>
    </div>
    
	<div class="row text-center jumper-20">
        <a href="/adm/admin/menu/editar" class="icon-btn">
            <i class="fa fa-plus" alt="<?=$translate->translate('bt_novo')?>" title="<?=$translate->translate('bt_novo')?>"></i>
            <div> <?=$translate->translate('bt_novo')?> </div>
        </a>
    </div>

	<div class="portlet box grey-cascade">
		<div class="portlet-title">
			<div class="caption"></div>
			<div class="tools"></div>
		</div>
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="list">
				<thead>
				
					<tr>
        				<th><?=$translate->translate("titulo")?></th>
        				<th><?=$translate->translate("tipo")?></th>        				
					<? 	if( temPermissao("ADMIN_MENU_ALTERAR") ){ ?>
							<th class="coluna-acao">&nbsp;</th>
					<? 	}?>
					<? 	if( temPermissao("ADMIN_MENU_EXCLUIR") ){ ?>
							<th class="coluna-acao">&nbsp;</th>
							<th class="coluna-acao">&nbsp;</th>
					<? 	}?>
					</tr>
        
				</thead>
				<tbody>
					
				<?
					$exe = executaSQL("SELECT * FROM menu WHERE id_evento = '".$idEvento."' ORDER BY ordem");
					if(nLinhas($exe)>0){
						while($reg = objetoPHP($exe)){

				?>
							<tr class="<?=($reg->ativo==2 ? 'warning' : '')?>">
								<td><?=$reg->titulo?></td>
								<td><?=$translate->translate("tipo_menu_".$reg->id_tipo)?></td>
								<td>
									<a href="/adm/admin/menu/editar/<?=$reg->id?>" class="btn btn-sm green">
										<i class="fa fa-edit"></i> <?=$translate->translate("editar")?>
									</a>
								</td>
								<td>
								<?	if($reg->ativo == 1){ ?>
										<a href="javascript:void(0);" onclick="bootbox.confirm('<?=$translate->translate("msg_inativar")?>', function(result){ if(result){ window.location.href='/adm/admin/menu/inativar/<?=$reg->id?>';} })" class="btn btn-sm yellow">
											<i class="fa fa-ban"></i> <?=$translate->translate("inativar")?>
										</a>
								<?	}else{?>
										<a href="/adm/admin/menu/inativar/<?=$reg->id?>" class="btn btn-sm green">
											<i class="fa fa-check"></i> <?=$translate->translate("ativar")?>
										</a>
								<?	}?>
								</td>								
								<td>
								<?	if($reg->id_menu_padrao == 0){ ?>										
										<a href="javascript:void(0);" onclick="bootbox.confirm('<?=$translate->translate("msg_excluir")?>', function(result){ if(result){ window.location.href='/adm/admin/menu/excluir/<?=$reg->id?>';} })" class="btn btn-sm red">
											<i class="fa fa-times"></i> <?=$translate->translate("excluir")?>
										</a>
								<?	} ?>
								</td>
							</tr>

				<?		}
					}else{ ?>
						
						<tr>
							<td colspan="5"><?=$translate->translate("msg_nenhum_menu_encontrado_evento")?></td>
						</tr>

				<?	} ?>

				</tbody>			   
			</table>
		</div>
	</div>
    
  	<script>
		$(funtion(){


		});
    </script>