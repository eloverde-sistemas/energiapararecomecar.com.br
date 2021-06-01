<?php

    $campanha = 1;

    $participantes = executaSQL("SELECT p.* FROM participante p ORDER BY id ASC");

    if( nLinhas($participantes)>0 ){
        while( $participante = objetoPHP($participantes) ){
            
            $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", trim($participante->cpf)),11);

            $regs = executaSQLPadrao('base_cliente', "cpf = '".$cpfFor."' AND id_situacao='1' ORDER BY unidade");
            if( nLinhas($regs)>0 ){
                while($reg = objetoPHP($regs)){
                    
                    $unidadeFor = formataNumeroComZeros($reg->unidade,12);

                    $participanteUnidade = executaSQL("SELECT * FROM participante_unidade WHERE id_evento='".$campanha."' AND id_participante='".$participante->id."' AND unidade='".$unidadeFor."' ", true);
                    if( nLinhas($participanteUnidade)>0 ){

                    }else{

                        echo '<br><br>CPF '.$cpfFor;
                        echo '<br>CC '.$unidadeFor;

                        $dadosPartUnid = array();
                        $dadosPartUnid['id_evento'] = $campanha;
                        $dadosPartUnid['id_participante'] = $participante->id;
                        $dadosPartUnid['unidade'] = $unidadeFor;
                        $dadosPartUnid['id_situacao'] = 1;

                        inserirDados("participante_unidade", $dadosPartUnid);
                    }
                
                }
            }

        }
    }

?>