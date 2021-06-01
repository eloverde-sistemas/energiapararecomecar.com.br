<?php

	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);

	ob_start();
	
	ini_set('display_errors', false);

	ini_set("session.cookie_secure", 1);

	
	date_default_timezone_set('America/Sao_Paulo');
	
	session_start();
	
	include_once('sigadm/inc/config.php');
	include_once('sigadm/inc/conexao.php');
	
	include_once('i18nZF2.php');
	
	include_once('sigadm/inc/funcoes.php');	
	
	include_once('sigadm/inc/bancofuncoes.php');
	include_once('sigadm/inc/sessao.php');
	
	$tipo = intval($_GET['tipo']);
	$id = intval($_GET['id']);	
	
	if( $id >0 && $tipo==1 ){
		
		$exeDocumento = executaSQLPadrao("documento", " id='".$id."'");
		
		if(nLinhas($exeDocumento)>0){
			
			$reg=objetoPHP($exeDocumento);
			
			if( temPermissao(array('ADMIN_DOCUMENTOS_VISUALIZAR')) || ehMason() || ehAdminGeral() || permiteAcessoDocumentoByIdCategoria($reg->id_categoria)){
				
				$ext = explode(".",$reg->arquivo);
				$extensao = end($ext);				
				
				$nomeArquivo = converteArquivoNome("DOCUMENTO_".getNomeDocumentoCategoriaById($reg->id_categoria)."_".str_replace("/", "_", $reg->numero)).".".$extensao; 

				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				
				header('Content-Length: '.filesize($reg->arquivo));
				readfile($reg->arquivo);
				exit;
				
			}else{
				echo $translate->translate('msg_nao_tem_permissao_pagina');
			}			
		}else{
			header("Location: /adm");
		}
		
		//NOTICIAS
	}elseif( $id >0 && $tipo==2 ){
		
		$exeFile = executaSQL("SELECT file_dir, titulo FROM noticia WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			if(is_file($file->file_dir)){
			
				$ext = explode(".",$file->file_dir);
				$extensao = end($ext);
				$nomeArquivo = substr(converteArquivoNome(str_replace(" ", "_", trim($file->titulo))), 0, 50).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->file_dir));
				readfile($file->file_dir);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
		//E-BIBLIOTECA
	}elseif( $id > 0 && $tipo==3 ){
		
		$exeFile = executaSQL("SELECT * FROM artigo WHERE id = '".$id."' AND loja='".$_SESSION['loja_sge']."' ");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if($file->arquivo!=''){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = substr(converteArquivoNome(str_replace(" ", "_", $file->nome)), 0,50).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
		
	}elseif( $id>0 && $tipo==4){
		
		$exeFile = executaSQL("SELECT * FROM evento WHERE id = '".$id."' AND loja_cod='".$_SESSION['loja_sge']."' ");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if($file->arquivo!=''){
				
				$file->arquivo;
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = substr(converteArquivoNome(str_replace(" ", "_", $file->titulo)), 0,50).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
		
	}elseif( $id>0 && $tipo==5 ){
		
		$exeFile = executaSQL("SELECT * FROM convite WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if($file->file_dir!=''){
				
				$ext = explode(".",$file->file_dir);
				$extensao = end($ext);
				$nomeArquivo = substr(converteArquivoNome(str_replace(" ", "_", $file->titulo)), 0,50).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->file_dir));
				readfile($file->file_dir);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
				
	}elseif( $id>0 && $tipo==6 ){
		
		$file = get_option_value(139);
		
		if($file!=''){
				
			$ext = explode(".",$file);
			$extensao = end($ext);
			$nomeArquivo = "arquivo_submissao_ebiblioteca.".$extensao;

			header('Content-type: octet/stream');
			header("Content-disposition: attachment; filename=".basename($nomeArquivo));
			header('Content-Length: '.filesize($file));
			readfile($file);
			exit;

		}else{
			header("Location: /adm");
		}
				
	}elseif( $id>0 && $tipo==7 ){
		
		$exeFile = executaSQL("SELECT * FROM certificado WHERE id = '".$id."' AND loja='".$_SESSION['loja_sge']."' ");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if($file->arquivo!=''){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = $translate->translate('certificado')."_".consultaPessoaPrimeiroNomeById($file->id_irmao)."_".converteData($file->data,"","-").".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
	
	}elseif( $id>0 && $tipo==8 ){
		
		$exeFile = executaSQL("SELECT arquivo, dt_user_criacao FROM baixa WHERE id = '".$id."' AND loja_cod='".$_SESSION['loja_sge']."' ");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if($file->arquivo!=''){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome("baixa_dia_".$file->dt_user_criacao).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
				
	}elseif( $id>0 && $tipo==9 ){
		
		$exeFile = executaSQL("SELECT arquivo, data FROM ata WHERE id = '".$id."' AND id_loja='".$_SESSION['loja_sge']."' ");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo)){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome($translate->translate("nome_ata_".get_option_value(220))."_".$file->data).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
				
	}elseif( $id>0 && $tipo==10 ){
		
		$exeFile = executaSQL("SELECT *	FROM jornal_edicao 
								WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			//echo $file->arquivo;
			if(is_file($file->arquivo)){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);				
				$nomeArquivo = converteArquivoNome(getJornalNome($file->id_jornal)."_".$file->numero).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
				
	}elseif( $id>0 && $tipo==11 ){
		$param = (!$active ? ' AND restrito=2 ' : '');
		$exeFile = executaSQL("SELECT *	FROM mensagem_grao
								WHERE id = '".$id."' ".$param);
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo)){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome($translate->translate("mensagem_grao_mestre")."_".$file->data).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
								
	}elseif( $id>0 && $tipo==12 ){
		$exeFile = executaSQL("SELECT *	FROM boletim
								WHERE id = '".$id."' ".$param);
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo)){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome($translate->translate("boletim")."_".$file->cod).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
	
	}elseif( $id>0 && $tipo==13 ){
		$exeFile = executaSQL("SELECT *	FROM candidato_documento
								WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo)){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim($file->titulo)).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
	
	}elseif( $id>0 && $tipo==14 ){
		$exeFile = executaSQL("SELECT *	FROM regularizacao_documento
								WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo)){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim($file->titulo)).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
	
//	ARQUIVOS DO MÓDULO DE SOLICITAÇÕES
	}elseif( $id>0 && $tipo==15 ){
		$exeFile = executaSQLPadrao("solicitacao_arquivo", "id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->caminho)){
				$ext = explode(".",$file->caminho);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim($file->titulo)).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->caminho));
				readfile($file->caminho);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
	
//	ARQUIVOS DO PROCESSOS ADMINISTRATIVO
	}elseif( $id>0 && $tipo==16 ){
		$exeFile = executaSQLPadrao("processo_administrativo_anexo", "id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->caminho)){
				$ext = explode(".",$file->caminho);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim($file->titulo)).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->caminho));
				readfile($file->caminho);
				exit;
			}else{
				header("Location: /adm");
			}
			
		}else{
			header("Location: /adm");
		}
		
	//ARQUIVOS DA BAIXA AUTOMATICA
	}elseif( $id>0 && $tipo==17 ){
		$exeFile = executaSQLPadrao("baixa", "id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo)){
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);				
				$nomeArquivo = converteArquivoNome(trim("baixa_automatica_".$file->data_hora_geracao)).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
			}else{
				header("Location: /adm");
			}
			
		}else{
			header("Location: /adm");
		}
	
	//ARQUIVO PLAM
	}elseif( $id>0 && $tipo==20 ){
		$exeFile = executaSQL("SELECT arquivo_plam, nome FROM pessoa WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo_plam)){
				
				$ext = explode(".",$file->arquivo_plam);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim("arquivo_plam_".$file->nome)).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo_plam));
				readfile($file->arquivo_plam);
				exit;
			}else{
				header("Location: /adm");
			}
			
		}else{
			header("Location: /adm");
		}
	
	//ARQUIVO MIDIA
	}elseif( $id>0 && $tipo==22 ){
		$exeFile = executaSQL("SELECT caminho, titulo FROM midia WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->caminho)){
				
				$ext = explode(".",$file->caminho);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim("midia_".$file->titulo)).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->caminho));
				readfile($file->caminho);
				exit;
			}else{
				header("Location: /adm");
			}
			
		}else{
			header("Location: /adm");
		}
	
	}elseif( $id >0 && $tipo==23 ){
		
		$exeDocumento = executaSQLPadrao("documento", "id='".$id."' AND id_loja='".$_SESSION['loja_sge']."'");
		
		if(nLinhas($exeDocumento)>0){
			
			$reg=objetoPHP($exeDocumento);
						
			$ext = explode(".",$reg->arquivo);
			$extensao = end($ext);				
			
			$nomeArquivo = converteArquivoNome("DOCUMENTO_".getNomeDocumentoCategoriaById($reg->id_categoria)."_".str_replace("/", "_", $reg->numero)).".".$extensao; 

			header('Content-type: octet/stream');
			header("Content-disposition: attachment; filename=".basename($nomeArquivo));
			
			header('Content-Length: '.filesize($reg->arquivo));
			readfile($reg->arquivo);
			exit;
		
		}else{
			header("Location: /adm");
		}
	
	//LOJAS MUNDO
	}elseif( $id>0 && $tipo==29 ){
		$exeFile = executaSQL("SELECT *	FROM loja_mundo
								WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo)){
				
				$ext = explode(".",$file->arquivo);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim(substr($file->nome,0,20))).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo));
				readfile($file->arquivo);
				exit;
				
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
	
	//REGULARIZAÇÃO DE OUTRA POTENCIA - FOTO AFASTAMENTO
	}elseif( $id>0 && $tipo==30 ){
		$exeFile = executaSQL("SELECT arquivo_afastamento, nome FROM regularizacao
								WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->arquivo_afastamento)){
				
				$ext = explode(".",$file->arquivo_afastamento);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim(substr($file->nome,0,20))).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->arquivo_afastamento));
				readfile($file->arquivo_afastamento);
				exit;
			}
		}
	
	//HISTORIA DA LOJA
	}elseif( $id>0 && $tipo==31 ){
		$exeFile = executaSQL("SELECT * FROM loja_arquivo
								WHERE id = '".$id."'");
		
		if(nLinhas($exeFile)>0){
			
			$file = objetoPHP($exeFile);
			
			if(is_file($file->file_dir)){
				
				$ext = explode(".",$file->file_dir);
				$extensao = end($ext);
				$nomeArquivo = converteArquivoNome(trim(substr($file->descricao,0,20))).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($file->file_dir));
				readfile($file->file_dir);
				exit;
			}else{
				header("Location: /adm");
			}
		}else{
			header("Location: /adm");
		}
	
	}elseif( $tipo==99 ){ //EXPORTAÇÃO ZIP

			$fileURL = $_GET['fileURL'];
			$dirURL = $_GET['dirURL'];
			
			if(is_file($fileURL)){
				
				$extensao = end(explode(".", $fileURL));
				$arquivo = end(explode("/", $fileURL));
				$arquivo = explode(".", $arquivo);
				
				$nomeArquivo = converteArquivoNome(trim($arquivo[0])).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($fileURL));
				readfile($fileURL);
				
				unlink($fileURL);
				
				$diretorio = dir($dirURL); 
				//echo "Lista de Arquivos do diretório '<strong>".$dirURL."</strong>':<br />";
				while($arquivo = $diretorio -> read()){ 
					if($arquivo!='.' && $arquivo!='..'){
						echo $dirURL.$arquivo."<br />"; 
						unlink($dirURL."/".$arquivo);
						
					}
				}
				$diretorio->close();
				
				rmdir($dirURL);
			}else{
				header("Location: /adm");
			}
			
			exit;
	
	}elseif( $tipo==100 ){ //ARQUIVOS DIVERSOS
			
			$fileURL = $_GET['fileURL'];
			
			if( is_file($fileURL) ){
				
				$extensao = end(explode(".", $fileURL));
				$arquivo = end(explode("/", $fileURL));
				$arquivo = explode(".", $arquivo);
				
				$nomeArquivo = converteArquivoNome(trim($arquivo[0])).".".$extensao;
				
				header('Content-type: octet/stream');
				header("Content-disposition: attachment; filename=".basename($nomeArquivo));
				header('Content-Length: '.filesize($fileURL));
				readfile($fileURL);
				
				exit;
			}else{
				header("Location: /adm");
			}
	
	}else{
		header("Location: /adm");
	}
?>