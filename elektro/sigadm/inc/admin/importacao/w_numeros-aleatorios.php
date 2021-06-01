<?php

        $numeroAletorio = array();
        $numeroAletorio[] = rand(44000000, 49000000);
        $numeroAletorio[] = rand(44000000, 49000000);
        $numeroAletorio[] = rand(44000000, 49000000);
        $numeroAletorio[] = rand(44000000, 49000000);
        $numeroAletorio[] = rand(44000000, 49000000);
        //echo "<br /><br />";
        $exeNumero = executaSQL("SELECT id, elemento FROM elemento_sorteavel WHERE id_evento='1' AND id_participante_cupom='0' AND id IN (".implode(',', $numeroAletorio).") ");
        if( nLinhas($exeNumero)>0 ){


            $elemento_sorteavel = objetoPHP($exeNumero);
            echo "<br>Id Achado:".$idAchado = $elemento_sorteavel->id;
            echo "<br>Numero Achado:".$numeroAchado = $elemento_sorteavel->elemento;
            
        }

?>