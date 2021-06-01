<?php

$idCampanha = $_GET['id'];

$campanha = executaSQL("SELECT * FROM evento WHERE id= '".$idCampanha."' ");
if( nLinhas($campanha)>0 ){
	//CAMPANHA: 1-cadastre_e_participe, 2-cadastre_e_ganhe, 3-cnpj_cupom, 4-codigo
	$campanha = objetoPHP($campanha);


	$lojasPart		= executaSQL("SELECT l.id, l.nome_fantasia, l.cnpj FROM loja l, evento_loja el WHERE el.id_loja=l.id AND el.id_evento='".$campanha->id."' ORDER BY nome_fantasia");



	$styleArray = array(
						'font' => array(
						'bold' => true
						)
					);
	
	header('Content-Disposition: attachment;filename="correlacao_lojas_'.date("Y_m_d_H_i_s").'.xls"');

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', utf8_encode( $campanha->titulo." - ".$translate->translate('loja_correlacao') ));

	$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);


	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A2', utf8_encode($translate->translate('loja')))
				->setCellValue('B2', utf8_encode($translate->translate('correlacoes')));


	$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($styleArray);

	
	$objPHPExcel->getActiveSheet()->setTitle('lojas');

	$dados = executaSQL("SELECT l.id, l.nome_fantasia, l.cnpj FROM loja l, evento_loja el WHERE el.id_loja=l.id AND el.id_evento='".$campanha->id."' ORDER BY nome_fantasia");
	$count = 2;
	
	if(nLinhas($dados)){
		while($dado = objetoPHP($dados)){ 
			$count++;

			$correlacao = array();
			$partCorrelacionados = executaSQL("SELECT DISTINCT(c.id_participante) as idPart
													FROM participante_cupom c, loja l
													WHERE c.id_evento='".$campanha->id."'
													AND c.cnpj = l.cnpj 
													AND REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '')=REPLACE(REPLACE(REPLACE('".$dado->cnpj."', '.', ''), '/', ''), '-', '')
													AND c.id_situacao IN (2,90)");
			if(nLinhas($partCorrelacionados)>0){ 
				$arrayExiste = array();
				while($partCorrelacionado = objetoPHP($partCorrelacionados)){ 
				
					$cuponsCorrelacionados = executaSQL("SELECT l.nome_fantasia, l.cnpj, REPLACE(REPLACE(REPLACE(l.cnpj, '.', ''), '/', ''), '-', '') as cnpjFormatado
														FROM participante_cupom c, loja l
														WHERE c.id_evento='".$campanha->id."'
														AND c.cnpj = l.cnpj 
														AND REPLACE(REPLACE(REPLACE(c.cnpj, '.', ''), '/', ''), '-', '')<>REPLACE(REPLACE(REPLACE('".$dado->cnpj."', '.', ''), '/', ''), '-', '')
														AND c.id_participante='".$partCorrelacionado->idPart."'
														AND c.id_situacao IN (2,90)");
													
					if(nLinhas($cuponsCorrelacionados)>0){ 
							
						while($cupomCorrelacionado = objetoPHP($cuponsCorrelacionados)){ 
								
							//var_dump($arrayExiste);

							if( !in_array($cupomCorrelacionado->cnpjFormatado, $arrayExiste) ){
								$arrayExiste[] = $cupomCorrelacionado->cnpjFormatado;
								//echo "<BR><strong>ACHO</strong>: ".$cupomCorrelacionado->cnpj;
								$correlacao[] = $cupomCorrelacionado->nome_fantasia." (".$cupomCorrelacionado->cnpj.")";
							}
						} 

					} 
				
				}

			}
			
			$correlacao = (count($correlacao)>0)?implode("\n", $correlacao) :'';
			
			$objPHPExcel->setActiveSheetIndex(0)					
						->setCellValue('A'.$count, utf8_encode($dado->nome_fantasia))
						->setCellValue('B'.$count, utf8_encode($correlacao));
		}
	}

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()
    ->getStyle('A3:A'.$count)
    ->getAlignment()
    ->setWrapText(true);
	
}else{
	echo "Campanha não encontrada";
}
?>