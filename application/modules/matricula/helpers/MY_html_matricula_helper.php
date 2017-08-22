<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_createCardAlumnos')) {
    function _createCardAlumnos($alumnos, $count = null, $btn = null, $fechas = null, $fechaRat = null) {
        $CI =& get_instance();
        $vista = null;
        $i = 0;
        if($count != null) {
            $i = $count;
        }
        if($fechaRat != null) {
            $fechaIniRat = explode('-', $fechaRat['fec_inicio']);
            $fechaAct = explode('-', date("Y-m-d"));
            $okRat = 0;
            if($fechaAct[1] == $fechaIniRat[1]) {
                if($fechaAct[2] < $fechaIniRat[2]){
                    $okRat = 1;
                }
            } else if ($fechaAct[1] < $fechaIniRat[1]) {
                $okRat = 1;
            }
            if($okRat == 0){
                $yearRat = _getYear() + 1;
            } else {
                $yearRat = _getYear();
            }
        }

        foreach ($alumnos as $alumno) {
            $idAluEnc  = _simple_encrypt($alumno->nid_persona);
            $idAulaEnc = _simple_encrypt($alumno->nid_aula);
            $btnEliminaEstado  = "disabled";
            $btnEliminarOnclick = null;
            $btnTrasladoEstado  = "disabled";
            $btnTrasladoOnclick = null;
            $btnDesabilitarOnclick= 'abrirModalConfirmDesactivarAlumno(\''.$idAluEnc.'\', \''.$alumno->apellidos.', '.$alumno->nombres.'\', this, \''.$alumno->flg_acti.'\')';
            $btnDesabilitarEstado = null;
            $btnDecJuradaOnclick= null;
            $btnDecJuradaEstado = "disabled";
            $letra = null;
            if($fechaRat != null && $btn == null){
                $confirmoDatos = $CI->m_matricula->countConfirmacionDatos($yearRat,$alumno->nid_persona, 'R',1);
                if(($confirmoDatos['existe'] == 0 || $confirmoDatos['recibido'] == 0) && $alumno->estado != 'RETIRADO' && $alumno->estado != 'PREREGISTRO'){
                    $btnDecJuradaOnclick= 'abrirModalConfirmDeclaracionJurada(\''.$idAluEnc.'\', \''.$alumno->apellidos.', '.$alumno->nombres.'\', this)';
                    $btnDecJuradaEstado = "";
                }
            }
            if($alumno->estado == ALUMNO_EGRESADO){
                $letra = 'egresado';
            }else if($alumno->estado == ALUMNO_MATRICULADO){
                $letra = 'matriculado';
                if($fechas != null){
                	$fechaIni = explode('-', $fechas['fec_inicio']);
                	$fechaFin = explode('-', $fechas['fec_fin']);
                	$fechaAct = explode('-', date("Y-m-d"));
                	$ok1 = 0;
                	$ok2 = 0;
                	if($fechaIni[1] == $fechaAct[1]){
                		if($fechaIni[2] <= $fechaAct[2]){
                			$ok1 = 1;
                		}
                	} else if ($fechaIni[1] < $fechaAct[1]) {
                		$ok1 = 1;
                	}
                	if($fechaAct[1] < $fechaFin[1]){
                		$ok2 = 1;
                	} else if($fechaAct[1] == $fechaFin[1]){
                		if($fechaAct[2] <= $fechaFin[2]){
                			$ok2 = 1;
                		}
                	}
                	if($ok1 != 0 && $ok2 != 0){
                         $btnTrasladoEstado  = null;
                         $btnTrasladoOnclick = 'abrirModalTrasladar(\''.$idAluEnc.'\')';
                	}
                }
            }else if($alumno->estado == ALUMNO_NOPPROMOVIDO){
                $letra = 'no-promovido';
            }else if($alumno->estado == ALUMNO_PREREGISTRO){
                $letra = 'pre-registro';
            }else if($alumno->estado == ALUMNO_PROMOVIDO){
                $letra = 'promovido';
            } else if($alumno->estado == ALUMNO_REGISTRADO){
                $letra = 'registrado';
            } else if($alumno->estado == ALUMNO_RETIRADO){
                $letra = 'retirado';
                $btnDesabilitarOnclick  = NULL;
                $btnDesabilitarEstado = 'disabled';
            } else if($alumno->estado == ALUMNO_VERANO){
                $letra = 'verano';
            } else if ($alumno->estado == ALUMNO_MATRICULABLE) {
                $letra = 'matriculable';
            } else if ($alumno->estado == ALUMNO_DATOS_INCOMPLETOS) {
                $letra = 'datos-incompletos';
                $btnEliminaEstado  = null;
                $btnEliminarOnclick = 'abrirModalConfirmDeleteAlumno(\''.$idAluEnc.'\', \''.$alumno->apellidos.', '.$alumno->nombres.'\', this)';
            }
             
            $pf = $alumno->nombrecompletoresponsable;
            if($pf == null){
                $pf = '-';
            }
            $telfF = $alumno->telefonoresponsable;
            if($telfF == null){
                $telfF = '-';
            }
            $sede = null;
            if(_getSesion(MATRICULA_ROL_SESS) == ID_ROL_ADMINISTRADOR){
                $sede = '<div class="col-xs-3 student-item">Sede</div>
                         <div class="col-xs-9 student-value">'.$alumno->desc_sede.'</div>';
            }
            $descAula = '<div class="col-xs-8 student-value">'.$alumno->desc_aula.'</div>';
            if($alumno->desc_aula != '-' && $btn == null){
                $descAula = '<div class="col-xs-8 student-value student-link" style="cursor:pointer" onclick="goToViewAula(\''.$idAulaEnc.'\')">'.$alumno->desc_aula.'</div>';
            }
            $textEstado = ($alumno->flg_acti == FLG_ACTIVO) ? 'Desactivar' : 'Activar';
            $extranj = ($alumno->pais == 173 || $alumno->pais == null) ? 'style="display:none"' : 'style="display:block"';
            $button = '';
            if ($btn == null){
                $button =  '<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="goToViewAlumno(\''.$idAluEnc.'\')">Ver</button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="goToEditAlumno(\''.$idAluEnc.'\')">Editar</button>
                            </div>
                            <div class="mdl-card__menu">
                                <button id="foreign_people" class="mdl-button mdl-js-button mdl-button--icon" '.$extranj.'>
    	                            <i class="mdi mdi-language"></i>
    	                        </button>
                                <button id="student-'.$alumno->nid_persona.'" class="mdl-button mdl-js-button mdl-button--icon">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="student-'.$alumno->nid_persona.'">
                                    <!--QUITAR Y AGREGAR REMOVE_CIRCLE : FRANCO-->
                                    <li class="mdl-menu__item item-desabilitar"  onclick="'.$btnDesabilitarOnclick.'" '.$btnDesabilitarEstado.'><i class="mdi mdi-remove_circle mdi-check_circle"></i>'.$textEstado.'</li>
                                    <li class="mdl-menu__item"  onclick="'.$btnTrasladoOnclick.'" '.$btnTrasladoEstado.'><i class="mdi mdi-swap_horiz"></i>Trasladar</li>
                                    <li class="mdl-menu__item"  onclick="'.$btnEliminarOnclick.'" '.$btnEliminaEstado.'><i class="mdi mdi-delete"></i>Eliminar</li>
                                    <li class="mdl-menu__item"  onclick="'.$btnDecJuradaOnclick.'" '.$btnDecJuradaEstado.'><i class="mdi mdi-picture_as_pdf"></i>Declaración Jurada</li>
                                </ul>
                            </div>';
            }
            if ($btn == 1 && $fechaRat != null){
                $textoBtn    = "OK";
                $disabledBtn = "disabled";
                $classBtn    = "mdl-button__matricula--check";
                $onclickBtn  = "";
                //Remota
            	if($alumno->countaulas == 0 || $alumno->estado == ALUMNO_REGISTRADO){
                    $textoBtn    = "Iniciar Matr&iacute;cula";
                    $disabledBtn = "";
                    $classBtn    = "";
                    $onclickBtn  = 'onclick="abrirModalConfirmarGenerarRatificacion(\''.$idAluEnc.'\',\'Matr&iacute;cula\')"';
                    $estado = $CI->m_matricula->getEstadoCuota(FLG_MATRICULA,$alumno->nid_persona,$alumno->year_ingreso);
                    if($estado != NULL){
                        $textoBtn    = "<FONT SIZE=2>Matric. por pagar</FONT>";
                        $classBtn    = "";
                        $onclickBtn  = "";
//                         strcmp($estado,ESTADO_PAGADO)
                        if($estado == ESTADO_PAGADO){
                            $textoBtn    = "<FONT SIZE=2>MATR&iacute;CULA OK</FONT>";
                            $classBtn    = "mdl-button__matricula--check";
                            $onclickBtn  = "";
                        }
                    }
            	} else if($alumno->estado == ALUMNO_MATRICULADO || $alumno->estado == ALUMNO_PROMOVIDO || $alumno->estado == ALUMNO_PROM_PREREGISTRO || $alumno->estado == ALUMNO_PROM_REGISTRO) {
	                $textoBtn    = "<FONT SIZE=2>Iniciar ratificaci&oacute;n</FONT>";
	                $onclickBtn  = 'onclick="abrirModalConfirmarGenerarRatificacion(\''.$idAluEnc.'\',\'Ratificaci&oacute;n\')"';
	                $disabledBtn = "";
                    $classBtn    = "";
                    $flgConfirmacion = $CI->m_matricula->getFlagConfirmacion($alumno->nid_persona,$yearRat);
	                if($flgConfirmacion != NULL) {
	                    $textoBtn = "<FONT SIZE=2>Ratif. en proceso</FONT>";
	                    $onclickBtn =  "";
	                }
	                $estado = $CI->m_matricula->getEstadoCuota(FLG_RATIFICACION,$alumno->nid_persona,$yearRat);
	                if($estado != NULL) {
                        $textoBtn    = "<FONT SIZE=2>Ratif. por pagar</FONT>";
                        $classBtn    = "";
                        $onclickBtn  = "";
	                    if($estado == ESTADO_PAGADO) {
                            $textoBtn    = "<FONT SIZE=2>Ratificaci&oacute;n OK</FONT>";
                            $classBtn    = "mdl-button__matricula--check";
                            $onclickBtn  = "";
	                    }
	                }
            	}
            	$button  = '<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button__matricula '.$classBtn.'" '.$disabledBtn.' '.$onclickBtn.'>'.$textoBtn.'</button>
                            </div>';
            }
            $iconoMoney = null;
            if($btn == null){
                $monet_class = 'monet-inactive';
                $namePago    = ($alumno->por_pagar > 0) ? 'Debe' : 'Al d&iacute;a';
                if($alumno->pagado > 0 && $alumno->por_pagar == 0){
                    $monet_class = 'monet-active';
                    $namePago    = 'Al d&iacute;a';
                } else if($alumno->pagado == 0 && $alumno->por_pagar == 0){
                    $monet_class = '';
                    $namePago    = 'Sin compromisos';
                }
                $iconoMoney = '<div class="col-xs-3 text-right p-0" style="padding-top: 2.5px">
                                    <div id="pagoOk-'.$alumno->nid_persona.'" class="icon mdi mdi-monetization_on '.$monet_class.'" onclick = "abrirModalCompromisos(\''.$idAluEnc.'\',1)" style="cursor:pointer"></div>
                                    <div class="mdl-tooltip" for="pagoOk-'.$alumno->nid_persona.'">'.$namePago.'</div>
                                </div>';
            }
            
            $estadoDisa = ($alumno->flg_acti == FLG_ACTIVO) ? null : 'part-disable';
            $vista .= ' <div class="mdl-card mdl-student '.$estadoDisa.'">
                            <div class="mdl-card__title">
                                <img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$alumno->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$alumno->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="mdl-img" id="foto_estudiante'.$i.'">
                                <div class="state '.$letra.'" id="state'.$alumno->nid_persona.'" data-toggle="modal" data-target="#modalLeyendaAlumno"></div>
                                <div class="mdl-tooltip" for="state'.$alumno->nid_persona.'"  >'.strtoupper($letra).'</div>
                            </div>
                            <div class="mdl-card__supporting-text student-'.$letra.'">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 student-name">'.$alumno->apellidos.',</div>
                                    <div class="col-xs-12 student-name">'.$alumno->nombres.'</div>
                                    <div class="col-xs-12 p-0 m-0 m-b-10">
                                        <div class="col-xs-9 student-head"><strong>Detalles del Estudiante</strong></div>
                                        '.$iconoMoney.'
                                    </div>
                                    <div class="col-xs-6 student-item">C&oacute;d. Alumno</div>
                                    <div class="col-xs-6 student-value">'.$alumno->cod_alumno.'</div>
                                    <div class="col-xs-4 student-item">Doc. Id</div>
                                    <div class="col-xs-8 student-value">'.$alumno->nro_documento.'</div>
                                    <div class="col-xs-5 student-item">Apoderado</div>
                                    <div class="col-xs-7 student-value">'.$pf.'</div>
                                    <div class="col-xs-7 student-item">Tlf. Apoderado</div>
                                    <div class="col-xs-5 student-value">'.$telfF.'</div>
                                    '.$sede.'
                                    <div class="col-xs-3 student-item">Nivel</div>
                                    <div class="col-xs-9 student-value">'.$alumno->desc_nivel.'</div>
                                    <div class="col-xs-3 student-item">Grado</div>
                                    <div class="col-xs-9 student-value">'.$alumno->desc_grado.'</div>
                                    <div class="col-xs-4 student-item">Secci&oacute;n</div>
                                    '.$descAula.'
                                </div>
                            </div>
                            '.$button.'
                        </div>';
            $i++;
        }
        return $vista;
    }
}

if(!function_exists('_createTableAulas')) {
    function _createTableAulas($aulas, $count = null){
        $vista = null;
        $i = 0;
        if($count != null){
            $i = $count;
        }
        foreach ($aulas as $aula) {
            $idAulaEnc  = _simple_encrypt($aula->nid_aula);
             
            if($aula->year < _getYear()){
                $yearClass ='classroom-last-year';
                $yearAnterior ='disabled';
            } else {
                $yearClass ='classroom-now-year';
                $yearAnterior = null;
            }
            if($aula->year == null){
                $yearClass ='classroom-now-year';
                $yearAnterior = null;
            }
            $idRol = _getSesion('id_rol');
            if( $idRol != ID_ROL_ADMINISTRADOR ){
                $datosAula='';
            } else {
                $datosAula='<div class="col-xs-4 classroom-item">Sede</div><div class="col-xs-8 classroom-value">'.$aula->desc_sede.'</div>';
            }
             
            $alumnos_matriculados = ($aula->capa_actual > 0) ? 'disabled' : null;
            $alumnos_matriculados_onc = ($aula->capa_actual > 0) ? null : 'onclick="abrirModalConfirmarEliminarAula(\''.$idAulaEnc.'\', \'aula_'.$i.'\')"';
            $vista .= '
                                <div class="mdl-card mdl-classroom" id="aula_'.$i.'">
                                    <div class="mdl-card__title">
                                        <h2 id="class'.$i.'" class="mdl-card__title-text">'.(($aula->desc_aula == null) ? 'NOMBRE PREDETERMINADO' : $aula->desc_aula).'</h2>
                                        <div class="mdl-tooltip" for="class'.$i.'">'.(($aula->desc_aula == null) ? 'NOMBRE PREDETERMINADO' : $aula->desc_aula).'</div>
                                        <span class="transparencia1"></span>    
                                    </div>
                                    <div class="mdl-card__supporting-text '.$yearClass.'">
                                        <div class="row p-0 m-0">
                                            <div class="col-xs-12 classroom-head"><strong>Detalles del Aula</strong></div>
                                            '.$datosAula.'
                                            <div class="col-xs-5 classroom-item">Nivel</div>
                                            <div class="col-xs-7 classroom-value">'.$aula->desc_nivel.'</div>
                                            <div class="col-xs-5 classroom-item">Grado</div>
                                            <div class="col-xs-7 classroom-value">'.$aula->desc_grado.'</div>
                                            <div class="col-xs-7 classroom-item">Sec. UGEL</div>
                                            <div class="col-xs-5 classroom-value">'.(($aula->nombre_letra == null) ? '-' : $aula->nombre_letra).'</div>
                                            <div class="col-xs-7 classroom-item">A&ntilde;o</div>
                                            <div class="col-xs-5 classroom-value">'.(($aula->year == null) ? '-' : $aula->year).'</div>
                                            <div class="col-xs-7 classroom-item">Capacidad</div>
                                            <div class="col-xs-5 classroom-value classroom-link" onclick ="abrirModalAlumnos(\''.$idAulaEnc.'\')" style = "cursor:pointer;'.(($aula->capa_actual == $aula->capa_max && $aula->capa_actual != 0) ? 'color:red' : null).'" >'.$aula->capa_actual.'/'.$aula->capa_max.'</div>
                                            <div class="col-xs-7 classroom-item p-l-10">Varones</div>
                                            <div class="col-xs-5 classroom-value">'.$aula->hombres.'</div>
                                            <div class="col-xs-7 classroom-item p-l-10">Mujeres</div>
                                            <div class="col-xs-5 classroom-value">'.$aula->mujeres.'</div>
                                            <div class="col-xs-9 classroom-photo-desc">
                                                <div class="row-fluid m-0 p-0">
                                                    <div class="col-xs-12 m-0 p-0 text-left"><strong>Tutor</strong></div>
                                                    <div class="col-xs-12 m-0 p-0 classroom-item">'.$aula->nombretutor.'</div>
                                                </div>
                                            </div>
                                            <div class="col-xs-3 classroom-photo">
                                                <img alt="" src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$aula->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$aula->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="mdl-img" id="foto_colaborador'.$i.'">
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="mdl-card__actions">
                                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="goToViewAula(\''.$idAulaEnc.'\')">Ver</button>
                                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="goToEditAula(\''.$idAulaEnc.'\')" '.$yearAnterior.'>Editar</button>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button id="classroom-'.$i.'" class="mdl-button mdl-js-button mdl-button--icon">
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="classroom-'.$i.'">
                                            <li class="mdl-menu__item '.$yearClass.'" '.$yearAnterior.' '.$alumnos_matriculados.' '.$alumnos_matriculados_onc.' ><i class="mdi mdi-delete"></i>Eliminar</li>
                                        </ul>
                                    </div>
                                </div>
                           ';
            $i++;
            //
        }
    
        return $vista;
    }
}

if(!function_exists('_createTableAlumnos')) {
    function _createTableAlumnos($alumnos) {
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tbAlumnosAula">',//este id se lee en el js
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0_1 = array('data' => '#', 'class' => 'text-left');
        $head_0   = array('data' => 'Nombre'   , 'class' => 'text-left');
        $head_3   = array('data' => 'Nro. Doc.', 'class' => 'text-right');
        //$head_3   = array('data' => 'Accion');
        $CI->table->set_heading($head_0_1, $head_0, $head_3);
        $i = 1;
        foreach ($alumnos as $row){
            $idAlumnEnc = _simple_encrypt($row->nid_persona);
    
            $imageStudent = '<img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" WIDTH=30 HEIGHT=30
		        class="img-circle m-r-10" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">
		        <p class="classroom-value classroom-link" style="display: inline" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">'.$row->nombrecompleto.'</p>';
            $row_0_1 = $i;
            $row_0   = array('data' => $imageStudent);
            $row_3   = array('data' => $row->nro_documento);
            //$row_3   = array('data' => '<button onclick="eliminarAlumnosFromAula(\''.$idAlumnEnc.'\')">Eliminar</button>');
            	
            $CI->table->add_row($row_0_1, $row_0, $row_3);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createCabeceraAlfabetico')) {
    function _createCabeceraAlfabetico($onclick){
        $alphas = range('A', 'Z');
        $ret = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">';
        $ret .= '<a href="#tab-alumno" class="mdl-layout__tab is-active" onclick="'.$onclick.'(\'\', this)" id="tabTodos" style="cursor: pointer">Todos</a>';
        foreach($alphas as $al){
            $ret .= '<a href="#tab-alumno" class="mdl-layout__tab" onclick="'.$onclick.'(\''.$al.'\', this)" style="cursor: pointer">'.$al.'</a>';
        }
        $ret = $ret.'</div>';
        return $ret;
    }
}

if(!function_exists('_createCardDetalleAlumnos')) {
    function _createCardDetalleAlumnos($alumnos, $fechas = null) {
        $CI =& get_instance();
        $vista = null;
        $i = 0;
        if($fechas != null){
            $fechaIni = explode('-', $fechas['fec_inicio']);
            $fechaFin = explode('-', $fechas['fec_fin']);
            $fechaAct = explode('-', date("Y-m-d"));
            $ok1 = 0;
            $ok2 = 0;
            if($fechaIni[1] == $fechaAct[1]){
                if($fechaIni[2] <= $fechaAct[2]){
                    $ok1 = 1;
                }
            } else if ($fechaIni[1] < $fechaAct[1]) {
                $ok1 = 1;
            }
            if($fechaAct[1] < $fechaFin[1]){
                $ok2 = 1;
            } else if($fechaAct[1] == $fechaFin[1]){
                if($fechaAct[2] <= $fechaFin[2]){
                    $ok2 = 1;
                }
            }
        }
            
        foreach ($alumnos as $alumno) {
            $idAluEnc = _simple_encrypt($alumno->nid_persona);
             
            //$btnEliminarEstado  = "disabled";
            //$btnEliminarOnclick = null;
            $letra = null;
            if($alumno->estado == ALUMNO_EGRESADO){
                $letra = 'egresado';
            }else if($alumno->estado == ALUMNO_MATRICULADO){
                $letra = 'matriculado';
            }else if($alumno->estado == ALUMNO_NOPPROMOVIDO){
                $letra = 'no-promovido';
            }else if($alumno->estado == ALUMNO_PREREGISTRO){
                $letra = 'pre-registro';
                //$btnEliminarEstado  = null;
                //$btnEliminarOnclick = 'abrirModalConfirmDesactivarAlumno(\''.$idAluEnc.'\', \''.$alumno->apellidos.', '.$alumno->nombres.'\', this)';
                //$btnEliminarOnclick = 'abrirModalConfirmDeleteAlumno(\''.$idAluEnc.'\', \''.$alumno->apellidos.', '.$alumno->nombres.'\', this)';
            }else if($alumno->estado == ALUMNO_PROMOVIDO){
                $letra = 'promovido';
            }else if($alumno->estado == ALUMNO_REGISTRADO){
                $letra = 'registrado';
                //$btnEliminarEstado  = null;
                //$btnEliminarOnclick = 'abrirModalConfirmDesactivarAlumno(\''.$idAluEnc.'\', \''.$alumno->apellidos.', '.$alumno->nombres.'\', this)';
                //$btnEliminarOnclick = 'abrirModalConfirmDeleteAlumno(\''.$idAluEnc.'\', \''.$alumno->apellidos.', '.$alumno->nombres.'\', this)';
            }else if($alumno->estado == ALUMNO_RETIRADO){
                $letra = 'retirado';
            }else if($alumno->estado == ALUMNO_VERANO){
                $letra = 'verano';
            }else if($alumno->estado == ALUMNO_NOPROMOVIDO_NIVELACION){
                $letra = 'no-promovido-nivelacion';
            }
             
            $pf = $alumno->nombrecompletoresponsable;
            if($pf == null){
                $pf = '-';
            }
             
            $telfF = $alumno->telefonoresponsable;
            if($telfF == null){
                $telfF = '-';
            }
            $disabled1 = (_getSesion('accionDetalleAula') == 0 ) ? 'disabled' : null;
            $onClickDesmatricular = null;
            // CAMBIAR CUANDO CREEN SUBDIRECTOR
            //if(_getSesion(MATRICULA_ROL_SESS) == ID_ROL_ADMINISTRADOR && $fechas != null){
            if($fechas != null){
	            if($ok1 == 0 || $ok2 == 0){
                	$onClickDesmatricular = 'abrirModalConfirmarDesmatricular(\''.$idAluEnc.'\')';
	            } else {
            		$disabled1 = 'disabled';
            	}
            } else {
            	$disabled1 = 'disabled';
            }
            //BORRAR
            //$onClickDesmatricular = 'abrirModalConfirmarDesmatricular(\''.$idAluEnc.'\')';
            //$disabled1='';
            $monet_class = 'monet-inactive';
            if($alumno->cod_alumno_temp != null){
//                 $countDeudas = $CI->m_matricula->getCountDeudasByEstudiantes($alumno->cod_alumno_temp);
                $namePago    = ($alumno->por_pagar > 0 /* || $countDeudas != 0 */) ? 'Debe' : 'Al d&iacute;a';
                if($alumno->pagado > 0 && $alumno->por_pagar == 0 /*&& $countDeudas == 0*/){
                    $monet_class = 'monet-active';
                    $namePago    = 'Al d&iacute;a';
                } else if($alumno->pagado == 0 && $alumno->por_pagar == 0 /*&& $countDeudas == 0*/){
                    $monet_class = '';
                    $namePago    = 'Sin compromisos';
                }
            } else {
                $namePago    = ($alumno->por_pagar > 0) ? 'Debe' : 'Al d&iacute;a';
                if($alumno->pagado > 0 && $alumno->por_pagar == 0){
                    $monet_class = 'monet-active';
                    $namePago    = 'Al d&iacute;a';
                } else if($alumno->pagado == 0 && $alumno->por_pagar == 0){
                    $monet_class = '';
                    $namePago    = 'Sin compromisos';
                }
            }
            
            $extranj = ($alumno->pais == 173 || $alumno->pais == null) ? 'style="display:none"' : 'style="display:block"';
            $vista .= '
                            <div class="mdl-card mdl-student">
                                <div class="mdl-card__title">
                                    <img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$alumno->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$alumno->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'">
                                    <div class="state '.$letra.'" id="state'.$i.'" data-toggle="modal" data-target="#modalLeyendaAlumno"></div>
                                    <div class="mdl-tooltip" for="state'.$i.'">'.strtoupper($letra).'</div>
                                </div>
                                <div class="mdl-card__supporting-text student-'.$letra.'">
                                    <div class="row p-0 m-0">
                                        <div class="col-xs-12 student-name">'.$alumno->apellidos.'</div>
                                        <div class="col-xs-12 student-name">'.$alumno->nombres.'</div>
                                        <div class="col-xs-9 student-head"><strong>Detalles del Estudiante</strong></div>
                                        <div class="col-xs-3 text-right p-0" style="padding-top: 2.5px">
                                            <div id="pagoOk-'.$i.'" class="icon mdi mdi-monetization_on '.$monet_class.'" onclick = "abrirModalCompromisos(\''.$idAluEnc.'\',1)" style="cursor:pointer"></div>
                                            <div class="mdl-tooltip" for="pagoOk-'.$i.'">'.$namePago.'</div>
                                        </div>
                                        <div class="col-xs-6 student-item">C&oacute;d. Alumno</div>
                                        <div class="col-xs-6 student-value">'.$alumno->cod_alumno.'</div>
                                        <div class="col-xs-6 student-item">Nro. Doc.</div>
                                        <div class="col-xs-6 student-value">'.((strlen($alumno->nro_documento) == 0) ? '-' : $alumno->nro_documento).'</div>
                                        <div class="col-xs-6 student-item">Apoderado</div>
                                        <div class="col-xs-6 student-value">'.$pf.'</div>
                                        <div class="col-xs-7 student-item">Telef. Familiar</div>
                                        <div class="col-xs-5 student-value">'.$telfF.'</div>
                                        <div class="col-xs-6 student-item">Sede</div>
                                        <div class="col-xs-6 student-value">'.$alumno->desc_sede.'</div>
                                        <div class="col-xs-6 student-item">Grado</div>
                                        <div class="col-xs-6 student-value">'.$alumno->desc_grado.'</div>
                                    </div>
                                </div>
                                <div class="mdl-card__menu">
                                    <button id="foreign_people" class="mdl-button mdl-js-button mdl-button--icon" '.$extranj.'>
			                            <i class="mdi mdi-language"></i>
			                        </button>
                                    <button id="student-'.$i.'" class="mdl-button mdl-js-button mdl-button--icon">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="student-'.$i.'">
                                        <li class="mdl-menu__item" '.$disabled1.' onclick="'.$onClickDesmatricular.'"><i class="mdi mdi-directions_run"></i>Desmatricular</li>
                                    </ul>
                                </div>
                                <div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" disabled>Ver</button>
                                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" disabled>Editar</button>
                                </div>
                            </div>
                        ';
            $i++;
        }
        return $vista;
    }
}

if(!function_exists('_createTablaProfesoresAulaCurso')) {
    function _createTablaProfesoresAulaCurso($data){
        $vista = null;
        $i = 0;
        foreach ($data as $cur) {
            $nombres  = explode(";", $cur->nombres);
            $activos   = explode(",", $cur->activos);
            $titulares = explode(",", $cur->titulares);
            $fotos     = explode(",", $cur->fotos);
            
            $fotos_cont = null;
            $i = 0;
            for ($i = 0; $i < count($nombres); $i++) {
                $fotos_cont .= '<img data-toggle="tooltip" data-original-title="'.$nombres[$i].'" data-placement="bottom" alt="" src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$fotos[$i])) ? RUTA_IMG_PROFILE.'colaboradores/'.$fotos[$i] : RUTA_IMG_PROFILE."nouser.svg").'">';
            }
            
            $vista .= '<div class="mdl-card mdl-tour">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">'.$cur->desc_curso.'</h2>
                            </div>
                            <div class="mdl-card__supporting-text br-b">
                                <div class="list-images">
                                    '.$fotos_cont.'
                                </div>
                                <small>'.$i.' persona(s)</small>
                            </div>
                        </div>';
            $i++;
        }
        return $vista;
    }
}

if(!function_exists('_createTableBusquedaAlumnos')) {
    function _createTableBusquedaAlumnos($alumnos) {
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless"
		                                 data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
		                                 id="tbAlumnobyNombre">',//este id se lee en el js
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => 'Estudiante');
        $head_1 = array('data' => 'Nro. Doc.');
        $head_2 = array('data' => 'Agregar', 'class'=> 'text-center');
         
        $val = 0;
        $CI->table->set_heading($head_0,$head_1, $head_2);
        foreach ($alumnos as $row){//itera segun un valor(en este caso el parametro)
            $val++;
            $imageStudent = '<img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" WIDTH=30 HEIGHT=30
		        class="img-circle m-r-10">
		        <p class="classroom-value" style="display: inline">'.($row->nombrecompleto).'</p>';
            
            $idAlumnEnc = _simple_encrypt($row->nid_persona);
            $row_0 = array('data' => $imageStudent);
            $row_1 = array('data' => $row->nro_documento);
            $row_2 = array('data' => ' <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-'.$val.'">
                                      <input type="checkbox" id="checkbox-'.$val.'" class="mdl-checkbox__input cb-estudiante" attr-id-estudiante="'.$idAlumnEnc.'">
                                      <span class="mdl-checkbox__label"></span>
                                    </label>', 'class'=> 'text-center');//onclick envia el id de la fila a la funcion traeAlumnos del js
            $CI->table->add_row($row_0, $row_1, $row_2);
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableDocumentos')) {
    function _createTableDocumentos($documentos){
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
		                                   data-search="false" id="tb_documentos">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_1 = array('data' => '#', 'class' => 'text-center');
        $head_2 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left', 'data-sortable' => 'true');
        $head_5 = array('data' => '&#191;Entreg&oacute;?', 'class' => 'text-center');
        $head_3 = array('data' => 'Fecha Entreg&oacute;' , 'class' => 'text-left');
        $head_4 = array('data' => 'Fecha Registro', 'class' => 'text-center');
        $val = 0;
        $CI->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5);
        foreach($documentos as $row) {
            $idDocumentoEnc = _simple_encrypt($row->valor);
            $val++;
            $row_cell_1  = array('data' => $val);
            $row_cell_2  = array('data' => $row->desc_combo);
    
            $estado = 0;
            if($row->flg_recibio == 'checked'){
                $estado = 1;
            }
             
            $disabled = null;
            if(_getSesion('accionDetalleAlumno') == 0){
                $disabled = 'disabled';
            }
             
            $check = '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch-1">
                      <input type="checkbox" id="switch-1" class="mdl-switch__input" '.$row->flg_recibio.' onchange="checkedEntrego(\''.$idDocumentoEnc.'\', '.$estado.')" '.$disabled.'>
                      <span class="mdl-switch__label"></span>
                  </label>';
             
            $fechaEntrego = '<p></p>';
            if($row->fec_recibio != null){
                $fechaEntrego = '  <button class="mdl-button mdl-js-button mdl-button--icon"  onclick="abrirModalCambiarFechaDocumento(\''.$idDocumentoEnc.'\', \''._fecha_tabla($row->fec_recibio, "d/m/Y").'\')" '.$disabled.'>
                                   <i class="mdi mdi-edit"></i>
                               </button>
                               <label onclick="abrirModalCambiarFechaDocumento(\''.$idDocumentoEnc.'\', \''._fecha_tabla($row->fec_recibio, "d/m/Y").'\')">'._fecha_tabla($row->fec_recibio, "d/m/Y").'</label>';
            }
             
            $row_cell_3  = array('data' => $fechaEntrego);
            $row_cell_4  = array('data' => _fecha_tabla($row->fec_registro, "d/m/Y"), 'class' => 'text-center');
            $row_cell_5  = array('data' => $check, 'class' => 'text-center');
             
            $CI->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}
    
if(!function_exists('_createVistaPadresDeFamilia')) {
    function _createVistaPadresDeFamilia($familia){
        $vista = null;
        $i = 0;
        foreach ($familia as $familiar) {
            $idFamiliarEnc = _simple_encrypt($familiar->id_familiar);
            $generarUsuario = "";
            if($familiar->flg_apoderado == 1){
                /*if($familiar-> flg_usuario == 1){
                    $existeUsuario = 'Volver a generar ususario';
                } else {
                    $existeUsuario = 'Generar Usuario';
                }*/
                $existeUsuario = 'Enviar Usuario';
                $generarUsuario = '<li class="mdl-menu__item" onclick="abrirModalConfirmGenerarUsuario(\''.$idFamiliarEnc.'\', \''.$familiar->apellidos.' '.$familiar->nombres.'\')" mfb-component__button--child><i class="mfb-component__child-icon mdi mdi-account_circle"></i>'.$existeUsuario.'</li>';
            }
            $onclick = 'onclick="abrirSelectFotoFamiliar(\''.$idFamiliarEnc.'\', '.$i.')"';
            $btnEditar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="verDetalleFamiliar(\''.$idFamiliarEnc.'\', \''.$familiar->apellidos.' '.$familiar->nombres.'\')">Ver</button><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="editDetalleFamiliar(\''.$idFamiliarEnc.'\', \''.$familiar->apellidos.' '.$familiar->nombres.'\')">Editar</button>';
            $menu = '<div class="mdl-card__menu">
                                <button id="parent-'.$i.'" class="mdl-button mdl-js-button mdl-button--icon">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="parent-'.$i.'">
                                    <li class="mdl-menu__item" onclick="abrirConfirmDesagsinarFamiliar(\''.$idFamiliarEnc.'\', \''.$familiar->apellidos.' '.$familiar->nombres.'\')"><i class="mdi mdi-keyboard_backspace" ></i>Desasignar</li>
                                    '.$generarUsuario.'
                                </ul>
                            </div>';
            if(_getSesion('accionDetalleAlumno') == 0){
                $onclick   = null;
                $btnEditar = null;
                $menu      = null;
            }
             
            $nroDoc = null;
            if($familiar->tipo_doc_identidad == 1){
                $nroDoc = '<div class="col-xs-5  parent-item">Carnet de Ext.</div>
                      <div class="col-xs-7 parent-value">'.$familiar->nro_doc_identidad.'</div>';
            } else {
                $nroDoc = '<div class="col-xs-5  parent-item">Doc. Id</div>
                      <div class="col-xs-7 parent-value">'.$familiar->nro_doc_identidad.'</div>';
            }
            //'.($familiar->resp_economico).'
            $resp = $familiar->flg_resp_economico == '1' ? '<i class="mdi mdi-attach_money"></i>'       : '-';
            $apod = $familiar->flg_apoderado == '1'      ? '<i class="mdi mdi-supervisor_account"></i>' : '-';
            
            $vista .= '
                        <div class="mdl-card mdl-parent">
      
                            <div class="mdl-card__title">
                                <div class="mdl-photo" '.$onclick.'>
                                    <img alt="Parent"  src="'.((file_exists(FOTO_PROFILE_PATH.'familiares/'.$familiar->foto_persona)) ? RUTA_IMG_PROFILE.'familiares/'.$familiar->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" id="fotoFamiliar'.$i.'" class="mdl-img">
                                    <span class="caption fade-caption">
                                        <i class="mdi mdi-photo_camera"></i>
                                    </span>
                                </div>
                            </div>
    
                            <div class="mdl-card__supporting-text">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 parent-name">'.$familiar->apellidos.'</div>
                                    <div class="col-xs-12 parent-name">'.$familiar->nombres.'</div>
                                    <div class="col-xs-12 parent-head"><strong>Detalles del familiar</strong></div>
                                    <div class="col-xs-4  parent-item">Parentesco</div>
                                    <div class="col-xs-8  parent-value">'.$familiar->parentesco.'</div>
                                    '.$nroDoc.'
                                    <div class="col-xs-3  parent-item">Correo</div>
                                    <div class="col-xs-9  parent-value">'.$familiar->email.'</div>
                                    <div class="col-xs-8  parent-item">&#191;Apoderado?</div>
                                    <div class="col-xs-4  parent-value">'.$apod.'</div>
                                    <div class="col-xs-9  parent-item">&#191;Resp. Econ&oacute;mico?</div>
                                    <div class="col-xs-3  parent-value">'.$resp.'</div>
                                    <div class="col-xs-4  parent-item">M&oacute;vil</div>
                                    <div class="col-xs-8  parent-value">'.$familiar->telf_celular.'</div>
                                    <div class="col-xs-4  parent-item">Usuario</div>
                                    <div class="col-xs-8  parent-value">'.$familiar->usuario_edusys.'</div>
                                </div>
                            </div>
                            '.$menu.'
                            <div class="mdl-card__actions">
                                '.$btnEditar.'
                            </div>
                        </div>
                    ';
             
            $i++;
        }
         
        return $vista;
    }
}

if(!function_exists('_createTableColegios')) {
    function _createTableColegios($data){
        $CI =& get_instance();
        $tmpl = array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
		                                   id="tablaColegios">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0= array('data'=> '#');
        $head_1= array('data'=> 'Descripci&oacute;n');
        $CI->table->set_heading($head_0, $head_1);
        $cont = 1;
        foreach ($data as $row){
            $row_0= array('data'=> $cont);
            $row_1= array('data'=> $row->desc_colegio);
            $CI->table->add_row($row_0, $row_1);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableFamiliasBusqueda')) {
    function _createTableFamiliasBusqueda($data){
        $CI =& get_instance();
        $tmpl = array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
		                                   id="tablaFamiliasBusqueda">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0= array('data'=> 'Apellidos', 'class' => 'text-left');
        $head_1= array('data'=> 'C&oacute;digo', 'class' => 'text-center');
        $head_2= array('data'=> 'Asignar', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_2);
        foreach ($data as $row){
            $codFamEnc = _simple_encrypt($row->cod_familia);
            $row_0= array('data'=> $row->apellidoscompleto);
            $row_1= array('data'=> $row->cod_familia);
            $btnAsignar       = '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalConfirmAsignarFamilia(\''.$codFamEnc.'\', \''.$row->apellidoscompleto.'\')"><i class="mdi mdi-add"></i></a>';
            $btnVerFamiliares = '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" onclick="verFamiliares(\''.$codFamEnc.'\')"><i class="mdi mdi-group"></i></a>';
            $row_2= array('data'=> $btnAsignar.$btnVerFamiliares);
    
            $CI->table->add_row($row_0, $row_1, $row_2);
    
        }
        $table = $CI->table->generate();
         
        return $table;
    }
}

if(!function_exists('_createTableFamiliarEncontrado')) {
    function _createTableFamiliarEncontrado($data){
        $CI =& get_instance();
        $tmpl = array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
		                                   id="tablaFamiliarEncontrado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_1 = array('data'=> 'Nombres');
        $head_2 = array('data'=> 'Asignar');
        $CI->table->set_heading($head_1, $head_2);
         
        $idFamiliarEnc = _simple_encrypt($data['id_familiar']);
        $row_1 = array('data'=> $data['nombrecompleto']);
        $row_2 = array('data'=> '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" onclick="agsinarFamiliaraFamilia(\''.$idFamiliarEnc.'\', 1)"><i class="mdi mdi-account_circle"></i></a>');
        $CI->table->add_row($row_1, $row_2);
    
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableTraslados')) {
    function _createTableTraslados($resultado) {
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table style="text-align:center;" data-toggle="table" class="table borderless"
    			                                 data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                 id="tb_traslado" data-search="true">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0  = array('data' => 'Estudiante', 'class' => 'text-left');
        $head_1  = array('data' => 'Nro. Doc.', 'data-visible' => 'false', 'class' => 'text-center');
        $head_2  = array('data' => 'Tipo', 'class' => 'text-center');
        $head_3  = array('data' => 'Origen', 'class' => 'text-left');
        $head_4  = array('data' => 'Destino', 'class' => 'text-left');
        $head_5  = array('data' => 'Estado', 'class' => 'text-center');
        $head_6  = array('data' => 'Solicitante', 'data-visible' => 'false', 'class' => 'text-left');
        $head_7  = array('data' => 'Fecha', 'class' => 'text-center');
        $head_8  = array('data' => 'Confirmante', 'data-visible' => 'false', 'class' => 'text-left');
        $head_9 = array('data' => 'Fecha', 'data-visible' => 'false', 'class' => 'text-center');
        $head_10 = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_7, $head_8, $head_9, $head_10, $head_6);
        $i = 1;
        foreach ($resultado as $row) {
            $idTraslado = _simple_encrypt($row->id_traslado);
            $imageStudent = '<img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" WIDTH=25 HEIGHT=25 class="img-circle m-r-5" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->nombre_completo.'"><p class="classroom-value classroom-link" style="display: inline">'.$row->nombre_completo.'</p>';
            $row_0 = array('data' => $imageStudent);
            $row_1 = array('data' => $row->nro_documento);
            $row_2 = array('data' => $row->tipo_traslado);
            $row_3 = array('data' => $row->sede_grado_nivel_aula_origen);
            $row_4 = array('data' => $row->sede_grado_nivel_aula_destino);

            $estado = "default";
            $tipo = null;
            if($row->estado == SOLICITUD_SOLICITADA){
                $estado = "warning";
                $tipo = 0;
            }else if($row->estado == SOLICITUD_ACEPTADA){
                $estado = "success";
                $tipo = 1;
            }else if($row->estado == SOLICITUD_RECHAZADA){
                $estado = "danger";
                $tipo = 1;
            }

            $row_5  = array('data' => '<span class="label label-'.$estado.'">'.$row->estado.'</span>');
            $row_6  = array('data' => $row->nombres_usuario_traslado);
            $row_7  = array('data' => _fecha_tabla($row->fecha_hora_traslado, 'd/m/Y'));
            $row_8  = array('data' => $row->nombres_usuario_confirma);
            $row_9 = array('data' => _fecha_tabla($row->fecha_hora_confirmacion, 'd/m/Y'));

            $btnMotivos = '<a class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalMotivosTraslado(\''.$row->motivo_traslado.'\', \''.$row->motivo_rechazo.'\', '.$tipo.')"><i class="mdi mdi-comment" style="vertical-align:middle"></i></a>';
             
            $btnTrasladar = null;
            if((_getSesion(MATRICULA_ROL_SESS) == ID_ROL_ADMINISTRADOR || _getSesion(MATRICULA_ROL_SESS) == ID_ROL_SUBDIRECTOR || _getSesion(MATRICULA_ROL_SESS) == ID_ROL_SECRETARIA) && $row->estado == SOLICITUD_SOLICITADA){
                $btnTrasladar = '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalConfirmTraslado(\''.$idTraslado.'\', \''.$row->nombre_completo.'\')"><i class="mdi mdi-swap_horiz"></i></a>';
            }
             
            $row_10 = array('data' => $btnMotivos.$btnTrasladar);

            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_7, $row_8, $row_9, $row_10, $row_6);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableFamiliaresBusqueda')) {
    function _createTableFamiliaresBusqueda($data){
        $CI =& get_instance();
        $tmpl = array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tablaFamiliaresBusqueda">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_1 = array('data'=> 'Nombres', 'class' => 'text-left');
        $head_3 = array('data'=> 'Asignar', 'class' => 'text-center');
        $CI->table->set_heading($head_1, $head_3);
        $var = 1;
        foreach ($data as $row){
            $idFamiliarEnc = _simple_encrypt($row->id_familiar);
            $row_1 = array('data'=> $row->nombrecompleto, 'class' => 'text-left');
            $row_3 = array('data'=> '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" onclick="agsinarFamiliaraFamilia(\''.$idFamiliarEnc.'\', 0)"><i class="mdi mdi-check"></i></a>');
             
            $checkbox = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="cb'.$var.'">
                              <input type="checkbox" id="cb'.$var.'" class="mdl-checkbox__input cb-familiar" attr-id-familiar="'.$idFamiliarEnc.'">
                              <span class="mdl-checkbox__label"></span>
                          </label>';
             
            $row_2 = array('data'=> $checkbox, 'class' => 'text-center');
             
            $CI->table->add_row($row_1, $row_2);
            $var++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableFamiliaresByCodFam')) {
    function _createTableFamiliaresByCodFam($data){
        $CI =& get_instance();
        $tmpl = array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tablaFamiliaresByCodFam">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_1 = array('data'=> 'Nombres');
        $head_2 = array('data'=> 'Parentesco');
        $head_3 = array('data'=> 'Cod. Familia');
        $CI->table->set_heading($head_1, $head_2, $head_3);
        $i = 0;
        foreach ($data as $row){
        	$foto = '<img src="'.((file_exists(FOTO_PROFILE_PATH.'familiares/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'familiares/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" 
        			id="fotoFamiliar'.$i.'" class="img-circle m-r-10" alt="Parent" WIDTH=30 HEIGHT=30>';        	
            $row_1 = array('data'=> $foto.$row->nombrecompleto);
            $row_2 = array('data'=> $row->parentesco);
            $row_3 = array('data'=> $row->cod_familiar);
            $CI->table->add_row($row_1, $row_2, $row_3);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_getRadioButtonByTipo')) {
    function _getRadioButtonByTipo($nombre, $onchange, $id, $name, $attr, $valor){
        $cb = '<div class="col-xs-12 p-0 m-0 m-b-15">';
        $cb .= '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="'.$id.'">
                  <input type="radio" id="'.$id.'" class="mdl-radio__button" name="'.$name.'" value="'.$valor.'" onchange="'.$onchange.'" '.$attr.'>
                  <span class="mdl-radio__label">'.$nombre.'</span>
                </label>';
        $cb .= '</div>';
    
        return $cb;
    }
}

if(!function_exists('_createTableReporte1')){
    function _createTableReporte1($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_1 = array('data'=> 'Aula', 'class' => 'text-left');
        $head_2 = array('data'=> 'Tutor', 'class' => 'text-left');
        $head_3 = array('data'=> 'UGEL', 'class' => 'text-center');
        $head_3_1 = array('data'=> 'Capacidad', 'class' => 'text-right');
        $head_3_2 = array('data'=> 'Docentes', 'class' => 'text-center');
        $head_4 = array('data'=> 'Tipo Ciclo', 'class' => 'text-left');
    
        $CI->table->set_heading($head_1, $head_2, $head_3, $head_3_1, $head_3_2, $head_4);
        $cont = 1;
        foreach ($data as $row){
            if(strcmp ($row->nombrecompleto , '-' ) != 0){
                $tutor = '<img alt="collaborator" WIDTH=25 HEIGHT=25 src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="img-circle m-r-5" data-toggle="tooltip" data-original-title="'.$row->nombrecompleto.'" data-placement="bottom">'.$row->nombrecompleto;
            } else {
                $tutor = '-';
            }
            $idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_1 = array('data' => $row->desc_aula);
            $row_2 = array('data' => $tutor);
            $row_3 = array('data' => $row->nombre_letra);
            $row_3_1 = array('data' => '<p class="link-dotted" onclick="abrirModalAlumnos(\''.$idAulaEnc.'\')">'.$row->capa_actual.'/'.$row->capa_max.'</p>');
            $btnVerDocentes = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalDocentes(\''.$idAulaEnc.'\')"><i class="mdi mdi-group"></i></button>';
            $row_3_2 = array('data' => $btnVerDocentes);
            $row_4 = array('data' => $row->tipo_ciclo);
            $CI->table->add_row($row_1, $row_2, $row_3, $row_3_1, $row_3_2, $row_4);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte2')){
    function _createTableReporte2($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_2 = array('data'=> 'Nro. Doc.', 'class' => 'text-right');
        $head_3 = array('data'=> 'Cod. Alumno', 'class' => 'text-right');
        $head_3_1 = array('data'=> 'Cod. Familia', 'class' => 'text-right');
        $head_4 = array('data'=> 'Fec. Naci.', 'class' => 'text-center');
        $head_5 = array('data'=> 'Mes', 'class' => 'text-center');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_4, $head_5, $head_3, $head_3_1);
        $cont = 1;
        foreach ($data as $row){
            $codFamiliaEnc = _simple_encrypt($row->cod_familia);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => '<img alt="collaborator" WIDTH=25 HEIGHT=25 src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="img-circle m-r-5" data-toggle="tooltip" data-original-title="'.$row->nombrecompleto.'" data-placement="bottom">'.$row->nombrecompleto);
            $row_2 = array('data' => $row->nro_documento);
            $row_3 = array('data' => $row->cod_alumno);
            $row_3_1 = array('data' => '<p class="link-dotted" onclick="verFamiliares(\''.$codFamiliaEnc.'\')">'.$row->cod_familia.'</p>');
            $row_4 = array('data' => _fecha_tabla($row->fec_naci, 'd/m/Y'));
            $row_5 = array('data' => $row->mes);
            $CI->table->add_row($row_0, $row_1, $row_2, $row_4, $row_5, $row_3, $row_3_1);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte3_1')){
    function _createTableReporte3_1($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_2 = array('data'=> 'Nro. Doc.', 'class' => 'text-left');
        $head_3 = array('data'=> 'Cod. Alumno', 'class' => 'text-right');
        $head_4 = array('data'=> 'Cod. Familia', 'class' => 'text-right');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $cont = 1;
        foreach ($data as $row){
            $codFamiliaEnc = _simple_encrypt($row->cod_familia);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => '<img alt="collaborator" WIDTH=25 HEIGHT=25 src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="img-circle m-r-5" data-toggle="tooltip" data-original-title="'.$row->nombrecompleto.'" data-placement="bottom">'.$row->nombrecompleto);
            $row_2 = array('data' => $row->nro_documento);
            $row_3 = array('data' => $row->cod_alumno);
            $row_4 = array('data' => '<p class="link-dotted" onclick="verFamiliares(\''.$codFamiliaEnc.'\')">'.$row->cod_familia.'</p>');
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte3_2')){
    function _createTableReporte3_2($data) {
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre Estudiante', 'class' => 'text-left');
        $head_2 = array('data'=> 'Nombre Apoderado', 'class' => 'text-left');
        $head_3 = array('data'=> 'Nro. Doc.', 'class' => 'text-right');
        $head_4 = array('data'=> 'Firma', 'class' => 'text-right');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $cont = 1;
        foreach ($data as $row) {
            if($row->nombrecompletoresponsables != null) {
                $apoderadosArray = explode("/", $row->nombrecompletoresponsables);
                $dniArray = $row->nrodocresponsables != null ? explode("/", $row->nrodocresponsables) : "";
                $c = 1;
                foreach ($apoderadosArray as $apo) {
                    if($c == 1) {
                        $row_0 = array('data' => $cont);
                        $row_1 = array('data' => '<img alt="collaborator" WIDTH=25 HEIGHT=25 src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="img-circle m-r-5" data-toggle="tooltip" data-original-title="'.$row->nombrecompleto.'" data-placement="bottom">'.$row->nombrecompleto);
                        $row_2 = array('data' => $apo);
                        $row_3 = array('data' => $dniArray != "" ? $dniArray[$c-1] : "-");
                        $row_4 = array('data' => "-");
                        $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
                    } else {
                        $row_0 = array('data' => '');
                        $row_1 = array('data' => "-");
                        $row_2 = array('data' => $apo);
                        $row_3 = array('data' => $dniArray != "" ? $dniArray[$c-1] : "-");
                        $row_4 = array('data' => "-");
                        $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
                    }
                    $c++;
                }
            } else {
                $row_0 = array('data' => $cont);
                $row_1 = array('data' => '<img alt="collaborator" WIDTH=25 HEIGHT=25 src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="img-circle m-r-5" data-toggle="tooltip" data-original-title="'.$row->nombrecompleto.'" data-placement="bottom">'.$row->nombrecompleto);
                $row_2 = array('data' => "-");
                $row_3 = array('data' => "-");
                $row_4 = array('data' => "-");
                $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            }
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte3_3')){
    function _createTableReporte3_3($data) {
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_2 = array('data'=> 'Nro. Doc.', 'class' => 'text-right');
        $head_2_1 = array('data'=> 'Cod. Familia', 'class' => 'text-right');
        $head_3 = array('data'=> 'Cod. Alumno', 'class' => 'text-right');
        $head_4 = array('data'=> 'Sexo', 'class' => 'text-center');
        $head_5 = array('data'=> 'Colegio Proc', 'class' => 'text-left');
        $head_5_1 = array('data'=> 'Tel&eacute;fono', 'class' => 'text-right');
        $head_6 = array('data'=> 'Apoderado', 'class' => 'text-left');
        $head_7 = array('data'=> 'Fec. Naci.', 'class' => 'text-center');
        $head_8 = array('data'=> 'Estado', 'class' => 'text-center');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_2_1, $head_3, $head_4, $head_5, $head_5_1, $head_6, $head_7, $head_8);
        $cont = 1;
        foreach ($data as $row){
            $codFamiliaEnc = _simple_encrypt($row->cod_familia);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => '<img alt="collaborator" style="width:30px" src="'.((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" class="img-circle m-r-5">'.$row->nombrecompleto);
            $row_2 = array('data' => $row->nro_documento);
            $row_2_1 = array('data' => '<p class="link-dotted" onclick="verFamiliares(\''.$codFamiliaEnc.'\')">'.$row->cod_familia.'</p>');
            $row_3 = array('data' => $row->cod_alumno);
            $row_4 = array('data' => $row->sexo);
            $row_5 = array('data' => $row->colegio_procedencia);
            $row_5_1 = array('data' => $row->telf_pers);
            $row_6 = array('data' => $row->nombrecompletoresponsable != null ? $row->nombrecompletoresponsable : '-');
            $row_7 = array('data' => _fecha_tabla($row->fec_naci, 'd/m/Y'));            
            $row_8 = array('data' => '<span class="label label-'.$row->label.'">'.$row->estado.'</span>');
            $CI->table->add_row($row_0, $row_1, $row_2, $row_2_1, $row_3, $row_4, $row_5, $row_5_1, $row_6, $row_7, $row_8);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte4')){
    function _createTableReporte4($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_3 = array('data'=> 'Nro. Doc.', 'class' => 'text-right');
        $head_4 = array('data'=> 'Cod. Alumno', 'class' => 'text-right');
        $head_5 = array('data'=> 'Estado', 'class' => 'text-center');
    
        $CI->table->set_heading($head_0, $head_1, $head_3, $head_4, $head_5);
        $cont = 1;
        foreach ($data as $row){
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->nombrecompleto);
            $row_3 = array('data' => $row->nro_documento);
            $row_4 = array('data' => $row->cod_alumno);
            $row_5 = array('data' => '<span class="label label-'.$row->label.'">'.$row->estado.'</span>');
            $CI->table->add_row($row_0, $row_1, $row_3, $row_4, $row_5);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte5_1')){
    function _createTableReporte5_1($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_2 = array('data'=> 'Nivel', 'class' => 'text-left');
        $head_3 = array('data'=> 'Grado', 'class' => 'text-left');
        $head_4 = array('data'=> 'Capacidad', 'class' => 'text-right');
        $head_5 = array('data'=> 'Varones', 'class' => 'text-right');
        $head_6 = array('data'=> 'Mujeres', 'class' => 'text-right');
        $head_7 = array('data'=> 'Sec.UGEL', 'class' => 'text-center');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
        $cont = 1;
        foreach ($data as $row){
            $idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->desc_aula);
            $row_2 = array('data' => $row->desc_nivel);
            $row_3 = array('data' => $row->desc_grado);
            $row_4 = array('data' => '<p class="link-dotted" onclick="abrirModalAlumnos(\''.$idAulaEnc.'\')">'.$row->capa_actual.'/'.$row->capa_max.'</p>');
            $row_5 = array('data' => '<p class="link-dotted" onclick="abrirModalAlumnosSexo(\''.$idAulaEnc.'\', \''._simple_encrypt(1).'\')">'.$row->varones.'</p>');
            $row_6 = array('data' => '<p class="link-dotted" onclick="abrirModalAlumnosSexo(\''.$idAulaEnc.'\', \''._simple_encrypt(2).'\')">'.$row->mujeres.'</p>');
            $row_7 = array('data' => $row->nombre_letra);
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6, $row_7);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte5_2')){
    function _createTableReporte5_2($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_2 = array('data'=> 'Capacidad', 'class' => 'text-right');
        $head_3 = array('data'=> 'Varones', 'class' => 'text-right');
        $head_4 = array('data'=> 'Mujeres', 'class' => 'text-right');
        $head_5 = array('data'=> 'Sec.UGEL', 'class' => 'text-center');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        $cont = 1;
        foreach ($data as $row){
            $idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->desc_aula);
            $row_2 = array('data' => '<p class="link-dotted" onclick="abrirModalAlumnos(\''.$idAulaEnc.'\')">'.$row->capa_actual.'/'.$row->capa_max.'</p>');
            $row_3 = array('data' => '<p class="link-dotted" onclick="abrirModalAlumnosSexo(\''.$idAulaEnc.'\', \''._simple_encrypt(1).'\')">'.$row->varones.'</p>');
            $row_4 = array('data' => '<p class="link-dotted" onclick="abrirModalAlumnosSexo(\''.$idAulaEnc.'\', \''._simple_encrypt(2).'\')">'.$row->mujeres.'</p>');
            $row_5 = array('data' => $row->nombre_letra);
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte7')){
    function _createTableReporte7($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_1_1 = array('data'=> 'Nro. Doc.', 'class' => 'text-right');
        $head_2 = array('data'=> 'Curso', 'class' => 'text-left');
        $head_3 = array('data'=> 'Aula', 'class' => 'text-left');
    
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_2, $head_3);
        $cont = 1;
        foreach ($data as $row){
            //$idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->nombrecompleto);
            $row_1_1 = array('data' => $row->nro_documento);
            $row_2 = array('data' => $row->desc_curso);
            $row_3 = array('data' => $row->desc_aula);
            $CI->table->add_row($row_0, $row_1, $row_1_1, $row_2, $row_3);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte8')){
    function _createTableReporte8($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nivel', 'class' => 'text-left');
        $head_1_1 = array('data'=> '# Intrasedes', 'class' => 'text-left');
        $head_2 = array('data'=> '# Intersedes', 'class' => 'text-left');
    
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_2);
        $cont = 1;
        foreach ($data as $row){
            //$idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->desc_nivel);
            $row_1_1 = array('data' => $row->intrasede);
            $row_2 = array('data' => $row->intersedes);
            $CI->table->add_row($row_0, $row_1, $row_1_1, $row_2);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableAlumnosReportes')){
    function _createTableAlumnosReportes($alumnos){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tbAlumnosAula">',//este id se lee en el js
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0_1 = array('data' => '#', 'class' => 'text-left');
        $head_0   = array('data' => 'Nombre', 'class' => 'text-left', 'data-sortable' => 'true');
        $head_3   = array('data' => 'Nro. Doc.', 'class' => 'text-right', 'data-sortable' => 'true');
        //$head_3   = array('data' => 'Accion');
        $CI->table->set_heading($head_0_1, $head_0, $head_3);
        $i = 1;
        foreach ($alumnos as $row){
            $idAlumnEnc = _simple_encrypt($row->nid_persona);
    
            $imageStudent = '<img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" WIDTH=30 HEIGHT=30
		        class="img-circle m-r-10" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">
		        <p class="classroom-value classroom-link" style="display: inline" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">'.$row->nombrecompleto.'</p>';
    
            $row_0_1 = array('data' => $i, 'class' => 'text-left');
            $row_0   = array('data' => $imageStudent, 'class' => 'text-left');
            $row_3   = array('data' => $row->nro_documento, 'class' => 'text-right');
            //$row_3   = array('data' => '<button onclick="eliminarAlumnosFromAula(\''.$idAlumnEnc.'\')">Eliminar</button>');
    
            $CI->table->add_row($row_0_1, $row_0, $row_3);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte6_1')){
    function _createTableReporte6_1($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre Estudiante', 'class' => 'text-left');
        $head_1_1 = array('data'=> 'Nombre Familiar', 'class' => 'text-left');
        $head_1_2 = array('data'=> 'Parentesco', 'class' => 'text-left');
        $head_2 = array('data'=> 'Telef. Fijo', 'class' => 'text-left');
        $head_3 = array('data'=> 'Telef. Celular', 'class' => 'text-left');
        $head_4 = array('data'=> 'Correo', 'class' => 'text-left');
        $head_5 = array('data'=> 'Hijos', 'class' => 'text-left');
    
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_1_2, $head_2, $head_3, $head_4, $head_5);
        $cont = 1;
        foreach ($data as $row){
            $idFamiliarEnc = _simple_encrypt($row->id_familiar);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->nombrecompletoalumno);
            $row_1_1 = array('data' => $row->nombrecompletofamiliar);
            $row_1_2 = array('data' => $row->parentesco);
            $row_2 = array('data' => $row->telf_fijo);
            $row_3 = array('data' => $row->telf_celular);
            $btnVerHijos = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalHijos(\''.$idFamiliarEnc.'\')"><i class="mdi mdi-group"></i></button>';
            $row_4 = array('data' => '<a href="mailto:'.$row->correo.'" style = "color: #757575">'.$row->correo.'</a>' );
            $row_5 = array('data' => $btnVerHijos);
            $CI->table->add_row($row_0, $row_1,$row_1_1,$row_1_2, $row_2, $row_3, $row_4, $row_5);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte6_2')){
    function _createTableReporte6_2($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_1_1 = array('data'=> 'Direcci&oacute;n', 'class' => 'text-left');
        $head_2 = array('data'=> 'Telef. Fijo', 'class' => 'text-left');
        $head_3 = array('data'=> 'Telef. Celular', 'class' => 'text-left');
        $head_4 = array('data'=> 'Parentesco', 'class' => 'text-left');
    
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_2, $head_3, $head_4);
        $cont = 1;
        foreach ($data as $row){
            //$idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->nombrecompleto);
            $row_1_1 = array('data' => $row->direccion_hogar);
            $row_2 = array('data' => $row->telf_fijo);
            $row_3 = array('data' => $row->telf_celular);
            $row_4 = array('data' => $row->parentesco);
            $CI->table->add_row($row_0, $row_1, $row_1_1, $row_2, $row_3, $row_4);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_generarTablaHTMLReporte1')){
    function _generarTablaHTMLReporte1($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Aula</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Tutor</FONT>'       ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>UGEL</FONT>'    ,'style' => $left);
        $head_3_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Capacidad</FONT>'    ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Tipo Ciclo</FONT>'     ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_3_1, $head_4);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_aula.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombre_letra.'</FONT>');
            $row_col3_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->capa_actual.'/'.$row->capa_max.'</FONT>');
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->tipo_ciclo.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col3_1,$row_col4);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte2')){
    function _generarTablaHTMLReporte2($data){
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Nro. Doc.</FONT>'       ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>Cod. Alumno</FONT>'    ,'style' => $left);
        $head_3_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Cod. Familia</FONT>'    ,'style' => $left);
        $head_3_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Fec. Naci.</FONT>'    ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Mes</FONT>'     ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_3_1, $head_3_2, $head_4);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nro_documento.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->cod_alumno.'</FONT>');
            $row_col3_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->cod_familia.'</FONT>');
            $row_col3_2 = array('data' => _fecha_tabla($row->fec_naci, 'd/m/Y'));
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->mes.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col3_1,$row_col3_2,$row_col4);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte3_1')){
    function _generarTablaHTMLReporte3_1($data){
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'       ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Nro. Doc.</FONT>'    ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>Cod. Alumno</FONT>'  ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Cod. Familia</FONT>' ,'style' => $left);
        $head_5 = array('data' => '<FONT FACE="Arial" SIZE=3>  </FONT>'     ,'style' => $left);
        $head_6 = array('data' => '<FONT FACE="Arial" SIZE=3>  </FONT>'     ,'style' => $left);
        $head_7 = array('data' => '<FONT FACE="Arial" SIZE=3>  </FONT>'     ,'style' => $left);
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nro_documento.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->cod_alumno.'</FONT>');
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->cod_familia.'</FONT>');
            $row_col5  = array('data' => '<FONT FACE="Arial" SIZE=2>  </FONT>');
            $row_col6  = array('data' => '<FONT FACE="Arial" SIZE=2>  </FONT>');
            $row_col7  = array('data' => '<FONT FACE="Arial" SIZE=2>  </FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4,$row_col5,$row_col6,$row_col7);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte3_2')){
    function _generarTablaHTMLReporte3_2($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre Estudiante</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre Apoderado</FONT>'       ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>Nro. Doc.</FONT>'    ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Firma</FONT>'     ,'style' => $left);
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        foreach($data as $row){
            if($row->nombrecompletoresponsables != null){
                $apoderadosArray = explode("/", $row->nombrecompletoresponsables);
                $dniArray = explode("/", $row->nrodocresponsables);
                $c = 1;
                foreach ($apoderadosArray as $apo){
                    if($c == 1){
                        $row_0 = array('data' => $index);
                        $row_1 = array('data' => $row->nombrecompleto);
                        $row_2 = array('data' => $apo);
                        $row_3 = array('data' => $dniArray[$c-1]);
                        $row_4 = array('data' => " ");
                        $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
                    }else{
                        $row_0 = array('data' => $index);
                        $row_1 = array('data' => " ");
                        $row_2 = array('data' => $apo);
                        $row_3 = array('data' => $dniArray[$c-1]);
                        $row_4 = array('data' => " ");
                        $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
                    }
                    $c++;
                }
            }else{
                $row_0 = array('data' => $index);
                $row_1 = array('data' => $row->nombrecompleto);
                $row_2 = array('data' => " ");
                $row_3 = array('data' => " ");
                $row_4 = array('data' => " ");
                $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            }
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte3_3')){
    function _generarTablaHTMLReporte3_3($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Nro. Doc.</FONT>'       ,'style' => $left);
        $head_2_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Cod. Familia</FONT>'       ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>COD</FONT>'    ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Sexo</FONT>'     ,'style' => $left);
        $head_5 = array('data' => '<FONT FACE="Arial" SIZE=3>Colegio Proc.</FONT>'     ,'style' => $left);
        $head_5_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Tel&eacute;fono</FONT>'     ,'style' => $left);
        $head_6 = array('data' => '<FONT FACE="Arial" SIZE=3>Estado</FONT>'     ,'style' => $left);
        $head_7 = array('data' => '<FONT FACE="Arial" SIZE=3>Fec.Naci.</FONT>'     ,'style' => $left);
        $head_8 = array('data' => '<FONT FACE="Arial" SIZE=3>Apoderado</FONT>'     ,'style' => $left);
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_2_1, $head_3, $head_4, $head_5, $head_5_1, $head_6,$head_7, $head_8);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nro_documento.'</FONT>');
            $row_col2_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->cod_familia.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->cod_alumno.'</FONT>');
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->sexo.'</FONT>');
            $row_col5  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->colegio_procedencia.'</FONT>');
            $row_col5_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->telf_pers.'</FONT>');
            $row_col6  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->estado.'</FONT>');
            $row_col7  = array('data' => '<FONT FACE="Arial" SIZE=2>'._fecha_tabla($row->fec_naci, 'd/m/Y').'</FONT>');
            $row_col8  = array('data' => '<FONT FACE="Arial" SIZE=2>'.(($row->nombrecompletoresponsable != null) ? $row->nombrecompletoresponsable : '-').'</FONT>'); 
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col2_1,$row_col3,$row_col4,$row_col5,$row_col5_1,$row_col6,$row_col7,$row_col8);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte4')){
    function _generarTablaHTMLReporte4($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>Nro. Doc.</FONT>'       ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Cod. Alumno</FONT>'    ,'style' => $left);
        $head_5 = array('data' => '<FONT FACE="Arial" SIZE=3>Estado</FONT>'     ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_3, $head_4, $head_5);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nro_documento.'</FONT>');
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->cod_alumno.'</FONT>');
            $row_col5  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->estado.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col3,$row_col4, $row_col5);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte5_1')){
    function _generarTablaHTMLReporte5_1($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Nivel</FONT>'      ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>Grado</FONT>'       ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Capacidad</FONT>'    ,'style' => $left);
        $head_5 = array('data' => '<FONT FACE="Arial" SIZE=3>Varones</FONT>'     ,'style' => $left);
        $head_6 = array('data' => '<FONT FACE="Arial" SIZE=3>Mujeres</FONT>'     ,'style' => $left);
        $head_7 = array('data' => '<FONT FACE="Arial" SIZE=3>Sec.UGEL</FONT>'     ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_aula.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_nivel.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_grado.'</FONT>');
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->capa_actual.'/'.$row->capa_max.'</FONT>');
            $row_col5  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->varones.'</FONT>');
            $row_col6  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->mujeres.'</FONT>');
            $row_col7  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombre_letra.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4, $row_col5, $row_col6, $row_col7);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte5_2')){
    function _generarTablaHTMLReporte5_2($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Capacidad</FONT>'      ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>Varones</FONT>'       ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Mujeres</FONT>'    ,'style' => $left);
        $head_5 = array('data' => '<FONT FACE="Arial" SIZE=3>Sec.UGEL</FONT>'     ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_aula.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->capa_actual.'/'.$row->capa_max.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->varones.'</FONT>');
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->mujeres.'</FONT>');
            $row_col5  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombre_letra.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4, $row_col5);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte6_1')){
    function _generarTablaHTMLReporte6_1($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0 = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre Estudiante</FONT>'      ,'style' => $left);
        $head_2 = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre Familiar</FONT>'      ,'style' => $left);
        $head_3 = array('data' => '<FONT FACE="Arial" SIZE=3>Parentesco</FONT>'      ,'style' => $left);
        $head_4 = array('data' => '<FONT FACE="Arial" SIZE=3>Telef. Fijo</FONT>'      ,'style' => $left);
        $head_5 = array('data' => '<FONT FACE="Arial" SIZE=3>Telef. Celular</FONT>'       ,'style' => $left);
        $head_6 = array('data' => '<FONT FACE="Arial" SIZE=3>Correo</FONT>'       ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        foreach($data as $row){
            $row_col0  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompletoalumno.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompletofamiliar.'</FONT>');
            $row_col3  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->parentesco.'</FONT>');
            $row_col4  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->telf_fijo.'</FONT>');
            $row_col5  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->telf_celular.'</FONT>');
            $row_col6  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->correo.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3, $row_col4, $row_col5, $row_col6);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte6_2')){
    function _generarTablaHTMLReporte6_2($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0   = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1   = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_1_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Direcci&oacute;n</FONT>'      ,'style' => $left);
        $head_2   = array('data' => '<FONT FACE="Arial" SIZE=3>Telef. Fijo</FONT>'      ,'style' => $left);
        $head_3   = array('data' => '<FONT FACE="Arial" SIZE=3>Telef. Celular</FONT>'       ,'style' => $left);
        $head_4   = array('data' => '<FONT FACE="Arial" SIZE=3>Parentesco</FONT>'       ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_2, $head_3, $head_4);
        foreach($data as $row){
            $row_col0    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col1_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->direccion_hogar.'</FONT>');
            $row_col2    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->telf_fijo.'</FONT>');
            $row_col3    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->telf_celular.'</FONT>');
            $row_col4    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->parentesco.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col1_1,$row_col2,$row_col3, $row_col4);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte7')){
    function _generarTablaHTMLReporte7($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0   = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1   = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_1_1 = array('data' => '<FONT FACE="Arial" SIZE=3>Nro. Doc.</FONT>'      ,'style' => $left);
        $head_2   = array('data' => '<FONT FACE="Arial" SIZE=3>Curso</FONT>'      ,'style' => $left);
        $head_3   = array('data' => '<FONT FACE="Arial" SIZE=3>Aula</FONT>'       ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_2, $head_3);
        foreach($data as $row){
            $row_col0    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col1_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nro_documento.'</FONT>');
            $row_col2    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_curso.'</FONT>');
            $row_col3    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_aula.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col1_1,$row_col2,$row_col3);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte8')){
    function _generarTablaHTMLReporte8($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $index = 1;
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0   = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1   = array('data' => '<FONT FACE="Arial" SIZE=3>NIVEL</FONT>'      ,'style' => $left);
        $head_1_1 = array('data' => '<FONT FACE="Arial" SIZE=3># INTRASEDE</FONT>'      ,'style' => $left);
        $head_2   = array('data' => '<FONT FACE="Arial" SIZE=3># INTERSEDES</FONT>'      ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_1_1, $head_2);
        foreach($data as $row){
            $row_col0    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$index.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_nivel.'</FONT>');
            $row_col1_1  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->intrasede.'</FONT>');
            $row_col2    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->intersedes.'</FONT>');
            $CI->table->add_row($row_col0,$row_col1,$row_col1_1,$row_col2);
            $index++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_createTableDocentes')){
    function _createTableDocentes($docentes){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbDocentesAula">',//este id se lee en el js
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
//        $head_0_1 = array('data' => '#', 'class' => 'text-left');
        $head_0   = array('data' => 'Nombre', 'class' => 'text-left', 'data-sortable' => 'true');
        $head_3   = array('data' => 'Curso', 'class' => 'text-left', 'data-sortable' => 'true');
        //$head_3   = array('data' => 'Accion');
        $CI->table->set_heading( $head_0, $head_3);
        $i = 1;
        foreach ($docentes as $row){
//            $row_0_1 = array('data' => $i, 'class' => 'text-left');
            
            $row_0   = array('data' => $row->nombrecompleto, 'class' => 'text-left');
            $row_3   = array('data' => $row->desc_curso, 'class' => 'text-left');
    
            $CI->table->add_row($row_0, $row_3);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('__getDeudasByEstu')) {
    /**
     * Muestra las deudas pendientes de cronograma de un estudiante,
     * se usa en los cards del modulo de matricula para visualizar el detalle
     * de deudas pendientes
     * @param integer $idEstudiante
     * @author dfloresgonz
     * @since 02.12.2016
     * @return tabla html con las deudas del estudiante
     */
	function __getDeudasByEstu($idEstudiante) {
	    $CI =& get_instance();
	    $CI->load->model('pagos/m_movimientos');
	    $deudasArry = $CI->m_movimientos->getDeudasByEstudiante($idEstudiante);
        $CI =& get_instance();
	    $tmpl  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                                              data-page-list="[5, 10, 15, 20]"
                                                              data-search="false" id="tb_compromisoCalendarAlu">',
	                   'table_close' => '</table>');
	    $CI->table->set_template($tmpl);
	    $head_1      = array('data' => '#'                 , 'class' => 'text-right');
	    $head_2      = array('data' => 'Cuota'             , 'class' => 'text-left');
	    $head_3      = array('data' => 'Monto base (S/.)'  , 'class' => 'text-right');
	    $head_4      = array('data' => 'Mota Acum. (S/.)'  , 'class' => 'text-right');
	    $head_5      = array('data' => 'Monto Pagar. (S/.)', 'class' => 'text-right');
	    $head_6      = array('data' => 'F. de vencimiento' , 'class' => 'text-center');
	    $head_7      = array('data' => 'Estado'            , 'class' => 'text-center');
	    $CI->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
	    foreach ($deudasArry as $row) {
	        $row_cell_1 = array('data' => ($row['row_num'])       , 'class' => 'text-right');
	        $row_cell_2 = array('data' => ($row['desc_pago'])     , 'class' => 'text-left');
            $row_cell_3 = array('data' => ($row['monto'])         , 'class' => 'text-right');
            $row_cell_4 = array('data' => ($row['mora_acumulada']), 'class' => 'text-right');
            $row_cell_5 = array('data' => ($row['monto_final'])   , 'class' => 'text-right');
            $row_cell_6 = array('data' => ($row['fec_venc'])      , 'class' => 'text-center');
            $row_cell_7   = array('data' => '<span style="padding-left: 7px;" 
                                                   class="label label-'.$row['clase_css'].'" style="cursor:pointer">
                                                '.$row['estado'].'
                                            </span>'              , 'class' => 'text-center' );
            $CI->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5, $row_cell_6, $row_cell_7);
	    }
	    $table = $CI->table->generate();
	    return $table;
	}
}

if(!function_exists('_createTableDeudas')){
    /**
     * @deprecated reemplazado por __getDeudasByEstu
     * @param unknown $codigo
     * @param unknown $deudas
     * @return unknown
     */
    function _createTableDeudas($codigo,$deudas) {
        $CI =& get_instance();
        $tmpl  = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
                                                              data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                                              data-search="false" id="tb_compromisoCalendarAlu-'.$codigo.'">',
                       'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_2      = array('data' => 'Sede', 'class' => 'text-left');
        $head_3      = array('data' => 'Cuota' , 'class' => 'text-left');
        $head_4      = array('data' => 'F. de vencimiento'   , 'class' => 'text-center');
        $head_5      = array('data' => 'Monto Pagar (S/.)'       , 'class' => 'text-right');

        $CI->table->set_heading($head_2, $head_3, $head_4, $head_5);
        $val2=0;
        foreach ($deudas as $row){
            $row_cell_2           = array('data'   => ($row->desc_sede), 'class' => 'text-left');
            $row_cell_3           = array('data'   => ($row->desc_cuota), 'class' => 'text-left');
            $row_cell_4           = array('data'   => ($row->fec_vencimiento != NULL) ? (_fecha_tabla(strtolower($row->fec_vencimiento), "d/m/Y")) : '-', 'class' => 'text-center');
            $row_cell_5           = array('data'   => ($row->monto_pagar), 'class' => 'text-right');
            $CI->table->add_row($row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte9_1_2')){
    function _createTableReporte9_1_2($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_2 = array('data'=> 'Sede', 'class' => 'text-left');
        $head_3 = array('data'=> 'Nivel', 'class' => 'text-left');
        $head_4 = array('data'=> 'Grado', 'class' => 'text-left');
        $head_4_5 = array('data'=> 'Aula', 'class' => 'text-left');
        $head_5 = array('data'=> 'Estado', 'class' => 'text-left');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_4_5, $head_5);
        $cont = 1;
        foreach ($data as $row){
        	$idAlumnEnc = _simple_encrypt($row->nid_persona);
        	
//         	$imageStudent = '<img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" WIDTH=30 HEIGHT=30
// 		        class="img-circle m-r-10" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">
// 		        <p class="classroom-value classroom-link" style="display: inline" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">'.$row->nombrecompleto.'</p>';
            //$idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->nombrecompleto);
            $row_2 = array('data' => $row->desc_sede);
            $row_3 = array('data' => $row->desc_nivel);
            $row_4 = array('data' => $row->desc_grado);
            $row_4_5 = array('data' => $row->desc_aula);
            $row_5 = array('data' => $row->estado_alumno);
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_4_5, $row_5);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableReporte9_3_4')){
    function _createTableReporte9_3_4($data){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Nombre', 'class' => 'text-left');
        $head_2 = array('data'=> 'Sede', 'class' => 'text-left');
        $head_3 = array('data'=> 'Nivel', 'class' => 'text-left');
        $head_4 = array('data'=> 'Grado', 'class' => 'text-left');
        $head_4_5 = array('data'=> 'Aula', 'class' => 'text-left');
        $head_5 = array('data'=> 'Estado', 'class' => 'text-left');
        $head_6 = array('data'=> 'Ratificaci&oacute;n', 'class' => 'text-left');
    
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_4_5, $head_5, $head_6);
        $cont = 1;
        foreach ($data as $row){
        	$idAlumnEnc = _simple_encrypt($row->nid_persona);
        	
//         	$imageStudent = '<img alt="Student" src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" WIDTH=30 HEIGHT=30
// 		        class="img-circle m-r-10" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">
// 		        <p class="classroom-value classroom-link" style="display: inline" onclick="goToViewAlumno(\''.$idAlumnEnc.'\')" style="cursor:pointer">'.$row->nombrecompleto.'</p>';
            //$idAulaEnc = _simple_encrypt($row->nid_aula);
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->nombrecompleto);
            $row_2 = array('data' => $row->desc_sede);
            $row_3 = array('data' => $row->desc_nivel);
            $row_4 = array('data' => $row->desc_grado);
            $row_4_5 = array('data' => $row->desc_aula);
            $row_5 = array('data' => $row->estado_alumno);
            $row_6 = array('data' => $row->estado_ratif);
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_4_5, $row_5, $row_6);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_generarTablaHTMLReporte9_1_2')){
    function _generarTablaHTMLReporte9_1_2($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0   = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1   = array('data' => '<FONT FACE="Arial" SIZE=3>NOMBRE</FONT>'      ,'style' => $left);
        $head_2   = array('data' => '<FONT FACE="Arial" SIZE=3>SEDE</FONT>'      ,'style' => $left);
        $head_3   = array('data' => '<FONT FACE="Arial" SIZE=3>NIVEL</FONT>'      ,'style' => $left);
        $head_4   = array('data' => '<FONT FACE="Arial" SIZE=3>GRADO</FONT>'      ,'style' => $left);
        $head_5   = array('data' => '<FONT FACE="Arial" SIZE=3>AULA</FONT>'      ,'style' => $left);
        $head_6   = array('data' => '<FONT FACE="Arial" SIZE=3>ESTADO</FONT>'      ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $cont = 1;
        foreach($data as $row){
            $row_col0    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$cont.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col2  = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_sede.'</FONT>');
            $row_col3    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_nivel.'</FONT>');
            $row_col4    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_grado.'</FONT>');
            $row_col5    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_aula.'</FONT>');
            $row_col6    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->estado_alumno.'</FONT>');
            
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3, $row_col4, $row_col5, $row_col6);
            $cont++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}

if(!function_exists('_generarTablaHTMLReporte9_3_4"')){
    function _generarTablaHTMLReporte9_3_4($data){
        $CI =& get_instance();
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;color:#959595;">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $left = 'text-align:left';$right = 'text-align:right';
        $head_0   = array('data' => '<FONT FACE="Arial" SIZE=3>#</FONT>'            ,'style' => $left);
        $head_1   = array('data' => '<FONT FACE="Arial" SIZE=3>Nombre</FONT>'      ,'style' => $left);
        $head_2   = array('data' => '<FONT FACE="Arial" SIZE=3>Sede</FONT>'      ,'style' => $left);
        $head_3   = array('data' => '<FONT FACE="Arial" SIZE=3>Nivel</FONT>'      ,'style' => $left);
        $head_4   = array('data' => '<FONT FACE="Arial" SIZE=3>Grado</FONT>'      ,'style' => $left);
        $head_5   = array('data' => '<FONT FACE="Arial" SIZE=3>Aula</FONT>'      ,'style' => $left);
        $head_6   = array('data' => '<FONT FACE="Arial" SIZE=3>Estado</FONT>'      ,'style' => $left);
        $head_7   = array('data' => '<FONT FACE="Arial" SIZE=3>Ratificaci&oacute;n</FONT>'      ,'style' => $left);
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
        $cont = 1;
        foreach($data as $row){
            $row_col0    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$cont.'</FONT>', 'style' => 'border: 1px inset red');
            $row_col1    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->nombrecompleto.'</FONT>');
            $row_col2    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_sede.'</FONT>');
            $row_col3    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_nivel.'</FONT>');
            $row_col4    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_grado.'</FONT>');
            $row_col5    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->desc_aula.'</FONT>');
            $row_col6    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->estado_alumno.'</FONT>');
            $row_col7    = array('data' => '<FONT FACE="Arial" SIZE=2>'.$row->estado_ratif.'</FONT>');
            
            $CI->table->add_row($row_col0,$row_col1,$row_col2,$row_col3, $row_col4, $row_col5, $row_col6, $row_col7);
            $cont++;
        }
        $tabla = $CI->table->generate();
        return $tabla;
    }
}