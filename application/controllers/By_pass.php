<?php defined('BASEPATH') OR exit('No direct script access allowed');

class By_pass extends CI_Controller {
    
    private $_msj = 'La solicitud ha expirado.';
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('m_utils');
    }
    
    public function index() {
        try {
            if(_get('c') != null && _get('fh') != null && _get('cod') != null) {
                $correo  = _decodeCIURL(_get('c'));
                $fecHora = _decodeCIURL(_get('fh'));
                $idPers  = _decodeCIURL(_get('cod'));
                $correo = $this->validar($correo, $fecHora, $idPers);
                _setSesion(array(
                'correo_cambio'  => $correo,
                'fecha_cambio'   => $fecHora,
                'id_pers_cambio' => $idPers));
            }
        } catch (Exception $e) {
            _setSesion(array('rpta' => $e->getMessage()));
        }
        redirect('Reset_clave', 'refresh');
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