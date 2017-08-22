<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_selec_taller extends MX_Controller {

    private $_idRol     = null;
    private $_idUsuario = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->helper('html');
        $this->load->model('../m_utils');
        $this->load->model('m_selec_taller');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_SELECCIONAR_TALLER, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
    }

    public function index() {
        $data['titleHeader'] = 'Talleres';
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_NOTAS, NOTAS_FOLDER);  
	    $data['ruta_logo']        = MENU_LOGO_NOTAS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
	    $data['nombre_logo']      = NAME_MODULO_NOTAS;
                
    	$rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu'] = $this->load->view('v_menu', $data, true);
        
        $hijos = $this->m_selec_taller->getHijosByFamilia($this->_idUserSess);
        $data['hijos'] = $this->tableHijos($hijos);
        $this->load->view('v_selec_taller', $data);
    }

    function tableHijos($data) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="5" data-search="false" id="tbHijos" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Foto'   , 'class' => 'text-right');
        $head_2 = array('data' => 'Nombre' , 'class' => 'text-left');
        $head_3 = array('data' => 'Grado.' , 'class' => 'text-center');
        $head_4 = array('data' => 'Aula' , 'class' => 'text-center');
        $head_5 = array('data' => 'Cant. Talleres' , 'class' => 'text-center');
        $head_6 = array('data' => 'Acci&oacute;n' , 'class' => 'text-center');
        $this->table->set_heading($head_1,$head_2,$head_3,$head_4,$head_5,$head_6);
        $val = 0;
        foreach($data as $row) {
            $val++;
            $idPersonaEnc = _simple_encrypt($row->nid_persona);
            $row_1 = array('data' => '<img class="img-circle" style="width: 30px" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'">', 'class' => 'text-center');
            $row_2 = array('data' => $row->nombrecompleto, 'class' => 'text-left');
            $row_3 = array('data' => $row->abrev_grado.' '.$row->abrev_nivel, 'class' => 'text-left');
            $row_4 = array('data' => $row->desc_aula, 'class' => 'text-left');
            $cant = '<span class="label label-warning">'.$row->cant_talleres.'/2'.'</span>';
            if($row->cant_talleres == 2){
                $cant = '<span class="label label-success">'.$row->cant_talleres.'/2'.'</span>';
            }
            $icon = 'mdi-visibility';
            $text = 'Talleres';
            $onclick = 'mostrarTalleresHijo(\''.$idPersonaEnc.'\', \''.$row->nombrecompleto.'\', this)';
            if($row->count_vencidos > 0){
                $icon = 'mdi-lock';
                $text = 'Tiene cuotas vencidas';
                $onclick = null;
            }
            $row_5 = array('data' => $cant, 'class' => 'text-center');
            $actions = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="'.$onclick.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$text.'">
                            <i class="mdi '.$icon.'"></i>
                        </button>';
            $row_6 = array('data' => $actions, 'class' => 'text-center');
            $this->table->add_row($row_1,$row_2,$row_3,$row_4,$row_5,$row_6);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function talleresHijo(){
        $idHijo = _simpleDecryptInt(_post("hijo"));
        $talleres = $this->m_selec_taller->getTalleresByEstudiante($idHijo);
        
        $data['tbTalleres'] = $this->tableTalleres($talleres);
        $data['count'] = count($talleres);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tableTalleres($data) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="5" data-search="false" id="tbTalleres" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'  , 'class' => 'text-left');
        $head_1 = array('data' => 'Descripci&oacute;n' , 'class' => 'text-left');
        $head_2 = array('data' => '&Aacute;rea' , 'class' => 'text-left');
        $head_3 = array('data' => 'Grp. Disponibles' , 'class' => 'text-center');
        $head_4 = array('data' => 'Grupo' , 'class' => 'text-center');
        $head_5 = array('data' => 'Acci&oacute;n' , 'class' => 'text-center');
        $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4,$head_5);
        $val = 0;
        foreach($data as $row) {
            $val++;
            $idTallerEnc = _simple_encrypt($row->id_taller);
            $actions = '<strong>'.$row->in.'</strong>';
            $grupoDisp = '-';
            if($row->in == '0'){
                $actions = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="modalAsignarGrupoTaller(\''.$idTallerEnc.'\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Elegir">
                                <i class="mdi mdi-create"></i>
                            </button>';
                $grupoDisp = $row->cant_grupos_vacios;
            }
            $onclickChange = null;
            $iconChange    = 'lock';
            $popTitle      = 'Bloqueado';
            if($row->count_solicitudes == 1){
                $onclickChange = null;
                $iconChange    = 'send';
                $popTitle      = 'Solicitud Enviada';
            }else{
                $datetime1 = date_create(_fecha_tabla($row->fecha, 'Y/m/d'));
                $datetime2 = date_create(_fecha_tabla(date("Y/m/d"), 'Y/m/d'));
                $interval = date_diff($datetime1, $datetime2);
                if($row->in != '0' && $row->fecha != null && ($interval->format('%a') == 0 || $interval->format('%a') == 1) ){//CAMBIAR
                    $onclickChange = 'abrirModalChangeTallerGrupo(\''.$idTallerEnc.'\', \''._simple_encrypt(1).'\')';
                    $iconChange = 'swap_horiz';
                    $popTitle   = 'Cambiar';
                }else if($interval->format('%a') > 1){//SOLICITAR CAMBIO
                    $onclickChange = 'abrirModalChangeTallerGrupo(\''.$idTallerEnc.'\', \''._simple_encrypt(2).'\')';
                    $iconChange = 'inbox';
                    $popTitle   = 'Solicitar';
                }
            }
            $change  = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="'.$onclickChange.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$popTitle.'">
                            <i class="mdi mdi-'.$iconChange.'"></i>
                        </button>';
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row->desc_taller, 'class' => 'text-left');
            $row_2 = array('data' => $row->area, 'class' => 'text-left');
            $row_3 = array('data' => $grupoDisp, 'class' => 'text-center');
            $row_4 = array('data' => $actions, 'class' => 'text-center');
            $row_5 = array('data' => $change, 'class' => 'text-center');
            $this->table->add_row($row_0,$row_1,$row_2,$row_3,$row_4,$row_5);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function gruposTaller(){
        $idTaller = _simpleDecryptInt(_post("taller"));
        $idHijo   = _simpleDecryptInt(_post("hijo"));
        $grupos = $this->m_selec_taller->getGruposByTaller($idHijo, $idTaller);
        
        $data['tbGrupos'] = $this->tableGrupos($grupos);
        $data['count'] = count($grupos);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tableGrupos($data) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="5" data-search="false" id="tbGrupos" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
    
        $head_0 = array('data' => '#' , 'class' => 'text-left');
        $head_1 = array('data' => 'Descripci&oacute;n' , 'class' => 'text-left');
        $head_2 = array('data' => 'Capacidad' , 'class' => 'text-center');
        $head_3 = array('data' => 'Aula' , 'class' => 'text-center');
        $head_4 = array('data' => 'Elegir' , 'class' => 'text-center');
        $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
        $val = 0;
        foreach($data as $row) {
            $val++;
            $idGrupoEnc = _simple_encrypt($row->nid_main);
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row->nombre_grupo, 'class' => 'text-left');
            $opcion = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="grupo_'.$val.'">
                          <input type="radio" id="grupo_'.$val.'" data-id-grupo="'.$idGrupoEnc.'" data-nombre-grupo="'.$row->nombre_grupo.'" class="mdl-radio__button recto" name="grupos_taller" onchange="radioCheck(\'grupo_'.$val.'\')">
                       </label>';
            $row_2 = array('data' => $row->count_alumn.'/'.$row->limite_alumno, 'class' => 'text-center');
            $row_3 = array('data' => $row->nom_aula, 'class' => 'text-center');
            $row_4 = array('data' => $opcion, 'class' => 'text-center');
            $this->table->add_row($row_0,$row_1,$row_2,$row_3,$row_4);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function asignarEstudianteTaller(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idPersona  = _simpleDecryptInt(_post("hijo"));
            $idGrupo    = _simpleDecryptInt(_post("grupo"));
            if($idPersona == null || $idGrupo == null){
                throw new Exception(ANP);
            }
            if($this->m_selec_taller->getCantidadTalleresByHijo($idPersona) == 2){//MAXIMA CANTIDAD DE TALLERES A MATRICULARSE
                throw new Exception("El estudiante ya est&aacute; matriculado en 2 talleres");
            }
            $idTaller = $this->m_utils->getById("main", "__id_taller", "nid_main", $idGrupo);
            if($this->m_selec_taller->getCantidadTalleresSameAreaByHijo($idPersona, $idTaller) >= 1){//MAXIMA CANTIDAD DE TALLERES A MATRICULARSE
                throw new Exception("El estudiante ya est&aacute; matriculado en un taller de la misma &aacute;rea");
            }
            $arrayInsert = array("__id_main"      => $idGrupo,
                                 "__id_alumno"    => $idPersona,
                                 "flg_activo"     => FLG_ACTIVO,
                                 "audi_usua_modi" => $this->_idUserSess,
                                 "audi_fec_modi"  => date('Y-m-d H:i:s'),
                                 "estado"         => ESTADO_GRUPO_REGISTRADO
            );
            $data = $this->m_selec_taller->insertarEstudianteGrupo($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $talleres = $this->m_selec_taller->getTalleresByEstudiante($idPersona);
                $data['tbTalleres'] = $this->tableTalleres($talleres);
                $data['count'] = count($talleres);
                $hijos = $this->m_selec_taller->getHijosByFamilia($this->_idUserSess);
                $data['hijos'] = $this->tableHijos($hijos);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function modalChangeTallerGrupo(){
        $idTaller = _simpleDecryptInt(_post("taller"));
        $idHijo   = _simpleDecryptInt(_post("hijo"));
        $tipo     = _simpleDecryptInt(_post("tipo"));
        $talleres = $this->m_selec_taller->getTalleresByEstudianteCombo($idHijo, $idTaller);
        $opcion = null;
        foreach ($talleres as $row) {
            $opcion .= '<option value="'._simple_encrypt($row->id_taller).'">'.$row->desc_taller.'</option>';
        }
        $data['motivo']  = 1;
        if($tipo == 1){
            $data['motivo']  = 0;
        }
        $data['comboTalleres'] = $opcion;
        $data['taller']        = _simple_encrypt($idTaller);
        $grupos  = $this->m_selec_taller->getGruposByTaller($idHijo, $idTaller);
        $idGrupo = $this->m_selec_taller->getGrupoByHijoTaller($idHijo, $idTaller);
        $data['grupoSelec'] = _simple_encrypt($idGrupo);
        $data['tbGrupos'] = $this->tableGruposChange($grupos, $idGrupo);
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tableGruposChange($data, $idGrupo) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="5" data-search="false" id="tbGruposChange" data-show-columns="false">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#' , 'class' => 'text-left');
        $head_1 = array('data' => 'Descripci&oacute;n' , 'class' => 'text-left');
        $head_2 = array('data' => 'Capacidad' , 'class' => 'text-center');
        $head_3 = array('data' => 'Aula' , 'class' => 'text-center');
        $head_4 = array('data' => 'Elegir' , 'class' => 'text-center');
        $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
        $val = 0;
        foreach($data as $row) {
            $val++;
            $idGrupoEnc = _simple_encrypt($row->nid_main);
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row->nombre_grupo, 'class' => 'text-left');
            $check = null;
            $disable = null;
            if($idGrupo == $row->nid_main){
                $check = 'checked';
                $disable = 'disabled';
            }
            $opcion = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="grupo_cambio_'.$val.'">
                          <input type="radio" id="grupo_cambio_'.$val.'" data-id-grupo="'.$idGrupoEnc.'" class="mdl-radio__button recto" name="grupos_taller_change" onchange="radioCheckCambio(\'grupo_cambio_'.$val.'\')" '.$check.' '.$disable.'>
                       </label>';
            $row_2 = array('data' => $row->count_alumn.'/'.$row->limite_alumno, 'class' => 'text-center');
            $row_3 = array('data' => $row->nom_aula, 'class' => 'text-center');
            $row_4 = array('data' => $opcion, 'class' => 'text-center');
            $this->table->add_row($row_0,$row_1,$row_2,$row_3,$row_4);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function gruposTallerChange(){
        $idTaller = _simpleDecryptInt(_post("taller"));
        $idHijo   = _simpleDecryptInt(_post("hijo"));
        $grupos = $this->m_selec_taller->getGruposByTaller($idHijo, $idTaller);
        $idGrupo = $this->m_selec_taller->getGrupoByHijoTaller($idHijo, $idTaller);
        $data['tbGrupos'] = $this->tableGruposChange($grupos, $idGrupo);
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function solicitarRealizarCambio(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idPersona       = _simpleDecryptInt(_post("hijo"));
            $idGrupo         = _simpleDecryptInt(_post("grupo"));
            $idTaller        = _simpleDecryptInt(_post("taller"));
            $idTallerAntiguo = _simpleDecryptInt(_post("tallerAntiguo"));
            $tipo            = _simpleDecryptInt(_post("tipo"));
            if($idPersona == null || $idGrupo == null || $idTaller == null || $tipo == null){
                throw new Exception(ANP);
            }
            if($idTaller == $idTallerAntiguo && $this->m_selec_taller->getGrupoByHijoTaller($idPersona, $idTallerAntiguo) == $idGrupo){
                throw new Exception('El estudiante ya est&aacute; registrado en el grupo seleccionado');
            }
            if($idTaller != $idTallerAntiguo && $this->m_selec_taller->verificarHijoTalleroGrupo($idPersona, $idTaller) >= 1){
                throw new Exception('El estudiante ya est&aacute; registrado en el taller/grupo seleccionado');
            }
            
            $arrayInsert = array();
            $idGrupoEnc = $this->m_selec_taller->getGrupoByHijoTaller($idPersona, $idTallerAntiguo);
            if($tipo == 1){//CAMBIAR
                $arrayInsert = array("__id_main"      => $idGrupo,
                                     "__id_alumno"    => $idPersona,
                                     "flg_activo"     => FLG_ACTIVO,
                                     "audi_usua_modi" => $this->_idUserSess,
                                     "audi_fec_modi"  => date('Y-m-d H:i:s'),
                                     "estado"         => ESTADO_GRUPO_REGISTRADO);
                $data = $this->m_selec_taller->eliminarEstudianteGrupo($idGrupoEnc, $idPersona);
            }else{//SOLICITAR
                $motivo = utf8_decode(_post("motivo"));
                if($motivo == null){
                    throw new Exception("Ingrese un motivo de cambio");
                }
                $arrayInsert = array("__id_main"      => $idGrupo,
                                     "__id_alumno"    => $idPersona,
                                     "flg_activo"     => FLG_ACTIVO,
                                     "audi_usua_modi" => $this->_idUserSess,
                                     "audi_fec_modi"  => date('Y-m-d H:i:s'),
                                     "estado"         => ESTADO_GRUPO_SOLICITADO,
                                     "motivo_cambio"  => $motivo
                );
                $arrayUpdate = array("__id_main_solicitud" => $idGrupo);
                $data = $this->m_selec_taller->updateGrupoHijo($arrayUpdate, $idPersona, $idGrupoEnc);
            }
            if($data['error'] == EXIT_SUCCESS){
                $data = $this->m_selec_taller->insertarEstudianteGrupo($arrayInsert);
                if($data['error'] == EXIT_SUCCESS){
                    $talleres = $this->m_selec_taller->getTalleresByEstudiante($idPersona);
                    $data['tbTalleres'] = $this->tableTalleres($talleres);
                    $data['count'] = count($talleres);
                    $hijos = $this->m_selec_taller->getHijosByFamilia($this->_idUserSess);
                    $data['hijos'] = $this->tableHijos($hijos);
                }
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}