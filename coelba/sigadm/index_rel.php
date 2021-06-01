<?
	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	date_default_timezone_set('America/Sao_Paulo');
	
	// Inicia a sessao
	session_start();
	
	include_once('inc/config.php');
	include_once('inc/conexao.php');
	
	include_once('i18nZF2.php');
	
	include_once('inc/funcoes.php');
	include_once('inc/bancofuncoes.php');
	
	include_once('inc/sessao.php');

	if($active){
	
	
?>
	<html>

	    <head>
	    
	        <title>Relatório</title>
	        
	        <meta name="ROBOTS" content="NOFOLLOW" />
	        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	        <meta http-equiv="pragma" content="no-cache" />
	        <meta http-equiv="cache-control" content="no-cache" />
	        <meta name="company" content="teste" />
	        <meta name="Keywords" content="teste" />
	        <meta name="Description" content="teste" />
	        <meta name="author" content="<?=$_CONFIG['po_nome']." - ".$_CONFIG['po_sigla']?>" />
	        
	        <link rel="stylesheet" type="text/css" href="css/style_pdf.css">
		</head>
		
		<body>
					
			<?
	           $setor = "";
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
							
				$url = "inc/".$url."w_".$pagina;
				
				if(file_exists($url.".php")){				
					include($url.".php");
				}else{ 
					echo "<br />".$translate->translate("msg_pagina_nao_encontrada")."<br /><br /><br />";
				} 
				
	        ?>
		</body>
	</html>

<?	} ?>