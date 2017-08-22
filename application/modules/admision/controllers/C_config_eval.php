<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_config_eval extends CI_Controller {

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
        $this->load->model('mf_evento/m_detalle_evento');
        $this->load->model('mf_config_eval/m_config_eval');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_CONFIG_EVAL, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
	    ////Modal Popup Iconos///
    	$rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['titleHeader']      = "Configuraci&oacute;n";
	    $data['ruta_logo']        = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo']      = NAME_MODULO_ADMISION;
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $nivelesGrado = $this->m_config_eval->getGradosNiveles();
	    $data['nivelGrados'] = _createTableNivelesGrados($nivelesGrado);

	    $this->load->view('V_config_eval',$data);
	}
	
	function cursosGradoNivel(){
	    $idGrado = _simpleDecryptInt(_post("grado"));
	    $cursos = $this->m_config_eval->getCursosByGrado($idGrado);
	    $data['tbCursos'] = _createTableCursosNivelesGrados($cursos);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function addCursoNivelGrado(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idGrado = _simpleDecryptInt(_post("grado"));
	        $descripcion = utf8_decode(_post("descripcion"));
	        if($idGrado == null){
	            throw new Exception(ANP);
	        }
	        if($descripcion == null){
	            throw new Exception("Ingrese una descripci&oacute;n");
	        }
            if($this->m_config_eval->validateSameDescripcionCursoGrado($idGrado, $descripcion) > 0){
                throw new Exception("Ya existe un curso con el mismo nombre");
            }
            $arrayInsert = array("_id_nivel"     => $this->m_utils->getById("grado", "id_nivel", "nid_grado", $idGrado),
                                 "_id_grado"     => $idGrado,
                                 "descripcion"   => $descripcion,
                                 "fecha_modi"    => date('Y-m-d H:i:s'),
                                 "_id_usua_modi" => $this->_idUserSess,
                                 "flg_activo"    => FLG_ACTIVO);
            $data = $this->m_config_eval->insertCursoGrado($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $nivelesGrado = $this->m_config_eval->getGradosNiveles();
                $data['tbGradosNiveles'] = _createTableNivelesGrados($nivelesGrado);
                $cursos = $this->m_config_eval->getCursosByGrado($idGrado);
                $data['tbCursos'] = _createTableCursosNivelesGrados($cursos);
            }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deleteCursoNivelGrado(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        if($idCurso == null){
	            throw new Exception(ANP);
	        }
	        $data = $this->m_config_eval->deleteCursoGrado($idCurso);
	        if($data['error'] == EXIT_SUCCESS){
	            $idGrado = _simpleDecryptInt(_post("grado"));
	            $nivelesGrado = $this->m_config_eval->getGradosNiveles();
	            $data['tbGradosNiveles'] = _createTableNivelesGrados($nivelesGrado);
	            $cursos = $this->m_config_eval->getCursosByGrado($idGrado);
	            $data['tbCursos'] = _createTableCursosNivelesGrados($cursos);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function changeEstadoCurso(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        if($idCurso == null){
	            throw new Exception(ANP);
	        }
	        $estado = ($this->m_utils->getById("admision.config_eval", "flg_activo", "id_config_eval", $idCurso) == FLG_ACTIVO) ? FLG_INACTIVO : FLG_ACTIVO;
	        $arrayUpdate = array("flg_activo" => $estado);
	        $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        if($data['error'] == EXIT_SUCCESS){
	            $idGrado = _simpleDecryptInt(_post("grado"));
	            $nivelesGrado = $this->m_config_eval->getGradosNiveles();
	            $data['tbGradosNiveles'] = _createTableNivelesGrados($nivelesGrado);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function uploadDocCurso(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        if($idCurso == null){
	            throw new Exception(ANP);
	        }

	        if($data['error'] == EXIT_SUCCESS){
	            $idGrado = _simpleDecryptInt(_post("grado"));
	            $nivelesGrado = $this->m_config_eval->getGradosNiveles();
	            $data['tbGradosNiveles'] = _createTableNivelesGrados($nivelesGrado);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function crearTemasCurso(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        $descripcion = _post("desc");
	        if($idCurso == null){
	            throw new Exception(ANP);
	        }
	        if($descripcion == null){
	            throw new Exception("Ingrese una descripci&oacute;n");
	        }
	        $indicadores = json_decode($this->m_utils->getById("admision.config_eval", "indicadores", "id_config_eval", $idCurso));
	        if(count($indicadores) == 0){//INSERTAR
	            $jsonb = '[';
    	            $jsonb .= '{';
        	            $jsonb .= '"descripcion":"'.__only1whitespace($this->limpiar(utf8_decode($descripcion))).'",';
        	            $jsonb .= '"id":1';
    	            $jsonb .= '}';
	            $jsonb .= ']';
	            $arrayUpdate = array("indicadores" => $jsonb);
	            $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        }else{//UPDATEAR
	            if($this->m_config_eval->validateSameDescIndicadores($descripcion, $idCurso) > 0){
	                throw new Exception("Ya existe indicador con la misma descripci&oacute;n");
	            }
	            $id = 1;
	            $enc = 0;
	            $j = 1;
	            for($i = 0; $i < $j; $i++){
	                foreach($indicadores as $ind){
	                    if($ind->id != $id){
	                        $enc = 1;
	                    }else{
	                        $id++;
	                        $enc = 0;
	                    }
	                }
	                if($enc == 1){
	                    break;
	                }else{
	                   $j++;
	                }
	            }
	            $array = array('descripcion' => __only1whitespace($this->limpiar(utf8_decode($descripcion))),
	                           'id'          => $id);
	            array_push($indicadores, $array);
	            $arrayUpdate = array("indicadores" => json_encode($indicadores));
	            $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        }
	        if($data['error'] == EXIT_SUCCESS){
	            $indicadores   = json_decode($this->m_utils->getById("admision.config_eval", "indicadores", "id_config_eval", $idCurso));
	            $data['tabla'] = _createTableIndicadoresCurso($indicadores);
	        }
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
	
	function guardarTituloObservacion(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        $descripcion = _post("desc");
	        if($idCurso == null){
	            throw new Exception(ANP);
	        }
	        if($descripcion == null){
	            throw new Exception("Ingrese una descripci&oacute;n");
	        }
	        $arrayUpdate = array("titulo_observacion" => __only1whitespace(utf8_decode($descripcion)));
	        $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        if($data['error'] == EXIT_SUCCESS){
	            $idGrado = _simpleDecryptInt(_post("grado"));
	            $cursos = $this->m_config_eval->getCursosByGrado($idGrado);
	            $data['tbCursos'] = _createTableCursosNivelesGrados($cursos);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function crearOpcionesCurso(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        $descripcion = _post("desc");
	        if($idCurso == null){
	            throw new Exception(ANP);
	        }
	        if($descripcion == null){
	            throw new Exception("Ingrese una descripci&oacute;n");
	        }
	        $opciones = json_decode($this->m_utils->getById("admision.config_eval", "opciones_eval", "id_config_eval", $idCurso));
	        if(count($opciones) == 0){//INSERTAR
	            $jsonb = '[';
    	            $jsonb .= '{';
        	            $jsonb .= '"descripcion":"'.__only1whitespace($this->limpiar(utf8_decode($descripcion))).'",';
        	            $jsonb .= '"id":1';
    	            $jsonb .= '}';
	            $jsonb .= ']';
	            $arrayUpdate = array("opciones_eval" => $jsonb);
	            $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        }else{//UPDATEAR
	            if($this->m_config_eval->validateSameDescOpciones($descripcion, $idCurso) > 0){
	                throw new Exception("Ya existe el nivel con la misma descripci&oacute;n");
	            }
	            $id = 1;
	            $enc = 0;
	            $j = 1;
	            for($i = 0; $i < $j; $i++){
	                foreach($opciones as $ind){
	                    if($ind->id != $id){
	                        $enc = 1;
	                    }else{
	                        $id++;
	                        $enc = 0;
	                    }
	                }
	                if($enc == 1){
	                    break;
	                }else{
	                    $j++;
	                }
	            }
	            $array = array('descripcion' => __only1whitespace($this->limpiar(utf8_decode($descripcion))),
	                           'id'          => $id);
	            array_push($opciones, $array);
	            $arrayUpdate = array("opciones_eval" => json_encode($opciones));
	            $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        }
	        if($data['error'] == EXIT_SUCCESS){
	            $opciones   = json_decode($this->m_utils->getById("admision.config_eval", "opciones_eval", "id_config_eval", $idCurso));
	            $data['tabla'] = _createTableOpcionesCurso($opciones);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function configuracionGeneralCurso(){
	    $idCurso = _simpleDecryptInt(_post("curso"));
	    $opciones   = json_decode($this->m_utils->getById("admision.config_eval", "opciones_eval", "id_config_eval", $idCurso));
	    $data['tablaOpciones'] = _createTableOpcionesCurso($opciones);
	    $indicadores   = json_decode($this->m_utils->getById("admision.config_eval", "indicadores", "id_config_eval", $idCurso));
	    $data['tablaIndicadores'] = _createTableIndicadoresCurso($indicadores);
	    $opcionesIndicadores   = json_decode($this->m_utils->getById("admision.config_eval", "opc_indicadores", "id_config_eval", $idCurso));
	    $data['tablaIndicadoresOpcion'] = _createTableOpcionesIndicador($opcionesIndicadores);
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deleteIndicadorCurso(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $id = _simpleDecryptInt(_post("id"));
	        if($id == null){
	            throw new Exception(ANP);
	        }
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        
	        $indicadores = $this->m_utils->getById("admision.config_eval", "indicadores", "id_config_eval", $idCurso);
	        $indicadores = json_decode($indicadores);
	        $i = 0;
	        foreach($indicadores as $ind){
	            if($ind->id == $id){
	                break;
	            }else{
	                $i++;
	            }
	        }
	        array_splice($indicadores, $i,1);
	        //$indicadores = (count($indicadores) == 0)?'':$indicadores;
	        $arrayUpdate = array("indicadores" => json_encode($indicadores));
	        $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        if($data['error'] == EXIT_SUCCESS){
	            $indicadores   = json_decode($this->m_utils->getById("admision.config_eval", "indicadores", "id_config_eval", $idCurso));
	            $data['tabla'] = _createTableIndicadoresCurso($indicadores);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deleteNivelesCurso() {
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $id = _simpleDecryptInt(_post("id"));_log($id);
	        if($id == null){
	            throw new Exception(ANP);
	        }
	        $idCurso = _simpleDecryptInt(_post("curso"));
	         
	        $opciones = $this->m_utils->getById("admision.config_eval", "opciones_eval", "id_config_eval", $idCurso);
	        $opciones = json_decode($opciones);
	        $i = 0;
	        foreach($opciones as $opc){
	            if($opc->id == $id){
	                break;
	            }else{
	                $i++;
	            }
	        }
	        array_splice($opciones, $i,1);
	        //$opciones = (count($opciones) == 0)? '' : $opciones;
	        $arrayUpdate = array("opciones_eval" => json_encode($opciones));
	        $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        if($data['error'] == EXIT_SUCCESS){
	            $opciones   = json_decode($this->m_utils->getById("admision.config_eval", "opciones_eval", "id_config_eval", $idCurso));
	            $data['tabla'] = _createTableOpcionesCurso($opciones);
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function crearOpcionesIndicador(){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $idCurso = _simpleDecryptInt(_post("curso"));
	        $descripcion = _post("desc");
	        if($idCurso == null){
	            throw new Exception(ANP);
	        }
	        if($descripcion == null){
	            throw new Exception("Ingrese una descripci&oacute;n");
	        }
	        $indicadores = json_decode($this->m_utils->getById("admision.config_eval", "opc_indicadores", "id_config_eval", $idCurso));
	        if(count($indicadores) == 0){//INSERTAR
	            $jsonb = '[';
	            $jsonb .= '{';
	            $jsonb .= '"descripcion":"'.__only1whitespace($this->limpiar(utf8_decode($descripcion))).'",';
	            $jsonb .= '"id":1';
	            $jsonb .= '}';
	            $jsonb .= ']';
	            $arrayUpdate = array("opc_indicadores" => $jsonb);
	            $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        }else{//UPDATEAR
	            if($this->m_config_eval->validateSameDescIndicadores($descripcion, $idCurso) > 0){
	                throw new Exception("Ya existe una opci&oacute;n con la misma descripci&oacute;n");
	            }
	            $id = 1;
	            $enc = 0;
	            $j = 1;
	            for($i = 0; $i < $j; $i++){
	                foreach($indicadores as $ind){
	                    if($ind->id != $id){
	                        $enc = 1;
	                    }else{
	                        $id++;
	                        $enc = 0;
	                    }
	                }
	                if($enc == 1){
	                    break;
	                }else{
	                    $j++;
	                }
	            }
	            $array = array('descripcion' => __only1whitespace($this->limpiar(utf8_decode($descripcion))),
	                'id'          => $id);
	            array_push($indicadores, $array);
	            $arrayUpdate = array("opc_indicadores" => json_encode($indicadores));
	            $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        }
	        if($data['error'] == EXIT_SUCCESS){
	            $indicadores   = json_decode($this->m_utils->getById("admision.config_eval", "opc_indicadores", "id_config_eval", $idCurso));
	            $data['tabla'] = _createTableOpcionesIndicador($indicadores);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deleteOpcionIndicador() {
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = MSJ_ERROR;
	    try{
	        $id = _simpleDecryptInt(_post("id"));_log($id);
	        if($id == null){
	            throw new Exception(ANP);
	        }
	        $idCurso = _simpleDecryptInt(_post("curso"));
	
	        $opciones = $this->m_utils->getById("admision.config_eval", "opc_indicadores", "id_config_eval", $idCurso);
	        $opciones = json_decode($opciones);
	        $i = 0;
	        foreach($opciones as $opc){
	            if($opc->id == $id){
	                break;
	            }else{
	                $i++;
	            }
	        }
	        array_splice($opciones, $i,1);
	        //$opciones = (count($opciones) == 0)? '' : $opciones;
	        $arrayUpdate = array("opc_indicadores" => json_encode($opciones));
	        $data = $this->m_config_eval->updateCursoGrado($arrayUpdate, $idCurso);
	        if($data['error'] == EXIT_SUCCESS){
	            $opciones      = json_decode($this->m_utils->getById("admision.config_eval", "opc_indicadores", "id_config_eval", $idCurso));
	            $data['tabla'] = _createTableOpcionesIndicador($opciones);
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