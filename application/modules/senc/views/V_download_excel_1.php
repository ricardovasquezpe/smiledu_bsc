<?php 
    $date = date('Y-m-d H:i:s');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte_'.$date.'.xlsx"');
    header('Cache-Control: max-age=0');
    
    $objWorksheet = $objPHPExcel->getActiveSheet();
 
    
    
    /*AÑADIR TITULO*/
    $objRichText = new PHPExcel_RichText();
    $objBold = $objRichText->createTextRun('RESPUESTAS');
    $objBold->getFont()->setBold(true);
    $objWorksheet->getCell('D3')->setValue($objRichText);
    $objWorksheet->mergeCells('D3:G3');
    $objWorksheet->getStyle('D3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    /*FIN AÑADIR TITULO*/
    
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('Logo Avantgard');
    $objDrawing->setDescription('Logo Avantgard');
    $logo = './public/modulos/senc/img/logo/avantgard_logo.png';
    $objDrawing->setPath($logo);
    $objDrawing->setOffsetX(8);
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(90);
    $objDrawing->setWorksheet($objWorksheet);
    
    function letters(){
        $letters = array();
        $letter = 'A';
        while ($letter !== 'AAA'){
            $letters[] = $letter++;
        }
    
        return $letters;
    }
    $letters = letters();
    $idEnc = null;
    $countEncuestas = count(array_keys($array));
    
    $contAux = 0;
    $row = 0;
    
    $styleArray = array(
                  'font'  => 
                      array(
                          'size'  => 7,
                          'name'  => 'Century Gothic'
                     ),
                  'alignment' => array(
                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                 )
                 );
    $arrayEncLimit = array();
    foreach($array as $encuesta){
        $contPreg = 1;
        array_push($arrayEncLimit, $row);
        foreach ($encuesta as $pregunta){
            $inicio = 8;
            $objWorksheet->getCell($letters[$row].($inicio))->setValueExplicit(($contPreg).'. '.$pregunta['desc_preg'], PHPExcel_Cell_DataType::TYPE_STRING);
            $objWorksheet->getColumnDimension($letters[$row])->setAutoSize(true);
            $objWorksheet->getStyle($letters[$row].($inicio))->applyFromArray($styleArray);
            $inicio++;
            foreach($pregunta['rpta'] as $rpta){
                $descAux = null;
                foreach($rpta as $desc){
                    $descAux .= $desc.';';
                }
                $descAux = substr($descAux, 0,strlen($descAux)-1);
                $objWorksheet->getCell($letters[$row].($inicio))->setValueExplicit($descAux, PHPExcel_Cell_DataType::TYPE_STRING);
                $objWorksheet->getStyle($letters[$row].($inicio))->applyFromArray($styleArray);
                $inicio++;
            }
            $contPreg++;
            $row++;
        }
    }
    $row = 0;
    $inicio = 7;
    foreach($encuestas as $desc_enc){
        $objWorksheet->getCell($letters[$arrayEncLimit[$row]].($inicio))->setValueExplicit($desc_enc, PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getStyle($letters[$arrayEncLimit[$row]].($inicio))->applyFromArray($styleArray);
        $row++;
    }
//     $contAux = 0;
//     $inicio = 2;
//     $inicioAux = 2;
//     foreach($array as $encuesta){
//         $row = 0;
//         $inicioAux = ($contAux != 0) ? $inicio + 2 : 2;
//         $inicio    = $inicioAux;
//         $countPreguntas = count($encuesta);
//         foreach ($encuesta as $pregunta){
//             $objWorksheet->getCell($letters[$row].($inicio))->setValueExplicit($pregunta['desc_preg'], PHPExcel_Cell_DataType::TYPE_STRING);
//             $inicio++;
//             foreach($pregunta['rpta'] as $rpta){
//                 if(isset($rpta['rpta'])){
//                     $objWorksheet->getCell($letters[$row].($inicio))->setValueExplicit($rpta['rpta'], PHPExcel_Cell_DataType::TYPE_STRING);
//                 } else{
//                     $objWorksheet->getCell($letters[$row].($inicio))->setValueExplicit(null, PHPExcel_Cell_DataType::TYPE_STRING);
//                 }
//                 $inicio++;
//             }
//             $countPreguntas--;
//             $inicio = ($countPreguntas == 0) ? $inicio : $inicioAux;
//             $row++;
//         }
//         $contAux++;
//     }

    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->setIncludeCharts(TRUE);
    
    /*$objWorksheet->setTitle("DATA");
     $objWorkSheet_1->setTitle("REPORTE");*/
    
    $writer->save('php://output');
?>