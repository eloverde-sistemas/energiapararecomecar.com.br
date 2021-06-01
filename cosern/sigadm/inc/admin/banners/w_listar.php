
	<h3 class="page-title">
        <?=$translate->translate('banners')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('banners')?>
            </li>
		</ul>
    </div>
   
   	 <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate('filtro_avancado')?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
 			
            <form id="form-filtro" action="/adm/admin/banners/listar" method="post">
                   
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                        <label for="contato_nome"><?=$translate->translate("titulo")?> </label>
                        <input type="text" name="contato_nome" class="form-control campoFiltro" id="contato_nome"  value="">
                        </div>
                    </div>
                               	
                    <div class="col-md-4">
                        <div class="form-group">
	                        <label for="posicao"><?=$translate->translate("posicao")?></label>
	                        <select id="posicao" name="posicao" class="form-control campoFiltro" >
	                            <option value=""><?=$translate->translate("sel_posicao")?></option>
	                            <?
	                                $exePos = executaSQL("SELECT * FROM banner_posicao WHERE 1=1 ORDER BY ordem");
	                                if(nLinhas($exePos)>0){
	                                
	                                    while($posicao = objetoPHP($exePos)){ ?>
	                                            
	                                        <option value="<?=$posicao->id?>" <?=($_POST['posicao'] == $posicao->id) ? 'selected' : '' ?> > <?=$posicao->largura?>x<?=$posicao->altura?> - <?=$posicao->titulo?> </option>	
	                                            
	                            <?		}
	                                }
	                            ?>
	                            
	                        </select>
                      		
						</div>
                    </div>

                    <div class="col-md-4">
						<div class="form-group">
							<label for="evento"><?=$translate->translate("evento")?></label>
							<select id="evento" class="form-control campoFiltro" name="evento">
								<option value=""><?=$translate->translate("sel_evento")?></option>
								<?
									$exeEv = executaSQL("SELECT * FROM evento ORDER BY dt_inicio DESC");
									if(nLinhas($exeEv)>0){
									
										while($evento = objetoPHP($exeEv)){ ?>
												
											<option value="<?=$evento->id?>" > <?=$evento->titulo?></option>	
												
								<?		}
									}
								?>
								
							 </select>
												
						</div>
					</div>
                </div>
                
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/adm/admin/banners/listar'"><?=$translate->translate('bt_limpar')?></button>
                </div>
        	
           		<div class="clear"></div>
                
        	</form>
            
        </div>
    </div>
			
	<div class="row text-center jumper-20">
		<a href="/adm/admin/banners/editar" class="icon-btn">
			<i class="fa fa-plus"></i>
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
						<th><?=$translate->translate("posicao_tamanho_imagem")?></th>
						<th><?=$translate->translate("evento")?></th>
						<? if( temPermissao("ADMIN_PUBLICIDADE_ALTERAR") ){ ?>
								<th class="coluna-acao">&nbsp;</th>
						<? }?>
						<? if( temPermissao("ADMIN_PUBLICIDADE_EXCLUIR") ){ ?>
								<th class="coluna-acao">&nbsp;</th>
						<? }?>
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
					"url": "paginacao.php?page=admin/banners/paginacao.php",
					"type": "POST",
					"data": function ( d ) {
						//VALORES DOS FILTROS
						d.filterValue = [
										$('#contato_nome').val(),
										$('#posicao').val(),
										$('#evento').val()
								  ];
						//REGEX DA CONSULTA, EXEMPLO "= LIKE > <"
						d.filterRegex = [
										"LIKEALL",
										"=",
										"="
								  ];
					}
				},
				"columns": [
					{ "data": "contato_nome",		 		"orderable": true },
					{ "data": "posicao_tamanho_imagem", 	"orderable": false },
					{ "data": "campanha", 					"orderable": false },
					{ "data": "btn_alterar", 				"orderable": false },
					{ "data": "btn_excluir", 				"orderable": false }
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
		
    </script>