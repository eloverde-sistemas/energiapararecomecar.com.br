    
    <h3 class="page-title">
        Clientes Sem Participação
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                DUPLICADOS - ELIMINAR
            </li>
        </ul>
    </div>

    
<?
    $x=0;
    $participantes = executaSQL("SELECT id, nome, cpf FROM participante p ORDER BY id");

    while($participante = objetoPHP($participantes)){

        $duplicados = executaSQL("SELECT id, nome, cpf FROM participante p WHERE cpf='".$participante->cpf."' AND id>'".$participante->id."' ");
        
        if(nLinhas($duplicados)>0){
            $x++;
            echo "<br /><br /><b>DUPLICADO ".$x.'</b>';
            echo "<br />".$participante->id." - ".$participante->nome." - ".$participante->cpf;

            while($duplicado = objetoPHP($duplicados)){

                //echo "<br /><br />Participante Unidade ";
                //$duplicado->id." - ".$duplicado->nome." - ".$duplicado->cpf;
                $partUnidades = executaSQL("SELECT * FROM participante_unidade WHERE id_participante='".$duplicado->id."' ");
                if(nLinhas($partUnidades)>0){
                    while($unidade = objetoPHP($partUnidades)){
                        //echo "<br /><br />Unidade ".$unidade->unidade;
                        
                        $partCupons = executaSQL("SELECT * FROM participante_cupom WHERE id_unidade='".$unidade->id."' ");
                        if(nLinhas($partCupons)>0){
                            while($cupom = objetoPHP($partCupons)){
                                //echo "<br />Cupom ".$cupom->id;

                                $elemento = executaSQL("SELECT * FROM elemento_sorteavel WHERE id_participante_cupom='".$cupom->id."' ");
                                if(nLinhas($elemento)>0){
                                    $elemento = objetoPHP($elemento);
                                    
                                    //echo "<br />Elemento ".$elemento->elemento;

                                    executaSQL("UPDATE elemento_sorteavel SET id_participante_cupom=0, dt_atribuicao=NULL WHERE id='".$elemento->id."'");
                                    //echo "<br>UPDATE elemento_sorteavel SET id_participante_cupom=0, dt_atribuicao=NULL WHERE id='".$elemento->id."'";
                                }
                            }

                            executaSQL("DELETE FROM participante_cupom WHERE id_unidade='".$unidade->id."'");
                            //echo "<br>DELETE FROM participante_cupom WHERE id_unidade='".$unidade->id."'";
                        }
                    }

                    executaSQL("DELETE FROM participante_unidade WHERE id_participante='".$duplicado->id."'");
                    //echo "<br>DELETE FROM participante_unidade WHERE id_participante='".$duplicado->id."'";
                }

                executaSQL("DELETE FROM evento_participante WHERE id_participante='".$duplicado->id."'");
                //echo "<br>DELETE FROM evento_participante WHERE id_participante='".$duplicado->id."'";
                executaSQL("DELETE FROM participante WHERE id='".$duplicado->id."'");
                //echo "<br>DELETE FROM participante WHERE id='".$duplicado->id."'";
            }
        }

    }
?>