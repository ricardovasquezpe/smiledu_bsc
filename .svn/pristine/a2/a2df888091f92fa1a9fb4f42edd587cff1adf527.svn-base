<?php 
    $date = date('Y-m-d H:i:s');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Encuesta_'.$date.'.xlsm"');
    header('Cache-Control: max-age=0');
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    /*if($inputFileType == _HTML_TYPE) {
        throw new Exception("El archivo Excel no debe ser de tipo Página Web (HTML), guárdelo como libro de Excel");
    }*/
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
    
    
    $objWorksheet = $objPHPExcel->getActiveSheet();
    //$objWorksheet->fromArray($jsonArray);
    
    /*AÑADIR TITULO*/
    $objRichText0 = new PHPExcel_RichText();
    $objBold0 = $objRichText0->createTextRun('ENCUESTA:');
    $objBold0->getFont()->setBold(true);
    $objWorksheet->getCell('A1')->setValue($objRichText0);
    
    //CAMPO PARA PONER EL CODIGO DE AULA
    //dfloresgonz 29.09.2016
    if($tipoEncuesta == TIPO_ENCUESTA_ALUMNOS || $tipoEncuesta == TIPO_ENCUESTA_PADREFAM) {
        $objRichText0 = new PHPExcel_RichText();
        $objBold0 = $objRichText0->createTextRun('COD. AULA:');
        $objBold0->getFont()->setBold(true);
        $objWorksheet->getCell('A2')->setValue($objRichText0);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function letters() {
        $letters = array();
        $letter = 'A';
        while ($letter !== 'AAA') {
            $letters[] = $letter++;
        }
        return $letters;
    }
    $e = 1;
    $x = 1;
    $y = 1;
    $z = 1;
    $f = 1;
    $g = 1;
    $alphas  = letters();
    $alphas1 = letters();
    $alphas2 = letters();
    $alphas3 = letters();
    $alphas5 = letters();
    //Para ordenar las listas desplegables,radiobuttons o checklists
    $alphas4 = letters();
    $arrayOpciones = array();
    $columnaInicioDatos = '3';
    //define el excel
    $desc_encuesta = $this->m_encuesta->getDescEncbyIdEnc($idEncuesta);
    $objRichText1 = new PHPExcel_RichText();
    $objBold1 = $objRichText1->createTextRun($desc_encuesta['desc_enc']);
    $objBold1->getFont()->setBold(true);
    $objWorksheet->getCell('B1')->setValue($objRichText1);
    $cantPregxEnc = $this->m_encuesta->getPreguntasbyIdEncuesta($idEncuesta);
    $arrayCasillas = array();
    $cantidad = count($cantPregxEnc);
    $count=0;
    foreach($cantPregxEnc as $rowPreg) {
        $count++;
        $objWorksheet->getCell($alphas[$e].'3')->setValueExplicit(utf8_encode($rowPreg->_id_pregunta), PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objRichText3 = new PHPExcel_RichText();
        $objBold3 = $objRichText3->createTextRun(utf8_encode($rowPreg->desc_pregunta));        
        $objBold3->getFont()->setBold(true);
        $objWorksheet->getCell($alphas[$e].FILA_INICIAL)->setValue($objRichText3);
        if($count == $cantidad){
            $objRichText8 = new PHPExcel_RichText();
            $objBold8 = $objRichText8->createTextRun('INDICAR SU PROPUESTA DE MEJORA');
            $objBold8->getFont()->setBold(true);
            $objWorksheet->getCell($alphas[$e+1].FILA_INICIAL)->setValue($objRichText8);
            ////////////////////////// COMENTARIO PROPUESTA//////////////////////////////////////////////////
            $objRichText9 = new PHPExcel_RichText();
            $objBold9 = $objRichText9->createTextRun('COMENTARIO DE PROPUESTA MEJORA');
            $objBold9->getFont()->setBold(true);
            $objWorksheet->getCell($alphas[$e+2].FILA_INICIAL)->setValue($objRichText9);
            //------id del comentario propuesta-----------------------------------------------------------//
            $objWorksheet->getCell($alphas[$e+2].'3')->setValueExplicit(CODIGO_COMENTARIO_CELDA, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        }
        $e++;
        $x++;
        $y++;
        $z++;
        $f++;
        $g++;
        $alternativas      = ($rowPreg->_id_tipo_pregunta == LISTA_DESPLEGABLE || $rowPreg->_id_tipo_pregunta == CASILLAS_VERIFICACION || $rowPreg->_id_tipo_pregunta == OPCION_MULTIPLE) ? $this->m_encuesta->getAlternativas($rowPreg->_id_pregunta,$idEncuesta) : array();
        $countAlternativas = ($rowPreg->_id_tipo_pregunta == LISTA_DESPLEGABLE || $rowPreg->_id_tipo_pregunta == CASILLAS_VERIFICACION || $rowPreg->_id_tipo_pregunta == OPCION_MULTIPLE) ? count($alternativas) : 0;
        if($countAlternativas != 0){
            $arrayOpciones[$alphas4[$columnaInicioDatos+1]] = $alternativas;
            $columnaInicioDatos++;
        }
        for($cont = FILA_INICIAL+1,$r=1 ; $cont < ($cantEncuestados+FILA_INICIAL+1);$cont++,$r++){
            $objRichText4 = new PHPExcel_RichText();
            $objBold4 = $objRichText4->createTextRun('Encuesta_'.$r.':');
            $objBold4->getFont()->setBold(true);
            $objWorksheet->getCell(COLUMNA_INICIAL.$cont)->setValue($objRichText4);
            if($rowPreg->_id_tipo_pregunta == CUATRO_CARITAS){
                $gg =$alphas1[$x-1];
                $objValidation1 = $objPHPExcel->getActiveSheet()->getCell($gg.$cont)->getDataValidation();
                $objValidation1->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
                $objValidation1->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
                $objValidation1->setAllowBlank(false);
                $objValidation1->setShowInputMessage(true);
                $objValidation1->setShowErrorMessage(true);
                $objValidation1->setShowDropDown(true);
                $objValidation1->setErrorTitle('Valor incorrecto');
                $objValidation1->setError(utf8_encode('El valor ingresado no está en la lista.'));
                $objValidation1->setPromptTitle('Escoge un valor');
                $objValidation1->setPrompt('Por favor escoge un valor de la lista.');       
                $objValidation1->setFormula1('Datos!$'.COLUMNA_4CARITAS.'$1:$'.COLUMNA_4CARITAS.'$4');
                $gg = 0;
            }else if($rowPreg->_id_tipo_pregunta == CINCO_CARITAS){
                $hh =$alphas2[$y-1];
                $objValidation2 = $objPHPExcel->getActiveSheet()->getCell($hh.$cont)->getDataValidation();
                $objValidation2->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowErrorMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setErrorTitle('Valor incorrecto');
                $objValidation2->setError(utf8_encode('El valor ingresado no está en la lista.'));
                $objValidation2->setPromptTitle('Escoge un valor');
                $objValidation2->setPrompt('Por favor escoge un valor de la lista.');
                $objValidation2->setFormula1('Datos!$'.COLUMNA_5CARITAS.'$1:$'.COLUMNA_5CARITAS.'$5');
                $hh = 0;
            }else if($rowPreg->_id_tipo_pregunta == TRES_CARITAS){
                $pp =$alphas5[$g-1];
                $objValidation2 = $objPHPExcel->getActiveSheet()->getCell($pp.$cont)->getDataValidation();
                $objValidation2->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowErrorMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setErrorTitle('Valor incorrecto');
                $objValidation2->setError(utf8_encode('El valor ingresado no está en la lista.'));
                $objValidation2->setPromptTitle('Escoge un valor');
                $objValidation2->setPrompt('Por favor escoge un valor de la lista.');
                $objValidation2->setFormula1('Datos!$'.COLUMNA_3CARITAS.'$1:$'.COLUMNA_3CARITAS.'$3');
                $pp = 0;
            }else if($rowPreg->_id_tipo_pregunta == DOS_OPCIONES){
                $ff =$alphas3[$z-1];
                $objValidation2 = $objPHPExcel->getActiveSheet()->getCell($ff.$cont)->getDataValidation();
                $objValidation2->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowErrorMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setErrorTitle('Valor incorrecto');
                $objValidation2->setError(utf8_encode('El valor ingresado no está en la lista.'));
                $objValidation2->setPromptTitle('Escoge un valor');
                $objValidation2->setPrompt('Por favor escoge un valor de la lista.');
                $objValidation2->setFormula1('Datos!$'.COLUMNA_2OPCIONES.'$1:$'.COLUMNA_2OPCIONES.'$2');
                $ff = 0;
            } else if($rowPreg->_id_tipo_pregunta == LISTA_DESPLEGABLE || $rowPreg->_id_tipo_pregunta == OPCION_MULTIPLE || $rowPreg->_id_tipo_pregunta == CASILLAS_VERIFICACION){
                $asd =$alphas[$e-1]; 
                if($rowPreg->_id_tipo_pregunta == CASILLAS_VERIFICACION){
                    array_push($arrayCasillas, ($e));
                }
                $objValidation = $objPHPExcel->getActiveSheet()->getCell($asd.$cont)->getDataValidation();
                $objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
                $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Valor incorrecto');
                $objValidation->setError(utf8_encode('El valor ingresado no está en la lista.'));
                $objValidation->setPromptTitle('Escoge un valor');
                $objValidation->setPrompt('Por favor escoge un valor de la lista.');
                $objValidation->setFormula1('Datos!$'.$alphas4[$columnaInicioDatos].'$1:$'.$alphas4[$columnaInicioDatos].'$'.$countAlternativas);
                $asd = 0;
            }
        }
    }
    $arrayCasillas = array_values(array_unique($arrayCasillas));
    $objPHPExcel->createSheet(3);
    $objPHPExcel->setActiveSheetIndex(2);
    $objPHPExcel->getActiveSheet()->setTitle('Datos');
    $arrayKeysCol = array_keys($arrayOpciones);
    foreach($arrayKeysCol as $key) {
        $i = 0;
        foreach($arrayOpciones[$key] as $row) {
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue("".$key."{$i}", "$row->desc_alternativa");
        }
    }
    $array4caritas = $this->m_encuesta->getDesc4CaritasbyId();
    $j = 0;
    foreach ($array4caritas as $row4caritas) {
        $j++;
        $objPHPExcel->getActiveSheet()->setCellValue("".COLUMNA_4CARITAS."{$j}", "$row4caritas->desc_alternativa");
    }
    $array5caritas = $this->m_encuesta->getDesc5CaritasbyId();
    $k = 0;
    foreach ($array5caritas as $row5caritas) {
        $k++;
        $objPHPExcel->getActiveSheet()->setCellValue("".COLUMNA_5CARITAS."{$k}", "$row5caritas->desc_alternativa");
    }
    $array3caritas = $this->m_encuesta->getDesc3CaritasbyId();
    $o = 0;
    foreach ($array3caritas as $row3caritas) {
        $o++;
        $objPHPExcel->getActiveSheet()->setCellValue("".COLUMNA_3CARITAS."{$o}", "$row3caritas->desc_alternativa");
    }
    $arraySiNo = $this->m_encuesta->getDesc2Opciones();
    $l = 0;
    foreach ($arraySiNo as $rowSiNo) {
        $l++;
        //$objPHPExcel->getActiveSheet()->setCellValue("".COLUMNA_2OPCIONES."{$l}", "$rowSiNo->desc_alternativa");
        $objPHPExcel->getActiveSheet()->getCell("".COLUMNA_2OPCIONES."{$l}")->setValueExplicit(utf8_encode("$rowSiNo->desc_alternativa"), PHPExcel_Cell_DataType::TYPE_STRING);
    }
    $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setInsertColumns(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
    
    $objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    //Setear las columnas checklist//////////////////////////
    $objPHPExcel->setActiveSheetIndex(1);
    $contador = 1;
    foreach($arrayCasillas as $colum) {
        $objRichTextCasilla = new PHPExcel_RichText();
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $objBold0 = $objRichTextCasilla->createTextRun($colum);
        $objBold0->getFont()->setBold(true);
        $objWorksheet->getCell('A'.$contador)->setValue($objRichTextCasilla);
    }
    ///////Proteger Hoja casillas
    $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setSort(false);
    $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(false);
    $objPHPExcel->getActiveSheet()->getProtection()->setInsertColumns(false);
    $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(false);
    ///////////////////////////////////////////////////////
    /* CREAR HOJA DE IDs protegidos */
    $objPHPExcel->createSheet(4);
    $objPHPExcel->setActiveSheetIndex(3);
    $objPHPExcel->getActiveSheet()->setTitle('extras');
    
    $objPHPExcel->getActiveSheet()->setCellValue("A1", _encodeCI($idEncuesta));
    
    //Proteger la hoja Extras
    $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setSort(false);
    $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(false);
    $objPHPExcel->getActiveSheet()->getProtection()->setInsertColumns(false);
    $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(false);
    ///////////////////////////////////////////////////////
    $objPHPExcel->setActiveSheetIndex(0);
    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->save('php://output');
?>