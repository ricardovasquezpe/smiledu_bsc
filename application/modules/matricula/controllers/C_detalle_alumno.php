<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_detalle_alumno extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol = null;

    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_alumno/m_alumno');
        $this->load->model('mf_aula/m_aula');
		$this->load->model('mf_matricula/m_matricula');
		_validate_uso_controladorModulos(ID_SISTEMA_MATRICULA, ID_PERMISO_ALUMNO, MATRICULA_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
    }

    public function index() {
        $data['return'] = _getSesion('previousPage');
        
        $cantEst = $this->m_alumno->getCantidadEtudianteActivos();
        $data['cantEstudiantes'] = $cantEst."/".CANTIDAD_MAXIMA_ESTUDIANTES;
        $data['porcentajeEstudiantes'] = (($cantEst * 100)/CANTIDAD_MAXIMA_ESTUDIANTES);
        
        
        $data['comboNivelInstr']     = __buildComboByGrupo(COMBO_NIVEL_INST);
        $data['comboParentezco']     = __buildComboByGrupo(COMBO_PARENTEZCO);
        $data['comboOcupacion']      = __buildComboByGrupo(COMBO_OCUPACION);
        $data['comboSituacionLabo']  = __buildComboByGrupoNoEncryptId(COMBO_SITUACION_LABORAL);
        $data['comboSiNo']           = __buildComboByGrupo(COMBO_SI_NO);
	    $data['comboSiNoSinEncrypt'] = __buildComboByGrupoNoEncryptId(COMBO_SI_NO);
        $data['comboIdioma']         = __buildComboByGrupo(COMBO_IDIOMA);
        $data['comboTipoTraslado']   = __buildComboByGrupo(COMBO_TIPO_TRASLADO);
//         $data['comboNivelInstitucional'] = __buildComboByGrupo(COMBO_NIVEL_INST);

        $data['comboYearCronograma'] = __buildComboYearByCompromisos();
//         $data['comboSedes']          = __buildComboSedesByCompromisos(_getYear());
//         $data['comboNivel']          = __buildComboNiveles(1);
        
        $data['campoSima']    = _simple_encrypt(1);
        $data['campoSchoowl'] = _simple_encrypt(2);
        
        $data['paisPeru'] = PAIS_RESIDENTE;
        $data['paisPeruEnc'] = _simple_encrypt(PAIS_RESIDENTE);
        
        $disabled = null;
        $disabledComboSNG = null;
        $styleTab = 'opacity:0.2; pointer-events: none;';
        $data['titleHeader'] = 'Nuevo Estudiante';
        $data['tablaDocumentos'] = _createTableDocumentos(array());
        $data['foto'] = RUTA_IMG_PROFILE . "nouser.svg";
        $data['barraEst'] = '';
        if (_getSesion('accionDetalleAlumno') == 0 || _getSesion('accionDetalleAlumno') == 1) {
            $styleTab = null;
            $idPersona = _getSesion('idAlumnoEdit');
            $detalleAlumno = $this->m_alumno->getAlumno($idPersona);
            
            $data['nombres']       = $detalleAlumno['nom_persona'];
            $data['apePate']       = $detalleAlumno['ape_pate_pers'];
            $data['apeMate']       = $detalleAlumno['ape_mate_pers'];
            $data['fecnaci']       = _fecha_tabla($detalleAlumno['fec_naci'], 'd/m/Y');
            $data['tipoDoc']       = $detalleAlumno['tipo_documento'];
            $data['nro_documento'] = $detalleAlumno['nro_documento'];
            $data['telefono']      = $detalleAlumno['telf_pers'];
            $data['correo']        = $detalleAlumno['correo_pers'];
            $data['sexo']          = (strlen($detalleAlumno['sexo']) != 0) ? ($detalleAlumno['sexo']) : null;
            $data['colegio']       = (strlen($detalleAlumno['colegio_procedencia']) != 0) ? ($detalleAlumno['colegio_procedencia']) : null;
            $data['religion']      = (strlen($detalleAlumno['religion']) != 0) ? ($detalleAlumno['religion']) : null;
            $data['estadoCivil']   = (strlen($detalleAlumno['estado_civil']) != 0) ? ($detalleAlumno['estado_civil']) : null;
            $data['pais']          = (strlen($detalleAlumno['pais']) != 0) ? ($detalleAlumno['pais']) : null;
            $data['departamento']  = (strlen(substr($detalleAlumno['ubigeo'], 0, 2)) != 0) ? (substr($detalleAlumno['ubigeo'], 0, 2)) : null;
            $data['provincia']     = (strlen(substr($detalleAlumno['ubigeo'], 2, 2)) != 0) ? (substr($detalleAlumno['ubigeo'], 2, 2)) : null;
            $data['distrito']      = (strlen(substr($detalleAlumno['ubigeo'], 4, 2)) != 0) ? (substr($detalleAlumno['ubigeo'], 4, 2)) : null;
            
            $data['foto']          = ((file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $detalleAlumno['foto_persona'])) ? RUTA_IMG_PROFILE . 'estudiantes/' . $detalleAlumno['foto_persona'] : RUTA_IMG_PROFILE . "nouser.svg");
            
            $data['letra'] = null;
        	switch ($detalleAlumno['estado']) {
        	    case ALUMNO_EGRESADO:           $data['letra'] = 'egresado';           break;
        	    case ALUMNO_MATRICULADO:        $data['letra'] = 'matriculado';        break;
        	    case ALUMNO_NOPPROMOVIDO:       $data['letra'] = 'no-promovido';       break;
        	    case ALUMNO_PREREGISTRO:        $data['letra'] = 'pre-registro';       break;
        	    case ALUMNO_PROMOVIDO:          $data['letra'] = 'promovido';          break;
        	    case ALUMNO_REGISTRADO:         $data['letra'] = 'registrado';         break;
        	    case ALUMNO_RETIRADO:           $data['letra'] = 'retirado';           break;
        	    case ALUMNO_VERANO:             $data['letra'] = 'verano';             break;
        	    case ALUMNO_MATRICULABLE:       $data['letra'] = 'matriculable';       break;
        	    case ALUMNO_DATOS_INCOMPLETOS:  $data['letra'] = 'datos-incompletos';  break;
        	}
            $data['vistaFamiliares'] = null;
//             $documentos = $this->m_alumno->getDocumentosByAlumno($idPersona);
            //$data['tablaDocumentos'] = _createTableDocumentos($documentos);
            
            $data['titleHeader'] = 'Editar Estudiante';
            if (_getSesion('accionDetalleAlumno') == 0) {
                $disabled = 'disabled';
                $data['titleHeader'] = 'Ver Estudiante';
            }

            $data['barraEst'] = 'style="display:none"';
        }
        $data['comboSexo']           = __buildComboByGrupo(COMBO_SEXO, $data['sexo']);
        $data['comboTipoDoc']        = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC,$data['tipoDoc']);
        $data['comboColegios']       = __buildComboColegios($data['colegio']);
        $data['comboReligion']       = __buildComboByGrupo(COMBO_RELIGION, $data['religion']);
        $data['comboEstadoCivil']    = __buildComboByGrupo(COMBO_ESTADO_CIVIL, $data['estadoCivil']);
        $data['comboPaises']         = __buildComboPaises(1,$data['pais']);
        $data['comboPaisesSinEnc']   = __buildComboPaises();
        $data['comboDepartamento']   = __buildComboUbigeoByTipo(null, null, 1,$data['departamento']);
        $data['comboProvincia']      = null;
        $data['comboDistrito']       = null;
        if($data['departamento'] != null){
            $data['comboProvincia']      = __buildComboUbigeoByTipo($data['departamento'], null, 2,$data['provincia']);
            if($data['provincia'] != null){
                $data['comboDistrito']       = __buildComboUbigeoByTipo($data['departamento'], $data['provincia'], 3,$data['distrito']);
            }
        }
        
        $data['visibleSede'] = 0;
        if ($this->_idRol != ID_ROL_ADMINISTRADOR) {
            $data['visibleSede'] = 1;
//             $data['sedeActual'] = _simple_encrypt(_getSesion("id_sede_trabajo"));
        }
        
        $data['disabled'] = $disabled;
        //$data['disabledComboSNG'] = $disabledComboSNG;
        $data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                   <a href="#tab-1" class="mdl-layout__tab is-active">Informaci&oacute;n</a>
                                   <a href="#tab-2" class="mdl-layout__tab" onclick ="stepAlumno(this)">Admisi&oacute;n</a>
                                   <a href="#tab-3" class="mdl-layout__tab tabEscondido" style="' . $styleTab . '" disabled onclick ="stepAlumno(this)">Familia</a>
                                </div>';

        $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
        // ENVIAMOS LA DATA A LA VISTA
        $data['ruta_logo'] = MENU_LOGO_MATRICULA;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
        $data['nombre_logo'] = NAME_MODULO_MATRICULA;
        
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);
        $menu = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        $this->load->view('v_detalle_alumno', $data);
    }

    function getUbigeoByTipo()
    {
        $idubigeo = _simple_decrypt(_post("idubigeo"));
        $idubigeo1 = _simple_decrypt(_post("idubigeo1"));
        $tipo = _post("tipo");
        $data['comboUbigeo'] = __buildComboUbigeoByTipo($idubigeo, $idubigeo1, $tipo);
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function cambiarFotoEstudiante(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $ext = strtolower(_post('ext'));
            $imgBase = _post('foto');
            if($ext == null || $imgBase == null){
                throw new Exception(ANP);
            }
            if($ext != 'png' && $ext != 'jpg' && $ext != 'jpeg' && $ext != 'PNG'
                && $ext != 'JPG' && $ext != 'JPEG'){
                throw new Exception("Seleccione un archivo de tipo .JPG o .JPEG o .PNG");
            }
            $img = str_replace('data:image/png;base64,', '', $imgBase);
            $imagen = base64_decode($img);
            $fotoNombre = 'foto_estu_'.__generateRandomString(6).'_'.date("dmhis").'.png';
            $success = file_put_contents(RUTA_FISICA_IMG_PROFILE.'estudiantes/'.$fotoNombre, $imagen);
            if($success != null){
                $imgPersona = $this->m_utils->getById("persona", "foto_persona", "nid_persona", _getSesion('idAlumnoEdit'));
                if($imgPersona != null && file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$imgPersona)){
                    unlink(FOTO_PROFILE_PATH.'estudiantes/'.$imgPersona);
                }
                if(_getSesion('estadoCambio') == 0){
                    throw new Exception('Debe ingresar el nombre del postulante');
                } else {
                    $idPersona = _getSesion('idAlumnoEdit');
                    $arrayUpdate = array(
                        "foto_persona" => $fotoNombre
                    );
                    $data = $this->m_alumno->updateCampoDetalleAlumno($arrayUpdate, $idPersona, 2);
                }
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cambiarFotoFamiliar(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $idFam = _simpleDecryptInt(_post('idfamiliar'));
            if($idFam == null){
                throw new Exception('Elija a un colaborador');
            }
             
            $ext = strtolower(_post('ext'));
            $imgBase = _post('foto');
            if($ext == null || $imgBase == null){
                throw new Exception(ANP);
            }
            if($ext != 'png' && $ext != 'jpg' && $ext != 'jpeg' && $ext != 'PNG'
                && $ext != 'JPG' && $ext != 'JPEG'){
                throw new Exception("Seleccione un archivo de tipo .JPG o .JPEG o .PNG");
            }
            $img = str_replace('data:image/png;base64,', '', $imgBase);
            $imagen = base64_decode($img);
            $fotoNombre = 'foto_fami_'.__generateRandomString(6).'_'.date("dmhis").'.png';
            $success = file_put_contents(RUTA_FISICA_IMG_PROFILE.'familiares/'.$fotoNombre, $imagen);
            if($success != null){
                $imgPersona = $this->m_utils->getById("familiar", "foto_persona", "id_familiar", $idFam);
                if($imgPersona != null && file_exists(FOTO_PROFILE_PATH.'familiares/'.$imgPersona)){
                    unlink(FOTO_PROFILE_PATH.'familiares/'.$imgPersona);
                }
                
                $arrayUpdate = array("foto_persona" => $fotoNombre);
                $data = $this->m_alumno->editarFamiliar($arrayUpdate, array(), $idFam, null);
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function updateCampoCambio(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            if($this->m_alumno->getCantidadEtudianteActivos() == CANTIDAD_MAXIMA_ESTUDIANTES && _getSesion('estadoCambio') == 0){
                throw new Exception("");
                $data['error'] = 10;
            }
            $valor = null;
            if (_post('enc') == 1) {
                $valor = _simpleDecryptInt(_post("valor"));
            } else {
                $valor = __only1whitespace(trim(utf8_decode(_post("valor"))));
            }
            $campo = _post("abc");
            $bd = _simpleDecryptInt(_post("def"));
            
            if ($bd == null || $campo == null) {
                throw new Exception(ANP);
            }
            
            if(_getSesion('estadoCambio') == 0 && ($bd == 1 || $campo == 'foto_persona')){
            	throw new Exception('Debe ingresar el nombre del postulante');
            }
            if ($campo == 'id_sede_ingreso'  && $valor == null) {
            	throw new Exception('La sede es obligatoria');
            }
            if ($campo == 'id_nivel_ingreso' && $valor == null) {
            	throw new Exception('El nivel es obligatorio');
            }
            if ($campo == 'id_grado_ingreso' && $valor == null) {
            	throw new Exception('El grado es obligatorio');
            }
            
            if ($campo == 'correo_pers') {
                if (! filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Ingrese un correo personal v&aacute;lido");
                }
            }
            
            // SOLO CAMPOS EN MAYUSCULA
            if ($campo == 'correo_pers' && $campo == 'observacion') {
                $valor = strtoupper($valor);
            }
            
            if ($campo == 'nro_documento') {
                if (! ctype_digit($valor)) {
                    throw new Exception("Ingrese un n&uacute;mero de documento v&aacute;lido");
                }
                $tipoDoc = $this->m_utils->getById("persona", "tipo_documento", "nid_persona", _getSesion('idAlumnoEdit'));
                if ($tipoDoc == TIPO_DOC_DNI && strlen($valor) != 8) {
                    throw new Exception("El documento debe tener 8 n&uacute;meros");
                }
                if ($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && strlen($valor) != 12) {
                    throw new Exception("El documento debe tener 12 n&uacute;meros");
                }
                $cant = $this->m_utils->countByTipoDoc($valor, $tipoDoc, _getSesion('idAlumnoEdit'));
                if ($cant != 0) {
                    throw new Exception("Nro. Doc. ya ingresado");
                }
            }
            
            if ($campo == 'fec_naci' && $valor != null) {
                if(strlen($valor)!=10){
                    throw new Exception('La fecha es incorrecta');
                }
                $fechaNac = explode('/', $valor);
                if(ctype_digit($fechaNac[0]) == false || ctype_digit($fechaNac[1]) == false || ctype_digit($fechaNac[2]) == false){
                    throw new Exception('La fecha solo puede contener d&iacute;gitos');
                }
                if($fechaNac[0]>31){
                    throw new Exception('El d&iacute;a ingresado no puede ser mayor a 31');
                }
                if($fechaNac[1]>12){
                    throw new Exception('El mes ingresado no puede ser mayor a 12');
                }
                if($fechaNac[2]>_getYear()){
                    throw new Exception('El a&ntilde;o ingresado no puede ser mayor al actual');
                }
            }
            
            if ($campo == 'year_ingreso') {
                if ($valor < date("Y")) {
                    throw new Exception("El a&ntilde;o debe ser mayor o igual al actual");
                }
                
                $fechas = $this->m_matricula->getFechasReferenciaByTipo('M');
                if($fechas == null){
                    throw new Exception('A&uacute;n no se ha configurado la fecha l&iacute;mite de matr&iacute;cula');
                }
                if($valor == date('Y')){
                    if($fechas != null){
                        $fechaIni = explode('-', $fechas['fec_inicio']);
                        $fechaAct = explode('-', date("Y-m-d"));
                        $ok1 = 0;
                        if($fechaIni[1] == $fechaAct[1]){
                            if($fechaIni[2] <= $fechaAct[2]){
                                $ok1 = 1;
                            }
                        } else if ($fechaIni[1] < $fechaAct[1]) {
                            $ok1 = 1;
                        }
                         
                        if($ok1 == 1){
                            throw new Exception('La fecha de matr&iacute;cula configurada ya pas&oacute;');
                        }
                    }
                }
            }
            
            if ($campo == 'correo' && $valor != null && strlen($valor) != 0) {
                $idPersona = _getSesion('idAlumnoEdit');
                if (! filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Ingrese un correo v&aacute;lido");
                } else 
                    if ($this->m_alumno->validateCorreoRepetido($idPersona, $valor) != 0) {
                        throw new Exception("El correo ya est&aacute; registrado");
                    }
            }
            
            if ($campo == 'departamento') {
                $campo = 'ubigeo';
            } else 
                if ($campo == 'provincia') {
                    $ubigeo = $this->m_utils->getById("sima.detalle_alumno", "ubigeo", "nid_persona", _getSesion('idAlumnoEdit'));
                    $depart = substr($ubigeo, 0, 2);
                    $valor = $depart . $valor;
                    $campo = 'ubigeo';
                } else 
                    if ($campo == 'distrito') {
                        $ubigeo = $this->m_utils->getById("sima.detalle_alumno", "ubigeo", "nid_persona", _getSesion('idAlumnoEdit'));
                        $depart = substr($ubigeo, 0, 2);
                        $provinc = substr($ubigeo, 2, 2);
                        $valor = $depart . $provinc . $valor;
                        $campo = 'ubigeo';
                    }
            
            if (_getSesion('estadoCambio') == 0) { // CREAR POR PRIMERA VEZ
                $idSede = null;
                if ($this->_idRol != ID_ROL_ADMINISTRADOR) {
                    $idSede = $this->m_utils->getById("rrhh.personal_detalle", "id_sede_control", "id_persona", $this->_idUserSess);
                }
                $codAlumno = COD_ESTUDIANTE . $this->m_alumno->getContinuosCodAlu();
                $arrayInsertSchoowl = array("flg_acti" => FLG_ACTIVO);
                $arrayInsertSima = array(
                    "estado" => ALUMNO_DATOS_INCOMPLETOS,
                    "year_ingreso" => date("Y"),
                    "id_sede_ingreso" => $idSede,
                    "cod_alumno" => $codAlumno
                );
                if ($bd == 1) { // SIMA
                    $arrayInsertSima[$campo] = $valor;
                } else { // SCHOOWL
                    $arrayInsertSchoowl[$campo] = $valor;
                }
                $data = $this->m_alumno->insertAlumno($arrayInsertSchoowl, $arrayInsertSima);
                $dataUser = array(
                    "idAlumnoEdit" => $data['idAlumno'],
                    "accionDetalleAlumno" => 1,
                    "estadoCambio" => 1
                );
                $this->session->set_userdata($dataUser);
                $data['tab'] = 1;
                $data['estado'] = 'datos-incompletos';
                
                $cantEst = $this->m_alumno->getCantidadEtudianteActivos();
                $data['cantEstudiantes'] = $cantEst."/".CANTIDAD_MAXIMA_ESTUDIANTES;
                $data['porcentajeEstudiantes'] = (($cantEst * 100)/CANTIDAD_MAXIMA_ESTUDIANTES);
            } else { // EDITAR
                if (in_array($campo, json_decode(CAMPOS_OBLIGATORIOS_ESTUDIANTE)) && $valor == null) {
                    $data['error'] = 2;
                    throw new Exception('Es un campo obligatorio');
                }
                $idPersona = _getSesion('idAlumnoEdit');
                $arrayUpdate = array(
                		$campo => $valor
                );
                if($campo == 'pais'){
                	$arrayUpdate = array(
                			$campo => $valor,
                			'ubigeo' => null
                	);
                }
                if($campo == 'year_ingreso'){
                	$arrayUpdate = array(
                    	$campo => $valor,
                		'id_sede_ingreso' => null,
                		'id_nivel_ingreso' => null,
                		'id_grado_ingreso' => null
                	);
                }
                if($campo == 'id_sede_ingreso'){
                	$arrayUpdate = array(
                			$campo => $valor,
                			'id_nivel_ingreso' => null,
                			'id_grado_ingreso' => null
                	);
                }
                if($campo == 'id_nivel_ingreso'){
                	$arrayUpdate = array(
                			$campo => $valor,
                			'id_grado_ingreso' => null
                	);
                }
                
                $data = $this->m_alumno->updateCampoDetalleAlumno($arrayUpdate, $idPersona, $bd);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function buscarColegio()
    {
        $colegio = _post("nombreCole");
        $colegios = $this->m_alumno->getColegiosByName($colegio);
        $data['tablaColegios'] = _createTableColegios($colegios);
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function insertColegio()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            $colegio = _post("colegio");
            $existe = $this->m_utils->existeByCampoModel("desc_colegio", $colegio, "sima.colegios");
            if ($existe > 0) {
                throw new Exception("El colegio ingresado ya existe");
            }
            if ($colegio == null || strlen(trim($colegio)) == 0) {
                throw new Exception(ANP);
            }
            
            $arrayInsert = array(
                "desc_colegio" => utf8_decode(__only1whitespace($colegio))
            );
            
            $data = $this->m_alumno->insertColegio($arrayInsert);
            if ($data['error'] == EXIT_SUCCESS) {
                $data['comboColegios'] = __buildComboColegios();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function desagsinarFamiliar()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            $idFamiliar = _simple_decrypt(_post("familiar"));
            if ($idFamiliar == null) {
                throw new Exception(ANP);
            }
            
            $codFamilia = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
            // VALIDAR COMO MINIMO 1 RESPONSABLE ECONOMICO
            if ($this->m_alumno->getCountFamiliaresResponsableEconomico($codFamilia, $idFamiliar) == 0) {
                throw new Exception("Debe haber como m&iacute;nimo un responsable econ&oacute;mico");
            }
            // VALIDAR COMO MINIMO 1 APODERADO
            if ($this->m_alumno->getCountFamiliaresApoderado($codFamilia, $idFamiliar) == 0) {
                throw new Exception("Debe haber como m&iacute;nimo un apoderado");
            }
            
            $data = $this->m_alumno->desagsinarFamiliar($idFamiliar, $codFamilia);
            if ($data['error'] == EXIT_SUCCESS) {
                $familia = $this->m_alumno->getFamiliaByCodFam($codFamilia);
                $data['vistaFamiliares'] = _createVistaPadresDeFamilia($familia);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function validarFamiliarExiste()
    {
        $numeroDoc = _post("numeroDoc");
        $tipoDoc   = _post("tipoDoc");
        $codFam = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
        
        $data = array();
        if (is_numeric($numeroDoc) == true && $tipoDoc != null) {
             if (($tipoDoc == OPCION_CARNET_EXTRANJERIA && strlen($numeroDoc) != 12) || ($tipoDoc == OPCION_DNI && strlen($numeroDoc) != 8)) {
                $data['msj'] = "Ingrese la cantidad de numero necesarios dependiendo del tipo de documento";
                $data['existeCod'] = 2;
            } else {
                $data['existeCod'] = 0;
                $familiar = $this->m_alumno->getFamiliarByTipoDoc($numeroDoc, $tipoDoc, null);
                $data['count'] = count($familiar);
                if (count($familiar) != 0) {
//                     $array = implode("_", str_getcsv(trim($familiar['codfams'], '{}')));
                    $arra = explode(",", $familiar['codfams']);
                    if (in_array($codFam, $arra) && $codFam != null) {
                        //corregir
                        $data['msj'] = 'Este numero de documento ya existe en la familia';
                        $data['existeCod'] = 1;
                    } else {
                        $data['tablaFamiliarEncontrado'] = _createTableFamiliarEncontrado($familiar);
                        $data['existeCod'] = 0;
                        //corregir
                        $data['msj'] = 'Este numero de documento ya existe ¿Deseas agregarlo a la familia?';
                    }
                }
            }
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function buscarFamilias(){
        $nombre = _post("nombre");
        $codFam = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
        
        $familias = $this->m_alumno->buscarFamilia(utf8_decode($nombre), $codFam);
        $data['tablaFamiliar'] = _createTableFamiliasBusqueda($familias);
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function insertarFamiliar(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            // CABECERA
            $tipodoc = _post('tipodoc');
            if ($tipodoc != 1 && $tipodoc != 2) {
                throw new Exception(ANP);
            }
            $nrodoc = _post('nrodoc');
            if (! ctype_digit($nrodoc)) {
                throw new Exception("Ingrese un n&uacute;mero de documento v&aacute;lido");
            }
            if ($tipodoc == 1 && strlen($nrodoc) != 12) {
                throw new Exception("El documento elegido debe tener 12 n&uacute;meros");
            }
            if ($tipodoc == 2 && strlen($nrodoc) != 8) {
                throw new Exception("El documento elegido debe tener 8 n&uacute;meros");
            }
            // PART1
            $respEconomico      = _simpleDecryptInt(_post('respeconomico'));
            $parentesco         = _simpleDecryptInt(_post('parentesco'));
            $apoderado          = _simpleDecryptInt(_post('apodarado'));
            $nombres            = trim(strtoupper(utf8_decode(_post('nombres'))));
            $apPaterno          = trim(strtoupper(utf8_decode(_post('appaterno'))));
            $apMaterno          = trim(strtoupper(utf8_decode(_post('apmaterno'))));
            $vive               = (_post('vive'));
            $fecNaci            = _post('fecnaci');
            $pais               = (strlen(_post('pais')) == 0) ? null : _post('pais');
            // PART2
            //$viveEduc           = _simpleDecryptInt(_post('viveduc')); // NO BD
            $direccionHogar     = trim(utf8_decode(_post('direccionhogar')));
            $referenciaHogar    = trim(utf8_decode(_post('referenciahogar')));

            $departHogar        = _post('departhogar') != null ? _simpleDecryptInt(_post('departhogar')) : '00';
            $provHogar          = _post('provhogar') != null ? _simpleDecryptInt(_post('provhogar')) : '00';
            $distritHogar       = _post('distrhogar') !=  null ? _simpleDecryptInt(_post('distrhogar')) : '00';
            $telfFijo           = _post('telffijo');
            $telfCel            = _post('telfcel');
            $idioma             = _simpleDecryptInt(_post('idioma'));
            // PART3
            $estadoCivil        = _simpleDecryptInt(_post('estadocivil'));
            $exAlumno           = _simpleDecryptInt(_post('exalumno'));
            $yearEgreso         = trim(_post('yearegreso'));
            $coleEgreso         = _simpleDecryptInt(_post('coleegreso'));
            $correo1            = strtolower(trim(_post('correo1')));
            $correo2            = strtolower(trim(_post('correo2')));
            $religion           = _simpleDecryptInt(_post('religion'));
            // PART4
            $ocupacion          = trim(strtoupper(utf8_decode(_post('ocupacion'))));
            $centroTrabajo      = trim(strtoupper(utf8_decode(_post('centrotrabajo'))));
            $direccionTrabajo   = trim(utf8_decode(_post('direcciontrabajo')));
            $departTrabajo      = _post('departtrabajo') != null ? _simpleDecryptInt(_post('departtrabajo')) : '00';
            $provTrabajo        = _post('provtrabajo') != null ? _simpleDecryptInt(_post('provtrabajo')) : '00';
            $distritTrabajo     = _post('distrittrabajo') !=  null ? _simpleDecryptInt(_post('distrittrabajo')) : '00';
            $telfTrabajo        = trim(_post('telftrabajo'));
            $sitacionLaboral    = (strlen(_post('sitacionlaboral')) == 0) ? null : _post('sitacionlaboral');
            $sueldo             = trim(_post('sueldo'));
            $cargo              = trim(strtoupper(utf8_decode(_post('cargo'))));
            
            if ($sitacionLaboral == DESEMPLEADO) {
                $ocupacion = null;
                $centroTrabajo = null;
                $direccionTrabajo = null;
                $departTrabajo = null;
                $provTrabajo = null;
                $distritTrabajo = null;
                $telfTrabajo = null;
                $sueldo = null;
                $cargo = null;
            }
            
            if ($pais != PAIS_RESIDENTE) {
                $departHogar = null;
                $provHogar = null;
                $distritHogar = null;
            }

            
            if($vive != 2){
                if(strlen(trim($direccionHogar)) == 0 || strlen(trim($referenciaHogar)) == 0 || $pais == null || $idioma == null ){
                    throw new Exception(ANP);
                }
            }
                
            if ($respEconomico == null || $parentesco == null || $apoderado == null || $vive == null || $tipodoc == null) {
                throw new Exception(ANP);
            }
            if (strlen(trim($nombres)) == 0 || strlen(trim($apPaterno)) == 0 || strlen(trim($apMaterno)) == 0 || strlen(trim($fecNaci)) == 0 || strlen(trim($nrodoc)) == 0) {
                throw new Exception(ANP);
            }
            
            //$fecNaci VALIDAR
            if($fecNaci != null){
                if(strlen($fecNaci)!=10){
                    throw new Exception('La fecha es incorrecta');
                }
                $fechaNac = explode('/', $fecNaci);
                if(ctype_digit($fechaNac[0]) == false || ctype_digit($fechaNac[1]) == false || ctype_digit($fechaNac[2]) == false){
                    throw new Exception('La fecha solo puede contener d&iacute;gitos');
                }
                if($fechaNac[0]>31){
                    throw new Exception('El d&iacute;a ingresado no puede ser mayor a 31');
                }
                if($fechaNac[1]>12){
                    throw new Exception('El mes ingresado no puede ser mayor a 12');
                }
                if($fechaNac[2]>_getYear()){
                    throw new Exception('El a&ntilde;o ingresado no puede ser mayor al actual');
                }
            }
            // VALIDACIONES SI ES QUE LO LLENA
            if (strlen(trim($yearEgreso)) != 0) {
                if (is_numeric($yearEgreso) == false || strlen($yearEgreso) != 4 || $yearEgreso <= 0) {
                    throw new Exception(ANP);
                }
            } else {
                $yearEgreso = null;
            }
            /*if (strlen(trim($sueldo)) != 0) {
                if (is_numeric($sueldo) == false || $sueldo <= 0) {
                    throw new Exception('');
                }
            }*/

            /*$date1 = DateTime::createFromFormat("d/m/Y", $fecNaci);
            $date2 = DateTime::createFromFormat("d/m/Y", date("d/m/Y"));
            if ($date1 >= $date2) {
                throw new Exception('La fecha debe ser menor a la actual');
            }*/
            if($vive != 2){
	            if (! filter_var($correo1, FILTER_VALIDATE_EMAIL)) {
	                throw new Exception("Ingrese un correo v&aacute;lido");
	            } else if ($this->m_alumno->validateCorreoRepetidoFamiliar(null, $correo1) != 0) {
	            	throw new Exception("El correo ya est&aacute; registrado");
            	}
            }

            $codFamilia = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
            $correlativo = null;
            if ($codFamilia == null) {
                if ($vive == 2) {
                    throw new Exception("Debe haber m&iacute;nimo una persona viva");
                }
                if ($vive == 2 && ($apoderado == 1 || $respEconomico == 1)) {
                    $data['error'] = 2;
                    throw new Exception("Debe estar vivo para ser responsable o apoderado");
                }
                if ($respEconomico == 2) {
                    throw new Exception("Debe haber como m&iacute;nimo un responsable econ&oacute;mico");
                }
                if ($apoderado == 2) {
                    throw new Exception("Debe haber como m&iacute;nimo un apoderado");
                }
                
                $year = $this->m_utils->getById("sima.detalle_alumno", "year_ingreso", "nid_persona", _getSesion('idAlumnoEdit'));
                $codFamilia = $this->m_alumno->getContinuosCodFam($year);

                /*
                   $datoAlumno = $this->m_alumno->getAlumno(_getSesion('idAlumnoEdit'));
                   if($datoAlumno['ape_pate_pers'] == null || $datoAlumno['ape_mate_pers'] == null){
                   throw new Exception("El alumno debe tener apellido paterno y materno");
                   }
                 *
                   $usuario = strtolower($datoAlumno['ape_pate_pers'].'.'.$datoAlumno['ape_mate_pers']);
                   $j = 1;
                   for ($i = 0; $i < $j; $i++) {
                   if($this->m_alumno->validateUsuarioRepetido($usuario) != 0){
                   $numero = substr($usuario, strlen($usuario)-1);
                   if(is_numeric($numero)){
                   $usuario++;
                   }else{
                   $usuario = $usuario.'1';
                   }
                   $j++;
                   }else{
                   $i = $j;
                   }
                   }
                   $insertPersonaFamilia = array(
                   "nom_persona" => "FAMILIA",
                   "ape_pate_pers" => $datoAlumno['ape_pate_pers'],
                   "ape_mate_pers" => $datoAlumno['ape_mate_pers'],
                   "cod_familia" => $codFamilia,
                   "usuario" => $usuario,
                   "clave" => $usuario
                   );
                 *
                   $insertFamilia = $this->m_alumno->insertFamilia($insertPersonaFamilia);
                   if($insertFamilia['error'] == EXIT_ERROR){
                   throw new Exception($insertFamilia['msj']);
                   }
                 */
                $arrayUpdate = array(
                    "cod_familia" => $codFamilia
                );
                $this->m_alumno->updateCodFamAlumno($arrayUpdate, _getSesion('idAlumnoEdit'));
                $correlativo = 1;
            } else {
                if ($this->m_alumno->getCountFamiliaresVivas($codFamilia, null) == 0 && $vive == 2) {
                    throw new Exception("Debe haber m&iacute;nimo una persona viva");
                }
                if ($vive == 2 && ($apoderado == 1 || $respEconomico == 1)) {
                    $data['error'] = 2;
                    $data['opcion'] = _simple_encrypt(2);
                    throw new Exception("Debe estar vivo para ser responsable o apoderado");
                }
                if ($this->m_alumno->getCountFamiliaresResponsableEconomico($codFamilia, null) == 0 && $respEconomico == 2) {
                    throw new Exception("Debe haber como m&iacute;nimo un responsable econ&oacute;mico");
                }
                
                if ($this->m_alumno->getCountFamiliaresApoderado($codFamilia, null) == 0 && $apoderado == 2) {
                    throw new Exception("Debe haber como m&iacute;nimo un apoderado");
                }
            }
            
            $arrayInsertFamiliar = array(
                "tipo_doc_identidad" => $tipodoc,
                "nro_doc_identidad"  => $nrodoc,
                
                "nombres"            => (__only1whitespace($nombres)),
                "ape_paterno"        => (__only1whitespace($apPaterno)),
                "ape_materno"        => (__only1whitespace($apMaterno)),
                "flg_vive"           => $vive,
                "fec_naci"           => $fecNaci,
                "nacionalidad"       => $pais,
                
                "direccion_hogar"    => (__only1whitespace($direccionHogar)),
                "refer_domicilio"    => (__only1whitespace($referenciaHogar)),
                "ubigeo_hogar"       => $departHogar . $provHogar . $distritHogar,
                "telf_fijo"          => $telfFijo,
                "telf_celular"       => $telfCel,
                "idioma"             => $idioma,
                
                "estado_civil"       => $estadoCivil,
                "flg_ex_alumno"      => $exAlumno,
                "year_egreso"        => $yearEgreso,
                "colegio_egreso"     => $coleEgreso,
                "email1"             => $correo1,
                "email2"             => $correo2,
                "religion"           => $religion,
                
                "ocupacion"          => $ocupacion,
                "sueldo"             => $sueldo,
                "centro_trabajo"     => (__only1whitespace($centroTrabajo)),
                "direccion_trabajo"  => (__only1whitespace($direccionTrabajo)),
                "ubigeo_trabajo"     => $departTrabajo . $provTrabajo . $distritTrabajo,
                "telf_trabajo"       => $telfTrabajo,
                "situacion_laboral"  => $sitacionLaboral,
                "sueldo"             => $sueldo,
                "cargo"              => $cargo,
                "flg_acti"           => FLG_ACTIVO
            );
            $data = $this->m_alumno->insertFamiliar($arrayInsertFamiliar);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsert = array(
                    "id_familiar" => $data['idFamiliar'],
                    "cod_familiar" => $codFamilia,
                    "parentesco" => $parentesco,
                    "flg_resp_economico" => $respEconomico,
                    "flg_apoderado" => $apoderado
                );
                $data = $this->m_alumno->asignarFamiliarAFamilia($arrayInsert);
                
				if($apoderado == 1){
					$html = '<div>
	                        <p>Bienvenido a smiledu: ' . utf8_decode(__only1whitespace($nombres)) .' '.utf8_decode(__only1whitespace($apPaterno)).'</p>
							<p>Has sido correctamente registrado en nuestro sistema!</p>
                            <p>Grupo educativo Avantgard</p>
                         </div>';
					
					$arrayInsertCorreo = array('correos_destino'         => $correo1,
                        					   'asunto'                  => utf8_encode("Bienvenido a Smiledu!"),
                        					   'body'                    => $html,
                        					   'estado_correo'           => CORREO_PENDIENTE,
                        					   'sistema'                 => 'SMILEDU');
					$dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
				}
            }
            
            if ($data['error'] == EXIT_SUCCESS) {
//                 //PROBAR CUANDO SE HAGAN LOS CAMBIOS
                $detaAlumno = $this->m_alumno->getCamposMinimosAlumno(_getSesion('idAlumnoEdit'));
//                 if($detaAlumno['estado'] == ALUMNO_DATOS_INCOMPLETOS){
//                 	$sede         = $detaAlumno['id_sede_ingreso'];
//                 	$nivel        = $detaAlumno['id_nivel_ingreso'];
//                 	$grado        = $detaAlumno['id_grado_ingreso'];
//                 	$year         = $detaAlumno['year_ingreso'];
//                 	$config       = $this->m_alumno->getConfig($year, $sede);
//                 	if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
//                         $tieneCuotaGenerada = $this->m_alumno->getCountGeneroCuotasIniciales(_getSesion('idAlumnoEdit'));
//                 	} else {
//                 	    $tieneCuotaGenerada = $this->m_alumno->getCountMatricula(_getSesion('idAlumnoEdit'), $year, $sede);
//                 	}
//                 	if($tieneCuotaGenerada == 0 && $sede != null && $nivel != null && $grado != null && $year != null){
//                 		$detalles     = $this->m_alumno->getCompromisosEstudiante(_getSesion('idAlumnoEdit'), $year, $sede, $nivel, $grado);
//                 		if(count($detalles) != 0) {
//                 			$idpersona      = $this->_idUserSess;
//                 			$getnombre      = _getSesion('nombre_abvr');
//                 			$compromisos    = array(array());
//                 			$datos_audi_mov = array(array());
                			 
//                 			$i=0;
//                 			$idCondicion    = $this->m_matricula->getId_condicionAlumno($sede,$nivel,$grado,$year);
//                 			$total_becas = null;
//                 			if(0 < count($detalles)){
//                 				foreach ($detalles as $item){
//                 					if(is_numeric($item->descuento)){
//                 						$total_becas += $item->descuento;
//                 					}
//                 				}
//                 			} else {
//                 				$total_becas = 0;
//                 			}
//                 			if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
//                 			    $cuotIngreso  = $this->m_alumno->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year);
//                 			    $push = array(
//                 			        "tipo_movimiento"         => MOV_INGRESO,
//                 			        "estado"                  => ESTADO_POR_PAGAR,
//                 			        "monto"                   => $cuotIngreso,
//                 			        "monto_final"             => $cuotIngreso,
//                 			        "_id_persona"             => _getSesion('idAlumnoEdit'),
//                 			        "_id_detalle_cronograma"  => null,
//                 			        "_id_concepto"            => CUOTA_INGRESO);
//                 			    array_push($compromisos, $push);
//                 			}
//                 			foreach ($detalles as $item){
//                 				$itemDecry         = ($item->id_detalle_cronograma);
//                 				if($itemDecry != null && $item->id_detalle_cronograma != ""){
//                 					$necesario  = $this->m_matricula->getMoraByDetalle($itemDecry,0,$sede,$nivel,$grado,$year);
//                 				}
//                 				$push = array("tipo_movimiento"       => MOV_INGRESO,
//                 						"estado"                  => $necesario['estado'],
//                 						"monto"                   => $item->monto,
//                 						"monto_final"             => $item->monto,
//                 						"_id_persona"             => _getSesion('idAlumnoEdit'),
//                 						"_id_detalle_cronograma"  => (($item->id_detalle_cronograma) == "") ? null : $itemDecry,
//                 						"_id_concepto"            => (($itemDecry == null) ? CUOTA_INGRESO : CONCEPTO_SERV_ESCOLAR));
//                 				array_push($compromisos, $push); $i++;
//                 			}
//                 			unset($compromisos[0]);
//                 			$data 				= $this->m_matricula->SaveCompromisosMovimientos($compromisos);
//                 			$fisrt_id_mov 		= ($data['id_movimiento'] - $data['n_total_mov']+1);
//                 			$last_id_mov  		= $data['id_movimiento'];
//                 			for($i = $fisrt_id_mov ; $i<= $last_id_mov; $i++){
//                 				$push = array('_id_movimiento' => $i,
//                 						'correlativo'    => $this->m_matricula->getNextCorrelativo($i),
//                 						'id_pers_regi'   => $idpersona,
//                 						'audi_nomb_regi' => $getnombre,
//                 						'accion'         => REGISTRAR);
//                 				array_push($datos_audi_mov, $push);
//                 			}
//                 			unset($datos_audi_mov[0]);
//                 			$condicion_x_persona = array(
//                 					'_id_condicion'  => $idCondicion,
//                 					'_id_persona'    => _getSesion('idAlumnoEdit'),
//                 					'estado'         => FLG_ESTADO_ACTIVO,
//                 					'flg_beca'       => (0<$total_becas) ? 1 : 0
//                 			);
//                 			$data = $this->m_matricula->SaveCompromisosAudiMovimientos($datos_audi_mov,$condicion_x_persona);
                	
//                         	if($data['error'] == EXIT_SUCCESS){
                        	    if($detaAlumno['nom_persona'] != null && $detaAlumno['ape_pate_pers'] != null && $detaAlumno['ape_mate_pers'] != null && $detaAlumno['fec_naci'] != null &&
                    	           $detaAlumno['sexo'] != null && $detaAlumno['nro_documento'] != null && $detaAlumno['estado'] == ALUMNO_DATOS_INCOMPLETOS && $detaAlumno['count_familia'] >= 1
                    	            && $detaAlumno['id_grado_ingreso'] != null && $detaAlumno['id_nivel_ingreso'] != null && $detaAlumno['id_sede_ingreso'] != null && $detaAlumno['year_ingreso'] != null) {
                        	            $alumnoUpdate = array("estado" => ALUMNO_PREREGISTRO);
                        	            $rpta = $this->m_alumno->updateCampoDetalleAlumno($alumnoUpdate, _getSesion('idAlumnoEdit'), 1);
                        	            $data['estado'] = $rpta['error'];
                    	        }
//                         	}
//                 		}
//                 	}
//              }
                
                $familia = $this->m_alumno->getFamiliaByCodFam($codFamilia);
                $data['vistaFamiliares'] = _createVistaPadresDeFamilia($familia);
                $data['error']    = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function buscarFamiliares()
    {
        $nombre = _post("nombre");
        $codFam = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
        
        $familiares = $this->m_alumno->buscarFamiliar($nombre, $codFam);
        $data['tablaFamiliar'] = _createTableFamiliaresBusqueda($familiares);
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function asignarFamiliares()
    {
        $arrayFamiliares = _post("familiares");
        $codFam = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
        if ($codFam == null) {
        	$year = $this->m_utils->getById("sima.detalle_alumno", "year_ingreso", "nid_persona", _getSesion('idAlumnoEdit'));
        	$codFam = $this->m_alumno->getContinuosCodFam($year);
            $arrayUpdate = array(
                "cod_familia" => $codFam
            );
            $this->m_alumno->updateCodFamAlumno($arrayUpdate, _getSesion('idAlumnoEdit'));
        }
        
        foreach ($arrayFamiliares as $familiar) {
            $idFamiliar = _simpleDecryptInt($familiar);
            $data = $this->asginarFamiliaraFamiliarReutilizable($idFamiliar, $codFam);
        }
        
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function asignarFamiliaraFamilia()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        $idFamiliar = _simple_decrypt(_post("idfamiliar"));
        $nro        = _post("nro");
        if($nro == 1) {
            $codFamilia = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
            $data = $this->asginarFamiliaraFamiliarReutilizable($idFamiliar,$codFamilia);
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function asginarFamiliaraFamiliarReutilizable($idFamiliar, $codFamilia)
    {
        $correlativo = null;
        if ($codFamilia == null) {
            $year = $this->m_utils->getById("sima.detalle_alumno", "year_ingreso", "nid_persona", _getSesion('idAlumnoEdit'));
            $codFamilia = $this->m_alumno->getContinuosCodFam($year);
            $j = 1;
            $arrayInsert = array(
                "id_familiar" => $idFamiliar,
                "cod_familiar" => $codFamilia
            );
            $data = $this->m_alumno->asignarFamiliarAFamilia($arrayInsert);
            $arrayUpdateAlumno = array(
                "cod_familia" => $codFamilia
            );
            $updateAlumno = $this->m_alumno->updateCodFamAlumno($arrayUpdateAlumno, _getSesion('idAlumnoEdit'));
        } else {
            $arrayInsert = array(
                "id_familiar" => $idFamiliar,
                "cod_familiar" => $codFamilia
            );
            $data = $this->m_alumno->asignarFamiliarAFamilia($arrayInsert);
        }

        if ($data['error'] == EXIT_SUCCESS) {
            $familia = $this->m_alumno->getFamiliaByCodFam($codFamilia);
            $data['vistaFamiliares'] = _createVistaPadresDeFamilia($familia);
        }
        
        return $data;
    }

    function asginarFamilia(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            $codFam = _simple_decrypt(_post("codigofam"));
            if ($codFam == null) {
                throw new Exception(ANP);
            }
            
            $arrayUpdate = array(
                "cod_familia" => $codFam
            );
            $data = $this->m_alumno->asignarODesagsinarFamiliar($arrayUpdate, _getSesion('idAlumnoEdit'));
            
            if ($data['error'] == EXIT_SUCCESS) {
                //PROBAR CUANDO SE HAGAN LOS CAMBIOS
                $detaAlumno = $this->m_alumno->getCamposMinimosAlumno(_getSesion('idAlumnoEdit'));
//                 if($detaAlumno['estado'] == ALUMNO_DATOS_INCOMPLETOS){
//                 	$sede         = $detaAlumno['id_sede_ingreso'];
//                 	$nivel        = $detaAlumno['id_nivel_ingreso'];
//                 	$grado        = $detaAlumno['id_grado_ingreso'];
//                 	$year         = $detaAlumno['year_ingreso'];
//                 	$config       = $this->m_alumno->getConfig($year, $sede);
//                 	if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
//                 		$tieneCuotaGenerada = $this->m_alumno->getCountGeneroCuotasIniciales(_getSesion('idAlumnoEdit'));
//                 	} else {
//                 		$tieneCuotaGenerada = $this->m_alumno->getCountMatricula(_getSesion('idAlumnoEdit'), $year, $sede);
//                 	}
//                 	if($tieneCuotaGenerada == 0 && $sede != null && $nivel != null && $grado != null && $year != null){
//                 		$detalles     = $this->m_alumno->getCompromisosEstudiante(_getSesion('idAlumnoEdit'), $year, $sede, $nivel, $grado);
//                 		if(count($detalles) != 0) {
//                 			$idpersona      = $this->_idUserSess;
//                 			$getnombre      = _getSesion('nombre_abvr');
//                 			$compromisos    = array(array());
//                 			$datos_audi_mov = array(array());
                
//                 			$i=0;
//                 			$idCondicion    = $this->m_matricula->getId_condicionAlumno($sede,$nivel,$grado,$year);
//                 			$total_becas = null;
//                 			if(0 < count($detalles)){
//                 				foreach ($detalles as $item){
//                 					if(is_numeric($item->descuento)){
//                 						$total_becas += $item->descuento;
//                 					}
//                 				}
//                 			} else {
//                 				$total_becas = 0;
//                 			}
//                 			if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
//                 				$cuotIngreso  = $this->m_alumno->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year);
//                 				$push = array(
//                 						"tipo_movimiento"         => MOV_INGRESO,
//                 						"estado"                  => ESTADO_POR_PAGAR,
//                 						"monto"                   => $cuotIngreso,
//                 						"monto_final"             => $cuotIngreso,
//                 						"_id_persona"             => _getSesion('idAlumnoEdit'),
//                 						"_id_detalle_cronograma"  => null,
//                 						"_id_concepto"            => CUOTA_INGRESO);
//                 				array_push($compromisos, $push);
//                 			}
//                 			foreach ($detalles as $item){
//                 				$itemDecry         = ($item->id_detalle_cronograma);
//                 				if($itemDecry != null && $item->id_detalle_cronograma != ""){
//                 					$necesario  = $this->m_matricula->getMoraByDetalle($itemDecry,0,$sede,$nivel,$grado, $year);
//                 				}
//                 				$push = array("tipo_movimiento"       => MOV_INGRESO,
//                 						"estado"                  => $necesario['estado'],
//                 						"monto"                   => $item->monto,
//                 						"monto_final"             => $item->monto,
//                 						"_id_persona"             => _getSesion('idAlumnoEdit'),
//                 						"_id_detalle_cronograma"  => (($item->id_detalle_cronograma) == "") ? null : $itemDecry,
//                 						"_id_concepto"            => (($itemDecry == null) ? CUOTA_INGRESO : CONCEPTO_SERV_ESCOLAR));
//                 				array_push($compromisos, $push); $i++;
//                 			}
//                 			unset($compromisos[0]);
//                 			$data 				= $this->m_matricula->SaveCompromisosMovimientos($compromisos);
//                 			$fisrt_id_mov 		= ($data['id_movimiento'] - $data['n_total_mov']+1);
//                 			$last_id_mov  		= $data['id_movimiento'];
//                 			for($i = $fisrt_id_mov ; $i<= $last_id_mov; $i++){
//                 				$push = array('_id_movimiento' => $i,
//                 						'correlativo'    => $this->m_matricula->getNextCorrelativo($i),
//                 						'id_pers_regi'   => $idpersona,
//                 						'audi_nomb_regi' => $getnombre,
//                 						'accion'         => REGISTRAR);
//                 				array_push($datos_audi_mov, $push);
//                 			}
//                 			unset($datos_audi_mov[0]);
//                 			$condicion_x_persona = array(
//                 					'_id_condicion'  => $idCondicion,
//                 					'_id_persona'    => _getSesion('idAlumnoEdit'),
//                 					'estado'         => FLG_ESTADO_ACTIVO,
//                 					'flg_beca'       => (0<$total_becas) ? 1 : 0
//                 			);
//                 			$data = $this->m_matricula->SaveCompromisosAudiMovimientos($datos_audi_mov,$condicion_x_persona);
                			 
//                 			if($data['error'] == EXIT_SUCCESS){
                				if($detaAlumno['nom_persona'] != null && $detaAlumno['ape_pate_pers'] != null && $detaAlumno['ape_mate_pers'] != null && $detaAlumno['fec_naci'] != null &&
                						$detaAlumno['sexo'] != null && $detaAlumno['nro_documento'] != null && $detaAlumno['estado'] == ALUMNO_DATOS_INCOMPLETOS && $detaAlumno['count_familia'] >= 1
                						&& $detaAlumno['id_grado_ingreso'] != null && $detaAlumno['id_nivel_ingreso'] != null && $detaAlumno['id_sede_ingreso'] != null && $detaAlumno['year_ingreso'] != null) {
                							$alumnoUpdate = array("estado" => ALUMNO_PREREGISTRO);
                							$rpta = $this->m_alumno->updateCampoDetalleAlumno($alumnoUpdate, _getSesion('idAlumnoEdit'), 1);
                							$data['estado'] = $rpta['error'];
        						}
//                 			}
//                 		}
//                 	}
//              }
                
                $familia = $this->m_alumno->getFamiliaByCodFam($codFam);
                $data['vistaFamiliares'] = _createVistaPadresDeFamilia($familia);
                $data['error']    = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function verFamiliaresCodFamiliar()
    {
        $codFam = _simple_decrypt(_post("codFamiliar"));
        $familiares = $this->m_alumno->getFamiliaByCodFamAbrev($codFam);
        
        $data['tablaFamiliares'] = _createTableFamiliaresByCodFam($familiares);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function detalleFamiliar()
    {
        $idFamiliar = _simpleDecryptInt(_post("idFamiliar"));
        $familiar = $this->m_alumno->getFamiliarById($idFamiliar);
        
        // CABECERA
        $data['tipoDocFam'] = $familiar['tipo_doc_identidad'];
        $data['nroDocFam'] = $familiar['nro_doc_identidad'];
        
        // PART1
        $data['respEconoFam'] = (strlen($familiar['flg_resp_economico']) != 0) ? _simple_encrypt($familiar['flg_resp_economico']) : null;
        $data['parentescoFam'] = (strlen($familiar['parentesco']) != 0) ? _simple_encrypt($familiar['parentesco']) : null;
        $data['apoderadoFam'] = (strlen($familiar['flg_apoderado']) != 0) ? _simple_encrypt($familiar['flg_apoderado']) : null;
        $data['nomFam'] = $familiar['nombres'];
        $data['apePateFam'] = $familiar['ape_paterno'];
        $data['apeMateFam'] = $familiar['ape_materno'];
        $data['viveFam'] = (strlen($familiar['flg_vive']) != 0) ? ($familiar['flg_vive']) : null;
        $data['fecNaciFam'] = _fecha_tabla($familiar['fec_naci'], 'd/m/Y');
        $data['paisFam'] = (strlen($familiar['nacionalidad']) != 0) ? $familiar['nacionalidad'] : null;
        
        // PART2
        // $data['viveEdicFam'] = (strlen($familiar['flg_resp_economico']) != 0) ? _simple_encrypt($familiar['flg_resp_economico']) : null;
        $data['direccionHogarFam'] = $familiar['direccion_hogar'];
        $data['referenciaHogarFam'] = $familiar['refer_domicilio'];
        $data['departamentoHogarFam'] = (strlen(substr($familiar['ubigeo_hogar'], 0, 2)) != 0) ? _simple_encrypt(substr($familiar['ubigeo_hogar'], 0, 2)) : null;
        $data['provinciaHogarFam'] = (strlen(substr($familiar['ubigeo_hogar'], 2, 2)) != 0) ? _simple_encrypt(substr($familiar['ubigeo_hogar'], 2, 2)) : null;
        $data['distritoHogarFam'] = (strlen(substr($familiar['ubigeo_hogar'], 4, 2)) != 0) ? _simple_encrypt(substr($familiar['ubigeo_hogar'], 4, 2)) : null;
        $data['telefonoFijoFam'] = $familiar['telf_fijo'];
        $data['telefonoCelularFam'] = $familiar['telf_celular'];
        $data['idiomaFam'] = (strlen($familiar['idioma']) != 0) ? _simple_encrypt($familiar['idioma']) : null;
        
        // PART3
        $data['estadocivilFam'] = (strlen($familiar['estado_civil']) != 0) ? _simple_encrypt($familiar['estado_civil']) : null;
        $data['exalumnoFam'] = (strlen($familiar['flg_ex_alumno']) != 0) ? _simple_encrypt($familiar['flg_ex_alumno']) : null;
        $data['yearEgresoFam'] = $familiar['year_egreso'];
        $data['colegioFam'] = (strlen($familiar['colegio_egreso']) != 0) ? _simple_encrypt($familiar['colegio_egreso']) : null;
        $data['correo1Fam'] = $familiar['email1'];
        $data['correo2Fam'] = $familiar['email2'];
        $data['religionFam'] = (strlen($familiar['religion']) != 0) ? _simple_encrypt($familiar['religion']) : null;
        
        // PART4
        $data['ocupacionFam'] = $familiar['ocupacion'];
        $data['centroTrabajoFam'] = $familiar['centro_trabajo'];
        $data['direccionTrabajoFam'] = $familiar['direccion_trabajo'];
        $data['departamentoTrabajoFam'] = (strlen(substr($familiar['ubigeo_trabajo'], 0, 2)) != 0) ? _simple_encrypt(substr($familiar['ubigeo_trabajo'], 0, 2)) : null;
        $data['provinciaTrabajoFam'] = (strlen(substr($familiar['ubigeo_trabajo'], 2, 2)) != 0) ? _simple_encrypt(substr($familiar['ubigeo_trabajo'], 2, 2)) : null;
        $data['distritoTrabajoFam'] = (strlen(substr($familiar['ubigeo_trabajo'], 4, 2)) != 0) ? _simple_encrypt(substr($familiar['ubigeo_trabajo'], 4, 2)) : null;
        $data['telefonoTrabajoFam'] = $familiar['telf_trabajo'];
        $data['situacionLaboralFam'] = (strlen($familiar['situacion_laboral']) != 0) ? $familiar['situacion_laboral'] : null;
        $data['sueldoFam'] = $familiar['sueldo'];
        $data['cargoFam'] = $familiar['cargo'];
        $data['familiar'] = _simple_encrypt($idFamiliar);
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function checkDocumento()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            $idDocumento = _simple_decrypt(_post("iddocumento"));
            $tipo = _post("tipo");
            if ($idDocumento == null || $tipo == null) {
                throw new Exception(ANP);
            }
            
            if ($tipo == 0) {
                $arrayInsert = array(
                    "id_documento" => $idDocumento,
                    "flg_recibio" => 1,
                    "fec_recibio" => _fecha_tabla(date('Y-m-d'), "d/m/Y"),
                    "fec_registro" => _fecha_tabla(date('Y-m-d'), "d/m/Y"),
                    "id_alumno" => _getSesion('idAlumnoEdit')
                );
                
                $data = $this->m_alumno->insertDocumentoAlumno($arrayInsert);
            } else {
                $data = $this->m_alumno->deleteDocumentoAlumno($idDocumento, _getSesion('idAlumnoEdit'));
            }
            
            if ($data['error'] == EXIT_SUCCESS) {
                $documentos = $this->m_alumno->getDocumentosByAlumno(_getSesion('idAlumnoEdit'));
                $data['tablaDocumentos'] = _createTableDocumentos($documentos);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function changeFechaDocumento()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            $idDocumento = _simple_decrypt(_post("iddocumento"));
            $fecha = _post("fecrecibio");
            if ($idDocumento == null || $fecha == null) {
                throw new Exception(ANP);
            }
            
            $date1 = DateTime::createFromFormat("d/m/Y", $fecha);
            $date2 = DateTime::createFromFormat("d/m/Y", date("d/m/Y"));
            if ($date1 >= $date2) {
                throw new Exception('La fecha debe ser menor a la actual');
            }
            
            // $fechaRegistro = $this->m_alumno->getFechaRegistroDocumentoAlumno($idDocumento, _getSesion('idAlumnoEdit'));
            // $dateRegistro = DateTime::createFromFormat("d/m/Y", _fecha_tabla($fechaRegistro, "d/m/Y"));
            // if($date1 >= $fechaRegistro){
            // throw new Exception('La fecha debe ser menor a la de registro');
            // }
            
            $arrayUpdate = array(
                "fec_recibio" => $fecha
            );
            $data = $this->m_alumno->updateDocumentoAlumno($arrayUpdate, $idDocumento, _getSesion('idAlumnoEdit'));
            if ($data['error'] == EXIT_SUCCESS) {
                $documentos = $this->m_alumno->getDocumentosByAlumno(_getSesion('idAlumnoEdit'));
                $data['tablaDocumentos'] = _createTableDocumentos($documentos);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function editarFamiliar(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            // CABECERA
            $tipodoc = _post('tipodoc');
            $nrodoc = _post('nrodoc');
            if ($tipodoc == null) {
                throw new Exception("Debe ingresar tipo de documento y n&uacute;mero de documento");
            }
            if (! ctype_digit($nrodoc)) {
                throw new Exception("Ingrese un n&uacute;mero de documento v&aacute;lido");
            }
            if ($tipodoc == 1 && strlen($nrodoc) != 12) {
                throw new Exception("El documento elegido debe tener 12 n&uacute;meros");
            }
            if ($tipodoc == 2 && strlen($nrodoc) != 8) {
                throw new Exception("El documento elegido debe tener 8 n&uacute;meros");
            }
            // PART1
            $respEconomico    = _simpleDecryptInt(_post('respeconomico'));
            $parentesco       = _simpleDecryptInt(_post('parentesco'));
            $apoderado        = _simpleDecryptInt(_post('apodarado'));
            $nombres          = utf8_decode(_post('nombres'));
            $apPaterno        = utf8_decode(_post('appaterno'));
            $apMaterno        = utf8_decode(_post('apmaterno'));
            $vive             = (_post('vive'));
            $fecNaci          = (_post('fecnaci') != '') ? _post('fecnaci') : null;
            $pais             = (strlen(_post('pais')) == 0) ? null : _post('pais');
            // PART2
            //$viveEduc = _simpleDecryptInt(_post('viveduc')); // NO BD
            $direccionHogar   = utf8_decode(_post('direccionhogar'));
            $referenciaHogar  = utf8_decode(_post('referenciahogar'));
            $departHogar      = _post('departhogar') != null ? _simpleDecryptInt(_post('departhogar')) : '00';
            $provHogar        = _post('provhogar') != null ? _simpleDecryptInt(_post('provhogar')) : '00';
            $distritHogar     = _post('distrhogar') !=  null ? _simpleDecryptInt(_post('distrhogar')) : '00';
            $telfFijo         = _post('telffijo');
            $telfCel          = _post('telfcel');
            $idioma           = _simpleDecryptInt(_post('idioma'));
            // PART3
            $estadoCivil      = _simpleDecryptInt(_post('estadocivil'));
            $exAlumno         = _simpleDecryptInt(_post('exalumno'));
            $yearEgreso       = _post('yearegreso');
            $coleEgreso       = _simpleDecryptInt(_post('coleegreso'));
            $correo1          = strtolower(_post('correo1'));
            $correo2          = strtolower(_post('correo2'));
            $religion         = _simpleDecryptInt(_post('religion'));
            // PART4
            $ocupacion        = utf8_decode(_post('ocupacion'));
            $centroTrabajo    = utf8_decode(_post('centrotrabajo'));
            $direccionTrabajo = utf8_decode(_post('direcciontrabajo'));
            $departTrabajo    = _post('departtrabajo') != null ? _simpleDecryptInt(_post('departtrabajo')) : '00';
            $provTrabajo      = _post('provtrabajo') != null ? _simpleDecryptInt(_post('provtrabajo')) : '00';
            $distritTrabajo   = _post('distrittrabajo') !=  null ? _simpleDecryptInt(_post('distrittrabajo')) : '00';
            $telfTrabajo      = _post('telftrabajo');
            $sitacionLaboral  = (strlen(_post('sitacionlaboral')) == 0) ? null : _post('sitacionlaboral');
            $sueldo           = _post('sueldo');
            $cargo            = trim(strtoupper(utf8_decode(_post('cargo'))));
            
            if ($sitacionLaboral == DESEMPLEADO) {
                $ocupacion = null;
                $centroTrabajo = null;
                $direccionTrabajo = null;
                $departTrabajo = null;
                $provTrabajo = null;
                $distritTrabajo = null;
                $telfTrabajo = null;
                $sueldo = null;
                $cargo = null;
            }
            
            if ($pais != PAIS_RESIDENTE) {
                $departHogar = null;
                $provHogar = null;
                $distritHogar = null;
            }
            
            $idFamiliar = _simpleDecryptInt(_post("familiar"));
            $codFamilia = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
            
            // VALIDAR DNI REPETIDO
            $familiar = $this->m_alumno->countByTipoDocFamiliar($nrodoc, $tipodoc, $idFamiliar);
            if ($familiar != 0) {
                throw new Exception("Nro de Documento ya existente");
            }
            // VALIDAR MINIMO UNA PERSONA VIVA
            if ($this->m_alumno->getCountFamiliaresVivas($codFamilia, $idFamiliar) == 0 && $vive == 2) {
                throw new Exception("Debe haber m&iacute;nimo una persona viva");
            }
            // VALIDAR NO PUEDE SER RESPO O APODERADO SI ESTA MUERTO
            if ($vive == 2 && ($apoderado == 1 || $respEconomico == 1)) {
                throw new Exception("Debe estar vivo para ser responsable o apoderado");
            }
            // VALIDAR COMO MINIMO 1 RESPONSABLE DE MEDICION
            if ($this->m_alumno->getCountFamiliaresResponsableEconomico($codFamilia, $idFamiliar) == 0 && $respEconomico == 2) {
                throw new Exception("Debe haber como m&iacute;nimo un responsable econ&oacute;mico");
            }
            // VALIDAR COMO MINIMO 1 APODERADO
            if ($this->m_alumno->getCountFamiliaresApoderado($codFamilia, $idFamiliar) == 0 && $apoderado == 2) {
                throw new Exception("Debe haber como m&iacute;nimo un apoderado");
            }
            
            if (strlen(trim($yearEgreso)) != 0) {
                if (is_numeric($yearEgreso) == false || strlen($yearEgreso) != 4 || $yearEgreso <= 0) {
                    throw new Exception(ANP);
                }
            } else {
                $yearEgreso = null;
            }
            
            if (strlen(trim($sueldo)) != 0) {
                if (is_numeric($sueldo) == false || $sueldo <= 0) {
                    throw new Exception(ANP);
                }
            }
            
            if($fecNaci != null){
                if(strlen($fecNaci)!=10){
                    throw new Exception('La fecha es incorrecta');
                }
                $fechaNac = explode('/', $fecNaci);
                if(ctype_digit($fechaNac[0]) == false || ctype_digit($fechaNac[1]) == false || ctype_digit($fechaNac[2]) == false){
                    throw new Exception('La fecha solo puede contener d&iacute;gitos');
                }
                if($fechaNac[0]>31){
                    throw new Exception('El d&iacute;a ingresado no puede ser mayor a 31');
                }
                if($fechaNac[1]>12){
                    throw new Exception('El mes ingresado no puede ser mayor a 12');
                }
                if($fechaNac[2]>_getYear()){
                    throw new Exception('El a&ntilde;o ingresado no puede ser mayor al actual');
                }
            }
            
            /*$date1 = DateTime::createFromFormat("d/m/Y", $fecNaci);
            $date2 = DateTime::createFromFormat("d/m/Y", date("d/m/Y"));
            if ($date1 >= $date2) {
                throw new Exception('La fecha debe ser menor a la actual');
            }*/
            if($vive != 2){
            	if (! filter_var($correo1, FILTER_VALIDATE_EMAIL)) {
            		throw new Exception("Ingrese un correo v&aacute;lido");
            	} else if ($this->m_alumno->validateCorreoRepetidoFamiliar($idFamiliar, $correo1) != 0) {
            	    throw new Exception("El correo ya est&aacute; registrado");
            	}
            }
            
            
            $arrayEditarFamiliar = array(
                "tipo_doc_identidad" => $tipodoc,
                "nro_doc_identidad" => $nrodoc,
                
                "nombres" => (__only1whitespace($nombres)),
                "ape_paterno" => (__only1whitespace($apPaterno)),
                "ape_materno" => (__only1whitespace($apMaterno)),
                "flg_vive" => $vive,
                "fec_naci" => $fecNaci,
                "nacionalidad" => $pais,
                
                "direccion_hogar" => (__only1whitespace($direccionHogar)),
                "refer_domicilio" => (__only1whitespace($referenciaHogar)),
                "ubigeo_hogar" => $departHogar . $provHogar . $distritHogar,
                "telf_fijo" => $telfFijo,
                "telf_celular" => $telfCel,
                "idioma" => $idioma,
                
                "estado_civil" => $estadoCivil,
                "flg_ex_alumno" => $exAlumno,
                "year_egreso" => $yearEgreso,
                "colegio_egreso" => $coleEgreso,
                "email1" => $correo1,
                "email2" => $correo2,
                "religion" => $religion,
                
                "ocupacion" => $ocupacion,
                "sueldo" => $sueldo,
                "centro_trabajo" => (__only1whitespace($centroTrabajo)),
                "direccion_trabajo" => (__only1whitespace($direccionTrabajo)),
                "ubigeo_trabajo" => $departTrabajo . $provTrabajo . $distritTrabajo,
                "telf_trabajo" => $telfTrabajo,
                "situacion_laboral" => $sitacionLaboral,
                "sueldo" => $sueldo,
                "cargo" => $cargo
            );
            
            $arrayEditarFamiliar_1 = array(
                "parentesco" => $parentesco,
                "flg_resp_economico" => $respEconomico,
                "flg_apoderado" => $apoderado
            );
            
            $data = $this->m_alumno->editarFamiliar($arrayEditarFamiliar, $arrayEditarFamiliar_1, $idFamiliar, $codFamilia);
            
            if ($data['error'] == EXIT_SUCCESS) {
                $familia = $this->m_alumno->getFamiliaByCodFam($codFamilia);
                $data['vistaFamiliares'] = _createVistaPadresDeFamilia($familia);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function generarUsuario() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        $this->db->trans_begin();
        try {
            $idfamiliar = _post('idfamiliar') != null ? _simple_decrypt(_post('idfamiliar')) : null;
            if ($idfamiliar == null) {
                throw new Exception('Registre a un familiar apoderado y responsable económico');
            }
            $detalleFamiliar = $this->m_alumno->getDetalleApoderado($idfamiliar);
            $usuario = $detalleFamiliar['usuario_edusys'];
            if($usuario == null) {
                //GENERAR USUARIO USANDO LA FUNCION fun_generar_usuario_familiar (CUANDO SE USEN LOS USUARIOS DE SMILEDU) dfloresgonz 19.11.16
//                 throw new Exception('No tiene usuario, consultar con el Administrador de la plataforma.');

                $usuario = $this->m_alumno->getUsuario($idfamiliar,null,null,null);
            }
            if($detalleFamiliar['email_destino'] == null) {
                throw new Exception('El familiar no tiene asignado un correo electrónico. Registre uno para continuar.');
            }
            $clave = __generateRandomString(7);
            $arrayUpdate = array(
            		"usuario_edusys" => $usuario,
            		"clave_edusys"   => $clave
            );
//             throw new Exception('En construcci&oacute;n');
            $data = $this->m_alumno->editarFamiliar(null, $arrayUpdate, $idfamiliar, $detalleFamiliar['cod_familiar'], 1);
            if ($data['error'] == EXIT_SUCCESS) {
//             $rpta = $this->m_alumno->verificarEstadoEstudiante(_getSesion('idAlumnoEdit'));
//             $data['estado'] = $rpta['entro'];
//             $codFamilia = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
//             $familia = $this->m_alumno->getFamiliaByCodFam($codFamilia);
//             $data['vistaFamiliares'] = _createVistaPadresDeFamilia($familia);
            
            $body    = __bodyMensajeEnvioCredenciales(array('nombres' => $detalleFamiliar['nombre_abvr'],
                                                            'usuario' => $usuario,
                                                            'clave'   => $clave));
            $asunto  = 'Hola '.$detalleFamiliar['nombre_solo'].', aquí tienes tus credenciales de acceso a SMILEDU :)';
            $datosInsert = array(
                'correos_destino' => $detalleFamiliar['email_destino'],
//                 'correos_destino' => 'franco.condor.urp@gmail.com',
                'asunto'          => $asunto,
                'body'            => $body,
                'sistema'         => 'SMILEDU');
            $this->m_utils->insertarEnviarCorreo($datosInsert);
            $data['msj'] = 'Se envió un correo a ('.$detalleFamiliar['email_destino'].') con las credenciales del usuario.';
            $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE[__getCookieName()]);
        $cookie_name2 = __getCookieName();
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
	
	function getSedesByYear() {
		$year  = (_post('year'));
		$sedes = __buildComboSedesByCompromisos($year);
		$data['comboSedes'] = $sedes;
		echo json_encode(array_map('utf8_encode', $data));
	}

    function getNivelesBySede() {
		$year    = (_post('year'));
        $idSede  = _simpleDecryptInt(_post('idsede'));
        $niveles = __buildComboNivelesBySedeCondicion($idSede, $year);
        $data['comboNiveles'] = $niveles;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getGradosByNivel() {
		$year    = (_post('year'));
        $idSede  = _simpleDecryptInt(_post('idsede'));
        $idNivel = _simpleDecryptInt(_post('idnivel'));
        
        $grados = __buildComboGradosByNivelCondicion($idNivel, $idSede, $year );
        $data['comboGrados'] = $grados;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function abrirModalRegistrarFamiliar(){
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = MSJ_ERROR;
    	try {
	    	if(_getSesion('accionDetalleAlumno') == null){
	    		throw new Exception(ANP);
	    	}
	    	if(_getSesion('idAlumnoEdit') == null){
	    		throw new Exception(ANP);
	    	}
	    	$countFamilia = $this->m_alumno->getCountFamiliares(_getSesion('idAlumnoEdit'));
	    	if($countFamilia == 5){
	    		throw new Exception('Ha ingresado el máximo de familiares '.$countFamilia);
	    	}
	    	$detaAlumno = $this->m_alumno->getCamposMinimosAlumno(_getSesion('idAlumnoEdit'));
	    	if($detaAlumno['nom_persona'] == null || $detaAlumno['ape_pate_pers'] == null || $detaAlumno['ape_mate_pers'] == null || $detaAlumno['fec_naci'] == null ||
    			$detaAlumno['sexo'] == null || $detaAlumno['nro_documento'] == null || $detaAlumno['id_grado_ingreso'] == null || $detaAlumno['id_nivel_ingreso'] == null 
	    		|| $detaAlumno['id_sede_ingreso'] == null || $detaAlumno['year_ingreso'] == null) {
	    		throw new Exception('Complete los datos obligatorios del alumno');
    		}
    		$data['error'] = EXIT_SUCCESS;
    	} catch (Exception $e) {
            $data['msj'] = $e->getMessage();
    	}

    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function abrirSelectFotoPersona(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = MSJ_ERROR;
        try {
            if(_getSesion('estadoCambio') == 0){
                throw new Exception('Debe ingresar el nombre del postulante');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getAulasByGradoCantidad(){
        $year = _post("year");
        $sede = _simpleDecryptInt(_post("sede"));
        $nivel = _simpleDecryptInt(_post("nivel"));
        $grado = _simpleDecryptInt(_post("grado"));
        
        $aulas = $this->m_aula->getAulasCantidadByCombo($year, $sede, $nivel, $grado);
        $data['aulas'] = $this->createTableAulasCantidad($aulas);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function createTableAulasCantidad($data){
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                   data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
		                                   data-search="false" id="tb_aulasCantidad">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_1 = array('data' => '#', 'class' => 'text-center');
        $head_2 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left', 'data-sortable' => 'true');
        $head_5 = array('data' => 'Capacidad', 'class' => 'text-center');
        $val = 0;
        $CI->table->set_heading($head_1, $head_2, $head_5);
        foreach($data as $aula) {
            $val++;
            $row_cell_1  = array('data' => $val);
            $row_cell_2  = array('data' => $aula->desc_aula);
            $row_cell_3  = array('data' => $aula->capacidad);
             
            $CI->table->add_row($row_cell_1, $row_cell_2, $row_cell_3);
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
    
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $html = '<p>'.$url.'</p>';
        $html .= '<p>'.$mensaje.'</p>';
        $html .= '<p>'.$nombre.'</p>';
        $arrayInsertCorreo = array('correos_destino'         => CORREO_BASE,
            'asunto'                  => utf8_encode("¡Sugerencias a Smiledu!"),
            'body'                    => $html,
            'estado_correo'           => CORREO_PENDIENTE,
            'sistema'                 => 'SMILEDU');
        $dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
    }
    
    function getNoResponsableNoApoderado(){
    	$data['noVivo'] = _simple_encrypt(2);
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getFamiliaByEstudiante(){
        $cod_familia = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
        $familia = $this->m_alumno->getFamiliaByCodFam($cod_familia);
        $data['vistaFamiliares'] = _createVistaPadresDeFamilia($familia);
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDatosAdmision(){
        $detalleAlumno = $this->m_alumno->camposAdmision(_getSesion('idAlumnoEdit'));
        
        $data['yearIngreso']   = $detalleAlumno['year_ingreso'];
//         $data['sedeIngreso']   = (strlen($detalleAlumno['id_sede_ingreso']) != 0) ? _simple_encrypt($detalleAlumno['id_sede_ingreso']) : null;
//         $data['nivelIngreso']  = (strlen($detalleAlumno['id_nivel_ingreso']) != 0) ? _simple_encrypt($detalleAlumno['id_nivel_ingreso']) : null;
//         $data['gradoIngreso']  = (strlen($detalleAlumno['id_grado_ingreso']) != 0) ? _simple_encrypt($detalleAlumno['id_grado_ingreso']) : null;
        $data['sedeGradoNivel']    = $detalleAlumno['sedegradonivel'];
        $data['observ']            = $detalleAlumno['observacion'];
        $data['accion']            = _getSesion('accionDetalleAlumno');
        echo json_encode(array_map('utf8_encode', $data));
    }
}