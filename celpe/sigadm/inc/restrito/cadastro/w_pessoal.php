<?
	$id			= $_SESSION['pessoaCorrenteIdRestrito']; 
	
	if( $_POST['acao_irmao']=='pessoal' ){
	
		if($mesmoIrmao){
		
			$dados = array();
			
			$dados['nome']				= mb_strtoupper($_POST['nome']);
			
			$dados['email']	= $_POST['email'];

			$exePessoa = alterarDados('pessoa', $dados, "id = '".$id."'");
			
			if( $exePessoa )
				setarMensagem(array($translate->translate('msg_salvo_com_sucesso')), "success");
				
			else	
				setarMensagem(array($translate->translate('msg_salvo_com_erro')), "error");
		
		}
		
		header("Location: /adm/restrito/cadastro/dados#pessoal");
		exit;
	}
?>
    
	<form method="post" id="cadastro-pessoal" action="/adm/restrito/cadastro/dados">
    	<input type="hidden" name="acao_irmao" id="acao_irmao" value="pessoal">
        <input type="hidden" name="idCorrente" id="idCorrente" value="<?=$_SESSION['pessoaCorrenteIdRestrito']?>">
        
    	
                	
            <div class="portlet box grey-cascade">
                <div class="portlet-title">
                    <div class="caption"><?=$translate->translate('dados_pessoais')?></div>
                    <div class="tools"></div>
                </div>
                <div class="portlet-body">
                
                    <div class="form-body">
                        
                        <div class="row">
                        
                            <div class="col-md-8">
                                <div class="form-group">                                        	
                                    <label for="nome"><?=$translate->translate('nome')?></label>
                                    <input type="text" name="nome" id="nome" class="form-control" value="<?=$pessoa->nome?>" >                                            
                                </div>
                            </div>                                
                            
                            <div class="col-md-4">
                                <div class="form-group">                                        	
                                    <label for="email"><?=$translate->translate('email_principal')?></label>
                                    <input type="text" name="email" id="email" class="form-control" value="<?=$pessoa->email?>" >
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        
        <?	if($mesmoIrmao){?>
                <div class="form-actions left">
                    <button type="button" class="btn default" onclick="window.location='/restrito/cadastro/dados#pessoal'"><?=$translate->translate('bt_cancelar')?></button>
                    <button type="submit" class="btn green"><i class="fa fa-check"></i> <?=$translate->translate('bt_salvar')?></button>
                </div>
		<?	}?>		
        
            <div class="clear"></div>
        
	</form>
    
    <div class="clear"></div>
    
    
	<script>

    	$(function(){

    	});
    </script>