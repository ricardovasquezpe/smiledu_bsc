<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_confirmar_datos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_evento/m_detalle_evento');
        $this->load->model('mf_evento/m_evento');
        $this->load->model('mf_confirm_datos/m_confirm_datos');
    }
    
    public function index() {
        $data['comboParentesco']    = __buildComboByGrupo(COMBO_PARENTEZCO);
        $data['comboSexo']          = __buildComboByGrupo(COMBO_SEXO);
        $data['comboTipoDocumento'] = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC);
        $data['comboDepartamento']  = __buildComboUbigeoByTipo(null, null, 1);
	    $data['comboGradoNivel']    = __buildComboGradoNivel();
	    $data['generico'] = 0;
        if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_USUARIO_GENERICO){
            $data['generico'] = 1;
            $arraySession = array("grupoConfirmDatos" => null);
            $this->session->set_userdata($arraySession);
        }else{
            $familiares = $this->m_confirm_datos->getNombrePadres(_getSesion("grupoConfirmDatos"));
            $htmlCabe = $this->getCabeceraFamiliares($familiares);
            $data['cabeFam'] = $htmlCabe['html'];
            if($htmlCabe['idFam'] != null){
                $datosFam = $this->m_confirm_datos->getDatosFamiliar($htmlCabe['idFam']);
                $data['contacto']        = _simple_encrypt($htmlCabe['idFam']);
                $data['parentesco']      = ($datosFam['ape_paterno']!=null)?_simple_encrypt($datosFam['parentesco']):null;
                $data['apePaterno']      = ($datosFam['ape_paterno']);
                $data['apeMaterno']      = ($datosFam['ape_materno']);
                $data['nombres']         = ($datosFam['nombres']);
                $data['tipoDoc']         = $datosFam['tipo_documento'];
                $data['nroDoc']          = $datosFam['nro_documento'];
                $data['sexo']            = ($datosFam['sexo']!=null)?_simple_encrypt($datosFam['sexo']):null;
                $data['telefonoFijo']    = $datosFam['telefono_fijo'];
                $data['telefonoCelular'] = $datosFam['telefono_celular'];
                $data['correo']          = $datosFam['correo'];
                $data['departamento']    = (strlen(substr($datosFam['ubigeo'], 0, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 0, 2)) : null;
                $data['provincia']       = (strlen(substr($datosFam['ubigeo'], 2, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 2, 2)) : null;
                $data['distrito']        = (strlen(substr($datosFam['ubigeo'], 4, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 4, 2)) : null;
                $data['referencia']      = ($datosFam['referencia_domicilio']);
            }
        }
        $this->load->view('v_confirmar_datos', $data);
    }
    
    public function getCabeceraFamiliares($familiares){
        $html = null;
        $idFam = null;
        $i = 0;
        foreach ($familiares as $row){
            $active = null;
            if($i == 0){
                $idFam = $row->id_contacto;
                $active = 'active';
            }
            $html .= '<span class="mdl-chip mdl-chip--contact mdl-chip--deletable  '.$active.' chip-parientes" style="cursor:pointer" onclick="changeFamiliarDatos(\''._simple_encrypt($row->id_contacto).'\', this)">
                           <img class="mdl-chip__contact" src="'.RUTA_IMG_PROFILE.'nouser.svg"></img>
                           <span class="mdl-chip__text">'.$row->nombreabrev.'</span>
                       </span>';
            $i++;
        }
        $data['html']  = $html;
        $data['idFam'] = $idFam;
        return $data;
    }
    
    public function getDatosHijos(){
        $hijos = $this->m_confirm_datos->getNombrePostulantes(_getSesion("grupoConfirmDatos"));
        $htmlCabe = $this->getCabeceraHijos($hijos);
        $data['cabeHij'] = $htmlCabe['html'];
        if($htmlCabe['idHij'] != null){
            $data['contacto'] = _simple_encrypt($htmlCabe['idHij']);
            $datosPost = $this->m_confirm_datos->getDatosPostulante($htmlCabe['idHij']);
            $data['apePaterno'] = $datosPost['ape_paterno'];
            $data['apeMaterno'] = $datosPost['ape_materno'];
            $data['nombres']    = $datosPost['nombres'];
            $data['fecNaci']    = ($datosPost['fecha_nacimiento']!=null)?_fecha_tabla($datosPost['fecha_nacimiento'], 'd/m/Y'):null;
            $data['sexo']       = ($datosPost['sexo']!=null)?_simple_encrypt($datosPost['sexo']):null;
            $data['tipoDoc']    = $datosPost['tipo_documento'];
            $data['nroDoc']     = $datosPost['nro_documento'];
            $data['gradoNivel'] = ($datosPost['grado_ingreso']!= null)?_simple_encrypt($datosPost['grado_ingreso'].'_'.$datosPost['nivel_ingreso']):null;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getCabeceraHijos($hijos, $opc = null){
        $html = null;
        $idHij = null;
        $i = 0;
        foreach ($hijos as $row){
            $onclick = 'changeHijosDatos(\''._simple_encrypt($row->id_contacto).'\', this)';
            $class = 'chip-hijos';
            if($opc != null){
                $onclick = 'changeHijosDatosPsico(\''._simple_encrypt($row->id_contacto).'\', this)';
                $class = 'chip-hijos-psico';
            }
            $active = null;
            if($i == 0){
                $idHij = $row->id_contacto;
                $active = 'active';
            }
            $html .= '<span class="mdl-chip mdl-chip--contact mdl-chip--deletable  '.$active.' '.$class.' " style="cursor:pointer" onclick="'.$onclick.'">
                           <img class="mdl-chip__contact" src="'.RUTA_IMG_PROFILE.'nouser.svg"></img>
                           <span class="mdl-chip__text">'.$row->nombreabrev.'</span>
                       </span>';
            $i++;
        }
        $data['html']  = $html;
        $data['idHij'] = $idHij;
        return $data;
    }
    
    function getDatosFamiliar(){
        $idContacto = _simpleDecryptInt(_post("contacto"));
        $datosFam = $this->m_confirm_datos->getDatosFamiliar($idContacto);
        $data['contacto']        = _simple_encrypt($idContacto);
        $data['parentesco']      = ($datosFam['ape_paterno']!=null)?_simple_encrypt($datosFam['parentesco']):null;
        $data['apePaterno']      = ($datosFam['ape_paterno']);
        $data['apeMaterno']      = ($datosFam['ape_materno']);
        $data['nombres']         = ($datosFam['nombres']);
        $data['tipoDoc']         = $datosFam['tipo_documento'];
        $data['nroDoc']          = $datosFam['nro_documento'];
        $data['fecNaci']         = ($datosFam['fecha_nacimiento']!=null)?_fecha_tabla($datosFam['fecha_nacimiento'], 'd/m/Y'):null;
        $data['sexo']            = ($datosFam['sexo']!=null)?_simple_encrypt($datosFam['sexo']):null;
        $data['telefonoFijo']    = $datosFam['telefono_fijo'];
        $data['telefonoCelular'] = $datosFam['telefono_celular'];
        $data['correo']          = $datosFam['correo'];
        $data['departamento']    = (strlen(substr($datosFam['ubigeo'], 0, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 0, 2)) : null;
        $data['provincia']       = (strlen(substr($datosFam['ubigeo'], 2, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 2, 2)) : null;
        $data['distrito']        = (strlen(substr($datosFam['ubigeo'], 4, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 4, 2)) : null;
        $data['referencia']      = ($datosFam['referencia_domicilio']);
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDatosPostulante(){
        $idContacto = _simpleDecryptInt(_post("contacto"));
        $datosFam = $this->m_confirm_datos->getDatosPostulante($idContacto);
        $data['contacto']   = _simple_encrypt($idContacto);
        $data['apePaterno'] = ($datosFam['ape_paterno']);
        $data['apeMaterno'] = ($datosFam['ape_materno']);
        $data['nombres']    = ($datosFam['nombres']);
        $data['fecNaci']    = ($datosFam['fecha_nacimiento']!=null)?_fecha_tabla($datosFam['fecha_nacimiento'], 'd/m/Y'):null;
        $data['sexo']       = ($datosFam['sexo']!=null)?_simple_encrypt($datosFam['sexo']):null;
        $data['tipoDoc']    = $datosFam['tipo_documento'];
        $data['nroDoc']     = $datosFam['nro_documento'];
        $data['gradoNivel'] = ($datosFam['grado_ingreso']!= null)?_simple_encrypt($datosFam['grado_ingreso'].'_'.$datosFam['nivel_ingreso']):null;
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarDatosPariente(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idContacto    = _simpleDecryptInt(_post('contacto'));
            if($idContacto == null){
                throw new Exception(ANP);
            }
            $parentesco = _simpleDecryptInt(_post("parentesco"));
            $apePaterno = _post("apellidopaterno");
            $apeMaterno = _post("apellidomaterno");
            $nombres    = _post("nombres");
            $tipoDoc      = _post("tipodoc");
            $nroDoc       = _post("nrodoc");
            $sexo         = _simpleDecryptInt(_post("sexo"));
            $telfFijo     = _post("telffijo");
            $telfCel      = _post("telfcel");
            $correo       = _post("correo");
            $departamento = _simpleDecryptInt(_post("depart"));
            $provincia    = _simpleDecryptInt(_post("provincia"));
            $distrito     = _simpleDecryptInt(_post("distrito"));
            $referencia   = _post("referencia");
            $fecNaci      = _post("fecnaci");
            
            if(strlen($parentesco) == 0 || strlen($apePaterno) == 0 || strlen($apeMaterno) == 0 || strlen($nombres) == 0
               || strlen($tipoDoc) == 0 || strlen($nroDoc) == 0 || strlen($sexo) == 0 || strlen($departamento.$provincia.$distrito) != 6
               || strlen($fecNaci) == 0 || strlen($referencia) == 0){
                throw new Exception("Ingrese los campos m&iacute;nimos (*)");
            }
            
            if(($tipoDoc == TIPO_DOC_DNI && strlen($nroDoc) != 8) || ($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && strlen($nroDoc) != 12)){
                throw new Exception("Ingrese un n&uacute;mero de documento v&aacute;lido");
            }
            if((strlen($departamento.$provincia.$distrito) > 0) && (strlen($departamento.$provincia.$distrito) < 6)){
                throw new Exception("Termine de ingresar su ubicaci&oacute;n");
            }
            
            $arrayUpdate = array("nombres"              => _ucwords(utf8_decode(__only1whitespace($nombres))),
                                 "ape_paterno"          => _ucwords(utf8_decode(__only1whitespace($apePaterno))),
                                 "ape_materno"          => _ucwords(utf8_decode(__only1whitespace($apeMaterno))),
                                 "parentesco"           => $parentesco,
                                 "tipo_documento"       => intval($tipoDoc),
                                 "nro_documento"        => $nroDoc,
                                 "sexo"                 => $sexo,
                                 "telefono_fijo"        => $telfFijo,
                                 "telefono_celular"     => $telfCel,
                                 "correo"               => $correo,
                                 "ubigeo"               => ($departamento.$provincia.$distrito),
                                 "referencia_domicilio" => _ucfirst(utf8_decode(__only1whitespace($referencia))),
                                 "fecha_nacimiento"     => $fecNaci != null ? $fecNaci : NULL
            );
            $data = $this->m_confirm_datos->updateContacto($arrayUpdate, $idContacto);
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarDatosHijos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $idContacto    = _simpleDecryptInt(_post('contacto'));
            if($idContacto == null){
                throw new Exception(ANP);
            }
            $apePaterno = _post("apellidopaterno");
            $apeMaterno = _post("apellidomaterno");
            $nombres    = _post("nombres");
	        $gradoNivel = _simple_decrypt(_post("gradnive"));
	        $tipoDoc      = _post("tipodoc");
	        $nroDoc       = _post("numdoc");
	        $fecNaci       = (strlen(_post("fecnaci"))!=0)?_post("fecnaci"):null;
	        $sexo         = _simpleDecryptInt(_post("sexo"));
            if(strlen($apePaterno) == 0 || strlen($apeMaterno) == 0 || strlen($nombres) == 0 || strlen($gradoNivel) == 0
              || strlen($tipoDoc) == 0 || strlen($nroDoc) == 0 || strlen($fecNaci) == 0 || strlen($sexo) == 0){
                throw new Exception("Ingrese los campos m&iacute;nimos (*)");
            }
	        $gradoNivel = explode("_", $gradoNivel);
            if(($tipoDoc == TIPO_DOC_DNI && strlen($nroDoc) != 8) || ($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && strlen($nroDoc) != 12)){
                throw new Exception("Ingrese un n&uacute;mero de documento v&aacute;lido");
            }
            
            $arrayUpdate = array("nombres"          => _ucwords(__only1whitespace(utf8_decode($nombres))),
                                 "ape_paterno"      => _ucwords(__only1whitespace(utf8_decode($apePaterno))),
                                 "ape_materno"      => _ucwords(__only1whitespace(utf8_decode($apeMaterno))),
                                 "tipo_documento"   => intval($tipoDoc),
                                 "nro_documento"    => $nroDoc,
                                 "sexo"             => $sexo,
                                 "fecha_nacimiento" => $fecNaci,
                                 "grado_ingreso"    => $gradoNivel[0],
                                 "nivel_ingreso"    => $gradoNivel[1]);
            $data = $this->m_confirm_datos->updateContacto($arrayUpdate, $idContacto);
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buscarFamilia(){
        $busqueda = _post("busqueda");
        $contactos = $this->m_confirm_datos->buscarFamilia($busqueda);
        $data['combo'] = $this->buildSelectFamilia($contactos);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildSelectFamilia($data){
        $opcion = null;
        foreach ($data as $row){
            $codGrupo = _simple_encrypt($row->cod_grupo);
            $opcion .= '<option value="'.$codGrupo.'">'.$row->nombrecompleto.'</option>';
        }
        return $opcion;
    }
    
    function traeInfoFamilia(){
        $codgrupo = _simpleDecryptInt(_post("familia"));
        $arraySession = array("grupoConfirmDatos" => $codgrupo);
        $this->session->set_userdata($arraySession);
        
        $familiares = $this->m_confirm_datos->getNombrePadres(_getSesion("grupoConfirmDatos"));
        $htmlCabe = $this->getCabeceraFamiliares($familiares);
        $data['cabeFam'] = $htmlCabe['html'];
        if($htmlCabe['idFam'] != null){
            $datosFam = $this->m_confirm_datos->getDatosFamiliar($htmlCabe['idFam']);
            $data['contacto']        = _simple_encrypt($htmlCabe['idFam']);
            $data['parentesco']      = ($datosFam['ape_paterno']!=null)?_simple_encrypt($datosFam['parentesco']):null;
            $data['apePaterno']      = ($datosFam['ape_paterno']);
            $data['apeMaterno']      = ($datosFam['ape_materno']);
            $data['nombres']         = ($datosFam['nombres']);
            $data['tipoDoc']         = $datosFam['tipo_documento'];
            $data['nroDoc']          = $datosFam['nro_documento'];
            $data['fecNaci']         = ($datosFam['fecha_nacimiento']!=null)?_fecha_tabla($datosFam['fecha_nacimiento'], 'd/m/Y'):null;
            $data['sexo']            = ($datosFam['sexo']!=null)?_simple_encrypt($datosFam['sexo']):null;
            $data['telefonoFijo']    = $datosFam['telefono_fijo'];
            $data['telefonoCelular'] = $datosFam['telefono_celular'];
            $data['correo']          = $datosFam['correo'];
            $data['departamento']    = (strlen(substr($datosFam['ubigeo'], 0, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 0, 2)) : null;
            $data['provincia']       = (strlen(substr($datosFam['ubigeo'], 2, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 2, 2)) : null;
            $data['distrito']        = (strlen(substr($datosFam['ubigeo'], 4, 2)) != 0) ? _simple_encrypt(substr($datosFam['ubigeo'], 4, 2)) : null;
            $data['referencia']      = ($datosFam['referencia_domicilio']);
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //FICHA PSICOLOGICA
    function getDatosHijosPsicologia(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $html = null;
        try{
            $hijos = $this->m_confirm_datos->getNombrePostulantes(_getSesion("grupoConfirmDatos"));
            $htmlCabe = $this->getCabeceraHijos($hijos, 1);
            $data['cabeHij'] = $htmlCabe['html'];
            if($htmlCabe['idHij'] != null){
                $data['contacto'] = _simple_encrypt($htmlCabe['idHij']);
                $nivel = $this->m_utils->getById("admision.contacto", "nivel_ingreso", "id_contacto", $htmlCabe['idHij']);
                if($nivel == null){
                    throw new Exception("El contacto no ha configurado su nivel");
                }
                $preguntas = $this->m_confirm_datos->getPreguntasByNivel($nivel, $htmlCabe['idHij']);
                $html = null;
                $i = 1;
                $categoria = null;
                foreach ($preguntas as $row){
                    $cont_cate = null;
                    if($i == 1){
                        $categoria = $row->categoria;
                        $cont_cate = '<p class="text-left" style="width: 100%;border-bottom: 1px solid #b1b1b1;">'.$categoria.'</p>';
                    }
                    if($categoria != $row->categoria){
                        $categoria = $row->categoria;
                        $cont_cate = '<p class="text-left" style="width: 100%;border-bottom: 1px solid #b1b1b1;">'.$categoria.'</p>';
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
                    $html .= '<div class="col-sm-12 mdl-input-group text-left divPapa" attr-name="'.$name.'" attr-tipo="'.$tipo.'" attr-pregunta="'._simple_encrypt($row->id_pregunta).'">';
                        $html .= '<p class="'.(($row->respuesta==null)?'is-invalid-psico':null).'">'.$i.') '.$row->descripcion.(($row->flg_obligatorio == 1)?"(*)":"").'</p>';
                        $html .= $this->createContalternativas($row->tipo_pregunta, $row->alternativas, $i, $row->id_pregunta, $row->respuesta);
                    $html .= '</div>';
                    $i++;
                }
                $data['ficha'] = $html;
                $data['error'] = EXIT_SUCCESS;
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function fichaPsicologicaContacto(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $html = null;
        try{
            $idContacto = _simpleDecryptInt(_post("contacto"));
            $nivel = $this->m_utils->getById("admision.contacto", "nivel_ingreso", "id_contacto", $idContacto);
            if($nivel == null){
                throw new Exception("El contacto no ha configurado su nivel");
            }
            $preguntas = $this->m_confirm_datos->getPreguntasByNivel($nivel, $idContacto);
            $i = 1;
            $categoria = null;
            foreach ($preguntas as $row){
                $cont_cate = null;
                if($i == 1){
                    $categoria = $row->categoria;
                    $cont_cate = '<p class="text-left" style="width: 100%;border-bottom: 1px solid #b1b1b1;">'.$categoria.'</p>';
                }
                if($categoria != $row->categoria){
                    $categoria = $row->categoria;
                    $cont_cate = '<p class="text-left" style="width: 100%;border-bottom: 1px solid #b1b1b1;">'.$categoria.'</p>';
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
                $html .= '<div class="col-sm-12 mdl-input-group text-left divPapa " attr-name="'.$name.'" attr-tipo="'.$tipo.'" attr-pregunta="'._simple_encrypt($row->id_pregunta).'">';
                    $html .= '<p class="'.(($row->respuesta==null)?'is-invalid-psico':null).'">'.$i.') '.$row->descripcion.(($row->flg_obligatorio == 1)?"(*)":"").'</p>';
                    $html .= $this->createContalternativas($row->tipo_pregunta, $row->alternativas, $i, $row->id_pregunta, $row->respuesta);
                $html .= '</div>';
                $i++;
            }
            $data['ficha'] = $html;
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function createContalternativas($tipo, $alternativas, $i, $idPregunta, $respuesta){
        $html = null;
        $alternativas = json_decode($alternativas);
        if(count($alternativas) != 0){
            $base = null;
            if($tipo == PREGUNTA_1OPCION){
                $base = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="_ID_ELEMENT_">
                             <input type="radio" id="_ID_ELEMENT_" class="mdl-radio__button" name="_NAME_" value="_VALUE_"  _CHECKED_ onchange="cambioCampoPostulantePsico(this)">
                             <span class="mdl-radio__label">_DESC_ALTERN_</span>
                         </label>';
            }else if($tipo == PREGUNTA_NOPCION){
                $base = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="_ID_ELEMENT_">
                             <input type="checkbox" id="_ID_ELEMENT_" name="_NAME_[]" class="mdl-checkbox__input" value="_VALUE_"  _CHECKED_ onchange="cambioCampoPostulantePsico(this)">
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
                             <input type="radio" id="opcion_'.$i.'_1" class="mdl-radio__button" name="opcion_name_'.$i.'" value="'._simple_encrypt("SI").'"  '.(($respuesta == "SI")?"checked":null).' onchange="cambioCampoPostulantePsico(this)">
                             <span class="mdl-radio__label">S&Iacute;</span>
                         </label>
                         <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="opcion_'.$i.'_2">
                             <input type="radio" id="opcion_'.$i.'_2" class="mdl-radio__button" name="opcion_name_'.$i.'" value="'._simple_encrypt("NO").'"  '.(($respuesta == "NO")?"checked":null).' onchange="cambioCampoPostulantePsico(this)">
                             <span class="mdl-radio__label">NO</span>
                         </label>';
            }else if($tipo == PREGUNTA_LIBRE){
                $html = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="opcion_'.$i.'_1" maxlength="100" attr-pregunta="'._simple_encrypt($idPregunta).'"  value="'.$respuesta.'" onchange="cambioCampoPostulantePsico(this)">        
                            <label class="mdl-textfield__label" for="opcion_'.$i.'_1"></label>                      
                         </div>';
            }
        }
        return $html;
    }
    
    function guardarFichaPsicoContacto(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $html = null;
        try{
            $idContacto = _simpleDecryptInt(_post("contacto"));
            if($idContacto == null){
                throw new Exception(ANP);
            }
            $myPostData = json_decode(_post('respuestas'), TRUE);
            if(count($myPostData['respuesta']) == 0){
                throw new Exception("Seleccione una respuesta");
            }
            $json = '[';

            foreach($myPostData['respuesta'] as $key => $respuesta){
                $jsonAux = null;
                if($respuesta['tipo'] == 1){//RADIOBUTTON
                    $valor = _simple_decrypt($respuesta['valor']);
                    $jsonAux .= '{"valor":"'.$valor.'",';
                }else if($respuesta['tipo'] == 2){//CHECKBOX
                    $jsonAux .= '{"valor" : [';
                    foreach($respuesta['valor'] as $resp){
                        $jsonAux .= '"'._simple_decrypt($resp).'",';
                    }
                    $jsonAux = rtrim($jsonAux, ",");
                    $jsonAux .= '],';
                }else{//INPUTTEXT
                    $valor = $respuesta['valor'];
                    $jsonAux .= '{"valor":"'.ucfirst(rtrim(ltrim(__only1whitespace($this->limpiar(utf8_decode($valor)))))).'",';
                }
                $jsonAux .= '"pregunta":"'._simpleDecryptInt($respuesta['pregunta']).'"},';
                $json .= $jsonAux;                
            }
            $json = rtrim($json, ",");
            $json .= ']';
            $arrayUpdate = array("resp_ficha" => $json);
            
            $data = $this->m_confirm_datos->updateContacto($arrayUpdate, $idContacto);
        } catch(Exception $e){
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
    
    function crearPariente(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $html = null;
        try{
            $idContacto = _simpleDecryptInt(_post("contacto"));
            if($idContacto == null){
                throw new Exception(ANP);
            }
            $codGrupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContacto);
            $parentesco = _simpleDecryptInt(_post("parentesco"));
            $apePaterno = _post("apellidopaterno");
            $apeMaterno = _post("apellidomaterno");
            $nombres    = _post("nombres");
            $tipoDoc      = _post("tipodoc");
            $nroDoc       = _post("nrodoc");
            $sexo         = _simpleDecryptInt(_post("sexo"));
            $telfFijo     = _post("telffijo");
            $telfCel      = _post("telfcel");
            $correo       = _post("correo");
            $departamento = _simpleDecryptInt(_post("depart"));
            $provincia    = _simpleDecryptInt(_post("provincia"));
            $distrito     = _simpleDecryptInt(_post("distrito"));
            $referencia   = _post("referencia");
            $fecNaci      = _post("fecnaci");
            
            if(strlen($parentesco) == 0 || strlen($apePaterno) == 0 || strlen($apeMaterno) == 0 || strlen($nombres) == 0
               || strlen($tipoDoc) == 0 || strlen($nroDoc) == 0 || strlen($sexo) == 0 || strlen($departamento.$provincia.$distrito) != 6
               || strlen($fecNaci) == 0 || strlen($referencia) == 0){
                throw new Exception("Ingrese los campos m&iacute;nimos (*)");
            }
            if(($tipoDoc == TIPO_DOC_DNI && strlen($nroDoc) != 8) || ($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && strlen($nroDoc) != 12)){
                throw new Exception("Ingrese un n&uacute;mero de documento v&aacute;lido");
            }
            if((strlen($departamento.$provincia.$distrito) > 0) && (strlen($departamento.$provincia.$distrito) < 6)){
                throw new Exception("Termine de ingresar su ubicaci&oacute;n");
            }
            
            $arrayInsert = array("nombres"              => _ucwords(utf8_decode(__only1whitespace($nombres))),
                                 "ape_paterno"          => _ucwords(utf8_decode(__only1whitespace($apePaterno))),
                                 "ape_materno"          => _ucwords(utf8_decode(__only1whitespace($apeMaterno))),
                                 "parentesco"           => $parentesco,
                                 "tipo_documento"       => ($tipoDoc!=null)?intval($tipoDoc):null,
                                 "nro_documento"        => $nroDoc,
                                 "sexo"                 => $sexo,
                                 "telefono_fijo"        => $telfFijo,
                                 "telefono_celular"     => $telfCel,
                                 "correo"               => $correo,
                                 "ubigeo"               => ($departamento.$provincia.$distrito),
                                 "referencia_domicilio" => _ucfirst(utf8_decode(__only1whitespace($referencia))),
                                 "fecha_nacimiento"     => $fecNaci != null ? $fecNaci : NULL,
                                 "cod_grupo"            => $codGrupo,
                                 "flg_estudiante"       => FLG_FAMILIAR
            );
            $data = $this->m_confirm_datos->insertarContacto($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $html .= '<span class="mdl-chip mdl-chip--contact mdl-chip--deletable active chip-parientes" style="cursor:pointer" onclick="changeFamiliarDatos(\''._simple_encrypt($data['idContacto']).'\', this)">
                               <img class="mdl-chip__contact" src="'.RUTA_IMG_PROFILE.'nouser.svg"></img>
                               <span class="mdl-chip__text">'.$apePaterno.', '.$nombres.'</span>
                           </span>';
                $data['chip'] = $html;
                $data['idPariente'] = _simple_encrypt($data['idContacto']);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function crearPostulante(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $html = null;
        try{
            $idContacto = _simpleDecryptInt(_post("contacto"));
            if($idContacto == null){
                throw new Exception(ANP);
            }
            $apePaterno = _post("apellidopaterno");
            $apeMaterno = _post("apellidomaterno");
            $nombres    = _post("nombres");
	        $gradoNivel = (_post("gradnive")!=null)?_simple_decrypt(_post("gradnive")):null;
	        $tipoDoc      = _post("tipodoc");
	        $nroDoc       = _post("numdoc");
	        $fecNaci       = (strlen(_post("fecnaci"))!=0)?_post("fecnaci"):null;
	        $sexo         = _simpleDecryptInt(_post("sexo"));
            if(strlen($apePaterno) == 0 || strlen($apeMaterno) == 0 || strlen($nombres) == 0 || strlen($gradoNivel) == 0
              || strlen($tipoDoc) == 0 || strlen($nroDoc) == 0 || strlen($fecNaci) == 0 || strlen($sexo) == 0){
                throw new Exception("Ingrese los campos m&iacute;nimos (*)");
            }
            
	        $gradoNivel = explode("_", $gradoNivel);
            if(($tipoDoc == TIPO_DOC_DNI && strlen($nroDoc) != 8) || ($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO && strlen($nroDoc) != 12)){
                throw new Exception("Ingrese un n&uacute;mero de documento v&aacute;lido");
            }
            $codGrupo = $this->m_utils->getById("admision.contacto", "cod_grupo", "id_contacto", $idContacto);
            $arrayInsert = array("nombres"          => _ucwords(__only1whitespace(utf8_decode($nombres))),
                                 "ape_paterno"      => _ucwords(__only1whitespace(utf8_decode($apePaterno))),
                                 "ape_materno"      => _ucwords(__only1whitespace(utf8_decode($apeMaterno))),
                                 "tipo_documento"   => ($tipoDoc!=null)?intval($tipoDoc):null,
                                 "nro_documento"    => $nroDoc,
                                 "sexo"             => $sexo,
                                 "fecha_nacimiento" => $fecNaci,
                                 "grado_ingreso"    => $gradoNivel[0],
                                 "nivel_ingreso"    => $gradoNivel[1],
                                 "flg_estudiante"   => FLG_ESTUDIANTE,
                                 "cod_grupo"        => $codGrupo,
                                 "tipo_proceso"     => '{'.ANIO_LECTIVO.'}'//POR DEFECTO ANIO ELECTIVO
            );
            $data = $this->m_confirm_datos->insertarContacto($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $html .= '<span class="mdl-chip mdl-chip--contact mdl-chip--deletable active chip-hijos" style="cursor:pointer" onclick="changeHijosDatos(\''._simple_encrypt($data['idContacto']).'\', this)">
                               <img class="mdl-chip__contact" src="'.RUTA_IMG_PROFILE.'nouser.svg"></img>
                               <span class="mdl-chip__text">'.$apePaterno.', '.$nombres.'</span>
                           </span>';
                $data['chip'] = $html;
                $data['idPostulante'] = _simple_encrypt($data['idContacto']);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}