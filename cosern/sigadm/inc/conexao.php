<?php

	error_reporting (~E_NOTICE & ~E_DEPRECATED);
	
	//LINK COM O MYSQL
	$conexao  = mysql_connect($_CONFIG['banco_server'], $_CONFIG['banco_usuario'], $_CONFIG['banco_senha']); 
	//CASO NÃO CONSIGA FAZER A CONEXÃO
	if(!$conexao) {
		//MATA A EXECUÇÃO DO PHP E MOSTRA O ERRO GERADO NO MYSQL
	    die();
	}
	
	//SELECIONA O BANCO DE DADOS QUE SERÁ UTILIZADO
	$dbSelect = mysql_select_db($_CONFIG['banco_nome'], $conexao);
	//CASO NÃO CONSIGA SELECIONAR
	if (!$dbSelect) {
		//MATA A EXECUÇÃO E MOSTRA O ERRO GERADO NO MYSQL
	    die();
	}

?>