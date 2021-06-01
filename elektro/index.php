<?php

	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	ini_set('display_errors', false);

	ini_set("session.cookie_secure", 1);

	
	date_default_timezone_set('America/Sao_Paulo');
	
	session_start();
	
	include_once('inc/config.php');
	include_once('inc/conexao.php');
	
	include_once('i18nZF2.php');

	include_once('inc/funcoes.php');
	include_once('inc/bancofuncoes.php');
	
	include_once('inc/sessao.php');

		
	// Função que faz o logout
	$funcaoValor = $_GET['logout'];
	if($funcaoValor == true) { 
		// Destrói a sessão
		session_destroy();
		
		// Inicia nova sessão
		session_start();
		
		header("Location: /");
	}
	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?=$idioma?>">
<!--<![endif]-->

<!-- Head BEGIN -->
<head>
	<title><?=$_SESSION['nome_sis']?></title>

    <base href="<?=$_CONFIG['http_s']?><?=$_CONFIG['url_site']?>/">
	
	<meta charset="iso-8859-1">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/js/bootstrap/css/bootstrap.min.css">

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap/js/bootstrap.min.js"></script>

    <script src="/js/jquery.validate.min.js"></script>


    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <meta name="Keywords" content="<?=$_SESSION['nome_sis']?>" />
    <meta name="Description" content="<?=$_CONFIG['description']?>" />
    
    <meta name="company" content="LYCX - PLATAFORMA PARA INTELIGÊNCIA DE MERCADO ATRAVÉS DE CAMPANHAS E SORTEIOS PROMOCIONAIS" />
    <meta name="author" content="LYCX" />	
    <link rev="made" href="mailto:suporte[a]lycx[dot]com[dot]br" />
    
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-32x32.png">
    
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
		
   	<?=geraMetaSocias($_SESSION, $_CONFIG, $_GET)?>


    <link href="/css/green.css" rel="stylesheet" id="style-color">

    <link href="/css/style-responsive.css" rel="stylesheet">

    <link href="/css/style.css" rel="stylesheet">

    <script type="text/javascript" src="/js/format.js"></script>
    <script type="text/javascript" src="/js/json.js"></script>

    <script type="text/javascript" src="/js/funcoes.js"></script>
    <script type="text/javascript" src="/js/cpf_cnpj.js"></script>
    
    <script type="text/javascript" src="/js/jquery.mask.min.js"></script>

	<link href="/js/fontawesome/css/all.css" rel="stylesheet">

</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="<?=in_array($_GET['page'], array('', 'inicio')) ? 'inicio' : NULL?>">
    
    <?
    	if($_SESSION['campanha'] && $_SESSION['campanha']>0){
    		include_once("inc/header.php");
    	}
   	?>
	
    <div id="main">
        
    	<div class="container">
		
		<? 
			$setor	= "";
			if ($_GET['sec'] && reset($_GET)!=$_GET['sec']){
				$setor = $_GET['sec'];
				$url .= $setor."/";
			}
			
			$modulo = "";
			if ($_GET['modulo'] && reset($_GET)!=$_GET['modulo']){
				$modulo = $_GET['modulo'];
				$url .= $modulo."/";
			}
			
			$folder = "";
			if ($_GET['folder'] && reset($_GET)!=$_GET['folder']){
				$folder = $_GET['folder'];
				$url .= $folder."/";
			}
			
			$folder2 = "";
			if ($_GET['folder2'] && reset($_GET)!=$_GET['folder2']){
				$folder2 = $_GET['folder2'];
				$url .= $folder2."/";
			}

			if ($_GET['page'] && reset($_GET)!=$_GET['page']){
				$pagina = $_GET['page'];
			}
			
			$url = "inc/".$url."w_".$pagina;
			
		//	Se tiver em uma campanha
			if( $_SESSION['campanha'] && $_SESSION['campanha']>0){
		
		?>
				<div class="row">
				
					<div class="col-sm-<?=existePublicidade( array(1,2) )?'9' :12?> col-md-<?=existePublicidade( array(1,2) )?'9' :12?> contendo">
					<?	mostrarMensagem(); ?>
					
					<?
					//	Se tiver passando uma página e ela for diferente do nome da campanha
						if( $_GET['page']!='' && reset($_GET)!=$_GET['page'] ){

							$pagina = $_GET['page'];

							$exe = executaSQL("SELECT m.*, mm.caminho_include FROM menu m, menu_padrao mp 
												LEFT JOIN menu_modulo mm ON mm.id = mp.id_modulo
												WHERE m.url = '".$pagina."' 
													AND m.id_evento='".$_SESSION['campanha']->id."'
													AND m.id_menu_padrao = mp.id
													AND ativo = 1
												ORDER BY m.ordem");
							if(nLinhas($exe)>0){
								$reg = objetoPHP($exe);

								showPaginaDinamica($reg, $translate);
								
						//	Senão vai pegar a página inicio da campanha
							}else{

							//	Verifica se é um arquivo
								if(file_exists($url.".php")){
									include($url.".php");
							//	Senão vai pegar a página inicio da campanha
								}else{
									showPaginaInicioCampanha($translate); 
									//include("inc/404.php");
								}

							}


					//	Senão vai pegar a página inicio da campanha
						}else{
							
						//	Verifica se é um arquivo
							if(file_exists($url.".php")){
								include($url.".php");

						//	Senão vai pegar a página inicio da campanha
							}else{
								showPaginaInicioCampanha($translate); 
								//include("inc/404.php");
							}
								
						}
					?>
					</div>
				

			<?	if( existePublicidade( array(1,2) ) ){ ?>	
					<div class=" col-sm-3 col-md-3">
					<?	if( $_SESSION['campanha']->facebook ){ ?>
						<div id="fb-root" class="center"></div>
						<?=$_SESSION['campanha']->facebook?>
					<?	}
					
						//Menu Lateral
						getPublicidade(1);
					
						//Menu Lateral
						getPublicidade(2);

						//Menu Lateral
						getPublicidade(4);
						
						//Menu Lateral
						getPublicidade(5);
						
						//Menu Lateral
						getPublicidade(6);
						
					?>
					</div>
			<?	} ?>
				</div>
				<br />
				<div class="row">	
					<div class="col-sm-12 col-md-12">
					<?	getPublicidade(3); ?>
					</div>
				</div>
       		<?  
       			}else{
        
		            if($_SESSION['campanha'] && $_SESSION['campanha']>0 && file_exists($url.".php")){
		                
		                include($url.".php");
						
		            }else{ 
						
						header("Location: /campanha2020");

					}
                }
			?>
			
			<? include_once("inc/footer.php") ?>
		</div>
	    
    
    </div>

    <div id="fb-root"></div>
  	<? include_once('inc/facebook-pixel.php')?>
</body>

</html>