<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serv_bsc extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('mf_indicador/m_indicador');
    }
    
    public function index(){
        redirect('','refresh');
    }
    
    public function actualizarIndicadorMongoDB(){
        $data = $this->m_indicador->actualizarActualIndicadorMongoDB();
        echo $data['msj'];
    }
}