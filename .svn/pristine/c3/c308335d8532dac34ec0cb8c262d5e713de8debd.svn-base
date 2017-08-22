<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__buildComboEncuestaByTipo')) {
    function __buildComboEncuestaByTipo($tipoEncuesta){
        $CI =& get_instance();
        $CI->load->model('mf_encuesta/m_encuesta');
        $encuestas = $CI->m_encuesta->getEncuestasByTipoEncuesta($tipoEncuesta);
        $opt = null;
        foreach($encuestas as $enc){
            $idEncuesta  = $CI->encrypt->encode($enc->id_encuesta);
            $opt .= '<option value="'.$idEncuesta.'">'.$enc->titulo_encuesta.' ('.$enc->desc_enc.')</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboEncuestaByTipoPersona')) {
    function __buildComboEncuestaByTipoPersona($idTipo, $idPersona){
        $CI =& get_instance();
        $CI->load->model('mf_encuesta/m_encuesta');
        $encuestas = $CI->m_encuesta->getEncuestasByTipoEncuestaPersona($idTipo, $idPersona);
        $opt = null;
        foreach($encuestas as $enc){
            $idEncuesta  = $CI->encrypt->encode($enc->id_encuesta);
            $opt .= '<option value="'.$idEncuesta.'">'.$enc->titulo_encuesta.' ('.$enc->desc_enc.')</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboNivelesByMultiSedes')) {
    function __buildComboNivelesByMultiSedes($sedes,$year){
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $niveles = $CI->m_utils_senc->getNivelesByMultiSedesYear($sedes,$year);
        $opt = null;
        foreach($niveles as $niv){
            $idNivel  = $CI->encrypt->encode($niv->nid_nivel);
            $opt .= '<option value="'.$idNivel.'">'._ucwords($niv->desc_nivel).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboGradosByMultiSedeNivel')) {
    function __buildComboGradosByMultiSedeNivel($sede,$nivel, $year) {//dfloresgonz 02.10.16 $year
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $grados = $CI->m_utils_senc->getGradosByMultiNivelSede($sede, $nivel, $year);//dfloresgonz 02.10.16 $year
        $opcion = '';
        foreach ($grados as $grad){
            $opcion .= '<option value="'.$CI->encrypt->encode($grad->nid_grado).'">'._ucwords($grad->desc_grado).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboAulasMulti')) {
    function __buildComboAulasMulti($sede,$nivel,$grado,$year){
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $aulas = $CI->m_utils_senc->getAulasByGradoYearMulti($sede,$nivel,$grado,$year);
        $opcion = '';
        foreach ($aulas as $aul){
            $idAulas = $CI->encrypt->encode($aul->nid_aula);
            $opcion .= '<option value="'.$idAulas.'">'._ucwords($aul->desc_aula).'</option>';
        }_log($opcion);
        return $opcion;
    }
}

if(!function_exists('__buildComboPropuestasMejora')) {
    function __buildComboPropuestasMejora($idEncuesta) {
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $propuesta = $CI->m_utils_senc->getAllPropuestasMejora($idEncuesta);
        $opcion = '';
        foreach ($propuesta as $prop){
            $idPropuesta = $CI->encrypt->encode($prop->id_propuesta);
            $opcion .= '<option value="'.$idPropuesta.'">'._ucwords($prop->desc_propuesta).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboAreasAcad')) {
    function __buildComboAreasAcad() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $area = $CI->m_utils->getAreasAcad();
        $opcion = '';
        foreach ($area as $areasac){
            $idArea = $CI->encrypt->encode($areasac->nid_area_academica);
            $opcion .= '<option value="'.$idArea.'">'._ucwords($areasac->desc_area_academica).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboEstuEncuestaFisica')) {
    function __buildComboEstuEncuestaFisica($idAula) {
        $CI =& get_instance();
        $CI->load->model('m_crear_encuesta');
        $estus = $CI->m_crear_encuesta->getEstudiantesSinLlenarEncEntregFisico($idAula);
        $opcion = '';
        foreach ($estus as $row) {
            $opcion .= '<option value="'._encodeCI($row['nid_persona']).'">'.$row['estudiante'].'</option>';
        }
        return $opcion;
    }
}