<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>        
        <title>Aula | <?php echo NAME_MODULO_MATRICULA;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_MATRICULA?>" />
       
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>cropper/cropper.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css">
		
		<style type="text/css">
            .display-none{
            	display: none !important;

            }
        </style>
    </head>
    <body onload="screenLoader(timeInit);">
        
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
        
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel is-active " id="tab-1"  onload='tabLoader(#tab-1, timeInit,timeEnd)'>
                    <div class="page-content">
                        <div class="mdl-content-cards">
                            <div class="mdl-card">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text">Datos B&aacute;sicos</h2>
                                </div>
                                <div class="mdl-card__menu" >
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-original-title="Los tutores se asignan desde el modulo de notas" data-placement="left">
                                       <i class="mdi mdi-info"></i>
                                    </button>                               
                                </div>
                                <div class="mdl-card__supporting-text br-b">
                                    <div class="row-fluid">
                                        <div class="col-sm-6 col-md-4 mdl-input-group" id="contSedeAula">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-school"></i>
                                            </div>
                                            <div class="mdl-select">
                                                <select id="sedeAula" name="sedeAula" class="form-control selectButton" data-live-search="true" <?php echo $disabled?> val-previo="<?php echo (isset($nidSede) ? $nidSede: null)?>" <?php echo $disabled?> onchange="getNivelesBySede('sedeAula', 'nivelAula', 'gradoAula') ; onSaveCampo(this,1)" attr-abc="nid_sede">
            						                  <option value="">Selec. Sede (*)</option>
            						                  <?php echo $comboSedes?>
            						             </select>
                                            </div>                                            
                                         </div>
                                         <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-select">
                                                <select id="nivelAula" name="nivelAula" class="form-control selectButton" data-live-search="true" val-previo="<?php echo (isset($nidNivel) ? $nidNivel: null)?>" <?php echo $disabled?> onchange="getGradosByNivel('sedeAula', 'nivelAula','gradoAula') ; onSaveCampo(this,1) " attr-abc="nid_nivel">
            						                  <option value="">Selec. Nivel (*)</option>
            						             </select>                                              
                                            </div>                                                    
                                         </div>
                                         <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-select">
                                                <select id="gradoAula" name="gradoAula" class="form-control selectButton" data-live-search="true" val-previo="<?php echo (isset($nidGrado) ? $nidGrado: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this,1)" attr-abc="nid_grado">
            						                  <option value="">Selec. Grado (*)</option>
            						             </select>                                           
                                            </div>                                                    
                                         </div>                                                                               
                                         <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-classroom"></i>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input upper" type="text" id="descAula" name="descAula" maxlength="50" value="<?php echo (isset($descAula) ? $descAula: null)?>" <?php echo $disabled?> 
                                                onchange="onSaveCampo(this,1) ; enableDisableSelectDesc_Aula('descAula','descAula2')" attr-abc="desc_aula" val-previo="<?php echo (isset($descAula) ? $descAula: null)?>">
                                                <label class="mdl-textfield__label" for="descAula">Nombre del Aula</label>
                                                <span class="mdl-textfield__limit" for="descAula" data-limit="30"></span>
                                                <span class="mdl-textfield__error"></span> 
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-select">
                                                <select id="descAula2" name="descAula2" <?php echo $disabled?> onchange="onSaveCampo(this,1) ; enableDisableSelectDesc_Aula('descAula','descAula2')" class="form-control selectButton" 
                                                        data-live-search="true" attr-abc="desc_aula2" >
            						                   <option value="">Selec. Aula Histórica</option>
            						                   <?php echo $comboAulas?>
            						            </select>                                       
                                            </div>                                                    
                                        </div> 
                                        <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="nombreLetra" name="nombreLetra" maxlength="1" 
                                                        value="<?php echo (isset($nombreLetra) ? $nombreLetra: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this,1)" placeholder="Por Ejemplo: A, B, C, etc..." attr-abc="nombre_letra" val-previo="<?php echo (isset($nombreLetra) ? $nombreLetra: null)?>">        
                                                <label class="mdl-textfield__label" for="nombre_letra">Sec. UGEL (*)</label>                          
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-group"></i>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="capaMax" name="capaMax" maxlength="2" 
                                                        value="<?php echo (isset($capaMax) ? $capaMax: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this,1)" attr-abc="capa_max" val-previo="<?php echo (isset($capaMax) ? $capaMax: null)?>">        
                                                <label class="mdl-textfield__label" for="capaMax">Capacidad m&aacute;xima (*)</label>                          
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4 mdl-input-group" data-toggle="tooltip" data-original-title="Los tutores se asignan desde el modulo de notas" data-placement="top">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-assignment_ind"></i>
                                            </div>
                                            <div class="mdl-select s-tutorAula">
                                                <select id="tutorAula" name="tutorAula" class="form-control selectButton" data-live-search="true" disabled onchange="onSaveCampo(this,1)" attr-abc="id_tutor">
            						                  <option value="">Selec. Tutor</option>
            						                  <?php echo $comboTutores?>
            						             </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-select s-ciclo-aula">
                                                <select id="selectTipoCiclo" name="selectTipoCiclo" <?php echo $disabled?> onchange="onSaveCampo(this,1)" class="form-control selectButton" val-previo="<?php echo (isset($tipoCiclo) ? $tipoCiclo: null)?>"
                                                        data-live-search="true" attr-abc="tipo_ciclo">
            						                   <option value="">Selec. Ciclo (*)</option>
            						                   <?php echo $comboTipoCiclo?>
            						            </select>
                                            </div>
                                        </div>                                          
                                        <div class="col-sm-6 col-md-4 mdl-input-group">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-date_range"></i>
                                            </div>
                                            <div class="mdl-select">
                                                <select id="selectYear" name="selectYear" <?php echo $disabled?> onchange="onSaveCampo(this,1)" class="form-control selectButton" val-previo="<?php echo (isset($year) ? $year: NULL)?>"
                                                        data-live-search="true" attr-abc="year">
            						                   <option value="">Selec. A&ntilde;o (*)</option>
            						                   <?php echo $comboYearCronograma?>
            						            </select>
                                            </div>                                                    
                                        </div> 

                                        <div class="col-sm-12 col-md-12 mdl-input-group">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-remove_red_eye"></i>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="observacion" name="observacion"
                                                    value="<?php echo (isset($observacion) ? $observacion: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this,1)" attr-abc="observacion">        
                                                <label class="mdl-textfield__label" for="observacion">Observaciones</label>    
                                                <span class="mdl-textfield__limit" for="observacion" data-limit="100"></span> 
                                                <span class="mdl-textfield__error"></span>                         
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-2">
                    <div class="page-content">
                        <div class="img-search" id="cont2_search_empty" style="display: <?php echo (($countAlum > 0) ? "none" : "block")?>;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/empty_add.png">
                            <p>A&uacute;n no tiene estudiantes,</p>
                            <p>ingresa al <strong>+</strong> para registrarlos.</p>
                        </div>
                        <div class="img-search" id="cont2_search_not_found" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No hay estudiantes</p>
                            <p>para mostrar.</p>
                        </div>
                        <div class="mdl-content-cards" id="cont_tabla_alumnos">
                            <?php echo $tablaAlumnos?>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-3">
                    <div class="page-content">
                        <div class="img-search" id="cont3_search_empty" style="display: <?php echo (($countDoc > 0) ? "none" : "block")?>;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                            <p>A&uacute;n no hay docentes asignados a esta aula</p>
                            <p>(Hazlo desde el m&oacute;dulo de notas).</p>
                        </div>
                        <div class="img-search" id="cont3_search_not_found" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No se encontrar&oacute;n</p>
                            <p>resultados.</p>
                        </div>
                        <div class="mdl-content-cards" id="cont_tabla_profesores">
                            <?php echo $tablaDocentes?>   
                        </div>
                    </div>
                </section>
            </main>
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap ">
                <button class="mfb-component__button--main" >
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <button class="mfb-component__button--main" onclick="abrirModalMatricular()" data-mfb-label="Matricular Estudiante">
                    <i class="mfb-component__main-icon--active mdi mdi-new_student"></i>
                </button>
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child" data-mfb-label="Filtrar" onclick="abrirModalFiltros()">
                            <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                        </button>
                    </li>         
                </ul>  
            </li>
        </ul>
        
        <div class="modal fade backModal" id="modalConfirmarDesmatricular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="msjConfirma" attr-abc="msjConfirma"></h2>
                        </div>
                        <div class="mdl-card__supporting-text p-t-0">
                            <div class="row"> 
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <small>Recuerda: Debes colocar alguna observaci&oacute;n sobre el porqu&eacute; se retira al estudiante del aula.</small>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="observDesmatricula" name="observDesmatricula" maxlength="205" rows="5" cols="50"></textarea>
                                        <label    class="mdl-textfield__label" for="observDesmatricula">Observaci&oacute;n</label>
                                        <span class="mdl-textfield__limit" for="observDesmatricula" data-limit="200"></span>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                            <button id="botonDM" class="mdl-button mdl-js-button mdl-button--raised accept" onclick="desmatricular()">Aceptar</button>
                        </div>                                     
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Filtro de estudiantes</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row"> 
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreFiltro" name="nombreFiltro" maxlength="100" onchange="buscarAlumno()">        
                                        <label class="mdl-textfield__label" for="nombreFiltro">Nombre, C&oacute;digo o N* Documento</label>                            
                                    </div>
                                </div>
                            </div>
                        </div>
    					<div class="mdl-card__actions">
    					    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalMatricularAlumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md">
                <div class="modal-content ">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Matricular Estudiante</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-rl-0">
                            <div class="row-fluid">    
                                <div class="col-sm-12 mdl-input-group mdl-input-group__button p-rl-16">
				                   <div class="mdl-icon"></div>
				                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="inputAlumno">
                                        <input class="mdl-textfield__input" type="text" id="inputNomAlumno" name="inputNomAlumno" onkeyup="changeButtonBuscar()">        
                                        <label class="mdl-textfield__label" for="inputNomAlumno" id="labelInputNombreAlumno">Estudiante</label>                            
                                   </div>
            			           <div class="mdl-btn">
									   <button class="mdl-button mdl-js-button mdl-button--icon" onclick="getAlumnosByName()" disabled id="btnBuscar">
                                            <i class="mdi mdi-search" id="search-alumno"></i>
                                         </button>
            			           </div>
            			           <button class="mdl-button mdl-js-button mdl-button__icon" onclick="getAlumnosMatriculablesByName()" id="btnTodos" data-toggle="tooltip" data-placement="right" data-original-title="Ver Todos los Matriculables y Promovidos de este grado">
                                       <i class="mdi mdi-students" id="search-general"></i><span id="MatAlumnos">Ver MB y PR</span>
                                   </button>
					            </div>                                
                                <div class="col-xs-12 p-0">
                                    <div id="cont_tabla_alumnos_sinaula"></div>	
                                </div>
                            </div>
                        </div>
    					<div class="mdl-card__actions">

                            <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                            <button id="btnAceptarMatricular" class="mdl-button mdl-js-button mdl-button--raised accept" data-dismiss="modal" name="btnAceptarMatricular" onclick="abrirModalConfirmAsignarEstudiantes()">Aceptar</button>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalConfirmAsignarEstudiantes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleConfirmaMatricular"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					    <small id="msjConfirmaMatricular"></small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                            <button id="buttonEstado" class="mdl-button mdl-js-button mdl-button--raised accept" onclick="asignarEstudiantes()">Aceptar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>       
        
        <div class="modal fade backModal" id="modalFiltroDocentes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__menu" >
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-original-title="Los docentes se asignan a trav&eacute;s del m&oacute;dulo de notas."  data-placement="left">
                               <i class="mdi mdi-info info-doc-aula"></i>
                            </button>                               
                        </div>
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro de docentes</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
                            <div class="row"> 
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <select id="selectCurso" name="selectCurso" class="form-control selectButton" data-live-search="true" onchange="getDocentesByCursosAula('selectCurso')">
						                <option value="">Selec. curso</option>
						                <?php echo isset($comboCursos) ? $comboCursos : null ?>
						            </select>
                                </div>
                            </div>
                        </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal backModal" id="modalRecordatorioDocentes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Busqueda de Docentes</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
                            <p>Para agregar un docente debe dirigirse al modulo de notas</p>
                        </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal small fade" id="modalCompromisosEstudiante" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Compromisos de pago</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">
					       <div class="row m-0 p-0">
    					       <div class="col-sm-12 p-0" id="calendarCompromisos">
    					       </div>	   
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jsdetalleaula.js"></script>
    	
        <script type="text/javascript">
            returnPage();
            init();
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
            <?php if(_getSesion('accionDetalleAula') == 0 || _getSesion('accionDetalleAula') == 1){?>
                <?php if($visibleSede == 1){?>
                $("#contSedeAula").css("display", "none");
                $('#sedeAula').selectpicker('hide');
                <?php if($nidSede == null){?>
                    setearCombo('sedeAula','<?php echo $sedeActual?>');
                <?php }else{?>
                    setearCombo('sedeAula','<?php echo $nidSede?>');
                <?php }?>
                    getNivelesBySede('sedeAula', 'nivelAula', 'gradoAula');
            <?php }?>
                setearCombo('sedeAula','<?php echo $nidSede?>');
                getNivelesBySede('sedeAula', 'nivelAula','gradoAula');
                setearCombo('nivelAula','<?php echo $nidNivel?>');
                getGradosByNivel('sedeAula', 'nivelAula','gradoAula');
                setearCombo('gradoAula','<?php echo $nidGrado?>');
                setearCombo('tutorAula','<?php echo $idTutor?>','<?php echo $idTutor?>',1);
                setearCombo('selectYear','<?php echo (isset($year) ? $year: null)?>');
                setearCombo('selectTipoCiclo','<?php echo $tipoCiclo?>');
                enableDisableSelectDesc_Aula('descAula', 'descAula2');
                <?php if(_getSesion('accionDetalleAula') == 0){?>
                    disableEnableCombo("sedeAula", true);
                    disableEnableCombo("nivelAula", true);
                    disableEnableCombo("gradoAula", true);
                    disableEnableCombo("tutorAula", true);
                    disableEnableCombo("selectYear", true);
                    disableEnableCombo("selectTipoCiclo", true);
                    disableEnableInput("descAula", true);
                    disableEnableCombo("descAula2", true);
                <?php }?>
            <?php }else{?>

            <?php if($visibleSede == 1){?>
                $("#contSedeAula").css("display", "none");
                $('#sedeAula').selectpicker('hide');
                setearCombo('sedeAula','<?php echo $sedeActual?>');
                getNivelesBySede('sedeAula', 'nivelAula', 'gradoAula');
            <?php }?>
        
            <?php }?>
            setTimeout(function(){ $('.mfb-component__button--main').removeClass('is-up'); }, 500);

            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
        	});
            
        </script>
    </body>
</html>