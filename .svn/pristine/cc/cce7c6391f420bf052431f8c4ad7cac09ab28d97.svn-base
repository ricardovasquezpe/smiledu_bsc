<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__buildComboCompetencias')) {
    function __buildComboCompetencias($idGrado, $idCurso, $idYear) {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $competencias = $CI->m_utils_notas->getCompetencias($idGrado, $idCurso, $idYear);
        $opt = null;
        foreach($competencias as $row) {
            $opt .= '<option value="'._encodeCI($row['_id_competencia']).'">'._ucfirst($row['desc_competencia']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboCapacidades')) {
    function __buildComboCapacidades($idCompetencia, $idGrado, $idCurso, $idYear) {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $competencias = $CI->m_utils_notas->getCapacidades($idCompetencia, $idGrado, $idCurso, $idYear);
        $opt = null;
        foreach($competencias as $row) {
            $opt .= '<option value="'._encodeCI($row['_id_capacidad']).'">'._ucfirst($row['desc_capacidad']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboAreasAcademicas')) {
    function __buildComboAreasAcademicas() {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $talleres = $CI->m_utils_notas->getAreasAcademicas();
        $opt = null;
        foreach($talleres as $row) {
            $opt .= '<option value="'._encodeCI($row['id_area']).'">'._ucfirst($row['desc_area']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboIndicadores')) {
    function __buildComboIndicadores($idCompetencia, $idCapacidad, $idGrado, $idCurso, $idYear) {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $competencias = $CI->m_utils_notas->getIndicadores($idCompetencia, $idCapacidad, $idGrado, $idCurso, $idYear);
        $opt = null;
        foreach($competencias as $row) {
            $opt .= '<option value="'._encodeCI($row['_id_indicador']).'">'._ucfirst($row['desc_indicador']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboInstrumentos')) {
    function __buildComboInstrumentos($idGrado, $idCurso, $idCompetencia, $idCapacidad, $idIndicador, $idMain) {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $instrumentos = $CI->m_utils_notas->getInstrumentosConceptos($idGrado, $idCurso, $idCompetencia, $idCapacidad, $idIndicador, $idMain);
        $opt = null;
        foreach($instrumentos as $row) {
            $opt .= '<option value="'._encodeCI($row['id']).'">'._ucfirst($row['concepto_instru']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboTalleres')) {
    function __buildComboTalleres() {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $talleres = $CI->m_utils_notas->getTalleres();
        $opt = null;
        foreach($talleres as $row) {
            $opt .= '<option value="'._encodeCI($row['id_taller']).'">'._ucfirst($row['desc_taller']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboCursosUgelEquiv')) {
    function __buildComboCursosUgelEquiv($idArea) {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $cursos = $CI->m_utils_notas->getComboCursosUgelEquiv($idArea);
        $opt = null;
        foreach($cursos as $row) {
            $opt .= '<option value="'._encodeCI($row['id_curso']).'">'._ucfirst($row['desc_curso']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildAulasExternas')) {
    function __buildAulasExternas() {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $talleres = $CI->m_utils_notas->getAulaExt();
        $opt = null;
        foreach($talleres as $row) {
            $opt .= '<option value="'._encodeCI($row['id_aula_ext']).'">'._ucfirst($row['desc_aula_ext']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboAulaTutor')) {
    function __buildComboAulaTutor($idTutor) {
        $CI =& get_instance();
        $CI->load->model('m_utils_notas');
        $aulas = $CI->m_utils_notas->getAulasTutor($idTutor);
        $opcion = null;
        foreach ($aulas as $aul) {
            $opcion .= '<option value="'._encodeCI($aul->nid_aula).'">'._ucwords($aul->desc_aula).'</option>';
        }
        return $opcion;
    }
}