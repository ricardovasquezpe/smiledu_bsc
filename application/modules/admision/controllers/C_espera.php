<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_espera extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_espera/m_espera');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_TURNOS, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
   
	public function index() {
	    $infoEvento = $this->m_espera->getInfoEventoHoy();
	    $data = null;
	    if($infoEvento != null){
	        $data['descripcion'] = $infoEvento['desc_evento'];
	        $postulantesSuTurno     = $this->m_espera->getPostulantesSuTurno($infoEvento['id_evento']);
	        $postulantesPerdioTurno = $this->m_espera->getPostulantesPerdioTurno($infoEvento['id_evento']);
	        $postulantesEnEspera    = $this->m_espera->getPostulantesEnEspera($infoEvento['id_evento']);
	        $data['suTurno']     = $this->getContCardsPostulantes($postulantesSuTurno);
	        $data['perdioTurno'] = $this->getContCardsPostulantes($postulantesPerdioTurno);
	        $data['enEspera']    = $this->getContCardsPostulantesEnEspera($postulantesEnEspera);
	        $dataUser = array("id_evento_turno"   => $infoEvento['id_evento']);
	        $data['server_node'] = NODE_SERVER;
	        $this->session->set_userdata($dataUser);
	        $this->load->view('v_espera', $data);
	    }else{
	       redirect("admision/c_main");
	    }
	}
	
	function getContCardsPostulantes($postulantes){
	    $cards = null;
	    foreach ($postulantes as $row){
	        $cards .= '<div class="mdl-card" id="card_post-'.$row->id_contacto.'">
                          <div class="foto">
                              <img class="img-circle" width="50" src="'.RUTA_IMG_PROFILE.'nouser.svg">
                          </div>
                          <div class="contenido">
                              <h2>'.$row->nombrecompleto.'</h2>
                              <p>Entrevistador: '.$row->evaluador.'</p>
                          </div>
                       </div>';
	    }
	    return $cards;
	}
	
	function getContCardsPostulantesEnEspera($postulantes){
	    $cards = null;
	    foreach ($postulantes as $row){
	        _log($row->hora);
	        $cards .= '<div class="mdl-card" id="card_post-'.$row->id_contacto.'">
                        <div class="foto">
                            <img class="img-circle" width="35" src="'.RUTA_IMG_PROFILE.'nouser.svg">
                        </div>
                        <div class="contenido">
                            <h2>'.$row->nombrecompleto.'</h2>
                        </div>
                        <span>'._fecha_tabla($row->hora, 'h:i A').'</span>
                    </div>';
	    }
	    return $cards;
	}
	
	function postulantesRestantes(){
	    $postulantesSuTurno     = _post("suturno");
	    $postulantesPerdioTurno = _post("perdioturno");
	    $postulantesEspera      = _post("espera");
	    $pos_final_suTurno     = array();
	    $pos_final_perdioTurno = array();
	    $pos_final_espera      = array();
	    $data = null;
	    if($postulantesSuTurno != null){
	        foreach ($postulantesSuTurno as $post){
	            array_push($pos_final_suTurno, str_replace("card_post-", "", $post));
	        }
	        $postulantes = $this->m_espera->getPostulantesRestantes(_getSesion("id_evento_turno"), $pos_final_suTurno);
	        $data['postulantesSuTurno'] = $this->getContCardsPostulantes($postulantes);
	    }
	    if($postulantesPerdioTurno != null){
	        foreach ($postulantesPerdioTurno as $post){
	            array_push($pos_final_perdioTurno, str_replace("card_post-", "", $post));
	        }
	        $postulantes = $this->m_espera->getPostulantesRestantes(_getSesion("id_evento_turno"), $pos_final_perdioTurno);
	        $data['postulantesPerdioTurno'] = $this->getContCardsPostulantes($postulantes);
	    }
	    if($postulantesEspera != null){
    	    foreach ($postulantesEspera as $post){
    	        array_push($pos_final_espera, str_replace("card_post-", "", $post));
    	    }
    	    $postulantes = $this->m_espera->getPostulantesRestantes(_getSesion("id_evento_turno"), $pos_final_espera);
    	    $data['postulantesEspera'] = $this->getContCardsPostulantesEnEspera($postulantes);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
}
