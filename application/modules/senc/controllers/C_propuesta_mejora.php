<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_propuesta_mejora extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('mf_categoria/m_categoria');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, ID_PERMISO_PROP_MEJORA, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Propuesta Mejora';
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['apps']               = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $data['tipo_encuesta'] = __buildComboTipoEncuesta();
        $data['tbPropuestas']  = $this->buildTableComentariosHTML(array());
        ///////////
        $this->session->set_userdata(array('tab_active_config' => null));
        $this->load->view('v_propuesta_mejora',$data);
	}
	
	function buildTableComentariosHTML($comentarios){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-custom-search="$.noop"
			                                   data-pagination="true" 
			                                   data-show-columns="true" data-search="true" id="tb_comentarios">',
	                  'heading_row_start'     => '<tr class="filters">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    
	    $head_0 = array('data' => '#'          , 'class' => 'text-left');
	    $head_1 = array('data' => 'Comentario' , 'class' => 'text-left');
	    $head_3 = array('data' => 'Accion'     , 'class' => 'text-center');
	    $this->table->set_heading($head_0,$head_1,$head_3);
	    $val = 0;
	    foreach($comentarios as $row){
	        if(utf8_decode($row['comentario']) != null){
	            $val++;
	            $row_col0 = array('data' => $val);
	            $row_col1 = array('data' => utf8_decode($row['comentario']));
	            $idDispCrypt = _encodeCI($row['_id']);
	            $idEncCrypt  = _encodeCI($row['id_encuesta']);
	            $row_col3 = array('data' => '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="editPropMejora(\''.$idDispCrypt.'\',\''.$idEncCrypt.'\')">
	                                         <i class="mdi mdi-edit"></i>
	                                     </a>', 'class' => 'text-center');
	            $this->table->add_row($row_col0 ,$row_col1,$row_col3);
	        }
	    }
	    return $this->table->generate();
	}
	
	function getEncuestaByTipoEncuesta(){
	    $idTipo = _decodeCI(_post('tipo_encuesta'));
	    $opt = null;
	    if($idTipo != null){
	        if(_validate_metodo_rol(_getSesion(SENC_ROL_SESS))){
	            $opt = __buildComboEncuestaByTipo($idTipo);
	        }else{
	            $opt = __buildComboEncuestaByTipoPersona($idTipo, _getSesion("nid_persona"));
	        }
	    }
	    $data['tbComentarios'] = $this->buildTableComentariosHTML(array());
	    $data['optEnc'] = $opt;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComentariosByEncuesta(){
	    $idEncuesta = _decodeCI(_post('encuesta'));
	    $idTipoEncu = _decodeCI(_post('tipo_encuesta'));
	    $comentarios = $this->m_pregunta->getAllComentarios($idEncuesta);
	    $data['tbComentarios'] = $this->buildTableComentariosHTML($comentarios);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getPropMejoraByComentario(){
	    $data['msj'] = null;
	    $idEncuesta    = _decodeCI(_post('encuesta'));
	    $idDispositivo = _decodeCI(_post('dispositivo'));
	    $propuestas = $this->m_encuesta->getCantPropM($idEncuesta);
	    $propSelect = $this->m_pregunta->getPropuestasByDispositivoEncuesta($idEncuesta,$idDispositivo);
	    $result     = $this->getPropuestasMejoraHTML($propuestas, $propSelect);
	    $data['optProp']   = $result[0]; 
	    $data['arrayProp'] = $result[1];
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	
	function getPropuestasMejoraHTML($propuestaMrray, $cantNewPropM){
	    $propuestaMHTML = null;
	    $arrayEncrypt   = array();
	    foreach($propuestaMrray as $rowPropM){
	        $flgAgrego      = 0;
	        $idEncrypt = _simple_encrypt($rowPropM->id_propuesta);
	        foreach($cantNewPropM as $rowMarcar){
	            if($rowMarcar == $rowPropM->id_propuesta){
	                $propuestaMHTML .= '<option value = "'.$idEncrypt.'" selected>'.$rowPropM->desc_propuesta.'</option>';
	                $flgAgrego = 1;
	                array_push($arrayEncrypt, $idEncrypt);
	            }
	        }
	        if($flgAgrego == 0){
	            $propuestaMHTML .= '<option value = "'.$idEncrypt.'">'.$rowPropM->desc_propuesta.'</option>';
	        }
	    }
	    return array($propuestaMHTML, json_encode($arrayEncrypt));
	}
	
	
	function getPropuestasMejoraHTML1($propuestaMrray, $cantNewPropM){
	    $propuestaMHTML = null;
	    $contPropM = 0;
	    $arrayEncrypt = array();
	    foreach($propuestaMrray as $rowPropM){
	        $contPropM++;
	        $idEncrypt = _simple_encrypt($rowPropM->id_propuesta);
	        foreach($cantNewPropM as $rowMarcar){
	            if(($rowMarcar) == ($rowPropM->id_propuesta)){
	                $propuestaMHTML .= '<option value = "'.$rowMarcar.'" selected>'.$rowPropM->desc_propuesta.'</option>';
	                $idEncrypt= null;
	                $rowPropM->desc_propuesta = null;
	            }
	        }
	        array_push($arrayEncrypt, $idEncrypt);
	        if($idEncrypt != null || $rowPropM->desc_propuesta != null){
	            $propuestaMHTML .= '<option value = "'.$idEncrypt.'">'.$rowPropM->desc_propuesta.'</option>';
	        }
	    }
	    return array($propuestaMHTML, json_encode($arrayEncrypt));
	}
	
	function logout() {
	    $this->session->set_userdata(array("logout" => true));
	    unset($_COOKIE['smiledu']);
	    $cookie_name2 = "smiledu";
	    $cookie_value2 = "";
	    setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	    Redirect(RUTA_SMILEDU, true);
	}
	
	function registraNuevaPropM(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $flg_encuestado = $this->session->userdata('realizo_encuesta');
	        $idEncuesta     = _decodeCI(_post('idEncuesta'));
	        $arrayProp      = _post('objProp');
	        $arrayPropAux   = array();
	        if($idEncuesta == null){
	            throw new Exception();
	        }
	        if($flg_encuestado == REALIZO_ENCUESTA){
	            throw new Exception('Ya realizaste esta encuesta, gracias');
	        }
	        $nuevaPropM = utf8_decode(trim($this->input->post('newPropM')));
	        $newPropM = is_array($this->input->post('selePropM')) ? $this->input->post('selePropM') : array();
	        if($nuevaPropM == null){
	            throw new Exception('Escribe una nueva propuesta');
	        }
	        $porciones = explode(" ", $nuevaPropM);
	        if(count($porciones) > CANT_MAX_PALABRAS){
	            throw new Exception('Debe ingresar un máximo de 5 palabras');
	        }
	        $IdPropM = $this->m_encuesta->getIdPropMbyDesc(strtolower($nuevaPropM),$idEncuesta);
	        foreach($arrayProp as $id){
	             array_push($arrayPropAux, _simpleDecryptInt($id));
	        }
	        if($IdPropM != null) {
	            array_push($arrayProp, $IdPropM);
	            $idEncryptN = _simple_encrypt($IdPropM);
	            $arraNewPropM = $this->m_encuesta->getCantPropM($idEncuesta);
	            $propuestaMHTML = null;
	            if(!in_array($IdPropM, $arrayPropAux)){
	                array_push($arrayPropAux, $IdPropM);
	            }
	            $result = $this->getPropuestasMejoraHTML($arraNewPropM,$arrayPropAux);
	            $data['propuestaMHTML'] = $result[0];
	            $data['arrayProp']      = $result[1];
	            $data['error'] = EXIT_SUCCESS;
	        }else{
	            $arrayInsert = array("desc_propuesta" => strtoupper($nuevaPropM),
	                "flg_estado"     => ESTADO_ACTIVO ,
	                "count"          => 0,
	                "_id_encuesta"   => $idEncuesta
	            );
	            $data = $this->m_encuesta->insertDescProp($arrayInsert);//inserto en PG
	            if($data['error'] == EXIT_SUCCESS){
	                $idEncrypt = _simple_encrypt($data['id_propInsert']);
	                $arraNewPropM = $this->m_encuesta->getCantPropM($idEncuesta);
	                array_push($arrayPropAux, $data['id_propInsert']);
	                $result = $this->getPropuestasMejoraHTML($arraNewPropM,$arrayPropAux);
	                $data['propuestaMHTML'] = $result[0];
	                $data['arrayProp']      = $result[1];
	            }
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function linkComentarioPropuesta(){
	    $data['error'] = ERROR_MONGO;
	    $data['msj']   = null;
	    $arrayProp      = _post('propuestas');
	    $idDispositivo  = _decodeCI(_post('dispositivo'));
	    $idEncuesta     = _decodeCI(_post('encuesta'));
	    try{
	        if(!is_array($arrayProp)){
	            throw new Exception('Seleccione una propuesta');
	        }
	        if($idDispositivo == null || $idEncuesta == null){
	            throw new Exception(ANP);
	        }
	        $propuestas = $this->getArrayStringFromArray($arrayProp);
	        $data['error'] = $this->m_pregunta->updateArrayPropByComentario($idEncuesta,$idDispositivo,$propuestas);
	        if($data['error'] == SUCCESS_MONGO){
	            $data['msj'] = MSJ_UPT;
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getArrayStringFromArray($data){
	    $arrayIds = null;
	    foreach ($data as $var){
	        $id = _simpleDecryptInt($var);
	        if($id != null){
	            $arrayIds .= $id.',';
	        }
	    }
	    $arrayIds = substr($arrayIds,0,(strlen($arrayIds)-1));
	    return $arrayIds;
	}
}