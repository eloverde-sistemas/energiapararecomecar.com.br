    <h3 class="page-title">
        <?=$translate->translate('irmaos')?> <small><?=$translate->translate('listagem')?></small>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('tm_relacoes_interiores')?>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('irmaos')?>				
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <?=$translate->translate('ml_cadastro')?>
            </li>
        </ul>
    </div>

    <form id="form-filtro" action="/adm/paginacao" method="post">
        
        <div class="portlet box grey-cascade">
            <div class="portlet-title">
                <div class="caption">
                    <?=$translate->translate("filtro_avancado")?>
                </div>
                <div class="tools"></div>
            </div>
            <div class="portlet-body">
            
                <div class="form-body">            
                   
                    <div class="row">                            	
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nome"><?=$translate->translate("nome")?></label>
                                <input type="text" name="nome" id="nome" class="form-control campoFiltro" data-id="0" value="<?=$_POST['nome']?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="situacao"><?=$translate->translate("situacao")?></label>
                                <select id="situacao" class="form-control campoFiltro" name="situacao" data-id="2">
                                <option value=""><?=$translate->translate("sel_situacao")?></option>
                            <?
                                $situacoes = executaSQLPadrao("pessoa_situacao", " id NOT IN(1) ORDER BY valor");
                                
                                while( $situacao = objetoPHP( $situacoes )){   ?>
                                    <option value="<?=$situacao->id?>" <?=($situacao->id == $situacaoSel)? 'selected="selected"' : '' ?>><?=$translate->translate("pessoa_situacao_".$situacao->id)?></option>
                            <? } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cim"><?=$translate->translate("cim")?></label>
                                <input type="text" name="cim" id="cim" class="form-control campoFiltro" data-id="1" value="<?=$_POST['cim']?>">	
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="grau"><?=$translate->translate("grau")?></label>
                                <select name="grau" id="grau" class="form-control campoFiltro" data-id="3">
                                    <option value=""><?=$translate->translate("sel_grau")?></option>
                                    <? 
                                        $exe = executaSQL("SELECT * FROM grau ORDER BY id");
                                        if(nLinhas($exe)>0){
                                            while($grau = objetoPHP($exe)){?>
                                                <option value="<?=$grau->id?>" <?=($grau->id==$_POST['grau'])?'selected':''?>><?=$translate->translate("grau_".$grau->id)?></option>
                                    <?		}
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-actions left">
                        <button type="button" class="btn default" onclick="window.location='/adm/paginacao'"><?=$translate->translate("bt_limpar")?></button>                        
                    </div>
                    <div class="clear"></div>
                    
                </div>       
            </div>
        </div>
        
    </form>
	
	<div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption"><?=$translate->translate("irmaos")?></div>
            <div class="tools"></div>
        </div>
        <div class="portlet-body">
    		<table id="example" class="table table-striped table-bordered table-hover" >            	
                <thead>
                    <tr>
                        <th><?=$translate->translate("nome")?></th>
                        <th><?=$translate->translate("cim")?></th>
                        <th><?=$translate->translate("situacao")?></th>
                        <th><?=$translate->translate("grau")?></th>
                        <th class="coluna-acao hidden-print">&nbsp;</th>
                        <?	if( temPermissao("ADMIN_CADASTRO_ALTERAR")){ ?>
                                <th class="coluna-acao hidden-print">&nbsp;</th>
                        <?	} ?>            
                    </tr>
				</thead>               
            </table>
		</div>
	</div>
    
    <script>
		$(document).ready(function() {
			
			$('#example').dataTable( {
				"processing": true,
        		"serverSide": true,
				"ajax": {
					"url": "paginacao/irmaos.php",
					"type": "POST"
				},
				"columns": [
					{ "data": "nome", "name":"like", "orderable": true },
					{ "data": "cim", "name":"equal", "orderable": true },
					{ "data": "situacao", "name":"equal", "orderable": false },
					{ "data": "grau", "name":"equal", "orderable": false },
				<?	if( temPermissao("ADMIN_CADASTRO_ALTERAR")){ ?>
						{ "data": "btn_pdf_ficha", "orderable": false },
						{ "data": "btn_alterar", "orderable": false }
				<?	}else{ ?>
						{ "data": "btn_pdf_ficha", "orderable": false }
				<?	}?>
				],
				"order": [[ 0, "asc" ]]
			});
			
			var table = $('#example').DataTable();
			$( '.campoFiltro' ).on( 'keyup change', function () {
				table
					.column( $(this).attr("data-id") )
					.search( this.value )
					.draw();
			} );
		
			
		});		
    </script>   