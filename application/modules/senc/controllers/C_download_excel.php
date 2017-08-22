<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_download_excel extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('mf_graficos/m_g_comparar_preg');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('m_utils');
        $this->load->library('Classes/PHPExcel.php');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, null, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
    
    public function index(){
        $logeoUsario = $this->session->userdata('nid_persona');
        if($logeoUsario != null){
            $json        = _post("jsonChart");
            $filtros     = _post("filtroChart");
            $filtrosEspc = _post("filtroChartEspecifico");
            $email       = _post("enviarEmail");
            $correoPersona = _post("correoDestinoCont");
            
            $data['filtros']     = $this->createArrayFiltros($filtros);
            $data['filtrosEscp'] = $this->createArrayFiltrosEspc($filtrosEspc);
            $data['objPHPExcel'] = new PHPExcel();
            $data['jsonArray']   = $this->createArrayExcelComparativas($json);
            $data['alpha']       = $alphas = range('A', 'Z');
            $data['typeChart']   = _post("typeChart");
            if($email == 1){
                $this->generarExcelServidor($data);
                $this->enviarDocEmail($correoPersona,$_SERVER['DOCUMENT_ROOT'].'/smiledu/modulos/senc/excel/reportes.xlsx');
                echo "<script type='text/javascript'>window.close();</script>";
            }else{
                $this->load->view('v_download_excel', $data);
            }
        }else{
            $this->session->sess_destroy();
            redirect('','refresh');
        }
    }
    
    public function createArrayExcelComparativas($data){
        $data = json_decode($data);
        $arrayTodo = array();
        $j = 0;
        foreach ($data as $var){
            $spl = explode(",", $var);
            for($i = 0; $i < count($spl) ; $i++){
                if($j == 0){
                    array_push($arrayTodo, array(str_replace('"','',$spl[$i])));
                }else{
                    array_push($arrayTodo[$i], str_replace('"','',$spl[$i]));
                }
            }
            $j++;
        }
        
        return $arrayTodo;
    }
    
    public function createArrayFiltros($filtros){
        $filtros = json_decode($filtros)[0];
        foreach ($filtros as $key => $value){
            $filtros->$key = $this->getDescById($value, $key);
        }
    
        return $filtros;
    }
    
    public function createArrayFiltrosEspc($filtrosEsp){
        $filtros = json_decode($filtrosEsp);
        
        foreach ($filtros as $fil){
            foreach ($fil as $key => $value){
                if($value != null){
                    $fil->$key = $this->getDescById($value, $key);
                }
            }
        }
        return $filtros;
    }
    
    public function getDescById($val, $key){
        $desc = null;
        $lfcr = chr(10).chr(13);
        $c = 1;
        if($key == 'Tipo_Encuesta'){
            if(is_array($val)){
                foreach ($val as $var){
                    $id    = _decodeCI($var);
                    $desc .= $c.') '.utf8_encode($this->m_utils->getById("senc.tipo_encuesta", "desc_tipo_encuesta", "id_tipo_encuesta", $id, "senc").$lfcr);
                    $c++;
                }
            }else{
                $id   = _decodeCI($val);
                $desc = utf8_encode($this->m_utils->getById("senc.tipo_encuesta", "desc_tipo_encuesta", "id_tipo_encuesta", $id, "senc"));
            }
        }else if($key == 'Encuesta'){
            $id   = _decodeCI($val);
            $desc = utf8_encode($this->m_utils->getById("senc.encuesta", "desc_enc", "id_encuesta", $id, "senc"));
        }else if($key == 'Preguntas'){
            foreach ($val as $var){
                $id    = _decodeCI($var);
                $desc .= $c.') '.utf8_encode($this->m_utils->getById("senc.preguntas", "desc_pregunta", "id_pregunta", $id, "senc").$lfcr);
                $c++;
            }
        }else if($key == 'Propuesta_Mejora'){
            foreach ($val as $var){
                $id    = _decodeCI($var);
                $desc .= $c.') '.utf8_encode($this->m_utils->getById("senc.propuesta_mejora", "desc_propuesta", "id_propuesta", $id, "senc").$lfcr);
                $c++;
            }
        }else if($key == 'sede'){
            foreach ($val as $var){
                $id    = _simple_decrypt($var);
                $desc .= $c.') '.utf8_encode($this->m_utils->getById("sede", "desc_sede", "nid_sede", $id, "schoowl").$lfcr);
                $c++;
            }
        }else if($key == 'nivel'){
            foreach ($val as $var){
                $id    = _simple_decrypt($var);
                $desc .= $c.') '.utf8_encode($this->m_utils->getById("nivel", "desc_nivel", "nid_nivel", $id, "schoowl").$lfcr);
                $c++;
            }
        }else if($key == 'grado'){
            foreach ($val as $var){
                $id    = _simple_decrypt($var);
                $desc .= $c.') '.utf8_encode($this->m_utils->getById("grado", "desc_grado", "nid_grado", $id, "schoowl").$lfcr);
                $c++;
            }
        }else if($key == 'aula'){
            foreach ($val as $var){
                $id    = _simple_decrypt($var);
                $desc .= $c.') '.utf8_encode($this->m_utils->getById("aula", "desc_aula", "nid_aula", $id, "schoowl").$lfcr);
                $c++;
            }
        }else if($key == 'area'){
            foreach ($val as $var){
                $id    = _simple_decrypt($var);
                $desc .= $c.') '.utf8_encode($this->m_utils->getById("area", "desc_area", "id_area", $id, "schoowl").$lfcr);
                $c++;
            }
        }
        
        else if($key == 'index_serie' || $key == 'index_data'){
            $desc .= $val;
        }
        
        return $desc;
    }
    
    public function getFiltrosPDF(){
        $filtros = _post("filtroChart");
        $filtrosEspecificos = _post("filtroChartEspecificos");
        $nGrafico = _post("nGrafico");
        $filtros = $this->createArrayFiltros($filtros);
        $filtrosEspecificos = $this->createArrayFiltrosEspc($filtrosEspecificos);
        $fl = $this->createTableHTML($filtros, $nGrafico);
        $data['tablaFiltros'] = $fl['tabla'];
        $data['especifico']   = $fl['espec'];
        $data['tablaFiltrosEspecificos'] = $this->createTableHTML1($filtrosEspecificos);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function createTableHTML($data, $nGrafico){
        $cabecera = null;
        $cuerpo   = null;
        $cuerpo1   = null;
        $c = 1;
        foreach ($data as $key => $value){
            if($c == 3 && $nGrafico != 2){
                $cuerpo1   .= utf8_decode($value);
            }else if($c == 2 && $nGrafico == 2){
                $cuerpo1   .= utf8_decode($value);
            }else{
                $cabecera .= '<td>'.utf8_decode($key).'</td>';
                $cuerpo   .= '<td>'.utf8_decode($value).'</td>';
            }
            $c++;
        }
        
        $res = '<table id="table">
                    <thead>
                        <tr>'.
                            $cabecera.
                        '</tr>
                     </thead>
                     <tbody>
                         <tr>'.
                            $cuerpo.
                        '</tr>
                     </tbody>
                </table>';
        
        $data1['tabla'] = $res;
        $data1['espec'] = $cuerpo1;

        return $data1;
    }
    
    public function createTableHTML1($data){
        $cabecera = null;
        $cuerpo   = null;

        foreach ($data as $fil){
            foreach ($fil as $key => $value){
                if($value != null){
                    $cabecera .= '<td>'.$key.'</td>';
                    $cuerpo   .= '<td>'.$value.'</td>';
                }
            }
        }
    
        $res = '<table id="table1">
                    <thead>
                        <tr>'.
                            $cabecera.
                            '</tr>
                     </thead>
                     <tbody>
                         <tr>'.
                             $cuerpo.
                             '</tr>
                     </tbody>
                </table>';
    
        return $res;
    }
    
    public function generarExcelServidor($data){
        $objWorksheet = $data['objPHPExcel']->getActiveSheet();
        $objWorksheet->fromArray($data['jsonArray']);
        
        $labels = array();
        for($i = 0;$i < count($data['jsonArray'][0]); $i++){
            if($data['alpha'][$i] != 'A'){
                array_push($labels, new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$'.$data['alpha'][$i].'$1', null, 1));
            }
        }
        
        $xAxisTickValues = array();
        for($i = 0;$i < count($data['jsonArray'][0]); $i++){
            if($i != 0){
                array_push($xAxisTickValues, new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$'.count($data['jsonArray']), NULL, 4));
            }
        }
        
        $dataSeriesValues1 = array();
        for($i = 0;$i < count($data['jsonArray'][0]); $i++){
            if($i != 0){
                array_push($dataSeriesValues1, new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$'.$data['alpha'][$i].'$2:$'.$data['alpha'][$i].'$'.count($data['jsonArray']), NULL, 4));
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
        if($data['typeChart'] == 'column'){
            $series1 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                range(0, count($dataSeriesValues1)-1),
                $labels,
                $xAxisTickValues,
                $dataSeriesValues1
            );
        }else if($data['typeChart'] == 'line'){
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
        
        $objWorkSheet_1 = $data['objPHPExcel']->createSheet(1);
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
        foreach ($data['filtros'] as $key => $value){
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
        foreach ($data['filtrosEscp'] as $fil){
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
        $objDrawing->setCoordinates('B2');
        $objDrawing->setHeight(90);
        $objDrawing->setWorksheet($objWorkSheet_1);
        
//         $objDrawing = new PHPExcel_Worksheet_Drawing();
//         $objDrawing->setName('Logo La Merced');
//         $objDrawing->setDescription('Logo La Merced');
//         $logo = './public/img/logo/logo_la_merced.png';
//         $objDrawing->setPath($logo);
//         $objDrawing->setOffsetX(8);
//         $objDrawing->setCoordinates('I2');
//         $objDrawing->setHeight(75);
//         $objDrawing->setWorksheet($objWorkSheet_1);
        /*FIN LOGOS*/
        
        $writer = PHPExcel_IOFactory::createWriter($data['objPHPExcel'], 'Excel2007');
        $writer->setIncludeCharts(TRUE);
        
        /*$objWorksheet->setTitle("DATA");
         $objWorkSheet_1->setTitle("REPORTE");*/
        $writer->save("uploads/modulos/senc/excel/reportes.xlsx");
    }
    
    function enviarDocEmail($correo_destino, $doc){
        $date    = date('d/m/Y H:i:s');
        $persona = $this->session->userdata('nombre_usuario');
        $correo  = $this->session->userdata('correo_persona');
        $data = __enviarEmail($correo_destino,"Reporte Gráficos","<strong>Fecha:</strong> ".$date."<br><strong>Enviado por:</strong> ".$persona.'<br><strong>Correo: </strong>'.$correo, $doc);
        return $data;
    }
    
    function enviarPDFEmail(){
        $pdf = _post("docpdf"); 
        $correoPersona = _post("correo");
        $binary = base64_decode(str_replace("data:application/pdf;base64,", "", $pdf));
        file_put_contents('uploads/modulos/senc/pdf/reportes.pdf', $binary);
        
        $data = $this->enviarDocEmail($correoPersona,$_SERVER['DOCUMENT_ROOT'].'/smiledu/uploads/modulos/senc/pdf/reportes.pdf');
        echo json_encode(array_map('utf8_encode', $data));
    }
}