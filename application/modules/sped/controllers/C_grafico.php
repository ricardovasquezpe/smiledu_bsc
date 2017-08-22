<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_grafico extends MX_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_grafico');
        $this->load->model('m_utils');
        $this->load->helper('cookie');
        
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_GRAFICOS, SPED_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
    }
    
    public function index(){
    }
    
    function cambioRol() {
        $idRolEnc = _post('id_rol');
        $idRol = _simple_decrypt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array("id_rol"     => $idRol,
                          "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $idRol     = $this->session->userdata('nombre_rol');
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function dataGraficoByIndicador(){
        $idIndicador = _decodeCI(_post('idIndicador'));
        $idDocente   = _decodeCI(_post('idDocente'));
        $fechaInicio = _post('fecInicio');
        $fechaFin    = _post('fecFin');
        $selectIndi  = _post('selectedIndi');
        $selectDoc   = _post('selectedDoc');
        $cont        = _post('cont');
        $cont2       = _post('cont2');
        $cont3       = _post('cont3');
        $cont5       = _post('cont5');
        $result      = array('retval' => array());
        $result2     = array('retval' => array());
        $arrayPorcentaje = array();
        $selectIndDecryp = array();
        $selectDocDecrypt = array();
        if(is_array($selectIndi) && count($selectIndi) > 0){
            foreach($selectIndi AS $indi){
                array_push($selectIndDecryp, $this->encrypt->decode($indi));
            }
        }
        if(is_array($selectDoc) && count($selectDoc) > 0){
            foreach ($selectDoc as $id){
                array_push($selectDocDecrypt, $this->encrypt->decode($id));
            }   
        }
        $tipoGrafico = $this->evaluaTipoGrafico($idIndicador, $idDocente);
        if($fechaInicio != null){
            //Convert dd/mm/yyyy to yyyy-mm-dd 
            $fechaInicio = implode("-", array_reverse(explode("/", $fechaInicio)));
        }
        if($fechaFin != null){
            $fechaFin = implode("-", array_reverse(explode("/", $fechaFin)));
        }
        if($cont == 'true'){
            $result = $this->m_grafico->getDataGraficoByIndicador($selectIndDecryp,$fechaInicio,$fechaFin,$tipoGrafico);
        }
        if($cont3 == 'true'){
            $result2 = $this->m_grafico->getDataDocenteIndicador($selectDocDecrypt,$selectIndDecryp,$fechaInicio,$fechaFin,$tipoGrafico);
        }
        //Grafico Indicador
        $data = $this->dataIndicador($result,$selectIndDecryp);
        //Grafico Docente por Indicador
        $data  += $this->getDataDocenteIndi($selectIndDecryp, $selectDocDecrypt,$result2);
        //Grafico area Promedio Anios
        $dataGraficos3  = $this->getDataPromedioIndicador(($cont5 == 'true') ? $this->m_grafico->getDataAreaIndicadores($selectIndDecryp) : array('retval' => array()));
        $data['years'] = json_encode($dataGraficos3['year']);
        $data['promedios'] = json_encode($dataGraficos3['promedios']);
        //Grafico pie porcentajes
        if($cont2 == 'true'){
            $arrayPorcentaje = $this->getDataPorcentajeIndicador($selectIndDecryp);
        }
        $data['porcentaje'] = json_encode($arrayPorcentaje);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function dataGraficosDocente(){
        $idIndicador = $this->encrypt->decode(_post('idIndicador'));
        $idDocente   = $this->encrypt->decode(_post('idDocente'));
        $fechaInicio = _post('fecInicio');
        $fechaFin    = _post('fecFin');
        $selectDoc   = _post('selectedDoc');
        $selectIndi  = _post('selectedIndi');
        $cont4       = _post('cont4');
        $cont3       = _post('cont3');
        $cont6       = _post('cont6');
        $result      = array('retval' => array());
        $result2     = array('retval' => array());
        $result3     = array('retval' => array());
        $selectIndDecryp = array();
        $selectDocDecrypt = array();
        $tipoGrafico = $this->evaluaTipoGrafico($idIndicador, $idDocente);
        if(is_array($selectDoc) && count($selectDoc) > 0){
            foreach ($selectDoc as $idDoc){
                array_push($selectDocDecrypt, $this->encrypt->decode($idDoc));
            }   
        }
        if(is_array($selectIndi) && count($selectIndi) > 0){
            foreach($selectIndi AS $idIndi){
                array_push($selectIndDecryp, $this->encrypt->decode($idIndi));
            }
        }
        if($fechaInicio != null){
            $fechaInicio = implode("-", array_reverse(explode("/", $fechaInicio)));
        }
        if($fechaFin != null){
            $fechaFin = implode("-", array_reverse(explode("/", $fechaFin)));
        }
        //Grafico docente
        if($idDocente != ''){
            if($cont4 == 'true'){
                $result = $this->m_grafico->getDataGraficoDocente($selectDocDecrypt,$selectIndDecryp,$fechaInicio,$fechaFin,$tipoGrafico);
            }
        }
        $data = $this->dataDocente($result,$selectDocDecrypt);
        /*$data['desc']  = json_encode($dataGraficos['desc']);
        $data['nota']  = json_encode($dataGraficos['arrayGeneralNotas']);
        $data['fecha'] = json_encode($dataGraficos['arrayFechas']);*/
        //Grafico Docente por Indicador
        if($cont3 == 'true'){
            $result2 = $this->m_grafico->getDataDocenteIndicador($selectDocDecrypt,$selectIndDecryp,$fechaInicio,$fechaFin,$tipoGrafico);
        }
        //DOCENTE INDICADOR
        $data  += $this->getDataDocenteIndi($selectIndDecryp, $selectDocDecrypt,$result2);
        //Grafico Docentes Promedio Anual
        if($cont6 == 'true'){
            $result3 = $this->m_grafico->getPromedioAnualDocentes($selectDocDecrypt,$fechaInicio,$fechaFin);
        }
        $dataGraficos3    = $this->getDataPromedioDocentes($result3);
        $data['docente']  = json_encode($dataGraficos3['docentes']);
        $data['promedio'] = json_encode($dataGraficos3['promedios']);
        $data['year']     = json_encode($dataGraficos3['year']);
        //$data += $this->dataAux();
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //1 = DocenteGeneral || 2 = DocenteXIndicador || 3 = IndicadorGeneral
    function evaluaTipoGrafico($idIndicador,$idDocente){
        $tipoGrafico = null;
        if($idIndicador == '' && $idDocente != ''){
            $tipoGrafico = DOCENTE_GENERAL;
        } else if($idIndicador != '' && $idDocente != ''){
            $tipoGrafico = DOCENTE_INDICADOR;
        } else if($idIndicador != '' && $idDocente == ''){
            $tipoGrafico = INDICADOR_GENERAL;
        }
        return $tipoGrafico;
    }
    
    function setIdSistemaInSession(){
        $idSistema = $this->encrypt->decode(_post('id_sis'));
        $idRol     = $this->encrypt->decode(_post('rol'));
        if($idSistema == null || $idRol == null){
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function logOut() {
        $this->session->sess_destroy();
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        redirect(RUTA_SMILEDU, 'refresh');
    }
    
    function getDataGraficosGeneral($result,$tipoGrafico){
        $data = null; 
        $arrayDesc = array();
        $arrayGeneralNota = array();
        $arrayFecha  = array(); 
        foreach($result['retval'] as $row){
            if($tipoGrafico == DOCENTE_INDICADOR){
                foreach($row['desc'] AS $docente){
                    array_push($arrayDesc, $docente['doce'].' | '.$docente['indi']);
                }
            } else{
                array_push($arrayDesc, $row['desc']);
            }
            $arrayNota = array();
            foreach ($row['nota_vige'] AS $nota){
                array_push($arrayNota, $nota);
            }
            foreach ($row['fec_eval'] AS $fecha){
                $fecha = implode("/", array_reverse(explode("-", $fecha)));
                array_push($arrayFecha, $fecha);
            }
            array_push($arrayGeneralNota, $arrayNota);
        }
        /*if($tipoGrafico == DOCENTE_INDICADOR){
            $arrayDesc = array_values(array_unique($arrayDesc));
        }*/
        $data['error'] = (count($arrayGeneralNota) != 0) ? EXIT_SUCCESS : EXIT_ERROR;
        $data['arrayFechas']       = $arrayFecha;
        $data['arrayGeneralNotas'] = $arrayGeneralNota;
        $data['desc']              = $arrayDesc;
        return $data;
    }
     
    function getDataPorcentajeIndicador($selectIndDecryp){
        $result = $this->m_grafico->getPromedioIndicadores($selectIndDecryp);
        $arrayGeneral = array();
        foreach ($result['_firstBatch'] AS $row){
            $arrayDatos = array('name' => $row['desc_indi'].', Nota: '.round($row['nota_vige'],2),
                                'y'    => $row['nota_vige']
                               );
            array_push($arrayGeneral, $arrayDatos);
        }
        return ($arrayGeneral);
    }
    
    function getDataPromedioIndicador($result){
        $arrayYear = array();
        $arrayIndi = array();
        $arrayGeneral = array();
        foreach($result['retval'] AS $row){
            $arrayProm = array();
            foreach($row['year'] AS $year){
                array_push($arrayYear, $year);
            }
            $arrayProm = array();
            foreach($row['nota_vige'] AS $notas){
                foreach($notas['notas'] AS $prom){
                    array_push($arrayProm, round($prom,2));
                }
                array_push($arrayGeneral, array('name' => $notas['desc_indi'],
                                                'data' => $arrayProm
                                                ));
            }
        }
        $data['year'] = array_values(array_unique($arrayYear));
        $data['promedios'] = $arrayGeneral;
        return $data;
    }
    
    function getDataPromedioDocentes($result){
        $arrayYears       = array();
        $arrayGeneralProm = array();
        $arrayDocentes    = array();
        $h = 0;
        foreach($result['retval'] AS $row){
            $arrayProm = array();
            foreach($row['prom'] AS $var){
                array_push($arrayDocentes, $var['nombre_docente']);
                array_push($arrayYears, $var['year']);
                array_push($arrayProm, round($var['nota'],2));
            }
            array_push($arrayGeneralProm, $arrayProm);
        }
        $data['year']      = array_values(array_unique($arrayYears));
        $data['docentes']  = array_values(array_unique($arrayDocentes));
        
        $data['promedios'] = $arrayGeneralProm;
        return $data;
    }
    
    function dataDocente($listaData,$arrayIds){
        $arrayNotas   = array();
        $arrayNombres = array();
        $arrayFechas = array();
        $arrayNombresAux = array();
        $arrayNotasAux   = array();
        $arrayCountAux   = array();
        foreach($arrayIds AS $id){
            $arrayNotas[$id] = array();
            $arrayNombres[$id] = array();
            $arrayCountAux[$id] = 0;
        }
        $count = 0;
        foreach($listaData['retval'] AS $row){
            array_push($arrayFechas, implode("/", array_reverse(explode("-", $row['_id']))));
            $countAux = 0;
            foreach($row['lista_eval'] AS $data){
                array_push($arrayNotas[$data['id_docente']], $data['nota']);
                $arrayCountAux[$data['id_docente']]++;
            }
            foreach(array_keys($arrayNotas) AS $id){
                if(count($arrayNotas[$id]) == $count){
                    array_push($arrayNotas[$id], null);
                }
            }
            foreach(array_keys($arrayCountAux) AS $idCount){
                if($arrayCountAux[$idCount] > 1){
                    for($i=0;$i<$arrayCountAux[$id]-1;$i++){
                        foreach(array_keys($arrayNotas) AS $idNota){
                            if($idNota != $idCount){
                                array_push($arrayNotas[$idNota],null);
                                $count++;
                            }
                        }
                    }
                    array_push($arrayFechas, implode("/", array_reverse(explode("-", $row['_id']))));
                }
                $arrayCountAux[$idCount] = 0;
            }
            foreach($row['desc_nombre'] AS $nomb){
                array_push($arrayNombres[$nomb['id_docente']], $nomb['desc']);
            }
            $count++;
        }
        foreach(array_keys($arrayNombres) AS $data){
            $arrayNombres[$data] = array_values(array_unique($arrayNombres[$data])); 
        }
        foreach($arrayNombres AS $data){
            if(count($data) > 0){
                array_push($arrayNombresAux, $data[0]);
            }
        }
        foreach($arrayNotas AS $data){
            $aux = false;
            foreach($data AS $nota){
                if(is_numeric($nota)){
                    $aux = true;
                }
            }
            if($aux == true){
                array_push($arrayNotasAux, $data);
            }
        }
        $result['fechas'] = json_encode($arrayFechas);
        $result['desc']   = json_encode($arrayNombresAux);
        $result['notas']  = json_encode($arrayNotasAux);
        return $result;
    }
    
    function dataIndicador($listaData, $arrayIds){
        $arrayCount    = array();
        $arrayNombres  = array();
        $arrayFechas   = array();
        $arrayCountAux = array();
        $arrayNombAux  = array();
        foreach($arrayIds AS $id){
            $arrayCount[$id]   = array();
            $arrayNombres[$id] = array();
        }
        $contador = 0;
        foreach($listaData['retval'] AS $row){
            array_push($arrayFechas, implode("/", array_reverse(explode("-", $row['_id']))));
            foreach($row['lista_count'] AS $data){
                array_push($arrayCount[$data['id_indicador']], $data['count']);
            }
            foreach($row['lista_nombres'] AS $data){
                array_push($arrayNombres[$data['id_indicador']], $data['desc_indi']);
            }
            foreach(array_keys($arrayCount) AS $id){
                if(count($arrayCount[$id]) == $contador){
                    array_push($arrayCount[$id], null);
                }
            }
            $contador++;
        }
        foreach(array_keys($arrayNombres) AS $key){
            $arrayNombres[$key] = array_values(array_unique($arrayNombres[$key]));
        }
        foreach($arrayNombres AS $data){
            if(count($data) > 0){
                array_push($arrayNombAux, $data[0]);
            }
        }
        foreach($arrayCount AS $data){
            $aux = false;
            foreach($data AS $nota){
                if(is_numeric($nota)){
                    $aux = true;
                }
            }
            if($aux == true){
                array_push($arrayCountAux, $data);
            }
        }
        $result['desc']   = json_encode($arrayNombAux);
        $result['notas']  = json_encode($arrayCountAux);
        $result['fechas'] = json_encode($arrayFechas);
        return $result;
    }
    
    function getDetalleEvaluacionesIndicador() {
        $nomIndi = _post('nomIndi');
        $fecha   = implode("-", array_reverse(explode("/", _post('fecha'))));
        $listaEval = $this->m_grafico->getDetalleEvalIndi($nomIndi,$fecha);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tbDetalle" data-toolbar="#custom-toolbar">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Fecha', 'class' => 'text-center');
        $head_2 = array('data' => 'Nota', 'class' => 'text-center');
        $this->table->set_heading($head_0,$head_1,$head_2);
        $count = 0;
        foreach($listaEval['retval'] AS $row) {
            $count++;
            $row_0 = array('data' => $count);
            $row_1 = array('data' => implode("-", array_reverse(explode("/", $row['fec_eval']))));
            $row_2 = array('data' => round($row['nota_vige'],2));
            $this->table->add_row($row_0,$row_1,$row_2);
        }
        $table = $this->table->generate();
        $data['table'] = $table;
        $data['titleTable'] = utf8_decode($nomIndi);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDataDocenteIndi($listaIdsInd,$listaIdsDoc,$listaData){
        $arrayIds = array();
        $arrayFechas  = array();
        $arrayDesc    = array();
        $arrayNota    = array();
        $arrayDescAux = array();
        $arrayNotaAux = array();
        foreach($listaIdsInd AS $idInd){
            foreach($listaIdsDoc AS $idDoc){
                array_push($arrayIds, $idInd.$idDoc);
                //$arrayIds[] = array();
            }
        }
        foreach($arrayIds AS $id){
            $arrayNota[$id] = array();
            $arrayDesc[$id] = array();
        }
        $contador = 0;
        foreach($listaData['retval'] AS $row){
            array_push($arrayFechas, implode("/", array_reverse(explode("-", $row['_id']))));
            foreach($row['data'] AS $data){
                array_push($arrayNota[$data['id_indi'].$data['id_docente']], $data['nota_vige']);
                array_push($arrayDesc[$data['id_indi'].$data['id_docente']], $data['desc_doce'].' | '.$data['desc_indi']);
            }
            foreach(array_keys($arrayNota) AS $id){
                if(count($arrayNota[$id]) == $contador){
                    array_push($arrayNota[$id], null);
                }
            }
            $contador++;
        }
        foreach(array_keys($arrayDesc) AS $key){
            $arrayDesc[$key] = array_values(array_unique($arrayDesc[$key]));
        }
        foreach($arrayDesc AS $data){
            if(count($data) > 0){
                array_push($arrayDescAux, $data[0]);
            }
        }
        foreach($arrayNota AS $data){
            $aux = false;
            foreach($data AS $nota){
                if(is_numeric($nota)){
                    $aux = true;
                }
            }
            if($aux == true){
                array_push($arrayNotaAux, $data);
            }
        }
        $result['descA']  = json_encode($arrayDescAux);
        $result['notaA']  = json_encode($arrayNotaAux);
        $result['fechaA'] = json_encode($arrayFechas);
        return $result;
    }
    
    function getDetalleEvaluacionDocente(){
        $nomDoce  = _post('nomDoce');
        $value    = _post('value');
        $fecha    = implode("-", array_reverse(explode("/", _post('fecha'))));
        $idEval   = $this->m_grafico->getIdEvavaluacion($nomDoce,$fecha,$value);
        $dataEval = $this->m_grafico->getDataEvaluacionDocente($idEval['_id']);
        $body     = '<div class="row">  
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Fecha</label>
                                 <p>'.implode("/", array_reverse(explode("-", $dataEval['fec_eval']))).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Nota</label>
                                 <p>'.$idEval['nota_vige'].'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Sede</label>
                                 <p>'.utf8_decode($dataEval['sede']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Nivel</label>
                                 <p>'.utf8_decode($dataEval['nivel']).'</p>
                             </div>
                         </div> 
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Área</label>
                                 <p>'.utf8_decode($dataEval['area']).'</p>
                             </div>
                         </div> 
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Aula</label>
                                 <p>'.utf8_decode($dataEval['aula']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Evaluador</label>
                                 <p>'.utf8_decode($dataEval['nombre_evaluador']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Docente</label>
                                 <p>'.utf8_decode($dataEval['nombre_docente']).'</p>
                             </div>
                         </div> 
                     </div>';
        $data['body']   = $body;
        $data['title'] = "Evaluaci&oacute;n";
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleEvaluacionIndiDoce() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $dato     = explode('|', _post('dato'));
            $docente  = trim($dato[0]);
            $indi     = trim($dato[1]);
            $fecha    = implode("-", array_reverse(explode("/", _post('fecha'))));
            $dataId   = $this->m_grafico->getIdEvavaluacionIndiDoc($docente, $indi, $fecha);
            $dataEval = $this->m_grafico->getDataEvaluacionIndiDoce($dataId['_id']);
            $body     = '<div class="row">
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Fecha</label>
                                 <p>'.implode("/", array_reverse(explode("-", $dataEval['fec_eval']))).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Nota</label>
                                 <p>'.$dataId['nota_vige'].'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Sede</label>
                                 <p>'.utf8_decode($dataEval['sede']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Nivel</label>
                                 <p>'.utf8_decode($dataEval['nivel']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>&Aacute;rea</label>
                                 <p>'.utf8_decode($dataEval['area']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Aula</label>
                                 <p>'.utf8_decode($dataEval['aula']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Evaluador</label>
                                 <p>'.utf8_decode($dataEval['nombre_evaluador']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Docente</label>
                                 <p>'.utf8_decode($dataEval['nombre_docente']).'</p>
                             </div>
                         </div>
                         <div class="col-sm-12">
                             <div class="form-group">
                                 <label>Indicador</label>
                                 <p>'.utf8_decode($indi).'</p>
                             </div>
                         </div>
                     </div>';
            $data['body']   = $body;
            $data['title'] = "Evaluaci&oacute;n";
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
}