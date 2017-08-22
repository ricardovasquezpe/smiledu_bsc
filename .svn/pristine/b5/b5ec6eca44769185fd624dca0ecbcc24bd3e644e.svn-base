<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_aula extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
	function __construct() {
		parent::__construct();
		$this->output->set_header(CHARSET_ISO_8859_1);
		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->load->library('table');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
		$this->load->model('mf_aula/m_aula');
		$this->load->model('mf_persona/m_persona');
		$this->load->model('mf_matricula/m_matricula');
		_validate_uso_controladorModulos(ID_SISTEMA_MATRICULA, ID_PERMISO_AULA, MATRICULA_ROL_SESS);
		$this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
	}
	
	public function index() {
	    $dataUser = array("previousPage" => 'c_aula');
	    $this->session->set_userdata($dataUser);
		$data = _searchInputHTML('Busca tus aulas','onchange = "buscarAula()"');
		$data['tablaAulas']  = _createTableAulas(array());
		$data['comboYear']   = __createComboYear();
		//$data['docentes']    = __createComboDocentes();
		//$data['tipoNotas']   = __createComboTipoNotas();
		$data['titleHeader'] = "Aulas";
	    $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
            	             <a href="#tab-1" class="mdl-layout__tab is-active" onclick = "getAulasByTipoCiclo(\'\', this)" id="tabTodos" style="cursor: pointer">Todas</a>
            	             <a href="#tab-1" class="mdl-layout__tab" onclick = "getAulasByTipoCiclo(\''.TIPO_CICLO_REGULAR.'\', this)" style="cursor: pointer">Regular</a>
            	             <a href="#tab-1" class="mdl-layout__tab" onclick = "getAulasByTipoCiclo(\''.TIPO_CICLO_VERANO.'\', this)" style="cursor: pointer">Verano</a>
            	             </div>';

		$data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);
		
		$data['ruta_logo'] = MENU_LOGO_MATRICULA;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
        $data['nombre_logo'] = NAME_MODULO_MATRICULA;
        
        $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
        $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $menu = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
		$this->load->view('v_aula',$data);
	}

	function onScrollGetAulas(){
	    $count = _post("count");
	    if($this->_idRol != ID_ROL_ADMINISTRADOR){
		    $idSedeRol = _getSesion('id_sede_trabajo');
		    $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede(null, null, $idSedeRol, NUMERO_AULAS_CARGA, ($count*NUMERO_AULAS_CARGA));
	        $data['tablaAulas'] = _createTableAulas($aulas, ($count*NUMERO_AULAS_CARGA));
	    } else {
	        $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede(null, null, null, NUMERO_AULAS_CARGA, ($count*NUMERO_AULAS_CARGA));
	        $data['tablaAulas'] = _createTableAulas($aulas, ($count*NUMERO_AULAS_CARGA));
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getSedeRol(){
	    if ($this->_idRol != ID_ROL_ADMINISTRADOR){
	        $data['error']    = EXIT_ERROR;
	        $data['sedeRol'] = _simple_encrypt(_getSesion('id_sede_trabajo'));
	    } else {
	        $data['error']    = 0;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE[__getCookieName()]);
        $cookie_name2 = __getCookieName();
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
	
	function getSedesByYear() {
	    if($this->_idRol != ID_ROL_ADMINISTRADOR){
	        $idSedeRol = _simple_encrypt(_getSesion('id_sede_trabajo'));
	        $desc_sede = $this->m_utils->getById("sede", "desc_sede", "nid_sede", _getSesion('id_sede_trabajo'));
	        $data['comboSedes'] = '<option value="'.$idSedeRol.'">'.ucfirst($desc_sede).'</option>';
	    } else {
	        $year = _post('year');
	        $data['comboSedes'] = __buildComboSedesByYear($year);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGradoNivelBySedeYear() {
	    $year    = _post('year');
	    $idSede  = utf8_decode(_simpleDecryptInt(_post('idsede')));
	    $gradoNivel = __buildComboGradoNivelBySedeYear($idSede, $year);
	    $data['comboGradoNivel'] = $gradoNivel;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTablaAulasByGradoNivelSedeYear(){
	    $year    = _post('year');
	    $idSede  = _simpleDecryptInt(_post('idsede'));
		if( $this->_idRol != ID_ROL_ADMINISTRADOR ){
		    if($idSede != _getSesion('id_sede_trabajo')){
		        echo json_encode(array_map('utf8_encode', null));
		    }
		}
	    $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	    $gradoNivel = explode('_', $idGradoNivel);
	    $aulas = $this->m_aula->getAllAulasByGradoYear($year, $idSede, $gradoNivel[1], $gradoNivel[0], null);
	    $data['tablaAulas'] = _createTableAulas($aulas);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAulasPendientes(){
	    $resultado = $this->m_aula->getAulasPendientes();
        $data['tablaAulas'] = _createTableAulas($resultado);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buscarAula(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $tipoCiclo     = (strlen(_post('ciclo')) == 0) ? NULL : _post('ciclo');
	    $textBusqueda  = (_post('textoBusqueda'));
	    try {
	        if($this->_idRol != ID_ROL_ADMINISTRADOR){
	            $resultado = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($textBusqueda,$tipoCiclo,_getSesion('id_sede_trabajo'));
	            $data['tablaAulas'] = _createTableAulas($resultado);
	        } else {
	            $resultado = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($textBusqueda,$tipoCiclo,NULL);
	            $data['tablaAulas'] = _createTableAulas($resultado);
	        }
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAulasByTipoCiclo(){
	    $txtBusqueda   = (strlen(_post('textobusqueda')) == 0) ? NULL : _post('textobusqueda');
	    $tipoCiclo     = (strlen(_post('ciclo')) == 0) ? NULL : _post('ciclo');
	    $idSedeRol     = _getSesion('id_sede_trabajo');
	    $data['error'] = NULL;
	    if($this->_idRol != ID_ROL_ADMINISTRADOR){
	        if (strlen($txtBusqueda) > 0){//Se escribio en la busqueda
	            $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($txtBusqueda,$tipoCiclo,$idSedeRol);
	            $data['tablaAulas'] = _createTableAulas($aulas);
	        } else if (strlen(_post('year')) > 0){//Se eligio filtro
	            $year         = _post('year');
	            $idSede       = _simpleDecryptInt(_post('idsede'));
	            
	            if( $this->_idRol != ID_ROL_ADMINISTRADOR ){
	                if($idSede != _getSesion('id_sede_trabajo')){
	                    throw new Exception(ANP);
	                }
	            }
	            
	            $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	            $gradoNivel   = explode('_', $idGradoNivel);
	             
	            $aulas        = $this->m_aula->getAllAulasByGradoYear($year, $idSede, $gradoNivel[1], $gradoNivel[0], $tipoCiclo);
	            $data['tablaAulas'] = _createTableAulas($aulas);
	        }
	    } else {
	        if (strlen($txtBusqueda) > 0){
	            $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($txtBusqueda,$tipoCiclo,NULL);
	            $data['tablaAulas'] = _createTableAulas($aulas);
	        } else if (strlen(_post('year')) > 0){
	            $year         = _post('year');
	            $idSede       = _simpleDecryptInt(_post('idsede'));
	            $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	            $gradoNivel   = explode('_', $idGradoNivel);
	             
	            $aulas        = $this->m_aula->getAllAulasByGradoYear($year, $idSede, $gradoNivel[1], $gradoNivel[0], $tipoCiclo);
	            $data['tablaAulas'] = _createTableAulas($aulas);
	        }
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}

	function goToViewAula(){
	    $idAula = _simpleDecryptInt(_post("idaula"));
	    $dataUser = array("idAulaEdit"        => $idAula,
	                      "accionDetalleAula" => 0
	    );
	    $this->session->set_userdata($dataUser);
	}
	
	function goToEditAula(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	        $idAula      = _simpleDecryptInt(_post("idaula"));
	        $detalleAula = $this->m_aula->getDetalleAulas($idAula);
	        if( $detalleAula['year'] < date("Y") && $detalleAula['year'] != null){
	            throw new Exception('No puede editar aulas de a&ntilde;os anteriores');
	        }
	        $dataUser = array("idAulaEdit"        => $idAula,
	                          "accionDetalleAula" => 1	
	        );
	        $this->session->set_userdata($dataUser);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}    
	
	function abrirModalAlumnos(){
	    $idAula   = _simpleDecryptInt(_post('idaula'));
	    $alumnos = $this->m_matricula->getAlumnosByAulaLista($idAula);
	    $data['tablaAlumnos'] = _createTableAlumnos($alumnos);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function goToCreateAula(){
	    $dataUser = array( "idAulaEdit"        => null,
	                       "accionDetalleAula" => 2   
	    );
	    $this->session->set_userdata($dataUser);
	}
		
	function abrirModalConfirmarEliminarAula(){
	    $data['error']    = EXIT_ERROR;
	    try{
	        $idAula = _simpleDecryptInt(_post('idaula'));
	        $data['desc_aula']  = $this->m_utils->getById("aula", "desc_aula", "nid_aula", $idAula);
	        $year               = $this->m_utils->getById("aula", "year", "nid_aula", $idAula);
	        
	        if($year <  date("Y") ){
	            $data['eliminarPermiso'] = 0;
	            throw new Exception('No puede eliminar aulas de a&ntilde;os anteriores');
	        }
	        $cantAlumn          = $this->m_aula->getCapaActualAula($idAula);
	        if($cantAlumn != 0){
	            $data['eliminarPermiso'] = 0;
	            throw new Exception('No puede eliminar un aula con estudiantes matriculados');
	        }
	        $data['eliminarPermiso'] = 1;
	        $data['error']  = EXIT_SUCCESS;
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function eliminarAula(){
	    $data['error']    = EXIT_ERROR;
		$data['msj']      = MSJ_ERROR;
	    try {
	        $idAula      = _simpleDecryptInt(_post('idaula'));
	        if($idAula == null){
	            throw new Exception();
	        }
	        $data        = $this->m_aula->deleteAula($idAula);
	        if($data['error'] == EXIT_SUCCESS){
	            $txtBusqueda   = (strlen(_post('textobusqueda')) == 0) ? NULL : _post('textobusqueda');
	            $tipoCiclo     = (strlen(_post('ciclo')) == 0) ? NULL : _post('ciclo');
	            $idSedeRol     = _getSesion('id_sede_trabajo');
	            $data['error'] = NULL;
	            
	            if($this->_idRol != ID_ROL_ADMINISTRADOR){
	                if (strlen($txtBusqueda) > 0){//Se escribio en la busqueda
	                    $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($txtBusqueda,$tipoCiclo,$idSedeRol);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else if (strlen(_post('year')) > 0){//Se eligio filtro
	                    $year         = _post('year');
	                    $idSede       = _simpleDecryptInt(_post('idsede'));
	                    
	                    if( $this->_idRol != ID_ROL_ADMINISTRADOR ){
	                        if($idSede != _getSesion('id_sede_trabajo')){
	                            throw new Exception(ANP);
	                        }
	                    }
	                    
	                    $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	                    $gradoNivel   = explode('_', $idGradoNivel);
	                     
	                    $aulas        = $this->m_aula->getAllAulasByGradoYear($year, $idSede, $gradoNivel[1], $gradoNivel[0], $tipoCiclo);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else {
	                    $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede(null, null, $idSedeRol,NUMERO_AULAS_CARGA);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                 }
	            } else {
	                if (strlen($txtBusqueda) > 0){
	                    $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede($txtBusqueda,$tipoCiclo,NULL);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else if (strlen(_post('year')) > 0){
	                    $year         = _post('year');
	                    $idSede       = _simpleDecryptInt(_post('idsede'));
	                    $idGradoNivel = _simple_decrypt(_post('idgradonivel'));
	                    $gradoNivel   = explode('_', $idGradoNivel);
	                     
	                    $aulas        = $this->m_aula->getAllAulasByGradoYear($year, $idSede, $gradoNivel[1], $gradoNivel[0], $tipoCiclo);
	                    $data['tablaAulas'] = _createTableAulas($aulas);
	                } else {
        	            $aulas = $this->m_aula->getAllAulasByBusquedaTipoCicloSede(null, null, null, NUMERO_AULAS_CARGA);
        	            $data['tablaAulas'] = _createTableAulas($aulas);
        	        }
	            }
	        }
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
	function enviarFeedBack(){
	    $nombre  = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url     = _post('url');
	    $html = '<p>'.$url.'</p>';
	    $html .= '<p>'.$mensaje.'</p>';
	    $html .= '<p>'.$nombre.'</p>';
	    $arrayInsertCorreo = array('correos_destino'         => CORREO_BASE,
	        'asunto'                  => utf8_encode("¡Sugerencias a Smiledu!"),
	        'body'                    => $html,
	        'estado_correo'           => CORREO_PENDIENTE,
	        'sistema'                 => 'SMILEDU');
	    $dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
	}
}