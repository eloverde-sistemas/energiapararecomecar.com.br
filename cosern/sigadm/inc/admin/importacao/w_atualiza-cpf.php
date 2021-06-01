    
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

    $participantes = executaSQL("SELECT * FROM participante p WHERE LENGTH(cpf)<14 ORDER BY id");

    while($participante = objetoPHP($participantes)){

        echo "<br /><br />".$participante->id." - ".$participante->nome." - ".$participante->cpf;


        $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $participante->cpf), 11);

        $cpf = substr($cpfFor, 0,3).'.'.substr($cpfFor, 3,3).".".substr($cpfFor, 6,3).'-'.substr($cpfFor, 9);
        //echo "<br /><br />CPF: ".$cpf;

        executaSQL("UPDATE participante SET cpf='".$cpf."' WHERE id='".$participante->id."'");
    }
?>