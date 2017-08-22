<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_deta_indi {
    
    function buildTableIndicador1($idAula){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getAlumnosByAulaIndicador_1($idAula) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno');
        $head_2 = array('data' => 'Promedio', 'class' => 'col-sm-2');
        $head_3 = array('data' => 'Superó', 'class' => 'col-sm-1');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 0;
        $paso = 0;
        foreach($listaTable as $row){
            $val++;
            $supero = 'NO';
            if($row->color == 'bg-success'){
                $paso++;
                $supero = 'SI';
            }
            $row_col0  = array('data' => $val                 , 'class' => $row->color);
            $row_col1  = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2  = array('data' => $row->promedio_final , 'class' => $row->color);
            $row_col3  = array('data' => $supero , 'class' => $row->color);
            $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
        '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
    
        return $tabla;
    }
    
    /**
     * Tabla para indicador 2 
     * @param $idAula para los alumnos que pertenescan a esa aula
     * @return tabla con alumnos por indicador
     */
    function buildTableIndicador2($idAula, $tutor){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getAlumnosByAula($idAula) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno');
        $head_2 = array('data' => 'Promedio', 'class' => 'col-sm-2');
        $head_3 = array('data' => '¿Superó?', 'class' => 'col-sm-1');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 0;
        $paso = 0;
        foreach($listaTable as $row){
            $val++;
            $supero = 'NO';
            if($row->color == 'bg-success'){
                $paso++;
                $supero = 'SI';
            } else if($row->color == null) {
                $supero = null;
            }
            $row_col0  = array('data' => $val                 , 'class' => $row->color);
            $row_col1  = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2  = array('data' => $row->promedio_final , 'class' => $row->color);
            $row_col3  = array('data' => $supero , 'class' => $row->color);
            $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    Tutor: '.$tutor.'
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
        
        return $tabla;
    }
    
    /**
     * Tabla para indicador 3
     */
    function buildTableIndicador3($idGrado,$idSede){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idGrado != null) ? $CI->m_deta_indi_modal->getDatosAlumnosTercio($idGrado,$idSede) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumnos');
        $head_2 = array('data' => 'Aula');
        $head_3 = array('data' => 'Promedio', 'class' => 'col-sm-2');
        $head_4 = array('data' => 'Superó', 'class' => 'col-sm-1');
        $CI->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
        $val=0;
        $paso = 0;
        foreach($listaTable as $row){
            $val++;
            $supero = 'NO';
            if($row->color == 'bg-success'){
                $paso++;
                $supero = 'SI';
            }
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->desc_aula      , 'class' => $row->color);
            $row_col3 = array('data' => $row->promedio_final , 'class' => $row->color);
            $row_col4 = array('data' => $supero , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4);  
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
        
        return $tabla;
    }
    
    /**
     * Tabla para indicador 4
     */
    function buildTableIndicador4($idAula){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getDatosAlumnosOrdenMerito($idAula) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumnos');
        $head_2 = array('data' => 'Promedio','class' => 'col-sm-2');
        $head_3 = array('data' => 'Orden Merito','class' => 'col-sm-2');
        $CI->table->set_heading($head_0 , $head_1 , $head_2 , $head_3);
        $val = 0;
        $paso = 0;
        foreach ($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->promedio_final , 'class' => $row->color);
            $row_col3 = array('data' => $row->rank           , 'class' => $row->color);
            $CI->table->add_row($row_col0 , $row_col1 , $row_col2 , $row_col3);
            if($row->color == 'bg-success'){
                $paso++;
            }
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>                 
                </div>';
        return $tabla;
    }
    
    /**
     * Tabla para indicador 5, 6, 7, 8
     * @param unknown $idAula
     * @param unknown $materia
     * @return unknown
     */
    function buildTableIndicador5($idAula, $tipoEAI){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getAlumnosSuperanEAI($idAula, $tipoEAI) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno' ,'class' => 'col-sm-5');
        $head_2 = array('data' => 'Medida Rash','class' => 'col-sm-3');
        $head_3 = array('data' => 'Nivel de Logro','class' => 'col-sm-3');       
        $CI->table->set_heading($head_0 , $head_1 , $head_2, $head_3);
        $val = 0;
        $paso = 0;
        $totalInicio = 0;
        $totalProceso = 0;
        $totalSatisfa = 0;
        $otros = 0;
        foreach ($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->materia       ,'class' => $row->color );
            $row_col3 = array('data' => $row->nivel_logro   ,'class' => $row->color);          
            $CI->table->add_row($row_col0 , $row_col1 , $row_col2, $row_col3);
            if($row->color == 'bg-success'){
                $paso++;
            }
            if($row->nivel_logro == 'INICIO'){
                $totalInicio++;
            }else if($row->nivel_logro == 'PROCESO'){
                $totalProceso++;
            }else if($row->nivel_logro == 'SATISFACTORIO'){
                $totalSatisfa++;
            }else{
                $otros++;
            }
        }
         
        $tabla['totalInicio']=$totalInicio;
        $tabla['totalProceso']=$totalProceso;
        $tabla['totalSatisfa']=$totalSatisfa;
        $tabla['otros']=$otros;
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="md md-star-outline" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['totalInicio'].'</p></i>
                    <i class="md md-star-half" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['totalProceso'].'</p></i>
                    <i class="md md-star" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['totalSatisfa'].'</p></i>
                </div>';
        
        return $tabla;
    }
   /*function buildTableIndicador6($idAula,$materia){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getAlumnosEaiSuperado($idAula,$materia) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   data-show-columns="false" data-search="true" id="tb_detalle_indicador">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumnos');
        $head_2 = array('data' => '¿Superado?');
        $CI->table->set_heading($head_0 , $head_1 , $head_2);
        $val = 1;
        foreach ($listaTable as $row){
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->flg            , 'class' => $row->color);
            $CI->table->add_row($row_col0 , $row_col1 , $row_col2);
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }*/
    /**
     * Crea tabla para indicadores de postulantes
     * @author Artur Flores
     * @param $idAula,$materia
     * @return tablaHTML para indicador 9
     */
    function buildTableIndicador9($idAula, $materia){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getAlumnosEceSuperado($idAula, $materia) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
                     'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno','class' => 'col-sm-6');
        $head_2 = array('data' => 'Nivel de logro','class' => 'col-sm-3');
        $head_3 = array('data' => 'Supero','class' => 'col-sm-2');
        $CI->table->set_heading($head_0 , $head_1 , $head_2, $head_3);
        $val = 0;
        $paso = 0.0;
        foreach ($listaTable as $row){
            $val++;
            $supero = 'NO';
            if($row->color == 'bg-success'){
                $paso++;
                $supero = 'SI';
            }
            $row_col0 = array('data' => $val                    , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto    , 'class' => $row->color);
            $row_col2 = array('data' => $row->ind_logro_lectura , 'class' => $row->color);
            $row_col3 = array('data' => $supero , 'class' => $row->color);
            $CI->table->add_row($row_col0 , $row_col1 , $row_col2,$row_col3);
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
        return $tabla;
    }
    /**
     * Crea tabla para indicadores del 19 al 23
     * @author Cesar Villarreal
     * @param $idGrado, $idSede, $idUniversidad
     * @return tablaHTML para indicador 19-22
     */
    function buildTableIndicador10($idSede,$idGrado,$idUniversidad){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idGrado != null) ? $CI->m_deta_indi_modal->getAlumnosPostulantesUnivConsorcio($idSede,$idGrado,$idUniversidad) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno');
        $head_2 = array('data' => 'Aula');
        $head_3 = array('data' => 'Postulo','class' => 'col-sm-2');
        $CI->table->set_heading($head_0,$head_1,$head_2,$head_3);
        $val=0;
        $paso = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->desc_aula      , 'class' => $row->color);
            $row_col3 = array('data' => $row->postulo        , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3);
            if($row->color == 'bg-success'){
                $paso++;
            }
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
        
        return $tabla;
    }
    /**
     *
     * @param unknown $idSede
     * @param unknown $idGrado
     * @param unknown $idUniversidad
     * @return tabla HTML para simulacro de la PUCP
     */
    function buildTableIndicador11($idSede,$idGrado,$idUniversidad){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idGrado != null) ? $CI->m_deta_indi_modal->getAlumnosSimulacroPUCP($idSede,$idGrado,$idUniversidad) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno');
        $head_2 = array('data' => 'Apto','class' => 'col-sm-2');
        $head_3 = array('data' => 'Aula');
        $head_4 = array('data' => 'Simulacro');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $val=0;
        $paso = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->postulo        , 'class' => $row->color);
            $row_col3 = array('data' => $row->desc_aula       , 'class' => $row->color);
            $row_col4 = array('data' => $row->num_simu       , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2, $row_col3, $row_col4);
            if($row->color == 'bg-success'){
                $paso++;
            }
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
        
        return $tabla;
    }
    
    function buildTableIndicador12($idSede,$idGrado,$idUniversidad){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idGrado != null) ? $CI->m_deta_indi_modal->getAlumnosSimuUniv($idSede,$idGrado,$idUniversidad) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno');
        $head_2 = array('data' => 'Aula');
        $head_3 = array('data' => 'Puntaje','class' => 'col-sm-2');
        $head_4 = array('data' => 'Supero','class' => 'col-sm-1');
        $CI->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
        $val=0;
        $paso = 0;
        foreach($listaTable as $row){
            $val++;
            $supero = 'NO';
            if($row->color == 'bg-success'){
                $paso++;
                $supero = 'SI';
            }
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->desc_aula      , 'class' => $row->color);
            $row_col3 = array('data' => $row->puntaje        , 'class' => $row->color);
            $row_col4 = array('data' => $supero              , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4);
        }
        $tabla = $this->calculaPorcentaje($val, $paso);       
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
        
        return $tabla;
    }  
    /* Arturo Flores
       fecha 12/10/2015
       Indicador del 24-28 (buildTableIndicador13)
       Numero de ingresantes al consorcio grado 5to secundaria
     */
    function buildTableIndicador13($idSede,$idGrado,$isPUCP){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        if($isPUCP == 1){
            $listaTable = ($idGrado != null) ? $CI->m_deta_indi_modal->getAlumnosIngresantesPUCP($idSede,$idGrado,$isPUCP) : array();
        }else{
            $listaTable = ($idGrado != null) ? $CI->m_deta_indi_modal->getAlumnosIngresantesUnivConsorcioMenosPUCP($idSede,$idGrado,$isPUCP) : array();
        }
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false"id="tb_detalle_indicador">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno');
        $head_2 = array('data' => 'Participó','class' => 'col-sm-2');
        $head_3 = array('data' => ($isPUCP == 1) ? 'Ingreso' : 'Puntaje');
        $CI->table->set_heading($head_0,$head_1,$head_2,$head_3);
        $val  = 0;
        $paso = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->desc_aula      , 'class' => $row->color);
            
            if($isPUCP == 1){
            $row_col3  = array('data' => $row->ingreso       , 'class' => $row->color);
            }else{
            $row_col3  = array('data'  => $row->puntaje       , 'class' => $row->color);}           
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3);
            if($row->color == 'bg-success'){
                $paso++;
            }
        }
        $tabla = $this->calculaPorcentaje($val, $paso);
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['paso'].'('.$tabla['porcentaje'].'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['paso']).'</p></i>
                </div>';
        
        return $tabla;
    }
    /* Arturo Flores
     fecha 12/10/2015
     Indicador del 29-31 (buildTableIndicador14)
     Numero de logros y galardones obtenidos en la disciplina
     */
    function buildTableIndicador14($idNivel,$nivelComp,$tipoDiscip,$idDisciplina){//COPAS
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idNivel != null) ? $CI->m_deta_indi_modal->getLogrosGalardonesDisciplinas($idNivel,$nivelComp,$tipoDiscip,$idDisciplina) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
                      'table_close' => '</table>');       
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Disciplina');
        $head_2 = array('data' => 'Tipo','class' ,'class' => 'col-sm-3');
        $head_3 = array('data' => 'Nivel Competitivo');
        $head_4 = array('data' => 'Logros');
        $head_5 = array('data' => 'Fecha','class' => 'col-sm-3');
        $head_6 = array('data' => 'Docente','class' => 'col-sm-3');
        $CI->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4,$head_5,$head_6);
        $val = 0;
        $totalNroCopas = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                    );
            $row_col1 = array('data' => $row->desc_disciplina   );
            $row_col2 = array('data' => $row->tipo_disciplina   );
            $row_col3 = array('data' => $row->nivel_competitivo );
            $row_col4 = array('data' => $row->nro_copas         );
            $fecha = ($row->fecha == null) ? null : date('d/m/Y',strtotime($row->fecha));
            $row_col5 = array('data' => $fecha                  );
            $row_col6 = array('data' => $row->nom_persona       );
            
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4,$row_col5,$row_col6);
          
            $totalNroCopas = $totalNroCopas + $row->nro_copas;
        }
        $tabla['nroCopas'] = $totalNroCopas;
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="scwl-icon_logros" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$totalNroCopas.'</p></i>
                </div>';
        return $tabla;
    }
    /* Arturo Flores
     fecha 12/10/2015
     Indicador del 32 -33 (buildTableIndicador15)
     Número de alumnos participantes en las evaluaciones para la certificación  internacional del idioma inglés
     */
    function buildTableIndicador15($idAula,$idEstado){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        if($idAula != null && $idEstado == 0){
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getAlumnosParticipantesEvalCertificacionIngles($idAula,$idEstado) : array();
        }
        if($idAula != null && $idEstado == 2){
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getAlumnosObtienenCertificacionIngles($idAula) : array();
        }
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
            
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Alumno','class' => 'col-sm-7');
        $head_2 = array('data' => 'Participó','class' => 'col-sm-2');
        $CI->table->set_heading($head_0,$head_1,$head_2);
        $val  = 0;
        $paso = 0;
        $totalParticipantes = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->participo      , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2);
            
            if($row->participo   == 'Sí'){
             $totalParticipantes++;
            }
            if($row->color == 'bg-success'){
                $paso++;
            }
        }
        $tabla['participaron'] = $totalParticipantes;
        $tabla['total']        = $val;
        $porcentaje = 0;
        if($val != 0){
            $porcentaje = (100*$totalParticipantes)/$val;
        }
        
        $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                    <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                    <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.$tabla['participaron'].'('.$porcentaje.'%)'.'</p></i>
                    <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']-$tabla['participaron']).'</p></i>
                </div>';
        
        return $tabla;
    }
    /* Arturo Flores
     fecha 12/10/2015
     Indicador del 34-35 (buildTableIndicador16)
     Número de alumnos participantes en las evaluaciones para la certificación  internacional del idioma inglés
     */
    function buildTableIndicador16($idSede,$flg_certificado){//docentes
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idSede != null) ? $CI->m_deta_indi_modal->getDocentesCertificaciónEFCE_INGLESNATIVO($idSede,$flg_certificado) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#toolBarDetalle"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
    
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Docente','class' => 'col-sm-7');
        $head_2 = array('data' => ($flg_certificado == 'flg_certi_efce') ? 'Certificado' : 'Ingles Nativo','class' => 'col-sm-2');
        $CI->table->set_heading($head_0,$head_1,$head_2);
        $val  = 0;
        $paso = 0;
        $totalDocentesCertificado = 0;
        $totalDocentesInglesNativo = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                 , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombrecompleto , 'class' => $row->color);
            $row_col2 = array('data' => $row->flg            , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2);
    
            if($flg_certificado == 'flg_certi_efce'){
                if($row->flg   == 'Sí'){
                   $totalDocentesCertificado++;
                }
            }else{
                if($row->flg   == 'Sí'){
                   $totalDocentesInglesNativo++;
                }
            }  
            if($row->color == 'bg-success'){
                $paso++;
            }
        }
        $tabla['total'] = $val;
        if($flg_certificado == 'flg_certi_efce'){
                $tabla['totalDocentesCertificado']  = $totalDocentesCertificado;
                $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                                <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                                <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px">'.$totalDocentesCertificado.'</p></i>
                                <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']- $tabla['totalDocentesCertificado']).'</p></i>
                 </div>';         
        }else{
                $tabla['totalDocentesInglesNativo'] = $totalDocentesInglesNativo;               
                $tabla['tabla'] = $CI->table->generate().
                '<div id="toolBarDetalle" style="margin-top:-30px">
                                <i class="mdi mdi-face" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$tabla['total'].'</p></i>
                                <i class="mdi mdi-check" style="margin-right:15px;color:#959595;font-size:14px">'.$totalDocentesInglesNativo.'</p></i>
                                <i class="mdi mdi-close" style="color:#959595;font-size:14px"><p style="margin-left:4px;display:inline;">'.($tabla['total']- $tabla['totalDocentesInglesNativo']).'</p></i>
                </div>';
        }
        return $tabla;
        
    }
    /* Arturo Flores
     fecha 13/10/2015
     Indicador del 11-13 (buildTableIndicador8)
     Puesto alcanzado por los estudiantes de la secundaria en la evaluacion PPU Aptitud Numerica/Lectura/ciencias
     */
    function buildTableIndicador8($PPU, $idSede, $idGrado){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($PPU != null) ? $CI->m_deta_indi_modal->getEvaluacionesPPU($PPU, $idSede, $idGrado) : array();

        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
    
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Puesto','class' => 'col-sm-2');
        $head_2 = array('data' => 'Evaluación');
        $head_3 = array('data' => 'Grado');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val  = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val         );
            $row_col1 = array('data' => $row->puesto );
            $row_col2 = array('data' => $row->desc_ppu);
            $row_col3 = array('data' => $row->desc_grado);
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3);
        }
        $tabla['totalPPu']  = null;
        $tabla['tabla']     = $CI->table->generate();
    
        return $tabla;
    }
    /* Arturo Flores
     fecha 13/10/2015
     Indicador del 36 (buildTableIndicador17)
     Tardanza escolar: cantidad de alumnos que llegan tarde en promedio por mes (aula)
     */
    function buildTableIndicador17($idAula){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idAula != null) ? $CI->m_deta_indi_modal->getTardanzaEscolarAlumnos($idAula) : array();
    
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
    
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Mes' ,'class' => 'col-sm-5');
        $head_2 = array('data' => 'Porcentaje','class' => 'col-sm-4');
        $CI->table->set_heading($head_0,$head_1,$head_2);
        $val  = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val         );
            $row_col1 = array('data' => $row->mes_nomb );
            $row_col2 = array('data' => $row->porc_tard);
            $CI->table->add_row($row_col0,$row_col1,$row_col2);
        }
        $tabla['porcentajetard']  = null;
        $tabla['tabla']     = $CI->table->generate();
    
        return $tabla;
    }
    
    /* Ricardo Vasquez
     fecha 16/12/2015
     Indicador del 90 - 92 (buildTableIndicador90)
     */
    function buildTableIndicador90($idSede, $idAreaGen, $idAreaEsp,$idIndicador){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = $CI->m_deta_indi_modal->getVacantesBySedeAreaEstado($idSede, $idAreaGen, $idAreaEsp, null/*'CONTRATADO'*/);
        $porcentaje = 0;
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
    
                                        'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#'             ,'class' => 'col-sm-1');
        $head_1 = array('data' => 'Solicitante'   ,'class' => 'col-sm-3');
        $head_3 = array('data' => 'Fecha Inicio'  ,'class' => 'col-sm-2');
        $head_4 = array('data' => 'Atendido por'  ,'class' => 'col-sm-3');
        $head_2 = array('data' => 'Fecha Termino' ,'class' => 'col-sm-2');
        $head_5 = array('data' => 'Estado'        ,'class' => 'col-sm-3');
        $CI->table->set_heading($head_0,$head_1,$head_3,$head_4,$head_2, $head_5);
        $val  = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                        , 'class' => $row->color);
            $row_col1 = array('data' => $row->nombres_solicitante   , 'class' => $row->color);
            $row_col3 = array('data' => $row->fec_regi              , 'class' => $row->color);
            $row_col4 = array('data' => $row->nombres_usua_atencion , 'class' => $row->color);
            $row_col2 = array('data' => $row->fec_fin               , 'class' => $row->color);
            $row_col5 = array('data' => $row->estado                , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col3,$row_col4,$row_col2, $row_col5);
            $porcentaje = $row->promatencion;
        }
        $tabla['porcentajetard']  = null;
        if($idIndicador == INDICADOR_90) {
            $tabla['error'] = EXIT_SUCCESS;
            $tabla['tabla']     = $CI->table->generate();
        } else if($idIndicador == INDICADOR_92) {
            $tabla['error'] = EXIT_SUCCESS;
            $tabla['tabla']     = '<div id="toolBarDetalle" style="margin-top: 30px">
                                      <i class="md-today" style="margin-right:15px;color:#959595;font-size:14px;margin-left:25px"><p style="margin-left:4px;display:inline;">'.$porcentaje.' dias</p></i>
                                  </div>'.$CI->table->generate();
        }
        return $tabla;
    }
    
    function calculaPorcentaje($total , $success){
        $data['porcentaje'] = 0;
        if($success != 0 ){
            $data['porcentaje'] = round(($success*100)/$total,2);
            $data['total'] = $total;
            $data['paso'] = $success;
        }
        else{
            $data['total'] = $total;
            $data['paso'] = $success;
        }
        return $data;
    }

    /**
     * Metodo para mostrar el detalle dando click al OJO en el indicador 58 SD
     * @author dfloresgonz 23.10.2015
     * @param int $idSede
     * @param int $idNivel
     * @param int $idArea
     * @return tabla HTML a mostrar en el modal
     */
    function buildTableIndicadorDocentesSD($idSede, $idNivel, $idArea) {
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = ($idSede != null && $idNivel != null && $idArea != null) ? $CI->m_deta_indi_modal->getNotasDocentesSD_Indicador_58($idSede, $idNivel, $idArea) : array();
    
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
    
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#'           ,'class' => 'col-sm-1');
        $head_1 = array('data' => 'Docente'     ,'class' => 'col-sm-8');
        $head_2 = array('data' => 'Promedio SD' ,'class' => 'col-sm-2');
        $head_3 = array('data' => '¿Supera?'    ,'class' => 'col-sm-1');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val  = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val             , 'class' => $row->color);
            $row_col1 = array('data' => $row->profesor   , 'class' => $row->color);
            $row_col2 = array('data' => $row->nota       , 'class' => $row->color);
            $row_col3 = array('data' => $row->estado     , 'class' => $row->color);
            $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
        }
        $tabla['notasSD'] = null;
        $tabla['tabla'] = $CI->table->generate();
        return $tabla;
    }
    
    /* Cesar Villarreal
     fecha 17/12/2015
     Indicador del 94 (buildTableIndicador94)
     */
    function buildTableIndicador94($idSede,$idAreaGen,$idAreaEsp){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $listaTable = $CI->m_deta_indi_modal->getCapacitacionesBySedeArea($idSede, $idAreaGen, $idAreaEsp);
        $porcentaje = 0;
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               data-show-columns="false" id="tb_detalle_indicador">',
        
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#'               ,'class' => 'col-sm-1');
        $head_1 = array('data' => 'Programada por'  ,'class' => 'col-sm-3');
        $head_2 = array('data' => 'Descripción'     ,'class' => 'col-sm-3');
        $head_3 = array('data' => 'Fec. Programada' ,'class' => 'col-sm-2');
        $head_4 = array('data' => 'Estado'          ,'class' => 'col-sm-2');
        $head_5 = array('data' => 'Fec. Realizada'  ,'class' => 'col-sm-2');
        $head_6 = array('data' => 'Observaciones'   ,'class' => 'col-sm-3');
        $CI->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4,$head_5,$head_6);
        $val  = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                    ,'class' => $row->color);
            $row_col1 = array('data' => $row->audi_pers_regi    ,'class' => $row->color);
            $row_col2 = array('data' => $row->desc_capacitacion ,'class' => $row->color);
            $row_col3 = array('data' => $row->fec_programada    ,'class' => $row->color);
            $row_col4 = array('data' => $row->estado            ,'class' => $row->color);
            $row_col5 = array('data' => $row->fec_realizada     ,'class' => $row->color);
            $row_col6 = array('data' => $row->observaciones     ,'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4,$row_col5,$row_col6);
        }
        $tabla['porcentajetard']  = null;
        $tabla['error'] = EXIT_SUCCESS;
        $tabla['tabla'] = $CI->table->generate();
        return $tabla;
    }
    
    function buildTableIndicadorIncidencia($idSede, $idAreaGen, $idAreaEsp,$idIndicador){
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        //$tipoIncidencia = ($idIndicador == INDICADOR_95) ? INC_DECLARACION : ($idIndicador == INDICADOR_96) ? INC_CLIMA_LABORAL : INC_DESCANSO_MEDICO;
        if($idIndicador == INDICADOR_95) {
            $tipoIncidencia = INC_DECLARACION;
        } else if($idIndicador == INDICADOR_96) {
            $tipoIncidencia = INC_CLIMA_LABORAL;
        } else {
            $tipoIncidencia = INC_DESCANSO_MEDICO;
        }
        $listaTable = $CI->m_deta_indi_modal->getIncidenciasBySedeArea($idSede, $idAreaGen, $idAreaEsp,$tipoIncidencia,$idIndicador);
        $porcentaje = 0;
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                               data-show-columns="false" id="tb_detalle_indicador">',
    
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#'                  ,'class' => 'col-sm-1');
        $head_1 = array('data' => 'Descripción'        ,'class' => 'col-sm-3');
        $head_2 = array('data' => 'Fecha Incidencia'   ,'class' => 'col-sm-2');
        $head_3 = array('data' => 'Persona Incidencia' ,'class' => 'col-sm-3');
        $head_4 = array('data' => 'Persona Registro'   ,'class' => 'col-sm-3');
        $CI->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
        $val  = 0;
        foreach($listaTable as $row){
            $val++;
            $row_col0 = array('data' => $val                   , 'class' => $row->color);
            $row_col1 = array('data' => $row->desc_incidencia  , 'class' => $row->color);
            $row_col2 = array('data' => $row->fecha_incidencia , 'class' => $row->color);
            $row_col3 = array('data' => $row->nombres_personal , 'class' => $row->color);
            $row_col4 = array('data' => $row->audi_pers_regi   , 'class' => $row->color);
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4);
        }
        $tabla['porcentajetard']  = null;
        $tabla['error'] = EXIT_SUCCESS;
        $tabla['tabla'] = $CI->table->generate();
        return $tabla;
    }
    
    function buildTablePuntualidad_Asistencia($idSede, $idAreaGen, $idAreaEsp, $idIndicador, $year) {
        $CI =& get_instance();
        $CI->load->model('mf_indicadores/m_deta_indi_modal');
        $CI->load->library('table');
        $todo = null;
        $deta = null;
        if($idSede == null || $idAreaGen == null || $idAreaEsp == null || $idIndicador == null) {
            $listaTable = array();
        } else {
            if($idIndicador == INDICADOR_98) {
                $listaTable = $CI->m_deta_indi_modal->getPuntualidad($idSede, $idAreaGen, $idAreaEsp, $year);
                $todo = 'Fichajes';
                $deta = 'Temprano';
            } else if($idIndicador == INDICADOR_99) {
                $listaTable = $CI->m_deta_indi_modal->getAsistencia($idSede, $idAreaGen, $idAreaEsp, $year);
                $todo = 'Días Labo.';
                $deta = 'Asistió';
            }   
        }
        $porcentaje = 0;
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                               data-show-columns="false" id="tb_detalle_indicador">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#'        ,'class' => 'text-left col-xs-1 col-sm-1 col-md-1 col-lg-1');
        $head_1 = array('data' => 'Persona'  ,'class' => 'text-left col-xs-6 col-sm-6 col-md-6 col-lg-6');
        $head_2 = array('data' => $todo      ,'class' => 'text-center col-xs-2 col-sm-2 col-md-2 col-lg-2');
        $head_3 = array('data' => $deta      ,'class' => 'text-center col-xs-2 col-sm-2 col-md-2 col-lg-2');
        $head_4 = array('data' => 'Detalle'  ,'class' => 'text-center col-xs-1 col-sm-1 col-md-1 col-lg-1');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $val  = 0;
        foreach($listaTable as $row) {
            $val++;
            $boton = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="verDetalleAsistenciaPuntualidad(\''.$CI->encrypt->encode($row->nif).'\')"><i class="mdi mdi-remove_red_eye"></i></button>';
            $row_col0 = array('data' => $val          , 'class' => 'text-left '.$row->clase_activo);
            $row_col1 = array('data' => _ucwords($row->persona) , 'class' => 'text-left '.$row->clase_activo);
            $row_col2 = array('data' => $row->todos   , 'class' => 'text-center '.$row->clase_activo);
            $row_col3 = array('data' => $row->detalle , 'class' => 'text-center '.$row->clase_activo);
            $row_col4 = array('data' => $boton        , 'class' => 'text-center '.$row->clase_activo);
            $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3, $row_col4);
        }
        $tabla['tabla'] = $CI->table->generate();
        $tabla['error'] = EXIT_SUCCESS;
        return $tabla;
    }
}