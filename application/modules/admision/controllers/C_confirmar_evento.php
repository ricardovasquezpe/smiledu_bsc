<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_confirmar_evento extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_evento/m_detalle_evento');
        $this->load->model('mf_evento/m_evento');
        $this->load->model('mf_confirm_evento/m_confirm_evento');
    }
    
    public function index() {
        $personaReg = $this->m_utils->getById("admision.recurso_x_evento", "id_responsable", "id_recurso_x_evento", _getSesion("recursoEventoConfirmar"));
        if($personaReg == null){
            $arrayUpdate = array("flg_confirmacion"   => _getSesion("opcionConfirmar"),
                "fecha_confirmacion" => date('Y-m-d'));
            $this->m_detalle_evento->asistenciaApoyoAdministrativo(_getSesion("recursoEventoConfirmar"), _getSesion("personaConfirmar"), $arrayUpdate);
        }
        $infoEvento = $this->m_evento->getDetalleEvento($this->m_utils->getById("admision.recurso_x_evento", "id_evento", "id_recurso_x_evento", _getSesion("recursoEventoConfirmar")));
        $data['nomEvento']  = $infoEvento['desc_evento'];
        $data['nomPersona'] = $this->m_detalle_evento->getNombrePersonaCorreo(_getSesion("personaConfirmar"));
        $data['opc']        = _getSesion("opcionConfirmar");
        $this->load->view('v_confirmar_evento', $data);
    }
    
    function guardarObservacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $observacion = ucfirst(__only1whitespace(_post('observacion')));
            $personaReg = $this->m_utils->getById("admision.recurso_x_evento", "id_responsable", "id_recurso_x_evento", _getSesion("recursoEventoConfirmar"));
            $arrayUpdate = array("observacion_resp" => $observacion);
            if($personaReg == null){//APOYO ADMINISTRATIVO
                $data = $this->m_confirm_evento->updatePersonaRecurso($arrayUpdate, _getSesion("personaConfirmar"), _getSesion("recursoEventoConfirmar"));
            }else{//RECURSO MATERIAL
                $data = $this->m_confirm_evento->updateRecursoMaterial($arrayUpdate, _getSesion("recursoEventoConfirmar"));
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
