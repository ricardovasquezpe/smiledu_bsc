<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_perfil extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
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
        $this->load->library('table');
        $this->load->helper('cookie');
        $this->_idUserSess = _getSesion('nid_persona');
        if(!isset($_COOKIE[__getCookieName()])) {
            $this->session->sess_destroy();
            redirect('','refresh');
        }
    }

	public function index() {
	    $data['titleHeader']      = 'Perfil';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
        $data['return']           = '';
        
        $dataPersona = null;
        if(isset($_GET['usuario']) && $_GET['usuario'] != null) {
            $idUsuario   = _simple_decrypt(str_replace(" ","+",$_GET['usuario']));
            if($idUsuario != null && is_numeric($idUsuario)) {
                $dataPersona = $this->m_usuario->getDatosPersona($idUsuario);
            }else{
                redirect('/c_perfil','refresh');
            }
        } else {
            if(_getSesion('cod_familiar') != null) {
                $dataPersona = $this->m_usuario->getDatosFamiliar($this->_idUserSess, _getSesion('cod_familiar'));
            } else {
                $dataPersona = $this->m_usuario->getDatosPersona($this->_idUserSess);
            }
        }
        $data['fotouser'] = ($dataPersona['foto_persona']=='null' ? base_url().'uploads/general/images/foto_perfil/iconpnguser.png' : base_url().$dataPersona['foto_persona']);
        $data['nombrePersona']   = ucwords(strtolower($dataPersona['nom_persona'])); 
        $data['apellidoPatPersona']   = ucwords(strtolower($dataPersona['ape_pate_pers']));
        $data['apellidoMatPersona']   = ucwords(strtolower($dataPersona['ape_mate_pers']));
        $data['correoPersona']   = $dataPersona['correo_pers'];
        $data['correoInst']   = $dataPersona['correo_inst'];
        $data['nombreUsuario']   = $dataPersona['usuario'];
        $fecha = ($dataPersona['fec_naci'] == null) ? null : date('d/m/Y',strtotime($dataPersona['fec_naci']));
        $data['fnaciPersona']    = $fecha;
        $data['nro_documento']   = $dataPersona['nro_documento'];
        $data['sexo']   = $dataPersona['desc_sexo'];
        $data['nro_corazones']   = $dataPersona['nro_corazones'];
        $data['tipoSangre']      = $dataPersona['tipo_sangre'];
        $data['hobby']           = ($dataPersona['hobby']);
        $data['telefonoPersona'] = $dataPersona['telf_pers'];
        $data['sedeTrabajo'] = $dataPersona['desc_sede'];
        $data['tbCumple']        = $this->getCumpleanosMes();
        $data['font_size'] = $this->session->userdata('font_size');
        
        $roles = $this->session->userdata('roles');
        $final 	= array();
        if($roles != null) {
            foreach($roles as $rol) {
                array_push($final, $rol->nid_rol);
            }
            $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase(_getSesion("nid_persona"));
        }
        /*$rolSistemas = $this->m_main->getSistemasByRol($final);*/
        $idRol = _getSesion('id_rol');
        /*data['apps'] = __modalCreateSistemasByRol($rolSistemas);*/
        
        $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_MATENIMIENTO, $this->_idUserSess);
        $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
        //MENU Y CABECERA
        $menu = $this->load->view('v_menu', $data, true);
        //$cabecera = $this->load->view('v_cabecera', '', true);
        //$data['cabecera'] = $cabecera;
        $data['menu'] = $menu;
        $this->load->view('v_perfil', $data);
	}
	
	function guardarInformacionContacto(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try{
	        $telefono = _post("telefono");
	        $correoPers = _post("correopers");
	        $correoInst = _post("correoins");
	        
	        if ($correoInst != null && !filter_var($correoPers, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Ingrese un correo v&aacute;lido");
            }
            if ($correoPers != null && !filter_var($correoInst, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Ingrese un correo v&aacute;lido");
            }
	        
	        $arrayUpdate = array('telf_pers'   => $telefono,
	                             'correo_pers' => $correoPers,
	                             'correo_inst' => $correoInst);
	        $data = $this->m_usuario->updateDatosPersona($arrayUpdate, $this->_idUserSess);
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	public function editarDatosPersona() {
	    $nid_persona = $this->session->userdata('nid_persona');
	    $user        = $this->input->post('usuario');
	    $email       = $this->input->post('email');
	    $fecha       = ($this->input->post('fechaNac') == null || trim($this->input->post('fechaNac')) == "") ? null : implode("-", array_reverse(explode("/", $this->input->post('fechaNac'))));
	    $nro_doc     = $this->input->post('nro_doc');
	    $telefono    = ($this->input->post('telefono') == null || trim($this->input->post('telefono')) == "") ? null : $this->input->post('telefono');
	    $tipo_sangre = trim($this->input->post('tipoSangre'));
	    $update = array('usuario'       => $user,
            	        'correo'        => $email,
            	        'fec_naci'      => $fecha,
            	        'nro_documento' => $nro_doc,
            	        'telf_pers'     => $telefono,
	                    'tipo_sangre'   => $tipo_sangre);
	    $dataUser = array("usuario" => $user);
	    $this->session->set_userdata($dataUser);
	    $data = $this->m_usuario->updateDatosPersona($update, $nid_persona);
	    $dataPersona = $this->m_usuario->getDatosPersona($nid_persona);
	    
	    $data['nombreUsuario'] = _getSesion('usuario');
	    $data['email']         = $dataPersona['correo'];
	    $data['nro_documento'] = $dataPersona['nro_documento'];
	    $data['telefono']      = $dataPersona['telf_pers'];
	    $data['tipoSangre']    = $dataPersona['tipo_sangre'];
	    
	    $fecha = implode("/", array_reverse(explode("-", $dataPersona['fec_naci'])));
	    $data['fecha']         = $fecha;
	     echo json_encode(array_map('utf8_encode', $data)); 
	}
	
	public function cambiarClave() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $nid_persona = _getSesion('nid_persona');
	        $lastPass    = trim(_post('currPass'));
	        $newPass     = trim(_post('newPass'));
	        $newPass2    = trim(_post('newPass2'));
	        
	        if($lastPass == null || $newPass == null || $newPass2 == null) {
	            throw new Exception('Ingrese las claves');
	        }
	        $rpta = $this->m_usuario->getTestLoginForCambioClave($this->_idUserSess, $lastPass);
	        if($rpta != 'OK') {
	            throw new Exception('La clave actual no es la correcta. Por seguridad tienes que saber tu clave para cambiarla.');
	        }
	        if($newPass != $newPass2) {
	            throw new Exception('La nueva clave no coincide');
	        }
	        if(!__checkPasswordStrength($newPass)) {
	            throw new Exception('La nueva clave debe tener al menos 7 caracteres, una mayúscula y un número.');
	        }
	        $data = $this->m_utils->updatePassword($newPass, $nid_persona);
	        ////
	        $persona = $this->m_usuario->getCorreoByUsuarioByNid($this->_idUserSess);
	        $correo = $persona['correo_inst'];
	        if($correo == null) {
	            $correo = $persona['correo_admi'];
	        }
	        if($correo != null) {
	            $body    = __bodyMensajeResetearClave(array('nombres' => $persona['persona']));
	            $asunto  = 'Hola '.$persona['nombre_solo'].', tu contraseña en Smiledu ha sido cambiada :)';
	            $datosInsert = array(
	                'correos_destino' => $correo,
	                'asunto'          => $asunto,
	                'body'            => $body,
	                'sistema'         => 'BASE');
	            $this->m_utils->insertarEnviarCorreo($datosInsert);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	public function cambiarFoto(){
	    $nid_persona = $this->session->userdata('nid_persona');
	    $imagePersona = $this->m_usuario->getUrlImagePersona($nid_persona);

	    $imgBase = $this->input->post('urlImage');
	    $img = str_replace('data:image/png;base64,', '', $imgBase);
	    $img = str_replace(' ', '+', $img);
	    $data = base64_decode($img);
	    
	    $file = null;
	    $fotoNombre = null;
	    if($imagePersona != null && file_exists($_SERVER['DOCUMENT_ROOT'].'/smiledu/'.$imagePersona)) {
	        unlink($_SERVER['DOCUMENT_ROOT'].'/smiledu/'.$imagePersona);
	        $file = $_SERVER['DOCUMENT_ROOT'].'/smiledu/'.$imagePersona;
	    } else { //escribir en el directorio de imagenes
	        $fotoNombre = 'foto_'.__generateRandomString(6).'_'.date("dmhis").'.png';
	        $file = $_SERVER['DOCUMENT_ROOT'].'smiledu/'.FOTO_PATH_BD.$fotoNombre;
	    }
	    $success = file_put_contents($file, $data);
	    if($success && $imagePersona == null) {
	        $data1 = $this->m_usuario->updateDatosPersona(array("foto_persona" => FOTO_PATH_BD.$fotoNombre), $nid_persona);
	    } else if($success && $imagePersona != null) {
	        $fotoCookie = "pic";
	        $fotoRuta   = _simple_encrypt($imagePersona);
	        setcookie($fotoCookie, $fotoRuta, time() + (86400 * 30), "/");
	        $this->session->set_userdata(array("foto_usuario" => $imagePersona));
	    }
	    $data1['foto']    = $img;
	    $data1['fotoUrl'] = $imagePersona;
        echo json_encode(array_map('utf8_encode', $data1));
	}
	
	public function subirFoto(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        if(empty($_FILES['itFotoUpd']['name'])){
	            throw new Exception('Seleccione una foto');
	        }
	        $config['upload_path'] = FOTO_PATH_TEMP;
	        $config['allowed_types'] = 'jpeg|jpg|png';
	        $ext = pathinfo($_FILES['itFotoUpd']['name'], PATHINFO_EXTENSION);
	        $config['file_name']     = 'foto_'.__generateRandomString(6).'_'.date("dmhis").'.'.$ext;
	        $config['max_size']	   = PESO_KBS_FOTO_PERS;
	        $config['max_width']   = MAX_WIDTH_FOTO;
	        $config['max_height']  = MAX_HEIGTH_FOTO;
	        $this->load->library('upload', $config);
	        if (!$this->upload->do_upload('itFotoUpd')){
	            throw new Exception(utf8_decode($this->upload->display_errors())  );
	        }else{
	            $upload_data = $this->upload->data();
	            $imgSubidaTemp = $config['upload_path'].$config['file_name'];
	            if($upload_data['image_width'] > WIDTH_REDIMENSIONAR_FOTO) {
	                $config['image_library']  = 'gd2';
	                $config['file_name']      = $config['file_name'];
	                $config['source_image']   = $imgSubidaTemp;
	                $config['new_image']      = FOTO_PATH;
	                $config['create_thumb']   = FALSE;
	                $config['maintain_ratio'] = TRUE;
	                $config['width']          = WIDTH_REDIMENSIONAR_FOTO;
	                $config['height']         = HEIGHT_REDIMENSIONAR_FOTO;
	        
	                $this->load->library('image_lib', $config);
	                if (!$this->image_lib->resize()) {
	                    throw new Exception(utf8_decode($this->image_lib->display_errors()) );
	                }
	                $this->image_lib->resize();
	                $tituloImg =  FOTO_PATH_BD.$config['file_name'];
	                if(file_exists($imgSubidaTemp)) {
	                    if (!unlink($imgSubidaTemp)) {
	                        throw new Exception('Hubo un error con la imagen');
	                    }
	                }
	            } else {
	                if (copy($imgSubidaTemp, FOTO_PATH.$config['file_name'])) {
	                    if (!unlink($imgSubidaTemp)) {
	                        throw new Exception('Hubo un error con la imagen');
	                    }
	                }
	                $tituloImg = FOTO_PATH_BD.$config['file_name'];
	            }
	        }
	        
	        $nid_persona  = $this->session->userdata('nid_persona');
	        $fotoActual   = $this->m_usuario->getUrlImagePersona($nid_persona);
	        $fotoAntigua  = $fotoActual;
	        if($fotoAntigua != "public/files/images/profile/nouser.svg"){
	            if(file_exists($fotoAntigua)) {
	                $rutaFotoAntigua = base_rutas.'./'.$fotoActual;
	                if (!unlink($rutaFotoAntigua)) {
	                    throw new Exception('Hubo un error con la imagen');
	                }
	            }
	        }
	        
	        $arryDatos = array("foto_persona" => $tituloImg);
	        $data = $this->m_usuario->updateDatosPersona($arryDatos, $nid_persona);
	        
	        $base = file_get_contents($tituloImg);
	        $data['foto']    = base64_encode($base);
	        $data['fotoUrl'] = $tituloImg;
	        
	        $fotoCookie = "pic";
	        $fotoRuta   = _simple_encrypt(base_url().'./'.$tituloImg);
	        setcookie($fotoCookie, $fotoRuta, time() + (86400 * 30), "/");
	        $this->session->set_userdata(array("foto_usuario" => base_url().'./'.$tituloImg));
	        
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function enviarFeedBack(){
        $nombre = $this->session->userdata('nombre_completo');
        $mensaje = $this->input->post('feedbackMsj');
        $url = $this->input->post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
    
    function cambiarFontSize(){
        $fontSize     = $this->input->post('pixel');
        $nid_persona  = $this->session->userdata('nid_persona');
        
        $update = array('font_size' => $fontSize);
        $this->session->set_userdata($update);
        
        $data = $this->m_usuario->updateFontSize($nid_persona, $update);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveIntereses(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $intereses     = utf8_decode($this->input->post('hobby'));
        $nid_persona   = $this->session->userdata('nid_persona');
        try{
            $arrayUpdate = array('hobby' => ($intereses));
            $data = $this->m_usuario->updateDatosPersona($arrayUpdate,$nid_persona);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('No se pudo actualizar el dato');
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getCumpleanosMes(){
        $listaCumpleaÃ±osMes = $this->m_usuario->getPersonasCumpleaÃ±osMes();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_cumple" >',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Foto');
        $head_2 = array('data' => 'Nombre');
        $head_3 = array('data' => 'Día');
        $this->table->set_heading($head_0,$head_1,$head_2,$head_3);
        $val = 1;
        foreach($listaCumpleaÃ±osMes AS $row){
            $row_0 = array('data' => $val);
            $foto = _getFotoPerfil(array("foto_persona" => $row->foto_persona, "google_foto" => $row->google_foto));
            $row_1 = array('data' => '<img src="'.$foto.'" class="img-circle width-1" alt="foto" width="40" height="40">');
            $torta = "";
            if($row->color != '0'){
                $torta = '<i class="md md-cake" style="margin-left:3px;color:#26A6D1"></i>';
            }
            $row_2 = array('data' => $row->nombrecompleto.$torta);
            $row_3 = array('data' => $row->fec_naci);
            $val++;
            $this->table->add_row($row_0,$row_1,$row_2,$row_3);
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function createSistemas_x_rol($rolSistemas) {
        $result = null;
        $contador = 0;
        foreach ($rolSistemas as $var) {
            $idSistema = _encodeCI($var->nid_sistema);
            $roles     = $this->m_usuario->getRolesOnlySistem(_getSesion('nid_persona'),$var->nid_sistema);
            $opciones  = $this->rolesSistem($roles, $var->url_sistema, $var->nid_sistema, $var->flg_realizado, $var->desc_sist);
            $classSenc = "";
            $styleDesactivado = null;
            $col = "";
            if($var->flg_realizado == 0) {
                $styleDesactivado = "cursor:not-allowed";
            }
            if($var->nid_sistema == ID_SISTEMA_SENC) {
                $classSenc = "card-senc";
            }
            $result .= '<div class="ui-state-default draggable ui-sortable-handle">
                            <div class="mdl-app mdl-card mdl-shadow--2dp" id="card-main-'.$contador.'">
                                <div class="mdl-card__title mdl_height_2x">
                                    <img src="'.base_url().$var->logo_sistema.'">
                                </div>
                                <div class="mdl-card__actions closed">
                                    <div class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored">
                                        <ul class="mdl-nav">
                                            <li onclick="openPermisosList(\'card-main-'.$contador.'\');"><a title="'.((strlen($var->desc_sist) > 12) ?$var->desc_sist : null).'">'.$var->desc_sist.'<i class="mdi mdi-arrow_forward"></i></a><span class="transparencia"></span></li>
                                            '.$opciones.'
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>';
            $contador++;
        }
        return $result;
    }
    
    function logOut() {_log('cerrando.. sesion..');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
}