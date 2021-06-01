<!-- BEGIN HEADER -->
    <div class="header">
        
        <div class="container">
        
                <div class="row">
                    <div class="col-xs-4 col-md-3">
                        <button class="btn btn-outline-dark mobi-toggler" type="button" data-toggle="collapse" data-target="#collapseMenu" aria-expanded="false" aria-controls="collapseMenu">
                            <span class="fas fa-bars fa-3x" style="padding-left: 10px; padding-right: 10px"></span>
                        </button>    
                    </div>
                </div>

<?          if($_SESSION['campanha']->img_cabecalho!=''){ ?>
                <div class="row">
                    <div class="col-md-12 d-none d-lg-block">
                        <img src="<?=$_SESSION['campanha']->img_cabecalho?>" class="img-fluid mt-3 mx-auto">
                    </div>
               </div>   
<?          } ?>

                <!-- BEGIN NAVIGATION -->
                <div id="collapseMenu" class="header-navigation font-transform-inherit no-margin-top">
                    
                        <div class="row">
                            <div class="col-md-12">
                                <a name="page"></a>
                                <ul class="menu">
                                <?
                                    $menus = consultaMenusCampanha();

                                    if( nLinhas($menus)>0 ){

                                        while( $menu=objetoPHP($menus) ){
                                ?>
                                            <li>
                                                <a href="/<?=$_SESSION['campanha']->url_padrao?>/<?=$menu->url?>" <?=( ($_GET['page']==$_SESSION['campanha']->url_padrao && $menu->id_menu_padrao==1) || $_GET['page']==$menu->url)?'style="text-decoration: underline !important"' :''?>><?=$menu->titulo?></a>
                                            </li>        
                                <?
                                        }

                                    }
                                ?>
                            </div>
                        </div>
                            
                </div>

<?          if($_SESSION['campanha']->img_cabecalho!=''){ ?>
                <div class="row">
                    <div class="col-md-12 d-block d-sm-none">
                        <img src="<?=$_SESSION['campanha']->img_cabecalho?>" class="img-fluid mt-3 mx-auto">
                    </div>
               </div>   
<?          } ?>


        </div>

        
    </div>

        <?
            if($_SESSION['campanha']->img2_cabecalho!=''){
        ?>
    <div class="header col-xs-12 hidden-md hidden-lg">
        <div class="container">

                <div class="row">
                        <img src="<?=$_SESSION['campanha']->img2_cabecalho?>" class="img-responsive center">
                        <br />

               </div>
        </div>
    </div>
        
        <?
            }
        ?>
    
    <div class="container hide">
        <div id="auto-banner"></div>
    </div>
    
<!-- Header END -->