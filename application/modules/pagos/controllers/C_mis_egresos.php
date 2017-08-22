<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_mis_egresos extends CI_Controller {

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
        $this->load->model('m_movimientos');
        $this->load->model('m_mantenimiento');
        $this->load->model('m_caja');
        $this->load->model('m_utils');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_MIS_EGRESOS, PAGOS_ROL_SESS);
    }
   
	public function index() {
	    $idPersonaMov      = _getSesion('id_persona_egreso');
	    $fotoAux           = $this->m_utils->getById('persona', 'foto_persona', 'nid_persona', $idPersonaMov);
	    $foto              = ($fotoAux != null) ? $fotoAux : 'nouser.svg';
	    $data['return']    = '';
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader']      = 'Mis Egresos';
	    $data['ruta_logo']        = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']      = NAME_MODULO_PAGOS;
	    //MENU
	    $rolSistemas       = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']      = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']      = $this->load->view('v_menu', $data, true);
	    //NECESARIO
	    $egresos           = $this->m_movimientos->getAllEgresosByPersona($idPersonaMov, FLG_MOVI_COLABORADORES);
	    $data['tbEgresos'] = $this->buildTableEgresosHTML($egresos);
	    $data['fotoPers']  = (file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $foto)) ?  RUTA_IMG_PROFILE.'colaboradores/'.$foto : RUTA_SMILEDU.FOTO_DEFECTO;
	    $data['nombres']   = $this->m_utils->getNombrePersona($idPersonaMov);
	    $data['optConceptos'] = __buildComboConceptosByTipo(MOV_EGRESO);
	   
	    $data['rol']          = $this->m_utils->getRolByPersona($idPersonaMov);
	    ///////////
	    $this->session->set_userdata(array('tab_active_config' => null));
	    $this->session->set_userdata(array('entraFirstDocente' => 'true'));
	    $this->load->view('v_mis_egresos', $data);
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
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('nid_persona');
	    $idRol     = _getSesion('id_rol');
	    $roles  = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol = _simple_encrypt($var->nid_rol);
	        $return  .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
	}

    function setIdSistemaInSession(){
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }	    
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('nid_persona'),$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;   
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	//DNI 432
	function buildTableEgresosHTML($egresos){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-search="false" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_mis_egresos">',
	                                   'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    
	    $head_0 = array('data' => 'Descripci&oacute;n');
	    $head_1 = array('data' => 'Fecha Registro' , 'class' => 'text-center');
	    $head_5 = array('data' => 'Hora Registro'  , 'class' => 'text-center');
	    $head_2 = array('data' => 'Monto(S/)'      , 'class' => 'text-right');
	    $head_3 = array('data' => 'Documentos'     , 'class' => 'text-center');
	    $head_4 = array('data' => 'Acciones'       , 'class' => 'text-center');
	    ($this->_idRol == ID_ROL_DOCENTE) ? $this->table->set_heading($head_0,$head_1,$head_5 ,$head_2,$head_3) : 
	                                        $this->table->set_heading($head_0,$head_1,$head_5 ,$head_2,$head_3,$head_4);
	    $val = 0;
	    foreach($egresos as $row){
	        $idMovCrypt = _encodeCI($row->id_movimiento);
	        $hora       = _fecha_tabla($row->hora, 'h:i:s A');
	        
	        $row_0      = array('data' => $row->desc_concepto , 'class' => 'text-left');
	        $row_1      = array('data' => $row->fecha_registro, 'class' => 'text-center');
	        $row_5      = array('data' => $hora               , 'class' => 'text-center');
	        $row_2      = array('data' => $row->monto         , 'class' => 'text-right');
	        $row_3      = array('data' => '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="getReciboByEgreso(\''.$idMovCrypt.'\')">
                                               <i class="mdi mdi-content_copy"></i>
                                           </button>',          'class' => 'text-center');
	        $idButton         = "vistaOpciones".$val;
	        $botonObservacion = '<li class="mdl-menu__item" data-toggle="modal" onclick="openModalObservacionEgreso(\''.$idMovCrypt.'\')"><i class="mdi mdi-"></i>Observaci&oacute;n</li>';
	        $botones          = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$val.'" for="'.$idButton.'">
        	                        '.($botonObservacion).'
        	                     </ul>';
	        $botonGeneral     = '<button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                     <i class="mdi mdi-more_vert"></i>
                                 </button>
                             '.$botones;
	        $row_4 = array('data' => $botonGeneral,             'class' => 'text-center');
	        ($this->_idRol == ID_ROL_DOCENTE) ? $this->table->add_row($row_0,$row_1,$row_5,$row_2,$row_3) :
	                                            $this->table->add_row($row_0,$row_1,$row_5,$row_2,$row_3,$row_4);
	        $val++;
	    }
	    if(count($egresos) == 0){
	        $empty =  '<div class="img-search"> 
                           <img src="'.base_url().'public/general/img/smiledu_faces/not_data_found.png">
                           <p>A&uacute;n no ha solicitado ning&uacute;n egreso</p>
                       </div>';
	        return $empty;
	    } else{
	        return $this->table->generate();
	    }
	}
	
	function buildContentDocumentosHTML($documentos,$cant){
	    $content = null;
	    $class   = ($cant == 2) ? 'col-sm-6' : 'col-sm-12';
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_documentos">',
                      'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'Documento'   , 'class' => 'col-sm-2 text-left');
	    $head_1 = array('data' => 'Correlativo' , 'class' => 'col-sm-3 text-right');
	    $head_2 = array('data' => 'Acciones'    , 'class' => 'col-sm-7 text-center');
	    $this->table->set_heading($head_0,$head_1,$head_2);
	    foreach($documentos as $row){
	        $idComCrypt   = _encodeCI($row->_id_movimiento);
	        $tipoDocCrypt = _encodeCI(strtoupper($row->tipo_documento));
	        $nroDoc       = _encodeCI($row->nro_documento);
	        $row_col0 = array('data' => $row->tipo_documento , 'class' => 'text-left');
	        $row_col1 = array('data' => ((strtoupper($row->tipo_documento) == DOC_BOLETA) ? ($row->nro_serie.'-'.$row->nro_documento) : $row->nro_documento) , 'class' => 'text-left');
	        //ACCIONES
	        $onclickImprimir = 'onclick="imprimirDocumento(\''.$idComCrypt.'\',\''.$tipoDocCrypt.'\')"';
	        $buttonSendMail = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="correo" onclick="enviarDocumento(\''.$idComCrypt.'\',\''.$tipoDocCrypt.'\')">
                                   <i class="mdi mdi-email"></i>
                               </button>';
	        $buttonPrintDoc = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" data-original-title="imprimir" '.$onclickImprimir.'>
                                   <i class="mdi mdi-print"></i>
                               </button>';
	        $row_col2 = array('data' => $buttonSendMail.$buttonPrintDoc);
	        $this->table->add_row($row_col0,$row_col1,$row_col2);
	    }
	    $content = $this->table->generate();
	    return $content;
	}
	
	function getDatosByRecibo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $compromiso = _decodeCI(_post('compromiso'));
	        $tipo_doc   = _decodeCI(_post('tipo_doc'));
	        if($compromiso == null){
	            throw new Exception(ANP);
	        }
	        if($tipo_doc  == null){
	            throw new Exception(ANP);
	        }
	        $result            = $this->m_movimientos->getDataCreateRecibo($compromiso);
	        $result['sede']    = strtoupper($this->m_utils->getById('sede'    , 'desc_sede' , 'nid_sede'    , $result['_id_sede'], 'smiledu'));
	        $result['usuario'] = strtoupper($this->m_utils->getById('persona' , 'usuario'   , 'nid_persona' , $result['id_pers_regi'], 'smiledu'));
	        $result['persona'] = $this->m_utils->getNombrePersona($result['_id_persona']);
	        unset($result['_id_sede']);
	        unset($result['_id_persona']);
	        unset($result['id_pers_regi']);
	        $data    += $result;
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getReciboByEgreso(){
	    $data = null;
	    try{
	        $idEgreso = _decodeCI(_post('egreso'));
	        if($idEgreso == null){
	            throw new Exception(ANP);
	        }
	        $documentos = $this->m_movimientos->getDataDocumentos($idEgreso);
	        $data['boleta'] = $this->buildContentDocumentosHTML($documentos,count($documentos));
	    }catch (Exception $e){
	        $data['boleta'] = null;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
}