
	<h3 class="page-title">
        <?=$translate->translate('tt_usuarios')?> <small><?=$translate->translate('listagem')?></small>
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
                <?=$translate->translate('tt_usuarios')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form-filtro" action="/adm/admin/usuarios/listar" method="post">
                   
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome"><?=$translate->translate("nome")?></label>
                            <input type="text" name="nome" id="nome" class="form-control campoFiltro" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="situacao">
								<?=$translate->translate("situacao")?>
							</label>
		
							<select id="situacao" name="situacao" class="form-control campoFiltro">
								<option value=""><?=$translate->translate("sel_situacao")?></option>
								<?
									$situacoes = executaSQLPadrao("pessoa_situacao", " 1=1 ORDER BY id");
									
									while( $situacao = objetoPHP( $situacoes )){   ?>
										<option value="<?=$situacao->id?>"><?=$translate->translate("pessoa_situacao_".$situacao->id)?></option>
								<?	
									}
								?>
								
							</select>
                      		
						</div>
                    </div>
                </div>
                      
                <div class="row">                  	
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="perfil">
								<?=$translate->translate("perfil")?>
							</label>
		
							<select id="perfil" name="perfil" class="form-control campoFiltro">
								<option value=""><?=$translate->translate("sel_perfil")?></option>
									<?php
										$perfis = executaSQLPadrao("perfil", "id!=-99");
										
										while( $perfil = objetoPHP( $perfis )){
									?>
											<option value="<?=$perfil->id?>"><?=$perfil->nome?></option>
									<? 	}?>
							</select>
                      		
						</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo_membro">
								<?=$translate->translate("tipo_membro")?>
							</label>
		
							<select id="tipo_membro" name="tipo_membro" class="form-control campoFiltro">
								<option value=""><?=$translate->translate("sel_tipo_membro")?></option>
								<?
									$tipos = executaSQLPadrao("pessoa_tipo", "id!=4");
									
									while( $tipo = objetoPHP( $tipos )){ ?>
										<option value="<?=$tipo->id?>"><?=$translate->translate("tipo_pessoa_".$tipo->id)?></option>
								<?	} ?>
							</select>
                      		
						</div>
                    </div>
                </div>
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/usuarios/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
    </div>
			
	<div class="row text-center jumper-20">
	<? 	if( temPermissao("ADMIN_USUARIOS_INSERIR") ){ ?>
        	<a href="/adm/admin/usuarios/editar" class="icon-btn">
				<i class="fa fa-plus"></i>
				<div> <?=$translate->translate('novo_usuario')?> </div>
			</a>
	<?	} ?>
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
						<th><?=$translate->translate("nome")?></th>
						<th><?=$translate->translate("situacao")?></th>
						<th><?=$translate->translate("perfil")?></th>
						<th class="coluna-acao">&nbsp;</th>
						<th class="coluna-acao">&nbsp;</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
    
  	<script>
		$(document).ready(function() {
			
			$('#list').dataTable( {
				"filter": false,
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao.php?page=admin/usuarios/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$('#nome').val(),
										$('#situacao').val(),
										$('#perfil').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"LIKEALL",
										"=",
										""
								  ];
					}
				},
				"columns": [
					{ "data": "nome",		"orderable":true },
					{ "data": "situacao",	"orderable": true, "class":"idSituacao" },
					{ "data": "perfil",		"orderable": true },
					{ "data": "btn_alterar","orderable": false <?=temPermissao(array("ADMIN_USUARIOS_ALTERAR")) ? NULL : ', "class":"hide"'?>},
					{ "data": "btn_excluir","orderable": false <?=temPermissao(array("ADMIN_USUARIOS_EXCLUIR")) ? NULL : ', "class":"hide"'?>}
				],
				"order": [[ 0, "asc" ]],
				"fnDrawCallback": function( oSettings ) {
					colocaClasseTR(".idSituacao", "danger", 2);
				}
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});		
			
			$(".campoFiltro").click(function(){
				table.draw();
			});
			
		});
		
    </script>