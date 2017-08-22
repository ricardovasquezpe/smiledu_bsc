<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_log')) {
    function _log($var) {
        log_message('error', $var);
    }
}

if(!function_exists('_logLastQuery')) {
    /**
     * Valida si es entero
     * @param $input - valor a evaluar
     * @return bool true / false
     */
    function _logLastQuery($marca = null){
        $CI =& get_instance();
        log_message('error', $marca.' - '.$CI->db->last_query());
    }
}

if(!function_exists('_fecha_tabla')) {
    /**
     * Transforma la fecha en el formato indicado para una tabla
     * @author dfloresgonz
     * @since 22.03.2016
     * @param date $fecha
     * @param string $formato d/m/Y, d/m/Y h:i:s A
     * @return fecha con formato
     */
    function _fecha_tabla($fecha, $formato) {
        return ($fecha == null) ? null : date($formato,strtotime($fecha));
    }
}

if(!function_exists('_getYear')) {
    /**
     * Retornar el año actual
     * @author dfloresgonz
     * @since 22.03.2016
     * @return integer - aÃ±o actual
     */
    function _getYear() {
        return date('Y');
    }
}

if(!function_exists('_encodeCI')) {
    /**
     * Encripta usando codeigniter encode
     * @author dfloresgonz
     * @since 22.03.2016
     * @param $toEncrypt variable que sera encriptada
     * @return variable encriptada
     */
    function _encodeCI($toEncrypt) {
        $CI =& get_instance();
        return $CI->encrypt->encode($toEncrypt);
    }
}

if(!function_exists('_decodeCI')) {
    /**
     * Desencripta usando codeigniter decode
     * @author dfloresgonz
     * @since 22.03.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable desencriptada
     */
    function _decodeCI($toDecrypt) {
        $CI =& get_instance();
        return $CI->encrypt->decode($toDecrypt);
    }
}

if(!function_exists('_decodeCIURL')) {
    /**
     * Desencripta usando codeigniter decode pero si la variable viene de URL (get)
     * @author dfloresgonz
     * @since 08.11.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable desencriptada
     */
    function _decodeCIURL($toDecrypt) {
        $CI =& get_instance();
        return $CI->encrypt->decode(str_replace(' ', '+', $toDecrypt));
    }
}

if(!function_exists('_simpleEncrypt')) {
    /**
     * Desencripta usando mcrypt_encrypt
     * @author dfloresgonz
     * @since 22.03.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable encriptada
     */
    function _simple_encrypt($toEncrypt) {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, CLAVE_ENCRYPT, $toEncrypt, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
}

if(!function_exists('_simpleDecrypt')) {
    /**
     * Desencripta usando mcrypt_decrypt
     * @author dfloresgonz
     * @since 22.03.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable desencriptada
     */
    function _simple_decrypt($toDecrypt) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, CLAVE_ENCRYPT, base64_decode($toDecrypt), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
}

if(!function_exists('_simpleDecryptInt')) {
    /**
     * Desencripta usando mcrypt_decrypt para integer, retorna null si no desencripto bien
     * @author dfloresgonz
     * @since 22.03.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable desencriptada
     */
    function _simpleDecryptInt($toDecrypt) {
        $dec = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, CLAVE_ENCRYPT, base64_decode($toDecrypt), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        if(!is_numeric($dec)){
            $dec = null;
        }
        return $dec;
    }
}

if(!function_exists('_validateDate')) {
    /**
     * Esta funcion valida si una fecha tiene el formato correcto
     * @param $date fecha a validar
     * @param $format ejemplo: d/m/Y
     * @return boolean (TRUE OK / FALSE NO OK)
     */
    function _validateDate($date, $format = 'd/m/Y'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

if(!function_exists('__validFecha')) {
    /**
     * Valida fecha en formato DD/MM/YYYY
     * @param  $fecha
     * @return boolean
     */
    function __validFecha($fecha){
        $test_arr  = explode('/', $fecha);
        if (count($test_arr) == 3) {
            if (checkdate($test_arr[1], $test_arr[0], $test_arr[2])) {//MES / DIA / YEAR
                return true;
            }
            return false;
        }
        return false;
    }
}

if(!function_exists('__validFecha2')) {
    /**
     * Valida en formato YYYY-MM-DD
     * @param  $fecha
     * @return boolean
     */
    function __validFecha2($fecha){
        $test_arr  = explode('-', $fecha);
        if (count($test_arr) == 3) {
            if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {//YEAR / MES / DIA
                return true;
            }
            return false;
        }
        return false;
    }
}

if(!function_exists('_getFotoPerfil')) {
    /**
     * Necesita las constantes RUTA_SMILEDU y FOTO_DEFECTO
     * @param $usuario Array consulta BD tiene que traer foto_persona y google_foto
     * @return string ruta de la foto a mandar en sesion
     */
    function _getFotoPerfil($usuario) {
        $foto = null;
        if($usuario['foto_persona'] != null) {
            $foto = RUTA_SMILEDU.$usuario['foto_persona'];
        } else if($usuario['google_foto'] != null) {
            $foto = $usuario['google_foto'];
        } else if($usuario['foto_persona'] == null && $usuario['google_foto'] == null) {
            $foto = RUTA_SMILEDU.FOTO_DEFECTO;
        }
        return $foto;
    }
}

if(!function_exists('_validate_usuario_controlador')) {
    /** Valida la permanencia del usuario en el sistema
     * @author rvasquez
     */
    function _validate_usuario_controlador($idPermiso) {
        $CI =& get_instance();
        if(!isset($_COOKIE[$CI->config->item('sess_cookie_name')])) {
            $CI->session->sess_destroy();
            redirect('','refresh');
        }
        $idPersona = $CI->session->userdata('nid_persona');
        if($idPersona == null) {
            $CI->session->sess_destroy();
            redirect('','refresh');
        }
        if($CI->m_utils->validarPersonaPermiso($idPersona, $idPermiso) == false) {
            $CI->session->sess_destroy();
            redirect('','refresh');
        }
    }
}

if(!function_exists('_validate_uso_controladorModulos')) {
    /** Valida la permanencia del usuario en el sistema
     * @author dfloresgonz
     */
    function _validate_uso_controladorModulos($idModulo, $idPermiso = null, $rolSessName) {
        $CI =& get_instance();
        if(!isset($_COOKIE[$CI->config->item('sess_cookie_name')])) {
            $CI->session->sess_destroy();
            redirect('','refresh');
        }
        $idPersona = $CI->session->userdata('nid_persona');
        $idRol     = $CI->session->userdata($rolSessName);
        if($idPersona == null || $idRol == null) {
            $CI->session->sess_destroy();
            redirect('','refresh');
        }
        $CI->load->model('../m_utils', 'utiles');
        //VALIDAR QUE EL ROL TENGA EL PERMISO
        if($idPermiso != null) {
            if($CI->utiles->checkIfRolHasPermiso($idRol, $idModulo, $idPermiso) == false) {
                $CI->session->sess_destroy();
                redirect('','refresh');
            }
        }
        //VALIDAR QUE EL USUARIO TENGA EL ROL
        if($CI->utiles->checkIfUserHasRol($idPersona, $idRol) == false  && $idRol != ID_ROL_FAMILIA) {
            $CI->session->sess_destroy();
            redirect('','refresh');
        }
    }
}

if(!function_exists('_post')) {
    /** 
     * @author rvasquez
     */
    function _post($postIndex) {
        $CI =& get_instance();
        return $CI->input->post($postIndex);
    }
}

if(!function_exists('_get')) {
    /**
     * @author dfloresgonz
     */
    function _get($getIndex) {
        $CI =& get_instance();
        return $CI->input->get($getIndex);
    }
}

if(!function_exists('_getSesion')) {
    /**
     * @author rvasquez
     */
    function _getSesion($sessionIndex) {
        $CI =& get_instance();
        return $CI->session->userdata($sessionIndex);
    }
}

if(!function_exists('_setSesion')) {
    /**
     * @author dfloresgonz
     */
    function _setSesion($sessionArray) {
        $CI =& get_instance();
        return $CI->session->set_userdata($sessionArray);
    }
}

if(!function_exists('_unsetSesion')) {
    /**
     * @author dfloresgonz
     */
    function _unsetSesion($sessionKey) {
        $CI =& get_instance();
        return $CI->session->unset_userdata($sessionKey);
    }
}

if(!function_exists('_ucwords')) {
    function _ucwords($palabra) {
        return mb_convert_case(mb_strtolower($palabra, 'iso-8859-1'), MB_CASE_TITLE, 'iso-8859-1');
    }
}

if(!function_exists('__mayusc')) {
    function __mayusc($palabra) {
        return mb_convert_case(mb_strtoupper($palabra, 'iso-8859-1'), MB_CASE_UPPER, 'iso-8859-1');
    }
}

if(!function_exists('_ucfirst')) {
    function _ucfirst($palabra) {
        $newStr = '';
        $match = 0;
        foreach(str_split($palabra) as $k=> $letter) {
            if($match == 0 && preg_match('/^\p{L}*$/', $letter)) {
                $newStr .= _ucwords($letter);
                break;
            }else{
                $newStr .= $letter;
            }
        }
        return $newStr.substr($palabra,$k+1);
    }
}

if(!function_exists('__getDescReduce')) {
    function __getDescReduce($desc, $length) {
        $lenghDesc  = strlen($desc);
        if($lenghDesc > $length) {
            $desc1 = substr($desc, - ($lenghDesc), $length);
            $desc  = $desc1."..";
        }
        return $desc;
    }
}

if(!function_exists('validar_decimales')) {
    function validar_decimales($num) {
        if (preg_match("/^[01.]*$/",$num)){
            return 1;
        } else {
            return 0;
        }
    }
}

if(!function_exists('__generateRandomString')) {
    function __generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = null;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('__enviarEmail')) {
    function __enviarEmail($correoDestino, $asunto, $body, $doc = null) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $CI =& get_instance();
            $CI->load->library('email');
            $configGmail = array(
                'protocol'  => PROTOCOL,
                'smtp_host' => SMTP_HOST,
                'smtp_port' => SMTP_PORT,
                'smtp_user' => CORREO_BASE,
                'smtp_pass' => PASSWORD_BASE,
                'mailtype'  => MAILTYPE,
                'charset'   => 'utf-8',
                'newline'   => "\r\n",
                'starttls'  => TRUE);
            $CI->email->initialize($configGmail);
            $CI->email->from(CORREO_BASE);
            $CI->email->to($correoDestino);
            $CI->email->subject($asunto);
            $CI->email->message($body);
            if($doc != null){
                $CI->email->attach($doc);
            }
            if ($CI->email->send()) {
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = '¡Te enviamos un correo!';
            } else {
                $err = print_r($CI->email->print_debugger(), TRUE);
                throw new Exception($err);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    } 
}

if(!function_exists('__getArrayStringFromArray')) {
    /**
     * Desencripta usando mcrypt_decrypt
     * @author rVasquez
     * @since 22.03.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable desencriptada
     */
    function __getArrayStringFromArray($data, $decrypt = null) {
        $arrayIds = null;
        foreach ($data as $var){
            $id = null;
            if($decrypt == 1){
                $id = _simple_decrypt($var);
            }else{
                $id = _decodeCI($var);
            }
            if($id != null){
                $arrayIds .= $id.',';
            }
        }
        $arrayIds = substr($arrayIds,0,(strlen($arrayIds)-1));
        return $arrayIds;
    }
}

if(!function_exists('__getArrayObjectFromArray')) {
    /**
     * Desencripta usando mcrypt_decrypt
     * @author rVasquez
     * @since 22.03.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable desencriptada
     */
    function __getArrayObjectFromArray($data, $decrypt = null) {
        $arrayIds = array();
        foreach ($data as $var){
            $id = null;
            if($decrypt == 1){
                $id = _simple_decrypt($var);
            }else{
                $id = _decodeCI($var);
            }
            if($id != null){
                array_push($arrayIds, $id);
            }
        }
        return $arrayIds;
    }
}

if(!function_exists('__only1whitespace')) {
    /**
     * Elimina los espacios en blanco multiples
     * @author rVasquez
     * @since 27.09.2016
     * @param $text variable que sera transformada
     * @return variable transformada
     */
    
    function __only1whitespace($text) {
        $text = preg_replace('!\s+!', ' ', $text);
        return $text;
    }
}

if(!function_exists('__getCookieName')) {
    /**
     * Obtener el nombre del cookie del proyecto
     * @author rVasquez
     * @since 17.10.2016
     * @return Nombre del cookie del proyecto
     */

    function __getCookieName() {
        $CI =& get_instance();
        return $CI->config->item('sess_cookie_name');
    }
}

if(!function_exists('__getPostMaxFileSize')) {
    /**
     * En bytes, tamano maximo del post
     * @return number
     */
    function __getPostMaxFileSize() {
        return (int)(str_replace('M', '', ini_get('post_max_size')) * 1024 * 1024);//bytes
    }
}

if(!function_exists('__checkBase64_image')) {
    /**
     * Valida si un base64 es imagen o no
     * @return true => es imagen / false => no es imagen
     */
    function __checkBase64_image($img64) {
        $img64 = substr($img64, strpos($img64, ',')+1, strlen($img64));
        $img = imagecreatefromstring(base64_decode($img64));
        if (!$img) {
            return false;
        }
        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');
        unlink('tmp.png');
        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }
        return false;
    }
}

if(!function_exists('__checkPasswordStrength')) {
    function __checkPasswordStrength($clave) {
        $pattern = '/(?<=\d).*((?<=[a-z]).*[A-Z]|(?<=[A-Z]).*[a-z])|(?<=[a-z]).*((?<=[A-Z]).*\d|(?<=\d).*[A-Z])|(?<=[A-Z]).*((?<=[a-z]).*\d|(?<=\d).*[a-z])/';
        $clave = trim($clave);
        if (!empty($clave) && strlen($clave) >= 7 && preg_match($pattern, $clave)) {//not empty, match ANY character after trimming the string 8 or more times
            return true;
        } else {
            return false;
        }
    }
}