<?
	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	date_default_timezone_set('America/Sao_Paulo');

	ini_set("session.cookie_secure", 1);

	ini_set("memory_limit","512M");
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 3600);

	ini_set('display_errors', false);
	
	// Inicia a sessao
	session_start();
	
	include_once('inc/config.php');
	include_once('inc/conexao.php');
	
	include_once('i18nZF2.php');
	
	include_once('inc/funcoes.php');
	include_once('inc/bancofuncoes.php');
	
	include_once('inc/sessao.php');

	if($active){
		

	/**
	 * PHPExcel
	 *
	 * Copyright (C) 2006 - 2014 PHPExcel
	 *
	 * This library is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU Lesser General Public
	 * License as published by the Free Software Foundation; either
	 * version 2.1 of the License, or (at your option) any later version.
	 *
	 * This library is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	 * Lesser General Public License for more details.
	 *
	 * You should have received a copy of the GNU Lesser General Public
	 * License along with this library; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
	 *
	 * @category   PHPExcel
	 * @package    PHPExcel
	 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
	 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
	 * @version    1.8.0, 2014-03-02
	 */
		
		/** Include PHPExcel */
		require_once dirname(__FILE__) . '/lib/phpExcel/Classes/PHPExcel.php';
		
	/* ----------- Cheat para funcionar os GET ----------- */
		$uri = explode('?', $_SERVER['REQUEST_URI']);
		if(count($uri)>1){
			$params = explode('&', $uri[1]);
				
			foreach($params as $valor){
				$param = explode('=', $valor);
				$_GET[$param[0]] = $param[1];
			}
		}
	/* ----------- Cheat para funcionar os GET ----------- */
		
		$url = "inc/";
		$pagina = "home";
		
		if(isset($_GET['inc']) && $_GET['inc'] == 'sge') {
			$url = "inc_sge/";
		}
		
		if(isset($_GET['sec']) && $_GET['sec']) {
			$url .= $_GET['sec'] . "/";
		}
		
		if(isset($_GET['modulo']) && $_GET['modulo']) {
			$url .= $_GET['modulo'] . "/";
		}
		
		$folder = "";
		if (isset($_GET['folder']) && $_GET['folder']){
			$folder = $_GET['folder'];
			$url .= $folder."/";
		}
		
		$folder2 = "";
		if (isset($_GET['folder2']) && $_GET['folder2']){
			$folder2 = $_GET['folder2'];
			$url .= $folder2."/";
		}
		
		if(isset($_GET['page']) && $_GET['page']) {
			$pagina = $_GET['page'];
		}
		
		$url .= "w_" . $pagina . "Excel.php";
		
		//echo $url;
		$objPHPExcel = new PHPExcel();
		//$objPHPExcel->createSheet(0);
	/*	
		if(file_exists($url)) {
			$_SESSION['pessoaId'] = $_GET['pessoaId'];
			
			if( temPermissao()){
	*/			include($url);
	/*		}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', utf8_encode($translate->translate('msg_nao_tem_permissao_pagina')));
			}
			
		}else{
			echo "Erro ao gerar o PDF!";
		}
	*/	
		//Aba a ser mostrada (Irmãos)
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	
		exit;
	
	}
?>