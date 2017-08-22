<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."third_party\\facebook\\autoload.php";
require_once APPPATH."third_party//Google//autoload.php";
//require(APPPATH.'third_party//outlook//oauth.php');
//require(APPPATH.'third_party//outlook//outlook.php');
require_once 'HTTP/Request2.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRedirectLoginHelper;

class Padres extends CI_Controller {
    
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
    }
    
	public function index() {
	    $logeoUsario = _getSesion('usuario');
	    $data = array();
	    $data['sedes'] = __buildComboSedes();
	    if($logeoUsario == null) {
	        //$this->session->sess_destroy();

	        $cookie_name  = "userp";
	        $cookie_name1 = "passp";
	        $cookie_name2 = "checkp";
	        if(isset($_COOKIE[$cookie_name2])) {
	            $usuario  = _simple_decrypt($_COOKIE[$cookie_name]);
	            $password = _simple_decrypt($_COOKIE[$cookie_name1]);
	            $check    = $_COOKIE[$cookie_name2];
	            
	            $data['usuarioLogin']  = $usuario;
	            $data['passwordLogin'] = $password;
	            $data['checkLogin']    = $check;
	        }
	        //$this->initFB();
	        $data += $this->initFB();
	        $data += $this->initGoogle();
	        //$data += $this->initOutlook();
	        $this->load->view('v_login_padres', $data);
	    } else {
	        Redirect(_getSesion('urlEncuestaPadres'));
	    }
	}
	
	function logearPadres() {
	    $data['err']      = EXIT_ERROR;
	    $user     = _post('user');
	    $password = _post('pass');
	    $sede     = _decodeCI(_post('sede'));
	    $remember = _post('check');
	    if($sede == null) {
	        $data['error']    = '<p style="font-size: 12px;color:#f44336;margin-right:-8px">
        				             <label style="float:left">Seleccione la sede</label>
        				          </p>';
	        $data['sw'] = 1;
	    }
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
	        $varia = $this->m_usuario->loginPadresFamilia(trim($user), $password, $sede);
	        if($varia != null ) {
	            $data['err'] = EXIT_SUCCESS;
	            $familiar = array("nid_rol"  => ID_ROL_FAMILIA,
            	                  "desc_rol" => 'Padre de familia');
                $roles    = array();
                array_push($roles, (object)$familiar);
	            $dataUser = array('nid_persona'      => $varia['id_familiar'],
	                              'cod_familia_temp' => $varia['cod_familia_temp'],
	                              'cod_familiar'     => $varia['cod_familia'],
	                              'usuario'          => $varia['usuario_edusys'],
	                              'foto_usuario'     => $varia['foto_persona'],
	                              'usuarioMenu'      => $varia['nombre_abvr'],
	                              'nombre_abvr'      => $varia['familia_name'],
	                              'nombre_completo'  => $varia['nombre_abvr'],
	                              'roles'            => $roles
	            );
	            if($remember == '1') {
	                 $password = _simple_encrypt($password);
	                 $user     = _simple_encrypt($user);
	                 $cookie_name = "userp";
	                 $cookie_value = $user;
	                 setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	                 $cookie_name1 = "passp";
	                 $cookie_value1 = $password;
	                 setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30), "/");
	                 $cookie_name2 = "checkp";
	                 $cookie_value2 = "checked";
	                 setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	            } else {
	                $cookie_name = "userp";
	                $cookie_value = "";
	                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	                $cookie_name1 = "passp";
	                $cookie_value1 = "";
	                setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30), "/");
	                $cookie_name2 = "checkp";
	                $cookie_value2 = "";
	                setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
	            }
	            /////////////////
				$this->session->set_userdata($dataUser);
	            /*$url = RUTA_SMILEDU.'senc/c_encuesta_nueva/c_encuesta_efqm?codfam='._encodeCI(_getSesion('cod_familia_temp'));
	            $this->session->set_userdata(array('urlEncuestaPadres' => $url,
	                                               'usuario'           => $user
	                                         ));*/
				
	            $data['url'] = base_url().'c_main';//ENCUESTA
	        } else {
	            $data['error'] = '<p style="font-size: 12px;color:#f44336;margin-right:-8px;float:left">
        				              <a data-toggle="modal" href="#modalCorreo" onclick="openModalCorreo()">¿Olvidaste tu contraseña?</a>
        				          </p>';
	        }
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}

    function initFB() {
        $data['authUrl'] = null;
        $required_scope  = 'public_profile,user_photos,email'; //Permissions required
        
        FacebookSession::setDefaultApplication(APP_ID , APP_PASS);
        $session = _getSesion('FB_SESSION');
        if ($session != null) {
            if(_getSesion('FB_PROFILE') != null) {
                //$user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className())->asArray();
                Redirect('/c_main');
            }
        } else {
            $helper = new FacebookRedirectLoginHelper(REDIRECT_URI_LOGIN_PPFF);
            try {
                $session = $helper->getSessionFromRedirect();
                _setSesion(array('FB_SESSION' => $session) );
            } catch(FacebookRequestException $ex) {
                die(" 1Error : " . $ex->getMessage());
            } catch(\Exception $ex) {
                die(" 2Error : " . $ex->getMessage());
            }
            if ($session != null) {
                $user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className())->asArray();
                $userPicture = (new FacebookRequest($session,
                    'GET',
                    '/'.$user_profile['id'].'/picture',
                    array ('redirect' => false,'type' => 'large')))->execute()->getGraphObject()->asArray();
                $user_profile['picture'] = $userPicture["url"];
                $user_profile['nombres'] = $user_profile['name'];
                _setSesion(array('FB_PROFILE' => $user_profile) );
                if($user_profile['email'] != null) {
                    $this->sessionInitRedSocial($user_profile, FACEBOOK);
                } else {
                    _unsetSesion('FB_PROFILE');
                    _unsetSesion('FB_SESSION');
                    header('Location: ' . filter_var(REDIRECT_URI_LOGIN_PPFF, FILTER_SANITIZE_URL));
                }
            } else {
                $user_profileSess = _getSesion('FB_PROFILE');
                if($user_profileSess != null) {
                    $data = $this->initFBAux($user_profileSess, $data);
                } else {
                    $data['authUrl'] = $helper->getLoginUrl( array( 'scope' => $required_scope ) );
                }
            }
        }
        return $data;
    }
    
    function sessionInitRedSocial($user_profileSess, $redSocial) {
        $userLogged = $this->m_usuario->loginPadresByEmail($user_profileSess['email']);
        if($userLogged != null) {
            $familiar = array(
                'nid_rol'  => ID_ROL_FAMILIA,
                'desc_rol' => 'Padre de familia');
            $roles    = array();
            array_push($roles, (object)$familiar);
            $dataUser = array(
                'nid_persona'      => $userLogged['id_familiar'],
                'cod_familia_temp' => $userLogged['cod_familia_temp'],
                'cod_familiar'     => $userLogged['cod_familia'],
                'usuario'          => $userLogged['usuario_edusys'],
                'foto_usuario'     => ($redSocial == OUTLOOK ? $userLogged['foto_persona'] : $user_profileSess['picture']),
                'usuarioMenu'      => $user_profileSess['nombres'],
                'nombre_abvr'      => $user_profileSess['nombres'],
                'roles'            => $roles
            );
            _setSesion($dataUser);
            if($redSocial == FACEBOOK) {
                $arrayUpdate = array(
                    'facebook_id'      => $user_profileSess['id'],
                    'facebook_link'    => $user_profileSess['link'],
                    'facebook_picture' => $user_profileSess['picture']
                );
            } else if($redSocial == GOOGLE) {
                $arrayUpdate = array(
                    'google_id'      => $user_profileSess['id'],
                    'google_picture' => $user_profileSess['picture'],
                    'google_link'    => $user_profileSess['link'],
                );
            } else if($redSocial == OUTLOOK) {
                $arrayUpdate = array(
                    'outlook_id'      => $user_profileSess['id'],
                    //'outlook_picture' => $user_profileSess['picture'],
                    'outlook_email'   => $user_profileSess['email'],
                );
            }
            $this->m_utils->updateTabla_2('familiar', 'id_familiar', $userLogged['id_familiar'], $arrayUpdate);
            Redirect('/c_main');
        } else {
            _unsetSesion('FB_PROFILE');
            _unsetSesion('FB_SESSION');
            _unsetSesion('access_token');
            header('Location: ' . filter_var(REDIRECT_URI_LOGIN_PPFF, FILTER_SANITIZE_URL));
        }
    }
    
    function initGoogle() {
        $data = array();
        $client = new Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(REDIRECT_URI_LOGIN_PPFF);
        $client->addScope("email");
        $client->addScope("profile");
         
        $service = new Google_Service_Oauth2($client);
        if (_get('code') != null) {
            try {
                $client->authenticate(_get('code'));
                _setSesion(array('access_token' => $client->getAccessToken()));
            } catch (Exception $e) {
                //_log('eerr:: '.$e->getMessage());
            }
        }
        if (_getSesion('access_token') != null ) {
            $client->setAccessToken(_getSesion('access_token'));
        } else {
            $authUrl = $client->createAuthUrl();
            $data['authUrlGoogle'] = $authUrl;
        }
        if(!isset($authUrl)) {
            $user = $service->userinfo->get();
            _unsetSesion('error_google');
            $user_profile= array(
                'id'      => $user->id,
                'picture' => $user->picture,
                'nombres' => $user->name,
                'link'    => $user->link,
                'email'   => $user->email
            );
            $this->sessionInitRedSocial($user_profile, GOOGLE);
        }
        return $data;
    }
    
    function initOutlook() {
        $auth_code = _get('code');
        $tokens = array();
        if($auth_code != null) {
            try {
                $tokens = oAuthService::getTokenFromAuthCode($auth_code, REDIRECT_URI_LOGIN_PPFF);
            } catch (Exception $e) {
                //_log('eerr Outlook:: '.$e->getMessage());
            }
        }
        $data = array();
        //_log('tokens: '.print_r($tokens, true));
        if (isset($tokens['access_token'])) {
            $expiration = time() + $tokens['expires_in'] - 300;
            _setSesion(
                array(
                    'access_token_outlook'  => $tokens['access_token'],
                    'refresh_token_outlook' => $tokens['refresh_token'],
                    'token_expires_outlook' => $expiration
            ));
            $user = OutlookService::getUser($tokens['access_token']);            
            if(isset($user['EmailAddress']) && $user['EmailAddress'] != null) {
                $user_profile= array(
                    'id'      => $user['Id'],
                    //'picture' => $user[''],
                    'nombres' => $user['DisplayName'],
                    'email'   => $user['EmailAddress']
                );
                $this->sessionInitRedSocial($user_profile, OUTLOOK);
            }
        } else {
            $data['authUrlOutlook'] = oAuthService::getLoginUrl(REDIRECT_URI_LOGIN_PPFF);
        }
        return $data;
    }
}