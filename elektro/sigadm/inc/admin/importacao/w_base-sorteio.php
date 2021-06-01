    
    <h3 class="page-title">
        <?=$translate->translate('importacao')?> <small>Base de Sorteio</small>
    </h3>
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="/adm"><?=$translate->translate('inicio')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="/adm/admin/midia/listar"><?=$translate->translate('importacao')?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                Base de Sorteio
            </li>
        </ul>
    </div>

    
<?

    $anoMesAnterior = date('Y').'_'.formataNumeroComZeros(date(m) - 1, 2);

    //abre o arquivo somente para leitura (fopen "r")
    echo $_SERVER["DOCUMENT_ROOT"]."/uploads/base-sorteio/".$anoMesAnterior.".txt";
    
    $lendo = @fopen($_SERVER["DOCUMENT_ROOT"]."/uploads/base-sorteio/".$anoMesAnterior.".txt", "r");
    if (!$lendo){
        echo "<br /><span class='obsNaoBaixa'>Erro ao ler o Arquivo.</span>";
    }else{ 
        echo "<br>".date('d/m/Y h:i:s')."<br><br>";
        $x = 0;

        while(!feof($lendo)){
            $x++;

            $linha = fgets($lendo);

            $dados = explode(";", $linha);

            $unidadeFor     = formataNumeroComZeros(preg_replace("/[^0-9]/", "", trim($dados[0])), 12);

            $tipo = trim($dados[1]);
            switch ($tipo) {
                case 'ADIMPLENTES':
                    $tipoFor = 1;
                    break;
                case 'MODALIDADE PGT':
                    $tipoFor = 2;
                    break;
                case 'FATURA EMAIL':
                    $tipoFor = 3;
                    break;
            }

            $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", trim($dados[2])), 11);

/*
            $regs = executaSQL("SELECT * FROM base_sorteio WHERE unidade = '".$unidadeFor."' AND cpf = '".$cpfFor."' AND id_tipo = '".$tipoFor."'");

            if( nLinhas($regs)>0 ){
                echo "<br>CC ".$unidadeFor.", CPF ".$cpfFor." e Modalidade '".$tipo."' já inserido.";
            }else{
*/
                $dadosCliente = array();

                $dadosCliente['id_evento']  = 1;
                $dadosCliente['unidade']    = $unidadeFor;
                $dadosCliente['cpf']        = $cpfFor;
                $dadosCliente['id_tipo']    = $tipoFor;
                $dadosCliente['ano_mes']    = $anoMesAnterior;
            
                inserirDados("base_sorteio", $dadosCliente);
  /*          }*/   
        }

        echo "<br><br>".date('d/m/Y h:i:s');
    }

?>