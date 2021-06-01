<?php

	$styleArray = array(
						'font' => array(
						'bold' => true
						)
					);
	
	header('Content-Disposition: attachment;filename="participantes_geral_'.date("Y_m_d_H_i_s").'.xls"');

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', utf8_encode($translate->translate('nome')))
				->setCellValue('B1', utf8_encode($translate->translate('cpf')))
				->setCellValue('C1', utf8_encode($translate->translate('sexo')))
				->setCellValue('D1', utf8_encode($translate->translate('email')))
				->setCellValue('E1', utf8_encode($translate->translate('celular')))
				->setCellValue('F1', utf8_encode($translate->translate('telefone')))
				->setCellValue('G1', utf8_encode($translate->translate('data_nascimento')))
				
				->setCellValue('H1', utf8_encode($translate->translate('logradouro')))
				->setCellValue('I1', utf8_encode($translate->translate('numero')))
				->setCellValue('J1', utf8_encode($translate->translate('complemento')))
				->setCellValue('K1', utf8_encode($translate->translate('bairro')))
				->setCellValue('L1', utf8_encode($translate->translate('cidade')))
				->setCellValue('M1', utf8_encode($translate->translate('estado')))
				->setCellValue('N1', utf8_encode($translate->translate('cep')))
				
				->setCellValue('O1', utf8_encode($translate->translate('recebe_email')));


	$objPHPExcel->getActiveSheet()->getStyle('B1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);

	
	$objPHPExcel->getActiveSheet()->setTitle('participantes');

	$dados = executaSQL("SELECT * FROM participante ORDER BY nome");
	$count = 1;
	
	if(nLinhas($dados)){
		while($dado = objetoPHP($dados)){
			$count++;

			$cidade = '';
			$estado = '';
			
			if($dado->id_cidade > 0){
				$cidade = getMuniciopioById($dado->id_cidade);
				$estado = getEstadoSiglaByMuniciopioId($dado->id_cidade);
			}

			$objPHPExcel->setActiveSheetIndex(0)					
						->setCellValue('A'.$count, utf8_encode($dado->nome))
						->setCellValue('B'.$count, utf8_encode($dado->cpf))
						->setCellValue('C'.$count, utf8_encode($dado->sexo==1 ? "Masculino" : "Feminino"))
						->setCellValue('D'.$count, utf8_encode($dado->email))
						->setCellValue('E'.$count, utf8_encode($dado->celular))
						->setCellValue('F'.$count, utf8_encode($dado->telefone))
						->setCellValue('G'.$count, utf8_encode($dado->dt_nascimento))
						
						->setCellValue('H'.$count, utf8_encode($dado->logradouro ? $dado->logradouro : ""))
						->setCellValue('I'.$count, utf8_encode($dado->numero ? $dado->numero : ""))
						->setCellValue('J'.$count, utf8_encode($dado->complemento ? $dado->complemento : ""))
						->setCellValue('K'.$count, utf8_encode($dado->bairro ? $dado->bairro : ""))
						->setCellValue('L'.$count, utf8_encode($cidade ? $cidade : ""))
						->setCellValue('M'.$count, utf8_encode($estado ? $estado : ""))
						->setCellValue('N'.$count, utf8_encode($dado->cep ? $dado->cep : ""))
						
						->setCellValue('O'.$count, utf8_encode($dado->receber_email==1 ?"SIM" :"NÃO" ));
		}
	}

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);				
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);				
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);				
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);				
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
?>