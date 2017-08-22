<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_utils extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    function getComboGradosNivelBySede() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            $tipoEncuGlobal = $this->session->userdata("tipoEncuGlobal");
            if($idSede == null) {
                $data['error']         = EXIT_WARNING;
                $data['optGradoNivel'] = null;
                throw new Exception('Seleccione una sede');
            }else if(($idSede != null && $tipoEncuGlobal == 'P') || ($idSede != null && $tipoEncuGlobal == 'E')){
                $data['optGradoNivel'] = __buildComboGradoNivelBySede($idSede);
                $data['error']         = EXIT_SUCCESS;
            }else if($idSede != null && $tipoEncuGlobal == 'A'){
                $data['cmbAreasG']     = __buildComboAreasGenerales();
                $data['error']         = EXIT_SUCCESS;
            }else if($idSede != null && $tipoEncuGlobal == 'D'){
                $data['optNiveles']    = __buildComboNivelesBySede($idSede);
                $data['error']         = EXIT_SUCCESS;
            }else if($idSede !=null && $tipoEncuGlobal == null){
                $data['optGradoNivel'] = __buildComboGradoNivelBySede($idSede);
                $data['error']         = EXIT_SUCCESS;
            }
            
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getComboAreaGeneBySede(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            if($idSede == null) {
                $data['error'] = EXIT_WARNING;
                $data['cmbAreasG'] = null;
                throw new Exception(null);
            }
            $data['cmbAreasG'] = __buildComboAreasGenerales();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getComboAulasByGradoNivel() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            $idgradoNivel = _decodeCI(_post('idgradoNivel'));
            if($idSede == null) {
                $data['error'] = EXIT_WARNING;
                $data['optAulas'] = null;
                throw new Exception(null);
            }
            if($idgradoNivel == null) {
                $data['error'] = EXIT_WARNING;
                $data['optAulas'] = null;
                throw new Exception(null);
            }
            $gradoNivel = explode('_', $idgradoNivel);
            //Opcional se puede validar si el grado y nivel existen antes de hacer un query 
            $data['optAulas'] = __buildComboAulas($gradoNivel[0],$idSede/*, $gradoNivel[0]*/);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getComboAreaEspByNivel() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            $idNivel = _decodeCI(_post('idNivel'));
            if($idSede == null) {
                $data['error'] = EXIT_WARNING;
                $data['optAreaEsp'] = null;
                throw new Exception(null);
            }
            if($idNivel == null) {
                $data['error'] = EXIT_WARNING;
                $data['optAreaEsp'] = null;
                throw new Exception(null);
            }
            //Opcional se puede validar si el grado y nivel existen antes de hacer un query
            $data['optAreasEsp'] = __buildComboAreasEspecificas();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
    function getSedes() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $data['optSedes'] = __buildComboSedes();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}