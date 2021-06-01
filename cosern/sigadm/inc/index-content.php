<?
	switch($idioma) {
		case "en":
			$dataTables = 'datatables-en_US.js';
			$idiomaDatepicker = "jquery.ui.datepicker-en-US";
			$maskMoney = "maskMoney-en_US"; 
			$validate = "jquery.validate.min-en_US";
			$ckEditor = "ckeditor_en";
			$shadowBox = "shadowboxEN";
			$fullcalendar = "fullcalendarEN";			
		break;
		case "es":
			$dataTables = 'datatables-es_ES.js';
			$idiomaDatepicker = "jquery.ui.datepicker-es";
			$maskMoney = "maskMoney";
			$validate = "jquery.validate.min-es";
			$ckEditor = "ckeditor_pt";
			$shadowBox = "shadowboxES";
			$fullcalendar = "fullcalendarPT";
		break;
		default:
			$dataTables = 'datatables-pt_BR.js';
			$idiomaDatepicker = "jquery.ui.datepicker-pt-BR";
			$maskMoney = "maskMoney"; 
			$validate = "jquery.validate.min-pt_BR";
			$ckEditor = "ckeditor_pt";
			$shadowBox = "shadowboxPT";
			$fullcalendar = "fullcalendarPT";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<!-- BEGIN HEAD -->
	<head>
		<base href="<?=$_SESSION['http_s']?><?=$_SERVER['HTTP_HOST']?>/sigadm/">

	   <meta charset="iso-8859-1">

        <meta content="IE=9" http-equiv="X-UA-Compatible" />
        
        <title><?=$_SESSION['nome_sis']?></title>
        
        <meta name="Keywords" content="<?=$_SESSION['nome_sis']?>" />
        <meta name="Description" content="<?=$_SESSION['description']?>" />
        
        <meta name="company" content="InovaWeb Soluções em Tecnologia." />
        <meta name="author" content="InovaWeb Soluções em Tecnologia" />	
        <link rev="made" href="mailto:suporte[a]i3w[dot]com[dot]br" />
    
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        
		<link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

		<!-- Favicon -->
		<link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="images/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
		<link rel="manifest" href="images/favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<!-- Favicon -->
		
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
        <link href="metronic/assets/global/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
        <link href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL PLUGIN STYLES -->
        <!-- BEGIN PAGE STYLES -->
        <link href="metronic/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/admin/layout/css/themes/blue.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
        
        <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css"/>
        <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
        <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
        <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
        <link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
        
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
		
        
        <link href="metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" /><script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
        
        <script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
        <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
        <script src="metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        
        <script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="metronic/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>

        <script src="metronic/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
        <script src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
		<script type="text/javascript" src="metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
        <script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
        <script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
        <script type="text/javascript" src="metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<?	/*/ ?>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/plug-ins/3cfcc339e89/sorting/datetime-moment.js"></script>
<?	/*/ ?>
		<script type="text/javascript">
            jQuery(document).ready(function() {
                $.extend( $.fn.dataTable.defaults, {
                   language: {
                        url: 'js/ui_i18n/<?=$dataTables?>'
                    }
                });
				
				bootbox.setDefaults({
				  locale: "pt"
				});
            });
		</script>
        <!-- END PAGE LEVEL PLUGINS -->
        
        <script src="metronic/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript" ></script>
        
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
        <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
        <script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
        <script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>        
		<script src="metronic/assets/admin/pages/scripts/portlet-draggable.js"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
		<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
        <script type="text/javascript" src="metronic/assets/global/plugins/ckeditor/ckeditor.js"></script>
	    <script src="metronic/assets/admin/pages/scripts/components-editors.js"></script>
        <!-- END PAGE LEVEL SCRIPTS -->        
       
        <script src="metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
        <!--/*<script type="text/javascript" src="metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>*/-->
        <!--<script type="text/javascript" src="metronic/assets/global/plugins/ckeditor/ckeditor.js"></script>-->
        
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script type="text/javascript" src="js/datepicker-config.js"></script>        
        <script type="text/javascript" src="js/ui_i18n/<?=$idiomaDatepicker?>.js"></script>
        <script type="text/javascript" src="js/ui_i18n/<?=$validate?>.js"></script>
        <script type="text/javascript" src="js/format.js"></script>
        <script type="text/javascript" src="js/cpf_cnpj.js"></script>
        <script type="text/javascript" src="js/funcoes.js"></script>        
        <script type="text/javascript" src="js/json.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/definitions.js"></script>
		
        <!-- END PAGE LEVEL SCRIPTS -->
<!-- 	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">-->
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>
<!-- END HEAD -->

	<body class="page-header-fixed page-quick-sidebar-over-content">
		<?	include('inc/header.php');?>
        <div class="clearfix"></div>
        <div class="page-container">
			<?
                include_once('inc/sidebar.php');
			?>
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <div class="page-content">
				<?	mostrarMensagem(); ?>
            <?

				if( $_SESSION['campanha_atual']>0 && $_GET['page']=='inicio' ){
            ?>
                    <h4 class="font-red-flamingo">
                        <a href="#trocar-loja" data-toggle="modal" class="red btn btn-sm">
                            <i class="fa fa-exchange"></i>
                        </a>
                        Campanha: <?=getEventoById($_SESSION['campanha_atual'])->titulo?>
                    </h4>
            <?
                }

				if($_SESSION["esqueceuSenha"]){
					include_once("inc/w_alterar-senha.php");
				
				}else if( deprecated() ){
					
					include_once('inc/deprecated.php');
				
				}else if( in_array($_GET['page'], array('', 'inicio')) || (temPermissao()) ){
					
					$setor = $url = "";
					
					if ($_GET['sec']){
						$setor = $_GET['sec'];
						$url .= $setor."/";
					}
					
					$modulo = "";
					if ($_GET['modulo']){
						$modulo = $_GET['modulo'];
						$url .= $modulo."/";
					}
					
					$folder = "";
					if ($_GET['folder']){
						$folder = $_GET['folder'];
						$url .= $folder."/";
					}
					
					$folder2 = "";
					if ($_GET['folder2']){
						$folder2 = $_GET['folder2'];
						$url .= $folder2."/";
					}
					
					$pagina = "inicio";
					if ($_GET['page']){
						$pagina = $_GET['page'];
					}
					
					$url		= "inc/".$url."w_".$pagina;
					
					if(file_exists($url.".php")){						
						include($url.".php");						
					}else{ 
						echo "<br />".$translate->translate("msg_pagina_nao_encontrada")."<br /><br /><br />";
					}
					
				}else{
					if(!$active){
						echo "<br />".$translate->translate("msg_necessario_estar_logado")."<br /><br /><br />";
					}
				}
			?>

				<!-- END PAGE CONTENT-->
                </div>
            </div>
            <!-- END CONTENT -->
            
            <?	include_once('inc/footer.php');?>
        </div>
        
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <!-- BEGIN CORE PLUGINS -->
        <!--[if lt IE 9]>
        <script src="metronic/assets/global/plugins/respond.min.js"></script>
        <script src="metronic/assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        
        <script>
        jQuery(document).ready(function() {
			Metronic.init(); // init metronic core componets
			Layout.init(); // init layout
			QuickSidebar.init() // init quick sidebar

			Index.initDashboardDaterange();
			Index.initJQVMAP(); // init index page's custom scripts
			Index.initCalendar(); // init index page's custom scripts
			Index.initCharts(); // init index page's custom scripts
			Index.initChat();
			Index.initMiniCharts();
			Tasks.initDashboardWidget();
			
		//	Cheat para os bot?es drop-down em tabelas	
			$('.dataTable .btn-group .dropdown-toggle').each(function(){
				if( !$(this).hasClass('scroll') )
					$(this).closest('.btn-group').css('position', 'absolute').after( $(this).clone() );
			});
			
		//	Cheat para abrir e fechar portlet Sem ter que clicar nos ?cones de a??o
			$('.portlet-title .tools a, .portlet-title .actions a').click(function(){
				$(this).addClass('alreadyClick');
			});
			
			$('.portlet-title').each(function(){
				icon = $(this).find('.tools .collapse, .tools .expand');
				if(icon.length>0)
					$(this).addClass('pointer');
			}).click(function(){
				icon = $(this).find('.tools a.alreadyClick, .actions a.alreadyClick');
			//	console.dir(icon);
				if(icon.length>0){
					icon.each(function(){ $(this).removeClass('alreadyClick') });
				}else{
					$(this).find('.tools .collapse, .tools .expand').click();
				}
			});
        });
        </script>
        <!-- END JAVASCRIPTS -->
		
	</body>
</html>