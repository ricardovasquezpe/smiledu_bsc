<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_main extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('../m_utils');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('m_utils_senc');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
        
        if(!isset($_COOKIE[$this->config->item('sess_cookie_name')])) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
        
        if($this->_idUserSess == null || $this->_idRol == null) {
            $this->session->sess_destroy();
            Redirect(RUTA_SMILEDU, 'refresh');
        }
    }
    
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
	    ////Modal Popup Iconos///
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $tipoEnc = null;
        if(_getSesion('cod_familiar') == null) {
            $area = $this->m_utils->getById('rrhh.personal_detalle', 'id_area_general', 'id_persona', $this->_idUserSess);
            if($area == ID_AREA_ACADEMICA) {
                $tipoEnc = TIPO_ENCUESTA_DOCENTE;
            } else {
                $tipoEnc = TIPO_ENCUESTA_PERSADM;
            }
        } else {
            $tipoEnc = TIPO_ENCUESTA_PADREFAM;
        }
        
	    $tipoEncuestado = TIP_ENCU_LIBRE_ADMINS;
	    if($this->_idRol == ID_ROL_DOCENTE) {
	        $tipoEncuestado = TIP_ENCU_LIBRE_DOCENT;
	    } else if(_getSesion('cod_familiar') != null) {
	        $tipoEncuestado = TIP_ENCU_LIBRE_PADRES;
	    }
	    $encuestas = $this->m_encuesta->getEncuestasByTipoEncuestaLibreAperturada($tipoEnc, $tipoEncuestado);
	    $data['tbEnc'] = $this->getTableEncuestas($encuestas, $this->_idUserSess, $this->_idRol);
	    
        ///////////
        $this->load->view('v_main', $data);
	}
	
	function getTableEncuestas($data, $idPersona, $idRol){
	    $flg_x_rol = $this->m_usuario->realizoEncuesta($idPersona, $idRol);
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-save="true" id="tb_encuestas">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $cont = 0;
	    $contChecked = 0;
	    foreach($data as $row){
	        $cont++;
	        $ruta = $row->ruta;
	        if($row->_id_tipo_encuesta == TIPO_ENCUESTA_LIBRE){
	            $ruta = $ruta.'?encuesta='._encodeCI($row->id_encuesta);
	        }
	        $row_1 = array('data' => $cont);
	        $row_2 = array('data' => $row->titulo_encuesta);
	        $row_3 = array('data' => $row->desc_enc);
	        if($flg_x_rol == 1 && $row->_id_tipo_encuesta != TIPO_ENCUESTA_LIBRE){
	            $row_4 = array('data' => '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon"><i class="mdi mdi-lock"></i></a>', 'class' => 'text-right');
	        }else{
	            $row_4 = array('data' => '<a href="'.$ruta.'" class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon"><i class="mdi mdi-input"></i></a>', 'class' => 'text-right');
	        }
	        $row_5 = array('data' => $row->cant_encuestados, 'class' => 'text-center');
	        $this->table->add_row($row_1, $row_2,$row_3, $row_5, $row_4);
	    }
	    $head_1 = array('data' => '#');
	    $head_2 = array('data' => 'Descripci&oacute;n');
	    $head_3 = array('data' => 'C&oacute;digo');
	    $head_4 = array('data' => 'Link', 'class' => 'text-right');
	    $head_5 = array('data' => 'Nro Encuestados', 'class' => 'text-center');
	    $this->table->set_heading($head_1, $head_2, $head_3, $head_5, $head_4);
	    $table = $this->table->generate();
	    return $table;
	}
	
	function logout() {
	    $this->session->set_userdata(array("logout" => true));
	    unset($_COOKIE['smiledu']);
	    $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	    Redirect(RUTA_SMILEDU, true);
	}
	
	function cambioRol() {
	    $idRolEnc = _post('id_rol');
	    $idRol = $this->lib_utils->simple_decrypt($idRolEnc,CLAVE_ENCRYPT);
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser = array(SENC_ROL_SESS => $idRol,
	                      "nombre_rol"  => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $idRol     = _getSesion('nombre_rol');
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $roles  = $this->m_usuario->getRolesByUsuario($this->_idUserSess,$this->_idRol);
	    $return = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol = $this->lib_utils->simple_encrypt($var->nid_rol,CLAVE_ENCRYPT);
	        $return  .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
	}

    function setIdSistemaInSession(){
	    $idSistema = $this->encrypt->decode(_post('id_sis'));
	    $idRol     = $this->encrypt->decode(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }	    
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem($this->_idUserSess,$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function cambiarDescripcionesIdsMongo() {
	    $start = microtime(true);
        $data = $this->m_utils_senc->getDataCambio();
        foreach($data as $row){
            $idEncuesta = ($row->flg_encuesta == '0') ? 3 : 6;
            $idPregunta = ($row->flg_encuesta == '0') ? $row->id_pregunta_2014 : $row->id_pregunta_2015;
            //RESPUESTA ENCUESTA
            $this->m_utils_senc->updateDescAndIdsRespuestaEncuesta($idEncuesta,$idPregunta,$row->pregunta_2016,$row->id_pregunta_2015,$row->id_pregunta_2014,$row->flg_encuesta);
            $this->m_utils_senc->updateDescAndIdsSatisfaccionEncuesta($idEncuesta,$idPregunta,$row->pregunta_2016,$row->id_pregunta_2015,$row->id_pregunta_2014,$row->flg_encuesta);
            $this->m_utils_senc->updateDescAndIdsInsatisfaccionEncuesta($idEncuesta,$idPregunta,$row->pregunta_2016,$row->id_pregunta_2015,$row->id_pregunta_2014,$row->flg_encuesta);
        }
        $time_elapsed_secs = microtime(true) - $start;
        $unidMedida = 'segundo(s)';
        if($time_elapsed_secs >= 60) {
            $time_elapsed_secs = $time_elapsed_secs / 60;
            $unidMedida = 'minuto(s)';
        } 
        echo 'FINALIZO OK en '.(round($time_elapsed_secs, 2)).' '.$unidMedida;
	}
	
	function getArrNotIn(){
	    $result = $this->m_encuesta->getAllArrPullMongo();
	    $data     = $result[0];
	    $arrayGeneral  = array(array(),array());
	    foreach($data as $row){
	        $coleccion = ($row['preguntas']['id_respuesta'] == 5 || $row['preguntas']['id_respuesta'] == 4) ? 'satisfaccion' : 'insatisfaccion';
	        $sql = 'db.senc_'.$coleccion.'_encuesta.update(
                          { 	 
    	                       id_encuesta : 37,
                               year        : 2016,
                               id_sede     : '.$row['id_sede'].',   
                               preguntas   : {
                                           $elemMatch: {
                                           			      id_pregunta :  '.$row['preguntas']['id_pregunta'].',
                                        				  id_area     : '.$row['id_area'].'
                                                       }
                                          }
                          },
                          { $inc: { "preguntas.$.count": -1 } }
                        )';
	        array_push($arrayGeneral[0], $sql);
	        
	        $sql = 'db.senc_respuesta_encuesta.update(
                      { },
                      {$pull : {preguntas : {
                        	id_pregunta    : '.$row['preguntas']['id_pregunta'].',
                        	id_respuesta   : '.$row['preguntas']['id_respuesta'].',
                        	id_dispositivo : '.$row['preguntas']['id_dispositivo'].' }}},
                      {multi : true}
                    )';
	        array_push($arrayGeneral[1], $sql);
	    }
	    //---------------------------------------------------------------------------------
	    $arrayCantParti = array(array(),array());
	    $preguntas = $this->m_encuesta->getArrayPreguntas();
            foreach($preguntas as $preg){
	            $sql = 'db.senc_insatisfaccion_encuesta.update(
                          {
    	                       id_encuesta : 37,
                               year        : 2016,
                               id_sede     : '.$row['id_sede'].',
                               preguntas   : {
                                           $elemMatch: {
                                           			      id_pregunta :  '.$preg.',
                                        				  id_area     :  1
                                                       }
                                          }
                          },
                          { $inc: { "preguntas.$.cant_participantes": -1 } }
                        )';
	            array_push($arrayCantParti[0], $sql);
	            $sql = 'db.senc_satisfaccion_encuesta.update(
                          {
    	                       id_encuesta : 37,
                               year        : 2016,
                               id_sede     : '.$row['id_sede'].',
                               preguntas   : {
                                           $elemMatch: {
                                           			      id_pregunta :  '.$preg.',
                                        				  id_area     : 1
                                                       }
                                          }
                          },
                          { $inc: { "preguntas.$.cant_participantes": -1 } }
                        )';
	            array_push($arrayCantParti[1], $sql);
	        }
// 	        _log(print_r($arrayGeneral,true));
// 	        _log(print_r($arrayCantParti,true));
	    $this->m_encuesta->sanearColecciones($arrayGeneral,$arrayCantParti);
	    echo 'TERMINO';
	}
	
	function updateCountValues(){
	    $result = $this->m_encuesta->datosProceso(11776);
	    $arrSatis = array();
	    $arrInsatis = array();
	    foreach($result as $row){
	        $countSatis   = 0;
	        $countInsatis = 0;
	        foreach($row['datos'] as $sRow){
	            if($sRow['id_respuesta'] == 1 || $sRow['id_respuesta'] == 2){
	                $countInsatis = $countInsatis + intval($sRow['respuesta']);
	            } else if($sRow['id_respuesta'] == 4 || $sRow['id_respuesta'] == 5){
	                $countSatis = $countSatis + intval($sRow['respuesta']);
	            }
	        }
	        $sql = 'db.senc_satisfaccion_encuesta.update(
                          {
    	                       id_encuesta : 32,
                               year        : 2016,
                               preguntas   : {
                                           $elemMatch: {
                                           			      id_pregunta :  '.$row['_id']['pregunta'].',
                                        				  id_aula     : '.$row['_id']['aula'].'
                                                       }
                                          }
                          },
                          { $set: { "preguntas.$.count": '.$countSatis.'} }
                        )';
	        $sql1 = 'db.senc_insatisfaccion_encuesta.update(
                          {
    	                       id_encuesta : 32,
                               year        : 2016,
                               preguntas   : {
                                           $elemMatch: {
                                           			      id_pregunta :  '.$row['_id']['pregunta'].',
                                        				  id_aula     : '.$row['_id']['aula'].'
                                                       }
                                          }
                          },
                          { $set: { "preguntas.$.count": '.$countInsatis.'} }
                        )';
	        array_push($arrSatis, $sql);
	        array_push($arrInsatis, $sql1);
	    }
	    $this->m_encuesta->sanearColecciones(array($arrSatis,$arrInsatis));
	    echo 'Fin';
	}
	
	function updateServicioDocentes(){
	    $preguntas     = $this->m_encuesta->getPreguntasByEncuestas(array(2,4));
	    $preguntasStr  = null;
	    $arrayServPreg = array();
	    foreach($preguntas as $row){
	        $preguntasStr .= $row->_id_pregunta.',';
	        $arrayServPreg[$row->_id_pregunta] = $row->_id_servicio;
	    }
	    $preguntasStr = substr($preguntasStr, 0 , (strlen($preguntasStr)-1));
	    // 	    _log($preguntasStr);
	    // 	    _log(print_r($arrayServPreg,true));
	    $result = $this->m_encuesta->getArraysByPreguntaEncuesta('2,4',$preguntasStr);
	    $arrayUpdateSatisfaccion   = array();
	    foreach($result as $row){
	        $idServ = $arrayServPreg[$row['_id']['id_pregunta']];
	        $sql = 'db.senc_satisfaccion_encuesta.update(
                      {
                    	id_encuesta : '.$row['_id']['id_encuesta'].',
                    	id_sede     : '.$row['id_sede'].',
                    	preguntas   : {
                    	  $elemMatch: {
                    		id_pregunta :  '.$row['_id']['id_pregunta'].'
                    	  }
                    	}
                      },
                      { $set: { "preguntas.$.id_indicador" :  '.$idServ.'} }
                    )';
	        array_push($arrayUpdateSatisfaccion, $sql);
	    }
	    $this->m_encuesta->executeQuerysServicios($arrayUpdateSatisfaccion);
	    echo 'FinDocentes';
	}
	
	function updateServicioEstudiantes(){
	    $preguntas     = $this->m_encuesta->getPreguntasByEncuestas(array(1,5,32));
	    $preguntasStr  = null;
	    $arrayServPreg = array();
	    foreach($preguntas as $row){
	        $preguntasStr .= $row->_id_pregunta.',';
	        $arrayServPreg[$row->_id_pregunta] = $row->_id_servicio;
	    }
	    $preguntasStr = substr($preguntasStr, 0 , (strlen($preguntasStr)-1));
	    $result = $this->m_encuesta->getArraysByPreguntaEncuesta('1,5,32',$preguntasStr);
	    $arrayUpdateSatisfaccion   = array();
	    foreach($result as $row){
	        $idServ = $arrayServPreg[$row['_id']['id_pregunta']];
	            $sql = 'db.senc_satisfaccion_encuesta.update(
                      {
                    	id_encuesta : '.$row['_id']['id_encuesta'].',
                    	preguntas   : {
                    	  $elemMatch: {
                    		id_pregunta :  '.$row['_id']['id_pregunta'].',
                    		id_aula     :  '.$row['_id']['id_aula'].'
                    	  }
                    	}
                      },
                      { $set: { "preguntas.$.id_indicador" :  '.$idServ.'} }
                    )';
	        array_push($arrayUpdateSatisfaccion, $sql);
	    }
	    $this->m_encuesta->executeQuerysServicios($arrayUpdateSatisfaccion);
	    echo 'FinEstudiantes';
	}
	
    function updateServicioPadres(){
	    $preguntas     = $this->m_encuesta->getPreguntasByEncuestas(array(3,6,27));
	    $preguntasStr  = null;
	    $arrayServPreg = array();
	    foreach($preguntas as $row){
	        $preguntasStr .= $row->_id_pregunta.',';
	        $arrayServPreg[$row->_id_pregunta] = $row->_id_servicio;
	    }
	    $preguntasStr = substr($preguntasStr, 0 , (strlen($preguntasStr)-1));
	    $result = $this->m_encuesta->getArraysByPreguntaEncuesta('3,6,27',$preguntasStr);
	    $arrayUpdateSatisfaccion   = array();
	    foreach($result as $row){
	        $idServ = $arrayServPreg[$row['_id']['id_pregunta']];
	        $sql = 'db.senc_satisfaccion_encuesta.update(
                      {
                    	id_encuesta : '.$row['_id']['id_encuesta'].',
                    	preguntas   : {
                    	  $elemMatch: {
                    		id_pregunta :  '.$row['_id']['id_pregunta'].',
                    		id_aula     :  '.$row['_id']['id_aula'].'
                    	  }
                    	}
                      },
                      { $set: { "preguntas.$.id_indicador" :  '.$idServ.'} }
                    )';
	        array_push($arrayUpdateSatisfaccion, $sql);
	    }
	    $this->m_encuesta->executeQuerysServicios($arrayUpdateSatisfaccion);
	    echo 'FinPadres';
	}
	
	function updateCantParti(){
	    $preguntas = $this->m_encuesta->getArrayPreguntas();
	    $arrayQuerys = array(array(),array());
		
		foreach($preguntas as $preg){
			$sql = 'db.senc_satisfaccion_encuesta.update(
                      {
                    	id_encuesta : 32,
                    	preguntas   : {
                    	  $elemMatch: {
                    		id_pregunta :  '.$preg.',
                    		id_aula     :  51241 
                    	  }
                    	}
                      },
                      { $set: { "preguntas.$.cant_participantes" : 42  } }
                    )';
	        array_push($arrayQuerys[0], $sql);
	        $sql = 'db.senc_insatisfaccion_encuesta.update(
                      {
                    	id_encuesta : 32,
                    	preguntas   : {
                    	  $elemMatch: {
                    		id_pregunta :  '.$preg.',
                    		id_aula     :  51241 
                    	  }
                    	}
                      },
                      { $set: { "preguntas.$.cant_participantes" : 42  } }
                    )';
	        array_push($arrayQuerys[1], $sql);
		}
	    $this->m_encuesta->sanearColecciones($arrayQuerys);
	    echo 'Fin';   
	}
	
	function updateDispEncuestasLibres(){
	    $encuestas    = $this->m_encuesta->getAllEncuestasLibres();
	    $dispositivos = $this->m_encuesta->getRespuestasDispositivos($encuestas);
	    $arrayUpdate = array();
	    foreach ($dispositivos as $row){
	        $respuestas = "";
	        foreach($row['arrayPreguntas'] as $rowPreg){
	            $respuestas .= '{
                                    "id_pregunta"    : '.$rowPreg['id_pregunta'].',
                                    "desc_pregunta"  : "'.utf8_decode($rowPreg['desc_pregunta']).'",
                                    "id_respuesta"   : '.$rowPreg['id_respuesta'].',
                                    "respuesta"      : "'.utf8_decode($rowPreg['respuesta']).'",
                                    "count"          : 1
                                },';
	        }
	        $respuestas       = rtrim(trim($respuestas), ",");
	        array_push($arrayUpdate, array(
	                                       'id_device_info'   => $row['_id'],
	                                       'respuestas_jsonb' => '{ "preguntas" : [ '.$respuestas.' ] }',
	                                       'nid_sede'         => $row['id_sede'],
	                                       'nid_nivel'        => $row['id_nivel'],
	                                       'nid_grado'        => $row['id_grado'],
	                                       'nid_aula'         => $row['id_aula']
	                                      )
	                  );
	    }
// 	    $this->m_encuesta->updateDispositivos($arrayUpdate);
	    $dispositivos = $this->m_encuesta->getPropuestasDispositivos($encuestas);
	    foreach($dispositivos as $row){
	        $propuestasMejora = "";
	        foreach($row['propuestas'] as $prop){
	            $desc_prop = $this->m_utils->getById('senc.propuesta_mejora', 'desc_propuesta', 'id_propuesta', $prop);
	            $propuestasMejora .= '{
                                          "id_propuesta"    : '.$prop.',
                                          "desc_propuesta"  : "'.$desc_prop.'",
                                          "count"           : 1,
                                          "tipo_encuestado" : "'.$row['tipo_encuestado'].'"
                                      },';
	        }
	        $propuestasMejora       = rtrim(trim($propuestasMejora), ",");
	        array_push($arrayUpdate, array(
                                	          'id_device_info'   => $row['_id'],
                                	          'propuestas_jsonb' => '{ "propuestas" : [ '.$propuestasMejora.' ] }',
	                                          'comentario'       => utf8_decode($row['comentario'])
                                	      )
	                  );
	    }
// 	    _log(print_r($arrayUpdate,true));
	    $this->m_encuesta->updateDispositivos($arrayUpdate);
	    echo "Fin";
	}
}




