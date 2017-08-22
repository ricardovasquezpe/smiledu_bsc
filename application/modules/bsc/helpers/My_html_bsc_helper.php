<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_createTableIndicadores')) {
    function _createTableIndicadores($indicadores){
        $CI =& get_instance();
        $CI->load->library('table');
    
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   data-show-columns="true" data-search="true" id="tb_indicadores">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl); 
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'DescripciÃÂ³n','style' => 'text-align:left', 'data-sortable' => 'true');
        $head_2 = array('data' => 'Valor Actual','style' => 'text-align:left', 'data-sortable' => 'true','class' => 'col-sm-2 text-center');
        $head_3 = array('data' => 'Meta','style' => 'text-align:left', 'data-sortable' => 'true');
         
        $head_5 = array('data' => 'Frecuencia Medi','style' => 'text-align:left', 'data-sortable' => 'true', 'data-visible' => 'false');
        $head_6 = array('data' => 'EFQM','style' => 'text-align:left', 'data-sortable' => 'true', 'data-visible' => 'true');
         
        $head_7 = array('data' => 'Comparativa 1','style' => 'text-align:left', 'data-sortable' => 'true', 'data-visible' => 'false');
        $head_8 = array('data' => 'Comparativa 2','style' => 'text-align:left', 'data-sortable' => 'true', 'data-visible' => 'false');
        $head_9 = array('data' => 'Comparativa 3','style' => 'text-align:left', 'data-sortable' => 'true', 'data-visible' => 'false');
        $head_10 = array('data' => 'AcciÃÂ³n','data-sortable' => 'false', 'class' => 'col-sm-1 text-center');
         
        $val = 1;
        $idIndicadorActual = 0;
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_5, $head_6, $head_7, $head_8, $head_9,$head_10);
         
        $row_col0   = null;
        $row_col1   = null;
        $row_col2   = null;
        $row_col3   = null;
         
        $row_col5   = null;
        $row_col6   = null;
         
        $row_col7   = null;
        $row_col8   = null;
        $row_col9   = null;
        $row_col10   = null;
        $cantCompar = 7;
         
        foreach($indicadores as $row){ //SEGUIR AGREGANDO COMPARATIVAS AL ROW
            $tipoValor   = $row->tipo_valor;
            $idIndicador = _simple_encrypt($row->_id_indicador);
            if($idIndicadorActual == $row->_id_indicador){
                $compar = $row->desc_comparativa.' - '.$row->valor_comparativa.$row->tipo_valor;
                ${'row_col' . $cantCompar} = array('data' => $compar);
                $cantCompar++;
            }else if($idIndicadorActual != 0 && $idIndicadorActual != $row->_id_indicador){// CERRAR EL ROW Y AGERGAR NUEVO INDICADOR
                $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3, $row_col5, $row_col6, $row_col7, $row_col8, $row_col9,$row_col10);
                 
                $row_col7 = null;
                $row_col8 = null;
                $row_col9 = null;
                 
                $cantCompar = 7;
                $compar = $row->desc_comparativa.' - '.$row->valor_comparativa.$row->tipo_valor;
                $row_col0    = array('data' => $val,'class' => ' text-left');
                $row_col1    = array('data' => '<p class="dd">('.$row->cod_indi.')'.$row->desc_registro.'</p>');
                $row_col2    = array('data' => $row->valor_actual_porcentaje.$tipoValor.'<br class="bandgeActual"/>'._calculateIcon($row->diff_actual_y_anterior, $tipoValor),'class' => ' col-sm-2 text-center');
                $row_col3    = array('data' => $row->valor_meta.$tipoValor);
                $row_col5    = array('data' => $row->desc_frecuencia);
                $row_col6    = array('data' => $row->__codigo_criterio_efqm);
                ${'row_col' . $cantCompar} = array('data' => $compar);
                $idIndicadorActual = $row->_id_indicador;
                $val++;
                $cantCompar++;
            }else{//PARA EL 0(PRIMER DATO)
                $compar = $row->desc_comparativa.' - '.$row->valor_comparativa.$row->tipo_valor;
                $row_col0    = array('data' => $val);
                $row_col1    = array('data' => '<p class="dd">('.$row->cod_indi.')'.$row->desc_registro.'</p>');
                $row_col2    = array('data' => $row->valor_actual_porcentaje.$tipoValor.'<br class="bandgeActual"/>'._calculateIcon($row->diff_actual_y_anterior, $tipoValor),'class' => ' col-sm-2 text-center');
                $row_col3    = array('data' => $row->valor_meta.$tipoValor);
                $row_col5    = array('data' => $row->desc_frecuencia);
                $row_col6    = array('data' => $row->__codigo_criterio_efqm);
                ${'row_col' . $cantCompar} = array('data' => $compar);
                $idIndicadorActual = $row->_id_indicador;
                $val++;
                $cantCompar++;
            }
            $verDetalle='<button type="button" class="btn btn-icon-toggle" onclick="goToIndicadorDetalle(\''.$idIndicador.'\')" data-toggle="tooltip" data-placement="top" data-original-title="Edit row"><i class="md md-edit"></i></button>';
            $row_col10  = array('data' => $verDetalle);
        }
        $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3,$row_col5,$row_col6,$row_col7, $row_col8, $row_col9,$row_col10);
        $tabla = '<div id="custom-toolbar">
                        <p style="font-size:22px">Indicadores</p>
                    </div>';
        $tabla .= $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_getDescReduce')) {
    function _getDescReduce($desc,$length){
        $lenghDesc  = strlen($desc);
        if($lenghDesc > $length){
            $desc1 = substr($desc, -($lenghDesc), $length);
            $desc  = $desc1."..";
        }
    
        return $desc;
    }
}

if(!function_exists('_updateValorAmarilloByIndicador')) {
    function _updateValorAmarilloByIndicador($idIndicador,$valorAmarillo,$idObjetivo,$idUsuario,$idRol,$posicion,$idCont){
        $CI =& get_instance();
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $datosIndi   = $CI->m_indicador->getindicadorDetalleFromIndicador($idIndicador, array('valor_meta', 'id_ppu'));
            $valorActual = $CI->m_indicador->getCampoByIndicador($idIndicador);
            $tipoGauge = $CI->m_utils->getById("bsc.indicador", 'tipo_gauge', '_id_indicador', $idIndicador);
            $update = array('flg_amarillo' => $valorAmarillo);
            if($tipoGauge == GAUGE_PUESTO || $tipoGauge == GAUGE_CERO) {
                if ($valorAmarillo <= $datosIndi['valor_meta']){
                    $data['error'] = EXIT_ERROR;
                    $data['msj']   = "El valor no puede ser igual o menor que el valor meta";
                    throw new Exception("El valor no puede ser igual o menor que el valor meta");
                }
            } else if($tipoGauge == GAUGE_NORMAL || $tipoGauge == GAUGE_MAXIMO || $tipoGauge == GAUGE_RATIO){
                if ($valorAmarillo >= $datosIndi['valor_meta']){
                    $data['error'] = EXIT_ERROR;
                    $data['msj']   = "El valor no puede ser igual o mayor que el valor meta";
                    throw new Exception("El valor no puede ser igual o mayor que el valor meta");
                } else if($valorAmarillo < 0){
                    $data['error'] = EXIT_ERROR;
                    $data['msj']   = "El valor zona de riesgo debe ser mayor a 0";
                    throw new Exception("El valor zona de riesgo debe ser mayor a 0");
                }
    
            }
            $data = $CI->m_indicador->actualizaFlgAmarillo($idIndicador, $update);
    
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}

if(!function_exists('_calculateIcon')) {
    function _calculateIcon($ultimoValor,$tipoValor){
        if($ultimoValor < 0){
            return '<div style="display:inline;color:#F44336;margin-left:5px;"><i class="mdi mdi-trending_down" style="margin-right:5px;color:#F44336;"></i><span>'.abs($ultimoValor).$tipoValor.'</span></div>';
        }else if($ultimoValor > 0){
            return '<div style="display:inline;color:rgb(76, 175, 80);margin-left:5px;"><i class="mdi mdi-trending_up" style="margin-right:5px;color:rgb(76, 175, 80);vertical-align:middle;"></i><span>'.abs($ultimoValor).$tipoValor.'</span></div>';
        }else{
            return '<div style="display:inline;color:orange;margin-left:5px;"><i class="mdi mdi-trending_flat" style="margin-right:5px;color:orange;vertical-align:middle;"></i><span>'.abs($ultimoValor).$tipoValor.'</span></div>';
        }
    }
}

if(!function_exists('__buildTableAsignarComprativasXIndicador')) {
    function __buildTableAsignarComprativasXIndicador($idIndicador){
        $CI =& get_instance();
        $listaComparativas = ($idIndicador != null) ? $CI->m_comparativa->getComparativasByIndicador($idIndicador) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
        			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
        			                                id="tb_comparativas_x_indicador">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Comparativa', 'class' => 'text-left');
        $head_2 = array('data' => 'Valor', 'class' => 'text-right');
        $head_3 = array('data' => 'Asignado', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_2,$head_3);
        $val = 0;
        foreach($listaComparativas as $row){
            $idComparativa = $CI->encrypt->encode($row->_id_comparativa);
            $check_comp = ($row->flg_acti == 1)   ? 'checked' : null;
            $val++;
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row->desc_comparativa, 'class' => 'text-left');
            $row_2 = array('data' => $row->valor_comparativa, 'class' => 'text-right');
            $row_3 = array('data' => '  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="comp'.$val.'">
                                                <input type="checkbox"  id="comp'.$val.'" class="mdl-checkbox__input" '.$check_comp.' attr-bd="'.$check_comp.'"  attr-cambio="false" attr-idcomparativa="'.$idComparativa.'" onchange="cambioCheckComparativa(this);">
                                                <span class="mdl-checkbox__label"></span>
                                            </label>', 'class' => 'text-center');
            $CI->table->add_row($row_0,$row_1,$row_2,$row_3);
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }   
}

if(!function_exists('_buildTableHTMLFrecuencias')) {
    function _buildTableHTMLFrecuencias($idIndicador, $data) {
        $CI =& get_instance();
        $listaTable = ($idIndicador != null) ? $CI->m_deta_indi_modal->getAllFrecuenciasByIndicador($idIndicador) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
        			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
        			                                id="tb_frecuencias">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#','class'=>'text-left');
        $head_1 = array('data' => 'Descrip.','class'=>'text-left');
        $head_2 = array('data' => 'Fecha','class'=>'text-center');
        $head_3 = array('data' => 'Medido','class'=>'text-center');
        $head_4 = array('data' => 'Fec.Medi.','class'=>'text-left');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $val = 0;
        $ultimo = 0;
        foreach ($listaTable as $row) {
            $idFreqCrypt = $CI->encrypt->encode($row->id_frecuencia);
            $newformat2 = null;
            $val++;
            $row_0 = array('data' => $row->nro_medicion , 'class' => 'text-left '.$row->color);
            if($row->medido == 'No') {
                $row_1 = array('data' => _getSpan("classDescrip",$idFreqCrypt).utf8_encode($row->desc_frecuencia).'</span>' , 'class' => 'text-left '.$row->color);
                $time = strtotime($row->fecha_medicion);
                $newformat = date('d/m/Y',$time);
                $row_2 = array('data' => _getSpan("classFecha",$idFreqCrypt).$newformat.'</span>' , 'class' => 'text-center '.$row->color);
                if($row->fecha_medido != null){
                    $time2 = strtotime($row->fecha_medido);
                    $newformat2 = date('d/m/Y h:i:s A',$time2);
                }
            } else {
                $row_1 = array('data' => utf8_encode($row->desc_frecuencia) , 'class' => 'text-left '.$row->color);
                $time = strtotime($row->fecha_medicion);
                $newformat = date('d/m/Y',$time);
                $row_2 = array('data' => $newformat  , 'class' => 'text-center '.$row->color);
                if($row->fecha_medido != null){
                    $time2 = strtotime($row->fecha_medido);
                    $newformat2 = date('d/m/Y h:i:s A',$time2);
                }
            }
            $row_3 = array('data' => utf8_encode($row->medido) , 'class' => 'text-center '.$row->color);
            $row_4 = array('data' => $newformat2    , 'class' => 'text-left '.$row->color);

            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            $ultimo = $row->nro_medicion;
        }
        $data['lastMedicion'] = $ultimo + 1;
        $data['tabla'] = $CI->table->generate();
        return $data;
    }
}

if(!function_exists('_calculateEstado')) {
    function calculateEstado($porcentaje, $verde, $amarillo, $tipo){
        if($porcentaje <= $amarillo){
            $color = '<div style="width: 8px;height: 8px;background-color: #F44336;display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            if($tipo == GAUGE_PUESTO){
                $color = '<div style="width: 8px;height: 8px;background-color: rgb(76, 175, 80);display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            }
            else if($tipo == GAUGE_MAXIMO){
                $color = '<div style="width: 8px;height: 8px;background-color: #F44336;display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            }
            else if($tipo == GAUGE_CERO){
                $color = '<div style="width: 8px;height: 8px;background-color: rgb(76, 175, 80);display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            }
        }if($porcentaje < $verde && $porcentaje >= $amarillo){
            $color = '<div style="width: 8px;height: 8px;background-color: orange;display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
        }if($porcentaje >= $verde){
            $color = '<div style="width: 8px;height: 8px;background-color: rgb(76, 175, 80);display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            if($tipo == GAUGE_PUESTO){
                $color = '<div style="width: 8px;height: 8px;background-color: #F44336;display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            }else if($tipo == GAUGE_MAXIMO){
                $color = '<div style="width: 8px;height: 8px;background-color: rgb(76, 175, 80);display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            }
            else if($tipo == GAUGE_CERO){
                $color = '<div style="width: 8px;height: 8px;background-color: #F44336;display: inline-block;margin-right: 5px;cursor: pointer;border-radius:3px"></div>';
            }
        }
        return $color;
    }
}

if(!function_exists('_getSpan')) {
    function _getSpan($clase, $id) {
        return '<span class="'.$clase.' editable editable-click" data-pk="'.$id.'">';
    }
}