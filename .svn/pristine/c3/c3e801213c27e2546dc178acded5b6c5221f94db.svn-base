<?php 
    $date = date('Y-m-d H:i:s');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Grafico_'.$date.'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $objWorksheet->fromArray($jsonArray);
 
    $labels = array();
    for($i = 0;$i < count($jsonArray[0]); $i++){
        if($alpha[$i] != 'A'){
            array_push($labels, new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$'.$alpha[$i].'$1', null, 1));
        }
    }
    
    $xAxisTickValues = array();
    for($i = 0;$i < count($jsonArray[0]); $i++){
        if($i != 0){
            array_push($xAxisTickValues, new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$'.count($jsonArray), NULL, 4));
        }
    }
    
    $dataSeriesValues1 = array();
    for($i = 0;$i < count($jsonArray[0]); $i++){
        if($i != 0){
            array_push($dataSeriesValues1, new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$'.$alpha[$i].'$2:$'.$alpha[$i].'$'.count($jsonArray), NULL, 4));
        }
    }
    /*
        LINECHART
        ---------
        PHPExcel_Chart_DataSeries::TYPE_LINECHART,       
        PHPExcel_Chart_DataSeries::GROUPING_STANDARD,  
        
        PIECHART
        ---------
        PHPExcel_Chart_DataSeries::TYPE_PIECHART,       
        NULL, 
        
        BARCHART
        ---------
        PHPExcel_Chart_DataSeries::TYPE_BARCHART,       
        PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
     */
    $series1 = array();
    if($typeChart == 'column'){
        $series1 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_BARCHART,
            PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues1)-1),
            $labels,
            $xAxisTickValues,
            $dataSeriesValues1
        );
    }else if($typeChart == 'line'){
        $series1 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_LINECHART,       
            PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
            range(0, count($dataSeriesValues1)-1),
            $labels,
            $xAxisTickValues,
            $dataSeriesValues1
        );
    }else{
        $series1 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_PIECHART,       
            NULL,
            range(0, count($dataSeriesValues1)-1),
            $labels,
            $xAxisTickValues,
            $dataSeriesValues1
        );
    }
    
    $layout1 = new PHPExcel_Chart_Layout();
    $layout1->setShowVal(TRUE);
    $layout1->setShowPercent(TRUE);
    
    $series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

    $plotarea = new PHPExcel_Chart_PlotArea($layout1, array($series1));
    $legend   = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
    $title    = new PHPExcel_Chart_Title('');

    $chart = new PHPExcel_Chart(
        'reporte',    
        $title,        
        $legend,      
        $plotarea,     
        true,           
        0,             
        NULL,         
        NULL           
    );
    
    $chart->setTopLeftPosition('B10');
    $chart->setBottomRightPosition('K26');
    
    $objWorkSheet_1 = $objPHPExcel->createSheet(1);
    $objWorkSheet_1->addChart($chart);
    
    /*AÑADIR TITULO*/
    $objRichText = new PHPExcel_RichText();
    $objBold = $objRichText->createTextRun('REPORTE');
    $objBold->getFont()->setBold(true);
    $objWorkSheet_1->getCell('C7')->setValue($objRichText);
    $objWorkSheet_1->mergeCells('C7:H7');
    $objWorkSheet_1->getStyle('C7:H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    /*FIN AÑADIR TITULO*/
    
    /*AÑADIR FILTROS*/
    $i = 30;
    foreach ($filtros as $key => $value){
        $objRichText = new PHPExcel_RichText();
        $objKey = $objRichText->createTextRun($key);
        $objKey->getFont()->setBold(true);
        $objWorkSheet_1->getCell('B'.$i)->setValue($objRichText);
        
        $objRichText = new PHPExcel_RichText();
        $objValue = $objRichText->createTextRun($value);
        $objWorkSheet_1->getCell('C'.$i)->setValue($objRichText);
        $objWorkSheet_1->getStyle('C'.$i)->getAlignment()->setWrapText(true);
        $objWorkSheet_1->mergeCells('C'.$i.':E'.$i);
        $i++;
    }
    $objWorkSheet_1->getColumnDimension('B')->setWidth(20);
    /*FIN AÑADIR FILTROS*/
    
    /*AÑADIR FILTROS ESPECIFICOS*/
    $i = 35;
    foreach ($filtrosEscp as $fil){
        $objRichText = new PHPExcel_RichText();
        $objKey = $objRichText->createTextRun("BARRA ".($fil->index_data+1)." SERIE ".($fil->index_serie+1));
        $objKey->getFont()->setBold(true);
        $objWorkSheet_1->getCell('B'.$i)->setValue($objRichText);
        $objWorkSheet_1->getStyle('B'.$i.':E'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EACDB7');
        $objWorkSheet_1->getStyle('B'.$i)->getAlignment()->setWrapText(true);
        $objWorkSheet_1->mergeCells('B'.$i.':E'.$i);
        $objWorkSheet_1->getStyle('B'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $i++;
        foreach ($fil as $key => $value){
            if($value != null && $key != 'index_data' && $key != 'index_serie'){
                $objRichText = new PHPExcel_RichText();
                $objKey = $objRichText->createTextRun($key);
                $objKey->getFont()->setBold(true);
                $objWorkSheet_1->getCell('B'.$i)->setValue($objRichText);
                
                $objRichText = new PHPExcel_RichText();
                $objValue = $objRichText->createTextRun($value);
                $objWorkSheet_1->getCell('C'.$i)->setValue($objRichText);
                $objWorkSheet_1->getStyle('C'.$i)->getAlignment()->setWrapText(true);
                $objWorkSheet_1->mergeCells('C'.$i.':E'.$i);
                $i++;
            }
        }
        $i++;
    }
    /*FIN AÑADIR FILTROS ESPECIFICOS*/
    /*LOGOS*/
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('Logo Avantgard');
    $objDrawing->setDescription('Logo Avantgard');
    $logo = './public/modulos/senc/img/logo/avantgard_logo.png';
    $objDrawing->setPath($logo);
    $objDrawing->setOffsetX(8);               
    $objDrawing->setCoordinates('A1');            
    $objDrawing->setHeight(90);                   
    $objDrawing->setWorksheet($objWorkSheet_1);
    
//     $objDrawing = new PHPExcel_Worksheet_Drawing();
//     $objDrawing->setName('Logo La Merced');
//     $objDrawing->setDescription('Logo La Merced');
//     $logo = './public/img/logo/logo_la_merced.png';
//     $objDrawing->setPath($logo);
//     $objDrawing->setOffsetX(8);
//     $objDrawing->setCoordinates('I2');
//     $objDrawing->setHeight(75);
//     $objDrawing->setWorksheet($objWorkSheet_1);
    /*FIN LOGOS*/

    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->setIncludeCharts(TRUE);
    
    /*$objWorksheet->setTitle("DATA");
     $objWorkSheet_1->setTitle("REPORTE");*/
    
    $writer->save('php://output');
?>