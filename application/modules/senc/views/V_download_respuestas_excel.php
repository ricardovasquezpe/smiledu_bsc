<?php 
    $date = date('Y-m-d H:i:s');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Respuestas_'.$date.'.xlsm"');
    header('Cache-Control: max-age=0');
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    /*if($inputFileType == _HTML_TYPE) {
        throw new Exception("El archivo Excel no debe ser de tipo Página Web (HTML), guárdelo como libro de Excel");
    }*/
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
    
    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->save('php://output');
?>