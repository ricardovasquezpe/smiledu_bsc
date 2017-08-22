<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class c_frecuencia_medicion extends CI_Controller {

public function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->model('mf_indicador/m_deta_indi_modal');
        $this->load->library('table');
            
    }
     
    public function index(){
    }
    
    function editarFreqMedicion() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $pk = $this->encrypt->decode($this->input->post('pk'));
            if($pk == null){
                throw new Exception(ANP);
            }
            $pkEncry     = $this->input->post('pk');
            $valor       = trim(utf8_decode($this->input->post('value')));
            $columna     = $this->input->post('name');//columna
            $idIndicador = _simple_encrypt($this->session->userdata('id_indicador'));
            if($columna == 'fecha_medicion') {
                //Validar el formato de la fecha
                $fechasLimites    = $this->m_deta_indi_modal->getMedidasBeforeAfterByIndicador($idIndicador ,$pk);
                $fechaAntesBd     = $fechasLimites['fechaantes'];
                $fechaDespuesBd   = $fechasLimites['fechadespues'];
                $newFechaAntes    = date('d/m/Y',strtotime($fechaAntesBd));
                $newFechaDespues  = date('d/m/Y',strtotime($fechaDespuesBd));
                $fechaActual      = date('d/m/Y');
                $newFechaActual   = date('Y-m-d');
                $lastDay          = date('Y').'-12-30';
                $valor = DateTime::createFromFormat('d/m/Y', $valor);
                $valor = $valor->format('Y-m-d');
                
                if($fechaAntesBd != null && $fechaDespuesBd != null){
                    if($valor <= $fechaAntesBd || $valor >= $fechaDespuesBd){
                        throw new Exception('La fecha debe estar entre '.$newFechaAntes.' y '.$newFechaDespues);
                    } else{
                        if($valor < $newFechaActual){
                            throw new Exception('La fecha debe ser mayor a la actual');
                        } else if($valor > $lastDay){
                            throw new Exception('La fecha debe ser del año actual');
                        } else{
                            $data = $this->m_deta_indi_modal->editFrecuencia($pk, $columna, $valor);
                        }
                    }
                } else if($fechaAntesBd != null && $fechaDespuesBd == null){
                    if($valor <= $fechaAntesBd){
                        throw new Exception('La fecha debe ser mayor que '.$newFechaAntes);
                    } else{
                        if($valor < $newFechaActual){
                            throw new Exception('La fecha debe ser del año actual');
                        } else if($valor > $lastDay){
                            throw new Exception('La fecha debe ser del año actual');
                        } else{
                            $data = $this->m_deta_indi_modal->editFrecuencia($pk, $columna, $valor);
                        }
                    }
                } else if($fechaDespuesBd != null && $fechaAntesBd == null){
                    if($valor >= $fechaDespuesBd){
                        $data['msj'] = 'La fecha debe ser menor que '.$newFechaDespues;
                        throw new Exception('La fecha debe ser menor que '.$newFechaDespues);
                    } else{
                        if($valor < $newFechaActual){
                            throw new Exception('La fecha debe ser mayor que la actual');
                        } else{
                            $data = $this->m_deta_indi_modal->editFrecuencia($pk, $columna, $valor);
                        }
                    }
                } else if($fechaAntesBd == null && $fechaDespuesBd == null){
                    if($valor < $newFechaActual) {
                        throw new Exception('La fecha debe ser mayor que la actual');
                    }else if($valor > $lastDay){
                        throw new Exception('La fecha debe ser del año actual');
                    } else {
                        $data = $this->m_deta_indi_modal->editFrecuencia($pk, $columna, $valor);
                    }
                }
            } else if($columna == 'desc_frecuencia'){
                $data = $this->m_deta_indi_modal->editFrecuencia($pk, $columna, $valor);
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['pk']  = $pkEncry;
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
            header("HTTP/1.0 666 ".$data['msj'], TRUE, NULL);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function addNuevaMedicion() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $nro_medicion    = $this->input->post('nroMedicion');
            $desc_frecuencia = $this->input->post('descFrecuencia');
            $fecha_medicion  = $this->input->post('fechaMedicion');
            $idIndicador     = _simple_decrypt($this->session->userdata('id_indicador'));
            $lastFecha       = $this->m_deta_indi_modal->getLastFechaMedicion($idIndicador);
            $newLastFecha    = date('d/m/Y',strtotime($lastFecha['fecha_medicion']));
            $fechaActual     = date('d/m/Y');
            if($nro_medicion <= 0 || $desc_frecuencia == null || $fecha_medicion == null) {
                throw new Exception('No deben haber campos vacios');
            }
            if($lastFecha['fecha_medicion'] != null) {
                if($newLastFecha >= $fecha_medicion) {
                    throw new Exception('La fecha debe ser mayor a la ultima');
                }
            } else {
                if($fechaActual >= $fecha_medicion) {
                    throw new Exception('La fecha debe ser mayor a la ultimas');
                }
            }
            $insert = array('__id_indicador'  => $idIndicador ,
                            'year'            => date("Y"),
                            'desc_frecuencia' => $desc_frecuencia,
                            'fecha_medicion'  => $fecha_medicion,
                            'flg_medido'      => NO_MEDIDO,
                            'nro_medicion'    => $nro_medicion);
            $data = $this->m_indicador->insertNuevaMedicion($insert);
            $data = _buildTableHTMLFrecuencias($idIndicador, $data);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTableHTMLFrecuencias($idIndicador, $data){
        $listaTable = ($idIndicador != null) ? $this->m_deta_indi_modal->getAllFrecuenciasByIndicador($idIndicador) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="true" id="tb_frecuencias">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Descripción');
        $head_2 = array('data' => 'Fecha');
        $head_3 = array('data' => '¿Medido?');
        $head_4 = array('data' => 'Fecha de Medición');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $val = 0;
        $ultimo = 0;
        foreach ($listaTable as $row){
            $idFreqCrypt = $this->encrypt->encode($row->id_frecuencia);
            $newformat2 = null;
            $val++;
            $row_0 = array('data' => $row->nro_medicion , 'class' => $row->color);
            if($row->medido == 'No'){
                $row_1 = array('data' => $this->getSpan("classDescrip",$idFreqCrypt).$row->desc_frecuencia.'</span>' , 'class' => $row->color);
                $time = strtotime($row->fecha_medicion);
                $newformat = date('d/m/Y',$time);
                $row_2 = array('data' => $this->getSpan("classFecha",$idFreqCrypt).$newformat.'</span>' , 'class' => $row->color);
                if($row->fecha_medido != null){
                    $time2 = strtotime($row->fecha_medido);
                    $newformat2 = date('d/m/Y h:i:s A',$time2);
                }
            }else{
                $row_1 = array('data' => $row->desc_frecuencia , 'class' => $row->color);
                $time = strtotime($row->fecha_medicion);
                $newformat = date('d/m/Y',$time);
                $row_2 = array('data' => $newformat  , 'class' => $row->color);
                if($row->fecha_medido != null){
                    $time2 = strtotime($row->fecha_medido);
                    $newformat2 = date('d/m/Y h:i:s A',$time2);
                }
            }
            $row_3 = array('data' => $row->medido          , 'class' => $row->color);
            $row_4 = array('data' => $newformat2    , 'class' => $row->color);
    
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            $ultimo = $row->nro_medicion;
        }
        $data['lastMedicion'] = $ultimo + 1;
        $data['tabla'] = $this->table->generate();
        return $data;
    }
    
    function getSpan($clase, $id) {
        return '<span class="'.$clase.' editable editable-click" data-pk="'.$id.'">';
    }
    
    function getAllFrecuenciasXIndicador(){
        $idIndicador     = _simple_encrypt($this->session->userdata('id_indicador'));
        $data['error'] = EXIT_SUCCESS;
        $data = $this->lib_utils->buildTableHTMLFrecuencias($idIndicador, $data);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cambioRol(){
        $idRolEnc = $this->input->post('id_rol');
        $idRol = _simple_encrypt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
    
        $dataUser = array("id_rol"     => $idRol,
            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
    
        $idRol     = $this->session->userdata('nombre_rol');
    
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function setIdSistemaInSession(){
        $idSistema = $this->encrypt->decode($this->input->post('id_sis'));
        $idRol     = $this->encrypt->decode($this->input->post('rol'));
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
        Redirect(RUTA_SCHOOWL, true);
    }
    
    function enviarFeedBack(){
        $nombre = $this->session->userdata('nombre_usuario');
        $mensaje = $this->input->post('feedbackMsj');
        $url = $this->input->post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}