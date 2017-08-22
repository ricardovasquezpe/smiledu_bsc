<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_evaluar_rapido extends CI_Controller {
    
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
        $this->load->model('mf_evento/m_evento');
        $this->load->model('mf_evaluacion/m_evaluacion_rapido');
        $this->load->model('mf_evaluacion/m_evaluacion');
        $this->load->model('mf_evaluacion/m_detalle_evaluacion');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_EVENTO, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
	    $data['titleHeader'] = "Evaluaci&oacute;n: ".$this->m_utils->getById("admision.evento", "desc_evento", "id_evento", _getSesion("idEventoEvaluar"));
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['return'] = '';
	    $data['ruta_logo'] = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo'] = NAME_MODULO_ADMISION;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $days = explode(';', DIAS_SEMANA);
	    $dayofweek = date('w', strtotime(date("Y-m-d")));
	    $data['weekDay'] = 'Invitados ('.$days[$dayofweek].' - '.date("d/m/Y").')';

	    $contactos = $this->m_evaluacion_rapido->filtrarContactosDia(null, date("d/m/Y"), _getSesion("idEventoEvaluar"), $this->_idRol);
	    //$data['countContactos'] = count($contactos);
	    $data['contactosHoy'] = $this->buildTableContactosFecha($contactos);

        $this->load->view('V_evaluar_rapido', $data);
	}
	
	function buildTableContactosFecha($contactos){
	    $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbBusquedaContactoFiltro">',
	                 'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0_1 = array('data' => '#', 'class' => 'text-center');
	    $head_0   = array('data' => 'Nombre', 'class' => 'text-left');
	    $head_1   = array('data' => 'Sede', 'class' => 'text-left');
	    $head_2   = array('data' => 'Grado', 'class' => 'text-left');
	    $head_3   = array('data' => 'Hora', 'class' => 'text-left');
	    $head_4   = array('data' => 'Diagn&oacute;sticos', 'class' => 'text-center');
	    $head_5   = array('data' => 'Estado', 'class' => 'text-center');
	    $head_6   = array('data' => 'Opci&oacute;n', 'class' => 'text-center');
	    $this->table->set_heading($head_0_1, $head_3, $head_0, $head_1, $head_2, $head_4, $head_5, $head_6);
	    $i = 1;
	    foreach ($contactos as $row){
	        $idContactoEnc = _simple_encrypt($row->id_contacto);
	        $class = null;
	        $observ = null;
	        if($this->_idRol == ID_ROL_SECRETARIA){
	            if($row->cant_entrevista == 1){
	                $observ = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="mostrarObservacionEntrevista(\''.$row->observ_entrevista.'\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Evaluar" style="display:inline-block">
                                   <i class="mdi mdi-content_paste"></i>
                               </button>';
	            }
	            if($row->estado != EVALUACION_ENTREVISTADO){
	                $class = "active";
	            }
	        }
	        $row_0_1 = array('data' => $i ,'class' => 'text-left '.$class);
	        $row_0   = array('data' => $row->nombrecompleto ,'class' => 'text-left '.$class);
	        $row_1   = array('data' => $row->sede_interes ,'class' => 'text-left '.$class);
	        $row_2   = array('data' => $row->grado_interes.' '.$row->nivel_interes ,'class' => 'text-left '.$class);
	        $row_3   = array('data' => $row->hora ,'class' => 'text-left '.$class);
	        $row_4   = array('data' => '<p style="cursor:pointer; display:inline-block" onclick="verDiagnosticoTabla(\''.$idContactoEnc.'\', \''.$row->nombreabreviado.'\')">'.$row->cant_diag_real.'/'.$row->cant_diag.'</p>'.$observ ,'class' => 'text-center '.$class);
	        
	        if($row->estado == EVALUACION_A_EVALUAR){
	            $btn = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="goToEvaluacion(\''.$idContactoEnc.'\')">Evaluar</button>';
	        }else if($row->estado == EVALUACION_EVALUADO){
	            $btn = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="goToEvaluacion(\''.$idContactoEnc.'\')">Entrevistar</button>';
	        }else if($row->estado == EVALUACION_ENTREVISTADO){
	            $btn = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="abrirModalPasarMatricula(\''.$idContactoEnc.'\')">Proce. Matr&iacute;cula</button>';
	        }else if($row->estado == EVALUACION_MATRICULA){
	            $btn = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect">Termin&oacute;</button>';
	        }else{
	            $btn = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect">Cancelado</button>';
	        }
	        
	        if($row->estado == EVALUACION_ENTREVISTADO && $row->entrevista == 2){
	            $btn = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect">No Apto</button>';
	        }
	        
	        $quitar      = null;
	        $reprogramar = null;
	        if($row->cant_diag_real == 0){
	            $quitar      = '<li class="mdl-menu__item" onclick="abrirModalConfirmarQuitar(\''.$idContactoEnc.'\')"><i class="mdi mdi-delete"></i>Eliminar</li>';
	            $reprogramar = '<li class="mdl-menu__item" onclick="abrirModalAgendarContacto(\''.$idContactoEnc.'\', \''._fecha_tabla($row->fecha, "d/m/Y").'\', \''._fecha_tabla($row->fecha, "h:i A").'\')"><i class="mdi mdi-date_range"></i>Re-programar</li>';
	        }
	        
	        $row_5 = array('data' => $btn, 'class' => 'text-center '.$class);
	        $opciones = '<button id="agendado-'.$i.'" class="mdl-button mdl-js-button mdl-button--icon">
                             <i class="mdi mdi-more_vert"></i>
                         </button>
	                     <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="agendado-'.$i.'">
	                         '.$quitar.$reprogramar.'
	                     </u>';
	        if($quitar.$reprogramar == null){
	            $opciones = null;
	        }
	        $row_6   = array('data' => $opciones ,'class' => 'text-center '.$class);
	        $this->table->add_row($row_0_1, $row_3, $row_0, $row_1, $row_2, $row_4, $row_5, $row_6);
	        $i++;
	    }
	    $table = $this->table->generate();
	    return $table;
	}
	
	function busquedaContactos(){
	    $nombre = _post("nombre");
	    $contactos = $this->m_evaluacion_rapido->busquedaContactos($nombre, _getSesion("idEventoEvaluar"));
	    $data['tabla'] = $this->buildTableBusqueda($contactos);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTableBusqueda($contactos){
	    $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbBusquedaContacto">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0_1 = array('data' => '#', 'class' => 'text-center');
	    $head_0   = array('data' => 'Nombre', 'class' => 'text-left');
	    $head_1   = array('data' => 'Sede', 'class' => 'text-left');
	    $head_2   = array('data' => 'Grado', 'class' => 'text-left');
	    $head_3   = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
	    $this->table->set_heading($head_0_1, $head_0, $head_1, $head_2, $head_3);
	    $i = 1;
	    foreach ($contactos as $row){
	        $row_0_1 = $i;
	        $idContactoEnc = _simple_encrypt($row->id_contacto);
	        $row_0   = array('data' => $row->nombrecompleto ,'class' => 'text-left');
	        $row_1   = array('data' => $row->sede_interes ,'class' => 'text-left');
	        $row_2   = array('data' => $row->grado_interes.' '.$row->nivel_interes ,'class' => 'text-left');
	        $color = null;
	        if($row->fecha_agendado != null){
	            $color = "green";
	        }
	        $borrar = _fecha_tabla($row->fecha_agendado, "d/m").'<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="abrirModalAgendarContacto(\''.$idContactoEnc.'\', \''._fecha_tabla($row->fecha_agendado, "d/m/Y").'\', \''._fecha_tabla($row->fecha_agendado, "h:i A").'\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Agendar">
                           <i class="mdi mdi-date_range" style="color:'.$color.'"></i>
                       </button>'.
	                   '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="goToEvaluacion(\''.$idContactoEnc.'\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Evaluar">
                           <i class="mdi mdi-explicit"></i>
                       </button>';
	        $row_3   = array('data' => $borrar, 'class' => 'text-center');
	        $this->table->add_row($row_0_1, $row_0, $row_1, $row_2, $row_3);
	        $i++;
	    }
	    $table = $this->table->generate();
	    return $table;
	}
	
	function agendarContacto(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idContacto = _simpleDecryptInt(_post('contacto'));
	        $fecha      = _post("fecha");
	        $hora       = _post("hora");
	        if($idContacto == null){
	            throw new Exception(ANP);
	        }
	        if($fecha == null || $hora == null){
	            throw new Exception("Ingrese todos los campos (*)");
	        }
	        if($this->m_evaluacion_rapido->cantDiagnosticoRealizadosEvento($idContacto, _getSesion("idEventoEvaluar")) > 0){
	            throw new Exception('No se puede re-programar, ya se ha evaluado');
	        }
	        if($this->m_evaluacion_rapido->validateContactoAgendado(_getSesion("idEventoEvaluar"), $fecha, $idContacto) == 1){
	            $arrayUpdate = array("fecha"       => $fecha.' '.$hora,
	                                 "estado"      => EVALUACION_A_EVALUAR);
	            $data = $this->m_evaluacion_rapido->updateAgendado($arrayUpdate, $idContacto, _getSesion("idEventoEvaluar"));
	        }else{
	            $arrayInsert = array("id_contacto" => $idContacto,
	                                 "fecha"       => $fecha.' '.$hora,
	                                 "id_evento"   => _getSesion("idEventoEvaluar"),
	                                 "estado"      => EVALUACION_A_EVALUAR);
	            $data = $this->m_evaluacion_rapido->agendarContacto($arrayInsert);
	        }
	        if($data['error'] == EXIT_SUCCESS){
	            $fecha = _post('fecha_ubic');
	            if(strlen($fecha) == 0){
	                $fecha = date("d/m/Y");
	            }
	            $contactos = $this->m_evaluacion_rapido->filtrarContactosDia(null, $fecha, _getSesion("idEventoEvaluar"), $this->_idRol);
	            $data['tabla'] = $this->buildTableContactosFecha($contactos);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function evaluarContacto(){
	    $id_contacto = _simpleDecryptInt(_post("contacto"));
	    $dataUser = array(     "idPostulanteEvaluar" => $id_contacto,
	                           "idEventoProgreso"    => _getSesion("idEventoEvaluar"),
	                           "pantalla_evaluar"    => 2);
	    $this->session->set_userdata($dataUser);
	    $data['url'] = "c_detalle_evaluacion";
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buscarContactoDia(){
	    $nombre = _post("nombre");
	    $fecha  = (strlen(_post("fecha")!=0)?_post("fecha"):null);
	    $contactos = $this->m_evaluacion_rapido->filtrarContactosDia($nombre, $fecha, _getSesion("idEventoEvaluar"), $this->_idRol);
	    $data['tabla'] = $this->buildTableContactosFecha($contactos);
	    $data['fecha'] = $fecha;
	    
	    $fecFormar = implode('-', array_reverse(explode('/', $fecha )));
        $days = explode(';', DIAS_SEMANA);
        $dayofweek = date('w', strtotime($fecFormar));
        $data['weekDay'] = $days[$dayofweek];
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function contactosHoy(){
	    $contactos = $this->m_evaluacion_rapido->filtrarContactosDia(null, date("d/m/Y"), _getSesion("idEventoEvaluar"), $this->_idRol);
	    $data['tabla'] = $this->buildTableContactosFecha($contactos);
	    $data['fecha'] = date("d/m/Y");
        $days = explode(';', DIAS_SEMANA);
        $dayofweek = date('w', strtotime(date("Y-m-d")));
        $data['weekDay'] = $days[$dayofweek];
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function resumenDiagnosticos(){
	    $idContacto = _simpleDecryptInt(_post("contacto"));
	    _log("AGENDADO: "._getSesion("idEventoEvaluar"));
	    $diagnosticos = $this->m_evaluacion->getDiagnosticosResumen($idContacto, _getSesion("idEventoEvaluar"));
	    $data['tabla'] = _createTableResumenDiagnostico($diagnosticos);
	    $diagnosticoSubdirector = $this->m_evaluacion->getDiagnosticoSubdirector($idContacto, _getSesion("idEventoEvaluar"));
	    $data['tablaSubdirector'] = _createTableDiagnosticoSubdirector($diagnosticoSubdirector);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function evaluarProcesoMatricula(){
	    $idContacto = _simpleDecryptInt(_post("contacto"));
	    $codGrupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContacto);
	    $data['pass'] = 1;
	    if($this->m_evaluacion_rapido->validateCamposCompletosProcesoMatricula($codGrupo, $idContacto) != $this->m_evaluacion_rapido->cantParientesByGrupo($codGrupo) + 1){
	        $data['pass'] = 0;
	    }
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
	        $diag = $this->m_evaluacion->getDiagnosticoSubdirector($idContacto, _getSesion("idEventoEvaluar"));
	        if($diag['diagnostico'] != 'Apto'){
	            throw new Exception("El contacto no est&aacute; apto");
	        }
	        $estado = $this->m_utils->getById("admision.contacto", "estado", "id_contacto", $idContacto);
	        if($estado == ESTADO_CONTACTO_PAGO_CUOTA_INGRESO){
	            throw new Exception("El contacto ya est&aacute; en el proceso de matricula");
	        }
	        $codGrupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContacto);
	        if($this->m_evaluacion_rapido->validateCamposCompletosProcesoMatricula($codGrupo, $idContacto) != $this->m_evaluacion_rapido->cantParientesByGrupo($codGrupo) + 1){
	            throw new Exception("El contacto y sus parientes debe tener todos los campos llenos");
	        }
	        $data = $this->m_evaluacion->procesoMatricula($idContacto, $codGrupo, _getSesion("idEventoEvaluar"));
	        if($data['error'] == EXIT_SUCCESS){
	            $arrayUpdate = array("estado" => EVALUACION_MATRICULA);
	            $this->m_detalle_evaluacion->updateAgendados($arrayUpdate, $idContacto, _getSesion("idEventoEvaluar"));
	            
	            $nombre = (strlen(_post("nombre")!=0)?_post("nombre"):null);
	            $fecha  = (strlen(_post("fecha")!=0)?_post("fecha"):null);
	            $contactos = $this->m_evaluacion_rapido->filtrarContactosDia($nombre, $fecha, _getSesion("idEventoEvaluar"), $this->_idRol);
	            if(count($contactos) > 0){
	                $data['tabla'] = $this->buildTableContactosFecha($contactos);
	            }
	            $data['count'] = count($contactos);
	            
	            $data['msj'] = 'La familia se insert&oacute; correctamente en matricula';
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function contactosDireccion(){
	    $fecha = (strlen(_post("fecha")!=0)?_post("fecha"):null);
	    $tipo  = _post("tipo");
	    $newDate = null;
	    
	    $fecFormar = implode('-', array_reverse(explode('/', $fecha )));
	    $timeDiaNew = null;
	    if($tipo == 1) {//IZQUIERDA
	        $timeDiaNew = strtotime("-1 day", strtotime($fecFormar));
	        $newDate = date('d/m/Y', $timeDiaNew);
	    } else {//DERECHA
	        $timeDiaNew = strtotime("+1 day", strtotime($fecFormar));
	        $newDate = date('d/m/Y', $timeDiaNew);
	    }
	    $contactos = $this->m_evaluacion_rapido->filtrarContactosDia(null, $newDate, _getSesion("idEventoEvaluar"), $this->_idRol);
        $data['tabla'] = $this->buildTableContactosFecha($contactos);
        $data['fecha'] = $newDate;
        $days = explode(';', DIAS_SEMANA);
        $dayofweek = date('w', $timeDiaNew);
        $data['weekDay'] = $days[$dayofweek];
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function cambioRol() {
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, null);
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('id_persona');
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
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'),$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	function quitarContacto(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idContacto  = _simpleDecryptInt(_post('idcontacto'));
	        $observacion = _post('observacion');
	        if($idContacto == null){
	            throw new Exception(ANP);
	        }
	        if($this->m_evaluacion_rapido->cantDiagnosticoRealizadosEvento($idContacto, _getSesion("idEventoEvaluar")) > 0){
	            throw new Exception('No se puede eliminar, ya se ha evaluado');
	        }
	        $data = $this->m_evaluacion_rapido->deleteAgendado($idContacto, _getSesion("idEventoEvaluar"));
	        if($data['error'] == EXIT_SUCCESS){
	            $fecha = _post('fecha');
	            if(strlen($fecha) == 0){
	                $fecha = date("d/m/Y");
	            }
	            $contactos = $this->m_evaluacion_rapido->filtrarContactosDia(null, $fecha, _getSesion("idEventoEvaluar"), $this->_idRol);
	            $data['tabla'] = $this->buildTableContactosFecha($contactos);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	
	    echo json_encode(array_map('utf8_encode', $data));
	}

	function validateCantCont(){
	    $fecha    = _post('fecha');
	    $hora     = _post('hora');
 	    $cantidad = $this->m_evaluacion_rapido->cantidadContactos($fecha, $hora, _getSesion("idEventoEvaluar"));
 	    $data['msj'] = '';
 	    if($cantidad > 0){
 	        $data['msj'] = 'Hay '.$cantidad.' contactos agendados en este rango de hora';
 	    }
 	    echo json_encode(array_map('utf8_encode', $data));
	}
}