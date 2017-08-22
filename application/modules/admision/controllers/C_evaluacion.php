<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_evaluacion extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_contactos/m_contactos');
        $this->load->model('mf_evento/m_detalle_evento');
        $this->load->model('mf_evaluacion/m_evaluacion');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_EVENTO, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['return'] = '';
	    $data['titleHeader'] = "Progreso Evaluaci&oacute;n: ".$this->m_utils->getById("admision.evento", "desc_evento", "id_evento", _getSesion("idEventoProgreso"));
	    $data['ruta_logo'] = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo'] = NAME_MODULO_ADMISION;
        $data['comboGradoNivel'] = __buildComboGradoNivel();

	    $contactos = null;
	    $data['tabs'] = $this->getTabs(null, null);
	    if($this->_idRol == ID_ROL_SECRETARIA){
	        $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
	                                   <a href="#tab-3" class="mdl-layout__tab is-active" id="tabEval" onclick = "getContactosByEstado(\''._simple_encrypt(EVALUACION_ENTREVISTADO).'\', 3)">Entrevistados</a>
                                    </div>';
	        $contactosArray = $this->m_evaluacion->getContactosEntrevistados(_getSesion("idEventoProgreso"));
	        $data['estadoTab'] = _simple_encrypt(EVALUACION_ENTREVISTADO);
	        $data['indexTab']    = 3;
	        if(count($contactosArray) > 0){
	            $contactos = _create_cards_evaluacion($contactosArray, 1)['cardsFamilia'];
	            $data['tabs'] = $this->getTabs(null, $contactos);
	            $data['display'] = 'none';
	        }else{
	            $data['display'] = 'block';
	        }
	        $data['tab'] = EVALUACION_ENTREVISTADO;
	    }else{
	        $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                       <a href="#tab-1" class="mdl-layout__tab is-active" id="tabProspectos" onclick = "getContactosByEstado(\''._simple_encrypt(EVALUACION_A_EVALUAR).'\', 1)">Evaluar</a>
                                       <a href="#tab-2" class="mdl-layout__tab" onclick = "getContactosByEstado(\''._simple_encrypt(EVALUACION_EVALUADO).'\', 2)">Evaluados</a>
                                       <a href="#tab-3" class="mdl-layout__tab" id="tabEval" onclick = "getContactosByEstado(\''._simple_encrypt(EVALUACION_ENTREVISTADO).'\', 3)">Entrevistados</a>
                                       <a href="#tab-4" class="mdl-layout__tab" onclick = "getContactosByEstado(\''._simple_encrypt(EVALUACION_MATRICULA).'\', 4)">Matricula</a>
                                    </div>';
	        $contactosArray = $this->m_evaluacion->getContactosAEvaluarEvaluados(_getSesion("idEventoProgreso"));
	        $data['estadoTab'] = _simple_encrypt(EVALUACION_A_EVALUAR);
	        $data['indexTab']    = 1;
	        if(count($contactosArray) > 0){
	            $contactos = _create_cards_evaluacion($contactosArray)['cardsFamilia'];
	            $data['tabs'] = $this->getTabs($contactos, null);
	            $data['display'] = 'none';
	        }else{
	            $data['display'] = 'block';
	        }
	        $data['tab'] = EVALUACION_A_EVALUAR;
	    }
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $data['evento']      = _getSesion("idEventoProgreso");
	    //$data['evento']      = _getSesion('idEvent');
	    $data['server_node'] = NODE_SERVER;
	    $this->load->view('v_evaluacion',$data);
	}
	
	function getTabs($evaluar = null, $entrevistados = null){
	    $tabs = null;
	    
	    $tab_2 = '  <section class="mdl-layout__tab-panel" id="tab-2">
                        <div class="mdl-content-cards" id="cont_contactos_2"></div>
                    </section>';

	    $tab_4 = '  <section class="mdl-layout__tab-panel" id="tab-4">
                        <div class="mdl-content-cards" id="cont_contactos_4"></div>
                    </section>';
	    	    
	    if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_SUBDIRECTOR){
	        $tabs =  '  <section class="mdl-layout__tab-panel is-active" id="tab-1">
	                       <div class="mdl-content-cards">
                                <div id="cont_contactos_1">'.$evaluar.'</div>
                            </div>
                        </section>
                     '. $tab_2.
	                 '  <section class="mdl-layout__tab-panel" id="tab-3">
                            <div class="mdl-content-cards" id="cont_contactos_3"></div>
                        </section>
                     '. $tab_4;
	    }else if($this->_idRol == ID_ROL_PSICOPEDAGOGO_SEDE){
	        $tabs =  '  <section class="mdl-layout__tab-panel is-active" id="tab-1">
                            <div class="mdl-content-cards" id="cont_contactos_1">'.$evaluar.'</div>
                        </section>
                     '. $tab_2;
	    }else if($this->_idRol == ID_ROL_SECRETARIA){
	        $tabs =  '  <section class="mdl-layout__tab-panel is-active" id="tab-3">
                            <div class="mdl-content-cards" id="cont_contactos_3">'.$entrevistados.'</div>
                        </section>
                     '. $tab_4;
	    }else{
	        $tabs =  '  <section class="mdl-layout__tab-panel is-active" id="tab-1">
                            <div class="mdl-content-cards" id="cont_contactos_1">'.$evaluar.'</div>
                        </section>
                     '. $tab_2;
	    }
	    
	    return $tabs;
	}
	
	function contactosPorEstado(){
	    $estado = _simpleDecryptInt(_post("estado"));
	    $grado = null;
	    $nivel = null;
	    if(strlen(_post("gradonivel")) != 0){
	        $gradoNivel = _simple_decrypt(_post("gradonivel"));
	        $gradoNivel = explode('_', $gradoNivel);
	        $grado      = $gradoNivel[0];
	        $nivel      = $gradoNivel[1];
	    }
	    $curso = null;
	    if(strlen(_post("curso")) != 0){
	        $curso = _simpleDecryptInt(_post("curso"));
	    }
	    $contactos = array();
	    if($estado == EVALUACION_A_EVALUAR){
	        $contactos = $this->m_evaluacion->getContactosAEvaluarEvaluados(_getSesion("idEventoProgreso"), null, $nivel, $grado, $curso);
	    }else if($estado == EVALUACION_EVALUADO){
	        $contactos = $this->m_evaluacion->getEvaluados(_getSesion("idEventoProgreso"));
	    }else if($estado == EVALUACION_ENTREVISTADO){
	        $contactos = $this->m_evaluacion->getContactosEntrevistados(_getSesion("idEventoProgreso"));
	    }else{
	        $contactos = $this->m_evaluacion->getContactosMatricula(_getSesion("idEventoProgreso"));
	    }
	    $data['count']     = count($contactos);
	    if(count($contactos) > 0){
	        $data['contactos'] = _create_cards_evaluacion($contactos)['cardsFamilia'];
	    }
	    $data['evento'] = _getSesion("idEventoProgreso");
	    $data['tab']    = $estado;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function goToEvaluarContacto(){
	    $id_contacto = _simpleDecryptInt(_post("idcontacto"));
	    $data['opc'] = 0;
	    if($this->_idRol == ID_ROL_SECRETARIA){
	        $data['opc'] = 1;
	    }
	    $dataUser = array("idPostulanteEvaluar"  => $id_contacto,
	                      "pantalla_evaluar"     => 1);
	    $this->session->set_userdata($dataUser);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function resumenDiagnosticos(){
	    $idContacto = _simpleDecryptInt(_post("contacto"));
	    $diagnosticos = $this->m_evaluacion->getDiagnosticosResumen($idContacto, _getSesion("idEventoProgreso"));
	    $data['tabla'] = _createTableResumenDiagnostico($diagnosticos);
	    $diagnosticoSubdirector = $this->m_evaluacion->getDiagnosticoSubdirector($idContacto, _getSesion("idEventoProgreso"));
	    $data['tablaSubdirector'] = _createTableDiagnosticoSubdirector($diagnosticoSubdirector);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function procesoMatricula(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try{
	        $idContacto = _simpleDecryptInt(_post("contacto"));
	        if($idContacto == null){
	            throw new Exception(ANP);
	        }
	        $diag = $this->m_evaluacion->getDiagnosticoSubdirector($idContacto, _getSesion("idEventoProgreso"));
	        if($diag['diagnostico'] != 'Apto'){
	            throw new Exception("El contacto no est&aacute; apto");
	        }
	        $estado = $this->m_utils->getById("admision.contacto", "estado", "id_contacto", $idContacto);
	        if($estado == ESTADO_CONTACTO_PAGO_CUOTA_INGRESO){
	            throw new Exception("El contacto ya est&aacute; en el proceso de matricula");
	        }
	        $codGrupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContacto);
	        $data = $this->m_evaluacion->procesoMatricula($idContacto, $codGrupo, _getSesion("idEventoProgreso"));
	        if($data['error'] == EXIT_SUCCESS){
	            $data['msj'] = 'La familia se insert&oacute; correctamente en matricula';
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function cursosByGradoNivel(){
	    $gradoNivel = _simple_decrypt(_post("gradonivel"));
	    $grado = null;
	    $nivel = null;
	    if(strlen($gradoNivel) != 0){
	        $gradoNivel = explode('_', $gradoNivel);
	        $grado      = $gradoNivel[0];
	        $nivel      = $gradoNivel[1];
	        $cursos = $this->m_evaluacion->getCursosByGradoNivel($nivel, $grado);
	        $data['cursos'] = $this->buildComboCursos($cursos);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function filtrarPostulantes(){
	    //$nombre = (strlen(_post("nombre")) == 0) ? null : _post("nombre");
	    $estado     = _simpleDecryptInt(_post("estado"));
	    $grado = 0;
	    $nivel = 0;
	    if(strlen(_post("gradonivel")) != 0){
	        $gradoNivel = _simple_decrypt(_post("gradonivel"));
	        $gradoNivel = explode('_', $gradoNivel);
	        $grado      = $gradoNivel[0];
	        $nivel      = $gradoNivel[1];
	    }
	    $curso = 0;
	    if(strlen(_post("curso")) != 0){
	        $curso = _simpleDecryptInt(_post("curso"));
	    }
	    
	    /*$contactos = array();
	    if($estado == EVALUACION_A_EVALUAR){
	        $contactos = $this->m_evaluacion->getContactosAEvaluarEvaluados(_getSesion("idEventoProgreso"), null, $nivel, $grado);
	    }else if($estado == EVALUACION_EVALUADO){
	        $contactos = $this->m_evaluacion->getEvaluados(_getSesion("idEventoProgreso"));
	    }else{
	        $contactos = $this->m_evaluacion->getContactosEntrevistados(_getSesion("idEventoProgreso"));
	    }
	    $data['count']     = count($contactos);
	    if(count($contactos) > 0){
	        $data['contactos'] = _create_cards_evaluacion($contactos)['cardsFamilia'];
	         
	    }*/
	    
	    $data['evento'] = _getSesion("idEventoProgreso");
	    $data['tab']    = $estado;
	    $data['grado']  = $grado;
	    $data['nivel']  = $nivel;
	    $data['curso']  = $curso;
 	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function postulantesRestantes(){
	    $postulantes = _post("postulantes");
	    $pos_final = array();
	    foreach ($postulantes as $post){
	        array_push($pos_final, str_replace("mdl-inscritos-cont-", "", $post));
	    }
	    if($this->_idRol == ID_ROL_SECRETARIA){
	        $contactos = $this->m_evaluacion->getContactosRestantes(_getSesion("idEventoProgreso"), $pos_final);
	        $data['count']     = count($contactos);
	        if(count($contactos) > 0){
	            $data['contactos'] = _create_cards_evaluacion($contactos, 1)['cardsFamilia'];
	        }
	    }else{
	        $contactos = $this->m_evaluacion->getContactosRestantes(_getSesion("idEventoProgreso"), $pos_final);
	        $data['count']     = count($contactos);
	        if(count($contactos) > 0){
	            $data['contactos'] = _create_cards_evaluacion($contactos)['cardsFamilia'];
	        }
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildComboCursos($data) {
	    $opcion = '';
	    foreach ($data as $cr) {
	        $idCursosEnc = _simple_encrypt($cr->id_config_eval);
	        $opcion .= '<option value="'.$idCursosEnc.'">'.$cr->descripcion.'</option>';
	    }
	    return $opcion;
	}
	
	function logOut() {
	    $this->session->sess_destroy();
	    unset($_COOKIE['schoowl']);
	    $cookie_name2 = "schoowl";
	    $cookie_value2 = "";
	    setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	    redirect(RUTA_SMILEDU, 'refresh');
	}
}