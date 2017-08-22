<?php 
    $date = date('Y-m-d H:i:s');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte_'.$date.'.xlsx"');
    header('Cache-Control: max-age=0');
    
    $objWorksheet = $objPHPExcel->getActiveSheet();
    
    $cuotas = null;
    if(isset($datos[0])){
        $cuotas = explode(',', $datos[0]['cuotas']);
        $i = 0;
        foreach($cuotas as $row){
            $cuotas[$i] = explode('|', $row);
            $i++;
        }
    }
    function letters(){
        $letters = array();
        $letter = 'A';
        while ($letter !== 'AAAA'){
            $letters[] = $letter++;
        }
    
        return $letters;
    }
    $styleArray = array(
                        'font'  =>
                        array(
                            'size'  => 11,
                        ),
                        'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        )/*,
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'DFDFDF')
                        )*/
                    );
    $letters = letters();
    
    //BEGIN CABECERA CUOTAS
    $cont   = 1;
    $inicio = 7;
    $arrayDatos = array('Monto Cuota','Mora','Monto Cobrado','Fecha Pago');
    $key    = 0;
    $lastColumn = 7;
    if($cuotas[0][0] != null){
        foreach($cuotas as $cuota){
            $objWorksheet->getCell($letters[$inicio].$cont)->setValueExplicit(utf8_encode($cuota[1]), PHPExcel_Cell_DataType::TYPE_STRING);
            $objWorksheet->mergeCells($letters[$inicio].$cont.':'.$letters[$inicio+3].$cont);
            $objWorksheet->getColumnDimension($letters[$inicio])->setAutoSize(true);
            $objWorksheet->getStyle($letters[$inicio].($cont))->applyFromArray($styleArray);
            
            $objWorksheet->getCell($letters[$inicio].($cont+1))->setValueExplicit(utf8_encode($cuota[2]), PHPExcel_Cell_DataType::TYPE_STRING);
            $objWorksheet->mergeCells($letters[$inicio].($cont+1).':'.$letters[$inicio+3].($cont+1));
            $objWorksheet->getStyle($letters[$inicio].($cont+1))->applyFromArray($styleArray);
            $arrayColumnas = array();            
            for($i = $inicio,$j=0; $i <= $inicio+3 ; $i++,$j++){
                $objWorksheet->getCell($letters[$i].($cont+2))->setValueExplicit(utf8_encode($arrayDatos[$j]), PHPExcel_Cell_DataType::TYPE_STRING);
                $objWorksheet->getColumnDimension($letters[$i])->setAutoSize(true);
                $objWorksheet->getStyle($letters[$i].($cont+2))->applyFromArray($styleArray);
                array_push($arrayColumnas, $letters[$i]);
                $lastColumn++;
            }
//             $objWorksheet->getStyle($letters[$i].($cont+2))->applyFromArray($styleArray);
            if ($key % 2 == 0) {
                $styleArray['fill'] = array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DFDFDF')
                );
                $objWorksheet->getStyle($arrayColumnas[0].'1:'.$arrayColumnas[3].(count($datos)+17))->applyFromArray($styleArray);
                unset($styleArray['fill']);
            }
            $arrayAux = array($cuota[0],$cuota[1],$cuota[2],$arrayColumnas);
            $cuotas[$key] = $arrayAux;
            $inicio = $inicio + 3;
            $key++;
            $inicio++;
        }
    }
    //END CABECERA CUOTAS
    
    //BEGIN CABECERAS PERSONAS
    $cont   = 3;
    $arrayCabeceras = array('N°','Sede','Nivel','Grado','Aula','Código','Alumno');
    for($i = 0 ; $i < 7 ; $i++){
        $objWorksheet->getCell($letters[$i].$cont)->setValueExplicit(utf8_encode($arrayCabeceras[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getColumnDimension($letters[$i])->setAutoSize(true);
        $objWorksheet->getStyle($letters[$i].($cont))->applyFromArray($styleArray);
    }
    //END CABECERAS
    
    //BEGIN MONTOS DE CADA PERSONA POR CADA CUOTA
    $cont++;
    $inicio = 0;
    foreach($datos as $row){
        $objWorksheet->getCell($letters[$inicio].$cont)->setValueExplicit($cont-3, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objWorksheet->getColumnDimension($letters[$inicio])->setAutoSize(true);
        
        $objWorksheet->getCell($letters[$inicio+1].$cont)->setValueExplicit(utf8_encode($row['desc_sede']), PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getColumnDimension($letters[$inicio+1])->setAutoSize(true);
        
        $objWorksheet->getCell($letters[$inicio+2].$cont)->setValueExplicit(utf8_encode($row['desc_nivel']), PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getColumnDimension($letters[$inicio+2])->setAutoSize(true);
        
        $objWorksheet->getCell($letters[$inicio+3].$cont)->setValueExplicit(utf8_encode($row['desc_grado']), PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getColumnDimension($letters[$inicio+3])->setAutoSize(true);
        
        $objWorksheet->getCell($letters[$inicio+4].$cont)->setValueExplicit(utf8_encode($row['desc_aula']), PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getColumnDimension($letters[$inicio+4])->setAutoSize(true);
        
        $objWorksheet->getCell($letters[$inicio+5].$cont)->setValueExplicit(utf8_encode($row['cod_alumno']), PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getColumnDimension($letters[$inicio+5])->setAutoSize(true);
        
        $objWorksheet->getCell($letters[$inicio+6].$cont)->setValueExplicit(utf8_encode($row['nombre_completo']), PHPExcel_Cell_DataType::TYPE_STRING);
        $objWorksheet->getColumnDimension($letters[$inicio+6])->setAutoSize(true);

        $pagos = explode(',', $row['pagos']);
        if($cuotas[0][0] != null){
            foreach($pagos as $pago){
                $datos = explode('|', $pago);
                foreach($cuotas as $cuota){
                    if($cuota[0] == $datos[0]){
                        $contador = 1;
                        if($datos[5] != null){
                            $styleArray['fill'] = array(
                                                        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                                                        'color' => array('rgb' => (($datos[5] == 'warning') ? 'FEF78F' : 'FFC7CE'))
                                                    );
                        }
                        foreach($cuota[3] as $columnas){
                            if(!is_numeric($datos[$contador])){
                                $objWorksheet->getCell($columnas.$cont)->setValueExplicit(utf8_encode($datos[$contador]), PHPExcel_Cell_DataType::TYPE_STRING2);
                            } else{
                                $objWorksheet->getCell($columnas.$cont)->setValueExplicit(utf8_encode($datos[$contador]), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $objWorksheet->getStyle($columnas.$cont)->getNumberFormat()->setFormatCode('0.00');
                            }
                            $objWorksheet->getColumnDimension($letters[$inicio+1])->setAutoSize(true);
                            $objWorksheet->getStyle($columnas.$cont)->applyFromArray($styleArray);
                            $contador++;
                        }
                        if($datos[5] != null){
                            unset($styleArray['fill']);
                        }
                        break;
                    }
                }
            }  
        }
        $cont++;
    }
    //END MONTOS
    
    //BEGIN INFO FINAL
    $inicio  = 7;
    $contAux = 0;
    $styleArrayFin = array(
                    'font'  =>
                    array(
                        'size'  => 11,
                        'bold'  => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'CCCCCC')
                    )
                );
    for($i = $cont , $j = -2; $i < $cont + 14 ; $i++ , $j++){
        if($i >= $cont+2){
            $objWorksheet->getCell('A'.$i)->setValueExplicit($textos[$j], PHPExcel_Cell_DataType::TYPE_STRING);
        }
        $objWorksheet->mergeCells('A'.$i.':'.'G'.$i);
        $objWorksheet->getStyle('A'.$i)->applyFromArray($styleArrayFin);
    }
    unset($styleArrayFin['fill']);
    //END INFO
    
    //PIE DE PAGINA CON LOS MONTOS FINALES DE CADA CUOTA
    $styleArray['font']['bold'] = true;
    $montoTotalMes_acum   = 0;
    $total_cobrado_acum   = 0;
    $mora_acum            = 0;
    $monto_por_cobrarAcum = 0;
    if($cuotas[0][0] != null){
        foreach($cuotas as $cuota){
            $objWorksheet->getCell($letters[$inicio].$cont)->setValueExplicit(utf8_encode($cuota[1]), PHPExcel_Cell_DataType::TYPE_STRING);
            $objWorksheet->mergeCells($letters[$inicio].$cont.':'.$letters[$inicio+3].$cont);
            $objWorksheet->getColumnDimension($letters[$inicio])->setAutoSize(true);
            $objWorksheet->getStyle($letters[$inicio].($cont))->applyFromArray($styleArray);
            
            $objWorksheet->getCell($letters[$inicio].($cont+1))->setValueExplicit(utf8_encode($cuota[2]), PHPExcel_Cell_DataType::TYPE_STRING);
            $objWorksheet->mergeCells($letters[$inicio].($cont+1).':'.$letters[$inicio+3].($cont+1));
            $objWorksheet->getStyle($letters[$inicio].($cont+1))->applyFromArray($styleArray);
            $contAux = $cont+2;
            $contadorGeneral = 0;
            foreach($dataFin as $datos){
                if($cuota[0] == $datos->id_detalle_cronograma){
                    //MONTO TOTAL MES ACUMULADO
                    $montoTotalMes_acum = $montoTotalMes_acum + $datos->total_mes;
                    ///////////////////////////
                    $arrayCol1 = array($datos->total_mes,
                                       $montoTotalMes_acum,
                                       '','','','','','','','','',''
                                    );
                    //MORA ACUMULADA
                    $mora_acum = $mora_acum + $datos->mora_acumulada;
                    ////////////////
                    $arrayCol2 = array($datos->mora_acumulada,
                                       $mora_acum,
                                       '','','','','','','','','',''
                                      );
                    $arrayCol3 = array('','','','%','','%','','','%','','','%');
                    //ACUMULADO COBRADO
                    $total_cobrado_acum = $total_cobrado_acum + $datos->total_cuota_cobrado;
                    ///////////////////
                    //ACUMULADO POR COBRAR
                    $monto_por_cobrarAcum = $monto_por_cobrarAcum + $datos->monto_por_cobrar;
                    //////////////////////
                    $arrayCol4 = array($datos->total_cuota_cobrado,
                                       $total_cobrado_acum, 
                                       $datos->monto_cobranza,
                                       $datos->porce_cobranza,
                                       $datos->monto_morosidad,
                                       $datos->porce_morosidad,
                                       $datos->monto_por_cobrar,
                                       $monto_por_cobrarAcum,
                                       $datos->porce_monto_por_cobrar,
                                       '',
                                       '',
                                       '',
                                      );
                    for($i = $contAux , $j = 0 ; $i < $contAux + 12 ; $i++ , $j++){
                        $objWorksheet->getCell($cuota[3][0].$i)->setValueExplicit($arrayCol1[$j], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objWorksheet->getStyle($cuota[3][0].$i)->applyFromArray($styleArrayFin);
                        $objWorksheet->getStyle($cuota[3][0].$i)->getNumberFormat()->setFormatCode('0.00');
                        
                        $objWorksheet->getCell($cuota[3][1].$i)->setValueExplicit($arrayCol2[$j], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objWorksheet->getStyle($cuota[3][1].$i)->applyFromArray($styleArrayFin);
                        $objWorksheet->getStyle($cuota[3][1].$i)->getNumberFormat()->setFormatCode('0.00');
                        
                        $objWorksheet->getCell($cuota[3][2].$i)->setValueExplicit($arrayCol3[$j], PHPExcel_Cell_DataType::TYPE_STRING);
                        $objWorksheet->getStyle($cuota[3][2].$i)->applyFromArray($styleArrayFin);
                        
                        $objWorksheet->getCell($cuota[3][3].$i)->setValueExplicit($arrayCol4[$j], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objWorksheet->getStyle($cuota[3][3].$i)->applyFromArray($styleArrayFin);
                        $objWorksheet->getStyle($cuota[3][3].$i)->getNumberFormat()->setFormatCode('0.00'); 
                    }
                }
                $contadorGeneral++;
            }
            $inicio = $inicio + 4;
        }
    }
    //FIN DEL PIE DE PAGINA
    
    //BORDES A TODO EL EXCEL
    $styleArray = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => '000000'),
            )
        )
    );
    $contAux = ($cuotas[0][0] != null) ? $contAux : $cont+2;
    $objWorksheet->getStyle('A1:'.$letters[$lastColumn-1].($contAux+11))->applyFromArray($styleArray);
    //FIN DE BORDES
    
    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->save('php://output');
?>
