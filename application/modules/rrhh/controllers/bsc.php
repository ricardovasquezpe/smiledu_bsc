<?php

class Bsc extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('m_welcome');
        $this->load->library('table');
        $this->load->library('lib_utils_home');
        $this->load->library('../lib_utils');
    }
    
    function index(){
        $query = $this->m_welcome->getAllRoles();
        $this->lib_utils_home->imprimir();
        $this->lib_utils->imprimir1();
        $data['table'] = $this->table->generate($query);
        $this->load->view('v_prueba',$data);
    }
}
