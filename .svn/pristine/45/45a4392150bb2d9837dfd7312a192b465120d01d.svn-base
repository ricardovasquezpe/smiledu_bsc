<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_solicitud_personal extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_rh/m_solicitud_personal');
        $this->load->model('mf_rh/m_incidencia');
        $this->load->model('m_utils');
        $this->load->library('table');
        $this->load->model('mf_usuario/m_usuario');
        _validate_usuario_controlador(ID_PERMISO_SOLIC_PERSONAL);
        $this->_idUserSess = _getSesion('nid_persona');
    }

	public function index() {
	    $data['titleHeader'] = 'Solicitud de personal';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';

        $roles = _getSesion('roles');
        if($roles != null) {
            $final 	= array();
            foreach($roles as $rol) {
                array_push($final, $rol->nid_rol);
                if($rol->nid_rol == ID_ROL_SOLICITUD_PERSONAL) {
                    $this->session->set_userdata(array('rol' =>ID_ROL_SOLICITUD_PERSONAL));
                    $data['btnFlotante'] = $this->buildBotonAgregarSolicitud();
                    $data['tableSolicitud'] = $this->buildTableVacantesHTML(_getSesion('nid_persona'));
               } else if($rol->nid_rol == ID_ROL_RESP_RRHH || $rol->nid_rol == ID_ROL_DIRECTOR) {
                    $this->session->set_userdata(array('rol' =>ID_ROL_RESP_RRHH));
                    $data['tableSolicitud'] = $this->buildTableVacantesHTML(null);
               }
            }
            $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
        }
        
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
       //MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
        $data['font_size'] = _getSesion('font_size');

        $this->load->view('vf_rh/v_solicitud_personal', $data);
	}
	
	function buildComboAreas(){
	    $data = $this->m_solicitud_personal->getAreasGenerales();
	    $optAreas = '';
	    foreach($data as $area){
	        $areaCrypt = _encodeCI($area->id_area);
	        $optAreas .= '<option value="'.$areaCrypt.'">'.$area->desc_area.'</option>';
	    }
	    return $optAreas;
	}
	
	function buildComboAreasEspecificas(){
	    $idAreaGenDecrypt = _decodeCI(_post('idArea'));
	    $optAreasEsp = '';
	    $data = $this->m_incidencia->getAllAreasEspecificasEmpresa($idAreaGenDecrypt);
	    foreach ($data as $area){
	        $areaCrypt = _encodeCI($area->id_area);
	        $optAreasEsp .= '<option value="'.$areaCrypt.'">'.$area->desc_area.'</option>';
	    }
	    $result['comboAreaEsp'] = $optAreasEsp;
	    $result['nombre']       = 'ï¿½rea Especï¿½fica';
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function buildComboPuestos(){
	    $data = $this->m_solicitud_personal->getPuestos();
	    $optPuestos = '';
	    foreach($data as $puesto){
	        $puestoCrypt = _encodeCI($puesto->valor);
	        $optPuestos .= '<option value="'.$puestoCrypt.'">'.$puesto->desc_combo.'</option>';
	    }
	    return $optPuestos;
	}
	
	function buildTableVacantesHTML($idUsuario){
	    $listaVacante = $this->m_solicitud_personal->getAllVacantes($idUsuario);
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_solicitudes" data-toolbar="#custom-toolbar">',
	                                   'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $rolSession = _getSesion('rol');
	    $head_0  = array('data' => '#');
	    $head_1  = array('data' => 'Puesto');
	    $head_2  = array('data' => 'ï¿½rea Esp.');
	    $head_3  = array('data' => 'Sede');
	    $head_4  = array('data' => 'Observaciï¿½n');
	    $head_5  = array('data' => 'Fecha Registro');
	    $head_6  = array('data' => 'Estado');
	    $head_7  = array('data' => 'Fecha Atenciï¿½n');
	    $head_8  = array('data' => 'Acciï¿½n');
	    $head_9  = array('data' => 'Solicitante');
	    $head_10 = array('data' => 'Atendido por');
	    $head_11 = array('data' => 'ï¿½rea');
	    if($rolSession == ID_ROL_RESP_RRHH){
	        $this->table->set_heading($head_0,$head_1,$head_11,$head_2,$head_3,$head_4,$head_5,$head_9,$head_6,$head_10,$head_7,$head_8);
	    } else {
	        $this->table->set_heading($head_0,$head_1,$head_11,$head_2,$head_3,$head_4,$head_5,$head_6,$head_10,$head_7);
	    }
	    $cont = 0;
	    $opacidad = null;
	    foreach($listaVacante AS $vacante){
	        if($vacante->color == 'bg-success' || $vacante->color == ''){
	            $opacidad = '0.3';        
	        } else{
	            $opacidad = '1';
	        }
	        $cont++;
	        $idVacCrypt = _encodeCI($vacante->id_vacante);
	        $row_0 = array('data' => $cont                 , 'class'=>$vacante->color);
	        $row_1 = array('data' => $vacante->puesto      , 'class'=>$vacante->color);
	        $row_2 = array('data' => $vacante->desc_general, 'class'=>$vacante->color);
	        $row_3 = array('data' => $vacante->desc_sede   , 'class'=>$vacante->color);
	        $row_4 = array('data' => $vacante->observacion , 'class'=>$vacante->color);
	        $row_5 = array('data' => $vacante->fec_regi    , 'class'=>$vacante->color);
	        $row_6 = array('data' => $vacante->estado      , 'class'=>$vacante->color);
	        $row_7 = array('data' => $vacante->fec_fin     , 'class'=>$vacante->color);
	        $row_8 = array('data' => '<button type="button" onclick="abrirModalCambiarEstado(this);" id="sol'.$cont.'" attr-estado="'.$vacante->estado.'" attr-idvac="'.$idVacCrypt.'"  
	                                   class="btn ink-reaction btn-icon-toggle"><i class="material-icons" style="opacity:'.$opacidad.';font-size:17px">'.$vacante->icono.'</i></button>' , 'class'=>$vacante->color);
	        $row_9  = array('data' => $vacante->nombres_solicitante   , 'class' =>$vacante->color);
	        $row_10 = array('data' => $vacante->nombres_usua_atencion , 'class' =>$vacante->color);
	        $row_11 = array('data' => $vacante->desc_especifica , 'class' =>$vacante->color);
	        if($rolSession == ID_ROL_RESP_RRHH){
	            $this->table->add_row($row_0,$row_1,$row_2,$row_11,$row_3,$row_4,$row_5,$row_9,$row_6,$row_10,$row_7,$row_8);
	        } else{
	            $this->table->add_row($row_0,$row_1,$row_2,$row_11,$row_3,$row_4,$row_5,$row_6,$row_10,$row_7);
	        }
	    }
	    $table = $this->table->generate();
	    return $table;
	}
	
	function grabarSolicitud(){
	    $data['msj']   = null;
	    $data['error'] = EXIT_ERROR;
	    $idPuesto      = _decodeCI(_post('idPuesto'));
	    $idSede        = _decodeCI(_post('idSede'));
	    $idArea        = _decodeCI(_post('idArea'));
	    $idAreaEsp     = _decodeCI(_post('idAreaEsp'));
	    $observaciones = _post('observaciones');
	    $cantidad      = _post('cantidad');
	    $usuarioLogeado= _getSesion('nid_persona');
	    $nombreCompleto= _getSesion('nombre_completo');
	    try{
	        if($idPuesto == null){
	            throw new Exception('Debe seleccionar un puesto');
	        }
	        if($idArea == null){
	            throw new Exception('Debe seleccionar una ï¿½rea');
	        }
	        if($idSede == null){
	            throw new Exception('Debe seleccionar una sede');
	        }
	        if($observaciones == null){
	            throw new Exception('Debe ingresar las observaciones');
	        }
	        if($cantidad == null || $cantidad <= 0){
	            throw new Exception('Debe ingresar una cantidad mayor a 0');
	        }
	        if($idAreaEsp == null){
	            throw new Exception('Debe seleccionar una ï¿½rea especï¿½fica');
	        }
	        $arrayGeneral   = array();
	        $arraySolicitud = array('id_puesto'           => $idPuesto,
                                    'id_area'             => $idArea,
	                                'id_sede'             => $idSede,
	                                'observacion'         => utf8_encode($observaciones),
	                                'fec_regi'            => date('Y-m-d h:i:s a'),
	                                'id_solicitante'      => $usuarioLogeado,
	                                'nombres_solicitante' => $nombreCompleto,
	                                'estado'              => SOLICITUD_SOLICITADO,
	                                'id_area_especifica'  => $idAreaEsp
 	                               );
	        for($i = 0 ; $i < $cantidad ; $i++ ){
	            array_push($arrayGeneral, $arraySolicitud);
	        }
	        $nombreArea    = $this->m_utils->getById('area', 'desc_area', 'id_area', $idArea);
	        $nombreAreaEsp = $this->m_utils->getById('area', 'desc_area', 'id_area', $idAreaEsp);
	        $nombrePuesto  = $this->m_utils->getPuestoById($idPuesto);
	        $data = $this->m_solicitud_personal->insertSolicitudesPersonal($arrayGeneral);
	        if($data['error'] == EXIT_ERROR){
	            throw new Exception('No se pudo insertar los datos');
	        }
	        $body			=	"Puesto: ".$nombrePuesto."<br/>Área: ".$nombreArea."<br/>Área Específica: ".$nombreAreaEsp."<br/>Solicitado por: ".$nombreCompleto."<br/>".date('Y-m-d h:i:s a');   
	        //__enviarEmail(CORREO_RECURSOS_HUMANOS,'Solicitud de Personal',$body);
	        $data['comboPuesto'] = $this->buildComboPuestos();
	        $data['comboArea']   = $this->buildComboAreas();
	        $data['comboSede']   = __buildComboSedes();
	        $logeoUsario = _getSesion('nid_persona');
	        $data['tablaSolicitudes'] = $this->buildTableVacantesHTML($logeoUsario);
	        $data['msj'] = 'Se registraron '.$cantidad.' solicitud(es)';
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getDataModal(){
	    $data['comboPuesto'] = $this->buildComboPuestos();
	    $data['comboArea']   = $this->buildComboAreas();
	    $data['comboSede']   = __buildComboSedes();
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildBotonAgregarSolicitud(){
	    $btn = '<ul id="menu" class="mfb-component--br mfb-zoomin " style="z-index:1">
                  <li class="mfb-component__wrap">
	                  <a href="javascript:void(0)" class="mfb-component__button--main mdl-color--indigo" onclick="abrirModalSolicitud();" id="main_button">
                          <i class="mfb-component__main-icon--resting md md-filter-list" style="font-size:26px;padding-top: 3px;color:white;margin-top:0px;transform:rotate(0deg)"></i>
                    </a>
                  </li>
                </ul>';
	    return $btn;
	}
	
	function getDataModalEstado(){
	    $data['msj'] = null;
	    $idVacanteEncry = _post('idVacante');
	    $idVacante      = _decodeCI($idVacanteEncry);
	    try{
	       if($idVacante == null){
	           throw new Exception('No se puede realizar la acciï¿½n');
	       }
	       $estado = $this->m_utils->getById('vacante', 'estado', 'id_vacante', $idVacante);
	       $estados = null;
	       $data['btn']   = '';
	       if($estado == SOLICITUD_SOLICITADO){
	           $estados = '<label class="radio-inline radio-styled radio-success">
						      <input type="radio" name="radioVals" value="'.SOLICITUD_PENDIENTE.'"><span>Pendiente</span>
					      </label>';
	           $estados.= '<label class="radio-inline radio-styled radio-danger">
						      <input type="radio" name="radioVals" value="'.SOLICITUD_ANULADO.'"><span>Anulado</span>
					      </label>';
	           $data['btn']   = '<button type="button" id="btnAcepta" style="margin-right: 10px" onclick="cambiaEstadoSolicitud(this);" attr-idvac="'.$idVacanteEncry.'" class="btn ink-reaction btn-flat btn-primary pull-right">ACEPTAR</button>';
	       } else if($estado == SOLICITUD_PENDIENTE){
	           $estados = '<label class="radio-inline radio-styled radio-success">
						      <input type="radio" name="radioVals" value="'.SOLICITUD_CONTRATADO.'"><span>Contratado</span>
					      </label>';
	           $estados.= '<label class="radio-inline radio-styled radio-danger">
						      <input type="radio" name="radioVals" value="'.SOLICITUD_ANULADO.'"><span>Anulado</span>
					      </label>';
	           $data['btn']   = '<button type="button" id="btnAcepta" style="margin-right: 10px" onclick="cambiaEstadoSolicitud(this);" attr-idvac="'.$idVacanteEncry.'" class="btn ink-reaction btn-flat btn-primary pull-right">ACEPTAR</button>';
	       } else if($estado == SOLICITUD_CONTRATADO || $estado == SOLICITUD_ANULADO){
	           throw new Exception('No se puede cambiar el estado');
	       }
	       $data['radio'] = $estados;
	    } catch(Exception $e){
	        $data['radio'] = null;
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function cambiaEstadoSolicitud(){
	    $data['msj']   = null;
	    $data['error'] = EXIT_ERROR;
	    $idVacante = _decodeCI(_post('idVacante'));
	    $estado    = _post('estado');
	    $usuarioLogeado= _getSesion('nid_persona');
	    $nombreCompleto= _getSesion('nombre_completo');
	    try{
	        if($idVacante == null){
	            throw new Exception('No se puede realizar la acciï¿½n');
	        }
	        if($estado != SOLICITUD_PENDIENTE && $estado != SOLICITUD_CONTRATADO && $estado != SOLICITUD_ANULADO ){
               throw new Exception('El estado no es valido');
	        }
	        $arrayUpdate = array('estado'                => $estado,
	                             'id_usua_atencion'      => $usuarioLogeado,
	                             'nombres_usua_atencion' => $nombreCompleto,
	                             'fec_fin'               => date('Y-m-d h:i:s a')
	                            );
	        $data = $this->m_solicitud_personal->actualizaEstadoSolicitud($idVacante,$arrayUpdate);
	        if($data['error'] == EXIT_ERROR){
	            throw new Exception('No se pudo realizar la acciï¿½n');
	        }
	        $idSolicitante    = $this->m_utils->getById('vacante', 'id_solicitante', 'id_vacante', $idVacante);
	        $emailSolicitante = $this->m_utils->getById('persona', 'correo', 'nid_persona', $idSolicitante);
	        $body			  =	"Atendido por: ".$nombreCompleto."<br/>Estado: ".$estado."<br/>Fecha: ".date('Y-m-d h:i:s a');
	        __enviarEmail($emailSolicitante,'Atención a la solicitud',$body);
	        $data['tablaSolicitudes'] = $this->buildTableVacantesHTML(null);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logOut(){
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
	function enviarFeedBack(){
	    $nombre  = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url     = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
}