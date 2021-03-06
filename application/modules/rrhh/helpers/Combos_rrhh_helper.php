<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__buildComboCantidadNivelesRubrica')) {
    function __buildComboCantidadNivelesRubrica() {
        $CI =& get_instance();
        $valores = explode(';', CANT_NIVELES_RUBRICA);
        $opcion = '';
        foreach ($valores as $val) {
            $opcion .= '<option value="'._simple_encrypt($val).'">'.$val.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboYearContactos')) {
    function __buildComboYearContactos() {
        $CI =& get_instance();
        $CI->load->model('m_utils_admision');
        $year = $CI->m_utils_admision->getYearContactos();
        $opcion = '';
        foreach ($year as $ye){
            $opcion .= '<option value="'._simple_encrypt($ye->year).'">'.$ye->year.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboHorarios')) {
    function __buildComboHorarios($horarios, $idHorarioSelect = null) {
        $opcion = null;
        foreach ($horarios as $hor){
            $selected = null;
            if($idHorarioSelect == $hor->correlativo){
                $selected = 'selected';
            }
            $opcion .= '<option '.$selected.' value="'._simple_encrypt($hor->correlativo).'">'.$hor->desc_hora_cita.' ('._fecha_tabla($hor->hora_cita, 'h:i a').')</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboSedesAdmision')) {
    function __buildComboSedesAdmision($nivel){
        $CI =& get_instance();
        $CI->load->model('m_utils_admision');
        $sedes = $CI->m_utils_admision->getSedesAdmisionByNivel($nivel);
        $option='';
        $option.= '<option value="'._simple_encrypt(SEDE_POR_DEFINIR).'">POR DEFINIR</option>';
    
        foreach ($sedes as $sed){
            $option .= '<option value="'._simple_encrypt($sed->nid_sede).'">'.strtoupper($sed->desc_sede).'</option>';
        }
    
        return $option;
    }
}