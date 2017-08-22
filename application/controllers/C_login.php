<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."third_party//Google//autoload.php";

class C_login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->helper('cookie');
        $this->load->library('google');
        if(_getSesion('id_familiar') != null) {//Es un padre tratando de entrar a smiledu
            redirect('padres', 'refresh');
        }
    }
    
	public function index() {
	    /*$client  = $this->google->getClient();
	    $service = new Google_Service_Drive($client);
	    $resp    = $this->google->insertFile($client, $service, 'text/plain', base_url().'public/files/texto.txt', 'holaa');
	    */
	    $this->session->set_userdata(array('error_google' => null) );
	    $logeoUsario = _getSesion('usuario');
	    if($logeoUsario == null) {
	        $data = array();
	        $data = $this->initGoogle($data);
	        //$this->session->sess_destroy();
	        $cookie_name  = "user";
	        $cookie_name1 = "pass";
	        $cookie_name2 = "check";
	        if(isset($_COOKIE[$cookie_name2])) {
	            $usuario  = _simple_decrypt($_COOKIE[$cookie_name]);
	            $password = _simple_decrypt($_COOKIE[$cookie_name1]);
	            $check    = $_COOKIE[$cookie_name2];
	            
	            $data['usuarioLogin']  = $usuario;
	            $data['passwordLogin'] = $password;
	            $data['checkLogin']    = $check;
	        }
	        $this->load->view('v_login', $data);
	    } else {
	        Redirect('/c_main');
	    }
	}
	
	function getClient() {
	    $client = new Google_Client();
	    $client->setApplicationName("Prueba");
	    $client->addScope(array("https://www.googleapis.com/auth/drive.apps.readonly","https://www.googleapis.com/auth/.appdata","https://www.googleapis.com/auth/drive.files","https://www.googleapis.com/auth/drive"));
	    
	    $client->setAuthConfigFile(__DIR__ . '\client_secret.json');
	    $accessToken = file_get_contents(__DIR__ . '\drive-php-quickstart.json');
	    $client->setAccessToken($accessToken);
	    
	    if ($client->isAccessTokenExpired()) {
	        $client->refreshToken($client->getRefreshToken());
	        file_put_contents(__DIR__ . '\drive-php-quickstart.json', $client->getAccessToken());
	    }
	    $service = new Google_Service_Drive($client);
	    
	    if (isset($_REQUEST['logout'])) {
	        unset($_SESSION['upload_token']);
	    }
	    
	    if (isset($_GET['code'])) {
	        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
	        $client->setAccessToken($token);
	        $_SESSION['upload_token'] = $token;
	        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	    }
	    
	    if (!empty($_SESSION['upload_token'])) {
	        $client->setAccessToken($_SESSION['upload_token']);
	        if ($client->isAccessTokenExpired()) {
	            unset($_SESSION['upload_token']);
	        }
	    } else {
	        $authUrl = $client->createAuthUrl();
	    }
	    
	    return $client;
	}
	
	public function log() {
	    $data['err']      = EXIT_ERROR;
	    $user     = utf8_decode(_post('user'));
	    $password = utf8_decode(_post('pass'));
	    $remember = $this->input->post('check');
	    if($user == null && $password == null) {
	        $data['error']    = '<p style="font-size: 12px;color:#f44336;margin-right:-8px">
        				             <label style="float:left">Ingrese usuario y/o contrase&ntilde;a</label>
        				          </p>';
	        $data['sw'] = 1;
	    } else if($password == null) {
	        $data['error']    = '<p style="font-size: 12px;color:#f44336;margin-right:-8px">
        				             <label style="float:left">Una contrase&ntilde;a es requerida</label>
        				         </p>';
	        $data['sw'] = 2;
	    } else {
	        $ingreso = $this->m_usuario->getIngreso(utf8_decode(trim($user)), $password);
	        if($ingreso['personal'] == 1){
	            $varia = $this->m_usuario->getUsuarioLogin(utf8_decode(trim($user)), $password);
	            if($varia != null ) {
	                $data['err'] = EXIT_SUCCESS;
	                $roles = $this->m_usuario->getRolesByuser($varia['nid_persona']);
	                $dataUser = array("usuario"         => $varia['usuario'],//PARA EL MANEJO DE DATOS
                	                  "usuarioMenu"     => __getDescReduce($varia['usuario'],20),//PARA EL MENU
                	                  "nid_persona"     => $varia['nid_persona'],
                	                  "nombre_abvr"     => $varia['nombre_abvr'],
                	                  "id_sede_trabajo" => $varia['id_sede_control'],
                	                  "font_size"       => ($varia['font_size'] != null ) ? $varia['font_size'] : null,
                	                  "nombre_completo" => $varia['nom_persona'].' '.$varia['ape_pate_pers'].' '.$varia['ape_mate_pers'],
                	                  "foto_usuario"    => $varia['foto_persona'],
                	                  "roles"           => $roles);
	                if($remember == '1') {
	                    $password = _simple_encrypt($password);
	                    $user     = _simple_encrypt($user);
	                    $cookie_name = "user";
	                    $cookie_value = $user;
	                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	                    $cookie_name1 = "pass";
	                    $cookie_value1 = $password;
	                    setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30), "/");
	                    $cookie_name2 = "check";
	                    $cookie_value2 = "checked";
	                    setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	                } else {
	                    $cookie_name = "user";
	                    $cookie_value = "";
	                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	                    $cookie_name1 = "pass";
	                    $cookie_value1 = "";
	                    setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30), "/");
	                    $cookie_name2 = "check";
	                    $cookie_value2 = "";
	                    setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	                }
	                /////////////////
	                $this->session->set_userdata($dataUser);
	                $data['url'] = base_url().'c_main';
                    /*Redireccionamiento a encuestas
                     * if($varia['redirect_encuesta'] == 1){
                        $data['url'] = base_url().'senc/c_encuesta_nueva/c_encuesta_efqm';
                    }*/
	            } else {
	                $data['error'] = '<p style="font-size: 12px;color:#f44336;margin-right:-8px;float:left">
            				              <a data-toggle="modal" href="#modalCorreo" onclick="openModalCorreo()">¿Olvidaste tu contrase&ntilde;a?</a>
            				          </p>';
	            }
	        } else if ($ingreso['familiar'] == 1){
	            $varia = $this->m_usuario->getUsuarioLoginFamiliar(utf8_decode(trim($user)), $password);
	            if($varia != null){
	                $data['err'] = EXIT_SUCCESS;
	                $familiar = array("nid_rol"  => ID_ROL_FAMILIA,
	                                  "desc_rol" => 'Padre de familia');
                    $roles    = array();
	                array_push($roles, (object)$familiar);
	                $dataUser = array("usuario"         => $varia['usuario'],//PARA EL MANEJO DE DATOS
                	                  "usuarioMenu"     => __getDescReduce($varia['usuario'],20),//PARA EL MENU
                	                  "nid_persona"     => $varia['id_familiar'],
                	                  "nombre_abvr"     => $varia['nombre_abvr'],
                	                  "nombre_completo" => $varia['nombres'].' '.$varia['ape_paterno'].' '.$varia['ape_materno'],
                	                  "foto_usuario"    => ((file_exists(FOTO_PROFILE_PATH.'familiares/'.$varia['foto_persona'])) ? RUTA_IMG_PROFILE.'familiares/'.$varia['foto_persona'] : RUTA_IMG_PROFILE."nouser.svg"),
                	                  "cod_familiar"    => $varia['cod_familiar'],
                	                  "roles"           => $roles);
	                if($remember == '1') {
	                    $password = _simple_encrypt($password);
	                    $user     = _simple_encrypt($user);
	                    $cookie_name = "user";
	                    $cookie_value = $user;
	                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	                    $cookie_name1 = "pass";
	                    $cookie_value1 = $password;
	                    setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30), "/");
	                    $cookie_name2 = "check";
	                    $cookie_value2 = "checked";
	                    setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	                } else {
	                    $cookie_name = "user";
	                    $cookie_value = "";
	                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	                    $cookie_name1 = "pass";
	                    $cookie_value1 = "";
	                    setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30), "/");
	                    $cookie_name2 = "check";
	                    $cookie_value2 = "";
	                    setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	                }
	                /////////////////
	                $this->session->set_userdata($dataUser);
	                $data['url'] = base_url().'c_main';
	            } else {
	                $data['error'] = '<p style="font-size: 12px;color:#f44336;margin-right:-8px;float:left">
        				              <a data-toggle="modal" href="#modalCorreo" onclick="openModalCorreo()">ï¿½Olvidaste tu contraseï¿½a?</a>
        				          </p>';
	            }
	        }
	        
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
  
    function roles($idUser,$idSistema){
        $roles = $this->m_usuario->getRolesOnlySistem($idUser,$idSistema);
        $datos = null;
        $datos .= '<div id="move" style="height:24px; margin-bottom:15px;">';
        $mas   = 0;
        foreach ($roles as $rol){
            $id     = $mas;
            $idRol  = $this->encrypt->encode($rol->nid_rol);
            $datos .= '<div>';
            $datos .= '<a href="javascript:void(0)" onclick="log($(this));">
                           <input id="'.$id.'" value="'.$idRol.'" type="hidden" class="rol">
                           <label>'.$rol->desc_rol.'</label>
                       </a>';
            $datos .= '</div>';
            $mas++;
        }
        $datos .= '</div>';
        return $datos;
    }
    
    function allSistem(){
        $sistemas = $this->m_usuario->getAllSistem();
        $slider   = null;
        $slider = '<div id="carrusel" class="sky-carousel">
                    <div class="sky-carousel-wrapper">
        			<ul class="sky-carousel-container">
				';
        $mas      = 0;
        foreach ($sistemas as $sistema){
            if($sistema->nid_sistema != 16){
                $id      = $mas;
                $idSist  = $this->encrypt->encode($sistema->nid_sistema);
                $slider .= '<li>
                            <a href="javascript:void(0);" ">
                                <img src="'.base_url().$sistema->logo_sistema_c.'" alt="'.$sistema->orden.'" title="'.$sistema->desc_sist.'"/>
                            </a>';
                $slider .= '</li>';
                $mas++;
            } 
        }
        $slider  .= '    </ul>
		             </div>
            </div>';
        return $slider;
    }
    
    function getDescripcionSistem(){
        $orden = $this->input->post('orden');
        $desc = $this->m_usuario->getDescripcionBySistema($orden);
        
        echo $desc;
    }
    
    function enviarCorreoUsuario() {
        $data ['error'] = EXIT_ERROR;
        $data ['msj']   = null;
        $this->db->trans_begin();
        try {
            $correoUsuario = _post('correoUsuario');
            if (filter_var($correoUsuario, FILTER_VALIDATE_EMAIL)) {
                $persona = $this->m_usuario->getCorreoByUsuario('correo_inst', $correoUsuario);
                if($persona['correo_inst'] == null) {
                    $persona = $this->m_usuario->getCorreoByUsuario('correo_admi', $correoUsuario);
                }
            } else {
                $persona = $this->m_usuario->getCorreoByUsuario('usuario', $correoUsuario);
            }
            if($persona == null) {
                throw new Exception('No encontramos ese correo/usuario :O');
            }
            $correo = ($persona['correo_inst'] == null) ? $persona['correo_admi'] : $persona['correo_inst'];
            if($correo == null) {
                throw new Exception('El correo no existe.');
            }
            $fechaHoraActual = date('d/m/Y H:i:s');
            $fecHora = _encodeCI($fechaHoraActual);
            $url = base_url().'by_pass?c='._encodeCI($correo).'&fh='.$fecHora.'&cod='._encodeCI($persona['nid_persona']);
            $asunto = 'Hola '.$persona['nombre_solo'].', solicitaste cambiar tu clave de la plataforma Smiledu';
            $datosCorreo = array(
                'nombres' => $persona['persona'],
                'url'     => $url
            );
            $body = __bodyMensajeCambiarClave($datosCorreo);
            $this->m_utils->updateTabla('persona', 'nid_persona', $persona['nid_persona'], 'fec_hora_cambio_clave', $fechaHoraActual);
            $datosInsert = array(
                'correos_destino' => $correo,
                'asunto'          => $asunto,
                'body'            => $body,
                'sistema'         => 'BASE');
            $data = $this->m_utils->insertarEnviarCorreo($datosInsert);
            $this->db->trans_commit();
            $data ['msj'] = 'Te enviamos un correo para restablecer tu clave :)';
        } catch (Exception $e) {
            $data ['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function initGoogle($data) {
        $data['google'] = 'SI';
        $client = new Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(REDIRECT_URI);
        $client->addScope("email");
        $client->addScope("profile");
         
        $service = new Google_Service_Oauth2($client);
         
        if($this->input->get('state') == null) {//NO ES FB
            if (isset($_GET['code'])) {
                $client->authenticate($_GET['code']);
                $this->session->set_userdata(array('access_token' => $client->getAccessToken()) );
            }
        }
        
        if ($this->session->userdata('access_token') ) {
            $client->setAccessToken($this->session->userdata('access_token'));
        } else {
            $authUrl = $client->createAuthUrl();
        }
        $data['html_google'] = null;
        if(isset($authUrl)) {
            $data['html_google'] = '
                                <button type="button" class="google-button mdl-shadow--2dp" onclick="logRed($(this));" data-href_url="'.$authUrl.'" >
                                  <span class="google-button__icon">
                                      <svg viewBox="0 0 366 372"><path d="M125.9 10.2c40.2-13.9 85.3-13.6 125.3 1.1 22.2 8.2 42.5 21 59.9 37.1-5.8 6.3-12.1 12.2-18.1 18.3l-34.2 34.2c-11.3-10.8-25.1-19-40.1-23.6-17.6-5.3-36.6-6.1-54.6-2.2-21 4.5-40.5 15.5-55.6 30.9-12.2 12.3-21.4 27.5-27 43.9-20.3-15.8-40.6-31.5-61-47.3 21.5-43 60.1-76.9 105.4-92.4z" id="Shape" fill="#EA4335"></path><path d="M20.6 102.4c20.3 15.8 40.6 31.5 61 47.3-8 23.3-8 49.2 0 72.4-20.3 15.8-40.6 31.6-60.9 47.3C1.9 232.7-3.8 189.6 4.4 149.2c3.3-16.2 8.7-32 16.2-46.8z" id="Shape" fill="#FBBC05"></path><path d="M361.7 151.1c5.8 32.7 4.5 66.8-4.7 98.8-8.5 29.3-24.6 56.5-47.1 77.2l-59.1-45.9c19.5-13.1 33.3-34.3 37.2-57.5H186.6c.1-24.2.1-48.4.1-72.6h175z" id="Shape" fill="#4285F4"></path><path d="M81.4 222.2c7.8 22.9 22.8 43.2 42.6 57.1 12.4 8.7 26.6 14.9 41.4 17.9 14.6 3 29.7 2.6 44.4.1 14.6-2.6 28.7-7.9 41-16.2l59.1 45.9c-21.3 19.7-48 33.1-76.2 39.6-31.2 7.1-64.2 7.3-95.2-1-24.6-6.5-47.7-18.2-67.6-34.1-20.9-16.6-38.3-38-50.4-62 20.3-15.7 40.6-31.5 60.9-47.3z" fill="#34A853"></path></svg>
                                  </span>
                                </button> 
                                <div style="display:none">
	                                <a class="login" href="'.$authUrl.'"><img src="public/general/img/google-login-button.png"/></a>
	                            </div>';
        } else {
            $user = $service->userinfo->get();
            //Verificar si el correo existe en la BD, si existe updatear el id y foto al usuario (tabla persona)
            $personaGoogle = $this->m_usuario->getUsuarioLoginGoogle($user->email);
            if($personaGoogle == null) {
                //El correo no existe en la BD, no se puede linkear
                $this->session->set_userdata(array('access_token' => null) );
                setcookie('error_google', '1', time() + (86400 * 30), "/");
                header('Location: ' . filter_var(REDIRECT_URI, FILTER_SANITIZE_URL));
            } else {//El correo existe, updatear la tabla persona (id_google y google_foto)
                $this->session->set_userdata(array('error_google' => null) );
                if($personaGoogle['id_google'] == null) {//Solo si es la primera vez que linkea el google con el usuario
                    $this->m_usuario->updatearDatosGooglePersona($personaGoogle['nid_persona'], $user->id, $user->picture);
                } else {//Si ya esta linkeado
                    if($personaGoogle['google_foto'] != $user->picture) {//actualizar foto
                        $this->m_usuario->updatearDatosGooglePersona($personaGoogle['nid_persona'], $user->id, $user->picture);
                    }
                }
                //INICIANDO SESION - CORREO LINKEADO TODO OK
                $roles = $this->m_usuario->getRolesByuser($personaGoogle['nid_persona']);
                $dataUser = array("usuario"          => $personaGoogle['usuario'],//PARA EL MANEJO DE DATOS
                                  "usuarioMenu"      => __getDescReduce($personaGoogle['usuario'],20),//PARA EL MENU
                                  "nid_persona"      => $personaGoogle['nid_persona'],
                                  "id_sede_trabajo"  => $personaGoogle['id_sede_control'],
                                  "font_size"        => ($personaGoogle['font_size'] != null ) ? $personaGoogle['font_size'] : null,
                                  "nombre_completo"  => $personaGoogle['nom_persona'].' '.$personaGoogle['ape_pate_pers'].' '.$personaGoogle['ape_mate_pers'],
                                  "roles"            => $roles,
                                  "foto_usuario"     => $user->picture );
                $this->session->set_userdata($dataUser);
                Redirect('/c_main');
            }
        }
        return $data;
    }
}