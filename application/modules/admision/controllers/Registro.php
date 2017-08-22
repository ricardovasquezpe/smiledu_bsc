<?php defined('BASEPATH') OR exit('No direct script access allowed');

class registro extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_formulario/m_formulario');
        $this->load->model('mf_contactos/m_contactos');
        $this->load->model('matricula/mf_alumno/m_alumno');
        $this->load->library('table');
        $this->load->library('googleplus');
    }
    
    public function index() {
    	$data['optTiposCrono']      = __buildComboTiposCronograma(1);
        $data['comboParentesco']    = __buildComboByGrupo(COMBO_PARENTEZCO);
        $data['comboSexo']          = __buildComboByGrupo(COMBO_SEXO);
        $data['comboTipoDocumento'] = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC);
        $data['comboMedioColegio']  = __buildComboByGrupo(COMBO_MEDIO_COLEGIO);
        $data['comboOperador']      = __buildComboByGrupo(COMBO_OPERADOR_TELEF);
        $data['comboDepartamento']  = __buildComboUbigeoByTipo(null, null, 1);
	    $data['comboColegios']      = /*'<option value="0">'.strtoupper('En casa').'</option>'.*/__buildComboColegios();
	    $data['comboGradoNivel']    = __buildComboGradoNivel();
        $data['comboCanales']       = __buildComboByGrupo(COMBO_CANAL_COMUNICACION);
	    
        $data['mostrar'] =  'style="display:none"';
	    $data['disabledNext'] = 'disabled';
	    $data['checkgroup']   = '';
	    //_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion(ADMISION_ROL_SESS) == ID_ROL_SECRETARIA || _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR
	    if(_getSesion('nid_persona') != null && _getSesion('cod_familiar') == null) {
	        $data['checkgroup']   = 'style="display:none"';
	        $data['obligatorio']= ' (*)';
	        $data['mostrar'] = 'style="display:block"';
	        $data['disabledNext'] = '';
	        $data['comboOpciones'] = __buildComboByGrupo(COMBO_TIPO_OPCION_ASISTENCIA);
	        $data['comboEventosFuturos'] = __buildComboEventosFuturos();
	        if(!empty($data['comboEventosFuturos'])){
	            $data['toEvent']=
	            '<p class="text-center" style="font-size: 15px" id="txtRegistro">Confirma tu asistencia a nuestro pr&oacute;ximo evento o finaliza tu registro</p>
    		        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="nextStep(4)" id="btnGoToInscribir">Inscribirme a evento</button>
    	            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="location.reload()" id="btnSalirEnviarForm">FINALIZAR</button>';
	        } else {
    	        $data['toEvent']= 
    	        '<p class="text-center" style="font-size: 15px" id="txtRegistro">En este momento no hay eventos pr&oacute;ximos, estaremos en contacto contigo.</p>
    		        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="location.reload()" id="btnSALIR">FINALIZAR</button>';
	        }
	    } else if(_getSesion('nid_persona') == null || _getSesion('cod_familiar') != null) {
	    	$data['obligatorioFam']= ' (*)';
	        $evento          = $this->m_formulario->getEventoRegistro();
	        $data['evento']  = $evento['desc_evento'];
    	    if($evento == null){
    	        $data['toEvent']= 
    	        '<p class="text-center" style="font-size: 15px" id="txtRegistro">En este momento no hay eventos pr&oacute;ximos, estaremos en contacto contigo.</p>
    		        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="location.reload()" id="btnSALIR">FINALIZAR</button>';
    	    } else {
    	        $data['toEvent']= 
    	        '<p class="text-center" style="font-size: 15px" id="txtRegistro">Confirma tu asistencia a nuestro pr&oacute;ximo evento o finaliza tu registro</p>
    		        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="nextStep(4)" id="btnGoToInscribir">Inscribirme a evento</button>
    	            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="location.reload()" id="btnSalirEnviarForm">FINALIZAR</button>';
    	    }
    	    $data['fecha']          = $evento['fecha_realizar'] != NULL ? date('d/m/Y', strtotime($evento['fecha_realizar'])) : NULL;
    	    $data['hora']           = $evento['hora_inicio'] != NULL ? date('g:i a', strtotime($evento['hora_inicio'])) : NULL;
    	    $data['idevento']       = $evento['id_evento'];
    	    $data['observacion']    = $evento['observacion'];
    	    
    	    if($evento == null){
    	        $data['disabled'] = 'disabled';
    	    } else {
    	        $data['disabled'] = null;
    	    }
	    }
	    
	    $this->load->view('v_formulario',$data);
    }
    
    public function createButton(){
        $i = 0;
        $c = 0;
        $array   = _post('array');
        $tipo    = _post('tipo');
        $opcion  = null;
        $count   = 0;
        $editar  = _post('editar');
        $crear  = _post('crear');
        $active = '';
        $countArray = count($array);
        if($array != null){
            if($tipo == 1){
                foreach ($array as $par) {
                	if($crear != null){
                		$i++;
                		$active = ($i == $countArray) ? 'active' : '';
                	}
                    $count++;
                    $nombre = ucwords(strtolower(utf8_decode($par['nombre'])))." ".ucwords(substr(utf8_decode($par['apellidopaterno']),0, 1)).".";
                    $opcion.= '<span class="mdl-chip mdl-chip--contact mdl-chip--deletable '.$active.'  chip-parientes m-b-15" onclick="verFormularioPariente(\''._simple_encrypt($par['index_serie']).'\')" id="chip'.$par['index_serie'].'">
                                    <img class="mdl-chip__contact" alt="Estudiante" src="'.RUTA_SMILEDU.'uploads/images/foto_perfil/nouser.svg" style="cursor: pointer">
                                     <span class="mdl-chip__text">'.$nombre.'</span>
                                    <button onclick="quitarPariente(\''._simple_encrypt($par['index_serie']).'\')" class="mdl-chip__action"><i class="mdi mdi-close"></i></button>
                                </span>';
                }
            } else if ($tipo == 2){
                foreach ($array as $par) {
                	if($crear != null){
	                    $c++;
	                    $active = $c == $countArray ? 'active' : '';
                	}
                    $count++;
                    $nombre = ucwords(strtolower(utf8_decode($par['nombre'])))." ".ucwords(substr(utf8_decode($par['apellidopaterno']),0, 1)).".";
                    $opcion.= '<span class="mdl-chip mdl-chip--contact mdl-chip--deletable '.$active.' chip-postulantes m-b-15" onclick="verFormularioPostulante(\''._simple_encrypt($par['index_serie']).'\')" id="chip'.$par['index_serie'].'">
                                   <img class="mdl-chip__contact" alt="Estudiante" src="'.RUTA_SMILEDU.'uploads/images/foto_perfil/nouser.svg" style="cursor: pointer">
                                   <span class="mdl-chip__text">'.$nombre.'</span>
                                   <button class="mdl-chip" onclick="quitarPostulante(\''._simple_encrypt($par['index_serie']).'\')" id="chip'.$par['index_serie'].'"><i class="mdi mdi-close"></i></button>     
                               </span>';
                }
            }
        }
        
        $data['form'] = $opcion;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function guardarPariente(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try {
	        $obj          = _post('obj');
	        $editar         = $obj['editar'];
	        $apePaterno     = utf8_decode($obj['apellidopaterno']);
	        $apeMaterno     = utf8_decode($obj['apellidomaterno']);
	        $nombre         = utf8_decode($obj['nombre']);
	        $tipoDocumento  = ($obj['tipodocumento']);
	        $nroDocumento   = $obj['nrodocumento'];
	        $sexo           = _simpleDecryptInt($obj['sexo']);
	        $parentesco     = _simpleDecryptInt($obj['parentesco']);
	        $celular        = $obj['celular'];
	        $operador       = $obj['operador'];
	        $telFijo        = $obj['telfijo'];
	        $correo         = $obj['correo'];
	        $departamento   = ($obj['departamento'])==null ? null : _simpleDecryptInt($obj['departamento']);
	        $provincia      = ($obj['provincia'])==null ? null : _simpleDecryptInt($obj['provincia']);
	        $distrito       = ($obj['distrito'])==null ? null : _simpleDecryptInt($obj['distrito']);
	        $referencia     = utf8_decode($obj['referencia']);
	        $medioColegio   = ($obj['mediocolegio'])==null ? null : _simpleDecryptInt($obj['mediocolegio']);
	        $canal          = ($obj['canal'])==null ? null : _simpleDecryptInt($obj['canal']);
	        
	        $parientes      = _post('parientes');
	        $postulantes    = _post('postulantes');
	        
	        if(_getSesion('nid_persona') == null || _getSesion('cod_familiar') != null){
	            if($nombre==null || $apePaterno==null || $apeMaterno==null || $parentesco==null || $sexo==null
  	                || $tipoDocumento==null || $nroDocumento==null || $departamento==null || $provincia==null || 
  	                $distrito==null || $medioColegio==null || $referencia==null){
	                throw new Exception('Debe completar todos los datos solicitados del familiar');
	            }
	        } else if(_getSesion('nid_persona') != null && _getSesion('cod_familiar') == null){
	            if($parentesco==null){
	                throw new Exception('Debe elegir el parentesco del familiar');
	            }
	            if($apePaterno==null){
	                throw new Exception('Debe completar el apellido paterno del familiar');
	            }
	            if($nombre==null){
	                throw new Exception('Debe completar el nombre del familiar');
	            }
	            if($canal == null){
	                throw new Exception('Debe seleccionar un canal de comunicaci�n');
	            }
	            if($telFijo == null && $celular == null){
	                throw new Exception('Debe especificar un tel&eacute;fono fijo o celular');
	            }
	            
	        }
	        
	        if($tipoDocumento != null){
	            if(trim($nroDocumento) == null){
	                throw new Exception('Ingresar un n&uacute;mero de documento');
	            }
	        }
	        
	        if($nroDocumento != null){
	            $nroDocumento = trim($nroDocumento);
	            if($tipoDocumento == TIPO_DOC_DNI){
	                if(ctype_digit($nroDocumento)==false){
	                    throw new Exception('Solo ingresar n&uacute;meros en el dni');
	                }
	                if(strlen($nroDocumento)!=8){
	                    throw new Exception('Ingresar 8 digitos en el dni');
	                }
	                $countDNI = $this->m_utils->countByTipoDoc($nroDocumento, TIPO_DOC_DNI);
	                if($countDNI > 0){
	                    throw new Exception('El dni especificado ya est&aacute; registrado');
	                }
	                if($editar != 1){
	                    if($parientes!=null){
	                        foreach ($parientes as $par) {
	                            if($par['tipodocumento']==TIPO_DOC_DNI){
	                                if($nroDocumento == $par['nrodocumento']){
	                                    throw new Exception('Ya asignaste un familiar con igual DNI');
	                                }
	                            }
	                        }
	                    }
	                }
	            } else if($tipoDocumento == TIPO_DOC_CARNET_EXTRANJERO){
	                if(strlen($nroDocumento)!=12){
	                    throw new Exception('El carnet de extranjer&iacute;a debe tener 12 caracteres');
	                }
	                $countDocExtran = $this->m_utils->countByTipoDoc($nroDocumento, TIPO_DOC_CARNET_EXTRANJERO);
	                if($countDocExtran > 0){
	                    throw new Exception('El carnet de extranjer&iacute;a especificado ya est&aacute; registrado');
	                }
	                if($editar != 1){
	                    if($parientes!=null){
	                        foreach ($parientes as $par) {
	                            if($par['tipodocumento']==TIPO_DOC_CARNET_EXTRANJERO){
	                                if($nroDocumento == $par['nrodocumento']){
	                                    throw new Exception('Ya asignaste un familiar con igual carnet de extranjer&iacute;a');
	                                }
	                            }
	                        }
	                    }
	                }
	            }
	        }
    	        
// 	        if($celular != null){
// 	            $celular = trim($celular);
//     	            if(ctype_digit($celular)==false){
//     	                throw new Exception('Solo ingresar n&uacute;meros en el celular');
// 	            }
// 	        }
	        if($telFijo != null){
	            $telFijo = trim($telFijo);
	            if(ctype_digit($telFijo)==false){
	                throw new Exception('Solo ingresar n&uacute;meros en el telefono fijo');
	            }
	        }
	        if($correo != null){
	            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
	                throw new Exception("Ingrese un correo v&aacute;lido");
	            }
	        }
	        
	        $data['error']    = EXIT_SUCCESS;
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
    
    function retornarIndex(){
        $index = _post('index') != null ? _simpleDecryptInt(_post('index')) : null;
        $data['indice'] = $index;
        $data['rol']    = _getSesion(ADMISION_ROL_SESS);
        if (_getSesion('nid_persona') != null && _getSesion('cod_familiar') == null){
            $data['rol']    = 1;
        } else if(_getSesion('nid_persona') == null || _getSesion('cod_familiar') != null){
            $data['rol']    = ID_ROL_FAMILIA;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarPostulante(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
            $obj          = _post('obj');
            
            $editar             = $obj['editar'];
            $apePaterno         = utf8_decode($obj['apellidopaterno']);
            $apeMaterno         = utf8_decode($obj['apellidomaterno']);
            $nombre             = utf8_decode($obj['nombre']);
	        $tipoDocumento      = ($obj['tipodocumento']);
	        $nroDocumento       = $obj['nrodocumento'];
            $sexo               = $obj['sexo']==null ? null : _simpleDecryptInt($obj['sexo']);
            $colegioProcedencia = ($obj['colegioprocedencia'])==null ? null : _simpleDecryptInt($obj['colegioprocedencia']);
            $sedeInteres        = ($obj['sedeinteres'])==null ? null : _simpleDecryptInt($obj['sedeinteres']);
            $gradoNivel         = $obj['gradonivel']==null ? null : _simple_decrypt($obj['gradonivel']);
            $fechaNac           = $obj['fechanac']==null ? null : $obj['fechanac'];
            $observacion        = $obj['observacion']==null ? null : $obj['observacion'];
            $proceso            = $obj['proceso'];
            
            $parientes          = _post('parientes');
            $postulantes        = _post('postulantes');
            
            if($proceso == null){
            	throw new Exception('Debe elegir el(los) proceso(s) del postulante');
            }
            $arrayIds = array();
            foreach ($proceso as $var){
            	$id = null;
            	if($var != null){
            		$id = $this->encrypt->decode($var);
            		array_push($arrayIds, $id);
            	}
            }
//             if(in_array(ANIO_LECTIVO, $arrayIds) && count($arrayIds) > 1){
//             	throw new Exception('El a&ntilde;o lectivo no puede seleccionarse con otro proceso');
//             }
            
            if(_getSesion('nid_persona') == null || _getSesion('cod_familiar') != null){
                if($apePaterno==null || $apeMaterno==null || $nombre==null || $tipoDocumento==null || $nroDocumento==null || $sexo==null
                || $colegioProcedencia==null || $sedeInteres==null || $gradoNivel==null || $fechaNac==null || $proceso==null){
                    throw new Exception('Debe completar todos los datos solicitados del postulante');
                }
            } else if(_getSesion('nid_persona') != null && _getSesion('cod_familiar') == null) {
            	if($proceso==null){
            		throw new Exception('Debe elegir el(los) proceso(s) del postulante');
            	}
                if($apePaterno==null){
                    throw new Exception('Debe completar el apellido paterno del postulante');
                }
                if($nombre==null){
                    throw new Exception('Debe completar el nombre del postulante');
                }
                if($gradoNivel==null){
                    throw new Exception('Debe completar el grado-nivel');
                }
                if($sedeInteres==null){
                    throw new Exception('Debe completar la sede de inter&eacute;s');
                }
                if((in_array(CRONO_SPORT_SUMMER, $arrayIds) || in_array(CRONO_CREATIVE_SUMMER, $arrayIds)) && ($sedeInteres == NULL || $sedeInteres == 0)){
                	throw new Exception('Para verano debe seleccionar una sede');
                }
            }
            if(in_array(CRONO_SPORT_SUMMER, $arrayIds) && $sedeInteres != ID_SEDE_ECOLOGICA){
            	throw new Exception('"Sport summer" solo se puede elegir en la Sede Ecol&oacute;gica');
            }

            if($tipoDocumento != null){
                if(trim($nroDocumento) == null){
                    throw new Exception('Ingresar un n&uacute;mero de documento');
                }
            }
            
	        if($nroDocumento != null){
	            $nroDocumento = trim($nroDocumento);
	            if($tipoDocumento == TIPO_DOC_DNI){
	                if(ctype_digit($nroDocumento)==false){
	                    throw new Exception('Solo ingresar n&uacute;meros en el dni');
	                }
	                if(strlen($nroDocumento)!=8){
	                    throw new Exception('Ingresar 8 digitos en el dni');
	                }
	                $countDNI = $this->m_utils->countByTipoDoc($nroDocumento, TIPO_DOC_DNI);
	                if($countDNI > 0){
	                    throw new Exception('El dni especificado ya est&aacute; registrado');
	                }
	                if($editar != 1){
	                    if($postulantes!=null){
	                        foreach ($postulantes as $pos) {
	                            if($pos['tipodocumento']==TIPO_DOC_DNI){
	                                if($nroDocumento == $pos['nrodocumento']){
	                                    throw new Exception('Ya asignaste un postulante con igual DNI');
	                                }
	                            }
	                        }
	                    }
	                }
	            } else if($tipoDocumento == TIPO_DOC_CARNET_EXTRANJERO){
	                if(strlen($nroDocumento)!=12){
	                    throw new Exception('El carnet de extranjer&iacute;a solo puede tener m&aacute;ximo 12 caracteres');
	                }
	                $countDocExtran = $this->m_utils->countByTipoDoc($nroDocumento, TIPO_DOC_CARNET_EXTRANJERO);
	                if($countDocExtran > 0){
	                    throw new Exception('El carnet de extranjer&iacute;a especificado ya est&aacute; registrado');
	                }
	                if($editar != 1){
	                    if($parientes!=null){
	                        foreach ($parientes as $par) {
	                            if($par['tipodocumento']==TIPO_DOC_CARNET_EXTRANJERO){
	                                if($nroDocumento == $par['nrodocumento']){
	                                    throw new Exception('Ya asignaste un postulante con igual carnet de extranjer&iacute;a');
	                                }
	                            }
	                        }
	                    }
	                }
	            }
	        }
	        
	        if($fechaNac != null){
	            if(strlen($fechaNac)!=10){
	                throw new Exception('La fecha es incorrecta');
	            }
	            $fechaNac = explode('/', $fechaNac);
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
            
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function enviarFormulario(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
            $allInsert       = array();
            $dataInsert      = array();
            $correoParientes = array();
            $ubigeo          = null;
            $countLectivo    = 0;
            $postulantes     = _post('postulantes');
            $parientes       = _post('parientes');
            $estado          = null;
            if($parientes == null || $postulantes == null){
                throw new Exception(ANP);
            }
            $codGrupo = $this->m_formulario->getNextCodGrupo();
            foreach ($parientes as $par){
                if(($par['departamento'])!=null && ($par['provincia'])!=null && $par['distrito']!= null){
                    $ubigeo =_simpleDecryptInt($par['departamento'])._simpleDecryptInt($par['provincia'])._simpleDecryptInt($par['distrito']);
                }
                $obligatorio1 = CAMPOS_OBLIGATORIOS_COMPLETOS;
                if($par['nrodocumento'] == NULL || $par['correo'] == NULL || $par['celular'] == null){
                    $estado = ESTADO_CONTACTO_POR_CONTACTAR;
                    $obligatorio1 = CAMPOS_OBLIGATORIOS_INCOMPLETOS;
                }
                if(_getSesion(ADMISION_ROL_SESS) != ID_ROL_MARKETING && _getSesion(ADMISION_ROL_SESS) != ID_ROL_SECRETARIA){
                   $canal = CANAL_PAGINA_WEB;
                } else {
                    if($par['canal'] != null){
                        $canal = _simpleDecryptInt($par['canal']);
                    } else if($par['canal']==null){
                        $canal = null;
                    }
                }
                $dataInsert  = array("nombres"                 => __only1whitespace(utf8_decode(($par['nombre']))),
                                     "ape_paterno"             => __only1whitespace(utf8_decode(($par['apellidopaterno']))),
                                     "ape_materno"             => $par['apellidomaterno'] != null ? __only1whitespace(utf8_decode(($par['apellidomaterno']))) : NULL,
                                     "parentesco"              => ($par['parentesco'])!=null ? _simpleDecryptInt($par['parentesco']) : NULL,
                                     "sexo"                    => ($par['sexo'])!=null ? _simpleDecryptInt($par['sexo']) : NULL,
                                     "correo"                  => ($par['correo'])!=null ? ($par['correo']) : NULL,
                                     "tipo_documento"          => ($par['tipodocumento'])!=null ? ($par['tipodocumento']) : NULL,
                                     "nro_documento"           => ($par['nrodocumento'])!=null ? ($par['nrodocumento']) : NULL,
                                     "referencia_domicilio"    => ($par['referencia'])!=null ? __only1whitespace(utf8_decode(_ucfirst($par['referencia']))) : NULL,
                                     "telefono_celular"        => ($par['celular'])!=null ? ($par['celular']) : NULL,
                                     "telefono_fijo"           => ($par['telfijo'])!=null ? ($par['telfijo']) : NULL,
                                     "medio_colegio"           => ($par['mediocolegio'])!=null ? _simpleDecryptInt($par['mediocolegio']) : NULL,
                                     "cod_grupo"               => $codGrupo,
                                     "ubigeo"                  => ($ubigeo!=null) ? $ubigeo : NULL,
                                     "flg_apoderado"           => FLG_APODERADO,
                                     "flg_estudiante"          => FLG_FAMILIAR,
                                     "operador_telefonico"     => ($par['operador'])!=null ? _simpleDecryptInt($par['operador']) : NULL,
                                     "canal_comunicacion"      => $canal,
                                     "id_persona_registro"     => _getSesion('nid_persona') != null ? _getSesion('nid_persona') : NULL,
                                     "flg_campos_obligatorios" => $obligatorio1,
                                     "id_contacto_matricula"   => $par['id_familiar'] != null ? _simpleDecryptInt($par['id_familiar']) : NULL);
                if($par['correo'] != null){
                array_push($correoParientes, $dataInsert);
                }
                array_push($allInsert, $dataInsert);
            }
            foreach ($postulantes as $pos){
            	$arrayIds = array();
            	foreach ($pos['proceso'] as $var){
            		$id = null;
            		if($var != null){
            			$id = $this->encrypt->decode($var);
            			array_push($arrayIds, $id);
            		}
            	}
                if($pos['gradonivel'] != null){
                    $gradoNivel = explode('_', _simple_decrypt($pos['gradonivel']));
                }
                $obligatorio2 = CAMPOS_OBLIGATORIOS_COMPLETOS;
                if($pos['nrodocumento'] == NULL || $pos['nombre'] == "POSTULANTE"){
                    $obligatorio2 = CAMPOS_OBLIGATORIOS_INCOMPLETOS;
                    $estado = ESTADO_CONTACTO_POR_CONTACTAR;
                }
            	if(in_array(CRONO_SPORT_SUMMER, $arrayIds) ||  in_array(CRONO_CREATIVE_SUMMER, $arrayIds)){
            		$estado = ESTADO_CONTACTO_VERANO;
            	}

            	if(in_array(ANIO_LECTIVO, $arrayIds)){
            	    $countLectivo++;
            	}
                $dataInsert  = array("tipo_proceso"            =>  str_replace(']', '}', str_replace('[',  '{',json_encode($arrayIds))),
                					 "nombres"                 => __only1whitespace(utf8_decode(($pos['nombre']))),
                                     "ape_paterno"             => __only1whitespace(utf8_decode(($pos['apellidopaterno']))),
                                     "ape_materno"             => $pos['apellidomaterno'] != null ? __only1whitespace(utf8_decode(($pos['apellidomaterno']))) : NULL,
                                     "sexo"                    => ($pos['sexo'])!=null ? _simpleDecryptInt($pos['sexo']) : NULL,
                                     "tipo_documento"          => ($pos['tipodocumento'])!=null ? ($pos['tipodocumento']) : NULL,
                                     "nro_documento"           => ($pos['nrodocumento'])!=null ? ($pos['nrodocumento']) : NULL,
                                     "colegio_procedencia"     => ($pos['colegioprocedencia'])!=null ? _simpleDecryptInt($pos['colegioprocedencia']) : NULL,
                                     "sede_interes"            => ($pos['sedeinteres'])!=null ? _simpleDecryptInt($pos['sedeinteres']) : NULL,
                                     "grado_ingreso"           => ($pos['gradonivel'])!=null ? $gradoNivel[0] : NULL,
                                     "nivel_ingreso"           => ($pos['gradonivel'])!=null ? $gradoNivel[1] : NULL,
                                     "fecha_nacimiento"        => ($pos['fechanac'])!=null ? ($pos['fechanac']) : NULL,
                                     "cod_grupo"               => $codGrupo,
                                     "flg_estudiante"          => FLG_ESTUDIANTE,
                                     "estado"                  => ($estado != null) ? $estado : ESTADO_CONTACTO_CONTACTADO,
                                     "id_persona_registro"     => _getSesion('nid_persona') != null ? _getSesion('nid_persona') : NULL,
                                     "obser_solicitud"         => ($pos['observacion'])!=null ? _ucfirst(__only1whitespace(utf8_decode($pos['observacion']))) : NULL,
                                     "flg_campos_obligatorios" => $obligatorio2
                );
                array_push($allInsert, $dataInsert);
            }
            $data = $this->m_formulario->insertFamilia($allInsert);
            
            $this->session->set_userdata(array('array' => $data['arrayIds']));
            unset($data['arrayIds']);
            $data['$countLectivo'] = $countLectivo;
            /*if($data['error'] == EXIT_SUCCESS){
                foreach ($correoParientes as $cor){
                    $html  = '<p>Hola!, '.ucwords(strtolower($cor['nombres'])).' '.ucwords(strtolower($cor['ape_paterno'])).'</p>';
                    $html .= '<p>Has enviado tu formulario con exito</p>';
                    $html .= '<p>Puedes ir llenando el siguiente formulario para hacer tu proceso de admisi&oacute;n mas r&aacute;pido y f&aacute;cil</p>';
                    $html .= '<p><a href="http://181.224.241.203/schoowl/">Smil<strong>edu</strong></a></p>';
                    $html .= 'Para mayor informaci&oacute;n ingresar al sistema <a href="http://181.224.241.203/schoowl/">Smil<strong>edu</strong></a>, al modelo de ADMISI&Oacute;N';
                    $html .= '<p>Esperemos en contacto contigo!!</p>';
                    //ENVIAR CORREO AL ENCARGADO
                    $data = $this->lib_utils->enviarEmail($cor['correo'], "�La Merced te da la bienvenida!", $html);
                }
            }*/
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function inscribirAEvento(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
            if(_post('idevento') == null){
                throw new Exception();
            }
            $opcion = OPCION_ASISTIRA;
            if(_post('enc') == 1){
                $idevento = _simpleDecryptInt(_post('idevento'));
                $opcion   = _simpleDecryptInt(_post('opcion'));
                if($opcion == null){
                    $opcion = OPCION_ASISTIRA;
                }
            } else {
                $idevento = _post('idevento');
            }
            $familia = _getSesion('array');
            $allInscritos = array();
            
            $tipoevento = _post('tipoevento');
            if ($tipoevento != null){
                $fecha      = _post("fecha");
                $hora       = _post("hora");
                if($fecha == null || $hora == null){
                    throw new Exception("Ingrese la fecha y hora a la que solicita agendar");
                }
                foreach ($familia as $fam){
                    $arrayInsert = array("id_contacto" => $fam,
                                         "fecha"       => $fecha.' '.$hora,
                                         "id_evento"   => $idevento,
                                         "estado"      => EVALUACION_A_EVALUAR);
                    array_push($allInscritos, $arrayInsert);
                }
                $data = $this->m_formulario->agendarFamEvento($allInscritos);
            } else {
                foreach ($familia as $fam){
                    $dataInsert  = array("id_evento"              => $idevento,
                                         "id_contacto"            => $fam,
                                         "opcion"                 => $opcion,
                                         "asistencia"             => INASISTENCIA_CONTACTO,
                                         "flg_asistencia_directa" => ASISTENCIA_INVITACION_CONTACTO);
                    array_push($allInscritos, $dataInsert);
                }
                $data = $this->m_formulario->inscribirFamEvento($allInscritos);
            }
            if(_post('check') == 1){
                $data['url'] = $this->googleplus->loginUrl();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function registrarColegio(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $colegio = utf8_decode(_post("colegio"));
            if($colegio == null || strlen(trim($colegio)) == 0){
                throw new Exception(ANP);
            }
            
            $existe = $this->m_formulario->validateColegioRepetido($colegio);
            if($existe > 0){
                throw new Exception("El colegio ingresado ya existe");
            }

            $arrayInsert = array("desc_colegio" => $colegio);
    
            $data = $this->m_formulario->insertColegio($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $data['comboColegios'] = __buildComboColegios();
                $data['colegio'] = _simple_encrypt($data['id']);
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

    function changeMaxlength(){
        $tipo = strlen(_post('tipo')) != 0 ? _simpleDecryptInt(_post('tipo')) : null;
        $data['tipo'] = $tipo;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDatosEvento(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
            $idevento = (_post('idevento'))==null ? null : _simple_decrypt(_post('idevento'));
            if($idevento == null){
                throw new Exception(ANP);
            }
            $evento = $this->m_formulario->getDescripcionByEvento($idevento);
            $data['titulo']         = $evento['desc_evento'];
            $data['fecha']          = $evento['fecha_realizar'] != NULL ? date('d/m/Y', strtotime($evento['fecha_realizar'])) : NULL;
            $data['hora']           = $evento['hora_inicio'] != NULL ? date('g:i a', strtotime($evento['hora_inicio'])) : NULL;
            $data['idevento']       = $evento['id_evento'];
            $data['observacion']    = $evento['observacion'];
            $data['verano']         = $evento['tipo_evento'] != TIPO_EVENTO_EVALUACION_VERANO ? 1 : 0;
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buscarFamilias(){
        $nombre = _post("nombre");
        $codFam = $this->m_utils->getById("sima.detalle_alumno", "cod_familia", "nid_persona", _getSesion('idAlumnoEdit'));
        
        $familias = $this->m_alumno->buscarFamilia(utf8_decode($nombre), $codFam);
        $data['tablaParientes'] = _createTableParientesBusqueda($familias);
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function verFamiliaresCodFamiliar(){
        $codFam = _simple_decrypt(_post("codFamiliar"));
        $familiares = $this->m_alumno->getFamiliaByCodFamAbrev($codFam);
        
        $data['tablaFamiliares'] = _createTableFamiliaresByCodFam($familiares);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function agregarParientesMatricula(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try {
        	$codFam = _post('codigofam') != null ? _simple_decrypt(_post('codigofam')) : null;
        	$parientes = $this->m_formulario->getParientesFromMatricula($codFam);
        	$index_serie = 0;
        	$arrayParientes = array();
        	foreach ($parientes as $par){
        		$par = get_object_vars($par);
        		$par['id_familiar'] = $par['id_familiar']   != null ? _simple_encrypt($par['id_familiar'])   : null;
				$par['parentesco']  = $par['parentesco'] != null ? _simple_encrypt($par['parentesco']) : null;
				$par['sexo']        = $par['sexo']          != null ? _simple_encrypt($par['sexo'])          : null;
				$depar      = (strlen(substr($par['ubigeo_hogar'], 0, 2)) != 0 && $par['ubigeo_hogar'] != 0) ? _simple_encrypt(substr($par['ubigeo_hogar'], 0, 2)) : null;
				$provi      = (strlen(substr($par['ubigeo_hogar'], 2, 2)) != 0 && $par['ubigeo_hogar'] != 0) ? _simple_encrypt(substr($par['ubigeo_hogar'], 2, 2)) : null;
				$distr      = (strlen(substr($par['ubigeo_hogar'], 4, 2)) != 0 && $par['ubigeo_hogar'] != 0) ? _simple_encrypt(substr($par['ubigeo_hogar'], 4, 2)) : null;
// 				unset($par['id_familiar']);
// 				unset($par['parentesco']);
// 				unset($par['sexo']);
				unset($par['ubigeo_hogar']);
				
				$par['apellidopaterno'] = utf8_encode($par['apellidopaterno']);
				$par['apellidomaterno'] = utf8_encode($par['apellidomaterno']);
				$par['nombre'] = utf8_encode($par['nombre']);
				$par['correo'] = utf8_encode($par['correo']);
				$par['referencia'] = utf8_encode($par['referencia']);
				
        		// ENCRIPTAR : TIPODOC, SEXO, UBIGEO (3 CAMPOS), ID FAMILIAR
        		$par +=array('editar'        => 0,
        				     'index_serie'   => $index_serie,
//         		             'parentesco'    => $parentesco,
        				     'departamento'  => $depar,
        				     'provincia'     => $provi,
        				     'distrito'      => $distr,
        				     'mediocolegio'  => null,
        				     'operador'      => null,
        				     'canal'         => null,
//         					 'id_familiar'   => $idfamiliar,
//         					 'sexo'          => $sexo
        		    
        		);
        		$index_serie++;
        		array_push($arrayParientes, $par);
        	}
        	$data['parientes'] = json_encode($arrayParientes);
        	$data['error']     = EXIT_SUCCESS;
        } catch (Exception $e){
        	$data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}