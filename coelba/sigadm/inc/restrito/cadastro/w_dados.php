	<?
		if($_GET['id']>0){
		
			$_SESSION["pessoaCorrenteIdRestrito"]   = $_SESSION['pessoaId'];

		}
		
		$mesmoIrmao = true;
	?>
    
	<script>
		//SE LOCALIZA A HASHTAG NA URL MANTEM A PÁGINA NO TOPO SEM REALIZAR A ÂNCORA
		if (location.hash) {
			window.scrollTo(0, 0);
			setTimeout(function() {
				window.scrollTo(0, 0);
			}, 1);
		}		
    </script>
	
    <h3 class="page-title">
    	<?=($mesmoIrmao ? $translate->translate('meus_dados') : $translate->translate('visualizar_dados_irmao'))?>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
        
        <?	if($mesmoIrmao){?>
        		
                <li>
					<?=$translate->translate('minhas_informacoes')?>
                    <i class="fa fa-angle-right"></i>
                </li> 
                <li>
					<?=$translate->translate('meus_dados')?>
                </li>
                
        <?	}else{?>
    			
                <li>
					<?=$translate->translate('visualizar_dados_irmao')?>
                </li> 
                
        <?	}?>
            
        </ul>
    </div>    
    
<?	
	$pessoa = consultaPessoaById($_SESSION["pessoaCorrenteIdRestrito"]);	
	mostrarMensagem();
?>
    
    <div class="portlet box blue spacer-20">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i> <?=$pessoa->nome?>
            </div>
            <div class="tools">
                &nbsp;
            </div>
        </div>
        <div class="portlet-body">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#foto" data-toggle="tab"><?=$translate->translate('foto')?></a>
                </li>
                
                <li>
                    <a href="#pessoal" data-toggle="tab"><?=$translate->translate('pessoal')?></a>
                </li>
            </ul>
            
            <div class="tab-content">
               
                <div class="tab-pane fade active in" id="foto">
                    <? include_once("w_foto.php"); ?>
                </div>
                
                <div class="tab-pane fade" id="pessoal">
                <?  include_once("w_pessoal.php"); ?>
                </div>
                
            </div>
        </div>
    </div>