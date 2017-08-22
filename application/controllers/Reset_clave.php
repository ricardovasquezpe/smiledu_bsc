<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reset_clave extends CI_Controller {
    
    private $_correo = null;
    private $_fecha  = null;
    private $_idPers = null;
    private $_msj    = 'La solicitud ha expirado.';
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->_correo = _getSesion('correo_cambio');
        $this->_fecha  = _getSesion('fecha_cambio');
        $this->_idPers = _getSesion('id_pers_cambio');
    }
    
    public function index() {
        $data['correo_cambio']  = $this->_correo;
        $data['fecha_cambio']   = $this->_fecha;
        $data['id_pers_cambio'] = $this->_idPers;
        if(_getSesion('rpta') != null) {
            unset($data['correo_cambio']);
            unset($data['fecha_cambio']);
            unset($data['id_pers_cambio']);
            $data['rpta'] = _getSesion('rpta');//$this->_msj;
            $this->session->unset_userdata('rpta');
        }
        $this->load->view('v_reset_clave', $data);
    }
    
    function cambiarClave() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $clave  = trim(_post('clave'));
            $_clave = trim(_post('_clave'));
            if($clave == "" || $_clave == "") {
                throw new Exception('Ingrese su nueva clave');
            }
            if($clave != $_clave) {
                throw new Exception('Las claves no coinciden');
            }
            if(!__checkPasswordStrength($clave)) {
                throw new Exception('La nueva clave debe tener al menos 7 caracteres, una mayúscula y un número.');
            }
            $correo = $this->validar($this->_correo, $this->_fecha, $this->_idPers);
            $data = $this->m_utils->updatePassword($clave, $this->_idPers);
            $msj = $data['msj'];
            $persona = $this->m_usuario->getCorreoByUsuarioByNid($this->_idPers);
            $asunto  = 'Hola '.$persona['nombre_solo'].', tu contraseña en Smiledu ha sido cambiada :)';
            $datosCorreo = array(
                'nombres' => $persona['persona']
            );
            $body = __bodyMensajeResetearClave($datosCorreo);
            $datosInsert = array(
                'correos_destino' => $correo,
                'asunto'          => $asunto,
                'body'            => $body,
                'sistema'         => 'BASE');
            $data = $this->m_utils->insertarEnviarCorreo($datosInsert);
            $this->db->trans_commit();
            $this->session->unset_userdata('rpta');
            $this->session->unset_userdata('fecha_cambio');
            $this->session->unset_userdata('id_pers_cambio');
            $this->session->unset_userdata('correo_cambio');
            $data['msj'] = $msj;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function validar($correo, $fecHora, $id) {
        if($correo == null || $fecHora == null || $id == null) {
            throw new Exception($this->_msj);
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception($this->_msj);
        }
        $datetime = new DateTime();
        $fecHoraDTime = $datetime->createFromFormat('d/m/Y H:i:s', $fecHora);
        if (!$fecHoraDTime instanceof DateTime) {
            throw new Exception($this->_msj);
        }
        $fecHoraBD = $this->m_utils->getById('persona', 'fec_hora_cambio_clave', 'correo_inst', $correo);
        if($fecHoraBD == null) {
            $fecHoraBD = $this->m_utils->getById('persona', 'fec_hora_cambio_clave', 'correo_admi', $correo);
        }
        if($fecHoraBD == null) {
            throw new Exception($this->_msj);
        }
        if($fecHoraBD != $fecHora) {
            throw new Exception($this->_msj);
        }
        //Revisar si la fecha/hora tiene una edad de 5min
        $ahora = date('d/m/Y H:i:s');
        $fecHoraDTime->add(new DateInterval('PT' . MINUTOS_DURACION_LINK . 'M'));
        
        if($ahora > $fecHoraDTime) {
            throw new Exception($this->_msj);
        }
        return $correo;
    }
}