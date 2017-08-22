<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_buildComboAllIndicadores')) {
    function _buildComboAllIndicadores(){
        $CI =& get_instance();
        $CI->load->model('mf_indicador/m_comparativa');
        $indicadores = $CI->m_comparativa->getAllIndicadores();
        $option = '';
        $val = 0;
        foreach ($indicadores as $ind){
            $val++;
            $idIndicador = $CI->encrypt->encode($ind->__id_indicador);
            $option .= '<option value="'.$idIndicador.'">'.'('.$ind->cod_indi.')'.$ind->descripcion.'</option>';
        }
        return $option;
    }
}

if(!function_exists('_buildLineaEstrategica')) {
    function _buildLineaEstrategica(){
        $CI =& get_instance();
        $lineas = $CI->m_lineaEstrat->getComboLineasEstrategicas();
        $opcion = '';
        foreach ($lineas as $linea){
            $idLinea = $CI->encrypt->encode($linea->_id_linea_estrategica);
            $opcion .= '<option value="'.$idLinea.'">'.$linea->desc_linea_estrategica.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('_buildComboObjetivosByLinea')) {
    function _buildComboObjetivosByLinea($idLinea){
        $CI =& get_instance();
        $objetivos = $CI->m_responsable_indicador->getObjetivosByLinea($idLinea);
        $opcion = '';
        foreach ($objetivos as $obj){
            $idObj = $CI->encrypt->encode($obj->_id_objetivo);
            $opcion .= '<option value="'.$idObj.'">'.'('.$obj->cod_obje.')'.$obj->desc_objetivo.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('_buildComboCategoriaByObjetivo')) {
    function _buildComboCategoriaByObjetivo($idObjetivo){
        $CI =& get_instance();
        $categorias = $CI->m_responsable_indicador->getCategoriaByObjetivo($idObjetivo);
        $opcion = '';
        foreach ($categorias as $cat){
            $idCategoria = $CI->encrypt->encode($cat->id_categoria);
            $opcion .= '<option value="'.$idCategoria.'">'.$cat->desc_categoria.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('_buildComboIndicadorByCategoria')) {
    function _buildComboIndicadorByCategoria($idCategoria){
        $CI =& get_instance();
        $indicadores = $CI->m_responsable_indicador->getIndicadoresByCategoria($idCategoria);
        $opcion = '';
        foreach ($indicadores as $ind){
            $idIndicador = $CI->encrypt->encode($ind->_id_indicador);
            $opcion .= '<option value="'.$idIndicador.'">'.$ind->desc_indicador.' ('.$ind->cod_indi.')</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboIndicadorByCodigo')) {
    function __buildComboIndicadorByCodigo($codigo) {
        $CI =& get_instance();
        $indicadores = $CI->m_indicador->getIndicadoresByCodigo($codigo);
        $opcion = null;
        foreach ($indicadores as $ind) {
            $opcion .= '<option value="'._encodeCI($ind['_id_indicador']).'">('.$ind['cod_indi'].')  '.$ind['desc_indicador'].'</option>';
        }
        return $opcion;
    }
}