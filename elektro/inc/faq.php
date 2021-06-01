	<style>
		.ask {
			cursor: pointer;
			opacity: 0;
		}
		.reply {
			display: none;			
		}
		.action-button {
			display: block;
			height: 17px;
			margin-right: 13px;
			width: 17px;
		}
		.action-button.less{
			background: url(images/icon-menos.png) center center no-repeat;
		}
		.action-button.more {
			background: url(images/icon-mais.png) center center no-repeat;
		}
	</style>

	<script>
		jQuery(document).ready(function ($) {
			
			$('.ask').click(function(){
				num = $(this).attr('rel');
				
				if($('.reply[rel="'+num+'"]').css('display')=='none'){
					//Mostra a caixa da resposta
					$('.reply[rel="'+num+'"]').show('slow');
					//Mostra o conteudo
					$('.reply[rel="'+num+'"]').animate({
							opacity: .9,
							'margin-top': 10
						}, 500);
					//Muda o ícone
					$('.ask[rel="'+num+'"] span').animate({opacity: 0}, 'slow', function() {
						$(this).toggleClass('more, less').animate({opacity: 1});
					});
				}else{
					//console.dir('esconde');
					$('.reply[rel="'+num+'"]').animate({
							opacity: 0,
							'margin-top': 0
						}, 500, function(){
								$('.reply[rel="'+num+'"]').hide('slow');
							});
					
					//Muda o ícone
					$('.ask[rel="'+num+'"] span').animate({opacity: 0}, 'slow', function() {
						$(this).toggleClass('more, less').animate({opacity: 1});
					});
				}
				
				
			});
			
			$('.ask').each(function(){
				$(this).animate({
					opacity: 0.9
				}, 800);
			});
			
		});
	</script>	

<?
	$exe = executaSQL("SELECT * FROM perguntas_respostas WHERE id_evento='".$_SESSION['campanha']->id."' AND ativo=1 ORDER BY ordem");
	if(nLinhas($exe)>0){
?>


		<div class="container">
			
		<?
			while($reg=objetoPHP($exe)){
		?>
				
				<div class="item">
		            <div class="ask pointer spacer-20" rel="<?=$reg->id?>">
		                <span class="action-button more left"></span> <span class="saiba_mais"><?=mb_strtoupper($reg->pergunta)?></span>
		            </div>
		            <div class="reply ml-25 mr-25 justify clear" rel='<?=$reg->id?>'>
		                <?=$reg->resposta?>
		            </div>
		        </div>

		<?
			}
		?>

	        
	        
		</div>

<?
	}
?>