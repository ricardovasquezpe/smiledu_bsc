<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serv_senc extends CI_Controller {

    public function __construct(){
        parent::__construct();
        //EN * PONER LA DIRECCION DEL SERVIDOR EDUSYS
        $this->output->set_header('Access-Control-Allow-Origin: *');
        $this->load->model('mf_encuesta/m_encuesta');
        if($this->m_encuesta->getTipoEncuestaActiva() != 1){
            redirect('','refresh');
        }
    }
    
    public function index(){
        redirect('','refresh');
    }
    
    public function codificarcodfam(){
        $ret = $this->input->get('codfam');
        $url = null;
        if($ret != null){
            $ret = $this->encrypt->encode($ret);
            $url = 'http://181.224.241.203/senc/c_encuesta_efqm?codfam='.$ret;
        }
        $data['url'] = $url;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
}