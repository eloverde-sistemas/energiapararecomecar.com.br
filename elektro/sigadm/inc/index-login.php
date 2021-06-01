<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 3.2.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
   		<base href="<?=$_SESSION['http_s']?><?=$_SERVER['HTTP_HOST']?>/sigadm/">
        
        <meta content="IE=9" http-equiv="X-UA-Compatible" />
        
        <title><?=$_SESSION['nome_sis']?></title>
        
        <meta name="Keywords" content="<?=$_SESSION['nome_sis']?>, InovaWeb" />
        <meta name="Description" content="<?=$_SESSION['nome_sis']?>" />
        
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
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="metronic/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME STYLES -->
        <link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->

    </head>
    <!-- BEGIN BODY -->
    <!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
    <!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
    <!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
    <!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
    <!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
    <!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
    <!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
    <!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
    <!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
    <body class="login">
        
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="menu-toggler sidebar-toggler">
        </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <!-- BEGIN LOGIN -->
        <div class="content">
        	<!-- BEGIN LOGO -->
			<div class="logo">
				<a href="javascript:void()">
					<img src="images/logo-big.png" alt="<?=$_SESSION['POTENCIA']->sigla?>" title="<?=$_SESSION['POTENCIA']->sigla?>"/>
				</a>
			</div>
			<!-- END LOGO -->
        	
			<!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="index.html" method="post">
                <h3 class="form-title"><?=$translate->translate('texto_login')?></h3>
                
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span><?=$translate->translate('erro_login_senha2')?> </span>
                </div>
                
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9"><?=$translate->translate('usuario')?></label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?=$translate->translate('login_cim')?>" name="username"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?=$translate->translate('senha')?></label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="<?=$translate->translate('senha')?>" name="password"/>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn green pull-right">
                    <?=$translate->translate('bt_entrar')?> <i class="m-icon-swapright m-icon-white"></i>
                    </button>
                    <div class="clearfix"></div>
                </div>
                <div class="forget-password">
                	<h4><?=$translate->translate('esqueceu_senha')?></h4>
                    <p>
                    	<a href="javascript:;" id="forget-password"><?=$translate->translate('esqueceu_senha_clique')?></a>
                	</p>
                </div>

            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form class="forget-form" action="index.html" method="post">
                <h3><?=$translate->translate('esqueceu_senha')?></h3>
                <p><?=$translate->translate('rs_primeiro_texto')?></p>
                <p><?=$translate->translate('rs_segundo_texto')?></p>
                <p><?=$translate->translate('rs_terceiro_texto')?></p>
                
                <div class="alert alert-danger display-hide" >
                    <button class="close" data-close="alert"></button>
                    <span></span>
                </div>
                
                <div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9"><?=$translate->translate('login_cim')?></label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?=$translate->translate('login_cim')?>" id="cimES" name="cimES" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?=$translate->translate('email')?>" id="emailES" name="emailES" value="" />
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn back-btn">
                    	<i class="m-icon-swapleft"></i> <?=$translate->translate('voltar')?> 
                    </button>
                    <button type="submit" class="btn green pull-right">
                    	<?=$translate->translate('enviar_email')?> <i class="m-icon-swapright m-icon-white"></i>
                    </button>
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->

			
        </div>
        <!-- END LOGIN -->
        <!-- BEGIN COPYRIGHT -->
        <div class="copyright">
            <div class="page-footer-inner no-float spacer-10">
                 &copy; 
            </div>
        </div>
        <!-- END COPYRIGHT -->
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <!-- BEGIN CORE PLUGINS -->
        <!--[if lt IE 9]>
        <script src="metronic/assets/global/plugins/respond.min.js"></script>
        <script src="metronic/assets/global/plugins/excanvas.min.js"></script> 
        <![endif]-->
        <script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
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
        <script src="metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
        <script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
        <script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
        <script src="metronic/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
        <script src="js/login.js" type="text/javascript"></script>
        <script src="js/format.js" type="text/javascript"></script>
        <script src="js/definitions.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- END JAVASCRIPTS -->
        
        <script>
			jQuery(document).ready(function() {
				Login.init();
			});
		</script>
    </body>
    <!-- END BODY -->
</html>