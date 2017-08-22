<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_proceso_matricula extends CI_Controller {

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
        $this->load->model('mf_contactos/m_detalle_contactos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, null, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
	    
	    $data['comboTipoDocumento'] = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC);
	    $data['comboSexo']          = __buildComboByGrupo(COMBO_SEXO);
	    $data['comboOperadores']    = __buildComboByGrupo(COMBO_OPERADOR_TELEF);
	    $data['comboCanales']       = __buildComboByGrupo(COMBO_MEDIO_COLEGIO);
	    $data['comboColegios']      = '<option value="0">'.strtoupper('En casa').'</option>'.__buildComboColegios();
	    $data['comboGradoNivel']    = __buildComboGradoNivel();
	    $data['comboDepartamento']  = __buildComboUbigeoByTipo(null, null, 1);
	    
	    $data['comboParentesco']    = __buildComboByGrupo(COMBO_PARENTEZCO);
	    
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['return'] = '';
	    $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
            	             <a href="#tab-1" class="mdl-layout__tab is-active">Estudiante</a>
            	             <a href="#tab-2" class="mdl-layout__tab">Familiar</a>
            	             </div>';
	    $data['titleHeader'] = "Proceso Matricula";
	    $data['ruta_logo'] = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo'] = NAME_MODULO_ADMISION;
	    
	    $detalleContacto = $this->m_contactos->getDetalleContacto(_getSesion('idcontacto'));
	    $data['apePaterno']          = $detalleContacto['ape_paterno'];
	    $data['apeMaterno']          = $detalleContacto['ape_materno'];
	    $data['nombres']             = $detalleContacto['nombres'];
	    $data['sexo']                = ($detalleContacto['sexo']!=null) ? _simple_encrypt($detalleContacto['sexo']) : null;
	    $data['gradoNivel']          = (strlen($detalleContacto['gradonivel']) != 0) ? _simple_encrypt($detalleContacto['gradonivel']) : null;
	    $data['colegioProcedencia']  = ($detalleContacto['colegio_procedencia']!=null) ? _simple_encrypt($detalleContacto['colegio_procedencia']) : null;
	    $data['nroDoc']              = $detalleContacto['nro_documento'];
	    $data['observacion']         = $detalleContacto['obser_solicitud'];
	    
// 	    $parientes = $this->m_detalle_contactos->getFamiliaresByPostulante(_getSesion('idcontacto'));
// 	    $data['parientes']          = $this->createButtonFamiliares($parientes);
	    
	    /*
	    $idContacto = _simpleDecryptInt(_post("contacto"));
	    $detalle = $this->m_detalle_contactos->getDetalleContacto($idContacto);
	    $data['nombres']      = $detalle['nombres'];
	    $data['apPaterno']    = $detalle['ape_paterno'];
	    $data['apMaterno']    = $detalle['ape_materno'];
	    $data['correo']       = $detalle['correo'];
	    $data['celular']      = $detalle['telefono_celular'];
	    $data['parentesco']   = ($detalle['parentesco']!=null) ? _simple_encrypt($detalle['parentesco']) : null;
	    $data['sexo']         = ($detalle['sexo']!=null) ? _simple_encrypt($detalle['sexo']) : null;
	    $data['fijo']         = $detalle['telefono_fijo'];
	    $data['refer_dom']    = $detalle['referencia_domicilio'];
	    $data['tipoDoc']      = ($detalle['tipo_documento']!=null) ? ($detalle['tipo_documento']) : null;
	    $data['nroDoc']       = $detalle['nro_documento'];
	    $data['operador']     = ($detalle['operador_telefonico']!=null) ? _simple_encrypt($detalle['operador_telefonico']) : null;
	    $data['canal']        = ($detalle['canal_comunicacion']!=null) ? _simple_encrypt($detalle['canal_comunicacion']) : null;
	    $data['departamento'] = (strlen(substr($detalle['ubigeo'],0,2)) != 0) ? _simple_encrypt(substr($detalle['ubigeo'],0,2)) : null;
	    $data['provincia']    = (strlen(substr($detalle['ubigeo'],2,2)) != 0) ? _simple_encrypt(substr($detalle['ubigeo'],2,2)) : null;
	    $data['distrito']     = (strlen(substr($detalle['ubigeo'],4,2)) != 0) ? _simple_encrypt(substr($detalle['ubigeo'],4,2)) : null;
	    */
	    
	    $contactos = null;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    
	    $this->load->view('v_proceso_matricula',$data);
	}
    
    function createButtonFamiliares($parientes){
        $opcion = "";
        $i = 0;
        foreach ($parientes as $par) {
            $active = $i == 0 ? 'active' : '';
            $opcion.= ' <span class="mdl-chip mdl-chip--contact mdl-chip--deletable '.$active.' chip-parientes" id="chip'.$par->id_familiar.'" onclick="verDatosFamiliar(\''._simple_encrypt($par->id_familiar).'\',1)">
                            <img class="mdl-chip__contact" src="'.((file_exists(FOTO_PROFILE_PATH . 'familiares/' . $par->foto_persona)) ? RUTA_IMG_PROFILE . 'familiares/' . $par->foto_persona : RUTA_IMG_PROFILE . "nouser.svg").'"></img>
                            <span class="mdl-chip__text">'.$par->nombres.'</span>
                            <div class="mdl-chip__action"><i class="mdi mdi-state"></i></div>
                        </span>';
            $i++;
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