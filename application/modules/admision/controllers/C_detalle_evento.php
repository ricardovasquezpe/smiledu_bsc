<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_detalle_evento extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('m_utils_admision');
        $this->load->model('mf_evento/m_evento');
        $this->load->model('mf_contactos/m_contactos');
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
	    $data['return'] = '';
	    $data['titleHeader']      = "Planificar Evento";
	    $data['ruta_logo']        = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo']      = NAME_MODULO_ADMISION;
        $data['barraSec']         = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                            <a href="#tab-1" class="mdl-layout__tab is-active" onclick="clickTabMenu(2)">Informaci&oacute;n</a>
                                            <a href="#tab-3" class="mdl-layout__tab" onclick="clickTabMenu(1)">Apoyo administrativo</a>
                                            <a href="#tab-4" class="mdl-layout__tab" onclick="clickTabMenu(0)">Recursos materiales</a>
                                     </div>';
        $menu         = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        
        //OPCIONES LISTA
        $data['opcAsistira']   = _simple_encrypt(OPCION_ASISTIRA);
        $data['opcTalvez']     = _simple_encrypt(OPCION_TALVEZ);
        $data['opcNoAsistira'] = _simple_encrypt(OPCION_NO_ASISTIRA);
        
        $data['comboEventos']     = __buildComboEventosFuturos();
        $data['comboSeguimiento'] = __buildComboByGrupo(COMBO_SEGUIMIENTO);

        //COMBO SEDES
        $data['comboSedes']           = __buildComboSedes(1);
        //COMBO RECURSOS MATERIALES
        $data['comboRecursoMaterial'] = __buildComboByGrupo(COMBO_RECURSOS_MATERIALES);
        //COMBO APOYO ADM
        $apoyoAdm = $this->m_detalle_evento->getAllApoyoAdministrativo();
        $data['comboApoyoAdministrativo'] = $this->buildComboApoyoAdm($apoyoAdm);
        //COMBO ENCARGADOS
        $encargados = $this->m_detalle_evento->getAllEncargados();
        $data['encargadosRecursos'] = $this->buildComboEncargados($encargados);
        //TABLA RECURSOS MATERIALES
        $recursosMateriales = $this->m_detalle_evento->getRecursosMaterialesByEvento(_getSesion('id_evento_detalle'));
        $data['tablaRecursosMateriales'] = _createTableRecursosMateriales($recursosMateriales);
        
        
        if(_getSesion('tipo_evento_detalle') == TIPO_EVENTO_TOUR || _getSesion('tipo_evento_detalle') == TIPO_EVENTO_CHARLA || _getSesion('tipo_evento_detalle') == TIPO_EVENTO_EVALUACION){
            $recursosHumanos = $this->m_detalle_evento->getApoyoAdministrativoByEvento(_getSesion('id_evento_detalle'));
            if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING){
                $data['tablaRecursosHumanos'] = _createTableRecursosHumanos($recursosHumanos, null);
            }else if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR){
                $data['tablaRecursosHumanos'] = _createTableRecursosHumanos($recursosHumanos, _getSesion("id_sede_trabajo"));
            }else{
                $data['tablaRecursosHumanos'] = _createTableRecursosHumanos($recursosHumanos, null);
            }
        }else{
            $personas = $this->m_detalle_evento->getPersonasApoyoAdmSede(_getSesion('id_evento_detalle'));
            $data['tablaRecursosHumanosSede'] = _createTableRecursosHumanosSede($personas);
        }
        
        //INFORMACI�N
        $detalleEvento = $this->m_evento->getDetalleEvento(_getSesion('id_evento_detalle'));
        $data['nombreEvento']     = $detalleEvento['desc_evento'];
        $data['fechaEvento']      = _fecha_tabla($detalleEvento['fecha_realizar'], 'd/m/Y');
        $data['horaInicio']       = _fecha_tabla($detalleEvento['hora_inicio'], 'h:i a');
        $data['horaFin']          = _fecha_tabla($detalleEvento['hora_fin'], 'h:i a');
        $data['personaEncargada'] = (strlen($detalleEvento['id_persona_encargada']) != 0) ? _simple_encrypt($detalleEvento['id_persona_encargada']) : null;
        $data['observEvento']     = $detalleEvento['observacion'];
        $data['enc']   = _simple_encrypt(1);
        $data['noEnc'] = _simple_encrypt(0);
        $subDirectores = $this->m_detalle_evento->getAllSubdirectores();
        $data['comboSubDirectores'] = $this->buildComboSubDirectores($subDirectores);

        if(_getSesion('tipo_evento_detalle') == TIPO_EVENTO_TOUR || _getSesion('tipo_evento_detalle') == TIPO_EVENTO_CHARLA){
            $sedesRutaEvento = $this->m_detalle_evento->getSedesRutaEvento(_getSesion('id_evento_detalle'));
            $data['tablaRutas'] = _createTableSedesRuta($sedesRutaEvento);
        } else if(_getSesion('tipo_evento_detalle') == TIPO_EVENTO_EVALUACION){
            $horaCitaEvento = $this->m_detalle_evento->getHorasCitaEvento(_getSesion('id_evento_detalle'));
            $data['tablaHoras'] = _createTableHorasCita($horaCitaEvento);
            $horarios = $this->m_detalle_evento->getHorasCitaEvento(_getSesion('id_evento_detalle'));
            $data['comboHorario'] = __buildComboHorarios($horarios);
            $data['comboHorariosInvitar'] = '<select class="form-control selectButton" id="selectHorarioInvitar" name="selectHorarioInvitar" data-live-search="true" data-container="body">
                    			                <option value="">Selecciona un horario</option>
                                                '.$data['comboHorario'].'
                    			             </select>';
        }
        
        //BOTONES Y CAMPOS
        if(_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_VER && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SECRETARIA){
            $data['btnAgregarHorario'] = '<div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="abrirModalCrearHorarioEvaluacion()">
                                                     <i class="mdi mdi-add"></i>
                                                </button>
                                             </div>';
        }
        if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $this->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
            || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR
            || (_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $this->m_utils->getById("admision.evento", "id_persona_encargada", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
            ){
            $data['btnAgregarHorario'] = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon text-right" style="position: absolute;right: 16px;margin: 0;" onclick="abrirModalCrearHorarioEvaluacion()">
                                              <i class="mdi mdi-add"></i>
                                          </button>';
        
            $data['fabAsignarApoyoAdm'] = '<button class="mfb-component__button--main">
                                               <i class="mfb-component__child-icon mdi mdi-group_add"></i>
                                           </button>
                                           <button class="mfb-component__button--main" onclick="abrirModalAsignarApoyoAdministrativo()" data-mfb-label="Asignar apoyo administrativo">
                                               <i class="mfb-component__child-icon mdi mdi-group_add"></i>
                                           </button>';
            $data['btnAsignarRecursoMaterial'] = '<button class="mfb-component__button--main" onclick="abrirModalAsignarRecursoMaterial()" data-mfb-label="Asignar Material">
                                                      <i class="mfb-component__main-icon--active mdi mdi-build"></i>
                                                  </button>';
            $data['btnFab'] = 1;
            $data['btnCrearRecurso'] = '<button class="mfb-component__button--child" data-mfb-label="Crear Material" onclick="abrirModalCrearRecurso()">
                                            <i class="mfb-component__child-icon mdi mdi-edit"></i>
                                        </button>';
            
            $opc = '<li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''.$data['opcAsistira'].'\', 1)">Asistir&aacute;</li>
                    <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''.$data['opcTalvez'].'\', 1)">Por confirmar</li>
                    <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''.$data['opcNoAsistira'].'\', 0)">No asistir&aacute;</li>';
            
            $familias = $this->m_detalle_evento->getFamiliasByOpcion(_getSesion('id_evento_detalle'), OPCION_ASISTIRA);
            $contFam = null;
            if(count($familias) != 0) {
                $contFam = _createCardFamilias($familias, $opc, ID_PERMISO_EVENTO, 'editar')['cardsFamilia'];
            }
            
            $data['filterEstadosContacto']  = '<li class="licontactos active"><a href="#tab-asistira"    data-toggle="tab" onclick="familiasPorOpcion(\''.$data['opcAsistira'].'\', \'cont_cards_familia2\')">Asistir&aacute;n (<span style="display:inline-block" id="cont_invitados_asistiran">'.$this->m_detalle_evento->countContactosEventoTipo_1(_getSesion('id_evento_detalle'), OPCION_ASISTIRA).'</span>)</a></li>
                                               <li class="licontactos"><a href="#tab-talvez"      data-toggle="tab" onclick="familiasPorOpcion(\''.$data['opcTalvez'].'\', \'cont_cards_familia3\')">Por confirmar      (<span style="display:inline-block" id="cont_invitados_talvez">'.$this->m_detalle_evento->countContactosEventoTipo_1(_getSesion('id_evento_detalle'), OPCION_TALVEZ).'</span>)</a></li>
                                               <li class="licontactos"><a href="#tab-no-asistira" data-toggle="tab" onclick="familiasPorOpcion(\''.$data['opcNoAsistira'].'\', \'cont_cards_familia4\')">No Asistir�n   (<span style="display:inline-block" id="cont_invitados_no_asistiran">'.$this->m_detalle_evento->countContactosEventoTipo_1(_getSesion('id_evento_detalle'), OPCION_NO_ASISTIRA).'</span>)</a></li>';
            
            $data['tabContEstadosContacto'] = '<div class="tab-pane active" id="tab-asistira">
                                                   <div id="cont_cards_familia2" class="mdl-content-cards"> '.$contFam.' </div>
                                               </div>
                                               <div class="tab-pane" id="tab-talvez">
                                                   <div id="cont_cards_familia3" class="mdl-content-cards"></div>
                                               </div>
                                               <div class="tab-pane" id="tab-no-asistira">
                                                   <div id="cont_cards_familia4" class="mdl-content-cards"></div>
                                               </div>';
            $data['disabled'] = '';
        }else{
            $familias = $this->m_detalle_evento->getFamiliasByOpcion(_getSesion('id_evento_detalle'), OPCION_ASISTIRA);
            $contFam = null;
            if(count($familias) > 0){
                $contFam = _createCardFamilias($familias, null, ID_PERMISO_EVENTO, 'editar')['cardsFamilia'];
            }
            $data['filterEstadosContacto'] = '<li class="licontactos active"><a href="#tab-asistira" data-toggle="tab"    onclick="familiasPorOpcion(\''.$data['opcAsistira'].'\', \'cont_cards_familia2\')">Asistir&aacute;n (<span style="display:inline-block" id="cont_invitados_asistiran">'.$this->m_detalle_evento->countContactosEventoTipo_1(_getSesion('id_evento_detalle'), OPCION_ASISTIRA).'</span>)</a></li>
                                              <li class="licontactos"><a href="#tab-talvez" data-toggle="tab"      onclick="familiasPorOpcion(\''.$data['opcTalvez'].'\', \'cont_cards_familia3\')">Por confirmar (<span style="display:inline-block" id="cont_invitados_talvez">'.$this->m_detalle_evento->countContactosEventoTipo_1(_getSesion('id_evento_detalle'), OPCION_TALVEZ).'</span>)</a></li>
                                              <li class="licontactos"><a href="#tab-no-asistira" data-toggle="tab" onclick="familiasPorOpcion(\''.$data['opcNoAsistira'].'\', \'cont_cards_familia4\')">No Asistir�n (<span style="display:inline-block" id="cont_invitados_no_asistiran">'.$this->m_detalle_evento->countContactosEventoTipo_1(_getSesion('id_evento_detalle'), OPCION_NO_ASISTIRA).'</span>)</a></li>';
            
            $data['tabContEstadosContacto'] = '<div class="tab-pane active" id="tab-asistira">
                                                    <div id="cont_cards_familia2" class="mdl-content-cards"> '.$contFam.' </div>
                                                </div>
                                                <div class="tab-pane" id="tab-talvez">
                                                    <div id="cont_cards_familia3" class="mdl-content-cards"></div>
                                                </div>
                                                <div class="tab-pane" id="tab-no-asistira">
                                                    <div id="cont_cards_familia4" class="mdl-content-cards"></div>
                                                </div>';
            
            $data['disabled'] = 'disabled';
        }
        
        if(_getSesion('tipo_evento_detalle') == TIPO_EVENTO_EVALUACION_SEDE || _getSesion('tipo_evento_detalle') == TIPO_EVENTO_EVALUACION_VERANO){
            $data['fabAsignarApoyoAdm'] = '<button class="mfb-component__button--main">
                                               <i class="mfb-component__child-icon mdi mdi-group_add"></i>
                                           </button>
                                           <button class="mfb-component__button--main" onclick="abrirModalAsignarApoyoAdministrativoSede()" data-mfb-label="Asignar apoyo administrativo Sede">
                                               <i class="mfb-component__child-icon mdi mdi-group_add"></i>
                                           </button>';
            $data['btnFab'] = 1;
        }
        
        $this->load->view('v_detalle_evento', $data);
    }

    function saveCampo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $campo   = _post('campo');
            $valor   = _post('valor');
            $encrypt = _simpleDecryptInt(_post('enc'));
            
            if($campo == null || $valor == null || $encrypt == null){
                throw new Exception(ANP);
            }
            
            if($campo == 'fecha_realizar'){
                if(!_validateDate($valor, 'd/m/Y')){
                    throw new Exception("Ingrese una fecha valida");
                }
                $date1  = DateTime::createFromFormat("d/m/Y", $valor);
                $date2  = DateTime::createFromFormat("d/m/Y", date("d/m/Y"));
                if($date1 < $date2){
                    throw new Exception('La fecha debe ser mayor a la actual');
                }
                 
                if($date1->format("Y") != date("Y")){
                    throw new Exception('La fecha debe ser de este a�o');
                }
                 
                /*if(_getSesion("id_rol") != ID_ROL_SUBDIRECTOR && $this->m_evento->countEventosFecha($valor, _getSesion('id_evento_detalle')) >= 1){
                    throw new Exception('Existen eventos programados esa fecha');
                }*/
            }
            
            if($campo == "hora_inicio"){
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
            }

            if($encrypt == 1){
                $valor = _simpleDecryptInt($valor);
            }
            
            if($campo == 'desc_evento' || $campo == 'observacion'){
                $valor = utf8_decode($valor);
            }
            
            $arrayUpdate = array($campo => $valor);
            $data = $this->m_evento->updateEvento($arrayUpdate, _getSesion('id_evento_detalle'));
            if($data['error'] == EXIT_SUCCESS){
                if($campo == 'id_persona_encargada'){
                    $infoEvento    = $this->m_evento->getDetalleEvento(_getSesion('id_evento_detalle'));
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
                            			<h1 style="color: #959595;font-size:19px;margin-top:25px;margin-bottom: 30px;text-align:left">Hola, '.$this->m_detalle_evento->getNombrePersonaCorreo($valor).'</h1>
                            			<div style="text-align: left;color: #BBBBBB; font-size: 18px;">
                            				<p style="font-size: 16px;">Usted ha sido asignado como apoyo al evento: '.$infoEvento['desc_evento'].' que se realizar&aacute; el d&iacute;a : '._fecha_tabla($infoEvento['fecha_realizar'], 'd/m/Y').'. Para mayor informaci&oacute;n ingresa a
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
                    $correo = $this->m_utils->getCorreoByPersona($valor);
                    if($correo != null){
                        $arrayCorreo = array("correos_destino" => $correo/*"frandy0593@gmail.com"*/,
                            "asunto"          => utf8_encode("&#161;Has sido asignado de un evento!"),
                            "body"            => $html,
                            "estado_correo"  => "PENDIENTE",
                            "sistema"         => "ADMISION");
                        $this->m_utils->insertarEnviarCorreo($arrayCorreo);
                        
                    }
                    $recursos = $this->m_detalle_evento->getRecursosMaterialesByEvento(_getSesion('id_evento_detalle'));
                    $data['tablaRecursos'] = _createTableRecursosMateriales($recursos);
                }
            }   
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getFamiliasByOpcion(){
        $opcion = _simpleDecryptInt(_post('opcion'));
        $adc = null;
        $opc = null;
        if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $this->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle'), "admision") == _getSesion('nid_persona'))
            || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
            $opc = '<li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_ASISTIRA).'\')">Asistir&aacute;</li>
                <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_TALVEZ).'\')">Por confirmar</li>
                <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_NO_ASISTIRA).'\')">No asistir&aacute;</li>';
        }
        
        $familias = $this->m_detalle_evento->getFamiliasByOpcion(_getSesion('id_evento_detalle'), $opcion);
        
        if(count($familias) > 0){
            $data['familias'] = _createCardFamilias($familias, $opc, ID_PERMISO_EVENTO, 'editar')['cardsFamilia'];
        }
        $data['countFam'] = count($familias);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getFamiliasInvitar(){
        $familias = $this->m_detalle_evento->getFamiliasPorLlamadas(_getSesion('id_evento_detalle'), NUMERO_FAMILIAS_CARGA, 0);
        $opc = null;
        if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $this->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle'), "admision") == _getSesion('nid_persona'))
            || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
            $opc = '<li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_ASISTIRA).'\')">Asistir&aacute;</li>
                <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_TALVEZ).'\')">Por confirmar</li>
                <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_NO_ASISTIRA).'\')">No asistir&aacute;</li>';
            
        }
        $data['familias'] = _createCardFamilias($familias, $opc, ID_PERMISO_EVENTO, 'editar')['cardsFamilia'];
        $data['countFam'] = count($familias);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function crearRecurso(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $nombreRecurso = _post('nombrerecurso');
        
            if($nombreRecurso == null){
                throw new Exception("Faltan algunos campos");
            }
             
            if($this->m_detalle_evento->validateRecursoRepetido($nombreRecurso) != 0){
                throw new Exception("Ya existe Recurso material con ese nombre");
            }
            
            $valor = ($this->m_utils->getLastOpcionByGrupo(COMBO_RECURSOS_MATERIALES) + 1);
            $arrayInsert = array("desc_combo" => utf8_decode($nombreRecurso),
                                 "grupo"      => COMBO_RECURSOS_MATERIALES,
                                 "valor"      => $valor);
            $data = $this->m_detalle_evento->createRecursoMaterial($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $data['comboRecursoMaterial'] = __buildComboByGrupo(COMBO_RECURSOS_MATERIALES);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function borrarRecursoMaterial(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $recursoEvento = _simpleDecryptInt(_post("recursoevento"));
            if($recursoEvento == null){
                throw new Exception(ANP);
            }
            $personaEncargada = $this->m_utils->getById("admision.recurso_x_evento", "id_responsable", "id_recurso_x_evento", $recursoEvento);
            $data = $this->m_detalle_evento->deleteRecursoEvento($recursoEvento);
            if($data['error'] == EXIT_SUCCESS){
                /*$infoEncargado = $this->m_usuario->getDatosPersona($personaEncargada);
                $infoEvento    = $this->m_evento->getDetalleEvento(_getSesion('id_evento_detalle'));
                $html  = '<p>Hola!, '.$infoEncargado['nombres'].'</p>';
                $html .= '<p>Usted ha sido eliminado del evento: '.$infoEvento['desc_evento'].'</p>';
                $html .= '<p>Que se realizar&aacute; el : '._fecha_tabla($infoEvento['fecha_realizar'], 'd/m/Y').'</p>';
                $html .= 'Para mayor informaci&oacute;n ingresar al sistema <a href="'.base_url().'schoowl/">SmileDU</a>, al modelo de ADMISI&Oacute;N';
                $html .= '<p>Muchas gracias por tu comprensi&oacute;n</p>';
                //ENVIAR CORREO AL ENCARGADO
                $arrayCorreo = array("correos_destino" => 'jose.minayac15@gmail.com',
                    "asunto"  => utf8_encode("&#161;Has sido asignado de un evento!"),
                    "body" => $html,
                    "estado_correo" => "PENDIENTE",
                    "sistema" => "ADMISION"
                );
                $correo = $this->m_utils->insertarEnviarCorreo($arrayCorreo);*/
                $recursos = $this->m_detalle_evento->getRecursosMaterialesByEvento(_getSesion('id_evento_detalle'));
                $data['tablaRecursos'] = _createTableRecursosMateriales($recursos);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function asignarRecursoMaterial(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecurso   = _simpleDecryptInt(_post("recurso"));
            $encargado   = _simpleDecryptInt(_post("encargado"));
            $cantidad    = _post('cantidad');
            $observacion = _post('observacion');
            
            if($idRecurso == null || $encargado == null || $cantidad == null){
                throw new Exception(ANP);
            }
            
            if(!ctype_digit($cantidad)){
                throw new Exception("La cantidad debe ser entero");
            }
            
            if($cantidad <= 0){
                throw new Exception("La cantidad debe ser mayor a 0");
            }
            $cantidad = intval($cantidad);
            $arrayInsert = array("id_recurso"         => $idRecurso,
                                 "id_evento"          => _getSesion('id_evento_detalle'),
                                 "tipo_recurso"       => TIPO_RECURSO_MATERIAL,
                                 "cantidad"           => $cantidad,
                                 "observacion_pedido" => utf8_decode($observacion),
                                 "id_responsable"     => $encargado,
                                 "nombre_responsable" => $this->m_utils->getNombrePersona($encargado));
            $data = $this->m_detalle_evento->asignarRecursoEvento($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $infoEvento    = $this->m_evento->getDetalleEvento(_getSesion('id_evento_detalle'));
                $html  = '<body style="background-color: #f3f3f3;">
                                  <div style="text-align: center;margin-bottom: 10px;">
                            		<img src="'.RUTA_SMILEDU.'public/general/img/header/grupo_educativo.png" style="width: 250px;margin-top:40px;">
                            	</div>
                            	<div style="width: 320px; height: 480px;border: 1px solid #EEEEEE; border-radius: 5px;text-align: center;margin: auto;font-family:Arial, Helvetica, sans-serif;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px
                            rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);background-color: #ffffff;">
                            		<div style="">
                            			<img src="'.RUTA_SMILEDU.'public/general/img/header/smiledu_card.png" style="width: 320px;height: 165px;border-top-left-radius: 5px;border-top-right-radius: 5px; ">
                            		</div> 
                            		<div style="margin:0 20px;">
                            			<h1 style="color: #959595;font-size:19px;margin-top:25px;margin-bottom: 30px;text-align:left">Hola, '.$this->m_detalle_evento->getNombrePersonaCorreo($encargado).'</h1>
                            			<div style="text-align: left;color: #BBBBBB; font-size: 18px;">
                            				<p style="font-size: 16px;">Te han asignado la responsabilidad de un recurso del evento: '.$infoEvento['desc_evento'].' que se realizar&aacute; el d&iacute;a : '._fecha_tabla($infoEvento['fecha_realizar'], 'd/m/Y').'. Para mayor informaci&oacute;n ingresa a
                                                    <a style="color:#BBBBBB;" href="http://buhooweb.com/smiledu" target="_blank"><strong>smiledu</strong></a> al m&oacute;dulo de Admisi&oacute;n.</p>
                            				<p style="font-size:16px">Observacion: '.$observacion.'</p>
                            				<p style="font-size: 16px;">Gracias.</p>
                                  		</div>
                            		    <div style="">
                        				    <a href="'.RUTA_SMILEDU.'admision/c_confirm_asist_bypass?recevento='._simple_encrypt($data['idRecEvento']).'&opcion='._simple_encrypt(FLG_NO_CONFIRMACION_AUXILIAR).'&persona='._simple_encrypt($encargado).'" style="height: 36px;border-radius: 2px;border: none;min-width: 64px;padding: 14px 16px;outline: none;cursor: pointer;color: #FF5722;background-color: transparent;box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);text-decoration:none;">
                                                RECHAZAR
                        				    </a>
                            				 <a href="'.RUTA_SMILEDU.'admision/c_confirm_asist_bypass?recevento='._simple_encrypt($data['idRecEvento']).'&opcion='._simple_encrypt(FLG_CONFIRMACION_AUXILIAR).'&persona='._simple_encrypt($encargado).'" style="height: 36px;border-radius: 2px;border: none;min-width: 64px;padding: 8px 16px;outline: none;cursor: pointer;color: #fff;background-color: #FF5722;box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);text-decoration:none">
                                                CONFIRMAR
                        				    </a>   
                    				    </div>
                                  	</div>
            				    </div>
            				    <div style="text-align: center;margin: 20px auto;width: 320px;height: 100px;margin-top: 20px;">
                                      <span style="vertical-align: middle;color: #BDBDBD;font-family:Arial, Helvetica, sans-serif;">Tus consultas escribenos a <a style="color: #BDBDBD;text-decoration: none"href="mailto:soporte@smiledu.pe">soporte@smiledu.pe</a></span>
                                </div>
                            </body>';
                //ENVIAR CORREO AL ENCARGADO
                $correo = $this->m_utils->getCorreoByPersona($encargado);
                if($correo != null){
                    $arrayCorreo = array("correos_destino" => $correo/*"rikardo308@gmail.com"*/,
                        "asunto"          => utf8_encode("&#161;Has sido asignado de un evento!"),
                        "body"            => $html,
                        "estado_correo"  => "PENDIENTE",
                        "sistema"         => "ADMISION");
                    $this->m_utils->insertarEnviarCorreo($arrayCorreo);
                }
                $recursos = $this->m_detalle_evento->getRecursosMaterialesByEvento(_getSesion('id_evento_detalle'));
                $data['tablaRecursos'] = _createTableRecursosMateriales($recursos);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cambiarCantidadRecursoMaterial(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecursoEvento = _simpleDecryptInt(_post("recursoevento"));
            $cantidad        = _post('cantidad');
        
            if($idRecursoEvento == null || $cantidad == null){
                throw new Exception(ANP);
            }
        
            if(!ctype_digit($cantidad)){
                throw new Exception("La cantidad debe ser entero");
            }
            
            if($cantidad <= 0){
                throw new Exception("La cantidad debe ser mayor a 0");
            }
            $cantidad = intval($cantidad);
            $arrayUpdate = array("cantidad" => $cantidad);
            $data = $this->m_detalle_evento->updateRecursoEvento($arrayUpdate, $idRecursoEvento);
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeSedeRuta(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idSede = _simpleDecryptInt(_post("sede"));
        
            if($idSede == null){
                throw new Exception(ANP);
            }
            if($this->m_detalle_evento->verifySedeRutaEvento(_getSesion('id_evento_detalle'), $idSede) == 0){
                $arrayInsert = array("id_evento" => _getSesion('id_evento_detalle'),
                                     "id_sede"   => $idSede,
                                     "orden"     => $this->m_detalle_evento->getLastOrdenSedeRuta(_getSesion('id_evento_detalle')));
                $data = $this->m_detalle_evento->insertRutaTour($arrayInsert);
            }else{
                $orden = $this->m_detalle_evento->getOrdenBySedeEvento(_getSesion('id_evento_detalle'), $idSede);
                $data = $this->m_detalle_evento->deleteRutaTour(_getSesion('id_evento_detalle'), $idSede, $orden);
            }
            
            if($data['error'] == EXIT_SUCCESS){
                $sedesRutaEvento = $this->m_detalle_evento->getSedesRutaEvento(_getSesion('id_evento_detalle'));
                $data['tablaRutas'] = _createTableSedesRuta($sedesRutaEvento);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeOrdenSedeRuta(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idSede = _simpleDecryptInt(_post("idsede"));
            $direc  = _post("direccion");
            $orden  = _simpleDecryptInt(_post("orden"));
            if($idSede == null || $direc == null || $orden == null){
                throw new Exception(ANP);
            }
            
            if($direc == 1){//arriba
                $arrayUpdate1 = array("orden"     => $orden - 1,
                                      "id_sede"   => $idSede);
                $arrayUpdate2 = array("orden"     => $orden,
                                      "orden_cambio" => $orden - 1);
             
                $data = $this->m_detalle_evento->cambiarOrdenSedeRuta($arrayUpdate1, $arrayUpdate2, _getSesion('id_evento_detalle'));
            }else if($direc == 0){ //abajo
                $arrayUpdate1 = array("orden"     => $orden + 1,
                                      "id_sede"   => $idSede);
                $arrayUpdate2 = array("orden"     => $orden,
                                      "orden_cambio" => $orden + 1);
            
                $data = $this->m_detalle_evento->cambiarOrdenSedeRuta($arrayUpdate1, $arrayUpdate2, _getSesion('id_evento_detalle'));
            }
            if($data['error'] == EXIT_SUCCESS){
                $sedesRutaEvento = $this->m_detalle_evento->getSedesRutaEvento(_getSesion('id_evento_detalle'));
                $data['tablaRutas'] = _createTableSedesRuta($sedesRutaEvento);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeCheckConformidadRecursoMaterial(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecursoEvento = _simpleDecryptInt(_post("recursoevento"));
            $check           = _post('check');
            $observacion     = _post('observacion');
        
            if($idRecursoEvento == null || $check == null){
                throw new Exception(ANP);
            }
            
            if(!ctype_digit($check)){
                throw new Exception(ANP);
            }
        
            $arrayUpdate = array("observacion_cumplimiento" => utf8_decode($observacion),
                                 "flg_cumplimiento"         => $check);
            $data = $this->m_detalle_evento->updateRecursoEvento($arrayUpdate, $idRecursoEvento);
            if($data['error'] == EXIT_SUCCESS){
                $recursos = $this->m_detalle_evento->getRecursosMaterialesByEvento(_getSesion('id_evento_detalle'));
                $data['tablaRecursos'] = _createTableRecursosMateriales($recursos);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function borrarRecursoApoyoAdministrativo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $recursoEvento = _simpleDecryptInt(_post("recursoevento"));
            if($recursoEvento == null){
                throw new Exception(ANP);
            }
            $personasEncargadas = $this->m_detalle_evento->getApoyoAdministrativoRecursoEvento($recursoEvento);
            $data = $this->m_detalle_evento->deleteRecursoApoyoAdministrativo($recursoEvento);
            if($data['error'] == EXIT_SUCCESS){
                $infoEvento    = $this->m_evento->getDetalleEvento(_getSesion('id_evento_detalle'));
                /*foreach ($personasEncargadas as $row){
                    $html  = '<p>Hola!, '.$row->nombrecompleto.'</p>';
                    $html .= '<p>Usted ha sido eliminado del evento: '.$infoEvento['desc_evento'].'</p>';
                    $html .= '<p>Que se realizar&aacute; el : '._fecha_tabla($infoEvento['fecha_realizar'], 'd/m/Y').'</p>';
                    $html .= 'Para mayor informaci&oacute;n ingresar al sistema <a href="'.base_url().'schoowl/">SmileDU</a>, al modelo de ADMISI&Oacute;N';
                    $html .= '<p>Muchas gracias por tu comprensi&oacute;n</p>';
                    //ENVIAR CORREO AL ENCARGADO
                    $arrayCorreo = array("correos_destino" => 'jose.minayac15@gmail.com',
                        "asunto"  => utf8_encode("&#161;Has sido asignado de un evento!"),
                        "body" => $html,
                        "estado_correo" => "PENDIENTE",
                        "sistema" => "ADMISION"
                    );
                    $correo = $this->m_utils->insertarEnviarCorreo($arrayCorreo);
                }*/
                $recursos = $this->m_detalle_evento->getApoyoAdministrativoByEvento(_getSesion('id_evento_detalle'));
                $data['tablaRecursosHumanos'] = _createTableRecursosHumanos($recursos, 0);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function asignarApoyoAdministrativo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecurso   = _simpleDecryptInt(_post("recurso"));
            $idSede      = _simpleDecryptInt(_post("sede"));
            $cantidad    = _post('cantidad');
            $observacion = _post('observacion');
            $asistencia  = _post('asistencia');
        
            if($idRecurso == null || $cantidad == null || $asistencia == null){
                throw new Exception(ANP);
            }
        
            if(!ctype_digit($cantidad)){
                throw new Exception("La cantidad debe ser entero");
            }
        
            if($cantidad <= 0){
                throw new Exception("La cantidad debe ser mayor a 0");
            }
            $cantidad = intval($cantidad);
            
            $arrayInsert = array("id_recurso"          => $idRecurso,
                                 "id_evento"           => _getSesion('id_evento_detalle'),
                                 "tipo_recurso"        => TIPO_RECURSO_HUMANO,
                                 "cantidad"            => $cantidad,
                                 "observacion_pedido"  => utf8_decode($observacion),
                                 "id_sede"             => $idSede,
                                 "flg_toma_asistencia" => ($asistencia == 'true') ? 1 : 0);
            $data = $this->m_detalle_evento->asignarRecursoEvento($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $recursos = $this->m_detalle_evento->getApoyoAdministrativoByEvento(_getSesion('id_evento_detalle'));
                $data['tablaRecursosHumanos'] = _createTableRecursosHumanos($recursos, 0);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cantidadApoyoAdministrativoEvento(){
        $idRecursoEvento = _simpleDecryptInt(_post("recursoevento"));
        $encargados = $this->m_detalle_evento->getPersonasEncargadasApoyoAdministrativo($idRecursoEvento);
        $data['tablaApoyoAdministrativo'] = _createTableEncargadosApoyoAdministrativo($encargados, _post("recursoevento"));
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function asistenciaApoyoAdministrativo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecursoEvento = _simpleDecryptInt(_post("recursoevento"));
            $idPersona       = _simpleDecryptInt(_post("persona"));
            $check           = _post("check");

            if($idPersona == null || $idRecursoEvento == null || $check == null){
                throw new Exception(ANP);
            }
            $horaLlegada = null;
            if($check == ASISTENCIA_APOYO_ADM){
                $horaLlegada = date('Y-m-d H:i:s');
            }
            
            $arrayUpdate = array("asistencia"   => $check,
                                 "hora_llegada" => $horaLlegada);
            $data = $this->m_detalle_evento->asistenciaApoyoAdministrativo($idRecursoEvento, $idPersona, $arrayUpdate);
            $data['horallegada'] = _fecha_tabla($horaLlegada, 'h:i A');
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function invitarContacto(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $opc               = (_post("opcinvitacion") == null) ? null : _simpleDecryptInt(_post("opcinvitacion"));
            $opcMenu           = (_post("opcmenu") == null) ? null : _simpleDecryptInt(_post("opcmenu"));
            $razonInasistencia = _post("razoninasistencia");
            $tipoEvento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", _getSesion('id_evento_detalle'));

            $myPostData = json_decode(_post('contactos'), TRUE);
            $count = 0;
            $cantEst = 0;
            $cantFam = 0;
            $idcontacto = null;
            foreach($myPostData['contacto'] as $key => $contacto) {
                $idcontacto = _simpleDecryptInt($contacto['contacto']);
                if($tipoEvento == TIPO_EVENTO_EVALUACION){
                    if($opc == OPCION_ASISTIRA){
                        if($contacto['check'] == true && $contacto['hora'] == null){
                            break;
                        }
                        
                        if($contacto['check'] == true && $contacto['hora'] != null){
                            $est = $this->m_utils->getById("admision.contacto", "flg_estudiante", "id_contacto", _simpleDecryptInt($contacto['contacto']));
                            if($est == FLG_ESTUDIANTE){
                                $cantEst++;
                            }else{
                                $cantFam++;
                            }
                        }
                    }else if($opc == OPCION_TALVEZ){
                        if($contacto['check'] == true){
                            $est = $this->m_utils->getById("admision.contacto", "flg_estudiante", "id_contacto", _simpleDecryptInt($contacto['contacto']));
                            if($est == FLG_ESTUDIANTE){
                                $cantEst++;
                            }else{
                                $cantFam++;
                            }
                        }
                    }
                }else{
                    if($contacto['check'] == true){
                        $est = $this->m_utils->getById("admision.contacto", "flg_estudiante", "id_contacto", _simpleDecryptInt($contacto['contacto']));
                        if($est == FLG_ESTUDIANTE){
                            $cantEst++;
                        }else{
                            $cantFam++;
                        }
                    }
                }
                
                $count++;
            }

            if($tipoEvento == TIPO_EVENTO_EVALUACION){
                if($opc == OPCION_ASISTIRA){
                    if($count != count($myPostData['contacto'])){
                        throw new Exception("Elija los horarios de los invitados");
                    }
                    if($cantEst <= 0){
                        throw new Exception("Para la evaluaci�n, debe elegir por lo menos a un postulante");
                    }if($cantFam <= 0){
                        throw new Exception("Para la evaluaci�n, debe elegir por lo menos a un pariente");
                    }
                }else if($opc == OPCION_TALVEZ){
                    $cantEst = $cantEst + $this->m_detalle_evento->countContactosEventoTipo(_getSesion('id_evento_detalle'), OPCION_ASISTIRA, $idcontacto, FLG_ESTUDIANTE);
                    $cantFam = $cantFam + $this->m_detalle_evento->countContactosEventoTipo(_getSesion('id_evento_detalle'), OPCION_ASISTIRA, $idcontacto, FLG_FAMILIAR);
                    if($cantEst <= 0){
                        throw new Exception("Para la evaluaci�n, debe elegir por lo menos a un postulante");
                    }if($cantFam <= 0){
                        throw new Exception("Para la evaluaci�n, debe elegir por lo menos a un pariente");
                    }
                }
            }else{
                if($opc != OPCION_NO_ASISTIRA){
                    $cantFam = $cantFam + $this->m_detalle_evento->countContactosEventoTipo(_getSesion('id_evento_detalle'), OPCION_ASISTIRA, $idcontacto, FLG_FAMILIAR);
                    if($cantFam <= 0){
                        throw new Exception("Para el tour, debe elegir por lo menos a un pariente");
                    }
                }
            }

            foreach($myPostData['contacto'] as $key => $contacto) {
                $idContacto = _simpleDecryptInt($contacto['contacto']);
                $campo = "id_hora_cita";
                $valor = null;
                if($tipoEvento == TIPO_EVENTO_EVALUACION){
                    if($opc == OPCION_ASISTIRA){
                        $campo = "id_hora_cita";
                        $valor = _simpleDecryptInt($contacto['hora']);
                    }
                }
                if($opc == OPCION_NO_ASISTIRA){
                    $campo = "razon_inasistencia";
                    $valor = utf8_decode($razonInasistencia);
                }
                $existeContacto = $this->m_detalle_evento->validExisteContactoInvitacionEvento($idContacto, _getSesion('id_evento_detalle'));
                if($existeContacto == 0 && $contacto['check'] == true){//INSERT
                    $arrayInsert = array("id_evento"              => _getSesion('id_evento_detalle'),
                                         "id_contacto"            => $idContacto,
                                         "opcion"                 => $opc,
                                         "asistencia"             => INASISTENCIA_CONTACTO,
                                         "flg_asistencia_directa" => ASISTENCIA_INVITACION_CONTACTO,
                                         $campo                   => $valor);
                    $data = $this->m_detalle_evento->insertInvitacionEvento($arrayInsert);
                }else{//UPDATE O DELETE
                    if($contacto['check'] == false && $existeContacto == 1){
                        $data = $this->m_detalle_evento->deleteInvitacionEvento(_getSesion('id_evento_detalle'), $idContacto);
                    }else if($contacto['check'] == true && $existeContacto == 1){
                        $arrayUpdate = array($campo => $valor);
                        $data = $this->m_detalle_evento->updateInvitacionEvento($arrayUpdate, _getSesion('id_evento_detalle'), $idContacto);
                    }
                }
            }
            
            if($data['error'] == EXIT_SUCCESS){
                if($opcMenu != null){
                    $opciones = null;
                    if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $this->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
                        || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
                        $opciones = '<li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_ASISTIRA).'\')">Asistir&aacute;</li>
                                     <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_TALVEZ).'\')">Por confirmar</li>
                                     <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_NO_ASISTIRA).'\')">No asistir&aacute;</li>';
                    }
                    $familias = $this->m_detalle_evento->getFamiliasByOpcion(_getSesion('id_evento_detalle'), $opcMenu);
                    if(count($familias) != 0){
                        $data['familias'] = _createCardFamilias($familias, $opciones, ID_PERMISO_EVENTO, 'editar')['cardsFamilia'];
                    }else{
                        $data['familias'] = null;
                    }
                }else{
                    $familias = $this->m_detalle_evento->getFamiliasPorLlamadas(_getSesion('id_evento_detalle'), NUMERO_FAMILIAS_CARGA, 0);
                    $opciones = null;
                    if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $this->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
                        || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
                        $opciones = '<li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_ASISTIRA).'\')">Asistir&aacute;</li>
                                     <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_TALVEZ).'\')">Por confirmar</li>
                                     <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_NO_ASISTIRA).'\')">No asistir&aacute;</li>';
                    }
                        
                    if(count($familias) != 0){
                        $data['familias'] = _createCardFamilias($familias, $opciones, ID_PERMISO_EVENTO, 'editar')['cardsFamilia'];
                    }else{
                        $data['familias'] = null;
                    }
                }
                
                if($opc == OPCION_ASISTIRA){
                    $data['id_span'] = 'cont_invitados_asistiran';
                }else if($opc == OPCION_TALVEZ){
                    $data['id_span'] = 'cont_invitados_talvez';
                }else{
                    $data['id_span'] = 'cont_invitados_no_asistiran';
                }
                $data['count_contactos'] = $this->m_detalle_evento->countContactosEventoTipo_1(_getSesion('id_evento_detalle'), $opc);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buscarApoyoAdministrativo() {
        $busqueda      = _post("busqueda");
        $idRecursoEvento = _simple_decrypt(_post("recursoevento"));
        $idRol = $this->m_utils->getById("admision.recurso_x_evento", "id_recurso", "id_recurso_x_evento", $idRecursoEvento);
        $auxiliares = $this->m_detalle_evento->busquedaAuxiliares($busqueda, $idRol, $idRecursoEvento);
        $data['busqueda'] = _createTableAuxiliaresApoyoAdministrativo($auxiliares, _post("recursoevento"));
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function asignarAuxiliarApoyoAdministrativo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecursoEvento = _simpleDecryptInt(_post("recursoevento"));
            $idPersona       = _simpleDecryptInt(_post("persona"));
            $busqueda        = _post("busqueda");
            $observacion     = _post("observacion");
            
            if($idPersona == null || $idRecursoEvento == null){
                throw new Exception(ANP);
            }
            
            /*if($observacion == null || strlen($observacion) == 0){
                throw new Exception("Ingrese una observaci&oacute;n");
            }*/
            
            if($this->m_detalle_evento->validateApoyoAdministrativoAux($idRecursoEvento, $idPersona) > 0){
                throw new Exception("La persona ya est&aacute; asignada");
            }
        
            $arrayInsert = array("id_persona"          => $idPersona,
                                 "id_recurso_x_evento" => $idRecursoEvento,
                                 "asistencia"          => INASISTENCIA_APOYO_ADM,
                                 "observacion_ped"     => __only1whitespace(utf8_decode(ucfirst($observacion))));
            $data = $this->m_detalle_evento->insertPersonaRecursoApoyoAdm($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $infoEvento    = $this->m_evento->getDetalleEvento(_getSesion('id_evento_detalle'));
                $observacionEvento = $this->m_utils->getById("admision.recurso_x_evento", "observacion_pedido", "id_recurso_x_evento", $idRecursoEvento);
                $html  = '<body style="background-color: #f3f3f3;">	        	    
                              <div style="text-align: center;margin-bottom: 10px;">
                        		<img src="'.RUTA_SMILEDU.'public/general/img/header/grupo_educativo.png" style="width: 250px;margin-top:40px;">
                        	</div>
                        	<div style="width: 320px; height: 553px;border: 1px solid #EEEEEE; border-radius: 5px;text-align: center;margin: auto;font-family:Arial, Helvetica, sans-serif;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px
                        rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);background-color: #ffffff;">
                        		<div style="">
                        			<img src="'.RUTA_SMILEDU.'public/general/img/header/smiledu_card.png" style="width: 320px;height: 165px;border-top-left-radius: 5px;border-top-right-radius: 5px; ">
                        		</div>
                        		<div style="margin:0 20px;">
                        			<h1 style="color: #959595;font-size:19px;margin-top:25px;margin-bottom: 30px;text-align:left">Hola, '.$this->m_detalle_evento->getNombrePersonaCorreo($idPersona).'</h1>
                        			<div style="text-align: left;color: #BBBBBB; font-size: 18px;">
                        				<p style="font-size: 16px;">Has sido asignado al evento: '.$infoEvento['desc_evento'].' que se realizar&aacute; el d&iacute;a : '._fecha_tabla($infoEvento['fecha_realizar'], 'd/m/Y').'. Para mayor informaci&oacute;n ingresa a 
                                                <a style="color:#BBBBBB;" href="http://buhooweb.com/smiledu" target="_blank"><strong>smiledu</strong></a> al m&oacute;dulo de Admisi&oacute;n.</p>
                        				<p style="font-size:16px">Comentario para todos: '.$observacionEvento.'</p>
                        				<p style="font-size:16px">Comentario para usted: '.$observacion.'</p>
                        				<p style="font-size: 16px; color:#BBBBBB">Has click aqu&iacute; para confirmar su asistencia</p>      				    
                              		</div>
                				    <div style="">
                    				    <a href="'.RUTA_SMILEDU.'admision/c_confirm_asist_bypass?recevento='._simple_encrypt($idRecursoEvento).'&opcion='._simple_encrypt(FLG_NO_CONFIRMACION_AUXILIAR).'&persona='._simple_encrypt($idPersona).'" style="height: 36px;border-radius: 2px;border: none;min-width: 64px;padding: 14px 16px;outline: none;cursor: pointer;color: #FF5722;background-color: transparent;box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);text-decoration:none;">
                                            RECHAZAR
                    				    </a>
                        				 <a href="'.RUTA_SMILEDU.'admision/c_confirm_asist_bypass?recevento='._simple_encrypt($idRecursoEvento).'&opcion='._simple_encrypt(FLG_CONFIRMACION_AUXILIAR).'&persona='._simple_encrypt($idPersona).'" style="height: 36px;border-radius: 2px;border: none;min-width: 64px;padding: 8px 16px;outline: none;cursor: pointer;color: #fff;background-color: #FF5722;box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);text-decoration:none">
                                            CONFIRMAR
                    				    </a>   
                				    </div>
                              	</div>
        				    </div>	
        				    <div style="text-align: center;margin: 20px auto;width: 320px;height: 100px;margin-top: 20px;">
                                  <span style="vertical-align: middle;color: #BDBDBD;font-family:Arial, Helvetica, sans-serif;">Tus consultas escribenos a <a style="color: #BDBDBD;text-decoration: none"href="mailto:soporte@smiledu.pe">soporte@smiledu.pe</a></span>
                            </div>
                        </body>';
                //ENVIAR CORREO AL ENCARGADO
                $correoDestino = $this->m_utils->getCorreoByPersona($idPersona);
                /*if($correoDestino != null){
                    $arrayCorreo = array("correos_destino" => $correoDestino/*"rikardo308@gmail.com",
                        "asunto"          => utf8_encode("Has sido asignado de un evento!"),
                        "body"            => $html,
                        "estado_correo"  => "PENDIENTE",
                        "sistema"         => "ADMISION");
                    $this->m_utils->insertarEnviarCorreo($arrayCorreo);
                }*/
                
                $idRol = $this->m_utils->getById("admision.recurso_x_evento", "id_recurso", "id_recurso_x_evento", $idRecursoEvento);
                $auxiliares = $this->m_detalle_evento->busquedaAuxiliares($busqueda, $idRol, $idRecursoEvento);
                $data['busqueda'] = _createTableAuxiliaresApoyoAdministrativo($auxiliares, _post("recursoevento"));
                $data['count'] = $this->m_detalle_evento->getCountApoyoAdmRecursoEvento($idRecursoEvento);
                $data['msj']      = "Se Agreg&oacute; a esta Persona";
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeDuracionSedeRuta(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idSede = _simpleDecryptInt(_post("sede"));
            $duracion = _post("duracion");
        
            if($idSede == null || $duracion == null){
                throw new Exception(ANP);
            }
            
            if(!is_numeric($duracion)){
                throw new Exception("La duraci&oacute;n debe ser num&eacute;rico");
            }
            
            $arrayUpdate = array("duracion" => $duracion);
            $data = $this->m_detalle_evento->updateSedeRuta($arrayUpdate, $idSede, _getSesion('id_evento_detalle'));
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildComboSubDirectores($subDirectores){
        $opcion = null;
        foreach ($subDirectores as $sDirect){
            $opcion .= '<option value="'._simple_encrypt($sDirect->nid_persona).'">'.$sDirect->nombrecompleto.' ('.$sDirect->desc_sede.')</option>';
        }
        return $opcion;
    }
    
    function buildComboEncargados($encargados){
        $opcion = null;
        foreach ($encargados as $encargado){
            $opcion .= '<option value="'._simple_encrypt($encargado->nid_persona).'">'.$encargado->nombrecompleto.'</option>';
        }
        return $opcion;
    }
    
    function buildComboApoyoAdm($apoyo){
        $opcion = null;
        foreach ($apoyo as $ap){
            $opcion .= '<option value="'._simple_encrypt($ap->nid_rol).'">'.$ap->desc_rol.'</option>';
        }
        return $opcion;
    }
    
    function getPersonasAInvitar(){
        $idcontacto = _simpleDecryptInt(_post('idcontacto'));
        $opc        = _simple_decrypt(_post("opc"));
        $familiares = $this->m_detalle_evento->getFamiliaCompletaInvitarEvento($idcontacto, _getSesion('id_evento_detalle'), $opc);
        $tipoEvento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", _getSesion('id_evento_detalle'));
        $horas = null;
        if($opc == OPCION_ASISTIRA){
            $data['opc'] = 1;
            $horas = $this->m_detalle_evento->getHorasCitaEvento(_getSesion('id_evento_detalle'));
        }else if($opc == OPCION_TALVEZ){
            $data['opc'] = 2;
        }else{
            $data['opc'] = 3;
        }

        $data['tabla'] = _createTableInvitar($familiares, $horas, $tipoEvento);

        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function crearHorarioEvaluacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $hora        = _post("hora");
            $descripcion = _post("descripcion");
        
            if($hora == null || $descripcion == null){
                throw new Exception(ANP);
            }
            
            $hora   = date('Y-m-d H:i:s', strtotime($hora));
            if($this->m_detalle_evento->validateHorarioEvaluacion($hora, _getSesion('id_evento_detalle')) != 0){
                throw new Exception("Ya hay un horario en la misma hora");
            }

            $arrayInsert = array("hora_cita"      => $hora,
                                 "desc_hora_cita" => utf8_decode($descripcion),
                                  "id_evento"     => _getSesion('id_evento_detalle')
            );
            $data = $this->m_detalle_evento->insertHorarioEvaluacion($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $horaCitaEvento = $this->m_detalle_evento->getHorasCitaEvento(_getSesion('id_evento_detalle'));
                $data['tablaHoras']   = _createTableHorasCita($horaCitaEvento);
                $data['comboHorario'] = __buildComboHorarios($horaCitaEvento);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function borrarHorarioEvaluacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $correlativo = _simple_decrypt(_post("correlativo"));
            
            if($correlativo == null){
                throw new Exception(ANP);
            }
            
            $cont = $this->m_detalle_evento->validateHorarioReservado($correlativo);
            if($cont <= 0){
                $data = $this->m_detalle_evento->deleteHorarioEvaluacion($correlativo, _getSesion('id_evento_detalle'));
                if($data['error'] == EXIT_SUCCESS){
                    $horaCitaEvento = $this->m_detalle_evento->getHorasCitaEvento(_getSesion('id_evento_detalle'));
                    $data['tablaHoras'] = _createTableHorasCita($horaCitaEvento);
                    $data['comboHorario'] = __buildComboHorarios($horaCitaEvento);
                }
            }else{
                throw new Exception("El horario ya tiene contactos reservados");
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getHorarioCambiar(){
        $idcontacto = _simpleDecryptInt(_post('idcontacto'));
        $opcion     = _simpleDecryptInt(_post('opcion'));
        
        $horarios = $this->m_detalle_evento->getHorarioByFamiliaOpcion($idcontacto, $opcion, _getSesion('id_evento_detalle'));
        $data['tabla'] = $this->createTableCambiarHorariosInvitados($horarios);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function createTableCambiarHorariosInvitados($horarios){
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tbCambiarHorariosInvitados">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Horario', 'class' => 'text-left');
        $this->table->set_heading($head_0, $head_1, $head_2);
        
        $i = 1;
        $horas = $this->m_detalle_evento->getHorasCitaEvento(_getSesion('id_evento_detalle'));
        
        foreach ($horarios as $row){
            $idContactoEnc = _simple_encrypt($row->id_contacto);
        
            $row_0 = array('data' => $i , 'class' => 'text-left');
            $row_1 = array('data' => $row->nombrecompleto.' ('.$row->parentesco.')' , 'class' => 'text-left');
            
            $comboHorarios = __buildComboHorarios($horas, $row->id_hora_cita);
            $select = '<select class="form-control selectButton" data-live-search="true" data-container="body"
                               onchange="cambiarHorarioInvitado(\''.$idContactoEnc.'\', this)">
		                  <option value="">Selecciona un horario</option>
                          '.$comboHorarios.'
		               </select>';
            $row_2 = array('data' => $select , 'class' => 'text-left');
            $this->table->add_row($row_0, $row_1, $row_2);
        
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function cambiarHorarioInvitado(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $hora     = _simple_decrypt(_post("hora"));
            $contacto = _simple_decrypt(_post("idcontacto"));
        
            if($contacto == null || $hora ==null){
                throw new Exception(ANP);
            }
            
            $arrayUpdate = array("id_hora_cita" => $hora);
            
            $data = $this->m_detalle_evento->updateInvitacionEvento($arrayUpdate, _getSesion('id_evento_detalle'), $contacto);
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function eliminarPersonaRecursoEvento(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $recursoEvento = _simpleDecryptInt(_post("recursoevento"));
            $idPersona     = _simpleDecryptInt(_post("persona"));
            if($recursoEvento == null || $idPersona == null){
                throw new Exception(ANP);
            }
             
            $data = $this->m_detalle_evento->deletePersonaRecursoEvento($recursoEvento, $idPersona);
            if($data['error'] == EXIT_SUCCESS){
                $encargados = $this->m_detalle_evento->getPersonasEncargadasApoyoAdministrativo($recursoEvento);
                $data['tablaApoyoAdministrativo'] = _createTableEncargadosApoyoAdministrativo($encargados, _post("recursoevento"));
                $data['count'] = $this->m_detalle_evento->getCountApoyoAdmRecursoEvento($recursoEvento);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cambiarConfirmacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecursoEvento = _simpleDecryptInt(_post("recursoevento"));
        
            if($idRecursoEvento == null){
                throw new Exception(ANP);
            }
            
            $confirmacion = $this->m_utils->getById("admision.recurso_x_evento", "flg_confirmacion", "id_recurso_x_evento", $idRecursoEvento);
            $icon  = "mdi-thumb_up";
            $color = "#2196F3";
            $confirm = FLG_CONFIRMACION_AUXILIAR;
            if($confirmacion == FLG_CONFIRMACION_AUXILIAR){
                $icon  = "mdi-thumb_down";
                $color = "red";
                $confirm = FLG_NO_CONFIRMACION_AUXILIAR;
            }
            
            $arrayUpdate = array("flg_confirmacion"   => $confirm,
                                 "fecha_confirmacion" => date('Y-m-d')
            );
            $data = $this->m_detalle_evento->updateRecursoEvento($arrayUpdate, $idRecursoEvento);
            if($data['error'] == EXIT_SUCCESS){
                $data['color'] = $color;
                $data['icon']  = $icon;
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function confirmacionRecursoEvento(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idRecursoEvento = _simpleDecryptInt(_post("recursoevento"));
            $idPersona       = _simpleDecryptInt(_post("persona"));
    
            if($idRecursoEvento == null || $idPersona == null){
                throw new Exception(ANP);
            }
    
            $confirmacion = $this->m_detalle_evento->getConfirmacionAsistenciaRecursoEventoPersona($idRecursoEvento, $idPersona);
            $icon  = "mdi-thumb_up";
            $color = "#2196F3";
            $confirm = FLG_CONFIRMACION_AUXILIAR;
            if($confirmacion == FLG_CONFIRMACION_AUXILIAR){
                $icon  = "mdi-thumb_down";
                $color = "red";
                $confirm = FLG_NO_CONFIRMACION_AUXILIAR;
            }
    
            $arrayUpdate = array("flg_confirmacion"   => $confirm,
                                 "fecha_confirmacion" => date('Y-m-d')
            );
            $data = $this->m_detalle_evento->updateRecursoEventoPersona($arrayUpdate, $idRecursoEvento, $idPersona);
            if($data['error'] == EXIT_SUCCESS){
                $data['color'] = $color;
                $data['icon']  = $icon;
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function onScrollGetFamilias(){
        $scroll = _post("countScroll");
        $familias = $this->m_detalle_evento->getFamiliasPorLlamadas(_getSesion('id_evento_detalle'), NUMERO_FAMILIAS_CARGA, ($scroll * NUMERO_FAMILIAS_CARGA));
        $contFam = null;
        if(count($familias) != 0){
            $opc = '<li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_ASISTIRA).'\', 1)">Asistir&aacute;</li>
                    <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_TALVEZ).'\', 1)">Por confirmar</li>
                    <li class="mdl-menu__item" onclick="abrirModalConfirmInvitarContacto(this, \''._simple_encrypt(OPCION_NO_ASISTIRA).'\', 0)">No asistir&aacute;</li>';
            $contFam = _createCardFamilias($familias, $opc, ID_PERMISO_EVENTO, 'editar')['cardsFamilia'];
        }
        
        $data['cardsFamilia'] = $contFam;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function postulantesHorario(){
        $horario = _simpleDecryptInt(_post("horario"));
        $postulantes = $this->m_detalle_evento->getContactosByHorario(_getSesion('id_evento_detalle'), $horario);
        $data['tabla'] = _createTableHorasCitaPostulantes($postulantes);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buscarApoyoAdmSede(){
        $nombre = _post("nombre");
        $personas = $this->m_detalle_evento->buscarPersonaApoyoAdmSede($nombre, _getSesion('id_evento_detalle'));
        $data['tabla'] = _createTableAuxiliaresApoyoAdministrativoSede($personas);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarApoyoAdministrativoSede(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idPersona = _simpleDecryptInt(_post("persona"));
            if($idPersona == null){
                throw new Exception(ANP);
            }
            $recursoEvento = $this->m_utils->getById("admision.recurso_x_evento", "id_recurso_x_evento", "id_evento", _getSesion('id_evento_detalle'));
            if($recursoEvento == null){
                $recursoEvento = $this->m_detalle_evento->crearRecursoEventoFictisio(_getSesion('id_evento_detalle'))['id'];
            }
            $arrayInsert = array("id_persona"          => $idPersona,
                                 "id_recurso_x_evento" => $recursoEvento,
                                 "asistencia"          => INASISTENCIA_APOYO_ADM,
                                 "id_persona_registro" => $this->_idUserSess
            );
            $data = $this->m_detalle_evento->insertPersonaRecursoApoyoAdm($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $personas = $this->m_detalle_evento->getPersonasApoyoAdmSede(_getSesion('id_evento_detalle'));
                $data['tabla'] = _createTableRecursosHumanosSede($personas);
                $nombre = _post("nombre");
                $personas = $this->m_detalle_evento->buscarPersonaApoyoAdmSede($nombre, _getSesion('id_evento_detalle'));
                $data['tablaBusqueda'] = _createTableAuxiliaresApoyoAdministrativoSede($personas);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function eliminarApoyoAdmSede(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idPersona = _simpleDecryptInt(_post("persona"));
            if($idPersona == null){
                throw new Exception(ANP);
            }
            $recursoEvento = $this->m_utils->getById("admision.recurso_x_evento", "id_recurso_x_evento", "id_evento", _getSesion('id_evento_detalle')); 
            $data = $this->m_detalle_evento->deletePersonaRecursoEvento($recursoEvento, $idPersona);
            if($data['error'] == EXIT_SUCCESS){
                $personas = $this->m_detalle_evento->getPersonasApoyoAdmSede(_getSesion('id_evento_detalle'));
                $data['tabla'] = _createTableRecursosHumanosSede($personas);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
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