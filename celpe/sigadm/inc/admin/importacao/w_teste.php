<?php

    echo '<br>Ano: '.$anoAnterior = (date('m')==1)?date('Y')-1 :date('Y');
    echo '<br>Mês: '.$mesAnterior = (date('m')==1)?12 :formataNumeroComZeros(date('m') - 1, 2);

?>