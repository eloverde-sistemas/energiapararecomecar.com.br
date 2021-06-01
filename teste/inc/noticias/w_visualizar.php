<?
        $id = intval($_GET['id']);
        
        $sqlNoticia = "SELECT * FROM noticia WHERE id = '".$id."'";
        
        $exeNoticia = executaSQL($sqlNoticia);
        
        if( nLinhas($exeNoticia)>0 ){
            $noticia = objetoPHP($exeNoticia);
    ?>
                
                <div id="noticiaIndividual">
                    <h2><?=$noticia->titulo?></h2>
                    
                    <p align="justify" style="line-height:1.7em">
                                      
					<?  if( is_file($noticia->image_dir_thumb) ){  ?> 
                            <a href="/<?=$noticia->image_dir?>" class="fancybox-button col-xs-4 col-lg-3 right jumper-10 ml-10" data-rel="fancybox-button">
                            	<img src="/<?=$noticia->image_dir_thumb?>" class="img-responsive right" />
                            </a>
                    <?  } ?>
                         
                        <?=$noticia->noticia?>
                    
                    </p>
                    <br />
                    <? if($noticia->link_url != ''){ ?>	
                    		<a href="<?=$noticia->link_url?>" class="btn btn-sm blue" target="_blank">
                                <i class="fa fa-external-link"></i> <?=($noticia->link_texto =='') ? $translate->translate("clique_aqui_para_ler_mais") : $noticia->link_texto?>
                            </a>
                    <? }?>
                    
                    <? if( is_file($noticia->file_dir) ){ ?> 
                    		<a href="/baixarDocumento.php?id=<?=$id?>&tipo=2" class="btn btn-sm blue" target="_blank" title="<?=$translate->translate("download_arquivo")?>" alt="<?=$translate->translate("download_arquivo")?>">
                                <i class="fa fa-download"></i> <?=$translate->translate("download_arquivo")?>
                            </a>	                    
                    <? } ?>
                    
                    <div class="right">
                        <a href="/<?=$_SESSION['campanha']->url_padrao?>/inicio" class="btn btn-sm default">
                            <i class="fa fa-reply-all"></i> <?=$translate->translate('inicio')?>
                        </a>
					</div>
                    <br clear="all">
                    
                </div>
                <br />
                
                <link type="text/css" rel="stylesheet" href="/sigadm/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css" />
				<script src="/sigadm/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
                <script>
                    $(function(){
                        $(".fancybox-button").fancybox({
							groupAttr: 'data-rel',
							prevEffect: 'none',
							nextEffect: 'none',
							closeBtn: true,
							helpers: {
								title: {
									type: 'inside'
								}
							}
						});
                    });
                </script>
    <?      
        }else{
            echo $translate->translate("msg_sem_registro");
        }		
    ?>