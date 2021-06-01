    
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
    $x=0;
    $participantes = executaSQL("SELECT id, nome, cpf FROM participante p ORDER BY id");

    while($participante = objetoPHP($participantes)){

        $duplicados = executaSQL("SELECT id, nome, cpf FROM participante p WHERE cpf='".$participante->cpf."' AND id>'".$participante->id."' ");
        
        if(nLinhas($duplicados)>0){
            $x++;
            echo "<br /><br />DUPLICADO".$x;
            echo "<br />".$participante->id." - ".$participante->nome." - ".$participante->cpf;

            while($duplicado = objetoPHP($duplicados)){

                echo "<br /><br />".$duplicado->id." - ".$duplicado->nome." - ".$duplicado->cpf;
            }
        }

    }
?>