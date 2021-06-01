    
    <h3 class="page-title">
        <?=$translate->translate('importacao')?> <small>Base de Clientes</small>
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
                Base de Clientes
            </li>
        </ul>
    </div>

    
<?


    //abre o arquivo somente para leitura (fopen "r")
    $lendo = @fopen($_SERVER["DOCUMENT_ROOT"]."/uploads/base-cliente/csr_v2.txt", "r");
    if (!$lendo){
        echo "<br /><span class='obsNaoBaixa'>Erro ao ler o Arquivo.</span>";
    }else{ 
        echo "<br>".date('d/m/Y h:i:s')."<br><br>";
        $x = 0;
        while(!feof($lendo)){
            $x++;
            /*if($x>20){
                break;
            }*/

            $linha = fgets($lendo);

            $dados = explode(";", $linha);

            $unidadeFor     = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[0]), 12);

            $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", $dados[1]), 11);


            $regs = executaSQL("SELECT * FROM base_cliente WHERE unidade = '".$unidadeFor."'");
/*          if( nLinhas($regs)>0 ){

                $reg = objetoPHP($regs);

                echo $reg->unidade.", ";
                if( $reg->cpf != $cpfFor ){
                    alterarDados("base_cliente", array("cpf"=>$cpfFor), "unidade='".$unidadeFor."'");
                }
*/
            if( nLinhas($regs)==0 ){
                inserirDados("base_cliente", array("cpf"=>$cpfFor, "unidade"=>$unidadeFor), true);
            }   
        }

        echo "<br><br>".date('d/m/Y h:i:s');
    }

?>