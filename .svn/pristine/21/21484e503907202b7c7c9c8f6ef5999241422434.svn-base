<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_detalle_evaluacion extends CI_Controller {

    private $_idUserSess   = null;
    private $_idRol        = null;
    private $_idEvento     = null;
    private $_idPostulante = null;

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('m_utils_admision');
        $this->load->model('mf_contactos/m_contactos');
        $this->load->model('mf_evaluacion/m_detalle_evaluacion');
        $this->load->model('mf_contactos/m_detalle_contactos');
        $this->load->model('mf_espera/m_espera');
        $this->load->model('mf_confirm_datos/m_confirm_datos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_EVENTO, ADMISION_ROL_SESS);
        $this->_idUserSess   = _getSesion('nid_persona');
        $this->_idRol        = _getSesion(ADMISION_ROL_SESS);
        $this->_idEvento     = _getSesion("idEventoProgreso");
        $this->_idPostulante = _getSesion("idPostulanteEvaluar");
        if($this->_idEvento == null || $this->_idPostulante == null) {
            Redirect('../C_evento', 'refresh');
        }
    }

    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
        $data['comboDiagnostico'] = __buildComboByGrupo(COMBO_RESULTADO_DIAGNOSTICO);
        $data['comboSiNo'] = __buildComboByGrupo(COMBO_SI_NO);
        $data['comboSexo'] = __buildComboByGrupo(COMBO_SEXO);
        $data['comboColegios'] = '<option value="0">' . strtoupper('En casa') . '</option>' . __buildComboColegios();
        $data['comboGradoNivel'] = __buildComboGradoNivel();
        $data['comboTipoDocumento'] = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC);
        $data['enc']   = _simple_encrypt(1);
        $data['noEnc'] = _simple_encrypt(0);
        
        $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
	                               <a href="#tab-1" class="mdl-layout__tab is-active" onclick="">EVALUACI&Oacute;N</a>
	                               <a href="#tab-2" class="mdl-layout__tab" onclick="">ENTREVISTA</a>
                                   <a href="#tab-3" class="mdl-layout__tab"  onclick="">INFORMACI&Oacute;N</a>
                                   <a href="#tab-4" class="mdl-layout__tab"  onclick="">FICHA PSICOL&Oacute;GICA</a>
                                </div>';
        
        $diags = $this->m_detalle_evaluacion->countDiagRealizados($this->_idPostulante, $this->_idEvento);
        $data['completoCursos'] = 0;
        if($diags['cant_cursos'] == $diags['cant_realiz']){
            $data['completoCursos'] = 1;
        }
        

        $entrevista = $this->m_detalle_evaluacion->getDiagnosticoSubidrector($this->_idPostulante, $this->_idEvento);
        $detalleContacto = $this->m_contactos->getDetalleContacto($this->_idPostulante, $this->_idEvento);
            $data['disabledSubDir'] = 'enabled';
        
        $data['onclickEntrevista'] = "guardarEntrevista()";
        $data['disableEntrevista'] = "disabled";
        $data['estadoEntrevista']  = '<i class="mdi mdi-priority_high" id="icon_estado_entrevista" data-toggle="tooltip" style="position: relative; top: 5px; color:green"></i>';
        $data['evaluadorButton'] = null;
        
        if(_getSesion("pantalla_evaluar") == 1){
            $data['fabEntrevista'] = '<ul id="menu" class="mfb-component--br mfb-zoomin" __ON_CLICK__ data-mfb-toggle="hover">
                                	      <li class="mfb-component__wrap mfb-only-btn">
                                              <button class="mfb-component__button--main" data-mfb-label="__NOMBRE_FAB__" style="_COLOR_RED_">
                                                  <i class="mfb-component__main-icon--resting mdi mdi-record_voice_over"></i>
                                                  <i class="mfb-component__main-icon--active  mdi mdi-record_voice_over"></i>
                                              </button>
                                          </li>
                                      </ul>';
            $data['disabledSubDir'] = 'disabled';
            if($detalleContacto['id_entrevistador'] == null) {//No es de nadie. Si entro y aun no es llamado, todo ok, el FAB x defecto y los campos bloqueados
                if($data['completoCursos'] == 1) {
                    $data['disabledSubDir'] = 'disabled';
                    $data['fabEntrevista']  = str_replace("__ON_CLICK__", "onclick=modal('entrevista')", $data['fabEntrevista']);
                    $data['fabEntrevista']  = str_replace("__NOMBRE_FAB__", "Llamar a familia", $data['fabEntrevista']);
                    $data['onclickEntrevista'] = null;
                } else {
                    $data['fabEntrevista']     = null;
                    $data['onclickEntrevista'] = null;
                }
            } else {//ES DE ALGUIEN
                if($detalleContacto['id_entrevistador'] == $this->_idUserSess) { // HA SIDO LLAMADO POR MI
                    if($detalleContacto['estado_eval'] == ESTADO_SU_TURNO_CONTACTO) {//Si entro y esta llamando, mostrar telf timbrando, abrir modal de inicio y campos bloqueados
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = str_replace("__ON_CLICK__", "onclick=modal('entrevista')", $data['fabEntrevista']);
                        $data['fabEntrevista']  = str_replace("__NOMBRE_FAB__", "Entrevistar", $data['fabEntrevista']);
                        $data['modalInit'] = 'SI';
                        $data['onclickEntrevista'] = null;
                    } else if($detalleContacto['estado_eval'] == ESTADO_PERDIO_TURNO_CONTACTO) {//Si entro y perdió turno, todo ok, el FAB x defecto y campos bloqueados
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = str_replace("__ON_CLICK__", "onclick=modal('entrevista')", $data['fabEntrevista']);
                        $data['fabEntrevista']  = str_replace("__NOMBRE_FAB__", "Entrevistar", $data['fabEntrevista']);
                        $data['onclickEntrevista'] = null;
                    } else if($detalleContacto['estado_eval'] == ESTADO_EN_ENTREVISTA) {//Si entro y ya esta en entrevista, mostrar el FAB con la opcion de cancelar. Campos desbloqueados
                        $data['disabledSubDir'] = null;
                        $data['fabEntrevista']  = str_replace("__ON_CLICK__", "onclick=modal('cancel_entrevista')", $data['fabEntrevista']);
                        $data['fabEntrevista']  = str_replace("__NOMBRE_FAB__", "Cancelar entrevista", $data['fabEntrevista']);
                        $data['fabEntrevista']  = str_replace("_COLOR_RED_", "background-color:red!important", $data['fabEntrevista']);
                        $data['onclickEntrevista'] = "guardarEntrevista()";
                    } else if($detalleContacto['estado_eval'] == ESTADO_ENTREVISTADO) {//Si entro y ya esta entrevistado, no mostrar el FAB, campos son editables
                        $data['disabledSubDir'] = null;
                        $data['fabEntrevista']  = null;
                        $data['onclickEntrevista'] = "guardarEntrevista()";
                    } else if($detalleContacto['estado_eval'] == ESTADO_CANCELADA) {//Si entro y esta cancelada, todo ok, el FAB x defecto y los campos bloqueados
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = str_replace("__ON_CLICK__", "onclick=modal('entrevista')", $data['fabEntrevista']);
                        $data['fabEntrevista']  = str_replace("__NOMBRE_FAB__", "Entrevistar", $data['fabEntrevista']);
                        $data['onclickEntrevista'] = null;
                    }
                } else {//NO HA SIDO LLAMADO POR MI
                    if($detalleContacto['estado_eval'] == ESTADO_SU_TURNO_CONTACTO) {//Si entro y esta llamando, no mostrar nada
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = null;
                        $data['onclickEntrevista'] = null;
                    } else if($detalleContacto['estado_eval'] == ESTADO_PERDIO_TURNO_CONTACTO) {//Si entro y perdió turno, FAB x defecto, pero advertir que perdió llamada con tal entrevistador
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = str_replace("__ON_CLICK__", "onclick=modal('entrevista')", $data['fabEntrevista']);
                        $data['fabEntrevista']  = str_replace("__NOMBRE_FAB__", "Entrevistar", $data['fabEntrevista']);
                        $data['onclickEntrevista'] = null;
                    } else if($detalleContacto['estado_eval'] == ESTADO_EN_ENTREVISTA) {//Si entro y ya esta en entrevista, no mostrar el FAB y campos bloqueados
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = null;
                        $data['onclickEntrevista'] = null;
                    } else if($detalleContacto['estado_eval'] == ESTADO_ENTREVISTADO) {//Si entro y ya esta entrevistado, no mostrar el FAB, campos bloqueados
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = null;
                        $data['onclickEntrevista'] = null;
                    } else if($detalleContacto['estado_eval'] == ESTADO_CANCELADA) {//Si entro y esta cancelada, todo ok, el FAB x defecto y los campos bloqueados. Advertir que tal entrevistador la canceló
                        $data['disabledSubDir'] = 'disabled';
                        $data['fabEntrevista']  = str_replace("__ON_CLICK__", "onclick=modal('entrevista')", $data['fabEntrevista']);
                        $data['fabEntrevista']  = str_replace("__NOMBRE_FAB__", "Entrevistar", $data['fabEntrevista']);
                        $data['onclickEntrevista'] = null;
                    }
                }
            }
        }
        
        
        if($entrevista != null) {//EL ENTREVISTADOR YA HA REGISTRADO LOS DATOS DE ENTREVISTA
            if($entrevista['id_evaluador'] == $this->_idUserSess){
                $data['disabledSubDir'] = null;
                $data['fabEntrevista']  = null;
            } else {
                $data['disabledSubDir'] = 'disabled';
                $data['fabEntrevista']  = null;
                $data['onclickEntrevista'] = null;
            }
            $data['comboDiagnostico']       = __buildComboByGrupo(COMBO_RESULTADO_DIAGNOSTICO);
            $data['diagFinal']              = _simple_encrypt($entrevista['diagnostico_final']);
            $data['tallerVeranoEntrevista'] = $entrevista['taller_verano'];
            $data['observacionEntrevista']  = $entrevista['obser_diagnostico'];
            $data['archivosEntrevista']     = $this->createContArchivo($entrevista['documentos']);
            $data['onclickEntrevista']      = "editarEntrevista()";
            $data['disableEntrevista']      = null;
            $data['estadoEntrevista']  = '<i class="mdi mdi-check" id="icon_estado_entrevista" data-toggle="tooltip" style="position: relative; top: 5px; color:green"></i>';
            $data['fotoEntrevista'] = '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$entrevista['foto_persona'].'" data-toggle="tooltip" data-placement="bottom" id="foto_persona_entrevista" data-original-title="'.$entrevista['nombre_evaluador'].' ('._fecha_tabla($entrevista['fecha_registro'], 'd/m/Y h:i A').') ">'; 
        }
        $data['cardEntrevista'] = $this->load->view('V_card_entrevista', $data, true);

        $diagnosticos = $this->m_detalle_evaluacion->getDiagnosticosByEstudiante($this->_idPostulante, $this->_idEvento);
        $cards = $this->createCardDiagnosticos($diagnosticos);
        $data['cardsDiagnosticos'] = $cards;
        $data['apePaterno'] = $detalleContacto['ape_paterno'];
        $data['apeMaterno'] = $detalleContacto['ape_materno'];
        $data['nombres'] = $detalleContacto['nombres'];
        $data['sexo'] = ($detalleContacto['sexo'] != null) ? _simple_encrypt($detalleContacto['sexo']) : null;
        $data['gradoNivel'] = (strlen($detalleContacto['gradonivel']) != 0) ? _simple_encrypt($detalleContacto['gradonivel']) : null;
        $data['sedeInt'] = ($detalleContacto['sede_interes'] != null) ? _simple_encrypt($detalleContacto['sede_interes']) : null;
        $data['colegioProcedencia'] = ($detalleContacto['colegio_procedencia'] != null) ? _simple_encrypt($detalleContacto['colegio_procedencia']) : null;
        $data['fechaNac'] = _fecha_tabla($detalleContacto['fecha_nacimiento'], 'd/m/Y');
        $data['tipoDocPostulante'] = ($detalleContacto['tipo_documento'] != null) ? ($detalleContacto['tipo_documento']) : null;
        $data['nroDoc'] = $detalleContacto['nro_documento'];
        $data['observacion'] = $detalleContacto['obser_solicitud'];
        
        $data['ficha'] = $this->getFichaPsicologicaContacto($this->_idPostulante);
        
        $data['return'] = '';
        $data['titleHeader'] = $this->m_detalle_evaluacion->getNombreContactoAbrev($this->_idPostulante);
        $data['ruta_logo'] = MENU_LOGO_ADMISION;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
        $data['nombre_logo'] = NAME_MODULO_ADMISION;
        
        $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $menu = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        
        $this->load->view('v_detalle_evaluacion', $data);
    }
    
    function getFichaPsicologicaContacto($idContacto){
        $nivel = $this->m_utils->getById("admision.contacto", "nivel_ingreso", "id_contacto", $idContacto);
        $preguntas = $this->m_confirm_datos->getPreguntasByNivel($nivel, $idContacto);
        $html = null;
        $i = 1;
        foreach ($preguntas as $row){
            $cont_cate = null;
            if($i == 1){
                $categoria = $row->categoria;
                $cont_cate = '<p>'.$categoria.'</p>';
            }
            if($categoria != $row->categoria){
                $categoria = $row->categoria;
                $cont_cate = '<p>'.$categoria.'</p>';
            }
            $tipo = "0";//INPUTTEXT
            $name = "opcion_name_$i";
            if($row->tipo_pregunta == PREGUNTA_1OPCION || $row->tipo_pregunta == PREGUNTA_SINO){//RADIOBUTTON
                $tipo = "1";
            }else if($row->tipo_pregunta == PREGUNTA_NOPCION){//CHECKBOX
                $tipo = "2";
                $name = "opcion_name_".$i."[]";
            }
            $html .= $cont_cate;
            $html .= '<div class="col-sm-4 mdl-input-group text-left divPapa" attr-name="'.$name.'" attr-tipo="'.$tipo.'" attr-pregunta="'._simple_encrypt($row->id_pregunta).'">';
            $html .= '<p>'.$i.') '.$row->descripcion.(($row->flg_obligatorio == 1)?"(*)":"").'</p>';
            $html .= $this->createContalternativas($row->tipo_pregunta, $row->alternativas, $i, $row->id_pregunta, $row->respuesta);
            $html .= '</div>';
            $i++;
        }
        return $html;
    }
    
    function createContalternativas($tipo, $alternativas, $i, $idPregunta, $respuesta){
        $html = null;
        $alternativas = json_decode($alternativas);
        if(count($alternativas) != 0){
            $base = null;
            if($tipo == PREGUNTA_1OPCION){
                $base = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="_ID_ELEMENT_">
                             <input type="radio" id="_ID_ELEMENT_" class="mdl-radio__button" name="_NAME_" value="_VALUE_"  _CHECKED_ disabled>
                             <span class="mdl-radio__label">_DESC_ALTERN_</span>
                         </label>';
            }else if($tipo == PREGUNTA_NOPCION){
                $base = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="_ID_ELEMENT_">
                             <input type="checkbox" id="_ID_ELEMENT_" name="_NAME_[]" class="mdl-checkbox__input" value="_VALUE_"  _CHECKED_ disabled>
                             <span class="mdl-checkbox__label">_DESC_ALTERN_</span>
                         </label>';
            }
            $j = 1;
            if (strpos($respuesta, '[') !== false) {
                $respuesta = json_decode($respuesta);
            }
            foreach($alternativas as $row){
                $base_aux = $base;
                //$base_aux = str_replace('_ID_PREGUNTA_', _simple_encrypt($idPregunta), $base_aux);
                $base_aux = str_replace('_DESC_ALTERN_', $row->valor, $base_aux);
                $base_aux = str_replace('_ID_ELEMENT_', "opcion_".$i."_".$j, $base_aux);
                $base_aux = str_replace('_NAME_', "opcion_name_".$i, $base_aux);
                $base_aux = str_replace('_VALUE_', _simple_encrypt($row->valor), $base_aux);
                if($respuesta == $row->valor){
                    $base_aux = str_replace('_CHECKED_', 'checked', $base_aux);
                }else{
                    if($tipo == PREGUNTA_NOPCION && $respuesta != null && in_array($row->valor, $respuesta)){
                        $base_aux = str_replace('_CHECKED_', 'checked', $base_aux);
                    }
                }
                $html .= $base_aux;
                $j++;
            }
        }else{
            if($tipo == PREGUNTA_SINO){
                $html = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="opcion_'.$i.'_1">
                             <input type="radio" id="opcion_'.$i.'_1" class="mdl-radio__button" name="opcion_name_'.$i.'" value="'._simple_encrypt("SI").'"  '.(($respuesta == "SI")?"checked":null).' disabled>
                             <span class="mdl-radio__label">S&Iacute;</span>
                         </label>
                         <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="opcion_'.$i.'_2">
                             <input type="radio" id="opcion_'.$i.'_2" class="mdl-radio__button" name="opcion_name_'.$i.'" value="'._simple_encrypt("NO").'"  '.(($respuesta == "NO")?"checked":null).' disabled>
                             <span class="mdl-radio__label">NO</span>
                         </label>';
            }else if($tipo == PREGUNTA_LIBRE){
                $html = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="opcion_'.$i.'_1" maxlength="100" attr-pregunta="'._simple_encrypt($idPregunta).'"  value="'.$respuesta.'" disabled>
                            <label class="mdl-textfield__label" for="opcion_'.$i.'_1"></label>
                         </div>';
            }
        }
        return $html;
    }

    function createCardDiagnosticos($data){
        $cards = null;
        $i = 1;
        foreach ($data as $diag) {
            $idConfigEval = _simple_encrypt($diag->id_config_eval);
            $idDiagnostico = (($diag->id_diagnostico == NULL) ? NULL : _simple_encrypt($diag->id_diagnostico));
            $disabledGuardarEditar = 'disabled';
            $disabled   = 'disabled';
            $nameButton = 'Guardar';
            $onclickButton = 'guardarDiagnostico('.$i.', \''.$idConfigEval.'\', this)';
            $estado = '<i class="mdi mdi-priority_high" id="icon_estado_'.$i.'" data-toggle="tooltip" style="position: relative; top: 5px; color:green"></i>';
            if($diag->id_diagnostico != null){
                $disabled = null;
                $nameButton = 'Editar';
                $onclickButton = 'editarDiagnostico('.$i.', \''.$idDiagnostico.'\', this)';
                $estado = '<i class="mdi mdi-check" id="icon_estado_'.$i.'" data-toggle="tooltip" style="position: relative; top: 5px; color:green"></i>';
            }
            if($diag->id_evaluador != null && $diag->id_evaluador == $this->_idUserSess){
                $disabledGuardarEditar = null;
            }else if($diag->id_diagnostico == null){
                $disabledGuardarEditar = null;
            }
            $disabledConfig = null;
            $select = '<option value="">Seleccione un diagn&oacute;stico (*)</option>';
            if(count(json_decode($diag->opciones_eval))==0){
                $select = '<option value="">--Falta configurar niveles (*) --</option>';
            }
            $foto = '<i id="foto_persona_'.$i.'"></i>';
            if($diag->fecha_registro != null) {
                $foto = '<img class="img-circle m-l-5" WIDTH=25 HEIGHT=25 src="'.$diag->foto_persona.'" data-toggle="tooltip" data-placement="bottom" id="foto_persona_'.$i.'" data-original-title="'.$diag->nombre_evaluador.' ('._fecha_tabla($diag->fecha_registro, 'd/m/Y h:i A').') ">';
            }
            $cards .= '    <div class="mdl-card mdl-evaluator">
                                <div class="mdl-card__title p-b-0">
                                    <h2 class="mdl-card__title-text">'.$diag->descripcion.'</h2>
                                </div>
                                <div class="mdl-card__supporting-text br-b p-r-25 p-l-25">
                                    <div class="row">
                                        <div class="col-sm-12 mdl-input-group m-b-0">
    				                        <div class="mdl-icon"><i class="mdi mdi-comment"></i></div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label no-transparent">
                                                <textarea class="mdl-textfield__input" type="text" rows= "9" id="observaciones_'.$i.'" name="observaciones_'.$i.'" style="resize:none">'.$diag->obser_diagnostico.'</textarea>   
                                                <label class="mdl-textfield__label" for="observaciones_'.$i.'">'.$diag->titulo_observacion.' (*)</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mdl-input-group">
                                            <div class="mdl-icon"><i class="mdi mdi-build"></i></div>                                                    
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label no-transparent">
                                                <textarea class="mdl-textfield__input" type="text" rows= "3" id="select_taller_verano_'.$i.'" name="select_taller_verano_'.$i.'" style="resize:none">'.$diag->taller_verano.'</textarea>         
                                                <label class="mdl-textfield__label" for="select_taller_verano_'.$i.'">&#191;A Taller&#63;</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="left: 50px; text-align:left">            
                                            <button id="btnIndicadores'.$i.'" class="mdl-button mdl-js-button mdl-js-ripple-effect m-l-0" onclick="abrirModalIndicadores('.$i.', \''.$idDiagnostico.'\', \''.$idConfigEval.'\', \''.$diag->descripcion.'\', \''.$disabledGuardarEditar.'\')"  data-toggle="tooltip" data-placement="bottom" data-original-title="Indicadores" style="text-transform: capitalize;">
                                                <i class="mdi mdi-flag" style="position: relative; top: 5px; margin-right: 5px; color: #2196F3"></i>Indicadores (*)
                                            </button>
                                            <!--button id="btnSubirArchivo'.$i.'" class="mdl-button mdl-js-button mdl-js-ripple-effect" style="" onclick="abrirModalAgregarDocumento('.$i.', \''.$idDiagnostico.'\')" disabled data-toggle="tooltip" data-placement="bottom" data-original-title="Adjuntar doc.">
                                                <i class="mdi mdi-attach_file" style="position: relative; top: 5px; margin-right: 5px;"></i><span style="text-transform: capitalize">Adjuntar doc.<span>
                                            </button-->
                                        </div>    
                                        <div style="height: 40px;" class="col-sm-10 col-md-10 p-l-15 text-left" id="cont_archivos_'.$i.'">'.$this->createContArchivo($diag->documentos).'</div>
                                        <div id="content_publicaciones_'.$i.'"></div>
                                        <div class="col-sm-12 mdl-input-group">
                                            <div class="mdl-icon"><i class="mdi mdi-straighten"></i></div>
                                            <div class="mdl-select p-t-10 no-transparent">
                                                <select id="select_diagnostico_'.$i.'" name="select_diagnostico_'.$i.'" class="form-control selectButton" data-live-search="true" data-noneSelectedText="Seleccione un diagn&oacute;stico">
                					                '.$select.'
                					                '. $this->createComboOpciones($diag->opciones_eval, $diag->diagnostico_final) .'
                					            </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" id="btnSaveEditEntrevista'.$i.'" disabled> Limpiar</button>                					                    
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnGuardar'.$i.'" onclick="'.$onclickButton.'" '.$disabledGuardarEditar.'> '.$nameButton.' </button>
                                </div>
                                <div class="mdl-card__menu">  
                                    <span class="nomb-colaborador" id="nombre_eva'.$i.'">'.$diag->nombre_evaluador_abreviado.'</span>
                                    '.$foto.'
                                    '.$estado.'
                                </div>
                            </div>';
            $i++;
        }
        return $cards;
    }
    
    function createComboOpciones($data, $select){
        $return = null;
        $data = json_decode($data);
        if(count($data) != 0){
            foreach($data as $row){
                $selected = null;
                if($row->descripcion == $select){
                    $selected = 'selected';
                }
                $return .= '<option value="'.$row->descripcion.'" '.$selected.'>'.$row->descripcion.'</option>';
            }
        }
        return $return;
    }
    
    public function agregarArchivos(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idDiagnostico = _simpleDecryptInt(_post('diagnostico'));
            if($idDiagnostico == null){
                throw new Exception(ANP);
            }
            $data = $this->subirImagenes($_FILES);
            if ($data['error'] == EXIT_SUCCESS) {
                $this->load->library('m_pdf');
                $pdf = $this->m_pdf->load();
                $pdfArray['pdfObj'] = $pdf;
                $pdfArray['countImages'] = $data['countImages'];
                $nombre = $nombreArchivo = __generateRandomString(6) . '_' . date("dmhis") . '.pdf';
                $pdfArray['nombreArchivo'] = $nombre;
                for ($i = 0; $i < $data['countImages']; $i ++) {
                    $pdfArray['img' . $i] = $data['img' . $i];
                }
                $this->load->view('V_pdf_documento', $pdfArray);
                for ($i = 0; $i < $data['countImages']; $i ++) {
                    unlink("./uploads/modulos/admision/imagenes/" . $data['img' . $i]);
                }
                $arrayInsert = array('id_persona_registro' => _getSesion("nid_persona"),
                                     'ruta'                => $nombre,
                                     'id_diagnostico'      => $idDiagnostico);
                $data = $this->m_detalle_evaluacion->insertDocumentoPostulante($arrayInsert);
                if($data['error'] == EXIT_SUCCESS){
                    $archivos = $this->m_detalle_evaluacion->getDocumentosByTipo($idDiagnostico);
                    $data['docs'] = $this->createContArchivo($archivos);
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function subirImagenes($files){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            $c = 0;
            $arrayBatch = array();
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '1024';
            $this->load->library('upload');
            if (count($files) == 0) {
                throw new Exception('Selecciona una imagen');
            }
            foreach ($files as $fieldname => $fileObject) {
                $ext = pathinfo($fileObject['name'], PATHINFO_EXTENSION);
                $nombreFoto = __generateRandomString(6) . '_' . date("dmhis") . $c;
                $nombreFotoCompleto = $nombreFoto . '.' . $ext;
                $tipo = null;
                $path = "./uploads/modulos/admision/imagenes/";
                $config['upload_path'] = $path;
                $config['file_name'] = $nombreFotoCompleto;
                if (! empty($fileObject['name'])) {
                    $this->upload->initialize($config);
                    if (! $this->upload->do_upload($fieldname)) {
                        throw new Exception(utf8_decode($this->upload->display_errors()));
                    }
                    $data['img' . $c] = $nombreFotoCompleto;
                    $c ++;
                } else {
                    throw new Exception('Seleccione un archivo');
                }
            }
            if ($c != count($_FILES)) {
                throw new Exception('No se ha podido subir todas las imagenes');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = MSJ_INSERT_SUCCESS;
            $data['countImages'] = count($_FILES);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    public function createContArchivo($archivos){
        $arch = null;
        $archivos = explode(',', $archivos);
        for ($i = 0; $i < count($archivos); $i ++) {
            if($archivos[$i] != null){
                $arch .= '<a href="' . RUTA_DOCUMENTOS_ADMISION . $archivos[$i] . '" data-toggle="tooltip" data-placement="bottom" data-original-title="Descargar" download><i class="mdi mdi-picture_as_pdf"></i></a>';
            }
        }
        return $arch;
    }

    function getSedesByNivel(){
        $valorNivel = (_post('valorNivel')) == null ? null : _simple_decrypt(_post('valorNivel'));
        if ($valorNivel != null) {
            $valorNivel = explode('_', $valorNivel);
            $data['comboSedes'] = __buildComboSedesAdmision($valorNivel[1]);
        } else 
            if ($valorNivel == null) {
                $data['comboSedes'] = __buildComboSedesAdmision(null);
            }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function onChangeCampo(){ // INFORMACION
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        
        try {
            $enc = _post('enc') != null ? _simpleDecryptInt(_post('enc')) : null;
            $valor = _post('valor') == '' ? null : _post('valor');
            $campo = _post('campo');
            $personaRegistro = _getSesion("nid_persona");
            $idEstudiante = _getSesion("idPostulanteEvaluar");
            
            if ($valor == null && $campo == 'grado_nivel') {
                throw new Exception('Debe seleccionar un grado y nivel');
            }
            if ($valor == null && $campo == 'sede_interes') {
                throw new Exception('Debe seleccionar una sede de inter&eacute;s');
            }
            if ($enc == 1 && $valor != null && $campo != 'grado_nivel') { // ENCRIPTADO
                $valor = _simpleDecryptInt(_post('valor'));
            } else 
                if ($enc == 1 && $valor != null && $campo == 'grado_nivel') {
                    $valor = _simple_decrypt(_post('valor'));
                }
            
            $arrayUpdate = array(
                $campo => utf8_decode($valor)
            );
            if ($campo == 'grado_nivel') {
                if ($valor != null) {
                    $valor = explode('_', $valor);
                    $arrayUpdate = array(
                        'grado_ingreso' => $valor[0],
                        'nivel_ingreso' => $valor[1],
                        'sede_interes' => null
                    );
                } else 
                    if ($valor == null) {
                        $arrayUpdate = array(
                            'grado_ingreso' => null,
                            'nivel_ingreso' => null,
                            'sede_interes' => null
                        );
                    }
            }
            
            $data = $this->m_detalle_contactos->updateCampoDetalleContacto($arrayUpdate, $idEstudiante);
            
            if ($data['error'] == EXIT_SUCCESS) {
                $datos = $this->m_contactos->getDetalleContacto($idEstudiante);
                $flg_campos = CAMPOS_OBLIGATORIOS_INCOMPLETOS;
                if (strlen(trim($datos['ape_paterno'])) != 0 && strlen(trim($datos['ape_materno'])) != 0 && strlen(trim($datos['nombres'])) != 0 && $datos['ape_materno'] != 'POSTULANTE' && $datos['nro_documento'] != NULL) {
                    $flg_campos = CAMPOS_OBLIGATORIOS_COMPLETOS;
                }
                
                $arrayUpdate3 = array(
                    'flg_campos_obligatorios' => $flg_campos
                );
                $data = $this->m_detalle_contactos->updateCampoDetalleContacto($arrayUpdate3, $idEstudiante);
                
                if ($data['error'] == EXIT_SUCCESS) {
                    $familia = $this->m_detalle_contactos->getFlgCampoObligatoriosFamilia($idEstudiante);
                    $updateEstado = ESTADO_CONTACTO_CONTACTADO;
                    foreach ($familia as $fam) {
                        if ($fam->flg_campos_obligatorios != CAMPOS_OBLIGATORIOS_COMPLETOS) {
                            $updateEstado = ESTADO_CONTACTO_POR_CONTACTAR;
                        }
                    }
                    $arrayUpdate2 = array(
                        'estado' => $updateEstado
                    );
                    $data = $this->m_detalle_contactos->updateCampoDetalleContacto($arrayUpdate2, $idEstudiante);
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarDiagnostico(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $diagnostico   = _post("diagnostico");
            $tallerVerano  = _post("verano");
            $observaciones = _post("observacion");
            $tipo          = _simpleDecryptInt(_post("tipo"));  
            if($tipo == null ){
                throw new Exception(ANP);
            }
            if($diagnostico == null/* || $tallerVerano == null*/){
                throw new Exception("Falta llenar algunos campos");
            }
            if($this->m_detalle_evaluacion->verifySameDiagnostico(_getSesion("idPostulanteEvaluar"), $tipo, _getSesion("idEventoProgreso")) > 0){
                throw new Exception("Ya se ha evaluado al postulante en el mismo curso / &aacute;rea");
            }
            $arrayInsert = array("id_estudiante"     => _getSesion("idPostulanteEvaluar"),
                                 "id_evaluador"      => _getSesion("nid_persona"),
                                 "diagnostico_final" => __only1whitespace(utf8_decode($diagnostico)),
                                 "taller_verano"     => __only1whitespace(utf8_decode($tallerVerano)),
                                 "obser_diagnostico" => __only1whitespace(utf8_decode($observaciones)),
                                 "id_config_eval"    => $tipo,
                                 "id_evento"         => _getSesion("idEventoProgreso"),
                                 "tipo_diagnostico"  => DIAGNOSTICO_CURSO);
            $data = $this->m_detalle_evaluacion->insertDiagnosticoEstudiante($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $myPostData = json_decode(_post('indicadores'), TRUE);
                if(count($myPostData['indicador']) == 0){
                    throw new Exception("Debe haber m&iaceute;nimo 1 indicador");
                }
                $resp = '[';
                foreach($myPostData['indicador'] as $key => $indicador) {
                    $resp .= '{';
                    $resp .= '"indicador" : "'._simple_decrypt($indicador['desc']).'",';
                    $resp .= '"valor" : "'.str_replace('"', "'", __only1whitespace($this->limpiar(utf8_decode($indicador['valor'])))).'"';
                    $resp .= '},';
                }
                $resp = rtrim($resp, ",");
                $resp .= ']';
                $arrayUpdate = array("resultado_indicadores" => $resp);
                $idDiag =$data['diag'];
                $data = $this->m_detalle_evaluacion->editarDiagnosticoEstudiante($arrayUpdate, _simpleDecryptInt($idDiag));
                
                if($data['error'] == EXIT_SUCCESS){
                    if(_getSesion("pantalla_evaluar") == 2){
                        if($this->m_detalle_evaluacion->validateContactoAgendado(_getSesion("idEventoProgreso"), _getSesion("idPostulanteEvaluar")) > 0){
                            $arrayUpdate = array("estado" => EVALUACION_A_EVALUAR);
                            $this->m_detalle_evaluacion->updateAgendados($arrayUpdate, _getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
                        }else{
                            $arrayInsert = array("id_contacto" => _getSesion("idPostulanteEvaluar"),
                	                             "fecha"       => date("d/m/Y H:i:s"),
                	                             "id_evento"   => _getSesion("idEventoProgreso"),
                	                             "estado"      => EVALUACION_A_EVALUAR);
                            $this->m_detalle_evaluacion->agendarContacto($arrayInsert);
                        }
                        
                        $diags = $this->m_detalle_evaluacion->countDiagRealizados(_getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
                        $data['completoCursos'] = 0;
                        if($diags['cant_cursos'] == $diags['cant_realiz']) {
                            $arrayUpdate = array("estado" => EVALUACION_EVALUADO);
                            $this->m_detalle_evaluacion->updateAgendados($arrayUpdate, _getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
                            
                            $datosParam['disabledSubDir'] = null;
                            $datosParam['disableEntrevista'] = "disabled";
                            $datosParam['comboDiagnostico'] = __buildComboByGrupo(COMBO_RESULTADO_DIAGNOSTICO);
                            $datosParam['onclickEntrevista'] = "guardarEntrevista()";
                            $datosParam['estadoEntrevista']  = '<i class="mdi mdi-priority_high" id="icon_estado_entrevista" data-toggle="tooltip" style="position: relative; top: 5px; color:green"></i>';
                            $data['cardEntrevista'] = $this->load->view('V_card_entrevista', $datosParam, true);
                        }
                        $fecha = $this->m_detalle_evaluacion->fechaAgendado(_getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
                        if($diags['cant_realiz'] == 1 && _fecha_tabla($fecha, "d/m/Y") != date("d/m/Y")){
                            $arrayUpdate = array("fecha" => date("d/m/Y H:i:s"));
                            $this->m_detalle_evaluacion->updateAgendados($arrayUpdate, _getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
                        }
                    }
                    
                    $data['diag'] = $idDiag;
                    /*$fotoPersona = $this->m_utils->getById("persona", "foto_persona", "nid_persona", _getSesion("nid_persona"));
                    $fotoGoogle = $this->m_utils->getById("persona", "google_foto", "nid_persona", _getSesion("nid_persona"));
                    
                    $foto = $fotoGoogle;
                    if($foto == null){
                        $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$fotoPersona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$fotoPersona : RUTA_IMG_PROFILE."nouser.svg");
                    }
                    */
                    $data['nombre_eva'] = _getSesion('nombre_abvr'); //$this->m_detalle_evaluacion->getNombreEva(_getSesion("nid_persona"));
                    $data['foto']       = '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'._getSesion('foto_usuario').'" data-toggle="tooltip" data-placement="bottom" id="fotoPersona" data-original-title="'.$data['nombre_eva'].' ('.date('d/m/Y h:i A').') ">';
                    $data['curso']      = _post("tipo");
                    $data['nomcurso']   = $this->m_utils->getById("admision.config_eval", "descripcion", "id_config_eval", $tipo);
                }
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function editarDiagnostico(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $diagnostico   = _post("diagnostico");
            $tallerVerano  = _post("verano");
            $idDiagnostico = _simpleDecryptInt(_post("diag"));
            $observaciones = _post("observacion");
            if($idDiagnostico == null ){
                throw new Exception(ANP);
            }
            if($diagnostico == null/* || $tallerVerano == null*/){
                throw new Exception("Falta llenar algunos campos");
            }
            $arrayUpdate = array("diagnostico_final" => __only1whitespace(utf8_decode($diagnostico)),
                                 "taller_verano"     => __only1whitespace(utf8_decode($tallerVerano)),
                                 "obser_diagnostico" => __only1whitespace(utf8_decode($observaciones)),
                                 "id_evaluador"      => _getSesion("nid_persona"),
                                 "fecha_registro"    => date('Y-m-d H:i:s'));
            $data = $this->m_detalle_evaluacion->editarDiagnosticoEstudiante($arrayUpdate, $idDiagnostico);
            if($data['error'] == EXIT_SUCCESS){
                /*$fotoPersona = $this->m_utils->getById("persona", "foto_persona", "nid_persona", _getSesion("nid_persona"));
                $fotoGoogle = $this->m_utils->getById("persona", "google_foto", "nid_persona", _getSesion("nid_persona"));
                
                $foto = $fotoGoogle;
                if($foto == null){
                    $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$fotoPersona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$fotoPersona : RUTA_IMG_PROFILE."nouser.svg");
                }
                */
                $data['foto'] = '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'._getSesion('foto_usuario').'" data-toggle="tooltip" data-placement="bottom" id="fotoPersona" data-original-title="'._getSesion('nombre_abvr').' ('.date('d/m/Y h:i A').') ">';
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function indicadoresDiagnostico(){
        $idDiagnostico = _simpleDecryptInt(_post("diag"));
        $idCurso       = _simpleDecryptInt(_post("curso"));
        $indicadores   = json_decode($this->m_utils->getById("admision.config_eval", "indicadores", "id_config_eval", $idCurso));
        $resp   = json_decode($this->m_utils->getById("admision.diagnostico", "resultado_indicadores", "id_diagnostico", $idDiagnostico));
        $data['tabla'] = _createTableIndicadoresCursoResultado($indicadores, _post("i"), $resp);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarIndicadoresCurso(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $idDiagnostico = _simpleDecryptInt(_post("diagnostico"));
            if($idDiagnostico == null ){
                throw new Exception(ANP);
            }
            $myPostData = json_decode(_post('indicadores'), TRUE);
            if(count($myPostData['indicador']) == 0){
                throw new Exception("Debe haber m&iaceute;nimo 1 indicador");
            }
            $resp = '[';
            foreach($myPostData['indicador'] as $key => $indicador) {
                $resp .= '{';
                $resp .= '"indicador" : "'._simple_decrypt($indicador['desc']).'",';
                $resp .= '"valor" : "'.__only1whitespace($this->limpiar(utf8_decode($indicador['valor']))).'"';
                $resp .= '},';
            }
            $resp = rtrim($resp, ",");
            $resp .= ']';
            $arrayUpdate = array("resultado_indicadores" => $resp);
            $data = $this->m_detalle_evaluacion->editarDiagnosticoEstudiante($arrayUpdate, $idDiagnostico);
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function limpiar($String){
        $String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
        $String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
        $String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
        $String = str_replace(array('í','ì','î','ï'),"i",$String);
        $String = str_replace(array('é','è','ê','ë'),"e",$String);
        $String = str_replace(array('É','È','Ê','Ë'),"E",$String);
        $String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
        $String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
        $String = str_replace(array('ú','ù','û','ü'),"u",$String);
        $String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
        $String = str_replace(array('[','^','´','`','¨','~',']'),"",$String);
        $String = str_replace("ç","c",$String);
        $String = str_replace("Ç","C",$String);
        $String = str_replace("ñ","n",$String);
        $String = str_replace("Ñ","N",$String);
        $String = str_replace("Ý","Y",$String);
        $String = str_replace("ý","y",$String);
         
        $String = str_replace("&aacute;","a",$String);
        $String = str_replace("&Aacute;","A",$String);
        $String = str_replace("&eacute;","e",$String);
        $String = str_replace("&Eacute;","E",$String);
        $String = str_replace("&iacute;","i",$String);
        $String = str_replace("&Iacute;","I",$String);
        $String = str_replace("&oacute;","o",$String);
        $String = str_replace("&Oacute;","O",$String);
        $String = str_replace("&uacute;","u",$String);
        $String = str_replace("&Uacute;","U",$String);
        return $String;
    }
    
    function guardarEntrevista(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $diagnostico   = _simpleDecryptInt(_post("diagnostico"));
            $tallerVerano  = _post("verano");
            $observaciones = _post("observacion");
            if($diagnostico == null/* || $tallerVerano == null*/){
                throw new Exception("Falta llenar algunos campos");
            }
            if($this->m_detalle_evaluacion->verifySameEntrevista(_getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso")) > 0){
                throw new Exception("Ya se ha entrevistado al postulante");
            }
            $arrayInsert = array("id_estudiante"     => _getSesion("idPostulanteEvaluar"),
                                 "id_evaluador"      => _getSesion("nid_persona"),
                                 "diagnostico_final" => $diagnostico,
                                 "taller_verano"     => __only1whitespace(utf8_decode($tallerVerano)),
                                 "obser_diagnostico" => __only1whitespace(utf8_decode($observaciones)),
                                 "id_evento"         => _getSesion("idEventoProgreso"),
                                 "tipo_diagnostico"  => DIAGNOSTICO_ENTREVISTA);
            $data = $this->m_detalle_evaluacion->insertDiagnosticoEstudiante($arrayInsert);
            if($data['error'] == EXIT_SUCCESS) {
                $arryUpdate = array(
                    'estado_eval'           => ESTADO_ENTREVISTADO,
                    'id_entrevistador'      => _getSesion("nid_persona"),
                    'fec_hora_entrevistado' => date('Y-m-d H:i:s')
                );
                if(_getSesion("pantalla_evaluar") == 1){
                    $data = $this->m_espera->registrarLlamada(_getSesion("idEventoProgreso"), _getSesion("idPostulanteEvaluar"), $arryUpdate);
                }else if(_getSesion("pantalla_evaluar") == 2){
                    $arrayUpdate = array("estado" => EVALUACION_ENTREVISTADO);
                    $this->m_detalle_evaluacion->updateAgendados($arrayUpdate, _getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
                }
                /*$fotoPersona = $this->m_utils->getById("persona", "foto_persona", "nid_persona", _getSesion("nid_persona"));
                $fotoGoogle = $this->m_utils->getById("persona", "google_foto", "nid_persona", _getSesion("nid_persona"));
                $foto = $fotoGoogle;
                if($foto == null){
                    $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$fotoPersona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$fotoPersona : RUTA_IMG_PROFILE."nouser.svg");
                }*/
                $data['foto']  = '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'._getSesion('foto_usuario').'" data-toggle="tooltip" data-placement="bottom" id="foto_persona_entrevista" data-original-title="'._getSesion('nombre_abvr').' ('.date('d/m/Y h:i A').') ">';
                $data['curso'] = _post("tipo");
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function editarEntrevista(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $diagnostico   = _simpleDecryptInt(_post("diagnostico"));
            $tallerVerano  = _post("verano");
            $observaciones = _post("observacion");
            if($diagnostico == null/* || $tallerVerano == null*/){
                throw new Exception("Falta llenar algunos campos");
            }
            $arrayUpdate = array("diagnostico_final" => $diagnostico,
                                 "taller_verano"     => __only1whitespace(utf8_decode($tallerVerano)),
                                 "obser_diagnostico" => __only1whitespace(utf8_decode($observaciones)),
                                 "id_evaluador"      => _getSesion("nid_persona"),
                                 "fecha_registro"    => date('Y-m-d H:i:s'));
            $idDiagnostico = $this->m_detalle_evaluacion->getIDDiagnosticoEvaluacionEventoEstudiante(_getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
            $data = $this->m_detalle_evaluacion->editarDiagnosticoEstudiante($arrayUpdate, $idDiagnostico);
            if($data['error'] == EXIT_SUCCESS){
                /*$fotoPersona = $this->m_utils->getById("persona", "foto_persona", "nid_persona", _getSesion("nid_persona"));
                $fotoGoogle = $this->m_utils->getById("persona", "google_foto", "nid_persona", _getSesion("nid_persona"));
                $foto = $fotoGoogle;
                if($foto == null){
                    $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$fotoPersona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$fotoPersona : RUTA_IMG_PROFILE."nouser.svg");
                }*/
                $data['foto'] = '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'._getSesion('foto_usuario').'" data-toggle="tooltip" data-placement="bottom" id="foto_persona_entrevista" data-original-title="'._getSesion('nombre_abvr').' ('.date('d/m/Y h:i A').') ">';
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function agregarArchivosEntrevista(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idDiagnostico = $this->m_detalle_evaluacion->getIDDiagnosticoEvaluacionEventoEstudiante(_getSesion("idPostulanteEvaluar"), _getSesion("idEventoProgreso"));
            if($idDiagnostico == null){
                throw new Exception(ANP);
            }
            $data = $this->subirImagenes($_FILES);
            if ($data['error'] == EXIT_SUCCESS) {
                $this->load->library('m_pdf');
                $pdf = $this->m_pdf->load();
                $pdfArray['pdfObj'] = $pdf;
                $pdfArray['countImages'] = $data['countImages'];
                $nombre = $nombreArchivo = __generateRandomString(6) . '_' . date("dmhis") . '.pdf';
                $pdfArray['nombreArchivo'] = $nombre;
                for ($i = 0; $i < $data['countImages']; $i ++) {
                    $pdfArray['img' . $i] = $data['img' . $i];
                }
                $this->load->view('V_pdf_documento', $pdfArray);
                for ($i = 0; $i < $data['countImages']; $i ++) {
                    unlink("./uploads/modulos/admision/imagenes/" . $data['img' . $i]);
                }
                $arrayInsert = array(
                    'id_persona_registro' => _getSesion("nid_persona"),
                    'ruta'                => $nombre,
                    'id_diagnostico'      => $idDiagnostico);
                $data = $this->m_detalle_evaluacion->insertDocumentoPostulante($arrayInsert);
                if($data['error'] == EXIT_SUCCESS){
                    $archivos = $this->m_detalle_evaluacion->getDocumentosByTipo($idDiagnostico);
                    $data['docs'] = $this->createContArchivo($archivos);
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardar_llamada() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $tipo = _post('tipo_accion');
            if($tipo == null) {
                throw new Exception(ANP);
            }
            if($tipo == ESTADO_SU_TURNO_CONTACTO) {
                $rpta = $this->m_espera->checkLlamadaAInvitado($this->_idEvento, $this->_idPostulante);
                if(is_array($rpta)) {
                    $msj = null;
                    if($rpta['estado_eval'] == ESTADO_SU_TURNO_CONTACTO) {
                        $msj = 'está llamado al postulante en estos momentos.';
                    } else if($rpta['estado_eval'] == ESTADO_EN_ENTREVISTA) {
                        $msj = 'ya está entrevistando al postulante.';
                    } else if($rpta['estado_eval'] == ESTADO_ENTREVISTADO) {
                        $msj = 'ya ha entrevistado al postulante.';
                    }
                    $otroEvaluador = $this->m_usuario->getDatosPersona($rpta['id_entrevistador']);
                    $data['detalle'] = 
                    '<div class="col-sm-6">
                        <img alt="Student" src="'.$otroEvaluador['foto_persona'].'" width=60 height=60 class="img-circle m-r-10">
                    </div>
                    <div class="col-sm-6">
                        '.$otroEvaluador['nombre_abvr'].' '.$msj.'
                    </div>';
                    throw new Exception(null);
                }
                $arryUpdate = array(
                    'estado_eval'       => ESTADO_SU_TURNO_CONTACTO,
                    'id_entrevistador'  => $this->_idUserSess,
                    'fec_hora_su_turno' => date('Y-m-d H:i:s')
                );
                $data = $this->m_espera->registrarLlamada($this->_idEvento, $this->_idPostulante, $arryUpdate);
                $data['msj'] = 'Llamando al postulante...';
            } else if($tipo == ESTADO_PERDIO_TURNO_CONTACTO) {
                $isOkLostCall = $this->m_espera->checkLlamadaLlamadaPerdida_o_pasar_a_Entrevista($this->_idEvento, $this->_idPostulante, $this->_idUserSess);
                if($isOkLostCall == null) {
                    throw new Exception('No puede dar como llamada perdida');
                }
                $arryUpdate = array(
                    'estado_eval'           => ESTADO_PERDIO_TURNO_CONTACTO,
                    'id_entrevistador'      => $this->_idUserSess,
                    'fec_hora_perdio_turno' => date('Y-m-d H:i:s')
                );
                $data = $this->m_espera->registrarLlamada($this->_idEvento, $this->_idPostulante, $arryUpdate);
                $data['msj'] = 'El postulante no llegó';
            } else if($tipo == ESTADO_EN_ENTREVISTA) {
                $isOkEntrevistar = $this->m_espera->checkLlamadaLlamadaPerdida_o_pasar_a_Entrevista($this->_idEvento, $this->_idPostulante, $this->_idUserSess);
                if($isOkEntrevistar == null) {
                    throw new Exception('No puede empezar la entrevista');
                }
                $arryUpdate = array(
                    'estado_eval'            => ESTADO_EN_ENTREVISTA,
                    'id_entrevistador'       => $this->_idUserSess,
                    'fec_hora_en_entrevista' => date('Y-m-d H:i:s')
                );
                $data = $this->m_espera->registrarLlamada($this->_idEvento, $this->_idPostulante, $arryUpdate);
                $data['msj'] = 'Entrevista iniciada';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cancelar_entrevista() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $detalleCancel = utf8_decode(trim(_post('detalleCancel')));
            if(strlen($detalleCancel) == 0) {
                throw new Exception('Ingrese la razón');
            }
            $isOkToCancelar = $this->m_espera->checkIfEsMiEntrevista($this->_idEvento, $this->_idPostulante, $this->_idUserSess);
            if($isOkToCancelar == null) {
                throw new Exception('No puedes cancelar esta entrevista');
            }
            $arryUpdate = array(
                'razon_entrevista_cancel' => $detalleCancel,
                'estado_eval'             => null,
                'id_entrevistador'        => null,
                'fec_hora_cancelado'      => date('Y-m-d H:i:s')
            );
            $data = $this->m_espera->registrarLlamada($this->_idEvento, $this->_idPostulante, $arryUpdate);
            $data['msj'] = 'Entrevista cancelada';
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}