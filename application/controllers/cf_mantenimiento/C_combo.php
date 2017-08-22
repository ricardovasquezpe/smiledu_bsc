<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_combo extends MX_Controller {
    
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('m_utils');
        $this->load->model('m_main');
        $this->load->model('mf_mantenimiento/m_combo');
        $this->load->library('table');
        $this->load->library('lib_utils');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_COMBO);
    }

	public function index(){
    	$data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos();
    	$combos = $this->m_combo->getAllCombos();
    	$data['tablaCombos'] = $this->createTableCombos($combos);
    	
    	$idRol     = _getSesion('id_rol');
    	$rolSistemas   = $this->m_utils->getSistemasByRol($idRol);
    	$data['apps']  = $this->lib_utils->modalCreateSistemasByrol($rolSistemas);
    	
    	//MENU
    	$menu = $this->load->view('v_menu_v2', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
        $this->load->view('vf_mantenimiento/v_combo', $data);
	}
	
	public function createTableCombos($data){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_combos">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_8 = array('data' => '#');
	    $head_0 = array('data' => 'Descripci&oacute;n');
	    $head_1 = array('data' => 'Acci&oacute;n');
	    $head_2 = array('data' => 'Estado');
	    $val = 0;
	    $this->table->set_heading($head_8, $head_0, $head_1, $head_2);
	    foreach($data as $row){
	        $val++;
	        $idComboEnc = _simple_encrypt($row->grupo);
	        $row_col8  = array('data' => $val);
	        $row_col0  = array('data' => $row->desc_combo);
	        
	        
	        $editCombo = '<button type="button" onclick="abrirModalEditCombo(\''.$idComboEnc.'\');" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top" data-original-title="Delete row"><i class="md md-edit"></i></button>';
	        $row_col1  = array('data' => $editCombo);
	        $foco = 0;
	        if($row->estado == 'checked'){
	            $foco = 1;
	        }
	        $row_col2  = array('data' => '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch_'.$val.'">
                                              <input type="checkbox" id="switch_'.$val.'" class="mdl-switch__input" attr-foco="'.$foco.'" onchange="cambiarEstadoCombo(this, \''.$idComboEnc.'\')" '.$row->estado.'>
                                              <span class="mdl-switch__label"></span>
                                          </label>');
	        
	        $this->table->add_row($row_col8, $row_col0, $row_col1, $row_col2);	
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function insertCombo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_INSERT_ERROR;
	    try {
	        $titulo   = strtoupper(utf8_decode(_post("titulo")));
	        $opciones = _post("opciones");
	         
	        if($this->m_combo->validateTitutloRepetido($titulo) != 0){
	            throw new Exception("El t�tulo ya esta registrado");
	        }
	        
	        $grupo = $this->m_combo->lastGrupoCombo();
	         
	        $arrayInsert = array(
	            "grupo"      => ($grupo + 1),
	            "valor"      => 0,
	            "desc_combo" => $titulo
	        );
	        $data = $this->m_combo->insertCombo($arrayInsert);

	        if($data['error'] == EXIT_SUCCESS && count($opciones) > 0){
	            $cont = 1;
	            foreach($opciones as $opc){
	                $arrayInsertOpc = array(
	                    "grupo"      => ($grupo + 1),
	                    "valor"      => $cont,
	                    "desc_combo" => utf8_decode($opc)
	                );
	                $data = $this->m_combo->insertCombo($arrayInsertOpc);
	                if($data['error'] == EXIT_ERROR){
	                    throw new Exception(ANP);
	                }
	                $cont++;
	            }
	        }
	        
	        if($data['error'] == EXIT_SUCCESS){
	            $combos = $this->m_combo->getAllCombos();
	            $data['tablaCombos'] = $this->createTableCombos($combos);
	        }
	    }catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function detalleCombo(){
	    $grupo    = _simpleDecryptInt(_post("grupo"));
	    $opciones = $this->m_combo->getOpcionesByGrupo($grupo);
	    $arrayOpc = array();
	    $data     = null;
	    
	    foreach ($opciones as $opc) {
	        $arrayOpcUno = array();
	        if($opc->valor == 0){
	            $data['tituloCombo'] = $opc->desc_combo;
	        }else{
	            array_push($arrayOpcUno, $opc->desc_combo);
	            array_push($arrayOpcUno, _simple_encrypt($opc->valor));
	            array_push($arrayOpc, $arrayOpcUno);
	        }
	    }
	    
	    $data['opc'] = json_encode($arrayOpc);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deleteOpcionCombo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_INSERT_ERROR;
	    try{
	        $grupo = _simple_decrypt(_post("grupo"));
	        $valor = _simple_decrypt(_post("valor"));
	        if($grupo == null || $valor == null){
	            throw new Exception(ANP);
	        }
	        
	        $data = $this->m_combo->deleteOpcion($grupo, $valor);
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function agregarOpcionCombo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_INSERT_ERROR;
	    try{
	        $grupo = _simple_decrypt(_post("grupo"));
	        $desc  = _post("desc");
	        
	        if($grupo == null || strlen(trim($desc)) == 0){
	            throw new Exception(ANP);
	        }
	        $valor = ($this->m_combo->getLastOpcionByGrupo($grupo) + 1);
	        $arrayInsert = array(
	            "grupo"      => $grupo,
	            "desc_combo" => utf8_decode($desc),
	            "valor"      => $valor
	        );
	        $data = $this->m_combo->insertOpcion($arrayInsert);
	        $data['valor'] = _simple_encrypt($valor);
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function cambiarEstado(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_INSERT_ERROR;
	    try{
	        $foco  = _post("foco");
	        $grupo = _simpleDecryptInt(_post("grupo"));
	        
	        if($grupo == null || $foco == null){
	            throw new Exception(ANP);
	        }
	        
	        $arrayUpdate = array();
	        if($foco == 1){
	            $arrayUpdate = array(
	                "flg_estado" => 0 
	            );
	        }else{
	            $arrayUpdate = array(
	                "flg_estado" => 1
	            );
	        }
	        
	        $data = $this->m_combo->updateOpcionByGrupoValor($arrayUpdate, $grupo, 0);
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data)); 
	}
	
	function cambiarDescCombo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_INSERT_ERROR;
	    try{
	        $grupo    = _simpleDecryptInt(_post("grupo"));
	        $opciones = json_decode(_post('opciones'), TRUE);
	        $titulo   = _post("titulo");
	        
	        $arrayUpdate = array(
	            "desc_combo" => strtoupper(utf8_decode($titulo))
	        );
	         
	        $data = $this->m_combo->updateOpcionByGrupoValor($arrayUpdate, $grupo, 0);
	        foreach($opciones['opcion'] as $key => $opcion) {
	            $valor = _simple_decrypt($opcion['valor']);
	            $desc  = $opcion['desc'];
	             
	            $arrayUpdate = array(
	                "desc_combo" => utf8_decode($desc)
	            );
	            $data = $this->m_combo->updateOpcionByGrupoValor($arrayUpdate, $grupo, $valor);
	        }
	        
	        if($data['error'] == EXIT_SUCCESS){
	            $combos = $this->m_combo->getAllCombos();
	            $data['tablaCombos'] = $this->createTableCombos($combos);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data)); 
	}
}