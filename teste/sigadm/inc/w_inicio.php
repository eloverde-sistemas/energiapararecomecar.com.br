    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="javascript:void()"><?=$translate->translate('tm_inicio')?></a>
            </li>
        </ul>
    </div>

    <div class="row">
    
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 hide">
            <div class="dashboard-stat blue-steel">
                <div class="visual">
                    <i class="fa fa-university"></i>
                </div>
                <div class="details">
                    <div class="number">
                         <? //nLinhas(consultaLojasAtivas())?>
                    </div>
                    <div class="desc">
                         <?=$translate->translate('lojas_ativas')?>
                    </div>
                </div>
		
            </div>
        </div>
        
    </div>

<?  include_once('inicio/graficos.php') ?>