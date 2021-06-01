    
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
                Clientes Sem Participação
            </li>
        </ul>
    </div>

    
<?

    $participantes = executaSQL("SELECT * FROM participante p WHERE NOT EXISTS (SELECT 1 FROM evento_participante ep WHERE ep.id_participante=p.id)");

    while($participante = objetoPHP($participantes)){

        echo "<br /><br />".$participante->nome." - ".$participante->cpf;

        $unidades = executaSQL("SELECT * FROM base_cliente WHERE cpf=REPLACE(REPLACE('".$participante->cpf."', '.', ''), '-', '')");

        if(nLinhas($unidades)>0){

            $ultimaData = objetoPHP(executaSQL("SELECT * FROM evento_participante ep WHERE ep.id_participante<'".$participante->id."' ORDER BY id_participante DESC"))->data_participacao;

            echo " - ".$ultimaData;
            
            inserirDados("evento_participante", array("id_evento"=>1, "id_participante"=>$participante->id, "data_participacao"=>$ultimaData));

            while($unidade = objetoPHP($unidades)){
                $participanteUnidade = executaSQL("SELECT * FROM participante_unidade WHERE id_evento='1' AND id_participante='".$participante->id."' AND unidade='".$unidade->unidade."'", true);
                if(nLinhas($participanteUnidade)==0){
                    echo "<br />INSERIR UNIDADE ".$unidade->unidade;
                    inserirDados("participante_unidade", array("id_evento"=>1, "id_participante"=>$participante->id, "unidade"=>$unidade->unidade, "id_situacao"=>1));
                }

            }

        }else{
            echo "<br />NÃO TEM UNIDADE";
        }

    }
?>