<?php
	
	$id = intval($_GET['evento']);

	$dt_inicio 	= trim($_GET['dt_inicio']);
	$dt_fim 	= trim($_GET['dt_fim']);

	$nome 	= trim($_GET['nome']);
	$cpf 	= trim($_GET['cpf']);	

	
	if($id>0){
		$exe = executaSQL("SELECT * FROM evento WHERE id = '".$id."'");
		if(nLinhas($exe)>0){
			$reg=objetoPHP($exe);
			
		}else{
			header("Location: /adm/admin/eventos/listar");
			die();
		}
	}

	$styleArray = array(
						'font' => array(
						'bold' => true
						)
					);
	
	header('Content-Disposition: attachment;filename="participantes_campanha_'.$dt_inicio.'_'.$dt_fim.'.xls"');

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', utf8_encode($translate->translate('dt_hr')))
				->setCellValue('B1', utf8_encode($translate->translate('nome')))
				->setCellValue('C1', utf8_encode($translate->translate('cpf')))
				->setCellValue('D1', utf8_encode($translate->translate('email')))
				->setCellValue('E1', utf8_encode($translate->translate('celular')))
				->setCellValue('F1', utf8_encode($translate->translate('telefone')))
				->setCellValue('G1', utf8_encode($translate->translate('data_nascimento')))
				
				->setCellValue('H1', utf8_encode($translate->translate('nome_mae')))
				->setCellValue('I1', utf8_encode($translate->translate('matricula')))
				
				->setCellValue('J1', utf8_encode($translate->translate('unidades')));


	$objPHPExcel->getActiveSheet()->getStyle('B1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);

	
	$objPHPExcel->getActiveSheet()->setTitle('participantes');

	$dados = executaSQL("SELECT p.*, e.data_participacao as dtInscricao, e.matricula 
							FROM participante p LEFT JOIN evento_participante e ON e.id_participante=p.id 
							WHERE 1=1 ORDER BY e.data_participacao, p.id");
	$count = 1;
	
	if(nLinhas($dados)){
		while($dado = objetoPHP($dados)){
			$count++;

			$unidades = "";
			$unis 	  = array();
			
			$uniParts = executaSQL("SELECT * FROM participante_unidade WHERE id_evento='".$id."' AND id_participante='".$dado->id."' ");
			if(nLinhas($uniParts)>0){

				while( $uniPart = objetoPHP($uniParts) ){
					$unis[] = '"'.$uniPart->unidade.'"';
				}

				if( count($unis)>0 ){
					$unidades = implode(", ", $unis);
				}
			}

			$objPHPExcel->setActiveSheetIndex(0)					
						->setCellValue('A'.$count, utf8_encode(converteDataHora($dado->dtInscricao)))
						->setCellValue('B'.$count, utf8_encode($dado->nome))
						->setCellValue('C'.$count, utf8_encode($dado->cpf))
						->setCellValue('D'.$count, utf8_encode($dado->email))
						->setCellValue('E'.$count, utf8_encode($dado->celular))
						->setCellValue('F'.$count, utf8_encode($dado->telefone))
						->setCellValue('G'.$count, utf8_encode(converteDataHora($dado->dt_nascimento)))
						
						->setCellValue('H'.$count, utf8_encode($dado->nome_mae))
						->setCellValue('I'.$count, utf8_encode($dado->matricula))
						->setCellValue('J'.$count, utf8_encode($unidades));
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
?>