<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_matricula extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
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
		$this->load->model('mf_matricula/m_matricula');
		$this->load->model('pagos/m_compromisos');
		_validate_uso_controladorModulos(ID_SISTEMA_MATRICULA, ID_PERMISO_CONFIRMACION_DATOS, MATRICULA_ROL_SESS);
		$this->_idUserSess = _getSesion('nid_persona');
		$this->_idRol      = _getSesion(MATRICULA_ROL_SESS);
	}

	public function index(){
	    $combosFamiliar = array(COMBO_SI_NO,COMBO_PARENTEZCO,COMBO_ESTADO_CIVIL,COMBO_NIVEL_INST,COMBO_SEXO,COMBO_RELIGION,COMBO_IDIOMA,COMBO_DIFICULTAD,COMBO_DISCAPACIDAD,COMBO_SIST_OPERATIVO, COMBO_TIPO_SANGRE);
	    $combos = __buildComboByGrupos($combosFamiliar);
	    $data['comboSiNo']              = $combos[0];
	    $data['comboParentesco']        = $combos[1];
	    $data['comboEstadoCivil']       = $combos[2];
	    $data['comboNivelInstr']        = $combos[3];
		$data['comboSexo']              = $combos[4];
	    $data['comboReligion']          = $combos[5];
	    $data['comboIdioma']            = $combos[6];
	    $data['comboNivelIngles']       = $combos[7];
	    $data['comboDiscapacidad']      = $combos[8];
	    $data['comboSistemaOperativo']  = $combos[9];
	    $data['comboTipoSangre']        = $combos[10];
	    
	    $combosNoEncrypt = __buildComboByGruposNoEncrypt(array(COMBO_SI_NO,COMBO_TIPO_DOC,COMBO_SITUACION_LABORAL,COMBO_COLEGIO_EGRESO));
	    $data['comboSiNoSinEncrypt']    = $combosNoEncrypt[0];
	    $data['comboTipoDocumento']     = $combosNoEncrypt[1];
	    $data['comboSituacionLabo']     = $combosNoEncrypt[2];
	    $data['comboColegioEgreso']     = $combosNoEncrypt[3];
	    
	    $data['comboColegios']          = __buildComboColegios();
	    $data['comboPaises']            = __buildComboPaises(1);
	    $data['comboPaisesSinEncrypt']  = __buildComboPaises();
	    $data['comboDepartamento']      = __buildComboUbigeoByTipo(null, null, 1);
	    //$data['comboGradoNivel']     = __buildComboGradoNivel();
	    
		$data['barraSec'] = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
            	             <a href="#tab-0" class="mdl-layout__tab is-active" id="tabTodos" onclick= "stepMatricula(this)">Matr&iacute;cula</a>
            	             <a href="#tab-1" class="mdl-layout__tab" onclick= "stepMatricula(this)">Pariente</a>
            	             <a href="#tab-2" class="mdl-layout__tab" onclick= "stepMatricula(this)">Postulante</a>
            	             </div>';
		$data['iconUpdate'] = '<button id="btn-update-data" class="mdl-button mdl-js-button mdl-button--icon mdl-icon__save" onclick="updatecard()">
                                    <i class="mdi mdi-refresh"></i>
                               </button>';
		//CARDS DE ESTUDIANTES DEL PRIMER TAB (TODOS)
		$alumnos = $this->m_matricula->getAlumnosByFamilia(_getSesion('cod_familiar'));
	    $fechas = $this->m_matricula->getFechasReferenciaByTipo('R');
		$data['tablaAlumnos'] = _createCardAlumnos($alumnos, null, 1, null, $fechas);
		
 		$data['enc']   = _simple_encrypt(1);
 		$data['noEnc'] = _simple_encrypt(0);
		
	    $parientes       = $this->m_matricula->getParientesByFamilia(_getSesion('cod_familiar'));//cod_familiar
        $data['idfamiliar']     = $parientes[0]->id_familiar != null ? $parientes[0]->id_familiar : null;
//         //DIFERENCIAR SI ES MATRICULA O RATIFICACION
		$postulantes            = $this->m_matricula->getHijosByFamiliaPagoMatricula(_getSesion('cod_familiar'));//cod_familia
		if(sizeof($postulantes) == 0){
			$data['ingreso'] = 'style="display:none"';
			$data['none']    = '';
		} else {
		    $detallePostulante          = $this->m_matricula->getDetallePostulante($postulantes[0]->nid_persona);//nid_persona
		    $data['idpostulante']       = $postulantes[0]->nid_persona;
		}
        
		//ENVIAMOS LA DATA A LA VISTA
		$data['ruta_logo']        = MENU_LOGO_MATRICULA;
		$data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_MATRICULA;
		$data['nombre_logo']      = NAME_MODULO_MATRICULA;

		$data['titleHeader'] =  'Confirmaci&oacute;n de datos';
		$data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_MATRICULA, MATRICULA_FOLDER);
		$rolSistemas   = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATRICULA, $this->_idUserSess);
		$data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
		$menu = $this->load->view('v_menu', $data, true);
		$data['menu'] = $menu;
		$this->load->view('v_matricula',$data);
	}
	
	function onChangeCampo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try {
	        $enc        = (_post('enc')) != null ? _simpleDecryptInt(_post('enc')) : null;
	        $campo      = _post('campo');
	        $idFamiliar = _post('idpariente') != null ? (_post('idpariente')) : null;
	        $valor      = (_post('valor') == '') ? NULL : _post('valor');
	        
	        if($idFamiliar == null){
	            throw new Exception(ANP);
	        }
	        
	        if($enc == 1 && $valor != null){//ENCRIPTADO
	            $valor = _simpleDecryptInt($valor);
	        }

	        //OBLIGATORIOS 1
	        if($valor == null){
                throw new Exception('No puede dejar el campo vac&iacute;o');
	        }
	        if($campo == 'nro_doc_identidad' && $valor != null){
	            $valor = trim($valor);

	            $tipoDoc = $this->m_utils->getById('familiar', 'tipo_doc_identidad', 'id_familiar', $idFamiliar);
	            if($tipoDoc == TIPO_DOC_DNI){
	                if(ctype_digit($valor)==false){
	                    throw new Exception('Solo ingresar n&uacute;meros en el dni');
	                }
	                if(strlen($valor)!=8){
	                    throw new Exception('Ingresar 8 d&iacute;gitos en el dni');
	                }
	                $countDNI = $this->m_matricula->countByTipoDocMatriculaFamiliares($valor, TIPO_DOC_DNI, $idFamiliar, FLG_FAMILIAR);
	                if($countDNI > 0){
	                    throw new Exception('El dni especificado ya est&aacute; registrado');
	                }
	            } else if($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO){
	                if(strlen($valor)!=12){
	                    throw new Exception('Ingresar 12 caracteres en el carnet de extranjer&iacute;a');
	                }
	                $countDocExtran = $this->m_matricula->countByTipoDocMatricula($valor, TIPO_DOC_CARNET_EXTRANJERO, $idFamiliar, FLG_FAMILIAR);
	                if($countDocExtran > 0){
	                    throw new Exception('El carnet de extranjer&iacute;a especificado ya est&aacute; registrado');
	                }
	            }
	        }

	        if($campo == 'fec_naci'){
	            $date1  = DateTime::createFromFormat("d/m/Y", $valor);
	            $date2  = DateTime::createFromFormat("d/m/Y", date("d/m/Y"));
	            if($date1 > $date2){
	                throw new Exception('La fecha debe ser menor a la actual');
	            }
	            $valor = _fecha_tabla($valor, 'd/m/Y');
	        }
	        
	        if($campo == 'email1' && $valor != null && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
	            throw new Exception("Ingrese un correo v&aacute;lido");
	        }
	        
	        if($campo == 'departamento'){
	            $campo = 'ubigeo_hogar';
	        }else if($campo == 'provincia'){
	            $ubigeo = $this->m_utils->getById("familiar", "ubigeo_hogar", "id_familiar", $idFamiliar);
	            $depart = substr($ubigeo,0,2);
	            $valor = $depart.$valor;
	            $campo = 'ubigeo_hogar';
	        }else if($campo == 'distrito'){
	            $ubigeo  = $this->m_utils->getById("familiar", "ubigeo_hogar", "id_familiar", $idFamiliar);
	            $depart  = substr($ubigeo,0,2);
	            $provinc = substr($ubigeo,2,2);
	            $valor = $depart.$provinc.$valor;
	            $campo = 'ubigeo_hogar';
	        }
	        
	        if($campo == 'year_egreso' &&  (ctype_digit($valor)==false || $valor >= _getYear() || strlen($valor) != 4) ){
	            throw new Exception('Ha ingresado un a&ntilde;o de egreso incorrecto');
	        }
	        
            $arrayUpdate = array($campo  => $valor);
            if($campo == 'ape_paterno' || $campo == 'ape_materno' || $campo == 'nombres' || $campo == 'direccion_hogar' || $campo == 'refer_domicilio'
                 || $campo == 'ocupacion' || $campo == 'cargo' || $campo == 'centro_trabajo' || $campo == 'direccion_trabajo'){
                $arrayUpdate = array($campo  => utf8_decode($valor));
            }


            if($campo == 'flg_ex_alumno' && $valor != null ){
            	$arrayUpdate = array($campo           => $valor,
            			             'year_egreso'      => null );
            }

	        if($campo == 'situacion_laboral'){
	            $arrayUpdate = array($campo                    => $valor,
                                     'ocupacion'               => null,
                                     'cargo'                   => null,
                                     'centro_trabajo'          => null,
                                     'direccion_trabajo'       => null);
	        }
	        
            if($campo == 'tipo_doc_identidad'){
                $arrayUpdate = array($campo                    => $valor,
                                     'nro_doc_identidad'       => null);
            }
	        $table = 'familiar';
	        if($campo == 'parentesco' || $campo == 'flg_resp_economico' || $campo == 'flg_apoderado'){
	            $table = 'sima.familiar_x_familia';
	        }
            $data  = $this->m_matricula->updateCampoFamiliar($arrayUpdate, $idFamiliar,$table);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function onChangeCampoPostulante(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try {
	        $enc          = (_post('enc')) != null ? _simpleDecryptInt(_post('enc')) : null;
	        $campo        = _post('campo');
	        $id_postulante = _post('idpostulante');
	        $valor        = (_post('valor') == '') ? NULL : _post('valor');

	        if($id_postulante == null){
	            throw new Exception(ANP);
	        }
	        if($valor == null){
	            throw new Exception('No puede dejar el campo vac&iacute;o');
	        }
	        
	        if ($campo == 'encargado_contacto' && strlen($valor) > 100){
	        	throw new Exception('Has sobrepasado el l&iacute;mite de caracteres permitidos');
	        }
	        
	        if($enc == 1 && $valor != null && $campo != 'grado_nivel'){
	            $valor = _simpleDecryptInt($valor);
	        } else if ($enc == 1 && $valor != null && $campo == 'grado_nivel'){
	            $valor = _simple_decrypt($valor);
	        }
	        
	        if(($campo == 'total_hermano' || $campo == 'nro_hermano') && $valor == 0){
	            throw new Exception('Ingrese un valor mayor a 0');
	        }
	        
	        if(($campo == 'total_hermano' || $campo == 'nro_hermano') && ctype_digit($valor)==false){
	            throw new Exception('Solo ingresar n&uacute;meros');
	        }
	        
	        if($campo == 'total_hermano'){
	            $nroHermano = $this->m_utils->getById("sima.detalle_alumno", "nro_hermano", "nid_persona", $id_postulante);
	            if($valor < $nroHermano){
	                throw new Exception('El lugar que ocupa es mayor al n&uacute;mero de hermanos');
	            }
	        }
	        
	        if($campo == 'nro_hermano'){
	            $totalHermano = $this->m_utils->getById("sima.detalle_alumno", "total_hermano", "nid_persona", $id_postulante);
	            if($valor > $totalHermano){
	                throw new Exception('El lugar que ocupa es mayor al n&uacute;mero de hermanos');
	            }
	        }

	        if($campo == 'departamento'){
	            $campo = 'ubigeo';
	        }else if($campo == 'provincia'){
	            $ubigeo = $this->m_utils->getById("sima.detalle_alumno", "ubigeo", "nid_persona", $id_postulante);
	            $depart = substr($ubigeo,0,2);
	            $valor = $depart.$valor;
	            $campo = 'ubigeo';
	        }else if($campo == 'distrito'){
	            $ubigeo  = $this->m_utils->getById("sima.detalle_alumno", "ubigeo", "nid_persona", $id_postulante);
	            $depart  = substr($ubigeo,0,2);
	            $provinc = substr($ubigeo,2,2);
	            $valor = $depart.$provinc.$valor;
	            $campo = 'ubigeo';
	        }

	        if($campo == 'fec_naci'){
	            $date1  = DateTime::createFromFormat("d/m/Y", $valor);
	            $date2  = DateTime::createFromFormat("d/m/Y", date("d/m/Y"));
	            if($date1 > $date2){
	                throw new Exception('La fecha debe ser menor a la actual');
	            }
	            //$valor = _fecha_tabla($valor, 'Y-m-d');
	        }
	        
	        if($campo == 'evacuacion_contacto' && strlen($valor) > 100){
	            throw new Exception('solo admite m&aacute;ximo 100 caracteres');
	        }
	        
	        if($campo == 'evacuacion_contacto' && strlen($valor) > 100){
	            throw new Exception('solo admite m&aacute;ximo 100 caracteres');
	        }
	        
	        $arrayUpdate = array($campo  => $valor);
	        if($campo == 'ape_pate_pers' || $campo == 'ape_mate_pers' || $campo == 'nom_persona' || $campo == 'convivencia' || $campo == 'familiar_frecuente'
	        || $campo == 'tipo_sangre' || $campo == 'alergia' || $campo == 'evacuacion_contacto'){
                $arrayUpdate = array($campo  => utf8_decode($valor));
	        }

	        if($campo == 'tipo_documento'){
	            $arrayUpdate = array($campo                    => $valor,
	                                 'nro_documento'           => null);
	        }

	        if($campo == 'flg_alergia'){
	            $arrayUpdate = array($campo                    => $valor,
	                                 'alergia'           => null);
	        }if($campo == 'pais'){
	            $arrayUpdate = array($campo                    => $valor,
	                                 'ubigeo'           => null);
	        }
	        
	        $table = 'persona';
	        if($campo == 'total_hermano' || $campo == 'nro_hermano' || $campo == 'religion' || $campo == 'pais' || $campo == 'ubigeo' || $campo == 'colegio_procedencia'
	            || $campo == 'lengua_materna' || $campo == 'flg_padres_juntos' || $campo == 'convivencia' || $campo == 'familiar_frecuente' || $campo == 'flg_nac_registrado'
	            || $campo == 'nac_complicaciones' || $campo == 'grado_nivel' || $campo == 'tipo_discapacidad' || $campo == 'peso' || $campo == 'talla' || $campo == 'alergia' 
	        		|| $campo == 'flg_alergia' || $campo == 'flg_permiso_datos' || $campo == 'flg_permiso_fotos' || $campo == 'evacuacion_contacto' || $campo == 'encargado_contacto'){//CAMBIAR
	            $table = 'sima.detalle_alumno';
	        }
	        
	        $data  = $this->m_matricula->updateCampoPostulante($arrayUpdate, $id_postulante,$table);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
    }
	
	function getUbigeoByTipo(){
	    $idubigeo  = _simple_decrypt(_post("idubigeo"));
	    $idubigeo1 = _simple_decrypt(_post("idubigeo1"));
	    $tipo      = _post("tipo");
	    $data['comboUbigeo'] = __buildComboUbigeoByTipo($idubigeo, $idubigeo1, $tipo);
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
    function registrarColegio(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $colegio = utf8_decode(_post("colegio"));
            if($colegio == null || strlen(trim($colegio)) == 0){
                throw new Exception(ANP);
            }
            
            $existe = $this->m_matricula->validateColegioRepetido($colegio);
            if($existe > 0){
                throw new Exception("El colegio ingresado ya existe");
            }

            $arrayInsert = array("desc_colegio" => $colegio);
    
            $data = $this->m_matricula->insertColegio($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $data['comboColegios'] = __buildComboColegios();
                $data['colegio'] = _simple_encrypt($data['id']);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function createButtonFamiliares($parientes){
        $opcion = "";
        $i = 0;
        foreach ($parientes as $par) {
            $active = $i == 0 ? 'active' : '';
            $opcion.= ' <span class="mdl-chip mdl-chip--contact mdl-chip--deletable '.$active.' chip-parientes" id="chip'.$par->id_familiar.'" onclick="verDatosFamiliar(\''._simple_encrypt($par->id_familiar).'\',1)">
                            <img class="mdl-chip__contact" src="'.((file_exists(FOTO_PROFILE_PATH . 'familiares/' . $par->foto_persona)) ? RUTA_IMG_PROFILE . 'familiares/' . $par->foto_persona : RUTA_IMG_PROFILE . "nouser.svg").'"></img>
                            <span class="mdl-chip__text">'.$par->nombres.'</span>
                            <div class="mdl-chip__action"><i class="mdi mdi-state"></i></div>
                        </span>';
            $i++;
        }
        return $opcion;
    }
    
	function createButtonPostulantes($postulantes){
	    $opcion = "";
	    $i = 0;
	    
	    foreach ($postulantes as $pos) {
	        $active = $i == 0 ? 'active' : '';
	        $complete = $pos->confirmo_datos != 0 ? 'complete' : '';
	        $opcion.= ' <span class="mdl-chip mdl-chip--contact mdl-chip--deletable '.$active.' chip-postulantes '.$complete.'" id="chip'.$pos->nid_persona.'" onclick="verDatosPostulante(\''._simple_encrypt($pos->nid_persona).'\',1)">
                            <img class="mdl-chip__contact" src="'.((file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $pos->foto_persona)) ? RUTA_IMG_PROFILE . 'estudiantes/' . $pos->foto_persona : RUTA_IMG_PROFILE . "nouser.svg").'"></img>
                            <span class="mdl-chip__text">'.$pos->nombres.'</span>
                            <div class="mdl-chip__action"><i class="mdi mdi-state"></i></div>
                        </span>';
	        $i++;
	    }
	    return $opcion;
	}
	
	function verDatosFamiliar(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	    	$tipo = _post('tipo');
	    	$id_familiar = null;
	    	if($tipo == 1){
	    		$id_familiar = _post('idfamiliar') != null ? _simpleDecryptInt(_post('idfamiliar')) : null;
	    	} else if ($tipo == 2){
    	    	$id_familiar = _post('idfamiliar') != null ? (_post('idfamiliar')) : null;
	    	}
    	    if($id_familiar == null){
    	        throw new Exception(ANP);
    	    }
    	    if($tipo == 2){
    	        $parientes                  = $this->m_matricula->getParientesByFamilia(_getSesion('cod_familiar'));
    	        $data['parientes']          = $this->createButtonFamiliares($parientes);
    	    }
	        $detallePariente               = $this->m_matricula->getDetallePariente($id_familiar);
	        $data['parentesco']            = $detallePariente['parentesco'] != null ? _simple_encrypt($detallePariente['parentesco']) : null;
	        $data['flg_resp_economico']    = $detallePariente['flg_resp_economico'] != null ? _simple_encrypt($detallePariente['flg_resp_economico']) : null;
	        $data['flg_apoderado']         = $detallePariente['flg_apoderado'] != null ? _simple_encrypt($detallePariente['flg_apoderado']) : null;
	        $data['ape_paterno']           = $detallePariente['ape_paterno'] != null ? ($detallePariente['ape_paterno']) : null;
	        $data['ape_materno']           = $detallePariente['ape_materno'] != null ? ($detallePariente['ape_materno']) : null;
	        $data['nombres']               = $detallePariente['nombres'] != null ? ($detallePariente['nombres']) : null;
	        $data['email1']                = $detallePariente['email1'] != null ? ($detallePariente['email1']) : null;
	        $data['flg_vive']              = $detallePariente['flg_vive'] != null ? _simple_encrypt($detallePariente['flg_vive']) : null;
	        $data['sexo']                  = $detallePariente['sexo'] != null ? _simple_encrypt($detallePariente['sexo']) : null;
	        $data['fec_naci']              = $detallePariente['fec_naci'] != null ? _fecha_tabla($detallePariente['fec_naci'], "d/m/Y") : null;
	        $data['nacionalidad']          = $detallePariente['nacionalidad'] != null ? _simple_encrypt($detallePariente['nacionalidad']) : null;
	        $data['tipo_doc_identidad']    = $detallePariente['tipo_doc_identidad'] != null ? ($detallePariente['tipo_doc_identidad']) : null;
	        $data['nro_doc_identidad']     = $detallePariente['nro_doc_identidad'] != null ? ($detallePariente['nro_doc_identidad']) : null;
	        $data['estado_civil']          = $detallePariente['estado_civil'] != null ? _simple_encrypt($detallePariente['estado_civil']) : null;
	        $data['idioma']                = $detallePariente['idioma'] != null ? _simple_encrypt($detallePariente['idioma']) : null;
	        $data['nivel_instruccion']     = $detallePariente['nivel_instruccion'] != null ? _simple_encrypt($detallePariente['nivel_instruccion']) : null;
	        $data['colegio_egreso']        = $detallePariente['flg_ex_alumno'] != null ? ($detallePariente['flg_ex_alumno']) : null;
	        $data['religion']              = $detallePariente['religion'] != null ? _simple_encrypt($detallePariente['religion']) : null;
	        $data['ocupacion']             = $detallePariente['ocupacion'] != null ? ($detallePariente['ocupacion']) : null;
	        $data['centro_trabajo']        = $detallePariente['centro_trabajo'] != null ? ($detallePariente['centro_trabajo']) : null;
	        $data['direccion_trabajo']     = $detallePariente['direccion_trabajo'] != null ? ($detallePariente['direccion_trabajo']) : null;
	        $data['situacion_laboral']     = $detallePariente['situacion_laboral'] != null ? ($detallePariente['situacion_laboral']) : null;
	        $data['cargo']                 = $detallePariente['cargo'] != null ? ($detallePariente['cargo']) : null;
	        $data['year_egreso']           = $detallePariente['year_egreso'] != null ? ($detallePariente['year_egreso']) : null;
	        $data['direccion_hogar']       = $detallePariente['direccion_hogar'] != null ? ($detallePariente['direccion_hogar']) : null;
	        $data['refer_domicilio']       = $detallePariente['refer_domicilio'] != null ? ($detallePariente['refer_domicilio']) : null;
	        $data['telf_fijo']             = $detallePariente['telf_fijo'] != null ? ($detallePariente['telf_fijo']) : null;
	        $data['telf_celular']          = $detallePariente['telf_celular'] != null ? ($detallePariente['telf_celular']) : null;
	        $data['departamento']          = (strlen(substr($detallePariente['ubigeo_hogar'],0,2)) != 0) ? _simple_encrypt(substr($detallePariente['ubigeo_hogar'],0,2)) : null;
	        $data['provincia']             = (strlen(substr($detallePariente['ubigeo_hogar'],2,2)) != 0) ? _simple_encrypt(substr($detallePariente['ubigeo_hogar'],2,2)) : null;
	        $data['distrito']              = (strlen(substr($detallePariente['ubigeo_hogar'],4,2)) != 0) ? _simple_encrypt(substr($detallePariente['ubigeo_hogar'],4,2)) : null;
	        $data['flg_nivel_dom_ingles']  = $detallePariente['flg_nivel_dom_ingles'] != null ? _simple_encrypt($detallePariente['flg_nivel_dom_ingles']) : null;
	        $data['movil_datos']           = $detallePariente['movil_datos'] == null ? null : _simple_encrypt($detallePariente['movil_datos']);
	        $data['so_movil']              = $detallePariente['so_movil'] == null ? null : _simple_encrypt($detallePariente['so_movil']);
	        
	        $data['familiar'] = $id_familiar;
	        $data['error']   = EXIT_SUCCESS;
	    } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function verDatosPostulante(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	    	$tipo = _post('tipo');
	    	$id_postulante = null;
	    	if($tipo == 1){
	    		$id_postulante = _post('idpostulante') != null ? _simpleDecryptInt(_post('idpostulante')) : null;
	    	} else if ($tipo == 2){
    	    	$id_postulante = _post('idpostulante') != null ? (_post('idpostulante')) : null;
	    	}
    	    if($id_postulante == null){
    	        throw new Exception(ANP);
    	    }

    	    if($tipo == 2){
    	        $postulantes                = $this->m_matricula->getHijosByFamiliaPagoMatricula(_getSesion('cod_familiar'));//cod_familia
    	        $data['postulantes']        = $this->createButtonPostulantes($postulantes);
    	    }
	        $detallePostulante            = $this->m_matricula->getDetallePostulante($id_postulante);
	        $data['ape_paterno_pos']      = $detallePostulante['ape_pate_pers'];
	        $data['ape_materno_pos']      = $detallePostulante['ape_mate_pers'];
	        $data['nombres_pos']          = $detallePostulante['nom_persona'];
	        $data['sexo_pos']             = $detallePostulante['sexo'] == null ? null : _simple_encrypt($detallePostulante['sexo']);
	        $data['tipoDoc_pos']          = $detallePostulante['tipo_documento'] == null ? null : ($detallePostulante['tipo_documento']);
	        $data['nroDoc_pos']           = $detallePostulante['nro_documento'];
	        $data['lenguamaterna_pos']    = $detallePostulante['lengua_materna'] == null ? null : _simple_encrypt($detallePostulante['lengua_materna']);
	        $data['flg_padres_juntos']    = $detallePostulante['flg_padres_juntos'] == null ? null : _simple_encrypt($detallePostulante['flg_padres_juntos']);
	        $data['total_hermano']        = $detallePostulante['total_hermano'];
	        $data['convivencia']          = $detallePostulante['convivencia'];
	        $data['familiar_frecuente']   = $detallePostulante['familiar_frecuente'];
	        $data['nro_hermano']          = $detallePostulante['nro_hermano'];
	        $data['religionPos']          = $detallePostulante['religion'] == null ? null : _simple_encrypt($detallePostulante['religion']);
	        $data['paisPos']              = $detallePostulante['pais'] == null ? null : ($detallePostulante['pais']);
	        $data['departamentoPos']      = (strlen(substr($detallePostulante['ubigeo'],0,2)) != 0) ? _simple_encrypt(substr($detallePostulante['ubigeo'],0,2)) : null;
	        $data['provinciaPos']         = (strlen(substr($detallePostulante['ubigeo'],2,2)) != 0) ? _simple_encrypt(substr($detallePostulante['ubigeo'],2,2)) : null;
	        $data['distritoPos']          = (strlen(substr($detallePostulante['ubigeo'],4,2)) != 0) ? _simple_encrypt(substr($detallePostulante['ubigeo'],4,2)) : null;
// 	        $data['gradoNivel']           = (strlen($detallePostulante['gradonivel']) != 0) ? _simple_encrypt($detallePostulante['gradonivel']) : null;
	        $data['colegio_proc']         = $detallePostulante['colegio_procedencia'] == null ? null : _simple_encrypt($detallePostulante['colegio_procedencia']);
	        $data['fec_naci_pos']         = $detallePostulante['fec_naci'] != null ? _fecha_tabla($detallePostulante['fec_naci'], "d/m/Y") : null;
	        $data['tipo_sangre']          = $detallePostulante['tipo_sangre'] == null ? null : _simple_encrypt($detallePostulante['tipo_sangre']);
	        $data['flg_nac_registrado']   = $detallePostulante['flg_nac_registrado'] == null ? null : _simple_encrypt($detallePostulante['flg_nac_registrado']);
	        $data['nac_complicaciones']   = $detallePostulante['nac_complicaciones'] == null ? null : _simple_encrypt($detallePostulante['nac_complicaciones']);
	        $data['tipo_discapacidad']    = $detallePostulante['tipo_discapacidad'] == null ? null : _simple_encrypt($detallePostulante['tipo_discapacidad']);
	        $data['flg_alergia']          = $detallePostulante['flg_alergia'] == null ? null : ($detallePostulante['flg_alergia']);
	        $data['peso']                 = $detallePostulante['peso'];
	        $data['talla']                = $detallePostulante['talla'];
	        $data['alergia']              = $detallePostulante['alergia'];
	        $data['flg_permiso_datos']    = $detallePostulante['flg_permiso_datos'] == null ? null : _simple_encrypt($detallePostulante['flg_permiso_datos']);
	        $data['flg_permiso_fotos']    = $detallePostulante['flg_permiso_fotos'] == null ? null : _simple_encrypt($detallePostulante['flg_permiso_fotos']);
	        $data['evacuacion_contacto']  = $detallePostulante['evacuacion_contacto'];
	        $data['encargado_contacto']   = $detallePostulante['encargado_contacto'];
	        
	        $data['disabled']             = null;
// 	        $confirmoDatos = $this->m_matricula->countConfirmacionDatos($detallePostulante['year_ingreso'], $id_postulante,'P');
// 	        if($confirmoDatos > 0){
// 	            $data['disabled']             = 1;
// 	        }
	        	
	        $data['postulante'] = $id_postulante;
	        $data['error']   = EXIT_SUCCESS;
	    } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTableEstudiantesCronograma($calendar,$descuento,$codigo,$year, $cuotIngreso,$id_postulante, $okProm) {
	    $tmpl  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                                              data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                                              data-search="false" id="tb_compromisoCalendarAlu-'.$codigo.'">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_2      = array('data' => 'Descripci&oacute;n', 'class' => 'text-center');
	    $head_3      = array('data' => 'F. de vencimiento' , 'class' => 'text-center');
	    $head_4      = array('data' => 'F. de descuento'   , 'class' => 'text-center');
	    $head_5      = array('data' => 'Monto S/.'             , 'class' => 'text-center');
	    $head_6      = array('data' => 'Beca'			   , 'class' => 'text-center');
	    $head_6_7      = array('data' => 'F. de Pago'	   , 'class' => 'text-center');
	    $head_7      = array('data' => 'Estado'			   , 'class' => 'text-center');
	     
	    $this->table->set_heading($head_2, $head_3,$head_4,$head_5,$head_6, $head_6_7, $head_7);
	    $val2=0;

	    if($cuotIngreso != null && $cuotIngreso != 0){
	        $datosIngreso = $this->m_matricula->datosIngresoPostulante($id_postulante);
	        if($datosIngreso['estado']  == ALUMNO_PREREGISTRO || $datosIngreso['estado']  == ALUMNO_REGISTRADO){
	            $sede         = $datosIngreso['id_sede_ingreso'];
	            $nivel        = $datosIngreso['id_nivel_ingreso'];
	            $grado        = $datosIngreso['id_grado_ingreso'];
	            $year         = $datosIngreso['year_ingreso'];
	            $countAulas   = 0;
	        } else {
	            $sede         = $datosIngreso['id_sede_ratificacion'];
	            $nivel        = $datosIngreso['id_nivel_ratificacion'];
	            $grado        = $datosIngreso['id_grado_ratificacion'];
	            $year         = $datosIngreso['year_ratificacion'];
	            $countAulas   = $datosIngreso['countaulas'];
	        }
			$fechaMatricula = $this->m_matricula->getFechaMatricula($sede,$nivel,$grado,$year);
    		$row_cell_2           = array('data'   => 'Cuota Ingreso', 'class' => 'text-center');
    		$row_cell_3           = array('data'   => $fechaMatricula != null ? _fecha_tabla($fechaMatricula, "d/m/Y") : '-', 'class' => 'text-center');
    		$row_cell_4           = array('data'   => '-', 'class' => 'text-center');
    		$row_cell_5           = array('data'   => $cuotIngreso, 'class' => 'text-center');
    		$row_cell_6           = array('data'   => '-', 'class' => 'text-center');
    		$row_cell_6_7         = array('data'   => '-', 'class' => 'text-center');
    		$row_cell_7           = array('data'   => 'Compromiso a generar', 'class' => 'text-center');
    		$this->table->add_row($row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5,$row_cell_6, $row_cell_6_7, $row_cell_7);
    		$val2++;
	    }
	    foreach ($calendar as $row2){
            $val2++;
            
            $detalle = _encodeCI($row2->id_detalle_cronograma);
            $row_cell_2           = array('data'   => (($row2->detalle)), 'class' => 'text-center');
            $row_cell_3           = array('data'   => _fecha_tabla(strtolower($row2->fecha_v), "d/m/Y"), 'class' => 'text-center');
            $row_cell_4           = array('data'   => ($row2->fecha_d != NULL) ? (_fecha_tabla(strtolower($row2->fecha_d), "d/m/Y")) : '-', 'class' => 'text-center');
            if($row2->flg_tipo == 2){
                $row_cell_5           = array('data'   => (($okProm == 1) ? $row2->monto_matricula_prom : (strtolower($row2->monto))), 'class' => 'text-center');
            } else {
                $row_cell_5           = array('data'   => (strtolower($row2->monto)), 'class' => 'text-center');
            }
            $row_cell_6           = array('data'   => ($row2->descuento == 'BECA') ? (strtolower(round($descuento).' %')) : '-','class' => 'text-center');
            $row_cell_6_7         = array('data'   => $row2->fecha_pago != null ? _fecha_tabla($row2->fecha_pago, "d/m/Y") : '-', 'class' => 'text-center');
            $row_cell_7           = array('data'   => ($row2->estado),'class' => 'text-center');
            $this->table->add_row($row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5,$row_cell_6, $row_cell_6_7,$row_cell_7);
	    }
	    return array("table" => $this->table->generate(),'codigo' =>$codigo);
	}
	
	function verificarPagos(){
	    $data['error']         = EXIT_ERROR;
	    $data['msj']           = null;
	    $data['confirmoDatos'] = 0;
	    try {
	    	$id_postulante = _post('idpostulante') != null ? (_post('idpostulante')) : null;
	    	if($id_postulante == null){
	    		throw new Exception(ANP);
	    	}
	    	
	    	$datosIngreso = $this->m_matricula->datosIngresoPostulante($id_postulante);
	    	
	    	$sede         = $datosIngreso['id_sede_ratificacion'];
	    	$nivel        = $datosIngreso['id_nivel_ratificacion'];
	    	$grado        = $datosIngreso['id_grado_ratificacion'];
	    	$year         = $datosIngreso['year_ratificacion'];
	    	
	    	$data['confirmoDatos'] = null;
	        $detalles     = $this->m_matricula->getCountCompromisosEstudiante($id_postulante, $year, $sede, $nivel, $grado);
//         	$confirmoDatos = $this->m_matricula->countConfirmacionDatos($year, $id_postulante,'P');
        	if(count($detalles) != 0){
        		if($detalles[0]->count_compromisos != 0
        		    // && $confirmoDatos > 0
        		    ){
	        		//$data['confirmoDatos']  = 1;
		        	$data['error']    = EXIT_SUCCESS;
		        }
        	}
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function confirmacionDatos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
        	//$idpersona      = $this->_idUserSess;
	        $id_postulante = _post('idpostulante') != null ? (_post('idpostulante')) : null;
	    	if($id_postulante == null){
	    		throw new Exception(ANP);
	    	}
	    	
	    	$year = $this->m_utils->getById('sima.detalle_alumno', 'year_ingreso', 'nid_persona', $id_postulante);
	    	$arrayInsert = array("id_familiar"        => $this->_idUserSess,
	    			             "year_confirmacion"  => $year,
	    			             "id_estudiante"      => $id_postulante,
		                         "tipo"               => 'P');
	    	
	    	$data = $this->m_matricula->insertConfirmacion($arrayInsert);
	    	if($data['error'] == EXIT_SUCCESS){
	    		$alumnoUpdate = array("estado" => ALUMNO_MATRICULABLE);
	    		$data = $this->m_alumno->updateCampoDetalleAlumno($alumnoUpdate, $id_postulante, 1);
	    	}
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function abrirModalConfirmarGenerarRatificacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
//             $id_postulante = _post('idpostulante') != null ? _simpleDecryptInt(_post('idpostulante')) : null;
//             if($id_postulante == null){
//                 throw new Exception(ANP);
//             }
//             $datosIngreso = $this->m_matricula->datosIngresoPostulante($id_postulante);
// 	    	if($datosIngreso['deuda'] != 0){
//  	    		throw new Exception('Debe cancelar todas sus deudas');
// 	    	}
//             $countDeudas = $this->m_matricula->getDeudasByEstudiantes($datosIngreso['cod_alumno_temp']);
//             if(count($countDeudas) != 0){
//                 throw new Exception('Debe cancelar todas sus deudas');
//             }
// 	        $fechas = $this->m_matricula->getFechasReferenciaByTipo('R');
// 	        if(count($fechas) == 0){
//                  throw new Exception('No se ha configurado la fecha de ratificaci&oacute;n');
// 	        }
//             $fechaIniRat = explode('-', $fechas['fec_inicio']);
//             $fechaAct = explode('-', date("Y-m-d"));
//             $okRat = 0;
//             if($fechaAct[1] == $fechaIniRat[1]){
//                 if($fechaAct[2] < $fechaIniRat[2]){
//                     $okRat = 1;
//                 }
//             } else if ($fechaAct[1] < $fechaIniRat[1]) {
//                 $okRat = 1;
//             }
            $data['error']    = EXIT_SUCCESS;
//             $gradonivel = $this->m_matricula->getGradoNivelRatificacion($id_postulante);//
//             $idgradoactual = $this->m_utils->getById('sima.detalle_alumno', 'id_grado_ingreso', 'nid_persona', $id_postulante);
//             if($okRat != 0){//ante de la fecha de ratificaci�n
//                 $gradonivel = $this->m_matricula->getGradoNivelRatificacion($datosIngreso['id_grado_ingreso']);
//                 $data['gradoNivel'] = $gradonivel->desc_grado.' - '.$gradonivel->desc_nivel;
//                 $data['error']    = EXIT_SUCCESS;
//             } else if ($okRat == 0){
//                 $gradonivel = $this->m_matricula->getGradoNivelRatificacion($datosIngreso['id_grado_ingreso']+1);
//                 $data['gradoNivel'] = $gradonivel['desc_grado'].' - '.$gradonivel['desc_nivel'];
//                 $data['error']      = EXIT_SUCCESS;
//             }
            /**Verificar si ya se registro la aceptacion de terminos **/
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
	}
    
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo').' ('._getSesion('nid_persona').')';
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $html = '<p>'.$url.'</p>';
        $html .= '<p>'.$mensaje.'</p>';
        $html .= '<p>'.$nombre.'</p>';
        $arrayInsertCorreo = array(
            'correos_destino'         => CORREO_BASE,
            'asunto'                  => utf8_encode("�Sugerencias a Smiledu!"),
            'body'                    => $html,
            'estado_correo'           => CORREO_PENDIENTE,
            'sistema'                 => 'SMILEDU');
        $dataCorreo = $this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
    }
    
    function generarCuotas(){//RATIFICACION Y CUOTAS DEL A�O
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $id_postulante = _post('idpostulante') != null ? (_post('idpostulante')) : null;
            if($id_postulante == null){
                throw new Exception(ANP);
            }
            
            $datosIngreso = $this->m_matricula->datosIngresoPostulante($id_postulante);
            if($datosIngreso['estado']  == ALUMNO_PREREGISTRO || $datosIngreso['estado']  == ALUMNO_REGISTRADO){
            	$sede         = $datosIngreso['id_sede_ingreso'];
            	$nivel        = $datosIngreso['id_nivel_ingreso'];
            	$grado        = $datosIngreso['id_grado_ingreso'];
            	$year         = $datosIngreso['year_ingreso'];
            	$countAulas   = 0;
            } else {
	            $sede         = $datosIngreso['id_sede_ratificacion'];
	            $nivel        = $datosIngreso['id_nivel_ratificacion'];
	            $grado        = $datosIngreso['id_grado_ratificacion'];
	            $year         = $datosIngreso['year_ratificacion'];
	            $countAulas   = $datosIngreso['countaulas'];
            }
//             if($datosIngreso['deuda'] != 0){
//                 throw new Exception('Debe cancelar todas sus deudas');
//             }
            $fechas = $this->m_matricula->getFechasReferenciaByTipo('R');
            if(count($fechas) == 0){
                throw new Exception('No se ha configurado la fecha de ratificaci&oacute;n');
            }
            $fechaAct = explode('-', date("Y-m-d"));
            $okProm = 0;
            $configPromo =  $this->m_matricula->getConfigPromocion($sede, $year);
            if(count($configPromo) != 0){
                if($configPromo['flg_promo'] == 1){
                    $fechaIniProm = explode('-', $configPromo['fecha_fin_promo']);
                    if($fechaAct[0] == $fechaIniProm[0]){
                        if($fechaAct[1] == $fechaIniProm[1]){
                            if($fechaAct[2] < $fechaIniProm[2]){
                                $okProm = 1;
                            }
                        } else if ($fechaAct[1] < $fechaIniProm[1]) {
                            $okProm = 1;
                        }
                    } else if($fechaAct[0] < $fechaIniProm[0]){
                        $okProm = 1;
                    }
                }
            }
            
	    	if($sede != null && $nivel != null && $grado != null && $year != null){
	    		$idpersona      = $this->_idUserSess;
	    		$getnombre      = _getSesion('nombre_abvr');
	    		$compromisos    = array(array());
	    		$datos_audi_mov = array(array());

	    		$i=0;
	    		$countCuotasConfiguradas = $this->m_matricula->getCountCuotasConfiguradas($sede, $nivel, $grado, $year, $id_postulante);
	    		if($countCuotasConfiguradas['cant'] == 0){
	    			throw new Exception('Sin cuotas por generar');
	    		}
	    		$porcentaje     = (100-$this->m_matricula->getBecaByPersona($id_postulante))/100;
	    		$idCondicion    = $this->m_matricula->getId_condicionAlumno($sede,$nivel,$grado,$year,ANIO_LECTIVO);
	    		$countRat       = $countCuotasConfiguradas['ratificacion'];
	    		$countMat       = $countCuotasConfiguradas['matricula'];
	    		$countPens      = $countCuotasConfiguradas['pensiones'];
	    		$flg_cuota_ingreso = null;
	    		$cuotIngreso       = null;
	    		
	    		if($countPens != 0){
	    			throw new Exception('Sin cuotas por generar');
	    		}
	    		
	    		if($countAulas != 0) {
	    			if($year == _getYear()){
    		    		$arrayCuotas = ($countRat == 0) ? array((string)FLG_RATIFICACION,(string)FLG_CUOTA) : array((string)FLG_CUOTA);
    		    	} else {
    		    		$arrayCuotas = ($countRat == 0) ? array((string)FLG_RATIFICACION) : array('');
    		    	}
    		    } else {
    		        $flg_cuota_ingreso = $this->m_compromisos->checkSi_generarCuotaIngreso($year, $sede, _getSesion('cod_familiar'), $id_postulante);
    		        $cuotIngreso = $flg_cuota_ingreso == 0 ? $this->m_alumno->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year) : null;
    		    	/*$config           = $this->m_alumno->getConfig($year, $sede);
    		    	if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
    		    		if($config['flg_afecta'] == 1){
    		    			$flg_cuota_ingreso = $this->m_matricula->evaluateCuotaIngresoByFamilia(_getSesion('cod_familiar'));
    		    		} else if($config['flg_afecta'] == 2){
    		    			$flg_cuota_ingreso = $this->m_matricula->evaluateCuotaIngresoByPersona($id_postulante);
    		    			if ($datosIngreso['estado'] == ALUMNO_RETIRADO){
    		    			    $flg_cuota_ingreso = 0;
    		    			}
    		    		}
    		    		$cuotIngreso = $flg_cuota_ingreso == 0 ? $this->m_alumno->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year) : null;
    		    	}*/
    		    	if($year == _getYear()){
    		    		$arrayCuotas = ($countMat == 0) ? array((string)FLG_MATRICULA,(string)FLG_CUOTA) : array((string)FLG_CUOTA);
    		    	} else {
    		    		$arrayCuotas = ($countMat == 0) ? array((string)FLG_MATRICULA) : array('');
    		    	}
    		    }
	    		$detalles       = $this->m_alumno->getCompromisosEstudiante($id_postulante, $year, $sede, $nivel, $grado, $arrayCuotas);

	    		if(count($detalles) == 0) {
	    			throw new Exception('No hay conceptos para el estudiante');
	    		}
				if($cuotIngreso != null && $cuotIngreso != 0){
					$cuotIngreso    = $this->m_alumno->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year);
					$fechaMatricula = $this->m_matricula->getFechaMatricula($sede,$nivel,$grado,$year);
    		        $push = array("tipo_movimiento"         => MOV_INGRESO,
    		                	  "estado"                  => ESTADO_POR_PAGAR,
    		                	  "monto"                   => $cuotIngreso,
    		                	  "monto_final"             => $cuotIngreso,
	    						  "_id_persona"             => $id_postulante,
    		                	  "_id_concepto"            => CUOTA_INGRESO,
    		        		      "_id_detalle_cronograma"  => null,
    		        		      "fecha_vencimiento_aux"   => $fechaMatricula,
    		        		      "descuento_acumulado"     => null );
    		        array_push($compromisos, $push);
				}
	    		foreach ($detalles as $item){
	    			$itemDecry         = ($item->id_detalle_cronograma);
    	            if($item->flg_tipo == '1'){
    	                $monto = $item->monto;
    	                $montoFinal = $item->monto;
    	            } else if ($item->flg_tipo == '2'){
    	                $monto = ($okProm == 1) ? $item->monto_matricula_prom : $item->monto;
    	                $montoFinal = ($okProm == 1 && $item->flg_tipo == '2') ? $item->monto_matricula_prom : $item->monto;
    	            } else {
    	                $monto = $item->monto_base;
    	                $montoFinal = $item->monto;
    	            }
	    			$push = array("tipo_movimiento"   => MOV_INGRESO,
	    					//"estado"                => $necesario['estado'],
	    			        "estado"                  => 'POR PAGAR',
	    					"monto"                   => $monto,
	    					"monto_final"             => $montoFinal,
	    					"_id_persona"             => $id_postulante,
	    					"_id_detalle_cronograma"  => (($item->id_detalle_cronograma) == "") ? null : $itemDecry,
	    					"_id_concepto"            => (($itemDecry == null) ? CUOTA_INGRESO : CONCEPTO_SERV_ESCOLAR),
	    					"fecha_vencimiento_aux"   => $item->fecha_v,
	    					"descuento_acumulado"     => $item->descuento_nivel);
	    			array_push($compromisos, $push); $i++;
	    		}
	    		unset($compromisos[0]);
	    		$data 				= $this->m_matricula->SaveCompromisosMovimientos($compromisos);
	    		$fisrt_id_mov 		= ($data['id_movimiento'] - $data['n_total_mov']+1);
	    		$last_id_mov  		= $data['id_movimiento'];
	    		for($i = $fisrt_id_mov ; $i<= $last_id_mov; $i++){
	    			$push = array('_id_movimiento' => $i,
        	    				  'correlativo'    => $this->m_matricula->getNextCorrelativo($i),
        	    				  'id_pers_regi'   => $idpersona,
        	    				  'audi_nomb_regi' => $getnombre,
        	    				  'accion'         => REGISTRAR,
	    		                  'flg_audi_regi'  => 2);
	    			array_push($datos_audi_mov, $push);
	    		}
	    		unset($datos_audi_mov[0]);
	    		$condicion_x_persona = array(
	    				'_id_condicion'  => $idCondicion,
	    				'_id_persona'    => $id_postulante,
	    				'estado'         => FLG_ESTADO_ACTIVO,
	    				'flg_beca'       => 0,
	    		        'year_uso'       => $year
	    		);
	    		$data = $this->m_matricula->SaveCompromisosAudiMovimientos($datos_audi_mov,$condicion_x_persona);
 
	    		if($data['error'] == EXIT_SUCCESS){
                    $alumnos = $this->m_matricula->getAlumnosByFamilia(_getSesion('cod_familiar'));
            		$data['tablaAlumnos'] = _createCardAlumnos($alumnos, null, 1, null, $fechas);
	    		}
    		}
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function mostrarCompromisosYearAlumnoGenerar(){
        $data['error']     = EXIT_ERROR;
        $data['msj']       = null;
        $data['confirmoDatos']  = 0;
        try {
            $id_postulante = _post('idpostulante') != null ? (_post('idpostulante')) : null;
            
            if($id_postulante == null){
                throw new Exception(ANP);
            }
            $datosIngreso = $this->m_matricula->datosIngresoPostulante($id_postulante);
            if($datosIngreso['estado']  == ALUMNO_PREREGISTRO || $datosIngreso['estado']  == ALUMNO_REGISTRADO){
            	$sede         = $datosIngreso['id_sede_ingreso'];
            	$nivel        = $datosIngreso['id_nivel_ingreso'];
            	$grado        = $datosIngreso['id_grado_ingreso'];
            	$year         = $datosIngreso['year_ingreso'];
            	$countAulas   = 0;
            } else {
	            $sede         = $datosIngreso['id_sede_ratificacion'];
	            $nivel        = $datosIngreso['id_nivel_ratificacion'];
	            $grado        = $datosIngreso['id_grado_ratificacion'];
	            $year         = $datosIngreso['year_ratificacion'];
	            $countAulas   = $datosIngreso['countaulas'];
            }

            $fechaAct = explode('-', date("Y-m-d"));
            $okProm = 0;
            $fechas = null;
//             if($datosIngreso['deuda'] > 0){
//                 throw new Exception('Debe cancelar sus deudas vencidas');
//             }
			if($countAulas != 0) {
				$fechas = $this->m_matricula->getFechasReferenciaByTipo('R');
				if(count($fechas) == 0){
					throw new Exception('La fecha de ratificaci&oacute;n no ha sido configurada');
				}
				$configPromo =  $this->m_matricula->getConfigPromocion($sede, $year);
				if(count($configPromo) != 0){
					if($configPromo['flg_promo'] == 1){
						$fechaIniProm = explode('-', $configPromo['fecha_fin_promo']);
						if($fechaAct[0] == $fechaIniProm[0]){
							if($fechaAct[1] == $fechaIniProm[1]){
								if($fechaAct[2] < $fechaIniProm[2]){
									$okProm = 1;
								}
							} else if ($fechaAct[1] < $fechaIniProm[1]) {
								$okProm = 1;
							}
						} else if($fechaAct[0] < $fechaIniProm[0]){
							$okProm = 1;
						}
					}
				}
			}
//             $fechaIniRat = explode('-', $fechas['fec_inicio']);
            
//             $okRat = 0; // NEXT YEAR
//             if($fechaAct[1] == $fechaIniRat[1]){
//                 if($fechaAct[2] < $fechaIniRat[2]){
//                     $okRat = 1;// THIS YEAR
//                 }
//             } else if ($fechaAct[1] < $fechaIniRat[1]) {
//                 $okRat = 1;// THIS YEAR
//             }
            
//                 }
            $countCuotasConfiguradas = $this->m_matricula->getCountCuotasConfiguradas($sede, $nivel, $grado, $year, $id_postulante);
            if($countCuotasConfiguradas['cant'] != 0){
                $data['btn'] = 0;
                //$calendar     = $this->m_matricula->ValidarCronoAluCompromisos($sede,$nivel,$grado,$year,$id_postulante,$tipo);
                $countRat   = $countCuotasConfiguradas['ratificacion'];
                $countMat   = $countCuotasConfiguradas['matricula'];
                $countPens  = $countCuotasConfiguradas['pensiones'];
                $data['complete'] = 0;
                $flg_cuota_ingreso = null;
                $cuotIngreso       = null;
                
                if ($year != _getYear() && $countRat != 0 && $countAulas != 0){
                	$data['btn'] = 1;
                }
                if ($year != _getYear() && $countMat != 0 && $countAulas == 0){
                	$data['btn'] = 2;
                }
                if($countPens != 0){
                	$arrayCuotas = array('');
                    $data['btn'] = 3;
                	$data['complete'] = 1; 
                } else {
                	if($countAulas != 0) {
                		if($year == _getYear()){
                			$arrayCuotas = ($countRat == 0) ? array((string)FLG_RATIFICACION,(string)FLG_CUOTA) : array((string)FLG_CUOTA);
                		} else {
                			$arrayCuotas = ($countRat == 0) ? array((string)FLG_RATIFICACION) : array('');
                		}
                	} else {
                		$config           = $this->m_alumno->getConfig($year, $sede);
                		if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
                			if($config['flg_afecta'] == 1){
                				$flg_cuota_ingreso = $this->m_matricula->evaluateCuotaIngresoByFamilia(_getSesion('cod_familiar'));
                			} else if($config['flg_afecta'] == 2){
                				$flg_cuota_ingreso = $this->m_matricula->evaluateCuotaIngresoByPersona($id_postulante);
                				if ($datosIngreso['estado'] == ALUMNO_RETIRADO){
                				    $flg_cuota_ingreso = 0;
                				}
                			}
                			$cuotIngreso = $flg_cuota_ingreso == 0 ? $this->m_alumno->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year) : null;
                		}
                		if($year == _getYear()){
                			$arrayCuotas = ($countMat == 0) ? array((string)FLG_MATRICULA,(string)FLG_CUOTA) : array((string)FLG_CUOTA);
                		} else {
                			$arrayCuotas = ($countMat == 0) ? array((string)FLG_MATRICULA) : array('');
                		}
                	}
                }
                $detalles         = $this->m_alumno->getCompromisosEstudiante($id_postulante, $year, $sede, $nivel, $grado, $arrayCuotas);
                $tab = $this->getTableEstudiantesCronograma($detalles,null,$id_postulante, $year, $cuotIngreso,$id_postulante, $okProm);
                $data['table']       = $tab['table'];
                $data['codigo']      = $tab['codigo'];
//                 $confirmoDatos = $this->m_matricula->countConfirmacionDatos($year, $id_postulante,'P');
//                 if($confirmoDatos > 0){
//                     $data['confirmoDatos']  = 1;
//                 } else {
                
                
//                 }
                
                $data['error']       = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
	function allCompromisos(){
	    $data['error']     = EXIT_ERROR;
	    $data['msj']       = null;
	    $data['confirmoDatos']  = 0;
	    try {
	    	$id_postulante = _post('idpostulante') != null ? (_post('idpostulante')) : null;
    	    if($id_postulante == null){
    	        throw new Exception(ANP);
    	    }
    	    $datosIngreso = $this->m_matricula->datosIngresoPostulante($id_postulante);
    	    if($datosIngreso['estado']  == ALUMNO_PREREGISTRO || $datosIngreso['estado']  == ALUMNO_REGISTRADO){
                $sede         = $datosIngreso['id_sede_ingreso'];
                $nivel        = $datosIngreso['id_nivel_ingreso'];
                $grado        = $datosIngreso['id_grado_ingreso'];
                $year         = $datosIngreso['year_ingreso'];
                $countAulas   = 0;
            } else {
                $sede         = $datosIngreso['id_sede_ratificacion'];
                $nivel        = $datosIngreso['id_nivel_ratificacion'];
                $grado        = $datosIngreso['id_grado_ratificacion'];
                $year         = $datosIngreso['year_ratificacion'];
                $countAulas   = $datosIngreso['countaulas'];
            }
	        $countCuotasConfiguradas = $this->m_matricula->getCountCuotasConfiguradas($sede, $nivel, $grado, $year, $id_postulante);
	        if($countCuotasConfiguradas['cant'] != 0){
	        	$fechaMatricula = null;
	            $cuotIngreso = null;
	            if($countAulas != 0) {
	                $arrayCuotas = array((string)FLG_RATIFICACION,(string)FLG_CUOTA) ;
	            } else {
	                $arrayCuotas = array((string)FLG_MATRICULA,(string)FLG_CUOTA) ;

	                $config       = $this->m_alumno->getConfig($year, $sede);
	                if(count($config) != 0 && $config['estado'] == ESTADO_ACTIVO){
	                    $cuotIngreso    = $this->m_alumno->getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year);
	                    $fechaMatricula = $cuotIngreso != 0 ? $this->m_matricula->getFechaMatricula($sede,$nivel,$grado,$year) : null;
	                }
	            }
	            
	        	$calendar     = $this->m_matricula->allCompromisos($sede,$nivel,$grado,$year,$id_postulante, $arrayCuotas);
	        	
	        	$tab = $this->getTableEstudiantesCronogramaCompleto($calendar, $year,$id_postulante,$cuotIngreso, $fechaMatricula);
	        	$data['table']       = $tab['table'];
	        	$data['codigo']      = $tab['codigo'];
        		$data['year']        = $year;
	        	$data['error']       = EXIT_SUCCESS;
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
    function getTableEstudiantesCronogramaCompleto($calendar,$year,$id_postulante, $cuotIngreso, $fechaMatricula) {
        $tmpl  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                                              data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                                              data-search="false" id="tb_compromisoCalendarAlu-'.$id_postulante.'">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_2      = array('data' => 'Descripci&oacute;n', 'class' => 'text-center');
        $head_3      = array('data' => 'F. de vencimiento' , 'class' => 'text-center');
        $head_4      = array('data' => 'F. de descuento'   , 'class' => 'text-center');
        $head_5      = array('data' => 'Monto S/.'             , 'class' => 'text-center');
    
        $this->table->set_heading($head_2, $head_3,$head_4,$head_5);
        $val2=0;
        if($cuotIngreso != null && $cuotIngreso != 0){
        	$row_cell_2           = array('data'   => 'Cuota Ingreso', 'class' => 'text-left');
        	$row_cell_3           = array('data'   => $fechaMatricula != null ? _fecha_tabla($fechaMatricula, "d/m/Y") : '-', 'class' => 'text-center');
        	$row_cell_4           = array('data'   => '-', 'class' => 'text-center');
        	$row_cell_5           = array('data'   => $cuotIngreso, 'class' => 'text-center');
        	$this->table->add_row($row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5);
        	$val2++;
        }
        foreach ($calendar as $row2){
            $val2++;
            $detalle = _encodeCI($row2->id_detalle_cronograma);
            $row_cell_2           = array('data'   => (($row2->detalle)), 'class' => 'text-left');
            $row_cell_3           = array('data'   => _fecha_tabla(strtolower($row2->fecha_v), "d/m/Y"), 'class' => 'text-center');
            $row_cell_4           = array('data'   => ($row2->fecha_d != NULL) ? (_fecha_tabla(strtolower($row2->fecha_d), "d/m/Y")) : '-', 'class' => 'text-center');
            $row_cell_5           = array('data'   => (strtolower($row2->monto)), 'class' => 'text-center');
            $this->table->add_row($row_cell_2, $row_cell_3,$row_cell_4,$row_cell_5);
        }
        return array("table" => $this->table->generate(),'codigo' =>$id_postulante);
    }
    
    function abrirModalDeclaracionPDF(){
        $id_postulante = _post('idpostulante') != null ? _simpleDecryptInt(_post('idpostulante')) : null;
        
        $data['doc'] = $this->m_matricula->getDocDeclaracion($id_postulante);

        $data['error']     = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }
}