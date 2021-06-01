<?php
	error_reporting (~ E_NOTICE & ~ E_DEPRECATED);
	ini_set("display_errors", true);

	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 0);

	date_default_timezone_set('America/Sao_Paulo');

    $ambiente = 'celpe';
    $caminho = "/home/admin/domains/energiapararecomecar.com.br/private_html/".$ambiente."/";

	include($caminho."sigadm/inc/config.php");
	include($caminho."sigadm/inc/conexao.php");
	include($caminho."sigadm/inc/funcoes.php");
	include($caminho."sigadm/inc/bancofuncoes.php");


	function trimRetira($str){
		return str_replace(';', '', trim($str));
	}

	$campanha = 1;

	//echo "Disparo: ".$_SERVER['REMOTE_ADDR'];
	//echo "<br />Início: ".date("d/m/Y H:i:s");

    $arquivoExportacao = @fopen($caminho."uploads/exportacao/".$ambiente.'_numeros_sorte_'.date('Y_m_d_H_i_s').".txt","w+");
    if (!$arquivoExportacao){
        echo "<br /><span class='obsNaoBaixa'>Erro ao criar o Arquivo.</span>";
    }else{ 

    	echo $linhaArquivo = 'DATA HORA INSCRIÇÃO'.
						";".
						'NOME'.
						";".
						'CPF'.
						";".
						'EMAIL'.
						";".
						'CELULAR'.
						";".
						'EMAIL'.
						";".
						'DATA NASCIMENTO'.
						";".
						'NOME DA MÃE'.
						";".
						'MATRÍCULA'.
						";".
						'UNIDADE'.
						";".
						'REFERÊNCIA'.
						";".
						'TIPO PARTICIPAÇÃO'.
						";".
						'NÚMERO DA SORTE'.
						PHP_EOL;
       	//fwrite($arquivoExportacao, $linhaArquivo);


    	$exe = executaSQL("SELECT p.*, e.data_participacao as dtInscricao, e.matricula, pu.unidade, pc.ano, pc.mes, pc.id_tipo_participacao, pc.id as idCupom
							FROM participante p LEFT JOIN evento_participante e ON e.id_participante=p.id 
							LEFT JOIN participante_unidade pu ON pu.id_participante=p.id 
							LEFT JOIN participante_cupom pc ON pc.id_unidade=pu.id 
							WHERE e.id_evento='".$campanha."' AND e.data_participacao<'2020-11-01 00:00:00' 
							ORDER BY e.data_participacao, pu.unidade, pc.id_tipo_participacao");
    	while( $reg = objetoPHP($exe) ){

    		$referencia = '';
    		if($reg->mes>0 && $reg->ano>0){
    			$referencia = $reg->mes.'/'.$reg->ano;
    		}

    		$tipoParticipacao = '';
    		switch ($reg->id_tipo_participacao) {
                case 1:
                    $tipoParticipacao = 'ADIMPLENTES';
                    break;
                case 2:
                    $tipoParticipacao = 'MODALIDADE PGT';
                    break;
                case 3:
                    $tipoParticipacao = 'FATURA EMAIL';
                    break;
            }

    		$numeroSorte = '';
    		if($reg->idCupom>0){
	    		$elemento = executaSQL("SELECT * FROM elemento_sorteavel WHERE id_participante_cupom='".$reg->idCupom."' AND id_evento='".$campanha."' ");
	    		if( nLinhas($elemento)>0){
	    			$elemento = objetoPHP($elemento);
					$numeroSorte = $elemento->elemento;
	    		}
	    	}

    		$linhaArquivo = trimRetira($reg->dtInscricao).
    						";".
    						trimRetira($reg->nome).
    						";".
    						trimRetira($reg->cpf).
    						";".
    						trimRetira($reg->email).
    						";".
    						trimRetira($reg->celular).
    						";".
    						trimRetira($reg->email).
    						";".
    						trimRetira($reg->dt_nascimento).
    						";".
    						trimRetira($reg->nome_mae).
    						";".
    						trimRetira($reg->matricula).
    						";".
    						trimRetira($reg->unidade).
    						";".
    						trimRetira($referencia).
    						";".
    						trimRetira($tipoParticipacao).
    						";".
    						trimRetira($numeroSorte).
    						PHP_EOL;

        	fwrite($arquivoExportacao, $linhaArquivo);
        }
        fclose($arquivoExportacao); 

	}

	//echo "<br />Término: ".date("d/m/Y H:i:s");
	
?>