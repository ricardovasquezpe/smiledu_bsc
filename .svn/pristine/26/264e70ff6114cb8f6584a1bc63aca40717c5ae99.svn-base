<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_mural extends MX_Controller {
    
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->library('lib_utils');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_mural');
        $this->load->model('m_utils');
        $this->load->model('mf_usuario/m_usuario');
        if(!isset($_COOKIE['smiledu'])){
            $this->session->sess_destroy();
            redirect('/c_login','refresh');
        }
    }

	public function index(){
	    $logedUser = $this->session->userdata('usuario');
	    if($logedUser != null){	        
	        $roles = $this->session->userdata('roles');
	    	if($roles != null) {
	    	    $final 	= array();
	    	    foreach($roles as $rol){
	    	        $data['id_rol'] = $rol->nid_rol;
	    	        array_push($final, $rol->nid_rol);
	    	        
	    	    }
	    	    $data['arbolPermisosMantenimiento'] = $this->lib_utils->buildArbolPermisos($final);
	    	    $data['barraSec'] = '  <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                      <a href="#'.MURAL_PUBLICO.'" id="'.MURAL_PUBLICO.'fab" onclick="showFab()" class="mdl-layout__tab is-active">P&uacute;blico</a>
    	                              <a href="#'.MURAL_ESTRELLA.'" id="'.MURAL_ESTRELLA.'fab" onclick="showFab()" class="mdl-layout__tab ">Estrella</a>
	    	                          <a href="#'.MURAL_DOCENTE.'" id="'.MURAL_DOCENTE.'fab" onclick="showFab()" class="mdl-layout__tab ">Docente</a>
                                    </div>';
	    	    //MENU Y CABECERA
	    	    $idRol     = _getSesion('id_rol');
	    	    $rolSistemas   = $this->m_utils->getSistemasByRol($idRol);
    	       $data['apps']  = $this->lib_utils->modalCreateSistemasByrol($rolSistemas);
	    	    
	    	    $menu     = $this->load->view('v_menu_v2', $data, true);
	    	    //$cabecera = $this->load->view('v_cabecera', '', true);
	    	    //$data['cabecera'] = $cabecera;
	    	    $data['menu']     = $menu;
	    	    $data['font_size'] = $this->session->userdata('font_size');
	    	    $flg_fab = MURAL_PUBLICO.'fab';
	    	    $publicacionesEstrella = $this->m_mural->getPublicaciones(0,MURAL_ESTRELLA,1, null);
	    	    $publicacionesPublico  = $this->m_mural->getPublicaciones(0,MURAL_PUBLICO ,1, null);
	    	    $publicacionesDocente  = $this->m_mural->getPublicaciones(0,MURAL_DOCENTE ,1, null);
	    	    $data['publicacionesEstrella'] = $this->buildContenPublicaciones($publicacionesEstrella , 'estrella');
	    	    $data['publicacionesPublicas'] = $this->buildContenPublicaciones($publicacionesPublico, 'publico');
	    	    $data['publicacionesDocente']  = $this->buildContenPublicaciones($publicacionesDocente, 'docente');
	    	    $icono = "scwl-icon_buho_marca";
	    	    foreach($this->session->userdata('roles') as $row){
	    	        if($row->nid_rol == ID_ROL_PROMOTOR || $row->nid_rol == ID_ROL_DIRECTOR){
	    	            $icono = "md md-star";
	    	            $flg_fab = MURAL_ESTRELLA.'fab';
	    	        } else if($rol->nid_rol == ID_ROL_DOCENTE){
	    	            $flg_fab = MURAL_DOCENTE.'fab';
	    	        }
	    	    }
	    	    $data['flg_fab'] = '"'.$flg_fab.'"';
	    	    $data['icono'] = $icono;
	    	    $this->load->view('v_mural', $data);
	    	}
	    }else{
	        $this->session->sess_destroy();
	        redirect('','refresh');
	    }
	}
	
	public function createVistaPublicacion($data){
	    $vista = null;
	    foreach($data as $row){
	        $vista .= $this->createContentPublicacion($row);
	    }
	    
	    return $vista;
	}
	
	function createContentPublicacion($data){
	    $roles = $this->m_usuario->getRolesByuser($data->audi_usua_regi);
	    $iconPubli = 'class="scwl-icon_buho_marca" style="float: right;margin-right: 9px;font-size: 22px;opacity: 0.4;"';
	    foreach($roles AS $rol){
	        if($rol->nid_rol == ID_ROL_PROMOTOR || $rol->nid_rol == ID_ROL_DIRECTOR){
	            $iconPubli = 'class="md md-star" style="font-size: 18px;float: right;margin-right: 5px;margin-top: 3px;opacity: 0.4;"';
	        }
	    }
	    $fecha = ($data->audi_fec_regi == null) ? null : date('d/m/Y h:i:s A',strtotime($data->audi_fec_regi));
	    $idEncrypt = _simple_encrypt($data->id_publicacion);
	    $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
	    $string = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a><br/><br/>', $data->comentario);
	    
	    $vista = '<li>
					<div class="card">
	                    <p style="font-size: 15px;float: right;color:#959595;opacity: 0.7;margin-top: 7px;margin-right: 10px;">Mural</p>
	                    <i '.$iconPubli.'></i>
						<div class="comment-avatar">
	                       <img src="'.$data->foto_persona.'" class="img-circle">
	                    </div>
						<div class="card-body">
							<h4 class="comment-title" style="margin-bottom:20px">'.$data->audi_pers_regi.' <small>'.$fecha.'</small></h4>
							<div class="col-sm-12" style="margin-top:10px"><p>'.utf8_decode($string).'</p></div>';
		
	    if($data->rutas != null){
	        $rutas = explode("|", $data->rutas);
	        $tipos = explode("|", $data->tipos);
	        $exts  = explode("|", $data->extensiones);
	        $nombres  = explode("|", $data->nombres);
	    
	        for ($i = 0; $i < count($rutas); $i++) {
	            if($tipos[$i] == TIPO_IMAGEN){
	                $vista.= $this->createContentImagen($rutas[$i]);
	            }else if($tipos[$i] == TIPO_DOCUMENTO){
	                $vista.= $this->createContentDocument($rutas[$i], $exts[$i], $nombres[$i]);
	            }
	        }
	    }
		
		$vista .='		</div>
						<div class="card-actionbar">
							<div class="card-actionbar-row">
								<a href="javascript:void(0);" class="btn btn-icon-toggle btn-danger ink-reaction pull-right" onclick="like(\''.$idEncrypt.'\', this)"><i class="fa fa-heart" style="font-size: 17px;"> '.$data->nro_likes.'</i></a>
								<a href="javascript:void(0);" class="btn btn-icon-toggle btn-default ink-reaction pull-right" style="margin-right: 10px;"><i class="md md-comment" style="font-size: 17px;margin-top: -1px;"> '.$data->nro_comentarios.'</i></a>
							</div>
						</div>
							    
					</div>
				</li>';
	    
	    return $vista;
	}

	public function createContentImagen($ruta){
	    $img = '<div class="col-sm-3"><img src="'.base_url().'uploads/general/images/foto_mural/'.$ruta.'" class="img-responsive"></div>';
	    
	    return $img;
	}
	
	public function createContentDocument($ruta, $ext, $nombre){
	    $img = '<div class="col-sm-3">
    	            <a href="'.base_url().'uploads/general/documentos/'.$ruta.'" download="'.$nombre.'">
    	               <img src="'.base_url().'public/files/images/mural/file-'.$ext.'.png" class="img-responsive">
    	            </a>    
	            </div>';
	     
	    return $img;
	}
	
	public function publicar(){
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
	
	function getYouTubeIdFromURL($url){
	    $url_string = parse_url($url, PHP_URL_QUERY);
	    parse_str($url_string, $args);
	    return isset($args['v']) ? $args['v'] : false;
	}
	
	function getYouTubeIdFromURLRegex($url){
	    $pattern = '/(?:youtube.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu.be/)([^"&?/ ]{11})/i';
	    preg_match($pattern, $url, $matches);
	
	    return isset($matches[1]) ? $matches[1] : false;
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
	
	        if(count($files) == 0){
	            throw new Exception('Debe seleccionar una imagen');
	        }
	        foreach ($files as $fieldname => $fileObject) {
	            $ext = pathinfo($fileObject['name'], PATHINFO_EXTENSION);
	            $nombreFoto = __generateRandomString(6).'_'.date("dmhis").$c;
	            $nombreFotoCompleto = $nombreFoto.'.'.$ext;
	            $tipo = null;
	            $path = null;
	            if($ext == 'doc' || $ext == 'ppt' || $ext == 'pdf' || $ext == 'xls'){
	                $tipo = TIPO_DOCUMENTO;
	                $path = DOCUMENTO_MURAL_PATH;
	            }else if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'jpeg' || $ext == 'png'){
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
	                
	                $arrayInsert = array("extension"      => $ext,
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
	
	public function getMorePublish(){
	    $row = $this->input->post('row');
	    $tipo = $this->input->post('tipo');
	    $data['error'] = EXIT_ERROR;
	    try{
	        $publicaciones = $this->m_mural->getPublicaciones($row,$tipo);
	        $data['publicaciones'] = $this->createVistaPublicacion($publicaciones);
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage(); 
	    }
	    	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	public function like() {
	    $data['error'] = EXIT_ERROR;
	    try {
	        $id   = _simple_decrypt(_post('id'));
	        $this->m_mural->like($id);
	    } catch(Exception $e) {
	        $data['msj']   = $e->getMessage(); 
	    }
	}
	
	function logOut(){
	    $logedUser = $this->session->userdata('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
	function enviarFeedBack(){
	    $nombre = $this->session->userdata('nombre_completo');
	    $mensaje = $this->input->post('feedbackMsj');
	    $url = $this->input->post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
	/*
	function repaintMural(){
	    $tipo = $this->input->post('tipo');
	    $data['error'] = EXIT_ERROR;
	    try{
	        $publicaciones = $this->m_mural->getPublicaciones(0,$tipo);
	        $data['publicaciones'] = $this->createVistaPublicacion($publicaciones);
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage(); 
	    }
	    	    
	    echo json_encode(array_map('utf8_encode', $data));
	}*/
	
	function buildContenPublicaciones($publicaciones, $tipoPublicacion){
	    $panel = null;
	    $cont = 0;
	    $contm=0;
	   
	    foreach($publicaciones as $row){
	        $cont++;
	        $contm++;
	        $idPubliCrypt = _simple_encrypt($row->id_publicacion);
	        $panel .= '<div class="page-content p-t-15 p-l-0 p-r-0">
        		              <div class="col-xs-12 col-xs-offset-0 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 m-b-15 p-r-0 p-l-0">
                    				<div id="card-mural-'.$tipoPublicacion.'-'.$cont.'" class="mdl-card-mural mdl-card mdl-shadow--2dp">
                    					<div class="mdl-card__title p-r-40 p-l-80 p-t-25">
                    						<img id="foto_perfil" style="width:50px;height:50px;top:10px!important;" class="img-circle" src="'.$row->foto_persona.'" alt="Foto">
                    						<div class="row-fuid" style="width:100%;">
                    						  <div class="col-xs-12 p-0 m-0 m-b-5">
                    						      <h3 class="mdl-card__title-text mdl-typography--body-2 mdl-typography--font-bold" class="date">'.$row->audi_pers_regi.'</h3>
                    						  </div>
                    						  <div class="col-xs-12 p-0 m-0 m-b-5">
                    						      <h5 class="mdl-card__title-text mdl-typography--body-2 mdl-typograph#004062y--font-bold" style="font-size:10px!important;"> '.$this->get_timeago(strtotime($row->audi_fec_regi)).'</h5>  
                    						          
                    						  </div>
                    						</div>            						       
                    					</div>
                    					<div class="mdl-card__supporting-text p-0 p-r-20 p-l-20">
                							<label class="comentario text-justify" style="word-break: break-all;" id="'.$cont.'">
                    						    '.$row->comentario.'          
        						            </label>
                    						<div class="mdl-options text-right m-t-15 m-b-15">
                                                <a class="mdl-button mdl-js-button p-0  href="#" onclick="darLike($(this),\''.$idPubliCrypt.'\');" > <i class="material-icons">favorite</i></a>
                    							<span class="span-like">'.$row->nro_likes.'</span>
                    							<a class="mdl-button mdl-js-button p-0" href="#" onclick="mostrarComentarios(\'card-comments-'.$tipoPublicacion.'-'.$cont.'\')"> <i class="material-icons">comment</i></a>
                    							<span class="span-commet">'.$row->nro_comentarios.'</span>            							       						
                							</div>
                    					</div>
                    					        					
                    					<div class="mdl-card__actions p-0" style="display: none;" id="card-comments-'.$tipoPublicacion.'-'.$cont.'">
                                            <ul class="mdl-list">
                                              '.$this->buildComentariosByPublicacion($row->id_publicacion, $row->tipo_mural).'                              
            						          </ul>  
            						          <div class="mdl-comment text-left " style="padding-top: 12px;padding-bottom: 12px;padding-left: 16px;padding-right: 16px">
                						          <div class="row p-0 m-0"> 
            						                   <div class="col-xs-2 col-sm-1 p-0 m-0">
                        						          <img class="img-circle" src="'._getSesion('foto_usuario').'" alt="Foto">  
                    						           </div>
                    						           <div class="col-xs-7 col-sm-9 p-0 m-0 p-r-5">  
                        						          <div class="mdl-textfield  p-t-0 p-b-0">                        						              
                        						              <textarea class="mdl-textfield__input p-t-0 p-r-0 animated" id="comment"></textarea>                    						          
                        						          </div>  
                    						           </div>
                						               <div class="col-xs-3  col-sm-2 m-0 p-r-0 p-l-0 text-right">      					
                        						          <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-text-right m-0" style="position: relative;padding-top:5px;padding-bottom: 5px;width: 100%;height: 100%;padding-left:0px !important;padding-right:0px !important;">Comentar</button>
                						              </div>
                						           </div>    
        						              </div>            						         
            						      </div> 	
            						      <div class="mdl-card__menu">
            								<button  id="menu-'.$tipoPublicacion.'-'.$cont.'"class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            									<i class="material-icons">more_vert</i>
            								</button>
                								<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-'.$tipoPublicacion.'-'.$cont.'">
                        							<li class="mdl-menu__item"><i class="material-icons p-r-5" style="position: relative;top:5px">edit</i> Modificar</li>
                        							<li class="mdl-menu__item"><i class="material-icons p-r-5" style="position: relative;top:5px">delete</i> Eliminar</li>
                								</ul>
        							      </div>		
                    				</div>
                    				
                    			</div>            			           			
                		  </div>';
	    }
	    return $panel;
	}
	
	function buildComentariosByPublicacion($idPublicacion, $tipoMural){
	    $comentariosHTML = null;
	    $comentarios = $this->m_mural->getPublicaciones(0, $tipoMural, 0, $idPublicacion);
	    foreach ($comentarios as $row){
	        $comentariosHTML .= '<li class="mdl-list__item mdl-list__item--three-line">
                                        <span class="mdl-list__item-primary-content text-left" id="card-comment-1">
            					               <img class="mdl-list__item-avatar" src="'.$row->foto_persona.'" alt="Foto">
            					               <span class="mdl-card__title-text mdl-typography--body-2 mdl-typography--font-bold">'.$row->audi_pers_regi.' <small style="padding-top: 2px;"> '.$row->audi_fec_regi.'</small></span>
            					               <p class="mdl-list__item-text-body text-justify">
                                                   '.$row->comentario.' 
                                               </p>
                                          </span>
                                          <span class="mdl-list__item-secondary-content ">
                                            <a id="comentario" class="mdl-list__item-secondary-action mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" style="position:relative;right:10px" href="#" onclick="darLike($(this));" ><i class="material-icons">favorite</i></a>
                                          </span>                                           
                                     </li> ';
	    }
	    return $comentariosHTML;
	}
	
	function get_timeago( $ptime )
	{
	    $estimate_time = time() - $ptime;
	
	    if( $estimate_time < 1 )
	    {
	        return 'hace un momento';
	    }
	
	    $condition = array(
	        12 * 30 * 24 * 60 * 60  =>  'a�o',
	        30 * 24 * 60 * 60       =>  'mes',
	        24 * 60 * 60            =>  'd�a',
	        60 * 60                 =>  'hora',
	        60                      =>  'minuto',
	        1                       =>  'segundo'
	    );
	
	    foreach( $condition as $secs => $str )
	    {
	        $d = $estimate_time / $secs;
	
	        if( $d >= 1 )
	        {
	            $r = round( $d );
	            return 'hace ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . '';
	        }
	    }
	}
	
}