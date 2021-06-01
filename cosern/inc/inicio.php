<?	if( $_SESSION['campanha']->script1!='' ){ ?>
        <div class="row jump-20">
			<div class="col-md-12">
				<?=$_SESSION['campanha']->script1?>
			</div>
		</div>
		<br /><br />
<?	} ?>

<?
	include_once('inc/bar-destaques.php');
?>


<?
	$param = '';
	/*if(!$active){
		$param = "AND restrito='2' ";
	}*/

	$regs = executaSQLPadrao('noticia', "tipo = 1 AND id_evento = '".$_SESSION['campanha']->id."' ".$param." ORDER BY data DESC LIMIT 4");
	if(nLinhas($regs)>0){
?>
        <h3><?=$translate->translate('noticias')?></h3>
       
<?		while($reg = objetoPHP($regs)){ ?>

            <div class="row noticia-block">

			<?	if( is_file($reg->image_dir_thumb) ){ ?>
			
                <div class="col-xs-3 col-sm-4 col-md-3 col-lg-2">
                    <img src="<?=is_file($reg->image_dir_thumb) ? $reg->image_dir_thumb : '/images/logo.png'?>" class="img-responsive <?=!is_file($reg->image_dir_thumb) ? ' no-image' : ''?>">
                </div>
                <div class="col-xs-9 col-sm-8 col-md-9 col-lg-10">

					<div>
						<span class="subtitle"><?=$translate->translate('publicada_em_')." ".converte_data($reg->data)?></span>
					</div>

                    
                    <div class="content"><?=($reg->mostrar==2)?$reg->noticia :strip_tags(resumo($reg->noticia))?></div>
					
                    <a href="/<?=$_SESSION['campanha']->url_padrao?>/noticias/visualizar/<?=$reg->id?>" class="saiba-mais"><?=$translate->translate('leia_mais')?></a>
                </div>
            <? }else{ ?>
				<div class="col-md-12">

					<div>
						<span class="subtitle"><?=$translate->translate('publicada_em_')." ".converte_data($reg->data)?></span>
					</div>

                    
                    <a href="/<?=$_SESSION['campanha']->url_padrao?>/noticias/visualizar/<?=$reg->id?>" class="title">
                        <?=$reg->titulo?>
                    </a>
					
                    <div class="content"><?=($reg->mostrar==2)?$reg->noticia :strip_tags(resumo($reg->noticia))?></div>
					
                    <a href="/<?=$_SESSION['campanha']->url_padrao?>/noticias/visualizar/<?=$reg->id?>" class="saiba-mais"><?=$translate->translate('leia_mais')?></a>
                </div>
			<?	} ?>
			</div>
            <div class="row">
                <hr class="noticia-hr" />
            </div>
	<?	} ?>
    
		<a href="/<?=$_SESSION['campanha']->url_padrao?>/noticias/listar" class="leia-mais"><?=$translate->translate('todas_noticias')?></a>
		<div class="clear"></div>

<?	}

