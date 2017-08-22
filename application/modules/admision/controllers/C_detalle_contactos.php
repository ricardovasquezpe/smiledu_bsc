<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_detalle_contactos extends CI_Controller {

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
        $this->load->model('m_utils_admision');
        $this->load->model('mf_contactos/m_contactos');
        $this->load->model('mf_contactos/m_detalle_contactos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_CONTACTOS, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
        ////Modal Popup Iconos///
        $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        //MENU
        $data['return'] = '';
        $data['titleHeader'] = "Detalle Contacto";
        $data['ruta_logo'] = MENU_LOGO_ADMISION;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
        $data['nombre_logo'] = NAME_MODULO_ADMISION;
        $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
	                               <a href="#tab-1" class="mdl-layout__tab is-active" onclick="clickTabMenu(3)">INFORMACI&Oacute;N</a>
                                   <a href="#tab-2" class="mdl-layout__tab"  onclick="clickTabMenu(1)">PARIENTES</a>
	                               <a href="#tab-3" class="mdl-layout__tab"  onclick="clickTabMenu(2)">HISTORIAL</a>
                                </div>';

        $menu = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        
        $data['comboTipoDocumento'] = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC);
        $data['comboSexo']          = __buildComboByGrupo(COMBO_SEXO);
        $data['comboOperadores']    = __buildComboByGrupo(COMBO_OPERADOR_TELEF);
        $data['comboCanales']       = __buildComboByGrupo(COMBO_MEDIO_COLEGIO);
	    $data['comboColegios']      = '<option value="0">'.strtoupper('En casa').'</option>'.__buildComboColegios();
	    $data['comboGradoNivel']    = __buildComboGradoNivel();
	    $data['comboDepartamento']  = __buildComboUbigeoByTipo(null, null, 1);

	    $data['comboParentesco']    = __buildComboByGrupo(COMBO_PARENTEZCO);
	    $idcontacto = _getSesion('idContactoEdit');
	    $detalleContacto = $this->m_contactos->getDetalleContacto($idcontacto);
	    $data['apePaterno']          = $detalleContacto['ape_paterno'];
	    $data['apeMaterno']          = $detalleContacto['ape_materno'];
	    $data['nombres']             = $detalleContacto['nombres'];
	    $data['sexo']                = ($detalleContacto['sexo']!=null) ? _simple_encrypt($detalleContacto['sexo']) : null;
	    $data['gradoNivel']          = (strlen($detalleContacto['gradonivel']) != 0) ? _simple_encrypt($detalleContacto['gradonivel']) : null;
	    $data['sedeInt']             = ($detalleContacto['sede_interes']!=null) ? _simple_encrypt($detalleContacto['sede_interes']) : null;
	    $data['colegioProcedencia']  = ($detalleContacto['colegio_procedencia']!=null) ? _simple_encrypt($detalleContacto['colegio_procedencia']) : null;
	    $data['fechaNac']            = _fecha_tabla($detalleContacto['fecha_nacimiento'], 'd/m/Y');
	    $data['tipoDocPostulante']             = ($detalleContacto['tipo_documento']!=null) ? ($detalleContacto['tipo_documento']) : null;
	    $data['nroDoc']              = $detalleContacto['nro_documento'];
	    $data['observacion']         = $detalleContacto['obser_solicitud'];
	    
	    if(_getSesion('accionDetalleContacto') == 1){
	        $data['disabled'] = '';
	        $data['btnCrearPariente'] = '
                            	            <button class="mfb-component__button--main" >
                                                 <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                                             </button>
                            	            <button class="mfb-component__button--main" onclick="abrirModalCrearPariente()" data-mfb-label="Crear Pariente">
                                                                         <i class="mfb-component__main-icon--active mdi mdi-person_add"></i>
                                                                     </button> ';
	    } else if(_getSesion('accionDetalleContacto') == 0){
	        $data['disabled'] = 'disabled';
	    }
	    
	    $data['enc']   = _simple_encrypt(1);
	    $data['noEnc'] = _simple_encrypt(0);
	    
	    $parientes = $this->m_detalle_contactos->getFamiliaresByPostulante($idcontacto);
	    $data['parientes'] = _createVistaParientes($parientes);
	    
	    $historial  = $this->m_detalle_contactos->getHistorialPostulante($idcontacto);
	    $data['tablaHistorial'] = $this->createTableHistorial($historial);
	    
        $this->load->view('v_detalle_contactos',$data);
    }
    
    function detalleContacto(){
        $idContacto = _simpleDecryptInt(_post("contacto"));
        $detalle = $this->m_detalle_contactos->getDetalleContacto($idContacto);
        $data['nombres']      = $detalle['nombres'];
        $data['apPaterno']    = $detalle['ape_paterno'];
        $data['apMaterno']    = $detalle['ape_materno'];
        $data['correo']       = $detalle['correo'];
        $data['celular']      = $detalle['telefono_celular'];
        $data['parentesco']   = ($detalle['parentesco']!=null) ? _simple_encrypt($detalle['parentesco']) : null;
        $data['sexo']         = ($detalle['sexo']!=null) ? _simple_encrypt($detalle['sexo']) : null;
        $data['fijo']         = $detalle['telefono_fijo'];
        $data['refer_dom']    = $detalle['referencia_domicilio'];
        $data['tipoDoc']      = ($detalle['tipo_documento']!=null) ? ($detalle['tipo_documento']) : null;
        $data['nroDoc']       = $detalle['nro_documento'];
        $data['operador']     = ($detalle['operador_telefonico']!=null) ? _simple_encrypt($detalle['operador_telefonico']) : null;
        $data['canal']        = ($detalle['canal_comunicacion']!=null) ? _simple_encrypt($detalle['canal_comunicacion']) : null;
        $data['departamento'] = (strlen(substr($detalle['ubigeo'],0,2)) != 0) ? _simple_encrypt(substr($detalle['ubigeo'],0,2)) : null;
        $data['provincia']    = (strlen(substr($detalle['ubigeo'],2,2)) != 0) ? _simple_encrypt(substr($detalle['ubigeo'],2,2)) : null;
        $data['distrito']     = (strlen(substr($detalle['ubigeo'],4,2)) != 0) ? _simple_encrypt(substr($detalle['ubigeo'],4,2)) : null;
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function editarContacto(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idContacto      = (_post('contacto') == null) ? null :_simpleDecryptInt(_post('contacto'));
            $nombres         = _post('nombres');
            $apellidoPaterno = _post('appaterno');
            $apellidoMaterno = _post('apmaterno');
            $parentesco      = (_post('parentesco') == null) ? null :_simpleDecryptInt(_post('parentesco'));
            $tipoDoc         = (_post('tipodoc') == null) ? null : _post('tipodoc');
            $nroDoc          = _post('nrodoc');
            $sexo            = (_post('sexo') == null) ? null :_simpleDecryptInt(_post('sexo'));
            $correo          = _post('correo');
            $celular         = _post('celular');
            $fijo            = _post('fijo');
            $canal           = (_post('canal') == null) ? null :_simpleDecryptInt(_post('canal'));
            $operador        = (_post('operador') == null) ? null :_simpleDecryptInt(_post('operador'));
            $departmento     = (_post('departamento') == null) ? null :_simpleDecryptInt(_post('departamento'));
            $provincia       = (_post('provincia') == null) ? null :_simpleDecryptInt(_post('provincia'));
            $distrito        = (_post('distrito') == null) ? null :_simpleDecryptInt(_post('distrito'));
            $referencia      = _post('referencia');
            
            if($idContacto == null){
                throw new Exception(ANP);
            }
            
            if($nombres == null || $apellidoPaterno == null || $parentesco == null){
                throw new Exception("Llene los campos obligatorios <strong>(*)</strong>");
            }
            
            if($nroDoc != null){
                if($tipoDoc == TIPO_DOC_DNI && (strlen($nroDoc) != 8 || !ctype_digit($nroDoc))){
                    throw new Exception("Ingrese un tipo de documento v&aacute;lido");
                }
                
                if($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && strlen($nroDoc) != 12){
                    throw new Exception("Ingrese un tipo de documento v&aacute;lido");
                }
                
                if($tipoDoc == TIPO_DOC_DNI && ($this->m_utils->countByTipoDoc($nroDoc, TIPO_DOC_DNI,$idContacto) != 0)){
                    throw new Exception("El n&uacute;mero de documento ya existe");
                }
                
                if($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && ($this->m_utils->countByTipoDoc($nroDoc, TIPO_DOC_CARNET_EXTRANJERO,$idContacto) != 0)){
                    throw new Exception("El n&uacute;mero de documento ya existe");
                }
            }
            
            if($correo != null && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Ingrese un correo v&aacute;lido");
            }
            
            if(strlen($departmento.$provincia.$distrito) > 1 && strlen($departmento.$provincia.$distrito) < 6){
                throw new Exception("Termina de llenar tu ubicación");
            }

            $obligatorio = CAMPOS_OBLIGATORIOS_INCOMPLETOS;
            if($tipoDoc != null && $nroDoc != null && $celular != null && $correo != null){
                $obligatorio = CAMPOS_OBLIGATORIOS_COMPLETOS;
            }
            
            $arrayUpdate = array("nombres"                  => ucwords(__only1whitespace(utf8_decode($nombres))),
                                 "ape_paterno"              => ucwords(__only1whitespace(utf8_decode($apellidoPaterno))),
                                 "ape_materno"              => ucwords(__only1whitespace(utf8_decode($apellidoMaterno))),
                                 "parentesco"               => $parentesco,
                                 "tipo_documento"           => $tipoDoc,
                                 "nro_documento"            => $nroDoc,
                                 "sexo"                     => $sexo,
                                 "correo"                   => $correo,
                                 "telefono_celular"         => $celular,
                                 "telefono_fijo"            => $fijo,
                                 "canal_comunicacion"       => $canal,
                                 "operador_telefonico"      => $operador,
                                 "referencia_domicilio"     => __only1whitespace(utf8_decode($referencia)),
                                 "ubigeo"                   => $departmento.$provincia.$distrito,
                                 "flg_campos_obligatorios"  => $obligatorio);
             
            $data = $this->m_detalle_contactos->updateContacto($arrayUpdate, $idContacto);
            if($data['error'] == EXIT_SUCCESS){
                $familia = $this->m_detalle_contactos->getFlgCampoObligatoriosFamilia($idContacto);
                $updateEstado = ESTADO_CONTACTO_CONTACTADO;
                foreach ($familia as $fam){
                    if($fam->flg_campos_obligatorios != CAMPOS_OBLIGATORIOS_COMPLETOS){
                        $updateEstado = ESTADO_CONTACTO_POR_CONTACTAR;
                    }
                }
                $arrayUpdate2 = array('estado'  => $updateEstado);
                $data = $this->m_detalle_contactos->updateCampoDetalleContacto($arrayUpdate2, _getSesion('idContactoEdit'));
                
                $parientes = $this->m_detalle_contactos->getFamiliaresByPostulante(_getSesion('idContactoEdit'));
                $data['parientes'] = _createVistaParientes($parientes);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function crearContacto(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $nombres         = _post('nombres');
            $apellidoPaterno = _post('appaterno');
            $apellidoMaterno = _post('apmaterno');
            $parentesco      = (_post('parentesco') == null) ? null :_simpleDecryptInt(_post('parentesco'));
            $tipoDoc         = (_post('tipodoc') == null) ? null : _post('tipodoc');
            $nroDoc          = _post('nrodoc');
            $sexo            = (_post('sexo') == null) ? null :_simpleDecryptInt(_post('sexo'));
            $correo          = _post('correo');
            $celular         = _post('celular');
            $fijo            = _post('fijo');
            $canal           = (_post('canal') == null) ? null :_simpleDecryptInt(_post('canal'));
            $operador        = (_post('operador') == null) ? null :_simpleDecryptInt(_post('operador'));
            $departmento     = (_post('departamento') == null) ? null :_simpleDecryptInt(_post('departamento'));
            $provincia       = (_post('provincia') == null) ? null :_simpleDecryptInt(_post('provincia'));
            $distrito        = (_post('distrito') == null) ? null :_simpleDecryptInt(_post('distrito'));
            $referencia      = _post('referencia');

            if($nombres == null || $apellidoPaterno == null || $parentesco == null){
                throw new Exception("Llene los campos obligatorios <strong>(*)</strong>");
            }
    
            if($nroDoc != null){
                if($tipoDoc == TIPO_DOC_DNI && (strlen($nroDoc) != 8 || !ctype_digit($nroDoc))){
                    throw new Exception("Ingrese un tipo de documento v&aacute;lido");
                }
                
                if($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && strlen($nroDoc) != 12){
                    throw new Exception("Ingrese un tipo de documento v&aacute;lido");
                }
                
                if($tipoDoc == TIPO_DOC_DNI && ($this->m_utils->countByTipoDoc($nroDoc,TIPO_DOC_DNI) != 0)){
                    throw new Exception("El n&uacute;mero de documento ya existe");
                }
                
                if($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && ($this->m_utils->countByTipoDoc($nroDoc,TIPO_DOC_CARNET_EXTRANJERO) != 0)){
                    throw new Exception("El n&uacute;mero de documento ya existe");
                }
            }
            
            if ($correo!= null && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Ingrese un correo v&aacute;lido");
            }
            if(strlen($departmento.$provincia.$distrito) > 1 && strlen($departmento.$provincia.$distrito) < 6){
                throw new Exception("Termina de llenar tu ubicación");
            }
    
            $grupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", _getSesion('idContactoEdit'), "admision");   
            $arrayInsert = array("nombres"             => ucwords(__only1whitespace(utf8_decode($nombres))),
                                 "ape_paterno"         => ucwords(__only1whitespace(utf8_decode($apellidoPaterno))),
                                 "ape_materno"         => ucwords(__only1whitespace(utf8_decode($apellidoMaterno))),
                                 "parentesco"          => $parentesco,
                                 "tipo_documento"      => $tipoDoc,
                                 "nro_documento"       => $nroDoc,
                                 "sexo"                => $sexo,
                                 "correo"              => $correo,
                                 "telefono_celular"    => $celular,
                                 "telefono_fijo"       => $fijo,
                                 "canal_comunicacion"  => $canal,
                                 "operador_telefonico" => $operador,
                                 "cod_grupo"           => $grupo,
                                 "referencia_domicilio" => __only1whitespace(utf8_decode($referencia)),
                                 "ubigeo"               => $departmento.$provincia.$distrito,
                                 "flg_estudiante"       => FLG_FAMILIAR
            );
             
            $data = $this->m_detalle_contactos->insertContacto($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $parientes = $this->m_detalle_contactos->getFamiliaresByPostulante(_getSesion('idContactoEdit'));
                $data['parientes'] = _createVistaParientes($parientes);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function eliminarContacto(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $idContacto = _simple_decrypt(_post("contacto"));
            if($idContacto == null){
                throw new Exception(ANP);
            }
            $invitacion = $this->m_detalle_contactos->countEventosByIdContacto($idContacto);
        if($invitacion > 0){
	            throw new Exception("El contacto ya ha sido invitado a por lo menos 1 evento, no puede ser eliminado");
	        }            
            $data = $this->m_detalle_contactos->deleteContacto($idContacto);
            if($data['error'] == EXIT_SUCCESS){
                $parientes = $this->m_detalle_contactos->getFamiliaresByPostulante(_getSesion('idContactoEdit'));
                $data['parientes'] = _createVistaParientes($parientes);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
 
    }

    function getSedesByNivel(){
        $valorNivel = (_post('valorNivel'))==null ? null : _simple_decrypt(_post('valorNivel'));
        if($valorNivel != null){
            $valorNivel = explode('_', $valorNivel);
            $data['comboSedes'] = __buildComboSedesAdmision($valorNivel[1]);
        } else if($valorNivel == null){
            $data['comboSedes'] = __buildComboSedesAdmision(null);
        }
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function onChangeCampo(){
        $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;

	    try {
	        $enc        = (_post('enc')) != null ? _simpleDecryptInt(_post('enc')) : null;
	        $campo      = _post('campo');
	        $idContacto = _getSesion('idContactoEdit');
	        $valor      = (_post('valor') == '') ? null : _post('valor');
	        if($enc == 1 && $valor != null && $campo != 'grado_nivel'){//ENCRIPTADO
	            $valor = _simpleDecryptInt(_post('valor'));
	        } else if ($enc == 1 && $valor != null && $campo == 'grado_nivel'){
	            $valor = _simple_decrypt(_post('valor'));
	        }
	        
	        if($campo == 'nro_documento' && $valor != null){
	            $valor = trim($valor);
	        
	            $tipoDoc = $this->m_utils->getById('admision.contacto', 'tipo_documento', 'id_contacto', _getSesion('idContactoEdit'));
	            if($tipoDoc == TIPO_DOC_DNI){
	                if(ctype_digit($valor)==false){
	                    throw new Exception('Solo ingresar n&uacute;meros en el dni');
	                }
	                if(strlen($valor)!=8){
	                    throw new Exception('Ingresar 8 d&iacute;gitos en el dni');
	                }
	                $countDNI = $this->m_utils->countByTipoDoc($valor, TIPO_DOC_DNI);
	                if($countDNI > 0){
	                    throw new Exception('El dni especificado ya est&aacute; registrado');
	                }
	            } else if($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO){
	                if(strlen($valor)!=12){
	                    throw new Exception('Ingresar 12 caracteres en el carnet de extranjer&iacute;a');
	                }
	                $countDocExtran = $this->m_utils->countByTipoDoc($valor, TIPO_DOC_CARNET_EXTRANJERO);
	                if($countDocExtran > 0){
	                    throw new Exception('El carnet de extranjer&iacute;a especificado ya est&aacute; registrado');
	                }
	            }
	        }
	        if($campo == 'fecha_nacimiento' && $valor != null){
	            if(strlen($valor)!=10){
	                throw new Exception('La fecha es incorrecta');
	            }
	            $fecha = explode('/', $valor);
	            if(ctype_digit($fecha[0]) == false || ctype_digit($fecha[1]) == false || ctype_digit($fecha[2]) == false){
	                throw new Exception('La fecha solo puede contener d&iacute;gitos');
	            }
	            if($fecha[0]>31){
	                throw new Exception('El d&iacute;a ingresado no puede ser mayor a 31');
	            }
	            if($fecha[1]>12){
	                throw new Exception('El mes ingresado no puede ser mayor a 12');
	            }
	            if($fecha[2]>_getYear()){
	                throw new Exception('El a&ntilde;o ingresado no puede ser mayor al actual');
	            }
	        }
	        
	        $arrayUpdate = array($campo => utf8_decode($valor));
	        if($campo == 'colegio_procedencia' ||  $campo == 'sexo' ||  $campo == 'sede_interes' ||  $campo == 'fecha_nacimiento' ){
	            $arrayUpdate = array($campo                    => $valor);
	        }
	        if($campo == 'tipo_documento'){
	            $arrayUpdate = array($campo                    => $valor,
	                                 'nro_documento'           => null);
	        }
	        if($campo == 'grado_nivel'){
	            if($valor != null){
	                $valor = explode('_', $valor);
	                $arrayUpdate = array('grado_ingreso'           => $valor[0],
	                                     'nivel_ingreso'           => $valor[1],
	                                     'sede_interes'            => null);
	            } else if ($valor == null){
	                $arrayUpdate = array('grado_ingreso'           => null,
	                                     'nivel_ingreso'           => null,
	                                     'sede_interes'            => null);
	            }
	        }
	        
	        $data  = $this->m_detalle_contactos->updateCampoDetalleContacto($arrayUpdate, $idContacto);

	        if($data['error'] == EXIT_SUCCESS){
	            $datos = $this->m_contactos->getDetalleContacto($idContacto);
	            $flg_campos = CAMPOS_OBLIGATORIOS_INCOMPLETOS;
	            if(strlen(trim($datos['ape_paterno'])) != 0 && strlen(trim($datos['ape_materno'])) != 0 && strlen(trim($datos['nombres'])) != 0 && $datos['ape_materno']!= POSTULANTE
	                 && $datos['ape_paterno']!= POSTULANTE && $datos['nombres']!= POSTULANTE && $datos['nro_documento'] != NULL){
	                $flg_campos = CAMPOS_OBLIGATORIOS_COMPLETOS;
	            }
	             
	            $arrayUpdate3 = array('flg_campos_obligatorios'   => $flg_campos);
	            $data = $this->m_detalle_contactos->updateCampoDetalleContacto($arrayUpdate3, $idContacto);

	            if($data['error'] == EXIT_SUCCESS){
	                $familia = $this->m_detalle_contactos->getFlgCampoObligatoriosFamilia($idContacto);
	                $updateEstado = ESTADO_CONTACTO_CONTACTADO;
	                foreach ($familia as $fam){
	                    if($fam->flg_campos_obligatorios != CAMPOS_OBLIGATORIOS_COMPLETOS){
	                        $updateEstado = ESTADO_CONTACTO_POR_CONTACTAR;
	                    }
	                }
	                $arrayUpdate2 = array('estado'  => $updateEstado);
	                $data = $this->m_detalle_contactos->updateCampoDetalleContacto($arrayUpdate2, $idContacto);
	            }
	        }
	    } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
	    }
	    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getUbigeoByTipo(){
        $idubigeo  = _simpleDecryptInt(_post("idubigeo"));
        $idubigeo1 = _simpleDecryptInt(_post("idubigeo1"));
        $tipo      = _post("tipo");
        $data['comboUbigeo'] = __buildComboUbigeoByTipo($idubigeo, $idubigeo1, $tipo);
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeMaxlength(){
        $tipo = strlen(_post('tipo')) != 0 ? _simpleDecryptInt(_post('tipo')) : null;
        $data['tipo'] = $tipo;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function createTableHistorial($historial){
        $tabla     = '<table id="hist" class="table">';
        $tabla    .= '<tr>
                           <td class="text-center" style="border-top: none;">#</td>
                           <td class="text-left" style="border-top: none;">Descripci&oacute;n</td>
	                       <td class="text-center" style="border-top: none;">Fecha</td>
                           <td class="text-left" style="border-top: none;">Tipo</td>
	                       <td class="text-center" style="border-top: none;">Estado</td>
                     </tr>';
        $i = 1;
        foreach ($historial as $row) {
            $idEventoEnc = _simple_encrypt($row->id_evento);
            $link = '';
            if(date("d-m-Y") > date("d-m-Y", strtotime($row->fecha_realizar))){
                $asistencia= 'No asisti&oacute;';
                $link = 'class="link-dotted" onclick="verRazonInasistencia(\''.$idEventoEnc.'\')"';
                if($row->asistencia == ASISTENCIA_CONTACTO){
                    $asistencia= 'Asisti&oacute;';
                }
            } else if (date("d-m-Y") <= date("d-m-Y", strtotime($row->fecha_realizar))){
                if($row->opcion == OPCION_ASISTIRA){
                    $asistencia= 'Asistir&aacute;';
                } else if($row->opcion == OPCION_NO_ASISTIRA){
                    $link = 'class="link-dotted" onclick="verRazonInasistencia(\''.$idEventoEnc.'\')"';
                    $asistencia= 'No asistir&aacute;';
                } else if($row->opcion == OPCION_TALVEZ){
                    $asistencia= 'Tal vez asista';
                }
            }

            $tabla .='<tr>
	                       <td class="text-center">'.$i.'</td>
	                       <td class="text-left">'.$row->desc_evento.'</td>
	                       <td class="text-center">'.date_format(date_create($row->fecha_realizar), 'd/m/Y').'</td>
	                       <td class="text-left">'.$row->tipo_evento.'</td>
	                       <td class="text-center"><p '.$link.'  style="display: inline;">'.$asistencia.'</p></td>
            	       </tr>';
            $i++;
        }
         
        $tabla .= '</table>';
        return $tabla;
    }
    
    function verRazonInasistencia(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
            $idevento   = (_post('idevento')!=null ?_simpleDecryptInt(_post('idevento')) : null);
            $idcontacto = _getSesion('idContactoEdit');
            if( $idevento == null || $idcontacto == null ){
                throw new Exception(ANP);
            }
            $razon = $this->m_detalle_contactos->getRazonInasistencia($idevento,_getSesion('idContactoEdit'));
            $data['razon'] = $razon;
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
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