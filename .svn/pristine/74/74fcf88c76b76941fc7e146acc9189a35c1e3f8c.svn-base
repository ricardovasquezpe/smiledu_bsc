<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_agenda extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    private $_client     = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_agenda');
        $this->load->model('m_utils');
        $this->load->helper('cookie');
        
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_AGENDA, SPED_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
        
        $params = array('tipo_auth'   => 'CONSTANTES',
                        'redirec_uri' => REDIRECT_URI_CALED_AGEDA_SPED,
                        'app_name'    => 'Google API',
                        'scopes'      => array('calendar', 'calendar_readonly', 'email', 'profile')
        );
        $this->load->library('../Google_API', $params);
        $this->_client = $this->google_api->client();
    }

    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SPED, SPED_FOLDER);
        $data['calendarData']               = $this->populateFullCalendar();
        $data['optTipoVisita']              = $this->comboTipoVisitaHTML();
        $data['optFiltro']                  = $this->comboFiltros();

        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Agenda';
        $data['ruta_logo']        = MENU_LOGO_SPED;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SPED;
        $data['nombre_logo']      = NAME_MODULO_SPED;
        
        $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_SPED, $this->_idUserSess);
        $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
        //REVISAR SI C_agenda_bypass_calendar.php redirecciono aqui
        if(_getSesion('code_agenda_calendar') != null) {
            $data['code_calendar'] = true;
        }
        //MENU Y CABECERA
        $data['menu'] = $this->load->view('v_menu', $data, true);
        $this->load->view('v_agenda', $data);
    }
    
    function cambioRol() {
        $idRolEnc = $this->input->post('id_rol');
        $idRol = _simple_decrypt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array("id_rol"     => $idRol,
                          "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $idRol     = $this->session->userdata('nombre_rol');
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }   
    
    function populateFullCalendar() {
        $data = $this->m_agenda->getEvaluacionesCalendario($this->_idUserSess);
        $idx = 0;
        foreach ($data as $d) {
            $data[$idx]['id'] = _encodeCI($data[$idx]['id']);
            $idx++;
        }
        return $data;
    }
    
    function addToGoogleCalendar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $data['url'] = $this->google_api->loginUrl();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function removeCode() {
        $this->session->unset_userdata('code_agenda_calendar');
        echo true;
    }

    function getDetalleEvento() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEvent = _decodeCI(_post('idEvent'));
            if($idEvent == null) {
                throw new Exception(ANP);
            }
            //Verificar si el evento pertenece al usuario en sesion
            
            //
            $estado = $this->m_utils->getById('sped.evaluacion', 'estado_evaluacion', 'id_evaluacion', $idEvent);
            $data['evaluar'] = false;
            $data['borrar']  = false;
            if($estado == NO_EJECUTADO) {
                $data['evaluar'] = true;
            }
            if($estado == PENDIENTE) {
                $data['evaluar'] = true;
                $data['borrar']  = true;
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function editarDatosEvent() {
        $id          = _post('id');
        $descripcion = _post('descripcion');
        $fecha       = _post('fecha');
        if($fecha <= date('Y-m-d')) {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'No se puede editar una evaluacion pasada';
        } else {
            $arrayUpdate = array('descripcion'      => $descripcion,
                                 'fecha_evaluacion' => $fecha
            );
            $data = $this->m_agenda->updateDatosEvent($arrayUpdate,$id);
            $data['id']       = $id;
            $data['title']    = $descripcion;
            $data['start']    = $fecha;
            $data['editable'] = true;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    function comboTipoVisitaHTML(){
        $opt = '<option value="OPINADA">OPINADA</option>
                <option value="SEMI OPINADA">SEMI-OPINADA</option>
                <option value="NO OPINADA">NO OPINADA</option>';
        return $opt;
    }
    
    function comboFiltros(){
        $opt = '<option value="D">DOCENTE</option>
                <option value="C">CURSO</option>
                <option value="A">AULA</option>';
        return $opt;
    }
    
    function buildTableHorariosHTML($condicion, $texto, $idSedeSubDirector) {
        $listaHorarios = ($texto != null || $condicion != null) ? $this->m_agenda->getHorariosByDocenteCurso($condicion, $texto, $idSedeSubDirector) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" data-page-size="5"
			                                   data-show-columns="false" data-search="false" id="tb_horarios">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#', 'class' => 'text-left');
        $head_2 = array('data' => 'Sel.', 'class' => 'text-center', 'data-field' => 'radio_button');
        $head_3 = array('data' => 'Docente', 'class' => 'text-left');
        $head_4 = array('data' => 'Curso', 'class' => 'text-left');
        $head_5 = array('data' => 'Aula', 'class' => 'text-left');
        $val = 0;
        $this->table->set_heading($head_1, $head_2, $head_3, $head_4,$head_5);
        foreach($listaHorarios as $row) {
            $val++;
            $idMain = _encodeCI($row->nid_main);
            $row_1 = array('data' => $val, 'class' => 'text-left');
            $radio = '  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-b-20" for="radio-'.$val.'">
                            <input type="radio" id="radio-'.$val.'" class="mdl-radio__button" name="radioVals" value="'.$idMain.'" data-id-main="'.$idMain.'"
                                   onclick="clickRadio($(this));" data-idx="'.$val.'">
                            <span class="mdl-radio__label"></span>
                        </label>';
            $row_2 = array('data' => $radio, 'class' => 'text-center');
            $row_3 = array('data' => $row->docente, 'class' => 'text-left');
            $row_4 = array('data' => $row->curso, 'class' => 'text-left');
            $row_5 = array('data' => $row->aula, 'class' => 'text-left');
            $this->table->add_row($row_1,$row_2,$row_3,$row_4,$row_5);
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function getHorariosByFiltro() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $texto     = _post('texto');
            $texto     = trim($texto);
            $condicion = _post('condicion');
            if($texto == null || $condicion == null) {
                throw new Exception('Debe ingresar los datos');
            }
            $data['tbHorarios'] = $this->buildTableHorariosHTML($condicion, $texto, _getSesion('id_sede_trabajo'));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarNuevaEvaluacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            if(_getSesion('id_sede_trabajo') == null) {
                throw new Exception('Sede no configurada');
            }
            $fecha       = implode('-', array_reverse(explode('/', _post('fecha') )));
            $actualDate  = date('Y-m-d');
            if($fecha < $actualDate) {
                throw new Exception('La fecha de evaluaci&oacute;n no puede ser menor o igual a la actual');
            }
            //EVALUA TIPO DE VISITA VALIDO
            $horario    = _decodeCI(_post('horario'));
            $tipoVisita = _post('tipoVisita');
            $horaInicio = _post('horaInicio');
            $horaFin    = _post('horaFin');
            if($tipoVisita == null || $horaFin == null || $horaInicio == null) {
                throw new Exception('No debe dejar campos vac&iacute;os');
            }
            if($horario == null) {
                throw new Exception('El horario no es v&aacute;lido');
            }
            $horasIni = date('h:i A' ,strtotime($horaInicio));
            $horasFin = date('h:i A' ,strtotime($horaFin));

            if($horasIni > $horasFin) {
                throw new Exception('La hora de inicio debe ser mayor que la hora de fin');
            }
            /*$cuenta = $this->m_agenda->evaluaCruceHoras($fechaInicio, $fechaFin);
            if($cuenta > 0) {
                throw new Exception('No se puede agregar la evaluaci&oacute;n por un cruce');
            }*/
            $idRubrica = $this->m_agenda->getIdRubricaEvaluar();
            if($idRubrica == null) {
                throw new Exception('No se encontr&oacute; una r&uacute;brica con qu&eacute; evaluar');
            }
            $docenteData = $this->m_agenda->getDataDocente_A_Evaluar($horario);
            if(!isset($docenteData['id_persona']) ) {
                throw new Exception('Hubo un error al traer los datos del/a docente');
            }
            if(!isset($docenteData['id_sede_control']) ) {
                throw new Exception('El/la docente no tiene configurado la sede de control');
            }
            if(!isset($docenteData['id_nivel_control']) ) {
                throw new Exception('El/la docente no tiene configurado el nivel de control');
            }
            if(!isset($docenteData['id_area_especifica']) ) {
                throw new Exception('El/la docente no tiene configurado el &aacute;rea espec&iacute;fica');
            }
            
            $insert = array('estado_evaluacion' => PENDIENTE,
                            'fecha_inicio'      => $fecha.' '.$horaInicio,
                            'fecha_fin'         => $fecha.' '.$horaFin,
                            'id_evaluador'      => $this->_idUserSess,
                            'id_horario'        => $horario,
                            'tipo_visita'       => $tipoVisita,
                            'id_rubrica'        => $idRubrica,
                            'id_evaluado'       => $docenteData['id_persona'],
                            'id_sede_evaluador' => _getSesion('id_sede_trabajo'),
                            'id_sede'           => $docenteData['id_sede_control'],
                            'id_nivel'          => $docenteData['id_nivel_control'],
                            'id_area'           => $docenteData['id_area_especifica'],
                            'flg_google_cal'    => (_getSesion('code_agenda_calendar') != null ? '1' : null)
            );
            $data = $this->m_agenda->grabarNuevaEvaluacion($insert, $this->_idUserSess, $this->_idRol, $fecha);
            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }
            if($data['error'] == EXIT_SUCCESS) {
                $data['error'] = EXIT_ERROR;
                $data['calendarioData'] = json_encode($this->populateFullCalendar(), JSON_NUMERIC_CHECK);
                ////////////////////////////////////////
                if(_getSesion('code_agenda_calendar') != null) {
                    $sedeDesc = $this->m_utils->getById('sede', 'desc_sede', 'nid_sede', _getSesion('id_sede_trabajo'));
                    $horasIni = date('h:i:s' ,strtotime($horaInicio));
                    $horasFin = date('h:i:s' ,strtotime($horaFin));

                    try {
                        $service = new Google_Service_Oauth2($this->_client);
                        $this->_client->authenticate(_getSesion('code_agenda_calendar'));
                        $this->_client->setAccessToken($this->_client->getAccessToken());
                        $user = $service->userinfo->get();
                        $correos = $this->m_utils->getCamposById('persona', array('correo_inst', 'correo_admi'), 'nid_persona', _getSesion('nid_persona'));
                        if($user->email != $correos['correo_inst']) {
                            if($user->email != $correos['correo_admi']) {
                                $data['msj'] = 'Tu cuenta de correo no está asociada con tu cuenta SMILEDU';
                            }
                        }
                        ////////////
                        $evento = array(
                            'summary'     => utf8_encode('Eval. '.$docenteData['docente']),
                            'location'    => utf8_encode('Sede '.$sedeDesc),
                            'description' => utf8_encode('Evaluación pendiente para '.$docenteData['docente'].' en la sede '.$sedeDesc),
                            'start' => array(
                                'dateTime' => $fecha.'T'.$horasIni.'-05:00'/*,
                                'timeZone' => 'America/Los_Angeles',*/
                            ),
                            'end' => array(
                                'dateTime' => $fecha.'T'.$horasFin.'-05:00'/*,
                                'timeZone' => 'America/Los_Angeles',*/
                            ),
                            /*'recurrence' => array(
                             'RRULE:FREQ=DAILY;COUNT=2'
                            ),
                            'attendees' => array(
                                array('email' => 'lpage@example.com'),
                                array('email' => 'sbrin@example.com'),
                            ),*/
                            'reminders' => array(
                                'useDefault' => FALSE,
                                'overrides' => array(
                                    array('method' => 'email', 'minutes' => 24 * 60),
                                    array('method' => 'popup', 'minutes' => 180),
                                    array('method' => 'sms'  , 'minutes' => 45)
                                ),
                            ),
                        );
                        if($docenteData['destino'] != null && $tipoVisita == VISITA_OPINADA) {
                            $evento['attendees'] = array( array('email' => $docenteData['destino'] ) );
                        }
                        $event = new Google_Service_Calendar_Event($evento);
                        $calendarId = 'primary';
                        $calendar = new Google_Service_Calendar($this->_client);
                        $event = $calendar->events->insert($calendarId, $event);
                    } catch (Exception $e) {
                        $dara['error_calendar'] = true;
                        $data['msj'] = 'Hubo un error con la conexión a Google Calendar, inténtalo nuevamente';
                    }
                    $this->session->unset_userdata('code_agenda_calendar');
                }
                $data['error'] = EXIT_SUCCESS;
                $this->db->trans_commit();
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
            $this->m_agenda->resetSerialEvaluacion();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeEvaluacionDate() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $id            = $this->input->post('eventId');
        $dateTimeI     = explode('T', _post('fechaInicio'));
        $fechaI        = $dateTimeI[0];
        $horaI         = $dateTimeI[1];
        $dateTimeF     = explode('T', _post('fechaFin'));
        $fechaF        = $dateTimeF[0];
        $horaF         = $dateTimeF[1];
        $descripcion   = _post('descripcion');
        $fInicioOrigen = explode(' ', $this->m_utils->getById('evaluacion', 'fecha_inicio', 'id_evaluacion', $id, null))[0];
        try {
            if($id == null) {
                throw new Exception('No se actualiz&oacute; la fecha');
            }
            if($fInicioOrigen < date('Y-m-d')) {
                throw new Exception('No se puede editar evaluaciones pasadas');
            }
            if($fechaI < date('Y-m-d')) {
                throw new Exception('No se puede pasar a fechas menores o igual a la actual');
            }
            $fechaInicio = $fechaI.' '.$horaI;
            $fechaFin    = $fechaF.' '.$horaF;
            $cuenta = $this->m_agenda->evaluaCruceHoras($fechaInicio, $fechaFin);
            if($cuenta > 0) {
                throw new Exception('No se puede cambiar la evaluaci&oacute;n por un cruce');
            }
            $arrayUpdate = array('fecha_inicio' => $fechaInicio,
                                 'fecha_fin'    => $fechaFin);
            $data = $this->m_agenda->updateDatosEvent($arrayUpdate, $id);
            $data['id']    = $id;
            $data['title'] = $descripcion;
            $data['start'] = $fechaInicio;
            $data['end']   = $fechaFin;
        } catch (Exception $e) {
            $data['msj']   = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }   
    
    function goToEvaluarPendiente() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $id = _decodeCI(_post('idEvaluacion'));
            if($id == null || $this->_idUserSess == null) {
                throw new Exception(ANP);
            }
            //chequear si la evaluacion es pendiente en BD y si le pertenece al evaluador
            $isOk = $this->m_agenda->validarEvaluacion($id, $this->_idUserSess);
            if(!$isOk) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $idRubrica = $this->m_agenda->getRubricaFromEvaluacion($id);//Evaluar si la rubrica esta ACTIVA
            /*if($idRubrica == null) {
                throw new Exception('No se encontr&oacute; una r&uacute;brica con qu&eacute; evaluar');
            }*/
            $dataEvaluacion = array('id_evaluacion'   => $id,
                                    'id_rubrica_eval' => $idRubrica);
            $this->session->set_userdata($dataEvaluacion);
            $data['url'] = 'C_evaluar';
            //Redirect('sped/c_evaluar','refresh');
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function borrarAgenda() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $id = _decodeCI(_post('idEvaluacion'));
            if($id == null || $this->_idUserSess == null) {
                throw new Exception(ANP);
            }
            //
            $data = $this->m_agenda->borrarEvaluacion($id, $this->_idUserSess);
            if($data['error'] == EXIT_SUCCESS) {
                $data['calendarioData'] = json_encode($this->populateFullCalendar(), JSON_NUMERIC_CHECK);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function setIdSistemaInSession(){
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
        if($idSistema == null || $idRol == null){
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
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