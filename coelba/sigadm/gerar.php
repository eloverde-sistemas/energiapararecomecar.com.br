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

		$active = $_GET[irMaoestaActive];
	
		
?>
	<html>

	    <head>
	    
	        <title>teste</title>
	        
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
	            $url = "inc/";
	            $pagina = "home";
	            
	            if($_GET['inc'] == 'sge') {
	                $url = "inc_sge/";
	            }
	            
	            if($_GET['sec']) {
	                $url .= $_GET['sec'] . "/";
	            }
				
				if($_GET['modulo']) {
	                $url .= $_GET['modulo'] . "/";
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
	            
	            if($_GET['page']) {
	                $pagina = $_GET['page'];
	            }
	            
	            $url .= "w_" . $pagina . ".php";
	            
				//echo $url;
				
	            if(file_exists($url)) {
					$_SESSION['pessoaId']	= $_GET['pessoaId'];
					$_SESSION['loja_sge']	= $_GET['loja_sge'];
					$_SESSION['id_tipo']	= $_GET['id_tipo'];
					$_SESSION['active']		= $active;
				//	var_dump($_GET);
					
	               	if( temPermissao()){
						include($url);
					}else{
						echo $translate->translate('msg_nao_tem_permissao_pagina');
					}
					
	            } else {
	                echo "Erro ao gerar o PDF!";
	            }
				
	        ?>
	    </body>
	</html>

<?	} ?>