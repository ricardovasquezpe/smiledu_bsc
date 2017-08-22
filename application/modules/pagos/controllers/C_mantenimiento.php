<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_mantenimiento extends CI_Controller{

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_mantenimiento');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, null, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']                = 'Mantenimiento';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']               = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $data['tableConceptos']   = $this->buildTablaConceptosHTML();
        $data['optTipo']          = __buildComboByGrupo(COMBO_TIPO_CONCEPTO);
        $data['tipoGeneral']      = _simple_encrypt(TIPO_GENERAL);
        $data['tipoEspecifico']   = _simple_encrypt(TIPO_ESPECIFICO);
        ///////////
        $this->session->set_userdata(array('tab_active_config' => null));
        $this->load->view('v_mantenimiento', $data);
    }

    function logout() {
       $this->session->set_userdata(array("logout" => true));
       unset($_COOKIE[__getCookieName()]);
       $cookie_name2 = __getCookieName();
       $cookie_value2 = "";
       setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
       Redirect(RUTA_SMILEDU, true);
    }

    function cambioRol() {
        $idRol     = _simple_decrypt(_post('id_rol'));
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
        $dataUser  = array("id_rol" => $idRol,
				            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $result['url'] = base_url() . "c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }

    function getRolesByUsuario() {
        $idPersona = $this->_idUserSess;
        $idRol     = _getSesion('id_rol');
        $roles     = $this->m_usuario->getRolesByUsuario($idPersona, $idRol);
        $return    = null;
        foreach ($roles as $var) {
            $check = null;
            $class = null;
            if ($var->check == 1) {
                $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
                $class = 'active';
            }
            $idRol   = _simple_encrypt($var->nid_rol);
            $return .= "<li class='" . $class . "'>";
            $return .= '<a href="javascript:void(0)" onclick="cambioRol(\'' . $idRol . '\')"><span class="title">' . $var->desc_rol . $check . '</span></a>';
            $return .= "</li>";
        }
        $dataUser = array("roles_menu" => $return);
        $this->session->set_userdata($dataUser);
    }

    function setIdSistemaInSession() {
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function enviarFeedBack() {
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        __enviarFeedBack($mensaje, $url, $nombre);
    }

    function mostrarRolesSistema() {
        $idSistema = _decodeCI(_post('sistema'));
        $roles     = $this->m_usuario->getRolesOnlySistem($this->_idUserSess, $idSistema);
        $result    = '<ul>';
        foreach ($roles as $rol) {
            $idRol   = _encodeCI($rol->nid_rol);
            $result .= '<li style="cursor:pointer" onclick="goToSistema(\'' . _post('sistema') . '\', \'' . $idRol . '\')">' . $rol->desc_rol . '</li>';
        }
        $result        .= '</ul>';
        $data['roles']  = $result;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function comboPadres($valueSelect, $id) {
    	if($id == null){
    		$id = '0';
    	}
    	$combo  = $this->m_mantenimiento->getComboPadres($id);
    	$opcion = '';
    	foreach ($combo as $row){
    		$selected = null;
    		if($valueSelect == $row->id_concepto){
    			$selected = 'selected';
    		}
    		$opcion  .= '<option '.$selected.' value="'._simple_encrypt($row->id_concepto).'">'.$row->desc_concepto.'</option>';
    	}
    	return $opcion;
    }
    
    function buildTablaConceptosHTML() {
        $conceptos = $this->m_mantenimiento->getAllConceptos();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_concepto">',
            		  'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#'              , 'class' => 'text-center');
        $head_2 = array('data' => 'Tipo'           , 'style' => 'text-align:left');
        $head_3 = array('data' => 'Descripcci&oacute;n');
        $head_4 = array('data' => 'Fecha'          , 'class' => 'text-left');
        $head_5 = array('data' => 'Monto ref.(S/)' , 'class' => 'text-right');
        $head_6 = array('data' => 'Estado'         , 'class' => 'text-center');
        $head_7 = array('data' => 'Acciones'       , 'class' => 'text-center');
        $val    = 0;
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
        foreach ($conceptos as $row) {
            $count   = $this->m_mantenimiento->getCountConceptosUso($row->id_concepto);
            $hijos   = $this->m_mantenimiento->getCountConceptoHijo($row->id_concepto);
            $encrypt = _encodeCI($row->id_concepto);
            $val ++;
            $row_cell_1 = array('data' => $val);
            $row_cell_2 = array('data' => _ucwords($row->tipo_movimiento));
            $row_cell_3 = array('data' => $row->desc_concepto);
            $row_cell_4 = array('data' => $row->fecha_registro);
            $row_cell_5 = array('data' => $row->monto_referencia );
            $row_cell_6 = array('data' => ($row->estado == 'ACTIVO') ? '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                                                            <input type="checkbox" class="mdl-switch__input" checked onchange=estadoCambiar(\''.$encrypt.'\')>
                                                                            <span class="mdl-switch__label"></span>
                                                                        </label>' : '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                                                                 		 <input type="checkbox" class="mdl-switch__input" onchange=estadoCambiar(\''.$encrypt.'\')>
                                                                                 	     <span class="mdl-switch__label"></span>
                                                                                   	 </label>');
            $idRol     = _getSesion(PAGOS_ROL_SESS);
            if($idRol == ID_ROL_CONTABILIDAD){
            	$acciones = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" title="Ya est&aacute; en uso">
                             <i class="mdi mdi-lock"></i>
                             </button>
            			     <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModaleditarTipo(\''.$encrypt.'\')">
                                 <i class="mdi mdi-mode_edit"></i>
                             </button>';
            }else{
            	$acciones = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" title="Ya est&aacute; en uso">
                             <i class="mdi mdi-lock"></i>
                         </button>';
            }
            
            
            if($count == 0 && $hijos == 0){
                $acciones = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModaleditarConceptos(\''.$encrypt.'\')">
                                 <i class="mdi mdi-mode_edit"></i>
                             </button>
                             <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModaleliminarConceptos(\''.$encrypt.'\')">
                                 <i class="mdi mdi-delete"></i>
                             </button>';
            }
            $row_cell_7 = array('data' => $acciones);
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6, $row_cell_7);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }

    function deleteConcept() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $id            = _decodeCI($this->input->post('id_concepto'));
        $conceptoUsado = $this->m_mantenimiento->buscarConcepto($id);
        try {
        	if($conceptoUsado != 0){
        		$data['msj']   = "Concepto en uso no se puede eliminar";
        	}else{
            	$data = $this->m_mantenimiento->eliminarConcepto($id);
           		$data['tableConceptos'] = $this->buildTablaConceptosHTML();	
        	}
            
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function mostrarDetalle() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $id            = _decodeCI($this->input->post('id'));
        try {
            $data                  = $this->m_mantenimiento->mostrarConcepto($id);
        	if($data['flg_padre'] == 1){
        		$comboTipo = __buildComboByGrupo(COMBO_TIPO_CONCEPTO, TIPO_ESPECIFICO);
        		$data['optTipo'] = '<div class="col-sm-12 p-0" id="comboTipoEdit">
        				               <select id="selectTipoEdit" name="selectTipoEdit" class="form-group pickerButn" onchange="cambiarTipo();" data-live-search="true">
        				                   <option value="">Seleccionar Tipo</option>
        				                   '.$comboTipo.' 
                                       </select>
        				           </div> ';
        		$comboPadres = $this->comboPadres($data['id_padre'], $id);
            	$data['optPadre'] = '<div class="col-sm-12 p-0" id="comboPadreEdit">
    			     	                 <select id="selectPadreEdit" name="selectPadreEdit" class="form-group pickerButn" data-live-search="true">
    					                     <option value="">Seleccionar Concepto</option>
    					                     '.$comboPadres.'
                                         </select>
    					             </div> ';
            	$comboMovimiento = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO, INGRESO);
            	$data['optMov'] = '<div class="col-sm-12 p-0" id="comboMovimientoEdit">
    					               <select id="selectMovEdit" name="selectMovEdit" class="form-group pickerButn" data-live-search="true">
    					                   <option value="">Tipo de Movimiento</option>
    					                   '.$comboMovimiento.'
                                       </select>
    					           </div>';
            	$data['descripcion'] ='<div class="col-sm-12 p-0" id="inputDescripccionEdit">
    					                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="descEdit">
                                               <label class="mdl-textfield__label" for="descEdit">Descripci&oacute;n</label>
                                           </div>
    					               </div>';
            	$data['monto'] ='<div class="col-sm-12 p-0"id="inputMontoEdit">
    					             <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                         <input class="mdl-textfield__input" type="text" id="montoEdit">
                                         <label class="mdl-textfield__label" for="montoEdit">Monto de referencia(S/)</label>
                                     </div>
    					         </div>';
        	}else{
        	    $comboTipo = __buildComboByGrupo(COMBO_TIPO_CONCEPTO, TIPO_GENERAL);
        	    $data['optTipo'] = '<div class="col-sm-12 p-0" id="comboTipoEdit">
    					                <select id="selectTipoEdit" name="selectTipoEdit" class="form-group pickerButn" onchange="cambiarTipo();" data-live-search="true">
    					                    <option value="">Seleccionar Tipo</option>
    					                    '.$comboTipo.'
                                        </select>
    					            </div> ';
        		$data['optPadre'] = '<div class="col-sm-12 p-0" id="comboPadreEdit">
        		                     </div> ';
        		$comboMovimiento = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO, EGRESO);
        		$data['optMov'] = '<div class="col-sm-12 p-0" id="comboMovimientoEdit">
    					               <select id="selectMovEdit" name="selectMovEdit" class="form-group pickerButn" data-live-search="true">
    					                   <option value="">Tipo de Movimiento</option>
    					                   '.$comboMovimiento.'
                                       </select>
    					           </div>';
        		$data['descripcion'] ='<div class="col-sm-12 p-0" id="inputDescripccionEdit">
    					                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="descEdit">
                                               <label class="mdl-textfield__label" for="descEdit">Descripci&oacute;n</label>
                                           </div>
    					               </div>';
        		$data['monto'] ='<div class="col-sm-12 p-0"id="inputMontoEdit">
    					             <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                         <input class="mdl-textfield__input" type="text" id="montoEdit">
                                         <label class="mdl-textfield__label" for="montoEdit">Monto de referencia(S/)</label>
                                     </div>
    					         </div>';
        	}
        } catch (Exception $e) {
        	$data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
    function cambiarEstado() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $id            = _decodeCI($this->input->post('id_concepto'));
        $estado        = $this->m_mantenimiento->estadoConcepto($id);
        try {
	       	if($estado == ESTADO_ACTIVO){
	       		$estado = ESTADO_INACTIVO;
	       	}elseif($estado == FLG_ESTADO_INACTIVO){
	       		$estado = ESTADO_ACTIVO;
	       	}
        	$arrayUpdate            = array('estado'=> $estado);
        	$data                   = $this->m_mantenimiento->modificarConcepto($id, $arrayUpdate);
        	$data['tableConceptos'] = $this->buildTablaConceptosHTML();
        } catch (Exception $e) {
        	$data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
	function updateConcept() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $desc          = trim($this->input->post('desc'));
        $monto         = trim($this->input->post('monto'));
        $selectMov     = _simpleDecryptInt($this->input->post('selectMov'));
        $id            = _decodeCI($this->input->post('idConcepto'));
        $selectTipo    = _simpleDecryptInt($this->input->post('selectTipo'));
        $selectPadre   = _simpleDecryptInt($this->input->post('selectPadre'));
        try {
            if(empty($desc)) {
                throw new Exception('Ingrese una Descripcion');
            }
            if(strlen($desc) >= 100) {
                throw new Exception('Capacidad Maxima 100 carcteres');
            }
            $original=$this->m_mantenimiento->descConcepto($id);
            if(strtoupper($desc) != strtoupper($original)){
            	$descripccion=$this->m_mantenimiento->allConcepto($desc);
            	if($descripccion == 1) {
                	throw new Exception('Concepto ya registrado');
           		}
            }
            if(empty($monto)) {
                throw new Exception('Ingrese una monto');
            }
            if($monto <= 0) {
            	throw new Exception('Debe ser un n&uacute;mero positivo');
            }
            if(filter_var($monto, FILTER_VALIDATE_FLOAT) === false) {
                throw new Exception('Solo n&uacute;meros en monto');
            }
            if($monto >= 1000000) {
            	throw new Exception('El monto debe ser menor que 1000000');
            }
            if(empty($selectMov)) {
                throw new Exception('Seleccione Tipo de Movimiento');
            }
            if(empty($selectTipo)) {
                throw new Exception('Seleccione Tipo de Concepto');
            }
            if($selectTipo == '2') {
                if(empty($selectPadre)){
                    throw new Exception('Seleccione Concepto un Concepto');
                }
            }else {
                $selectPadre = null;
            }
            if($selectMov == INGRESO) {
                $selectMov = MOV_INGRESO;
            }else if($selectMov == EGRESO) {
                $selectMov = MOV_EGRESO;
            }
            $estadoMovimiento=$this->m_mantenimiento->conceptoMovimiento($id);
            if($estadoMovimiento == 0) {
            	$arrayUpdate = array('desc_concepto'    => $desc,
	                                 'monto_referencia' => $monto,
	                                 'fecha_registro'   => date('Y-m-d'),
	                                 'tipo_movimiento'  => $selectMov,
            	                     'flg_padre'        => ($selectTipo-1),
            	                     'id_padre'         => $selectPadre);
            $data                   = $this->m_mantenimiento->actualizarConcepto($id, $arrayUpdate);
            $data['tableConceptos'] = $this->buildTablaConceptosHTML();
            }else {
                throw new Exception('No se puede editar el concepto');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarConcept() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $desc          = trim($this->input->post('desc'));
        $monto         = $this->input->post('monto');
        $selectMov     = _simpleDecryptInt($this->input->post('selectMov'));
        $selectTipo    = _simpleDecryptInt($this->input->post('selectTipo'));
        $selectPadre   = _simpleDecryptInt($this->input->post('selectPadre'));
        try {
        	if(empty($selectTipo)) {
        		throw new Exception('Seleccione Tipo de Concepto');
        	}
            $selectTipo = $selectTipo-1;
            if($selectTipo == 1) {
            	if(empty($selectPadre)) {
            		throw new Exception('Seleccione un Concepto');
            	}
            }else{
                $selectPadre = null;
            }
            if(empty($desc)) {
                throw new Exception('Ingrese una Descripci&oacute;n');
            }
            if(strlen($desc) >= 100) {
                throw new Exception('Capacidad Maxima 100 carcteres');
            }
            $descripccion=$this->m_mantenimiento->allConcepto($desc);
            if($descripccion == 1) {
                throw new Exception('Concepto ya registrado');
            }
            if(empty($monto)) {
                throw new Exception('Ingrese una monto');
            }
            if($monto <= 0) {
            	throw new Exception('Debe ser un n&uacaute;mero positivo');
            }
            if($monto >= 1000000) {
            	throw new Exception('El monto debe ser menor que 1000000');
            }
            if(filter_var($monto, FILTER_VALIDATE_FLOAT) === false) {
                throw new Exception('Solo n&uacute;meros en monto');
            }
            if(empty($selectMov)) {
                throw new Exception('Seleccione Tipo de Movimiento');
            }
            
            if($selectMov == 1) {
                $selectMov = MOV_INGRESO;
            }else {
                $selectMov = MOV_EGRESO;
            }
            $arrayInsert = array('desc_concepto'    => utf8_decode($desc),
                                 'monto_referencia' => $monto,
                                 'fecha_registro'   => date('Y-m-d'),
                                 'tipo_movimiento'  => $selectMov,
                                 'estado'           => 'ACTIVO',
            		             'flg_padre'        => $selectTipo,
            			         'id_padre'         => $selectPadre);
            $data = $this->m_mantenimiento->registrarConcepto($arrayInsert);
            $data['tableConceptos'] = $this->buildTablaConceptosHTML();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeFormulario() {
        $selectTipo    = _simpleDecryptInt($this->input->post('selectTipo'));
        if(empty($selectTipo)) {
            throw new Exception('Seleccione Tipo de Concepto');
        }
        if($selectTipo == 2) {
            $comboPadres = $this->comboPadres(null,null);
            $data['optPadre'] = '<div class="col-sm-12 p-0" id="comboPadre"> 
					                 <select id="selectPadre" name="selectPadre" class="form-group pickerButn" data-live-search="true">
					                     <option value="">Seleccionar Concepto</option>
					                     '.$comboPadres.'
                                     </select>
					             </div> ';
            $comboMovimiento = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO);
            $data['optMov'] = '<div class="col-sm-12 p-0" id="comboMovimiento">
					               <select id="selectMov" name="selectMov" class="form-group pickerButn" data-live-search="true">
					                   <option value="">Tipo de Movimiento</option>
					                   '.$comboMovimiento.'
                                   </select>
					           </div>';
            $data['descripcion'] ='<div class="col-sm-12 p-0" id="inputDescripccion">
					                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                           <input class="mdl-textfield__input" type="text" id="desc">
                                           <label class="mdl-textfield__label" for="desc">Descripci&oacute;n</label>
                                       </div>
					               </div>';
            $data['monto'] ='<div class="col-sm-12 p-0"id="inputMonto">
					             <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                     <input class="mdl-textfield__input" type="text" id="monto">
                                     <label class="mdl-textfield__label" for="monto">Monto de referencia(S/)</label>
                                 </div>
					         </div>';
        }else {
            $data['optPadre'] = '';
            $comboMovimiento = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO);
            $data['optMov'] = '<div class="col-sm-12 p-0" id="comboMovimiento">
					               <select id="selectMov" name="selectMov" class="form-group pickerButn" data-live-search="true">
					                   <option value="">Tipo de Movimiento</option>
					                   '.$comboMovimiento.'
                                   </select>
					           </div>';
            $data['descripcion'] ='<div class="col-sm-12 p-0" id="inputDescripccion">
					                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                           <input class="mdl-textfield__input" type="text" id="desc">
                                           <label class="mdl-textfield__label" for="desc">Descripci&oacute;n</label>
                                       </div>
					               </div>';
            $data['monto'] ='<div class="col-sm-12 p-0" id="inputMonto">
					             <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                     <input class="mdl-textfield__input" type="text" id="monto">
                                     <label class="mdl-textfield__label" for="monto">Monto de referencia(S/)</label>
                                 </div>
					         </div>';
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeCombo() {
        $selectTipo = _simpleDecryptInt($this->input->post('selectTipo'));
        $idConcepto = _decodeCI($this->input->post('idConcepto'));
        if($selectTipo == 2) {
            $comboPadres = $this->comboPadres(null, $idConcepto);
            $data['optPadre'] = '<select id="selectPadreEdit" name="selectPadreEdit" class="form-group pickerButn" data-live-search="true">
					                 <option value="">Seleccionar Concepto</option>
					                 '.$comboPadres.'
                                 </select>';
        }else{
            $data['optPadre'] = '';
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function mostrarTipo() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$id            = _decodeCI($this->input->post('id'));
    	try {
    		$data                  = $this->m_mantenimiento->mostrarConcepto($id);
    		if($data['flg_padre'] == 1){
    			$comboTipo = __buildComboByGrupo(COMBO_TIPO_CONCEPTO, TIPO_ESPECIFICO);
    			$data['optTipo'] = '<div class="col-sm-12 p-0" id="comboTipoEdit">
        					            <select id="selectTipoEdit" name="selectTipoEdit" class="form-group pickerButn" onchange="cambiarTipo();" data-live-search="true">
        					                <option value="">Seleccionar Tipo</option>
        					                '.$comboTipo.'
                                        </select>
        					        </div> ';
    			$comboPadres = $this->comboPadres($data['id_padre'], $id);
    			$data['optPadre'] = '<div class="col-sm-12 p-0" id="comboPadreEdit">
    					                 <select id="selectPadreEdit" name="selectPadreEdit" class="form-group pickerButn" data-live-search="true">
    					                     <option value="">Seleccionar Concepto</option>
    					                     '.$comboPadres.'
                                         </select>
    					             </div> ';
    			$comboMovimiento = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO, INGRESO);
    			$data['optMov'] = '<div class="col-sm-12 p-0" id="comboMovimientoEdit">
    					               <select id="selectMovEdit" name="selectMovEdit" disabled class="form-group pickerButn" data-live-search="true">
    					                   <option value="">Tipo de Movimiento</option>
    					                   '.$comboMovimiento.'
                                       </select>
    					           </div>';
    			$data['descripcion'] ='<div class="col-sm-12 p-0" id="inputDescripccionEdit">
    					                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" disabled id="descEdit">
                                               <label class="mdl-textfield__label" for="descEdit">Descripci&oacute;n</label>
                                           </div>
    					               </div>';
    			$data['monto'] ='<div class="col-sm-12 p-0"id="inputMontoEdit">
    					             <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                         <input class="mdl-textfield__input" disabled type="text" id="montoEdit">
                                         <label class="mdl-textfield__label" for="montoEdit">Monto de referencia(S/)</label>
                                     </div>
    					         </div>';
    		}else {
    			$comboTipo = __buildComboByGrupo(COMBO_TIPO_CONCEPTO, TIPO_GENERAL);
    			$data['optTipo'] = '<div class="col-sm-12 p-0" id="comboTipoEdit">
    					                <select id="selectTipoEdit" name="selectTipoEdit" class="form-group pickerButn" onchange="cambiarTipo();" data-live-search="true">
    					                    <option value="">Seleccionar Tipo</option>
    					                    '.$comboTipo.'
                                        </select>
    					            </div> ';
    			$data['optPadre'] = '<div class="col-sm-12 p-0" id="comboPadreEdit">
    					             </div> ';
    			$comboMovimiento = __buildComboByGrupo(COMBO_TIPO_MOVIMIENTO, EGRESO);
    			$data['optMov'] = '<div class="col-sm-12 p-0" id="comboMovimientoEdit">
    					               <select id="selectMovEdit" name="selectMovEdit" disabled class="form-group pickerButn" data-live-search="true">
    					                   <option value="">Tipo de Movimiento</option>
    					                   '.$comboMovimiento.'
                                       </select>
    					           </div>';
    			$data['descripcion'] ='<div class="col-sm-12 p-0" id="inputDescripccionEdit">
    					                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" disabled type="text" id="descEdit">
                                               <label class="mdl-textfield__label" for="descEdit">Descripci&oacute;n</label>
                                           </div>
    					               </div>';
    			$data['monto'] ='<div class="col-sm-12 p-0"id="inputMontoEdit">
    					             <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                         <input class="mdl-textfield__input" disabled type="text" id="montoEdit">
                                         <label class="mdl-textfield__label" for="montoEdit">Monto de referencia(S/)</label>
                                     </div>
    					         </div>';
    		}
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function updateConceptTipo() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$selectTipo    = _simpleDecryptInt($this->input->post('selectTipo'));
    	$selectPadre   = _simpleDecryptInt($this->input->post('selectPadre'));
        $id            = _decodeCI($this->input->post('idConcepto'));
    	try {
    		if(empty($selectTipo)) {
    			throw new Exception('Seleccione Tipo de Concepto');
    		}
    		if($selectTipo == '2') {
    			if(empty($selectPadre)) {
    				throw new Exception('Seleccione Concepto un Concepto');
    			}
    		}else {
    			$selectPadre = null;
    		}
    		$arrayUpdate = array('flg_padre'        => ($selectTipo-1),
			   					 'id_padre'         => $selectPadre);
    		$data                   = $this->m_mantenimiento->actualizarConcepto($id, $arrayUpdate);
    		$data['tableConceptos'] = $this->buildTablaConceptosHTML();
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
}