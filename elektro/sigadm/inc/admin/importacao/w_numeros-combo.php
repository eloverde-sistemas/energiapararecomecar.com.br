<?php

    //INSERE CUPOM PARA O PARTICIPANTE SE ENCONTRAR O TIPO DE PARTICIPAÇÃO
    function insereParticipanteCupom($campanhaId, $unidadeId, $tipoParticipacao){
        $idCupom = 0;

        if($tipoParticipacao>0){

            $anoAnterior = (date('m')==1)?date('Y')-1 :date('Y');
            $mesAnterior = (date('m')==1)?12 :formataNumeroComZeros(date('m') - 1, 2);

            $idPartCupom = proximoId('participante_cupom');

            $dados = array();
            $dados['id'] = $idPartCupom;
            $dados['id_unidade'] = $unidadeId;
            $dados['id_tipo_participacao'] = $tipoParticipacao;
            $dados['id_situacao'] = 1;
            $dados['ano'] = $anoAnterior;
            $dados['mes'] = $mesAnterior;

            if( inserirDados('participante_cupom', $dados) ){
                $idCupom = $idPartCupom;    
            }

        }

        return $idCupom;
    }

    function atribuiElementoSorteavel($campanha){
        //conectaBanco();
        $achou = false;
        while(!$achou){
            $numeroAletorio = array();
            $numeroAletorio[] = rand(0, 10000000);
            $numeroAletorio[] = rand(10000000, 20000000);
            $numeroAletorio[] = rand(20000000, 30000000);
            $numeroAletorio[] = rand(30000000, 40000000);
            $numeroAletorio[] = rand(40000000, 50000000);
            $numeroAletorio[] = rand(50000000, 60000000);
            $numeroAletorio[] = rand(60000000, 70000000);
            $numeroAletorio[] = rand(70000000, 80000000);
            $numeroAletorio[] = rand(80000000, 90000000);
            $numeroAletorio[] = rand(90000000, 100000000);

            shuffle($numeroAletorio);

            //echo "<br /><br />";
            $exeNumero = executaSQL("SELECT id, elemento FROM elemento_sorteavel WHERE id_evento='".$campanha."' AND id_participante_cupom='0' AND id IN (".implode(',', $numeroAletorio).") ");
            if( nLinhas($exeNumero)>0 ){

                $achou = true;

                $elemento_sorteavel = objetoPHP($exeNumero);
                //echo "<br>Id Achado:".
                $idAchado = $elemento_sorteavel->id;
                //echo "<br>Numero Achado:".
                $numeroAchado = $elemento_sorteavel->elemento;
                
            }
        }

        return array('id'=>$idAchado, 'numero'=>$numeroAchado);
    }



    $campanha = 1; //seta a campanha


    $ano = date('Y');
    $mes = formataNumeroComZeros(date('m'), 2);


    $participantes = executaSQL("SELECT p.*, ep.* FROM participante p, evento_participante ep 
                                    WHERE p.id=ep.id_participante 
                                    AND ep.id_evento='".$campanha."' 
                                    ORDER BY data_participacao ASC LIMIT 10", true);


    if( nLinhas($participantes)>0 ){
        while( $participante = objetoPHP($participantes) ){
            
            $cpfFor = formataNumeroComZeros(preg_replace("/[^0-9]/", "", trim($participante->cpf)),11);

            //echo '<br><br>'.$participante->data_participacao.' Participante '.$participante->nome.' CPF '.$cpfFor;

            $regs = executaSQLPadrao('participante_unidade', "id_participante = '".$participante->id."' AND id_evento='".$campanha."' ORDER BY unidade");
            if( nLinhas($regs)>0 ){
                while($reg = objetoPHP($regs)){
                    
                    //echo '<br>CC '.
                    $unidadeFor = formataNumeroComZeros($reg->unidade,12);

                    $tiposParticipacao = executaSQL("SELECT * FROM base_combo WHERE id_evento='".$campanha."' AND unidade='".$unidadeFor."' AND cpf='".$cpfFor."' ");
                    if( nLinhas($tiposParticipacao)>0 ){
                        $tipoParticipacao = objetoPHP($tiposParticipacao);

                        //echo "<br>Qtde Combo Perfeito: ".
                        $qtdeCupom = $tipoParticipacao->qtde;

                        $jaInserido = executaSQL("SELECT * FROM participante_cupom WHERE id_unidade='".$reg->id."' AND id_tipo_participacao='4' ");
                        
                        if( nLinhas($jaInserido)>0 ){
                            echo '<br> Cupom já inserido para esta Unidade '.$reg->id.', Tipo de Participação COMBO PERFEITO.';
                        }else{
                            //echo "<br>Insere Cupom: ";
                            for($x=1;$x<=$qtdeCupom;$x++){
                                //echo " $x,";
                                $cupomPart = insereParticipanteCupom($campanha, $reg->id, 4);

                                if($cupomPart>0){

                                    //echo "<br>Cupom: ".$cupomPart;

                                    $elemSorteavel = atribuiElementoSorteavel($campanha);

                                    if($elemSorteavel['id']>0){

                                        //echo "<br>Id Achado:".
                                        $idAchado = $elemSorteavel['id'];
                                        //echo "<br>Numero Achado:".
                                        $numeroAchado = $elemSorteavel['numero'];

                                        $dadosCupom = array();
                                        $dadosCupom['id_evento'] = $campanha;
                                        $dadosCupom['id_participante_cupom'] = $cupomPart;
                                        $dadosCupom['dt_atribuicao'] = date('YmdHis');

                                        if( alterarDados('elemento_sorteavel', $dadosCupom, "id='".$idAchado."'") ){

                                            //echo "<br>Elemento Atualizado com sucesso: ".$tipoParticipacao->unidade.' - '.$participante->id.' - '.$tipoParticipacao->id_tipo.' - '.$cupomPart;

                                        }else{
                                            echo "<br>Erro ao atualizar o ES: ".$tipoParticipacao->unidade.' - '.$participante->id.' - '.$tipoParticipacao->id_tipo.' - '.$cupomPart;
                                        }
                                    }else{
                                        echo "<br>Sem atribuir elemento sorteável: ".$tipoParticipacao->unidade.' - '.$participante->id.' - '.$tipoParticipacao->id_tipo.' - '.$cupomPart;
                                    }
                                }else{
                                    echo "<br>Erro ao Inserir Cupom para o Participante: ".$importacao->unidade.' - '.$importacao->cliente.' - '.$importacao->tipo;
                                }*/
                            }
                        }

                        
                    }
                
                }
            }

        }
    }

?>