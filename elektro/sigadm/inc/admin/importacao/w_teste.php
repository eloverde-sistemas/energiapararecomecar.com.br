<?php

    echo '<br>Ano: '.$anoAnterior = (date('m')==1)?date('Y')-1 :date('Y');
    echo '<br>Mês: '.$mesAnterior = (date('m')==1)?12 :formataNumeroComZeros(date('m') - 1, 2);

    $ano = date('Y');
    $mes = formataNumeroComZeros(date('m'), 2);
    
    echo '<br>SQL: '."SELECT p.*, ep.* FROM participante p, evento_participante ep 
                                    WHERE p.id=ep.id_participante 
                                    AND ep.id_evento='".$campanha."' 
                                    AND data_participacao <= '".$ano."-".$mes."-01 00:00:00' 
                                    ORDER BY data_participacao ASC";

?>