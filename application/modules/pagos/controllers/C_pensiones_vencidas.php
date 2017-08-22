<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_pensiones_vencidas extends CI_Controller{
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
	public function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_reportes');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_REPORTES, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

    function logout() {
       $this->session->set_userdata(array("logout" => true));
       unset($_COOKIE[__getCookieName()]);
       $cookie_name2 = __getCookieName();
       $cookie_value2 = "";
       setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
       Redirect(RUTA_SMILEDU, true);
    }

    function cambioRol(){
        $idRol     = _simple_decrypt(_post('id_rol'));
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
        $dataUser  = array("id_rol" => $idRol,
				            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $result['url'] = base_url() . "c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }

    function getRolesByUsuario(){
        $idPersona = _getSesion('id_persona');
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

    function setIdSistemaInSession(){
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        __enviarFeedBack($mensaje, $url, $nombre);
    }

    function mostrarRolesSistema() {
        $idSistema = _decodeCI(_post('sistema'));
        $roles     = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'), $idSistema);
        $result    = '<ul>';
        foreach ($roles as $rol) {
            $idRol   = _encodeCI($rol->nid_rol);
            $result .= '<li style="cursor:pointer" onclick="goToSistema(\'' . _post('sistema') . '\', \'' . $idRol . '\')">' . $rol->desc_rol . '</li>';
        }
        $result        .= '</ul>';
        $data['roles']  = $result;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function comboSedesNivel() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	try {
    		$idSede    = empty($this->input->post('idSede')) ? null : _decodeCI($this->input->post('idSede'));
    		$fecInicio = $this->input->post('fecInicio');
    		$fecFin    = $this->input->post('fecFin');
    		$data['optNivel'] = null;
    		if($idSede == null) {
    			$data['error']    = EXIT_ERROR;
    			$data['optNivel'] = null;
    			throw new Exception(ANP);
    		}
   			if(empty($fecInicio)){
   				throw new Exception('Ingrese Fecha Inicio');
   			}
   			if(empty($fecFin)){
   				throw new Exception('Ingrese Fecha Fin');
   			}
   			if($fecInicio > $fecFin){
   				throw new Exception('Fecha Inicio no debe ser mayor que Fecha Fin');
   			}
   			$data += $this->chageTableVencidos($fecInicio, $fecFin, $idSede, null, null, null);
    		$data['optNivel'] = __buildComboNivelesBySede($idSede);
    		$data['error']    = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getComboGradoByNivel() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	try {
    		$idSede           = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
    		$idNivel          = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
    		$tab              = $this->input->post('tabGlobal');
    		$fecInicio        = $this->input->post('fecInicio');
    		$fecFin           = $this->input->post('fecFin');
    		$data['optGrado'] = null;
   			if(empty($fecInicio)){
   				throw new Exception('Ingrese Fecha Inicio');
   			}
   			if(empty($fecFin)){
   				throw new Exception('Ingrese Fecha Fin');
   			}
   			if($fecInicio > $fecFin){
   				throw new Exception('Fecha Inicio no debe ser mayor que Fecha Fin');
   			}
   			if($idNivel == null || $idSede == null) {
   				$data['error']    = EXIT_ERROR;
   				$data['optGrado'] = null;
   			}
   			$data            += $this->chageTableVencidos($fecInicio, $fecFin, $idSede, $idNivel, null, null);
   			$data['optGrado'] = __buildComboGradosByNivel($idNivel, $idSede);
    		$data['error']    = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function comboAulasByGrado() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
    	$idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
    	$idGrado       = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
    	$fecInicio     = $this->input->post('fecInicio');
    	$fecFin        = $this->input->post('fecFin');
    	try {
    		$data['optAula']  = null;
   			if(empty($fecInicio)){
   				throw new Exception('Ingrese Fecha Inicio');
   			}
   			if(empty($fecFin)){
   				throw new Exception('Ingrese Fecha Fin');
   			}
   			if($fecInicio > $fecFin){
   				throw new Exception('Fecha Inicio no debe ser mayor que Fecha Fin');
   			}
   			if($idGrado == null || $idSede == null) {
   				$data['error']    = EXIT_ERROR;
   				$data['optAula']  = null;
   			}
   			$data           += $this->chageTableVencidos($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, null);
   			$data['optAula'] = __buildComboAulas($idGrado, $idSede);  		
    		$data['error'] = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAlumnosFromAula() {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
    	$idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
    	$idGrado       = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
    	$idAula        = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
    	$tab           = $this->input->post('tabGlobal');
    	$fecInicio     = $this->input->post('fecInicio');
    	$fecFin        = $this->input->post('fecFin');
    	try {
    		if($idAula == null) {
    			$data['error']    = EXIT_ERROR;
    		}
   			if(empty($fecInicio)){
   				throw new Exception('Ingrese Fecha Inicio');
   			}
   			if(empty($fecFin)){
   				throw new Exception('Ingrese Fecha Fin');
   			}
   			if($fecInicio > $fecFin){
   				throw new Exception('Fecha Inicio no debe ser mayor que Fecha Fin');
   			}
   			$data += $this->chageTableVencidos($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula);
    		$data['error'] = EXIT_SUCCESS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function chageTableVencidos($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula) {
    	$personas       = array();
    	$arrayPersonas  = array();
//     	$personas       = $this->m_reportes->getAlumnosByFiltro($idSede, $idNivel, $idGrado, $idAula);
//     	foreach ($personas as $row){
//     		array_push($arrayPersonas, $row->nid_persona);
//     	}
    	$datos             = $this->m_reportes->getPensionesVencidas($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula);
    	$data['totalVenc'] = count($datos);
    	if(count($datos) > 0) {
    	    $data['tableVenc'] = $this->buildTablaPensionesVencidasHTML($datos);
    	} else {
    	    $data['tableVenc'] = '<div class="img-search">
	                    	          <img src="'.RUTA_IMG.'/smiledu_faces/not_filter_fab.png">
	                    	              <p>¡Ups!</p>
                                          <p>Tu filtro no ha sido</p>
                    	                  <p>encontrado.</p>
        	                      </div>';
    	}
    	return  $data;
    }
    //240
    function buildTablaPensionesVencidasHTML($datos){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_vencido">',
    			      'table_close' => '</table>');
    	$this->table->set_template($tmpl);
    	
    	/*$head_1 = array('data' => 'Nº'                    , 'class' => 'text-center');*/
    	$head_2 = array('data' => 'C&oacute;digo'         , 'class' => 'text-left');
    	$head_3 = array('data' => 'Estudiante'            , 'class' => 'text-left');
    	$head_4 = array('data' => 'Sede/Nivel/Grado/Aula' , 'class' => 'text-left');
    	$head_5 = array('data' => 'Monto'                 , 'class' => 'text-right');
    	$head_6 = array('data' => 'Mora'                  , 'class' => 'text-right');
    	$head_7 = array('data' => 'Monto Por Cobrar'      , 'class' => 'text-right');
    	$head_8 = array('data' => 'Opciones'              , 'class' => 'text-center');
    	$this->table->set_heading($head_2, $head_3, $head_4, $head_5, $head_6, $head_7, $head_8);
    	$val = 1;
    	
    	foreach ($datos as $row) {
    		$idPersonaEncripty = _encodeCI($row->_id_persona);
    		/*$row_cell_1 = array('data' => $val                            , 'class' => 'text-right');*/
    		$row_cell_2 = array('data' => $row->cod_alumno                , 'class' => 'text-left');
    		$row_cell_3 = array('data' => $row->nombre_estudiante         , 'class' => 'text-left');
    		$row_cell_4 = array('data' => $row->niveles                   , 'class' => 'text-left');
    		$row_cell_5 = array('data' => $row->monto_total               , 'class' => 'text-right');
    		$row_cell_6 = array('data' => $row->mora_acumulada_total      , 'class' => 'text-right');
    		$row_cell_7 = array('data' => $row->monto_final_total         , 'class' => 'text-right');
    		$row_cell_8 = array('data' => '<a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalAlumnosDetalles(\''.$idPersonaEncripty.'\');">
	                                           <i class="mdi mdi-list"></i>
	                                       </a>');
    		$val++;
    		$this->table->add_row($row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6, $row_cell_7, $row_cell_8);
    	}
    	$tabla = $this->table->generate();
    	return $tabla;
    }
    
    function createTableDetalleAlumno() {
    	$idPersona              = _decodeCI($this->input->post('idPersona'));
    	$fecInicio              = $this->input->post('fecInicio');
    	$fecFin                 = $this->input->post('fecFin');
    	$arrayPensiones         = array();
    	$arrayPensiones         = $this->m_reportes->getDetallePensiones($idPersona, $fecInicio, $fecFin);
    	$data['tablaPensVenc']  = $this->buildTablaDetallesPensiones($arrayPensiones);
    	$data['nombreCompleto'] = $this->m_utils->getNombrePersona($idPersona);
    	$detallePadres          = $this->m_reportes->getDetallePadres($idPersona);
    	$resul                  = $this->buildDetallesPadres($detallePadres);
    	$data['tabs']           = $resul[1];
    	$data['apoderados']     = $resul[0];
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildDetallesPadres($detallePadres) {
    	$datos = null;
    	$tabs = null;
    	$tabs .= '<a data-idDiv="#tabPensiones" onclick="cambiarTab($(this));" class="mdl-tabs__tab is-active tabsDetalles">Cronograma</a>';
    	foreach ($detallePadres as $row){
    		$datos .= '<div class="mdl-tabs__panel detalleDescripcion" id="tab'.$row->desc_combo.'">
		    		       <div class="col-sm-12">
			    		       <div class="col-sm-12 mdl-input-group">
			    			       <div class="mdl-icon">
			    				       <i class="mdi mdi-account_circle"></i>
			    				   </div>
			    				   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			                           <input class="mdl-textfield__input" type="text" id="desc_combo" name="desc_combo" value="'.$row->nombre_apoderado.'" disabled>
			                           <label class="mdl-textfield__label" for="desc_combo">Nombre</label>
			                       </div>
			                   </div>
			                   <div class="col-sm-12 mdl-input-group">
			    			       <div class="mdl-icon">
			    				       <i class="mdi mdi-phone"></i>
			    				   </div>
			    				   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			                           <input class="mdl-textfield__input" type="text" id="telefono" name="telefono" value="'.$row->telefono.'" disabled>
			                           <label class="mdl-textfield__label" for="telefono">Telefono</label>
			                       </div>
			                   </div>
			                   <div class="col-sm-12 mdl-input-group">
			    			       <div class="mdl-icon">
			    				       <i class="mdi mdi-email"></i>
			    				   </div>
			    				   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			                           <input class="mdl-textfield__input" type="text" id="email1" name="email1" value="'.$row->email1.'" disabled>
			                           <label class="mdl-textfield__label" for="email1">Correo</label>
			                       </div>
			                   </div>
			                   <div class="col-sm-12 mdl-input-group">
			    			       <div class="mdl-icon">
			    				       <i class="mdi mdi-home"></i>
			    				   </div>
			    				   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			                           <input class="mdl-textfield__input" type="text" id="direccion_hogar" name="direccion_hogar" value="'.$row->direccion_hogar.'" disabled>
			                           <label class="mdl-textfield__label" for="direccion_hogar">Direccion</label>
			                       </div>
			                   </div>
			                   <div class="col-sm-12 mdl-input-group">
			    			       <div class="mdl-icon">
			    				       <i class="mdi mdi-location_on"></i>
			    				   </div>
			    				   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
			                           <input class="mdl-textfield__input" type="text" id="ubigeo" name="ubigeo" value="'.$row->ubigeo.'" disabled>
			                           <label class="mdl-textfield__label" for="ubigeo">Ubigeo</label>
			                       </div>
			                   </div>
		                   </div>
		               </div>';
    		$tabs .= '<a data-idDiv="#tab'.$row->desc_combo.'" onclick="cambiarTab($(this));" class="mdl-tabs__tab tabsDetalles">'.$row->desc_combo.'</a>';
    	}
    	return array($datos,$tabs);
    }
    
    function buildTablaDetallesPensiones($arrayPensiones){
    	$tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_pensiones">',
    			      'table_close' => '</table>');
    	$this->table->set_template($tmpl);
//     	$head_1 = array('data' => 'Nº'              ,'class' => 'text-center');
    	$head_2 = array('data' => 'Cronograma'      ,'class' => 'text-center');
    	$head_3 = array('data' => 'Monto'           ,'class' => 'text-center');
    	$head_4 = array('data' => 'Mora'            ,'class' => 'text-center');
    	$head_5 = array('data' => 'Monto Por Cobrar','class' => 'text-center');
    	$head_6 = array('data' => 'Dias de Mora'    ,'class' => 'text-center');
    	$this->table->set_heading(/*$head_1, */$head_2, $head_3, $head_4, $head_5, $head_6);
    	$val        = 1;
    	$totalMonto = 0;
    	foreach ($arrayPensiones as $row) {
    		$row_cell_1 = array('data' => $val);
    		$row_cell_2 = array('data' => $row->desc_detalle_crono);
    		$row_cell_3 = array('data' => $row->monto);
    		$row_cell_4 = array('data' => $row->mora_acumulada);
    		$row_cell_5 = array('data' => $row->monto_final);
    		$row_cell_6 = array('data' => $row->dias_mora);
    		$totalMonto = $totalMonto + floatval($row->monto_final);
    		$val++;
    		$this->table->add_row(/*$row_cell_1,*/ $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6);
    	}
    	$this->table->add_row('TOTAL',null,null,null,$totalMonto,null);
    	$tabla = $this->table->generate();
    	return $tabla;
    }
    
    function buildGraficoByTab(){
        try{
            $tab           = _post('tab');
            $fecInicio     = _post('fecInicio');
            $fecFin        = _post('fecFin');
            $idSede        = empty($this->input->post('idSede'))  ? null : _decodeCI($this->input->post('idSede'));
            $idNivel       = empty($this->input->post('idNivel')) ? null : _decodeCI($this->input->post('idNivel'));
            $idGrado       = empty($this->input->post('idGrado')) ? null : _decodeCI($this->input->post('idGrado'));
            $idAula        = empty($this->input->post('idAula'))  ? null : _decodeCI($this->input->post('idAula'));
            $datos         = $this->m_reportes->getPensionesVencidasGrafico($fecInicio,$fecFin, $idSede, $idNivel, $idGrado, $idAula);
            $arraySeries   = array();
            $arrayCate     = array();
            foreach ($datos as $row){
            	array_push($arrayCate , utf8_encode($row->desc_sede));
                array_push($arraySeries , array('y'     => round($row->sum,2),
                                                'name'  => 'Vencidos '.intval($row->count),
                                                'color' => 'red'
                                               ));
                
            }
            $data['series'] = json_encode(array($arraySeries));
            $data['cate']   = json_encode($arrayCate);
        } catch(Exception $e){
    
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}