<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_evento extends CI_Controller {
    
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
        $this->load->model('mf_evento/m_detalle_evento');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_EVENTO, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['titleHeader'] = "Eventos";
	    $data['ruta_logo'] = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo'] = NAME_MODULO_ADMISION;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $eventos = array();
	    $data['permCrearEvento'] = 0;
	    
	    if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_SECRETARIA){
	        $eventos = $this->m_evento->getAllEventos(null, null, EVENTO_PENDIENTE);
	    }else if($this->_idRol == ID_ROL_SUBDIRECTOR){
	        $eventos = $this->m_evento->getAllEventosBySede(_getSesion("id_sede_trabajo"), _getSesion('nid_persona'));
	    }else{
	        $eventos = $this->m_evento->getAllEventosByPersona(_getSesion('nid_persona'));
	    }
	    
	    $data['display_not_found'] = 'none';
	    if(count($eventos) == 0){
	       $data['display_not_found'] = 'block';
	    }
	    
	    if($this->_idRol == ID_ROL_SUBDIRECTOR || $this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_SECRETARIA){
	        $data['permCrearEvento'] = 1;
	        $tours = $this->m_evento->getTourCampanaActual();
	        $data['comboTourCampanaActual'] = $this->buildComboTourCampanaActual($tours);
	    }
        
	    $data['comboTipoEventos'] = __buildComboByGrupo(COMBO_TIPO_EVENTO, null, 'orden');
	    
	    $data['tablaEventos'] = $this->createTableEventos($eventos);
	    $data['eventosCalendario'] = $this->createCalendario($eventos);
	    
	    $years = $this->m_evento->getYearsFromEventos();
	    $data['comboYearsEventos'] = $this->buildComboYearsEventos($years);
	    
	    $subDirectores = $this->m_detalle_evento->getAllSubdirectores();
	    $data['comboSubDirectores'] = $this->buildComboSubDirectores($subDirectores);
	    
	    $this->load->view('v_evento', $data);
	}
	
	function buildComboSubDirectores($subDirectores){
	    $opcion = null;
	    foreach ($subDirectores as $sDirect){
	        $opcion .= '<option value="'._simple_encrypt($sDirect->nid_sede).'">'.$sDirect->desc_sede.' ('.$sDirect->nombrecompleto.')</option>';
	    }
	    return $opcion;
	}
	
	function createTableEventos($eventos) {
	    $eventos = array_reverse($eventos);
	    $tabla     = '<table id="tree" class="tree table">';
	    $tabla    .= '<tr>
	                       <td class="text-right" style="border-top: none;"></td>
                           <td class="text-left" style="border-top: none;">Nombre</td>
	                       <td class="text-left" style="border-top: none;">Tipo</td>
	                       <td class="text-center" style="border-top: none;">Creador</td>
                           <td class="text-center" style="border-top: none;">A Realizar</td>
	                       <td class="text-center" style="border-top: none;">Colab.</td>
	                       <td class="text-center" style="border-top: none;color:#009688;">Asistir&aacute;</td>
	                       <td class="text-center" style="border-top: none;color:#F9A825;">Por conf.</td>
	                       <td class="text-center" style="border-top: none;">Prosp. Grado.</td>
	                       <td class="text-center" style="border-top: none;">Asistencia Total</td>
	                       <td class="text-center" style="border-top: none;">Estado</td>
	                       <td class="text-center" style="border-top: none;">Acci&oacute;n</td>
                     </tr>';
	    $i = 1;
	    $arrayEvento = array();
	    $array_toma_asistencia = array();
	    foreach ($eventos as $row) {
	        $idEventoEnc = _simple_encrypt($row->id_evento);
	        if(!in_array($idEventoEnc, $arrayEvento)){
	            array_push($array_toma_asistencia, $row->flg_toma_asistencia);
	        }else{
	            array_push($arrayEvento, $idEventoEnc);
	        }
	    }
	    $arrayEvento = array();
	    foreach ($eventos as $row) {
	        $idEventoEnc = _simple_encrypt($row->id_evento);
	        if(in_array($idEventoEnc, $arrayEvento)){
	            continue;
	        }else{
	            array_push($arrayEvento, $idEventoEnc);
	        }
            
	        $parent = null;
	        $color  = null;
	        if($row->id_evento_enlazado != null){
	            $parent = "treegrid-parent-$row->id_evento_enlazado";
	            $color  = 'style="background-color: #EEEEEE"'; 
	        }
	        
	        $onclickAsistencia  = null;
	        $opacity_asistencia = "0.4";
	        $onclickProgreso    = null;
	        $opacityProgreso    = "0.4";
	        if($row->fecha_realizar == date("Y-m-d") && $row->estado == EVENTO_PENDIENTE){//NO SOLO EL MISMO DIA
	            $onclickAsistencia  = 'abrirModalAsistencia(\''.$idEventoEnc.'\', '.$i.')';
	            $opacity_asistencia = "1";
	        }

	        /*if($row->estado == EVENTO_PENDIENTE){*/
	        $opacityProgreso = "1";
	        if( ($row->tipo_evento != TIPO_EVENTO_EVALUACION_SEDE && $row->tipo_evento != TIPO_EVENTO_EVALUACION_VERANO) || (($row->tipo_evento != TIPO_EVENTO_EVALUACION_SEDE && $row->tipo_evento != TIPO_EVENTO_EVALUACION_VERANO)|| $row->fecha_realizar != null) ){
	            $onclickProgreso = 'goToEvaluacion(\''.$idEventoEnc.'\', $(this))';
	        }else{
	            $onclickProgreso = 'goToEvaluacionFlash(\''.$idEventoEnc.'\', $(this))';
	        }
	        /*}   */
	        
	        $onclickEditar     = null;
	        $opacity_editar    = "0.4";
            $onclickAnular     = null;
	        $opacity_anular    = "0.4";
            $onclickFinalizar  = null;
	        $opacity_finalizar = "0.4";
            $onclickEliminar  = null;
	        $opacity_Eliminar = "0.4";
	        if($row->estado != EVENTO_REALIZADO && $row->estado != EVENTO_ANULADO){
	            $onclickAnular     = 'abrirModalConfirmAnular(\''.$idEventoEnc.'\', '.$row->cant_eventos_enlazados.')';
	            $opacity_anular    = "1";
	            $onclickEditar     = 'goToDetalleEvento(\''.$idEventoEnc.'\', \''._simple_encrypt(1).'\')';
	            $opacity_editar    = "1";
	            $onclickFinalizar  = 'abrirConfirmFinalizarEvento(\''.$idEventoEnc.'\')';
	            $opacity_finalizar = "1";
	            $onclickEliminar  = 'abrirConfirmEliminarEvento(\''.$idEventoEnc.'\', '.$row->cant_eventos_enlazados.')';
	            $opacity_Eliminar = "1";
	        }
	        
	        $btnAnular     = null;
	        $btnAsistencia = null;
	        $btnEditar     = null;
	        $btnFinalizar  = null;
	        $btnEliminar   = null;
	        $btnDarSeguimiento     = null;
	        if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR){
	            $btnAnular     = '<li class="mdl-menu__item" onclick="'.$onclickAnular.'"     style="opacity: '.$opacity_anular.'"><i class="mdi mdi-close"></i>Anular</li>';
	            $btnAsistencia = '<li class="mdl-menu__item" onclick="'.$onclickAsistencia.'" style="opacity: '.$opacity_asistencia.'"><i class="mdi mdi-check_box"></i>Asistencia</li>';
	            $btnEditar     = '<li class="mdl-menu__item" onclick="'.$onclickEditar.'"     style="opacity: '.$opacity_editar.'"><i class="mdi mdi-edit" style="margin-right: -7px"> </i>Planificar</li>';
                $btnFinalizar  = '<li style="border-top: 1px solid rgba(0,0,0,.12);" class="mdl-menu__item" onclick="'.$onclickFinalizar.'"     style="opacity: '.$opacity_finalizar.'"><i class="mdi mdi-check" style="margin-right: -7px"> </i>Finalizar</li>';
                $btnEliminar   = '<li class="mdl-menu__item" onclick="'.$onclickEliminar.'"     style="opacity: '.$opacity_Eliminar.'"><i class="mdi mdi-delete" style="margin-right: -7px"> </i>Eliminar</li>';
                $btnInvitarTour= '<li class="mdl-menu__item" onclick="" style="opacity: '.$opacity_Eliminar.'" disabled><i class="mdi mdi-group_add" style="margin-right: -7px" > </i>Invitar a...</li>';
	        }else if($this->_idRol == ID_ROL_SUBDIRECTOR && ($row->id_persona_encargada == _getSesion('nid_persona') || $row->id_persona_registro == _getSesion('nid_persona'))){
                $btnAnular     = '<li class="mdl-menu__item" onclick="'.$onclickAnular.'"     style="opacity: '.$opacity_anular.'"><i class="mdi mdi-close"></i>Anular</li>';
                $btnAsistencia = '<li class="mdl-menu__item" onclick="'.$onclickAsistencia.'" style="opacity: '.$opacity_asistencia.'"><i class="mdi mdi-check_box"></i>Asistencia</li>';
                $btnEditar     = '<li class="mdl-menu__item" onclick="'.$onclickEditar.'"     style="opacity: '.$opacity_editar.'"><i class="mdi mdi-edit" style="margin-right: -7px"> </i>Planificar</li>';
                $btnFinalizar  = '<li class="mdl-menu__item" onclick="'.$onclickFinalizar.'"     style="border-top: 1px solid rgba(0,0,0,.12); opacity: '.$opacity_finalizar.'"><i class="mdi mdi-check" style="margin-right: -7px"> </i>Finalizar</li>';
                $btnEliminar   = '<li class="mdl-menu__item" onclick="'.$onclickEliminar.'"     style="opacity: '.$opacity_Eliminar.'"><i class="mdi mdi-delete" style="margin-right: -7px"> </i>Eliminar</li>';
                $btnInvitarTour= '<li class="mdl-menu__item" onclick=""     style="opacity: '.$opacity_Eliminar.'" disabled><i class="mdi mdi-group_add" style="margin-right: -7px"> </i>Invitar a...</li>';
	        }
	        
	        if($this->_idRol == ID_ROL_SECRETARIA){
	            $btnAsistencia = '<li class="mdl-menu__item" onclick="'.$onclickAsistencia.'" style="opacity: '.$opacity_asistencia.'"><i class="mdi mdi-check_box"></i>Asistencia</li>';
	        }
	        
	        $asist = explode(",", $this->m_evento->isResponsableAsistencia(_getSesion('nid_persona'), $row->id_evento));
	        if(in_array(FLG_TOMA_ASISTENCIA, $asist) || $this->_idRol == ID_ROL_MARKETING){
	            $btnAsistencia = '<li class="mdl-menu__item" onclick="'.$onclickAsistencia.'" style="opacity: '.$opacity_asistencia.'"><i class="mdi mdi-check_box"></i>Asistencia</li>';
	        }
	        
	        $estado = '<i class="mdi mdi-query_builder"></i>'.$row->estado;
	        $label = 'pending';
	        
	        if($row->tipo_evento != TIPO_EVENTO_EVALUACION_SEDE && $row->tipo_evento != TIPO_EVENTO_EVALUACION_VERANO){
	            $estado = '<i class="mdi mdi-query_builder"></i>'.$row->estado;
	            $label = 'pending';
	            if($row->estado == EVENTO_ANULADO){
	                $label = 'canceled';
	                $estado = '<i class="mdi mdi-clear"></i>'.$row->estado;
	            }else if($row->estado == EVENTO_REALIZADO){
	                $label = 'realized';
	                $estado = '<i class="mdi mdi-done_all"></i>'.$row->estado;
	            }
	            
	            if($row->estado == EVENTO_PENDIENTE && $row->hoy == 1){
	                $label = 'pending-today';
	                $estado = '<i class="mdi mdi-check"></i>'.$row->estado;
	            }
	             
	            if($row->estado == EVENTO_PENDIENTE && $row->fecha_realizar < date("Y-m-d")){
	                $label = 'warning';
	                $estado = '<i class="mdi mdi-priority_high"></i>'.'POR FINALIZAR';
	            }
	        }else{
	            if($row->estado == EVENTO_REALIZADO){
	                $label = 'realized';
	                $estado = '<i class="mdi mdi-done_all"></i>'.$row->estado;
	            }
	        }
	        
	        if(($row->tipo_evento == TIPO_EVENTO_EVALUACION || $row->tipo_evento == TIPO_EVENTO_EVALUACION_SEDE || $row->tipo_evento == TIPO_EVENTO_EVALUACION_VERANO) && ($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_PSICOPEDAGOGO_SEDE || $this->_idRol == ID_ROL_SECRETARIA || $this->_idRol == ID_ROL_SUBDIRECTOR || $this->_idRol == ID_ROL_DOCENTE || $this->_idRol == ID_ROL_TUTOR || $this->_idRol == ID_ROL_PROFESORA_ASISTENTE || $this->_idRol == ID_ROL_ADMINISTRADOR)){
	            $btnDarSeguimiento = '<li class="mdl-menu__item" data-event="'.$row->id_evento_crypt.'" onclick="'.$onclickProgreso.'" style="opacity: '.$opacityProgreso.'"><i class="mdi mdi-timeline"></i>Evaluar</li>';
	        }
	        
	        $foto = $row->foto_persona_google;
	        if($foto == null){
	            $foto = ((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
	        }
	        
	        $tabla .='<tr class="treegrid-'.$row->id_evento.' '.$parent.'" '.$color.'>
	                       <td class="text-right"></td>
	                       <td class="text-left">'.$row->desc_evento.'</td>
	                       <td class="text-left">'.$row->tipo_evento_desc.'</td>
	                       <td class="text-center">'.'<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$foto.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->creador_completo.'('._fecha_tabla($row->fecha_registro, "d/m/Y").')"></td>
	                       <td class="text-center">'._fecha_tabla($row->fecha_realizar, "d/m/Y").'</td>
	                       <td class="text-center"><p>'.$row->cant_colab.'</p></td>
	                       <td class="text-center"><p class="link-dotted" onclick="verContactosPorOpcion(\''.$idEventoEnc.'\', \''._simple_encrypt(OPCION_ASISTIRA).'\', \'Asistir&aacute;(Total: '.$row->asistira_total.')\')">'.$row->asistira.'</p><!--button class="mdl-button mdl-js-button mdl-button--icon m-l-5" onclick="getPdf(\''._simple_encrypt(OPCION_ASISTIRA).'\', \''.$idEventoEnc.'\')"><i class="mdi mdi-picture_as_pdf"></i></button--></td>
	                       <td class="text-center"><p class="link-dotted" onclick="verContactosPorOpcion(\''.$idEventoEnc.'\', \''._simple_encrypt(OPCION_TALVEZ).'\', \'Por Confirmar (Total: '.$row->talvez_total.')\')">'.$row->talvez.'</p></td>  
	                       <td class="text-center"><p class="link-dotted" onclick="verContactosPorOpcion(\''.$idEventoEnc.'\', \''._simple_encrypt(0).'\', \'Invitados\')"><strong>'.$row->prosp_grado.'<strong></p></td>
	                       <td class="text-center"><p class="link-dotted" id="asistencia'.$i.'" onclick="verInvitadosAsistieron(\''.$idEventoEnc.'\')">'.$row->asistencia.'/'.($row->asistira+$row->talvez).'</p></td> 
	                       <td class="text-center" id="estado'.$i.'">'.'<span class="label label-'.$label.' mdl-cursor__pointer" data-toggle="modal" data-target="#modalLeyendaEvento">'.$estado.'</span>'.'</td>
	                       <td class="text-center"> 
                                <button id="evento-'.$i.'" class="mdl-button mdl-js-button mdl-button--icon">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="evento-'.$i.'">
                                    '.$btnAsistencia.'
                                    '.$btnEditar.'
                                    <li class="mdl-menu__item" onclick="goToDetalleEvento(\''.$idEventoEnc.'\', \''._simple_encrypt(0).'\')"><i class="mdi mdi-remove_red_eye"></i>Detalle</li>
                                    '.$btnDarSeguimiento.'
                                    '.$btnFinalizar.'
                                    '.$btnAnular.'  
                                    '.$btnEliminar.'                                  
                                </ul>
                           </td>
            	       </tr>';
	        $i++;
	    }
	    $tabla .= '</table>';
	    return $tabla;
	}
	
	function createCalendario($eventos){
	    $arrayGeneral = array();
	    foreach($eventos as $evento){
	        if($evento->hora_inicio != null && $evento->hora_fin != null){
	            $arrayEvento = array('title'      => utf8_encode($evento->desc_evento),
                	                 'start'      => strtotime($evento->hora_inicio).'000',
                	                 'end'        => strtotime($evento->hora_fin).'000',
                	                 'class'      => 'event-important',
                	                 'id'         => _simple_encrypt($evento->id_evento)
	            );
	            array_push($arrayGeneral, $arrayEvento);
	        }else{
	            $arrayEvento = array('title'      => utf8_encode($evento->desc_evento),
                	                 'start'      => strtotime($evento->fecha_realizar).'000',
                	                 'end'        => strtotime($evento->fecha_realizar).'000',
                	                 'class'      => 'event-important',
                	                 'id'         => _simple_encrypt($evento->id_evento)
	            );
	            array_push($arrayGeneral, $arrayEvento);
	        }
	    }
	    return json_encode($arrayGeneral);
	}
	
	function crearEvento(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $tipoEvento    = _simpleDecryptInt(_post('tipoEvento'));
	        $sedeEvento    = _simpleDecryptInt(_post('sedeEvento'));
	        $fechaEvento   = (strlen(_post('fechaEvento'))==0)?null:_post('fechaEvento');
	        $nombreEvento  = _post('nombreEvento');
	        $yearCampana   = _post('yearCampana');
	        $obsEvento     = _post('observacion');
	        $horaInicio    = _post('horaInicio');
	        $horaFin       = _post('horaFin');
	        
	        if($tipoEvento == null || $nombreEvento == null || $yearCampana == null){
	            throw new Exception("Faltan algunos campos (*)");
	        }
	        
	        if(ctype_digit($yearCampana) == false){
	            throw new Exception("Ingrese un año valido");
	        }
	        
	        if($yearCampana < date("Y")){
	            throw new Exception("Ingrese un año mayor al actual");
	        }
	        
	        if($tipoEvento == TIPO_EVENTO_TOUR || $tipoEvento == TIPO_EVENTO_EVALUACION){
	            if($fechaEvento == null){
	                throw new Exception("Faltan ingresar la fecha");
	            }
	            $date1  = DateTime::createFromFormat("d/m/Y", $fechaEvento);
	            $date2  = DateTime::createFromFormat("d/m/Y", date("d/m/Y"));
	            if($date1 < $date2){
	                throw new Exception('La fecha debe ser mayor a la actual');
	            }
	            if($horaInicio != null && $horaFin != null){
	                $valor   = date('Y-m-d H:i:s', strtotime($horaInicio));
	                $valor1  = date('Y-m-d H:i:s', strtotime($horaFin));
	                if($valor > $valor1 && $valor1 != null){
	                    throw new Exception('La hora de inicio debe ser menor a la hora fin');
	                }
	                 
	                if($valor1 < $valor && $valor != null){
	                    throw new Exception('La hora de fin debe ser mayor a la hora inicio');
	                }
	            }
	        }else{
	            if($sedeEvento == null){
	                throw new Exception("Faltan ingresar la sede donde se realizar&aacute;");
	            }
	        }
	        
	        
	        /*if($date1->format("Y") != date("Y")){
	            throw new Exception('La fecha debe ser de este año');
	        }*/
	        
	        /*if($this->_idRol != ID_ROL_SUBDIRECTOR && $this->m_evento->countEventosFecha($fechaEvento) >= 1){
	            throw new Exception('Existen eventos programados esa fecha');
	        }*/
	        
	        $tourEnlazado = null;
	        $orden = null;
	        $tourEnlazado  = _simpleDecryptInt(_post('tourEnlazado'));
	        if(($tipoEvento == TIPO_EVENTO_EVALUACION) && strlen(_post('tourEnlazado')) != 0){
	            $countEventosEnlazados = $this->m_evento->countEventosEnlazados($tourEnlazado);
	            $orden = $tourEnlazado.'.'.($countEventosEnlazados+1);
	        }
	        
	        /*if($this->_idRol == ID_ROL_SUBDIRECTOR){
	            $sedeEvento = _getSesion("id_sede_trabajo");
	        }*/
	        
	        
	        
	        /*if($campo == "hora_inicio"){
	            $valor   = date('Y-m-d H:i:s', strtotime($valor));
	            $horaFin = $this->m_evento->getHoraEvento(_getSesion('id_evento_detalle'))['hora_fin'];
	            if($valor > $horaFin && $horaFin != null){
	                throw new Exception('La hora de inicio debe ser menor a la hora fin');
	            }
	        }
	        
	        if($campo == "hora_fin"){
	            $valor   = date('Y-m-d H:i:s', strtotime($valor));
	            $horaInicio = $this->m_evento->getHoraEvento(_getSesion('id_evento_detalle'))['hora_inicio'];
	            if($valor < $horaInicio && $horaInicio != null){
	                throw new Exception('La hora de fin debe ser mayor a la hora inicio');
	            }
	        }*/
	        
	        $arrayInsert = array("fecha_realizar"      => $fechaEvento,
	                             "tipo_evento"         => $tipoEvento,
	                             "id_persona_registro" => _getSesion('nid_persona'),
	                             "observacion"         => utf8_decode($obsEvento),
	                             "desc_evento"         => utf8_decode($nombreEvento),
	                             "id_evento_enlazado"  => $tourEnlazado,
	                             "estado"              => EVENTO_PENDIENTE,
	                             "orden"               => $orden,
	                             "id_sede_realizar"    => $sedeEvento,
	                             "hora_inicio"         => date('Y-m-d H:i:s', strtotime($horaInicio)),
	                             "hora_fin"            => date('Y-m-d H:i:s', strtotime($horaFin)),
	                             "year_camp"           => $yearCampana
	        );
	        
	       $data = $this->m_evento->insertEvento($arrayInsert);
	        if($data['error'] == EXIT_SUCCESS){
	            $data['flg_tour'] = 0;
	            if($tipoEvento == TIPO_EVENTO_TOUR || $tipoEvento == TIPO_EVENTO_CHARLA){
	                $this->m_evento->updateEvento(array("orden" => $data['idEvento']), $data['idEvento']);
	                $tours = $this->m_evento->getTourCampanaActual();
	                $data['comboTourCampanaActual'] = $this->buildComboTourCampanaActual($tours);
	                $data['flg_tour'] = 1;
	            }

	            $tipoEvento  = (strlen(_post('tipoEventoFiltro')) == 0) ? null : _simpleDecryptInt(_post('tipoEventoFiltro'));
	            $yearEvento  = (strlen(_post('yearEvento')) == 0) ? null : _simpleDecryptInt(_post('yearEvento'));
	            $estadoEvento  = (strlen(_post('estado')) == 0) ? null : _post('estado');
	            $nombreEvento  = (strlen(_post('nombreEventoFiltro')) == 0) ? null : _post('nombreEventoFiltro');
	            $eventos = null;
	            if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR){
	                $eventos = $this->m_evento->getAllEventos($tipoEvento, $yearEvento, $estadoEvento, $nombreEvento);
	                if($sedeEvento != null){
	                    $infoEvento    = $this->m_evento->getDetalleEvento($data['idEvento']);
	                    $infoPersona   = $this->m_evento->getInfoSubidrectorBySede($sedeEvento);
	                    $html  = '<body style="background-color: #f3f3f3;">
                                  <div style="text-align: center;margin-bottom: 10px;">
                            		<img src="'.RUTA_SMILEDU.'public/general/img/header/grupo_educativo.png" style="width: 250px;margin-top:40px;">
                            	</div>
                            	<div style="width: 320px; height: 425px;border: 1px solid #EEEEEE; border-radius: 5px;text-align: center;margin: auto;font-family:Arial, Helvetica, sans-serif;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px
                            rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);background-color: #ffffff;">
                            		<div style="">
                            			<img src="'.RUTA_SMILEDU.'public/general/img/header/smiledu_card.png" style="width: 320px;height: 165px;border-top-left-radius: 5px;border-top-right-radius: 5px; ">
                            		</div>
                            		<div style="margin:0 20px;">
                            			<h1 style="color: #959595;font-size:19px;margin-top:25px;margin-bottom: 30px;text-align:left">Hola, '.$infoPersona['nombrecompleto'].'</h1>
                            			<div style="text-align: left;color: #BBBBBB; font-size: 18px;">
                            				<p style="font-size: 16px;">Se ha planificado un evento para su sede: <strong>'.$infoEvento['desc_evento'].'</strong> que se realizar&aacute; el d&iacute;a : '._fecha_tabla($infoEvento['fecha_realizar'], 'd/m/Y').'.<br> Para mayor informaci&oacute;n ingresa a
                                                    <a style="color:#BBBBBB;" href="http://buhooweb.com/smiledu" target="_blank"><strong>smiledu</strong></a> al m&oacute;dulo de Admisi&oacute;n.</p>
                            				<p style="font-size: 16px;">Gracias.</p>
                                  		</div>
                                  	</div>
            				    </div>
            				    <div style="text-align: center;margin: 20px auto;width: 320px;height: 100px;margin-top: 20px;">
                                      <span style="vertical-align: middle;color: #BDBDBD;font-family:Arial, Helvetica, sans-serif;">Tus consultas escribenos a <a style="color: #BDBDBD;text-decoration: none"href="mailto:soporte@smiledu.pe">soporte@smiledu.pe</a></span>
                                </div>
                            </body>';
	                    //ENVIAR CORREO AL ENCARGADO
	                    $correo = $infoPersona['correo'];
	                    if($correo != null){
	                        /*$arrayCorreo = array("correos_destino" => $correo,
	                            "asunto"          => utf8_encode("&#161;Hay un evento en su sede!"),
	                            "body"            => $html,
	                            "estado_correo"  => "PENDIENTE",
	                            "sistema"         => "ADMISION");
	                        $this->m_utils->insertarEnviarCorreo($arrayCorreo);*/
	                    }
	                }
	            }else if($this->_idRol == ID_ROL_SUBDIRECTOR){
	                $eventos = $this->m_evento->getAllEventosBySede(_getSesion("id_sede_trabajo"), _getSesion('nid_persona'), $nombreEvento, $tipoEvento, $yearEvento, $estadoEvento);
	            }else{
	                $eventos = $this->m_evento->getAllEventosByPersona(_getSesion('nid_persona'), $nombreEvento, $tipoEvento, $yearEvento, $estadoEvento);
	            }
	            $data['tablaEventos'] = $this->createTableEventos($eventos);
	            $data['count'] = count($eventos);
	            
	            
	            $data['tablaEventos'] = $this->createTableEventos($eventos);
	            $data['eventosCalendario'] = $this->createCalendario($eventos);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function cambiarTipoEvento(){
	    $tipoEvento  = _simpleDecryptInt(_post('tipoEvento'));
	    $data['res'] = 0;
	    if($tipoEvento == TIPO_EVENTO_EVALUACION){
	        $data['res'] = 1;
	    }
	    if($tipoEvento == TIPO_EVENTO_TOUR) {
	        $data['res'] = 2;
	    }
	    if($tipoEvento == TIPO_EVENTO_EVALUACION_VERANO || $tipoEvento == TIPO_EVENTO_EVALUACION_SEDE) {
	        $data['res'] = 3;
	    }else{
	        $data['res'] = 4;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getDetalleEvento(){
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $evento = $this->m_evento->getDetalleEvento($idEvento);
	    $data['nombreEvento'] = $evento['desc_evento'];
	    $data['fechaEvento']  = _fecha_tabla($evento['fecha_realizar'], "d/m/Y");
	    $data['obsEvento']    = $evento['observacion'];
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getListaAsistencia(){
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $invitados = $this->m_evento->getListaAsistencia($idEvento);
	    $data['tablaAsistencia'] = _createTableAsistenciaEvento($invitados, _post('idevento'), 0);
	    $data['count'] = count($invitados);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	function agregarALista(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idContacto = _simpleDecryptInt(_post('idcontacto'));
	        $idEvento   = _simpleDecryptInt(_post('idevento'));
	    
	        if($idContacto == null || $idEvento == null){
	            throw new Exception(ANP);
	        }
	         
	        $horaLlegada = date('Y-m-d H:i:s'); 
	        $arrayInsert = array("asistencia"             => ASISTENCIA_CONTACTO,
	                             "hora_llegada"           => $horaLlegada,
	                             "flg_asistencia_directa" => ASISTENCIA_DIRECTA_CONTACTO,
	                             "id_evento"              => $idEvento,
	                             "id_contacto"            => $idContacto
	        );
	        $data = $this->m_evento->insertContactoInvitado($arrayInsert);
	        if($data['error'] == EXIT_SUCCESS){
	            $data['countAsistencia'] = $this->m_evento->countAsistentesEvento($idEvento);
	            $invitados = $this->m_evento->getListaAsistencia($idEvento);
	            $data['tablaAsistencia'] = _createTableAsistenciaEvento($invitados, _post('idevento'), 0);
	            $busqueda = _post('nombrecontacto');
	            $invitadosOtros = $this->m_evento->getListaContactoBusqueda($busqueda, $idEvento);
	            $data['tablaAsistenciaOtros'] = _createTableAsistenciaEvento($invitadosOtros, _post('idevento'), 1);
	            $data['msj'] = "Se ha agregado a la lista de invitados";
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function anularEvento(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idEvento    = _simpleDecryptInt(_post('idevento'));
	        $observacion = _post('observacion');
	        if($idEvento == null){
	            throw new Exception(ANP);
	        }
	         
	        $arrayUpdate = array("estado"              => EVENTO_ANULADO,
	                             "observacion_anulado" => utf8_decode($observacion));
	        $data = $this->m_evento->updateEvento($arrayUpdate, $idEvento);
	        if($data['error'] == EXIT_SUCCESS){
	            $arrayUpdate = array("orden"              => null,
	                                 "id_evento_enlazado" => null);
	            $this->m_evento->updateEventosEnlazados($arrayUpdate, $idEvento);
	            $eventos = null;
	            if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR){
	                $eventos = $this->m_evento->getAllEventos();
	            }else if($this->_idRol == ID_ROL_SUBDIRECTOR){
	                $eventos = $this->m_evento->getAllEventosBySede(_getSesion("id_sede_trabajo"), _getSesion('nid_persona'));
	            }
	            $data['tablaEventos'] = $this->createTableEventos($eventos);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buscarContactoAsistencia(){
	    $busqueda = _post('nombrecontacto');
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $tipoEvento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idEvento);
	    if($tipoEvento == TIPO_EVENTO_EVALUACION || $tipoEvento == TIPO_EVENTO_EVALUACION_VERANO){
	        $invitadosOtros = $this->m_evento->getListaContactoBusqueda($busqueda, $idEvento);
	        $data['tablaAsistenciaOtros'] = _createTableAsistenciaEventoDRA($invitadosOtros, _post('idevento'), 1);
	        $data['count'] = count($invitadosOtros);
	    }else{
	        $invitadosOtros = $this->m_evento->getListaContactoBusqueda($busqueda, $idEvento);
	        $data['tablaAsistenciaOtros'] = _createTableAsistenciaEventoTour($invitadosOtros, _post('idevento'), 1);
	        $data['count'] = count($invitadosOtros);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function contactosEventoOpcion(){
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $opcion   = _simpleDecryptInt(_post('opcion'));
	    $invitados = array();
	    if($opcion == 0){
	        $invitados = $this->m_evento->getPostulantesGrados($idEvento);
	        $data['invitados'] = _createTableInvitadosGrados($invitados);
	        $data['count'] = count($invitados);
	    }else{
	        $invitadosOpcion = $this->m_evento->getListaInvitadosOpcion($idEvento, $opcion);
	        $data['invitados'] = _createTableInvitadosOpcion($invitadosOpcion);
	        $data['count'] = count($invitadosOpcion);
	    }
        $data['opc'] = $opcion;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function invitadosAsistieron(){
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $personas = '';
	    $invitadosAsistieron = $this->m_evento->getListaInvitadosAsistieron($idEvento, $personas);
	    $tipoEvento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idEvento);
	    $data['invitadosAsistieron'] = _createTableInvitadosAsistieron($invitadosAsistieron, $idEvento, $tipoEvento);  
	    $data['count'] = count($invitadosAsistieron);
	    echo json_encode(array_map('utf8_encode', $data));
	}

	
	function getEventosDraInvitar(){
	    $idEvento   = _simple_decrypt(_post("evento"));
	    $idContacto = _simple_decrypt(_post("contacto"));
	    $correo = $this->m_utils->getById("admision.contacto", "correo", "id_contacto", $idContacto);
	    $evals = $this->m_evento->getEvaluacionesPendientes($idEvento);
	    $data['evaluaciones'] = $this->buildComboTourCampanaActual($evals);
	    $data['correo']       = $correo;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getHorariosEventos(){
	    $idEvento = _simple_decrypt(_post("evento"));
	    $horarios = $this->m_evento->getHorariosByEvento($idEvento);
	    $data['horarios'] = $this->createComboHorariosEvento($horarios);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function createComboHorariosEvento($horarios){
	    $opcion = null;
	    foreach ($horarios as $var){
	        $opcion .= '<option value="'._simple_encrypt($var->correlativo).'">'.strtoupper($var->desc_hora_cita).' ('._fecha_tabla($var->hora_cita, 'h:i A').')</option>';
	    }
	    return $opcion;
	}
	
	function invitarContactoDra(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idEvento = _simpleDecryptInt(_post('evento'));
	        $contacto = _simpleDecryptInt(_post('contacto'));
	        $opcion   = _simpleDecryptInt(_post('opcion'));
	        $horario  = _simpleDecryptInt(_post('horario'));
	        $correo   = _post("correo");

	        $correoEnvio = $this->m_utils->getById("admision.contacto", "correo", "id_contacto", $contacto);
	        if($correoEnvio == null && $correo == null){
	            throw new Exception("Ingrese un correo de destino");
	        }
	        if($idEvento == null || $contacto == null || $opcion == null){
	            throw new Exception(ANP);
	        }
	        if($this->m_evento->validateContactoInvitacionEvento($idEvento, $contacto) > 0){
	            throw new Exception("Este contacto ya est&aacute; invitado al evento seleccionado");
	        }
	        $arrayInsert = array("id_evento"   => $idEvento,
	                             "id_contacto" => $contacto,
	                             "opcion"      => $opcion,
	                             "asistencia"  => INASISTENCIA_CONTACTO,
	                             "flg_asistencia_directa" => ASISTENCIA_INVITACION_CONTACTO,
	                             "id_hora_cita"  => $horario
	        );
	        $data = $this->m_detalle_evento->insertInvitacionEvento($arrayInsert);
	        if($data['error'] == EXIT_SUCCESS){
	            if(strlen($correo) != 0){
	                $arrayUpdate = array(correo => $correo);
	                $data = $this->m_contactos->updateContacto($arrayUpdate, $contacto);
	            }
	            $tipo = $this->m_utils->getById("admision.contacto", "flg_estudiante", "id_contacto", $contacto);
	            if($tipo == FLG_FAMILIAR){
	                $cod_grupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $contacto);
	                $html  = '<body style="background-color: #f3f3f3;">
                              <div style="text-align: center;margin-bottom: 10px;">
                        		<img src="'.RUTA_SMILEDU.'public/general/img/header/grupo_educativo.png" style="width: 250px;margin-top:40px;">
                        	</div>
                        	<div style="width: 320px; height: 470px;border: 1px solid #EEEEEE; border-radius: 5px;text-align: center;margin: auto;font-family:Arial, Helvetica, sans-serif;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px
                        rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);background-color: #ffffff;">
                        		<div style="">
                        			<img src="'.RUTA_SMILEDU.'public/general/img/header/smiledu_card.png" style="width: 320px;height: 165px;border-top-left-radius: 5px;border-top-right-radius: 5px; ">
                        		</div>
                        		<div style="margin:0 20px;">
                        			<h1 style="color: #959595;font-size:19px;margin-top:25px;margin-bottom: 30px;text-align:left">Hola, '.$this->m_evento->nombreCompletoContacto($contacto).'</h1>
                        			<div style="text-align: left;color: #BBBBBB; font-size: 18px;">
                        				<p style="font-size: 16px">Gracias por participar del evento y confirmar tu presencia en el diagn&oacute;stico de rendimiento ( '._fecha_tabla($this->m_utils->getById("admision.evento", "fecha_realizar", "id_evento", $idEvento), 'd/m/Y').' ). Para ti y nosotros es muy importante completar todos tus datos, esto te ayudar&aacute; a agilizar todo el proceso y a nuestros docentes a mejorar el diagn&oacute;tico.
                                               </p>
                              		</div>
                				    <div style="">
                        				 <a href="'.RUTA_SMILEDU.'admision/C_confirm_datos_bypass?grupodatos='._simple_encrypt($cod_grupo).'" style="height: 36px;border-radius: 2px;border: none;min-width: 64px;padding: 8px 16px;outline: none;cursor: pointer;color: #fff;background-color: #FF5722;box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);text-decoration:none">
                                            IR A FORMULARIO
                    				    </a>
                				    </div
                              	</div>
        				    </div>
        				    <div style="text-align: center;margin: 20px auto;width: 320px;height: 100px;margin-top: 20px;">
                                  <span style="vertical-align: middle;color: #BDBDBD;font-family:Arial, Helvetica, sans-serif;">Tus consultas escribenos a <a style="color: #BDBDBD;text-decoration: none"href="mailto:soporte@smiledu.pe">soporte@smiledu.pe</a></span>
                            </div>
                        </body>';
	                $correoEnvio = $this->m_utils->getById("admision.contacto", "correo", "id_contacto", $contacto);
	                if($correoEnvio != null){
	                    $arrayCorreo = array("correos_destino" => $correoEnvio,
                	                         "asunto"          => utf8_encode("Formulario de admision - Avantgard"),
                	                         "body"            => $html,
                	                         "estado_correo"   => "PENDIENTE",
                	                         "sistema"         => "ADMISION");
	                    $this->m_utils->insertarEnviarCorreo($arrayCorreo);
	                }
	            }
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function filtrarEventos(){
	    $tipoEvento  = (strlen(_post('tipoEvento')) == 0) ? null : _simpleDecryptInt(_post('tipoEvento'));
	    $yearEvento  = (strlen(_post('yearEvento')) == 0) ? null : _simpleDecryptInt(_post('yearEvento'));
	    $estadoEvento  = (strlen(_post('estado')) == 0) ? null : _post('estado');
		$nombreEvento  = (strlen(_post('nombreEvento')) == 0) ? null : _post('nombreEvento');
	    $eventos = null;
	    if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR){
	        $eventos = $this->m_evento->getAllEventos($tipoEvento, $yearEvento, $estadoEvento, $nombreEvento);
	    }else if($this->_idRol == ID_ROL_SUBDIRECTOR){
	        $eventos = $this->m_evento->getAllEventosBySede(_getSesion("id_sede_trabajo"), _getSesion('nid_persona'), $tipoEvento, $yearEvento, $estadoEvento, $nombreEvento);
	    }else{
	        $eventos = $this->m_evento->getAllEventosByPersona(_getSesion('nid_persona'), $tipoEvento, $yearEvento, $estadoEvento, $nombreEvento);
	    }
	    $data['tablaEventos'] = $this->createTableEventos($eventos);
	    $data['eventosCalendario'] = $this->createCalendario($eventos);
	    $data['count'] = count($eventos);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildComboTourCampanaActual($tours){
	    $opcion = null;
	    foreach ($tours as $tour){
	        $opcion .= '<option value="'._simple_encrypt($tour->id_evento).'">'.strtoupper($tour->desc_evento).' ('.date_format(date_create($tour->fecha_realizar), 'd/m/Y').')</option>';
	    }
	    return $opcion;
	}
	
	function buildComboYearsEventos($years){
	    $opcion = null;
	    foreach ($years as $year){
	        $opcion .= '<option value="'._simple_encrypt($year->year).'">'.$year->year.'</option>';
	    }
	    return $opcion;
	}
	
	function goToDetalleEvento(){
	    $idEvento = _simpleDecryptInt(_post('idevento'));
	    $opcion   = _simpleDecryptInt(_post('opciondetalle'));
	    $dataUser = array("id_evento_detalle"   => $idEvento,
	                      "accionDetalleEvento" => $opcion,
	                      "tipo_evento_detalle" => $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idEvento));
	    $this->session->set_userdata($dataUser);
	}  
    function finalizarEvento(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idevento = _post('idevento') != null  ? _simpleDecryptInt(_post('idevento')) : null;
            $arrayUpdate = array("estado" => EVENTO_REALIZADO);
            $data = $this->m_evento->updateEvento($arrayUpdate, $idevento);
            if($data['error'] == EXIT_SUCCESS){
                $participantesEvento = $this->m_detalle_evento->getParticipantesEvento($idevento);
                $infoEvento    = $this->m_evento->getDetalleEvento($idevento);
                
                foreach ($participantesEvento as $row){
                    $html  = '<body style="background-color: #f3f3f3;">
                                  <div style="text-align: center;margin-bottom: 10px;">
                            		<img src="'.RUTA_SMILEDU.'public/general/img/header/grupo_educativo.png" style="width: 250px;margin-top:40px;">
                            	</div>
                            	<div style="width: 320px; height: 380px;border: 1px solid #EEEEEE; border-radius: 5px;text-align: center;margin: auto;font-family:Arial, Helvetica, sans-serif;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px
                            rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);background-color: #ffffff;">
                            		<div style="">
                            			<img src="'.RUTA_SMILEDU.'public/general/img/header/smiledu_card.png" style="width: 320px;height: 165px;border-top-left-radius: 5px;border-top-right-radius: 5px; ">
                            		</div>
                            		<div style="margin:0 20px;">
                            			<h1 style="color: #959595;font-size:19px;margin-top:25px;margin-bottom: 30px;text-align:left">Hola, '.$row->nombrecompleto.'</h1>';
                    if($row->asistencia == ASISTENCIA_APOYO_ADM){
                        $html .= '<div style="text-align: left;color: #BBBBBB; font-size: 18px;">
                        				<p style="font-size: 16px;">Muchas grqacias por participàr en el evento: '.$infoEvento['desc_evento'].' que se realiz&oacute; el d&iacute;a : '._fecha_tabla($infoEvento['fecha_realizar'], 'd/m/Y').' Para mayor informaci&oacute;n ingresa a
                                                <a style="color:#BBBBBB;" href="http://buhooweb.com/smiledu" target="_blank"><strong>smiledu</strong></a> al m&oacute;dulo de Admisi&oacute;n.
                    				    </p>
            				     </div>
                                <div>';
                    }else{
                        $html .= '<p style="color: #BBBBBB; font-size: 16px;font-family:Arial, Helvetica, sans-serif;">Sabemos que no fuistes al evento</p>';
                    }
                    $html .= '</div>
            				    </div>
            				    <div style="text-align: center;margin: 20px auto;width: 320px;height: 100px;margin-top: 20px;">
                                      <span style="vertical-align: middle;color: #BDBDBD;font-family:Arial, Helvetica, sans-serif;">Tus consultas escribenos a <a style="color: #BDBDBD;text-decoration: none"href="mailto:soporte@smiledu.pe">soporte@smiledu.pe</a></span>
                                </div>
                        </body>';
                    //ENVIAR CORREO AL ENCARGADO
                    //$dataCorreo = __enviarEmail("rikardo308@gmail.com", utf8_encode("¡Se ha finalizado el evento!"), $html);
                }
                $eventos = null;
                if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR){
                    $eventos = $this->m_evento->getAllEventos();
                }else if($this->_idRol == ID_ROL_SUBDIRECTOR){
                    $eventos = $this->m_evento->getAllEventosBySede(_getSesion("id_sede_trabajo"), _getSesion('nid_persona'));
                }
                $data['tablaEventos'] = $this->createTableEventos($eventos);
                
                /*$dataUser = array("accionDetalleEvento" => 0);
                $this->session->set_userdata($dataUser);*/
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function downloadPdf(){
        $idEvento = _simpleDecryptInt(_post("idevento"));
        $tipoEvento = _simpleDecryptInt(_post("tipo"));
        if($idEvento != null && $tipoEvento != null){
            $this->load->library('m_pdf');
            $nomFile     = __generateRandomString(8);
            $file        = "uploads/modulos/admision/documentos/".$nomFile.".pdf";
            $pdf         = $this->m_pdf->load('','A4', 0, '', 15, 15, 16, 16, 9, 9, 'L');
            $data = $this->m_evento->getHijosByTipo($tipoEvento, $idEvento);
            $html = $this->generarTablaHTMLReporteInvitados($data);
             
            $pdf->SetFooter("Invitados".'|{PAGENO}|'.date('d/m/Y h:i:s a'));
            $pdf->WriteHTML(utf8_encode('<p style="margin-left:300px;margin-top:-50px;text-decoration: underline;font-size:15px">Invitados</p><br/><br/>'.
                $html));
            $pdf->Output("./".$file, 'F');
            echo RUTA_SMILEDU.$file;
        }
    }
    
    //NO LO ELIMINA PORQUE NO ENCUENTRA EL ARCHIVO
    function borrarPDF(){
        $imagen = $this->input->post('ruta');
        if(file_exists($imagen)) {
            $imagen = './'.$this->input->post('ruta');
            if (!unlink($imagen)){
                echo ("No se borra el archivo $imagen");
            }else{
                echo ("Se borro $imagen");
            }
        }
        echo null;
    }
    
    function generarTablaHTMLReporteInvitados($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre Completo</FONT>'      ,'style' => $left);
        $head_1_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nivel</FONT>'      ,'style' => $left);
        $head_1_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Grado</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Asistencia</FONT>'       ,'style' => $left);
        $head_2_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Observación</FONT>'       ,'style' => $left);
    
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_1_2, $head_2, $head_2_1);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col1_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_nivel_ingreso.'</FONT>');
            $row_col1_2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_grado_ingreso.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>    </FONT>');
            $row_col2_1  = array('data' => '<FONT FACE="Arial" SIZE=2>   </FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col1_1,$row_col1_2,$row_col2,$row_col2_1);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
    
    function contactosBusquedaLista(){
        $data = null;
        $busqueda = _post("busqueda");
        $idEvento = _simpleDecryptInt(_post("evento"));
        $tipoEvento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idEvento);
        if($tipoEvento == TIPO_EVENTO_EVALUACION || $tipoEvento == TIPO_EVENTO_EVALUACION_VERANO){
            $invitados = $this->m_evento->getListaAsistenciaDRA($idEvento, $busqueda);
            $data['tabla'] = _createTableAsistenciaEventoDRA($invitados, _post('evento'), 0);
            $data['count'] = count($invitados);
        }else{
            $invitados = $this->m_evento->getListaAsistenciaTour($idEvento, $busqueda);
            $data['tabla'] = _createTableAsistenciaEventoTour($invitados, _post('evento'), 0);
            $data['count'] = count($invitados);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function eliminarEvento(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idEvento = _simpleDecryptInt(_post('idevento'));
            if($idEvento == null){
                throw new Exception(ANP);
            }
            if($this->m_evento->countInvitadorEvento($idEvento) != 0){
                throw new Exception("Ya hay contactos invitados al evento");
            }
            if($this->m_evento->countConfiguracion($idEvento) != 0){
                throw new Exception("Ya hay una configuración hecha en el evento");
            }
            $data = $this->m_evento->deleteEvento($idEvento);
            if($data['error'] == EXIT_SUCCESS){
                $arrayUpdate = array("orden"              => null,
                                     "id_evento_enlazado" => null);
                $this->m_evento->updateEventosEnlazados($arrayUpdate, $idEvento);
                $tipoEvento  = (strlen(_post('tipoEvento')) == 0) ? null : _simpleDecryptInt(_post('tipoEvento'));
                $yearEvento  = (strlen(_post('yearEvento')) == 0) ? null : _simpleDecryptInt(_post('yearEvento'));
                $estadoEvento  = (strlen(_post('estado')) == 0) ? null : _post('estado');
                $nombreEvento  = (strlen(_post('nombreEvento')) == 0) ? null : _post('nombreEvento');
                $eventos = null;
                if($this->_idRol == ID_ROL_MARKETING || $this->_idRol == ID_ROL_PROMOTOR){
                    $eventos = $this->m_evento->getAllEventos($tipoEvento, $yearEvento, $estadoEvento, $nombreEvento);
                }else if($this->_idRol == ID_ROL_SUBDIRECTOR){
                    $eventos = $this->m_evento->getAllEventosBySede(_getSesion("id_sede_trabajo"), _getSesion('nid_persona'), $nombreEvento, $tipoEvento, $yearEvento, $estadoEvento);
                }else{
                    $eventos = $this->m_evento->getAllEventosByPersona(_getSesion('nid_persona'), $nombreEvento, $tipoEvento, $yearEvento, $estadoEvento);
                }
                $data['tablaEventos'] = $this->createTableEventos($eventos);
                $data['count'] = count($eventos); 
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function traerAsistenciaFamiliaDRA(){
        $idContacto = _simpleDecryptInt(_post("contacto"));
        $idEvento   = _simpleDecryptInt(_post("evento"));
        $grupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContacto);
        $familiares = $this->m_evento->getFamiliaAsistenciaEvento($idEvento, $grupo);
        $data['tablaFamilia'] = _creteTableAsistenciaFamilia($familiares, $idEvento, 1);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarAsistenciaDRA(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idEvento    = _simpleDecryptInt(_post('evento'));
            if($idEvento == null){
                throw new Exception(ANP);
            }
            $myPostData = json_decode(_post('contactos'), TRUE);
            $countPost = 0;
            $countPart = 0;
            foreach($myPostData['asistencia'] as $key => $asistencia) {
                if($asistencia['val'] == ASISTENCIA_CONTACTO){
                    if(_simpleDecryptInt($asistencia['tipo']) == FLG_ESTUDIANTE){
                        $countPost++;
                    }else{
                        $countPart++;
                    }
                }
            }
            $tipoEvento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idEvento);
            if($tipoEvento == TIPO_EVENTO_EVALUACION || $tipoEvento == TIPO_EVENTO_EVALUACION_VERANO){
                if(($countPart+$countPost) > 0){
                    if($countPart == 0 || $countPost == 0){
                        throw new Exception("Debe haber como m&iacute;nimo 1 pariente y 1 postulante");
                    }
                }
            }else{
                if($countPart == 0){
                    throw new Exception("Debe haber como m&iacute;nimo 1 pariente");
                }
            }
            
            foreach($myPostData['asistencia'] as $key => $asistencia) {
                $horaLlegada = null;
                if($asistencia['val'] == ASISTENCIA_CONTACTO){
                    $horaLlegada = date('Y-m-d H:i:s');
                }
                $arrayUpdate = array("asistencia"   => $asistencia['val'],
                                     "hora_llegada" => $horaLlegada);
                $idContacto = _simpleDecryptInt($asistencia['cont']);
                $data = $this->m_evento->updateAsistenciaInvitado($idContacto, $idEvento, $arrayUpdate);
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['countAsistencia'] = $this->m_evento->countAsistentesEvento($idEvento);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function traerFamiliaDRA(){
        $idContacto = _simpleDecryptInt(_post("contacto"));
        $idEvento   = _simpleDecryptInt(_post("evento"));
        $grupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContacto);
        $familiares = $this->m_evento->getFamiliaEvento($idEvento, $grupo);
        $data['tablaFamilia'] = _creteTableAsistenciaFamilia($familiares, $idEvento, 1);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarAsistenciaPasarDRA(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idEvento    = _simpleDecryptInt(_post('evento'));
            if($idEvento == null){
                throw new Exception(ANP);
            }
            $myPostData = json_decode(_post('contactos'), TRUE);
            $countPost = 0;
            $countPart = 0;
            $idContactoAux = null;
            foreach($myPostData['asistencia'] as $key => $asistencia) {
                $idContactoAux = _simpleDecryptInt($asistencia['cont']);
                if($asistencia['val'] == ASISTENCIA_CONTACTO){
                    if(_simpleDecryptInt($asistencia['tipo']) == FLG_ESTUDIANTE){
                        $countPost++;
                    }else{
                        $countPart++;
                    }
                }
            }
            $cod_grupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContactoAux);
            $validate = $this->m_evento->validateCantParPostEvento($cod_grupo, $idEvento);
            if($validate['count_post'] > 0 && $validate['count_par'] > 0){
                if(($countPart+$countPost) > 0){
                    foreach($myPostData['asistencia'] as $key => $asistencia) {
                        $horaLlegada = null;
                        if($asistencia['val'] == ASISTENCIA_CONTACTO){
                            $horaLlegada = date('Y-m-d H:i:s');
                            $idContacto = _simpleDecryptInt($asistencia['cont']);
                            $arrayInsert = array("asistencia"             => ASISTENCIA_CONTACTO,
                                "hora_llegada"           => $horaLlegada,
                                "flg_asistencia_directa" => ASISTENCIA_DIRECTA_CONTACTO,
                                "id_evento"              => $idEvento,
                                "id_contacto"            => $idContacto
                            );
                            $data = $this->m_evento->insertContactoInvitado($arrayInsert);
                        }
                    }
                }else{
                    throw new Exception("Elija un contacto como m&iacute;nimo");
                }
            }else{
                if(($countPart+$countPost) > 0){
                    if($countPart == 0 || $countPost == 0){
                        throw new Exception("Debe haber como m&iacute;nimo 1 pariente y 1 postulante");
                    }
                    foreach($myPostData['asistencia'] as $key => $asistencia) {
                        $horaLlegada = null;
                        if($asistencia['val'] == ASISTENCIA_CONTACTO){
                            $horaLlegada = date('Y-m-d H:i:s');
                            $idContacto = _simpleDecryptInt($asistencia['cont']);
                            $arrayInsert = array("asistencia"             => ASISTENCIA_CONTACTO,
                                "hora_llegada"           => $horaLlegada,
                                "flg_asistencia_directa" => ASISTENCIA_DIRECTA_CONTACTO,
                                "id_evento"              => $idEvento,
                                "id_contacto"            => $idContacto
                            );
                            $data = $this->m_evento->insertContactoInvitado($arrayInsert);
                        }
                    }
                }else{
                    throw new Exception("Elija un contacto como m&iacute;nimo");
                }
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['countAsistencia'] = $this->m_evento->countAsistentesEvento($idEvento);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function cambioRol() {
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
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
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
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
	
	function goToViewProgreso(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        /*if($this->m_evento->validateconfig_eval_completo() == false){
	           throw new Exception("Falta configurar todos los cursos ingresados");
    	    }*/
    	    $idEvento = (_post('idevento'))!=null ? _simpleDecryptInt(_post('idevento')) : null;
    	    $idEvent  = _post('idEvent');
    	    $dataUser = array('idEventoProgreso' => $idEvento,
    	                      'idEvent'          => $idEvent);
    	    _setSesion($dataUser);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function goToEvaluar(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idEvento = (_post('idevento'))!=null ? _simpleDecryptInt(_post('idevento')) : null;
	        $idEvent  = _post('idEvent');
	        $dataUser = array('idEventoEvaluar' => $idEvento,
	            'idEvent'          => $idEvent);
	        _setSesion($dataUser);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAllAsistentes() {
	    $idEvento = _simpleDecryptInt(_post("evento"));
	    $searchMagic                 = utf8_decode(trim(_post('filtro')));
	    $invitadosAsistieron         = $this->m_evento->getListaInvitadosAsistieron($idEvento, $searchMagic);
	    $tipoEvento                  = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idEvento);
	    $data['invitadosAsistieron'] = _createTableInvitadosAsistieron($invitadosAsistieron, $idEvento, $tipoEvento);
	    $data['datos']               = count($invitadosAsistieron);
	    echo json_encode(array_map('utf8_encode', $data));
	}
}