<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_utils extends MX_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    /*function existeByCampoCtrl(){
	    $tabla = _post('p_tbl');
	    $campo = _post('p_campo');
	    $valor = _post('p_valor');
	    $cant = null;
	    if($tabla != null && $campo != null && $valor != null){
            $res  = $this->m_utils->existeByCampoModel($campo,$valor,$tabla);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
	    }else{
	        $cant = '1';//Si hay un error que simule que si existe
	    }	    
	    echo $cant;
	}*/
	
	function checkClaveNow() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $clave = _post('clave');
	        if(strlen($clave) == 0) {
	            throw new Exception('Ingrese su clave actual');
	        }
	        $result = $this->m_utils->checkClaveActual($clave, _getSesion('nid_persona'));
	        $data['resultado'] = ( ($result == false) ? 1 : 0 );
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function existeByCampoCtrlById() {
	    $tabla       = _post('p_tbl');
	    $campo       = _post('p_campo');
	    $valor       = _post('p_valor');
	    $nid_persona = _getSesion('nid_persona');
	    $cant = null;
	    if($tabla != null && $campo != null && $valor != null && $nid_persona != null) {
	        $res  = $this->m_utils->existeByCampoModelById($campo, $valor, $tabla, $nid_persona);
	        $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
	    } else {
	        $cant = '1';//Si hay un error que simule que si existe
	    }
	    echo $cant;
	}
	
	function getComboGradosNivelBySede() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede = _decodeCI(_post('idSede'));
	        if($idSede == null) {
	            throw new Exception(null);
	        }
	        $data['optGradoNivel'] = __buildComboGradoNivelBySede($idSede);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComboAulasByGradoNivel() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede = _decodeCI(_post('idSede'));
	        $idgradoNivel = _decodeCI(_post('idgradoNivel'));
	        if($idSede == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optAulas'] = null;
	            throw new Exception(null);
	        }
	        if($idgradoNivel == null) {
	            $data['error'] = EXIT_WARM;
	            $data['optAulas'] = null;
	            throw new Exception(null);
	        }
	        $gradoNivel = explode('_', $idgradoNivel);
	        //Opcional se puede validar si el grado y nivel existen antes de hacer un query
	        $data['optAulas'] = __buildComboAulas($gradoNivel[0], $idSede);
	        //$this->lib_utils->buildComboAulas2($idSede, $gradoNivel[1], $gradoNivel[0]);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
}