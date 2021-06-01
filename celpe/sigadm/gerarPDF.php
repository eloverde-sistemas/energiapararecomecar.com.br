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

		$_GET['pessoaId']		= $_SESSION['usuarioId'];
		$_GET['pessoaActive']	= $_SESSION['active'];
		$_GET['loja_sge']		= $_SESSION['loja_sge'];
		$_GET['id_tipo']		= $_SESSION['id_tipo'];
		
		/* ----------- Cheat para funcionar os GET ----------- */
		
		//	var_dump($_SERVER['REQUEST_URI']);	
		//	var_dump($_GET);
		//	echo '<br /><br />';
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$params = explode('&', $url[1]);
		
		foreach($params as $valor){
			$param = explode('=', $valor);
			$_GET[$param[0]] = $param[1];
		}
		//	var_dump($_GET);

		/* ----------- Fim Cheat para funcionar os GET ----------- */


		include_once('lib/tcpdf_min/tcpdf.php');
		
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		
			//Page header
			public function Header() {
				
				$empresa = empresaDados();
				
				$dataFundacao = explode('-', $empresa->data_fundacao);

				// Logo
				$logoEmpresa = "images/logo.png";
				
				if( is_file($logoEmpresa) ){
					$this->Image( $logoEmpresa, '10', '8', '35', '', '', '', '', false, 90, '', false, false, 0, false, false, false);
				}			
				
				$txtCabecalho.= '<span style="font-weight:bold; font-size:1.5em; font-family:helvetica;">'.$empresa->nome.'</span><br /><br />';
				
				$txtCabecalho .= '<span style=" font-size:1.0em; font-family:helvetica;">'.$empresa->cidade." - ".$empresa->estado.'</span><br />';	
				$txtCabecalho .= '<span style="font-size:1.0em; font-family:helvetica;">'.$_SESSION['http_s'].$_SERVER['SERVER_NAME'].'</span><br />';

				$this->WriteHTMLCell(150, 0, 30, 9, utf8_encode($txtCabecalho), 0, 0, 0, 0, 'C');

				$this->WriteHTMLCell(200, 0, 5, 36, '<br /><br /><hr />', 0, 0, 0, 0, 'C');
				
			}
		
			// Page footer
			public function Footer() {
				$this->SetY(-10);			
				//$this->Cell(200, 10, utf8_encode($this->getAliasNumPage().' de '.$this->getAliasNbPages()), "T", 1, 'L', 0, '', 0, false, 'T', 'M');
				$irmao = consultaPessoaNomeById($_SESSION['pessoaId']);
				$txtFooter = '
								<table border="0" cellpadding="3">
									<tr>
										<td width="590" align="left">Impresso por '.$irmao.' em '.date("d/m/Y").' às '.date("H:i").'</td>
										<td width="100">'.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'</td>
									</tr>
								</table>
				';
				$this->WriteHTMLCell(195, 7, 7, 287, utf8_encode($txtFooter), 'T', 0, 0, 0, 'R');
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($_CONFIG['po_sigla']);
		$pdf->SetTitle($_CONFIG['po_sigla']);
		$pdf->SetSubject($_CONFIG['po_sigla']);
		$pdf->SetKeywords($_CONFIG['po_sigla']);
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, NULL );
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//HEADER SOMENTE NA PRIMEIRA PÁGINA
		$pdf->SetMargins(7, 45, 7);//left,top,right
		
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);
		
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 13);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l);
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('helvetica', 'N', 10);
		
		// add a page
		$pdf->AddPage();
		
		$url = $_CONFIG['http_s'].$_SERVER['SERVER_NAME']."/sigadm/gerar.php";
		$primeiro = true;
		
		$fileName = 'arquivo';
		if($_GET['fileName']!=''){
			$fileName = $_GET['fileName'];
		}
		
		if($_GET) {
			foreach($_GET as $campo=>$nome){
				if($primeiro) {
					$url .= "?";
					$primeiro = false;
				} else {
					$url .= "&";
				}
				$url .= "$campo=$nome";
			}
		}
		
		if($_POST) {
			foreach($_POST as $campo=>$nome) {
				if($primeiro) {
					$url .= "?";
				} else {
					$url .= "&";
				}
				$url .= "$campo=$nome";
			}
		}
		//echo $url;
		$html = file_get_contents($url);

		$html = utf8_encode($html);	
		
		$pdf->writeHTML($html, true, false, true, false, '');
		
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		//Close and output PDF document
		$pdf->Output($fileName.'.pdf', 'I');

	}
?>