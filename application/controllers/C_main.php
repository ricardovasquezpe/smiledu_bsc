<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_main extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('m_main');
        $this->load->model('m_mural');
        $this->load->model('m_utils');
        $this->load->helper('cookie');
        if(!isset($_COOKIE[__getCookieName()])) {
            redirect(RUTA_SMILEDU, 'refresh');
        }
    }
   
	public function index() {
	    $logedUser = _getSesion('usuario');
  	    $data['titleHeader'] = 'Hola';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
  	    
  	    $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
  	                                <a href="#tab-1" class="mdl-layout__tab is-active">Sistemas</a>
  	                                <a href="#" style="cursor:no-drop;color: #F5F5F5 !important; pointer-events:none" class="mdl-layout__tab" title="Pr&oacute;ximamente" disabled>Mural</a>
	                                <a href="#" style="cursor:no-drop;color: #F5F5F5 !important; pointer-events:none" class="mdl-layout__tab" title="Pr&oacute;ximamente" disabled>Ranking de Aulas</a>
                                </div>';//href="#tab-3" href="#'.MURAL_PUBLICO.'"
  	    $data['btnSearch']   = '<a type="button" class="mdl-button mdl-js-button mdl-button--icon" onclick="setFocus(\'#searchMagic\')" id="openSearch">
                                    <i class="mdi mdi-magic md-0"></i>
                                </a>';
  	    $data['inputSearch'] = '<div class="mdl-header-input-group">
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-magic md-0"></i>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield" id="cont_inputtext_busqueda">
                                        <input class="mdl-textfield__input" type="text" id="searchMagic">
                                        <label class="mdl-textfield__label" for="searchMagic">Busca un sistema</label>
                                    </div>
                                    <div class="mdl-icon mdl-right">
                                        <a type="button" class="mdl-button mdl-js-button mdl-button--icon" id="closeSearch">
                                            <i class="mdi mdi-close"></i>
                                        </a>
                                    </div>
                                </div>';
	    if (array_key_exists ('roles', $this->session->userdata())) {
	       $roles = _getSesion('roles');
	    } else {
	        $roles = null;
	    }
	    if($logedUser != null) {
 	        $data['usuarioLogeado'] = $logedUser;
 	        $data['nombreUsuario']  = _getSesion('usuario');
          	$roles  = _getSesion('roles');
          	
	        $finalRoles = array();
	        $arrayTipoEnc = arraY();
	        $cantEnc     = 0;
	        $cantFlgEnc  = 0;
	        /*$rolPersDetalle = $this->m_utils->getById('rrhh.personal_detalle', 'id_area_general', 'id_persona', _getSesion("nid_persona"));
	        if($rolPersDetalle == 18){
	            $this->session->set_userdata(array(SENC_ROL_SESS => ID_ROL_DOCENTE));
	            redirect('senc/c_encuesta_nueva/c_encuesta_efqm');
	        }*/
	        if(is_array($roles)) {
	            foreach($roles as $rol) {
	                array_push($finalRoles, $rol->nid_rol);
	                $cantFlgEnc = $this->m_main->validateEncuestaPersonaRol(_getSesion("nid_persona"), $rol->nid_rol) + $cantFlgEnc;
	                $tipoEnc = null;
	                if($rol->nid_rol == ID_ROL_DOCENTE){
	                    $tipoEnc = 1;
	                }else{
	                    $tipoEnc = 4;
	                }
	                if(!in_array($tipoEnc, $arrayTipoEnc)){
	                    array_push($arrayTipoEnc, $tipoEnc);
	                }
	            }
	        }
//       		foreach($roles as $rol){
//       		    if($rol->nid_rol == ID_ROL_DOCENTE){
//       		        $this->session->set_userdata(array(SENC_ROL_SESS => ID_ROL_DOCENTE));
//       		        redirect('senc/c_encuesta_nueva/c_encuesta_efqm');
//       		    }
//       		}
      		foreach($arrayTipoEnc as $tipoEnc){
      		    $cantEnc = $this->m_main->validateEncuestaByTiepoEncuesta($tipoEnc) + $cantEnc;
      		}
      		$data['countEnc']    = $cantEnc;
      		$data['countFlgEnc'] = $cantFlgEnc;
      		
      		$flg_fab = MURAL_PUBLICO.'fab';
      		$publicacionesEstrella = $this->m_mural->getPublicaciones(0, MURAL_ESTRELLA, 1, null);
      		$publicacionesPublico  = $this->m_mural->getPublicaciones(0, MURAL_PUBLICO , 1, null);
      		$publicacionesDocente  = $this->m_mural->getPublicaciones(0, MURAL_DOCENTE , 1, null);
      		$data['publicacionesEstrella'] = $this->buildContenPublicaciones($publicacionesEstrella , 'estrella');
      		$data['publicacionesPublicas'] = $this->buildContenPublicaciones($publicacionesPublico, 'publico');
      		$data['publicacionesDocente']  = $this->buildContenPublicaciones($publicacionesDocente, 'docente');
      		$icono = "scwl-icon_buho_marca";
      		if(is_array($this->session->userdata('roles'))) {
      		    foreach($this->session->userdata('roles') as $row){
      		        if($row->nid_rol == ID_ROL_PROMOTOR || $row->nid_rol == ID_ROL_DIRECTOR){
      		            $icono = "md md-star";
      		            $flg_fab = MURAL_ESTRELLA.'fab';
      		        } else if($rol->nid_rol == ID_ROL_DOCENTE){
      		            $flg_fab = MURAL_DOCENTE.'fab';
      		        }
      		    }   
      		}
      		$data['flg_fab'] = '"'.$flg_fab.'"';
      		$data['icono'] = $icono;
      		$mod = null;
      		/*if(in_array(ID_ROL_FAMILIA, $finalRoles)){
      			$countHijosAntiguos = $this->m_main->getCountHijosAntiguos(_getSesion("nid_persona"));
      			$antiguos = $countHijosAntiguos != null ? array_sum($countHijosAntiguos) : 0;
      			if($antiguos == 0){
      				$hijosDeben = $this->m_main->getCountHijosDeben(_getSesion("cod_familiar"), ALUMNO_PREREGISTRO, '!=');
      				if($hijosDeben['cant'] == $hijosDeben['total'] && count($hijosDeben) != 0){
      					//MOSTRAR MODAL
      					$data['faltaPago'] = 1;
      					$mod = 'pagos';
      					$data['modulo']    = 'pagos';
      					$data['color']     = '#009688';
      					$data['title']     = "Tienes deudas pendientes";
      				} else{
      				    $hijosDeben = $this->m_main->getCountHijosDeben(_getSesion("cod_familiar"), ALUMNO_REGISTRADO, '=');
      				    if($hijosDeben['total'] != $hijosDeben['confirmacion']){
      				        //MOSTRAR MODAL
      				        $data['faltaPago'] = 1;
      				        $mod = 'matr&iacute;cula';
      				        $data['modulo']    = 'matr&iacute;cula';
      				        $data['color']     = '#3c4db0';
      				        $data['title']     = "Matric&uacute;late";
  				        }
      				}
      			}
      		}*/
          	if($roles != null) {
          		$rolSistemas = $this->m_main->getSistemasByRol($finalRoles);
          	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase(_getSesion("nid_persona"));
          	    $data['tb']   = $this->createSistemas_x_rol($rolSistemas, $finalRoles, $mod);
          	    $data['apps'] = __modalCreateSistemasByRol($rolSistemas);
          	    //MENU
          	    $menu         = $this->load->view('v_menu', $data, true);
          	    $data['menu'] = $menu;
				$this->load->view('v_main', $data);
          	} else {
          	    //MENU
          	    $data['arbolPermisosMantenimiento'] = null;
          	    $menu         = $this->load->view('v_menu', $data, true);
          	    $data['menu'] = $menu;
          	    $this->load->view('v_main', $data);
          	}
	    } else {
	        $this->session->sess_destroy();
	        redirect(RUTA_SMILEDU,'refresh');
	    }
	}
	
	function cerrar() {
	    $redirectUrl = RUTA_SMILEDU;
	    if(_getSesion('cod_familiar') != null) {
	        $redirectUrl .= 'padres';
	    }
	    $this->session->sess_regenerate();
	    unset($_COOKIE[__getCookieName()]);
	    $cookie_name2  = __getCookieName();
	    $cookie_value2 = "";
	    setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	    $this->session->sess_destroy();
	    redirect($redirectUrl, 'refresh');
	}
	
    function createSistemas_x_rol($rolSistemas, $roles, $modulo = null) {
        $result = null;
        $contador = 0;
        foreach ($rolSistemas as $var) {
            $idSistema = _encodeCI($var->nid_sistema);
            $opcionesArry  = $this->rolesSistem($roles, $var->url_sistema, $var->nid_sistema, $var->flg_realizado, $var->desc_sist);
            $opciones = $opcionesArry[0];//HTML SI ES 1 solo viene el onclick
            $cantRoles = $opcionesArry[1];
            $classSenc = "";
            $styleDesactivado = null;
            $col = "";
            if($var->flg_realizado == 0) {
                $styleDesactivado = "cursor:not-allowed";
            }
            if($var->nid_sistema == ID_SISTEMA_SENC) {
                $classSenc = "card-senc";
            }
            
            $index = null;
            if($modulo == strtolower(str_replace(" ", "_", $var->desc_sist))){
                $index = 'style="z-index:5000"';
            }
            
            $opcionesHTML = null;
            $onclickHTML = null;
            $effectHTML = null;
            $not_app_style = null;
            $not_app_title = null;
            $app_icon  = "arrow_forward";
            
            if($cantRoles > 1) {
                $opcionesHTML = $opciones;
                $onclickHTML = 'onclick="openSistema(\'card-main-'.strtolower(str_replace(" ", "_", $var->desc_sist)).'\')"';
            } else if($cantRoles == 1) {
                $onclickHTML = $opciones;
                $effectHTML  = 'data-rippleria data-rippleria-duration="500"';
            }
            
            if( $var->nid_sistema == ID_SISTEMA_SEGURIDAD   || $var->nid_sistema == ID_SISTEMA_GEDUCA       || $var->nid_sistema == ID_SISTEMA_INSTRUMENTOS || 
                $var->nid_sistema == ID_SISTEMA_RRHH        || $var->nid_sistema == ID_SISTEMA_BIBLIOTECA   || $var->nid_sistema == ID_SISTEMA_JUEGOS       || 
                $var->nid_sistema == ID_SISTEMA_MOVILIDAD   || $var->nid_sistema == ID_SISTEMA_MEDICO       || $var->nid_sistema == ID_SISTEMA_COMEDOR      ||
                $var->nid_sistema == ID_SISTEMA_PSICOLOGIA  || $var->nid_sistema == ID_SISTEMA_JUEGOS       ) {
                $not_app_style    = 'mdl-app__none';
                $not_app_title    = 'title="Pr&oacute;ximamente"';
                $onclickHTML      = null;
                $effectHTML       = null;
                $app_icon         = "lock";
            }
            
            $result .= '<div class="mdl-card mdl-app_content '.$not_app_style.'" '.$index.' id="card-main-'.strtolower(str_replace(" ", "_", $var->desc_sist)).'" '.$onclickHTML.' '.$not_app_title.'>
            		          <div class="mdl-card__supporting-text mdl-card__front" '.$effectHTML.'>
            		              <img src="'.base_url().$var->logo_sistema.'">
            		              <div class="mdl-app_text">
            		                  <label>'.$var->desc_sist.'</label>
            		                  <i class="mdi mdi-'.$app_icon.'"></i>
        		                  </div>
            		          </div>
            		          <div class="mdl-card__supporting-text mdl-card__back">
            		              <h4>'.$var->desc_sist.'</h4>
            		              <ul>
            		                  '.$opcionesHTML.'
            		              </ul>
            		          </div>
            		      </div>';
            $contador++;
        }
        return $result;
    }
    
    function rolesSistem($roles, $url, $idSistema, $flg_realizado, $desc_sist) {
        $opciones = null;
        $gris     = null;
        if($flg_realizado == 0){
            $gris = "convertirgris";
        }
    	$rolesByModulo = array();
        if(in_array(ID_ROL_FAMILIA, $roles) ) {
            $familiar = array("nid_rol"  => ID_ROL_FAMILIA   ,
                              "desc_rol" => 'Padre de familia');
            array_push($rolesByModulo, (object) $familiar);
        } else {
            $rolesByModulo = $this->m_usuario->getRolesOnlySistem(_getSesion('nid_persona'), $idSistema);
        }
        $countRoles = count($rolesByModulo);
        if($countRoles > 1) {
            foreach ($rolesByModulo as $rol) {
                $opciones .= '<li data-rippleria data-rippleria-duration="500" onclick="goToSistema(\''._encodeCI($idSistema).'\',\''._encodeCI($rol->nid_rol).'\');"><i class="mdi mdi-open_in_new"></i><label>'.$rol->desc_rol.'</label></li>';
            }
        } else if($countRoles == 1) {
            $opciones = 'onclick="goToSistema(\''._encodeCI($idSistema).'\',\''._encodeCI($rolesByModulo[0]->nid_rol).'\');"';
        }
        return array($opciones, $countRoles);
    }
    
    function contEncuestaPendiente($encuestas) {
        $res = null;
        foreach($encuestas as $enc) {
            $res .= '<div class="col-sm-6">
                         '.$enc->desc_tipo_encuesta.'
                             <br/>
                         '.$enc->desc_enc.'
                     </div>';
        }
        return $res;
    }
    
    function setIdSistemaInSession() {
        $data['err'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idModulo = _decodeCI(_post('id_sis'));
            $idRol     = _decodeCI(_post('rol'));
            if($idModulo == null || $idRol == null) {
                throw new Exception(ANP);
            }
            $url = RUTA_SMILEDU.$this->m_main->getOneSitema($idModulo);
            $data['ses'] = 1;
            $rolSessName = $this->getRolSessionNameByModulo($idModulo);
            $this->session->set_userdata(array($rolSessName => $idRol));
            $data['err']  = EXIT_SUCCESS;
            $data['url']  = $url;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getRolSessionNameByModulo($idModulo) {
        switch ($idModulo) {
            case ID_SISTEMA_SPED      : return SPED_ROL_SESS;      break;
            case ID_SISTEMA_PAGOS     : return PAGOS_ROL_SESS;     break;
            case ID_SISTEMA_NOTAS     : return NOTAS_ROL_SESS;     break;
            case ID_SISTEMA_ADMISION  : return ADMISION_ROL_SESS;  break;
            case ID_SISTEMA_MATRICULA : return MATRICULA_ROL_SESS; break;
            case ID_SISTEMA_SENC      : return SENC_ROL_SESS;      break;
            case ID_SISTEMA_RRHH      : return RRHH_ROL_SESS;    break;
            case ID_SISTEMA_BSC      : return BSC_ROL_SESS;    break;
            default: return 'id_rol'; break;
        }
    }
    
    function enviarFeedBack() {
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje, $url, $nombre);
    }
    	
	function buildContenPublicaciones($publicaciones, $tipoPublicacion) {
	    $panel = null;
	    $cont  = 0;
	    $contm = 0;
	    foreach($publicaciones as $row) {
	        $cont++;
	        $contm++;
	        $idPubliCrypt = _simple_encrypt($row->id_publicacion);
	        $panel .= '<div class="col-xs-12 m-b-10">
            		      <div id="card-mural-'.$tipoPublicacion.'-'.$cont.'" class="mdl-card-mural mdl-card mdl-shadow--2dp">
            			     <div class="mdl-card__title">
            					  <img id="foto_perfil"class="img-circle" src="'.$row->foto_persona.'" alt="Foto">
        						  <h2 class="mdl-card__title-text date">'.$row->audi_pers_regi.'</h2>
    						      <h5 class="mdl-card__title-text " > '.$this->get_timeago(strtotime($row->audi_fec_regi)).'</h5>                    						            						       
            				 </div>
            				 <div class="mdl-card__supporting-text p-0 p-r-20 p-l-20">
        					      <label class="comentario text-justify" style="word-break: break-all;" id="'.$cont.'">
            						    '.$row->comentario.'
						          </label>
            					  <div class="mdl-options text-right m-t-15 m-b-15">
                                        <button class="mdl-button mdl-js-button mdl-button--icon p-0 " onclick="darLike($(this),\''.$idPubliCrypt.'\');" > <i class="mdi mdi-favorite"></i><div class="ripple"></div></button>
            							<span class="span-like">'.$row->nro_likes.'</span>
            							<button class="mdl-button mdl-js-button mdl-button--icon p-0"  onclick="mostrarComentarios(\'card-comments-'.$tipoPublicacion.'-'.$cont.'\')"> <i class="mdi mdi-forum"></i></button>
            							<span class="span-commet">'.$row->nro_comentarios.'</span>
        						  </div>
            				 </div>

            				 <div class="mdl-card__actions p-0" style="display: none;" id="card-comments-'.$tipoPublicacion.'-'.$cont.'">
                                      <ul class="mdl-list">
                                        '.$this->buildComentariosByPublicacion($row->id_publicacion, $tipoPublicacion).'
    						          </ul>
    						          <div class="mdl-comment">
        						          <img src="'._getSesion('foto_usuario').'" alt="Foto">                    						           
        						          <div class="mdl-textfield mdl-js-textfield">
        						              <textarea class="mdl-textfield__input" id="comment-'.$tipoPublicacion.'-'.$cont.'" type="text" rows="2"></textarea>
        						              <label class="mdl-textfield__label" for="comment-'.$tipoPublicacion.'-'.$cont.'">Comentario</label>
        						          </div>
        						          <button class="mdl-button mdl-js-button mdl-button--icon"><i class="mdi mdi-send"></i></button>
    						          </div>
				             </div>
        						                  
    						 <div class="mdl-card__menu">
    						      <button  id="menu-'.$tipoPublicacion.'-'.$cont.'"class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
    									<i class="mdi mdi-more_vert"></i>
    							  </button>
    							  <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-'.$tipoPublicacion.'-'.$cont.'">
            							<li class="mdl-menu__item"><i class="mdi mdi-edit"></i> Modificar</li>
            							<li class="mdl-menu__item"><i class="mdi mdi-delete"></i> Eliminar</li>
    							  </ul>
							 </div>
            		      </div>
					   </div>';
	    }
	    return $panel;
	}
	
	public function publicar() {
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    try{
	        $comentario    = utf8_decode(strip_tags(trim($this->input->post('comentario'))));
	        $tipMural      = $this->input->post('tipMural');
	        if($tipMural != MURAL_PUBLICO && $tipMural != MURAL_ESTRELLA && $tipMural != MURAL_DOCENTE) {
	            throw new Exception(ANP);
	        }
	        $personaSist   = ucwords(strtolower($this->session->userdata('nombre_completo')));
	        $pin = 0;
	        if($tipMural != MURAL_PUBLICO) {
	            $roles = array();
	            $tieneRolMuralEstrella = false;
	            foreach($this->session->userdata('roles') as $row) {
	                array_push($roles, $row->nid_rol);
	                if(in_array($row->nid_rol, json_decode(ROLES_PUBLICAR_MURAL_ESTRELLA)) ) {
	                    $tieneRolMuralEstrella = true;
	                }
	            }
	            //SOLO PUEDEN PUBLICAR EN EL MURAL DE ESTRELLA LOS ROLES DEFINIDOS EN LA CONSTANTE ROLES_PUBLICAR_MURAL_ESTRELLA
	            if($tipMural == MURAL_ESTRELLA && !$tieneRolMuralEstrella ) {
	                throw new Exception(ANP);
	                 
	            }
	            //SOLO PUEDEN PUBLICAR EN EL MURAL_DOCENTE AQUELLOS QUE TIENEN EL ROL DOCENTE
	            if($tipMural == MURAL_DOCENTE && !in_array(ID_ROL_DOCENTE, $roles) ) {
	                throw new Exception(ANP);
	            }
	        }
	         
	        $arrayInsert = array("comentario"      => $comentario,
	            "audi_usua_regi"  => $this->session->userdata('nid_persona'),
	            "audi_pers_regi"  => $personaSist,
	            "audi_fec_regi"   => date('Y-m-d H:i:s'),
	            "nro_likes"       => 0,
	            "nro_comentarios" => 0,
	            "tipo_mural"      => $tipMural);
	        $data = $this->m_mural->publicar($arrayInsert);
	        if($data['error'] == EXIT_SUCCESS) {
	            $dataImage = $this->subirImagenes($_FILES, $data['id_publicacion']);
	            $publicacion = $this->m_mural->getPublicacion($data['id_publicacion']);
	            $data['publicacion'] = $this->buildContenPublicaciones($publicacion, $tipMural);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function get_timeago( $ptime ) {
	    $estimate_time = time() - $ptime;
	    if( $estimate_time < 1 ) {
	        return 'hace un momento';
	    }
	    $condition = array(
	        12 * 30 * 24 * 60 * 60  =>  'a&ntilde;o',
	        30 * 24 * 60 * 60       =>  'mes',
	        24 * 60 * 60            =>  'd&iacute;a',
	        60 * 60                 =>  'hora',
	        60                      =>  'minuto',
	        1                       =>  'segundo'
	    );
	    foreach( $condition as $secs => $str ) {
	        $d = $estimate_time / $secs;
	        if( $d >= 1 ) {
	            $r = round( $d );
	            return 'hace ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . '';
	        }
	    }
	}
	
	function buildComentariosByPublicacion($idPublicacion, $tipoMural) {
	    $comentariosHTML = null;
	    $comentarios = $this->m_mural->getPublicaciones(0, $tipoMural, 0, $idPublicacion);
	    $val = 0;
	    foreach ($comentarios as $row) {
	        $val++;
	        $comentariosHTML .= '<li class="mdl-list__item mdl-list__item--three-line">
                                        <span class="mdl-list__item-primary-content text-left" id="list-comment-'.$idPublicacion.'-'.$val.'">
            					               <img class="mdl-list__item-avatar" src="'.$row->foto_persona.'" alt="Foto">
            					               <span>'.$row->audi_pers_regi.'</span>
        					                   <span class="mdl-list__item-text-body">
        					                       <small> '.$row->audi_fec_regi.'</small>
                					               <p>'.$row->comentario.'</p>
        					                   </span>
                                          </span>
                                          <span class="mdl-list__item-secondary-content ">
                                            <button id="comentario" class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" href="#" onclick="darLike($(this));" ><i class="mdi mdi-favorite"></i></button>
                                          </span>
                                     </li> ';
	    }
	    return $comentariosHTML;
	}
	
	function createFab() {
	    $tab = _post('tab');
	    $menu = null;
	    if($tab == 'fab-'.MURAL_HOME) {
	        $menu = '';
	    } else if($tab == 'fab-'.MURAL_PUBLICO) {
	        $menu = '<li class="mfb-component__wrap">
                        <a href="javascript:void(0);" class="mfb-component__button--main" data-toggle="modal" data-target="#modalNuevaPublicacion">
                            <i class="mfb-component__main-icon--resting mdi mdi-edit" style="transform: rotate(0);"></i>
                            <i class="mfb-component__main-icon--active mdi mdi-edit"></i>
                        </a>
                    </li>';
	    } else if($tab == 'fab-'.MURAL_DOCENTE) {
	        $menu = '';
	    } else if($tab == 'fab-'.MURAL_ESTRELLA) {
	        $menu = $menu = '<li class="mfb-component__wrap">
                        <a href="javascript:void(0);" class="mfb-component__button--main" data-toggle="modal" data-target="#modalNuevaPublicacion">
                            <i class="mfb-component__main-icon--resting mdi mdi-edit" style="transform: rotate(0);"></i>
                            <i class="mfb-component__main-icon--active mdi mdi-edit"></i>
                        </a>
                    </li>';
	    }
	    echo $menu;
	}
	
	public function subirImagenes($files, $id) {
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    try {
	        $c = 0;
	        $arrayBatch = array();
	        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|ppt|docx|doc||xls';
	        $config['max_size'] = '1024';
	        $this->load->library('upload');
	        if(count($files) == 0) {
	            throw new Exception('Debe seleccionar una imagen');
	        }
	        foreach ($files as $fieldname => $fileObject) {
	            $ext = pathinfo($fileObject['name'], PATHINFO_EXTENSION);
	            $nombreFoto = __generateRandomString(6).'_'.date("dmhis").$c;
	            $nombreFotoCompleto = $nombreFoto.'.'.$ext;
	            $tipo = null;
	            $path = null;
	            if($ext == 'doc' || $ext == 'ppt' || $ext == 'pdf' || $ext == 'xls') {
	                $tipo = TIPO_DOCUMENTO;
	                $path = DOCUMENTO_MURAL_PATH;
	            }else if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'jpeg' || $ext == 'png') {
	                $tipo = TIPO_IMAGEN;
	                $path = FOTO_MURAL_PATH;
	            }
	            $config['upload_path'] = $path;
	            $config['file_name']   = $nombreFotoCompleto;
	            if (!empty($fileObject['name'])) {
	                $this->upload->initialize($config);
	                if (!$this->upload->do_upload($fieldname)) {
	                    throw new Exception(utf8_decode($this->upload->display_errors()));
	                }
	                $c++;
	                $arrayInsert = array(
	                    "extension"       => $ext,
	                    "ruta"            => $nombreFotoCompleto,
	                    "nombre_archivo"  => $fileObject['name'],
	                    "_id_publicacion" => $id,
	                    "tipo"            => $tipo
	                );
	                array_push($arrayBatch, $arrayInsert);
	            } else {
	                throw new Exception('Seleccione un archivo');
	            }
	        }
	        if($c == count($_FILES)) {
	            $data = $this->m_mural->insertImagenMural($arrayBatch);
	        }
	    } catch(Exception $e) {
	        $data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
	
	function getSistemasByFiltro() {
	    $search     = _post('search');
	    $finalRoles = array();
	    $roles      = _getSesion('roles');
	    foreach($roles as $rol) {
	        array_push($finalRoles, $rol->nid_rol);
	    }
	    $rolSistemas = $this->m_main->getSistemasByRol($finalRoles,$search);
	    $data['sistemas'] = $this->createSistemas_x_rol($rolSistemas, $finalRoles);
	    echo json_encode(array_map('utf8_encode', $data));
	}
}