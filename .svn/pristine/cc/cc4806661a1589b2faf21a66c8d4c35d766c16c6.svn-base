<?php
if(!function_exists('__buildComboCronograma')) {
    function __buildComboCronograma($id_sede = null){
        $CI =& get_instance();
        $CI->load->model('m_cronograma');
        $cronogramas = $CI->m_cronograma->getCronogramas($id_sede);
        $opcion = '';
        foreach ($cronogramas as $row){
            $idCronograma = $CI->encrypt->encode($row->id_cronograma);
            $opcion .= '<option value="'.$idCronograma.'">'.strtoupper($row->desc_cronograma).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboCompromisosGlobales')) {
    function __buildComboCompromisosGlobales(){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $comp = $CI->m_compromisos->getListaCompromisosGlobales(_getSesion('nid_persona'));
    
        $opcion = '';
        foreach ($comp as $op){
            $id = $CI->encrypt->encode($op->id_compromiso_global);
            $opcion .= '<option value="'.$id.'">'.$op->desc_concepto.' '.$op->audi_fec_regi.'</option>';
        }
        return $opcion;
    }
}
