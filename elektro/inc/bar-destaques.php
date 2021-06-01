<?
    $param = "AND id_evento='".$_SESSION['campanha']->id."' ";
    
    $regs = executaSQLPadrao('noticia', "tipo=2 ".$param." ORDER BY data DESC LIMIT 4");
    if(nLinhas($regs)>0){
?>
        <div class="row jumper-30">
            <div class="col-md-12">
                <div id="owl-slider" class="owl-carousel home">
                <?
                    while($reg = objetoPHP($regs)){
                        if( is_file($reg->image_dir) ){
                            $image = '/'.$reg->image_dir;
                            
                            $dimensoes = getimagesize($reg->image_dir);
                            $altura = $dimensoes[1];
                        
                        }else{
                            $image = '/images/logo.png';
                            $altura = '500px';
                        }
                ?>
                        <a href="<?=$reg->link_url?>" target="_blank" class="item-slider" style="background: url(<?=$image?>) center center no-repeat; background-size: contain; height: 500px"></a>
                <?
                    }
                ?>
                </div>
                <div class="owl-buttons">
                    <a class="owl-prev left"><img src="/images/icon-banner-esquerda.png"></a>
                    <a class="owl-next right"><img src="/images/icon-banner-direita.png"></a>
                </div>
                
                <link rel="stylesheet" href="/js/owl-carousel/owl.carousel.css">
                <link rel="stylesheet" href="/js/owl-carousel/owl.theme.css">
                <script src="/js/owl-carousel/owl.carousel.js"></script>
                <script>
                    jQuery(document).ready(function ($) {
                        var owl = $("#owl-slider");
                        
                        owl.owlCarousel({
                            autoPlay : true,//5000,
                            stopOnHover : true,
                            navigation:false,
                            paginationSpeed : 1000,
                            goToFirstSpeed : 2000,
                            singleItem : true,
                            autoHeight : true,
                            transitionStyle:"fade"
                        });
                        
                        $(".owl-next").click(function(){
                            owl.trigger('owl.next');
                        })
                        $(".owl-prev").click(function(){
                            owl.trigger('owl.prev');
                        })
                        
                        $( window ).resize(function(){
                            //console.dir('entrou');
                            fixHomeBannerBlockTitle();
                        });
                        
                        fixHomeBannerBlockTitle()
                    });
                        
                    function fixHomeBannerBlockTitle(){
                        $('#owl-slider .titulo').each(function(){
                            //console.dir($(this).closest('.home-block').width() + ' - ' + $(this).width());
                            $(this).css('width', $('#owl-slider').width()+'px');
                        });
                    }
                </script>
            </div>
        </div>
<?
    }
?>