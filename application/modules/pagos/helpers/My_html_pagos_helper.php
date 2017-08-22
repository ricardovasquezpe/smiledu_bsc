<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__buildTablaCronogramaHTML')) {
    function __buildTablaCronogramaHTML($arraySedes) {
        $CI =& get_instance();
        $CI->load->model('m_cronograma');
        $tabla     = null;
        $tabla    .=  '<table id="cronogramaSede" class="tree table">';
        $tabla    .= '<tr>
                          <td class="col-xs-5 text left p-l-20" style="border-top: none;">Descripci&oacute;n</td>
                          <td class="col-sm-2 text-center" style="border-top: none;">Acciones</td>
                      </tr>';
        $val  = 0;
        $val1 = null;
        foreach ($arraySedes as $row) {
            $idCryptSede = _encodeCI($row->nid_sede);
            $val++;
            $val1 = $val;
            $tabla .='<tr class="treegrid-'.$val.'">
    	                  <td class="text-left p-l-10">'.$row->desc_sede.'</td>
    	                  <td class="text-center">
        	                  <button onclick="abrirCerrarModalCrearCrono(\''.$idCryptSede.'\',\''.$row->desc_sede.'\')" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                  <i class="mdi mdi-edit"></i>
        	                  </button>
    	                  </td>
                	  </tr>';
            $ListaCronogramas = $CI->m_cronograma->getCronogramaByFiltro($row->nid_sede);
            foreach ($ListaCronogramas as $row1) {
                $idCryptCronograma = _encodeCI($row1->id_cronograma);
                $tituloCronograma  = $row1->desc_cronograma;
                $flg_cerrado       = ($row1->flg_cerrado == FLG_CERRADO) ? 'disabled' : 'onclick="modal_eliminar(\'' . $idCryptCronograma . '\')" '; 
                $botones = '<button data-target="#modalVistaPreviaCronograma" onclick="vista_previa_cronograma(\'' . $idCryptCronograma . '\',\'' . $tituloCronograma . '\')" data-toggle="modal" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                <i class="mdi mdi-visibility"></i></button>
                            <button onclick="getCronogramaDetalle(\'' . $idCryptCronograma . '\')" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                <i class="mdi mdi-edit"></i></button>
                            <button data-toggle="modal" '.$flg_cerrado.' class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"> 
                                <i class="mdi mdi-delete"></i></button>';
                $val++;
                $tabla  .='<tr class="treegrid-'.($val).' treegrid-parent-'.$val1.'" class="p-l-10">
                    	        <td class="text-left">'.$row1->desc_cronograma.'</td>
                    	        <td class="text-center">'.$botones.'</td>
                    	   </tr>';
            }
        }
        $tabla .= '</table>';
        return $tabla;
    }
}

if(!function_exists('__getFlechasByYear')) {
    function __getFlechasByYear($yearActual, $tipoCrono) {
        $CI =& get_instance();
        $CI->load->model('m_pensiones');
        $arrayYear = $CI->m_pensiones->getYear($tipoCrono);
        $lastYear  = null;
        $opcion    = '';
        $opcion1   = '';
        $opcion2   = '';
        $click     = 'onclick="changeYearSedes(\''.($yearActual - 1).'\');"';
        $click2    = 'onclick="changeYearSedes(\''.($yearActual + 1).'\');"';
        if($arrayYear != null) {
            foreach ($arrayYear as $year) {
                $lastYear = $year->year;
            }
            $disabled = 'disabled';
            $opcion2  = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.(($arrayYear[0]->year == $yearActual) ? 'disabled' : $click).'><i class="mdi mdi-keyboard_arrow_left"></i></button>';
            $opcion1  = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.(($lastYear >= $yearActual) ? $click2 : 'disabled').'><i class="mdi mdi-keyboard_arrow_right"></i></button>';
        }else {
            $lastYear = date("Y");
            $opcion2  = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.(($lastYear == $yearActual) ? 'disabled' : $click).'><i class="mdi mdi-keyboard_arrow_left"></i></button>';
            $opcion1  = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.(($lastYear >= $yearActual) ? $click2 : 'disabled').'><i class="mdi mdi-keyboard_arrow_right"></i></button>';
        }
        $opcion .=$opcion2;
        $opcion .=$opcion1;
        return $opcion;
    }
}

if(!function_exists('__buildTablaSedesHTML')) {
    function __buildTablaSedesHTML($sedes, $pensiones_year,$tipoCrono) {
        $CI =& get_instance();
        $CI->load->model('m_pensiones');
         
        $tmpl = array(
            'table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-show-columns="false"
    			                    data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                    id="tb_sedes">',
            'table_close' => '</table>'
        );
        $CI->table->set_template($tmpl);
        $head_2 = array('data' => 'Sedes'             , 'class' => 'text-left');
        $head_3 = array('data' => 'C.Ingr. Ref.(S/)'  , 'class' => 'text-right');
        $head_4 = array('data' => 'Mat. Rat. Ref.(S/)', 'class' => 'text-right');
        $head_5 = array('data' => 'Pen. Ref.(S/)'     , 'class' => 'text-right');
        $head_6 = array('data' => 'Desc. Ref.(%)'     , 'class' => 'text-right', 'data-visible'=>'false');
        $head_7 = array('data' => 'Editar'            , 'class' => 'text-center');
        $montos=array();
        $CI->table->set_heading($head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
        foreach ($sedes as $row) {
            $encryptSede=_encodeCI($row->nid_sede);
            $montos    = $CI->m_pensiones->getAllMontos($row->nid_sede, $pensiones_year,$tipoCrono);
            $condicion = $montos['flg_cerrado'];
            $boton     = null;
            $nextYear  = null;
            $existe    = null;
            $lastYear  = null;
            if($pensiones_year == _getYear()) {
                $nextYear = 1;
            }else{
                $existe = $CI->m_pensiones->getExiste($row->nid_sede);
                if($existe['existe'] == 't') {
                $previousYear = $CI->m_pensiones->getPreviousYear($row->nid_sede, ($pensiones_year-1));
                    if($previousYear == 0) {
                        $nextYear = null;
                    }else {
                        $nextYear = 1;
                    }
                }else{
                    $nextYear = null;
                }
            }
            $icon = null;
            ($nextYear != null) ? $boton = ' onclick="openModalEditarCuota(\''.$encryptSede.'\', \''.$row->desc_sede.'\')" ': $boton = ' onclick="openModalEditarCuota(\''.$encryptSede.'\', \''.$row->desc_sede.'\')" ' /*' disabled '*/;
            ($condicion == 0 && $pensiones_year >= date("Y")) ? $condicion = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$boton.'>
    										                                      <i class="mdi mdi-mode_edit"></i>
    										                                  </button>' : $condicion = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" '.$boton.'>
    										                                      <i class="mdi mdi-mode_edit"></i>
    										                                  </button>' /*$condicion='<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cursor__default" disabled>
                    										                                               <i class="mdi mdi-lock"></i>
                    										                                           </button>'*/;
            $cronograma = $CI->m_pensiones->getCalendarioBySede($row->nid_sede, $pensiones_year,$tipoCrono);
            $idCryptCronograma = _encodeCI($cronograma['id_cronograma']);
            $accion = ($cronograma != null) ? ' onclick="getCronogramaDetalle(\'' . $idCryptCronograma . '\');" ': ' onclick="getCronogramaDetalle(\'' . $idCryptCronograma . '\');" ' /*' disabled '*/;
            $flgCerrado = (($cronograma['flg_cerrado'] != 0 && $cronograma['flg_cerrado'] != null) ?'data-toggle="tooltip" data-placement="bottom" title="Cronograma Listo"' :'data-toggle="tooltip" data-placement="bottom" title="Define tu Cronograma"' );
            ($accion == ' disabled ') ? $icon = '<i class="mdi mdi-event_busy"></i>' : $icon = '<i class="mdi mdi-event"></i>';
            $btn_calendar = ' <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect m-r-5" '.$accion.$flgCerrado.'>
                                  '.$icon.'
                              </button>';
            $row_cell_2 = array('data' => '<p attr-id-sede="'.$encryptSede.'"></p>'.$btn_calendar.$row->desc_sede, 'class' => 'text-left');
            $row_cell_3 = array('data' => $montos['cuota_ingreso']  , 'class' => 'text-right');
            $row_cell_4 = array('data' => $montos['monto_matricula'], 'class' => 'text-right');
            $row_cell_5 = array('data' => $montos['monto_pension']  , 'class' => 'text-right');
            $row_cell_6 = array('data' => $montos['descuento_sede'] , 'class' => 'text-right');
            $row_cell_7 = array('data' => $condicion                , 'class' => 'text-center');
    
            $CI->table->add_row($row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6, $row_cell_7);
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('__buildTablaBecasHTML')) {
    function __buildTablaBecasHTML($arrayBecas) {
        $CI =& get_instance();
        $CI->load->model('m_pensiones');
        $CI->load->model('m_becas');
        $tmpl = array(
            'table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                  data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                  id="tb_becas">',
            'table_close' => '</table>'
        );
        $CI->table->set_template($tmpl);
        $head_1 = array('data' => 'Tipo'      , 'class' => 'text-left');
        $head_2 = array('data' => '% Beca'    , 'class' => 'text-left');;
        $head_3 = array('data' => 'A&ntilde;o', 'class' => 'text-center');
        $head_4 = array('data' => 'Acciones'  , 'class' => 'text-center');
    
        $CI->table->set_heading($head_1, $head_2, $head_3, $head_4);
        foreach ($arrayBecas as $row) {
            $becaEncripty=_encodeCI($row->id_condicion);
            $row_cell_1 = array('data' => $row->desc_condicion  ,  'class' => 'text-left');
            $row_cell_2 = array('data' => $row->porcentaje_beca ,  'class' => 'text-left');
            $row_cell_3 = array('data' => $row->year_condicion  ,  'class' => 'text-center');
            $cont = $CI->m_becas->countBecas($row->id_condicion);
            $boton = null;
            if($cont == 0){
                $boton = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModaleditarBeca(\''.$becaEncripty.'\');">
    	                      <i class="mdi mdi-mode_edit"></i>
    	                  </button>';
            }else{
                $boton = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="bottom" title="Ya est&aacute; en uso">
                              <i class="mdi mdi-lock"></i>
                          </button>';
            }
            $row_cell_4 = array('data' =>  $boton, 'class' => 'text-center');
            $CI->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4);
        }
        if(count($arrayBecas) == 0){
            $empty = '  <div class="img-search m-b-30">
                            <img src="'.base_url().'public/general/img/smiledu_faces/empty_add.png">
                            <p>Ups! A&uacute;n no hay becas.</p>
                            <p>Para registrar ingresa al <strong>+</strong>. </p>
                        </div>';
            return $empty;
        } else{
            return $CI->table->generate();
        }
    }   
}

if(!function_exists('__buildTablaPromocionesHTML')) {
    function __buildTablaPromocionesHTML($arrayPromociones){
        $CI =& get_instance();
        $CI->load->model('m_pensiones');
    
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                            data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                            id="tb_promociones">',
                      'table_close' => '</table>'
        );
        $CI->table->set_template($tmpl);
        $head_1 = array('data' => 'Descripci&oacute;n' , 'class' => 'text-left');
        $head_2 = array('data' => 'Cant. Cuotas', 'class' => 'text-left');;
        $head_3 = array('data' => 'Descuento %' , 'class' => 'text-center');
        $head_4 = array('data' => 'Acciones'    , 'class' => 'text-center');
        
        $CI->table->set_heading($head_1, $head_2, $head_3, $head_4);
        foreach ($arrayPromociones as $row) {
            $promocionEncripty=_encodeCI($row->id_promocion);
            $row_cell_1 = array('data' => utf8_decode($row->desc_promo)           , 'class' => 'text-left');
            $row_cell_2 = array('data' => $row->cant_cuotas          , 'class' => 'text-left');
            $row_cell_3 = array('data' => $row->porcentaje_descuento , 'class' => 'text-center');
            $row_cell_4 = array('data' =>  '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModaleditarPromocion(\''.$promocionEncripty.'\');">
    	                                        <i class="mdi mdi-mode_edit"></i>
    	                                    </button>', 'class' => 'text-center');
             
            $CI->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4);
        }
        if(count($arrayPromociones) == 0){
            $empty = '<div class="img-search m-b-30">
                          <img src="'.base_url().'public/general/img/smiledu_faces/empty_add.png">
                          <p>Ups! A&uacute;n no registraste promociones.</p>
                          <p>Para registrar ingresa al <strong> + </strong>.</p>    
                      </div>';
            return $empty;
        } else{
            return $CI->table->generate();
        }
    }
}

if(!function_exists('__buildEditItemCronograma')) {
    /**
     * Transforma la fecha en el formato indicado para una tabla
     * @author dfloresgonz
     * @since 22.03.2016
     * @param date $fecha
     * @param string $formato d/m/Y, d/m/Y h:i:s A
     * @return fecha con formato
     */
    function __buildEditItemCronograma($result,$cabecera=null) {
        $i=0; $text='';  //print_r($result[0]);exit;
        foreach ($result[0] as $item){
            $text .= '<div class="col-sm-12">';
            $text .= '<div class="mdl-textfield mdl-js-textfield">';
            $text .= '<input class="mdl-textfield__input" type="text" id="item_cronograma_'.$i.'" name="item_cronograma_'.$i.'" value="'.$item.'">';
            $text .= '<label class="mdl-textfield__label" for="item_cronograma_'.$i.'">'.$cabecera[$i].'</label>';
            $text .= '</div></div>'; $i++;
        }
        return $text;
    }
}

if(!function_exists('__buildComboYearCronogramaBySede')) {
    function __buildComboYearCronogramaBySede($sede){
        $CI =& get_instance();
        $CI->load->model('m_cronograma');
        $conceptos = $CI->m_cronograma->getYearCronoBySede($sede);
        $opt = null;
        foreach($conceptos as $row){
            $opt .= '<option value="'.$row->year.'">'.$row->year.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildCardsAlumnosHTML')) {
    function __buildCardsAlumnosPagosHTML($personas,$tipo = 'pagar'){
        $CI =& get_instance();
        $CI->load->model('m_movimientos');
	    $card = null;
	    $val = 0;
	    foreach($personas as $row){
	        $accion = '<div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored btn_modal_card " onclick="goToPagosAlumno(\''._encodeCI($row->nid_persona).'\')">Pagar</button>
                       </div>';
	        if($tipo == 'padres'){
	            $accion = '<div class="mdl-card__actions">
                               <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored" onclick="goToDetalleAlumno(\''.base_url().'pagos/c_pagos'.'\');">IR A PAGAR</button>
                           </div>';
	        } else if($tipo == 'main'){
	            $accion = '<div class="mdl-card__actions">
                               <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored btn_modal_card" onclick="goToPagosAlumno(\''._encodeCI($row->nid_persona).'\')">Pagar</button>
                           </div>';
	        }
	        $val++;
	        $class = ($row->cuotas_deuda > 0) ? 'moroso' : 'puntual';
	        $opacity = ($row->estado == 'DATOS_INCOMPLETOS') ? 'part-disable' : null;
	        $foto    = (file_exists(FOTO_PROFILE_PATH . 'estudiantes/' . $row->foto_persona)) ?  RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_SMILEDU.FOTO_DEFECTO;
//             $foto = null;
	        $estado  = (($row->cuotas_deuda > 0) ? $row->cuotas_deuda.' cuota(s) vencida(s)' : (($row->adelanto > 0 ) ? 'Adelantado' : 'Al d&iacute;a'));
	        $card .= '  <div class="mdl-card mdl-student '.$opacity.'">
                            <div class="mdl-card__title">
                                <img alt="Student" src="'.$foto.'">
                            </div>
                            <div class="mdl-card__supporting-text pago '.$class.'">
                                <div class="row p-0 m-0">                                   
                                    <div class="col-xs-12 student-name">'.$row->apellidos.'</div>
                                    <div class="col-xs-12 student-name" >'.$row->nombres.'</div>
                                    <div class="col-xs-12 student-state">'.($estado).'</div>
                                    <div class="col-xs-12 student-head"><strong>Detalles del Estudiante:</strong></div>
                                    <div class="col-xs-7  student-item">C&oacute;d. de Alumno</div>
                                    <div class="col-xs-5  student-value">'.$row->cod_alumno.'</div>
                                    <div class="col-xs-7  student-item">C&oacute;d. de Familia</div>
                                    <div class="col-xs-5  student-value">'.$row->cod_familia.'</div>
                                    <div class="col-xs-3  student-item">Sede</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_sede.'</div>
                                    <div class="col-xs-3  student-item">Nivel</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_nivel.'</div>
                                    <div class="col-xs-3  student-item">Grado</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_grado.'</div>
                                    <div class="col-xs-3  student-item">Aula</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_aula.'</div>
                                </div>
                            </div>
                            '.$accion.'
                            <div class="mdl-card__menu">
                                <button id="pago'.$val.'" class="mdl-button mdl-js-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                            </div>
                        </div>';
	    }
	    return $card;
	}
}

if(!function_exists('__buildCardsAulasHTML')) {
    function __buildCardsAulasPagosHTML($aulas){
        $CI =& get_instance();
        $CI->load->model('m_movimientos');
        $card = null;
        $val = 0;
        foreach($aulas as $row){
            $idAulaCrypt = _encodeCI($row->nid_aula);
            $val++;
            $class       = ($row->cant_vencidos > 0) ? 'moroso' : 'puntual';
            $card .= 	'<div class="mdl-card mdl-classroom '.((($row->year) != _getYear()) ? 'anterior' : $class).'" id="aula_'.$val.'">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">'.(($row->desc_aula == null) ? '-' : $row->desc_aula).'</h2>
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="option_1">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>  
                            </div>
                            <div class="mdl-card__supporting-text">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 classroom-head">Detalles del Aula</div>
                                    <div class="col-xs-7  classroom-item">Nivel</div>
                                    <div class="col-xs-5  classroom-value">'.$row->desc_nivel.'</div>
                                    <div class="col-xs-7  classroom-item">Grado</div>
                                    <div class="col-xs-5  classroom-value">'.$row->desc_grado.'</div>
                                    <div class="col-xs-7  classroom-item">Secci&oacute;n</div>
                                    <div class="col-xs-5  classroom-value">'.(($row->nombre_letra == '') ? '-' : $row->nombre_letra).'</div>
                                    <div class="col-xs-7  classroom-item">Matriculados</div>
                                    <div class="col-xs-5  classroom-value">'.$row->matriculados.'</div>
                                    <div class="col-xs-7  classroom-item">Morosos</div>
                                    <div class="col-xs-5  classroom-value">'.$row->cant_vencidos.'</div>
                                    <div class="col-xs-7  classroom-item">Becados</div>
                                    <div class="col-xs-5  classroom-value">'.$row->cant_becas.'</div>
                                    <div class="col-xs-7  classroom-item">Año</div>
                                    <div class="col-xs-5  classroom-value">'.$row->year.'</div>
                                            
                                </div>
                            </div>
                            <div class="mdl-card__actions">    
                                <buttom data-toggle="modal" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised btn_modal_card" onclick="modalDetalleAulaCompromiso(\''.$idAulaCrypt .'\')">
                                    VER ESTUDIANTES
                                </button>
                            </div>
                        </div>';
        }
        return $card;
    }
}

if(!function_exists('__buildComboCuotasByCronograma')) {
    function __buildComboCuotasByCronograma($idCronograma) {
        $CI =& get_instance();
        $CI->load->model('m_reportes');
        $cuotas = $CI->m_reportes->getCuotasByCronograma($idCronograma);
        $opcion = '';
        foreach ($cuotas as $row){
            $idCuota = _encodeCI($row->id_detalle_cronograma);
            $opcion .= '<option value="'.$idCuota.'">'._ucwords($row->desc_detalle_crono).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildTableHTMLEstudiantesTaller')) {
    function __buildTableHTMLEstudiantesTaller($estudiantes) {
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-show-columns="false"
        			                    data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
        			                    id="tb_estu_verano">',
                      'table_close' => '</table>'
        );
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => 'Estudiante' , 'class' => 'text-left');
        $head_1 = array('data' => 'Sede'       , 'class' => 'text-right');
        $head_2 = array('data' => 'Nivel'      , 'class' => 'text-right');
        $head_3 = array('data' => 'Grado'      , 'class' => 'text-right');
        $montos=array();
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3);
        foreach ($estudiantes as $row) {
            $estu = '<img alt="Student" class="img-circle m-r-5" WIDTH=25 HIEGHT=25 src="'.$row->foto_persona.'">'.' '.$row->nombre_completo;
            $row_cell_0 = array('data' => $estu);
            $row_cell_1 = array('data' => $row->desc_sede  , 'class' => 'text-right');
            $row_cell_2 = array('data' => $row->desc_nivel , 'class' => 'text-right');
            $row_cell_3 = array('data' => $row->desc_grado , 'class' => 'text-right');
            $CI->table->add_row($row_cell_0,$row_cell_1,$row_cell_2,$row_cell_3);
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('__buildTableHTMLEstudiantesTallerPDF')) {
    function __buildTableHTMLEstudiantesTallerPDF($estudiantes) {
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
                          'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => 'Estudiante' , 'class' => 'text-left');
        $head_1 = array('data' => 'Sede'       , 'class' => 'text-right');
        $head_2 = array('data' => 'Nivel'      , 'class' => 'text-right');
        $head_3 = array('data' => 'Grado'      , 'class' => 'text-right');
        $montos=array();
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3);
        foreach ($estudiantes as $row) {
            $row_cell_0 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->nombre_completo.'</FONT>');
            $row_cell_1 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->desc_sede .'</FONT>');
            $row_cell_2 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->desc_nivel.'</FONT>');
            $row_cell_3 = array('data' => '<FONT FACE="Arial" SIZE=3>'.$row->desc_grado.'</FONT>');
            $CI->table->add_row($row_cell_0,$row_cell_1,$row_cell_2,$row_cell_3);
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

