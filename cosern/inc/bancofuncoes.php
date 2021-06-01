<?php
	
	
	function registrarLog( $acao, $sql, $tabela ){
		
		$idLog = proximoId('historico');
		
		$useragent = $_SERVER['HTTP_USER_AGENT'];
	 
		if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser = 'IE '.$matched[1];
		} elseif (preg_match("/Trident\/7.0;(.*)rv:11.0/",$useragent,$matched)) {
			$browser = 'IE 11';
		} elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser = 'Opera '.$matched[1];
		} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
			$browser = 'Firefox '.$matched[1];
		} elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
			$browser = 'Chrome '.$matched[1];
		} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
			$browser = 'Safari '.$matched[1];
		} else {
			$browser = $useragent;
		}
		

		$sql_log = mysql_query('INSERT INTO historico
								 (id, acao, str_sql, tabela, url, navegador, ip ) values
								("'.$idLog.'", 
								"'.$acao.'", 
								"'.mysql_real_escape_string(inj($sql)).'", 
								"'.$tabela.'", 
								"'.$_SERVER['QUERY_STRING'].'", 
								"'.mysql_real_escape_string(inj($browser)).'", 
								"'.$_SERVER["REMOTE_ADDR"].'")'); 
		
		return $idLog;
	}

	function confirmaLog($idLog){
		mysql_query("UPDATE historico SET resposta = 1 WHERE id = '".$idLog."'");
	}
	
	function errorLog($idLog){
		mysql_query("UPDATE historico SET erro = '".inj(mysql_error())."' WHERE id = '".$idLog."'");
	}


	//Protege contra SQL Injection
	function inj($str){		
		if ( get_magic_quotes_gpc() ){
			return $str;
		}else{
			return addslashes($str);
		}
	}
	
	
	//Inclui Dados
	function inserirDados($tabela, $dados, $mostrar=false){
		$campos = $valores = array();
		foreach($dados as $chave => $valor){
			$campos[]  = $chave;
			$valores[] = inj($valor);
		}
		$campos  = join(',', $campos);
		$valores = join("','", $valores);
		$sql = "INSERT INTO $tabela ($campos) values ('$valores');";
		
		if($mostrar) echo "<br>".$sql;
		
		if(function_exists('registrarLog')){
			$idLog = registrarLog('inserir', $sql, $tabela);
		}
		
		$exe = mysql_query($sql);
		
		if($exe){
			if( function_exists('confirmaLog') )
				confirmaLog($idLog);
		}else{
			if( function_exists('errorLog') )
				errorLog($idLog);
		}
		
		return $exe;
	}
	
	
	//Altera Dados
	function alterarDados($tabela, $dados, $clausula, $mostrar=false){
		foreach ($dados as $chave=>$valor){
			$alts[] = $chave." = '".inj($valor)."' ";
		}
		$lista_alts = join(",", $alts);
		
		$sql = "UPDATE $tabela SET $lista_alts WHERE $clausula ";
		
		if($mostrar) echo "<br>".$sql;
		
		if(function_exists('registrarLog')){
			$idLog = registrarLog('alterar', $sql, $tabela);
		}
		
		$exe =  mysql_query($sql);
		
		if($exe){
			if( function_exists('confirmaLog') )
				confirmaLog($idLog);
		}else{
			if( function_exists('errorLog') )
				errorLog($idLog);
		}
		
		return $exe;
	
	}
	
	function proximoId ($tabela){
		$exe = executaSQL("SELECT MAX(id) as id FROM $tabela ");
		if(nLinhas($exe)>0){
			$proxId = objetoPHP($exe);
			return ($proxId->id + 1);
		}else{
			return 1;
		}
	}

	//Função que pega o próximo OID da tabela informada. 
	function proximoOid ($tabela){
		$exe = executaSQL("SELECT MAX(oid) as id FROM $tabela ");
		$proxId = objetoPHP($exe);
		return ($proxId->id + 1);
	}
	
	//Exclui Dados
	function excluirDados($tabela, $clausula, $mostrar=false){

		$sql = "DELETE FROM $tabela WHERE $clausula";
		
		if($mostrar) echo $sql;
		
		if(function_exists('registrarLog')){
			$idLog = registrarLog('excluir', $sql, $tabela);
		}
		
		$exe =  mysql_query($sql);
		
		if($exe){
			if( function_exists('confirmaLog') )
				confirmaLog($idLog);
		}else{
			if( function_exists('errorLog') )
				errorLog($idLog);
		}
		
		return $exe;
	}

	//Mostrar Dados
	function mostrarErroBanco(){
		return mysql_error();
	}

	//Retorna todos os campos de uma Consulta Simples, informando Tabela e Cláusula 
	function executaSQLPadrao($tabela, $clausula="1", $mostrar=false){
		
		$sql = "SELECT * FROM $tabela WHERE $clausula";
		//echo $sql;
		if($mostrar==true) echo $sql;
			
		return mysql_query($sql);
		//echo "SELECT * FROM $tabela WHERE $clausula ";
	}

	//Retorna a execução do SQL completo no Banco de Dados 
	function executaSQL($sql, $mostrar=false){
		
		if($mostrar==true) echo $sql;
		return mysql_query($sql);
	}
	
	//Retorna um Objeto do Registro retornado na consulta
	function objetoPHP($exe){
		return mysql_fetch_object($exe);
	}
	
	function arrayPHP($exe){
		return mysql_fetch_array($exe);
	}
	
	//Retorna o Número de Registros da consulta
	function nLinhas($exe){
		return mysql_num_rows($exe);
	}

	function getName($ID,$tabela){
		$resultado = objetoPHP(executaSQLPadrao($tabela,"id = $ID"));
		return $resultado->nome;
	}
	
	function maximoId($tabela){
		$query = mysql_query("SELECT MAX(id) FROM $tabela");
		$id = mysql_fetch_array($query);
		return $id[0];
	}

	function geraMetaSocias($SESSION, $CONFIG, $GET){
		$meta = '';
		$id = $GET['id'];


		$meta.= '<meta property="og:site_name" content="'.$CONFIG["nome_sis"].'">';
	    $meta.= '<meta property="og:title" content="">';
	    $meta.= '<meta property="og:description" content="'.$CONFIG["description"].'">';
	    $meta.= '<meta property="og:type" content="website">';
	    $meta.= '<meta property="og:image" content="'.$CONFIG["http_s"].$CONFIG["url_site"].'/images/logo.png">';
	    $meta.= '<meta property="og:image:type" content="image/png">';
		$meta.= '<meta property="og:image:width" content="295">';
		$meta.= '<meta property="og:image:height" content="295">';
	    $meta.= '<meta property="og:url" content="'.$CONFIG["http_s"].$CONFIG["url_site"].'">';

	    return $meta;
	}

	function getPublicidade($id){
		
		$regs = executaSQLPadrao('banner', "id_posicao='".$id."' AND id_evento='".$_SESSION['campanha']->id."' ORDER BY id DESC ");
		if(nLinhas($regs)>0){
			
			$posicao = objetoPHP(executaSQLPadrao('banner_posicao', "id= ".$id." "));
?>
			<link rel="stylesheet" href="/js/owl-carousel/owl.carousel.css">
            <link rel="stylesheet" href="/js/owl-carousel/owl.theme.css">
			<script src="/js/owl-carousel/owl.carousel.js"></script>
			
			<div id="owl-slider-publicidade-<?=$id?>" class="owl-carousel home jumper-20">
			<?
                while($reg = objetoPHP($regs)){
                    if( is_file($reg->image_dir) ){
                        $image = '/'.$reg->image_dir;
                        
                        $dimensoes = getimagesize($reg->image_dir);
                        $altura = $dimensoes[1];
                    
                    }else{
                        $image = '/images/logo.png';
                        $altura = '300px';
                    }
            ?>
 					<a href="<?=$reg->link!='' ? $reg->link : '#'?>" <?=($reg->nova_pagina==1)?'target="_blank"': ''?> class="item-slider" data-id="<?=$reg->id?>" onclick="salvaClique('<?=$reg->id?>')">
                        <img src="<?=$reg->image_dir?>" class="img-responsive" title="<?=$reg->link_texto?>" alt="<?=$reg->link_texto?>" />
                    </a>
            <?
                }
            ?>
            </div>
			<script>
                jQuery(document).ready(function ($) {
                    var owl = $("#owl-slider-publicidade-<?=$id?>");
                    
                    owl.owlCarousel({
                        autoPlay : true,//5000,
                        stopOnHover : true,
                        navigation: false,
						pagination: false,
                        paginationSpeed : <?=$posicao->tempo?>,
                        goToFirstSpeed : 2000,
                        singleItem : true,
                        autoHeight : true,
                        transitionStyle:"fade"
                    });
                    
                });

                salvaClique = function(idBanner){ 
                	$.ajax({
		                url: 'inc/genericoJSON.php',
		                type: 'post',
		                data: {
		                        acao: 'bannerClique',
		                        id:   idBanner
		                },
		                cache: false,
		                async: false,
		                success: function(data) {		                    

		                },
		                error: function (XMLHttpRequest, textStatus, errorThrown) {
		                    alert(XMLHttpRequest.responseText);
		                },
		                dataType: 'json'
		            });
                }
            </script>
            <div class="owl-buttons hide"></div>
            
<?
		}
		
	}

/*
	FUNÇÕES DE PÁGINAS DE CAMPANHA
 */	
	
	function showPaginaInicioCampanha($translate){
		$exe = executaSQL("SELECT m.*, mm.caminho_include FROM menu m, menu_padrao mp 
	        					LEFT JOIN menu_modulo mm ON mm.id = mp.id_modulo
	        					WHERE mp.id = 1
	        						AND m.id_evento='".$_SESSION['campanha']->id."'
	        						AND m.id_menu_padrao = mp.id");

		if(nLinhas($exe)>0){
			$reg = objetoPHP($exe);

			showPaginaDinamica($reg, $translate);

		}else{
			include_once("inc/w_inicio.php");
		}
	}

	function showPaginaDinamica($reg, $translate){
		echo (strtolower($reg->titulo)!='inicio')?'<h1>'.$reg->titulo.'</h1>' :'';

		echo $reg->conteudo_pagina;

		if( isset($reg->caminho_include) ){
			include_once("inc/".$reg->caminho_include.".php");
		}
	}

	function consultaMenusCampanha(){
		return executaSQL("SELECT m.* FROM menu m, menu_padrao mp 
                                                LEFT JOIN menu_modulo mm ON mm.id = mp.id_modulo
                                                WHERE m.ativo = 1
                                                    AND m.id_evento='".$_SESSION['campanha']->id."'
                                                    AND m.id_menu_padrao = mp.id
							ORDER BY m.ordem");
	}

	function getMuniciopioById($id){			
		$reg = executaSQL("SELECT * FROM municipio WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}

	function getEstadoByMuniciopioId($id){			
		$reg = executaSQL("SELECT e.* FROM municipio m, estado e 
							WHERE m.id='".$id."'
								AND m.id_estado = e.id");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}

	function getCupomSituacao($id){			
		$reg = executaSQL("SELECT * FROM cupom_situacao WHERE id='".$id."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg)->valor;
		}else{
			return false;
		}
	}
	
	function getElementosByCampanhaTipoCupom($campanhaTipo, $id){

		if($campanhaTipo==3){
			$regs = executaSQL("SELECT e.elemento FROM cupom_multiplo cm, elemento_sorteavel e WHERE id_part_cupom='".$id."' AND cm.id_elem_sorteavel=e.id ORDER BY cm.id");
			if (nLinhas($regs)>0){
				$elems = array();
				while( $reg = objetoPHP($regs) ){
					$elems[]= $reg->elemento;
				}
				return implode($elems, ", ");
			}else{
				return "";
			}
		}else{
			$regs = executaSQL("SELECT elemento FROM elemento_sorteavel WHERE id_participante_cupom='".$id."'");
			if (nLinhas($regs)>0){
				$elems = '';
				while( $reg = objetoPHP($regs) ){
					$elems .= $reg->elemento."<br>";
				}
				return $elems;
			}else{
				return "";
			}
		}
	}
	
	function consultaLojaByCampanhaCNPJ($campanha, $cnpj, $campos="*"){
		$reg = executaSQL("SELECT $campos FROM evento_loja e, loja l WHERE id_evento='".$campanha."' AND e.id_loja=l.id AND l.cnpj='".$cnpj."'");
		if (nLinhas($reg)>0){
			return objetoPHP($reg);
		}else{
			return false;
		}
	}

	function existeLojaComCNPJ($campanha, $cnpj){
		$reg = executaSQL("SELECT * FROM evento_loja e, loja l WHERE id_evento='".$campanha."' AND e.id_loja=l.id AND REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$cnpj."', '.', ''), '/', ''), '-', '')");
		if (nLinhas($reg)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function dataCupomValida($campanha, $data){
		$reg = executaSQL("SELECT 1 FROM evento WHERE id= '".$campanha."' AND '".$data."'  BETWEEN CONCAT(dt_inicio,' ',hr_inicio) AND CONCAT(dt_termino,' ',hr_termino)", true);
		if ( nLinhas($reg )>0){
			return true;
		}else{
			return false;
		}
	}
	
	function existePublicidade($posicoes){
		$reg = executaSQLPadrao('banner', "id_posicao IN ('".implode(",", $posicoes)."') AND id_evento='".$_SESSION['campanha']->id."' ORDER BY id DESC ");
		if (nLinhas($reg)>0){
			return true;
		}else{
			return false;
		}
	}

	function consultaTipoPeloId($tipoId){
		return objetoPHP(executaSQL("SELECT * FROM participacao_tipo WHERE id='".$tipoId."' "));
	}
	
?>