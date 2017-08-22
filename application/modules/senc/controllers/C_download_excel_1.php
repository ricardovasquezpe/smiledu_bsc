<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_download_excel_1 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('mf_graficos/m_g_encuesta');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('m_utils');
        $this->load->library('Classes/PHPExcel.php');
        if(!isset($_COOKIE[$this->config->item('sess_cookie_name')])){
            $this->session->sess_destroy();
            redirect(RUTA_SMILEDU, 'refresh');
        }
    }
    
    public function index(){
        $logeoUsario = _getSesion('nid_persona');
        if($logeoUsario != null){
            $preguntas = json_decode(_post("jsonpreguntas"));
            $encuestas = json_decode(_post("jsonencuestas"));
            $arrEnc = array();
            foreach($encuestas as $id){
                $idDecry = _decodeCI($id);
                if($idDecry != null){
                    array_push($arrEnc, $idDecry);
                }
            }
            $idsEnc = $this->getArrayStringFromArray($encuestas);
            $result = $this->m_pregunta->getAllRespuestasPreguntasByEncuestas($idsEnc);
            $array  = $this->buildArrayToExcel($result,$arrEnc);
            $data['array']     = $array[0];
            $data['encuestas'] = $array[1];
            $data['objPHPExcel'] = new PHPExcel();
            $this->load->view('v_download_excel_1', $data);
        }else{
            $this->session->sess_destroy();
            redirect('','refresh');
        }
    }
    
   function getGraficoByPreguntas($encuestas1, $preguntas) {
       $excelData = array();
       $encuestas = $this->getArrayObjectFromArray($encuestas1);
       $preguntas = $this->getArrayObjectFromArray($preguntas, 1);
       $preguntas = $this->m_g_encuesta->getPreguntasById($encuestas, $preguntas);
       
       $encuestas = $this->getArrayStringFromArray($encuestas1);
       
       $excelData_count_all = array();
       $excelData_desc_all  = array();
       foreach($preguntas as $preg){
           $excelData_count = array();
           $excelData_desc  = array();
           if($preg->_id_tipo_pregunta == CINCO_CARITAS ||$preg->_id_tipo_pregunta == TRES_CARITAS ||$preg->_id_tipo_pregunta == CUATRO_CARITAS){
               $result = $this->m_g_encuesta->getGraficoPreguntasByEncuestas($preg->id_pregunta, $encuestas);
               if(count($result['retval']) >= 1){
                   array_push($excelData_count, $result['retval'][0]['count']); 
                   array_push($excelData_desc, $result['retval'][0]['desc_respuestas']);
               }
           }else if($preg->_id_tipo_pregunta == LISTA_DESPLEGABLE || $preg->_id_tipo_pregunta == DOS_OPCIONES || $preg->_id_tipo_pregunta == OPCION_MULTIPLE){
               $result  = $this->m_g_encuesta->getGraficoEncuestaTipoByPregunta($preg->id_pregunta, $encuestas);
               if(count($result['retval']) >= 1){
                   array_push($excelData_count, $result['retval'][0]['count']);
                   array_push($excelData_desc, $result['retval'][0]['desc_respuestas']);
               }
           }
           
           array_push($excelData_count_all, $excelData_count);
           array_push($excelData_desc_all, $excelData_desc);
       }
       
       array_push($excelData, $excelData_count_all);
       array_push($excelData, $excelData_desc_all);
       
       return $excelData;
   }
   
   function getArrayStringFromArray($data, $decrypt = null){
       $arrayIds = null;
       foreach ($data as $var){
           $id = null;
           if($decrypt == 1){
               $id = _simple_decrypt($var);
           }else{
               $id = _decodeCI($var);
           }
           if($id != null){
               $arrayIds .= $id.',';
           }
       }
       $arrayIds = substr($arrayIds,0,(strlen($arrayIds)-1));
       return $arrayIds;
   }
   
   function getArrayObjectFromArray($data, $decrypt = null){
       $arrayIds = array();
       foreach ($data as $var){
           $id = null;
           if($decrypt == 1){
               $id = _simple_decrypt($var);
           }else{
               $id = _decodeCI($var);
           }
           if($id != null){
               array_push($arrayIds, $id);
           }
       }
       return $arrayIds;
   }
   
   function buildArrayToExcel($result,$encuestas) {
       //ARRAY FINAL
       $arrayGeneral = array();
       $arrayIdDisp  = array();
       $arrayDescEnc = array();
       //ID DE CADA ENCUESTA CON SUS DISPOSITIVOS
       foreach($encuestas AS $id){
           $arrayGeneral[$id] = array();
           $arrayDisp = $this->m_pregunta->getAllIdDispositivos($id);
           $arrayIdDisp[$id] = array();
           $arrayDescEnc[$id] = null;
           foreach($arrayDisp as $idDisp){
               $arrayIdDisp[$id][$idDisp->dispositivo] = array();
           }
       }      
       $arrayAux = array();
       $arrayNivelesEnc = array();
       
       //LLENA LOS DATOS DE LAS ENCUESTAS POR CADA DISPOSITIVO
       foreach($result as $row){
           $arrayRpta  = $arrayIdDisp[$row['id_encuesta']];
           $arrayNivel = $arrayIdDisp[$row['id_encuesta']];
           if($arrayDescEnc[$row['id_encuesta']] == null){
               $arrayDescEnc[$row['id_encuesta']] = $this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $row['id_encuesta'], 'senc');
           }
           foreach($row['respuesta'] as $rpta){
               if(isset($arrayRpta[$rpta['id_unico']]['rpta'])){
                   array_push($arrayRpta[$rpta['id_unico']], $rpta['desc_respuesta']);
               } else{
                   $arrayRpta[$rpta['id_unico']]['rpta'] = $rpta['desc_respuesta'];
               }
           }
           foreach($row['niveles'] as $nivel){
               //SEDE
               $arrayNivel[$nivel['id_unico']]['sede']  = (isset($nivel['id_sede'])  && $nivel['id_sede'] != 0  && $nivel['id_sede'] != null)  ? 
                                                            $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', $nivel['id_sede'], 'schoowl')         : (isset($arrayNivel[$nivel['id_unico']]['sede'])  ? $arrayNivel[$nivel['id_unico']]['sede']  : null);
               //NIVEL
               $arrayNivel[$nivel['id_unico']]['nivel'] = (isset($nivel['id_nivel']) && $nivel['id_nivel'] != 0 && $nivel['id_nivel'] != null) ? 
                                                            $this->m_utils->getById('nivel' , 'desc_nivel' , 'nid_nivel' , $nivel['id_nivel'] , 'schoowl')  : (isset($arrayNivel[$nivel['id_unico']]['nivel']) ? $arrayNivel[$nivel['id_unico']]['nivel'] : null);
               //GRADO
               $arrayNivel[$nivel['id_unico']]['grado'] = (isset($nivel['id_grado']) && $nivel['id_grado'] != 0 && $nivel['id_grado'] != null) ? 
                                                            $this->m_utils->getById('grado' , 'desc_grado' , 'nid_grado' , $nivel['id_grado'] , 'schoowl')  : (isset($arrayNivel[$nivel['id_unico']]['grado']) ? $arrayNivel[$nivel['id_unico']]['grado'] : null);
               //AULA
               $arrayNivel[$nivel['id_unico']]['aula']  = (isset($nivel['id_aula'])  && $nivel['id_aula'] != 0 && $nivel['id_aula'] != null) ? 
                                                            $this->m_utils->getById('aula'  , 'desc_aula'  , 'nid_aula'  , $nivel['id_aula']  , 'schoowl')  : (isset($arrayNivel[$nivel['id_unico']]['aula'])  ? $arrayNivel[$nivel['id_unico']]['aula']  : null);
               //AREA
               $arrayNivel[$nivel['id_unico']]['area']  = (isset($nivel['id_area'])  && $nivel['id_area'] != 0 && $nivel['id_area'] != null) ? 
                                                            $this->m_utils->getById('area'  , 'desc_area'  , 'id_area'   , $nivel['id_area']  , 'schoowl')  : (isset($arrayNivel[$nivel['id_unico']]['area'])  ? $arrayNivel[$nivel['id_unico']]['area']  : null);
           }
           $arrayNivelesEnc[$row['id_encuesta']] = $arrayNivel;
           array_push($arrayGeneral[$row['id_encuesta']], array('desc_preg' => $row['desc_pregunta'], 'rpta' => array_values($arrayRpta)));
       }
       return array(array_values($arrayGeneral),array_values($arrayDescEnc));
   }
}