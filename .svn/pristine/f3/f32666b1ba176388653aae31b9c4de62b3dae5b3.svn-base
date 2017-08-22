<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__buildComboAulasByGradoYear')) {
    function __buildComboAulasByGradoYear($idNivel,$idSede, $idGrado, $year, $idaula = null){
        $CI =& get_instance();
        $aulas = $CI->m_matricula->getAulasByGradoYear($idNivel, $idSede, $idGrado, $year, $idaula);
        $opcion = '';
        foreach ($aulas as $aul){
            $opcion .= '<option value="'._simple_encrypt($aul->nid_aula).'">'.($aul->desc_aula).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboYearBySedeRol')) {
    function __buildComboYearBySedeRol($sedeRol){
        $CI =& get_instance();
        $year = $CI->m_aula->getYearBySedeRol($sedeRol);
        $options = '';
        foreach ($year as $y){
            $options .= '<option value="'.($y->year).'">'.($y->year).'</option>';
        }
        return $options;
    }
}

if(!function_exists('__buildComboGetAulasNoActiBysede')) {
    function __buildComboGetAulasNoActiBysede($idSede){
        $CI =& get_instance();
        $aulas = $CI ->m_aula->getAulasNoActivas($idSede);
        $opcion = '';
        foreach ($aulas as $var){
            $opcion .= '<option value="'._simple_encrypt($var->desc_a).'">'.strtoupper(utf8_decode($var->desc_a)).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboReportesByTipo')){
    function __buildComboReportesByTipo($tipo, $onchange, $opciones, $id, $attr, $first){
        $combo =  '<div class="col-xs-12 p-0 m-0 m-b-15"  for="'.$id.'">';
        $combo .= '<select id="'.$id.'" name="'.$id.'" class="form-control selectButton" data-live-search="true" data-none-selected-text="Selec. '.$tipo.'" onchange="'.$onchange.'"  '.$attr.'>
                      '.
                          (($first == 1) ? '<option value="">Selec. '.$tipo.'</option>' : null).
                          $opciones.'
	              </select>';
        $combo .= '</div>';
         
        return $combo;
    }
}

if(!function_exists('__buildComboYear')) {
    function __buildComboYear(){
        $yearActual = _getYear();
        $options = null;
        $i = 0;
    
        for($i = $yearActual-5 ; $i <= $yearActual+5 ; $i++){
            $options .= '<option value="'.($i).'">'.$i.'</option>';
        }
        return $options;
    }
}

if(!function_exists('__buildComboMeses')){
    function __buildComboMeses(){
        $CI =& get_instance();
        $meses = array("Enero","Febrero","Marzo",
            "Abril","Mayo","Junio",
            "Julio","Agosto","Septiembre",
            "Octubre","Noviembre","Diciembre");
        $i = 1;
        $opcion = '';
        foreach($meses as $mes){
            $idMes = _simple_encrypt($i);
            $opcion .= '<option value="'.$idMes.'">'.strtoupper($mes).'</option>';
            $i++;
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboReporte6')){
    function __buildComboReporte6(){
        $arrayOpciones = array("GRADO", "DISTRITO", "PROFESIÓN");
        $i = 1;
        $opcion = '';
        foreach($arrayOpciones as $opc){
            $idOpc = _simple_encrypt($i);
            $opcion .= '<option value="'.$idOpc.'">'.$opc.'</option>';
            $i++;
        }
         
        return $opcion;
    }
}

if(!function_exists('__buildComboTutores')){
    function __buildComboTutores($idtutor){
        $CI =& get_instance();
        $combo = $CI->m_aula->getTutoresNoAsignados($idtutor);
        $opcion = null;
        foreach ($combo as $row){
            $selected = null;
            $opcion .= '<option value="'._simple_encrypt($row->nid_persona).'">'.$row->nombre_completo.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__createComboYear')) {
    function __createComboYear($valueSelect = null){
        $CI =& get_instance();
        $CI->load->model('m_aula');
        $year = $CI->m_aula->getYear();
        $options = '';
        foreach ($year as $y){
            $selected = "";
            if($valueSelect == $y->year) {
                $selected = 'selected';
            }
            $options .= '<option '.$selected.' value="'.($y->year).'">'.($y->year).'</option>';
        }
        return $options;
    }
}