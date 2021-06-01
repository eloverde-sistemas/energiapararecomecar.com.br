
	<h3 class="page-title">
        <?=$translate->translate('permissoes')?> <small><?=$translate->translate('listagem')?></small>
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
                <?=$translate->translate('permissoes')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form-filtro" action="/adm/admin/jornais/listar" method="post">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jornal">
								<?=$translate->translate("perfil")?>
							</label>
    
                        <select id="perfil" name="perfil" class="form-control campoFiltro">
                            <option value=""><?=$translate->translate("sel_perfil")?></option>
                            <?
                                $exe = executaSQL("SELECT * FROM perfil WHERE id>0");
                                if(nLinhas($exe)>0){
                                
                                    while($perfil = objetoPHP($exe)){ ?>
                                            
                                        <option value="<?=$perfil->id?>"> <?=$perfil->nome?> </option>	
                                            
                            <?		}
                                }
                            ?>
                            
                        </select>
                      		
						</div>
                    </div>
					
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="numero">
								<?=$translate->translate("nome")?>
							</label>
                            
							<input type="text" name="nome" id="nome" class="form-control campoFiltro" value="" />
                      		
						</div>
                    </div>
					
                </div>
               
                <div class="form-actions left">
				
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/permissoes/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
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
						<th><?=$translate->translate("perfil")?></th>
					<?	if( temPermissao("ADMIN_PERMISSOES_EXCLUIR") ){ ?>
                            <th class="coluna-acao hidden-print">&nbsp;</th>
                    <?	}?>
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
					"url": "paginacao.php?page=admin/permissoes/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$('#nome').val(),
										$('#perfil').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"LIKEALL",
										"="								
								  ];
					}
				},
				"columns": [
					{ "data": "nome",	 	 "orderable": true },
					{ "data": "perfil", 		 "orderable": true }
				<?	if( temPermissao("ADMIN_PERMISSOES_EXCLUIR") ){ ?>
						,{ "data": "btn_excluir", "orderable": false }
				<?	} ?>
				],
				"order": [[ 0, "asc" ]]
			});
			
			var table = $('#list').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table.draw();
			});		
			
			$(".campoFiltro").click(function(){
				table.draw();
			});
			
		});
		
		
		excluirPermissao = function(idReg){
			
			if( confirm('<?=$translate->translate("msg_excluir")?>') ){			
				if(idReg>0){
					
					$.ajax({
						url: 'inc/genericoJSON.php',
						type: 'post',
						data: { 
							acao	:'excluiPermissaoIrmao',
							idReg	:idReg
						},
						cache: false,
						success: function(data) {
						
							$('html, body').animate( { scrollTop: 0 }, 'slow' );
							msgInfo('', '', 'success', data.mensagem, false, true, false, 5, '');
							window.setTimeout('location.reload()', 2000);
							
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							alert(XMLHttpRequest.responseText);
						},
						dataType: 'json'
					});			
					return false;
				
				}
			}
		}
		
    </script>