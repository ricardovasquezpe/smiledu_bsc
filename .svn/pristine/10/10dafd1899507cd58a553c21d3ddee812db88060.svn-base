<?php
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$nombreArchivo.'.xlsx"');
    header('Cache-Control: max-age=0');
    
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $objPHPExcel->getActiveSheet()->setTitle(utf8_encode('Correos') );
    
    if($msj != null) {
        $objWorksheet->getCell('A1')->setValueExplicit(utf8_encode($msj), PHPExcel_Cell_DataType::TYPE_STRING);
    } else {
        if(isset($correos) && is_array($correos) && count($correos) > 0) {
            $fila = 1;
            foreach ($correos as $row) {
                $objWorksheet->getCell('A'.$fila)->setValueExplicit(utf8_encode($row->correo), PHPExcel_Cell_DataType::TYPE_STRING);
                $fila++;
            }
        }
    }
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(TRUE);
    
    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->save('php://output');
    exit;