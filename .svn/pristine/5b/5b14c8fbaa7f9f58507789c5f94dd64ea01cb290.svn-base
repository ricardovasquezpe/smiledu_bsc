<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Asignar  Alumnos | <?php echo NAME_MODULO_NOTAS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width    =device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>cropper/cropper.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_NOTAS?>css/submenu.css">
        
        <style type="text/css">
            #cabecera .breadcrumb li:NTH-CHILD(2){
            	padding-left: 10px
            }
            
            #cabecera .breadcrumb li:NTH-CHILD(2):BEFORE{
            	content: none;
            }
            .paintRowAlum {
            	background-color:#D8F6CE;     	
            }
            
            .paintGroupRowAlum {
            	background-color:#FFF7AD;     	
            }
        </style>
	</head>

	<body>
	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content is-visible'>
                <section>
                    <div class="mdl-content-cards">                                
                        <div class="row-fluid">
                            <div class="col-sm-10 col-md-10 col-md-offset-1" id="cabecera" style="display: none;">
                                <ol class="breadcrumb">
                                    <li class="active"><strong>Filtro:</strong></li>
                    				<li class=""></li>
                    				<li class=""></li>
                    				<li class=""></li>
                    				<li class=""></li>
                    			</ol>                     
                			</div>
                            <div class="col-sm-4 col-md-4 col-md-offset-1"  >
                                <div id="tGrupos" class="mdl-card" style="display: none;">
                                    <div class="mdl-card__title">
                                        <h2 id="h2_Grupo" class="mdl-card__title-text"></h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" >
                                        <div class="table-responsive" id="contTbGrupos"></div>
                                        <div class="img-search" id="cont_search_grupo" style="display:none">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                            <p>Filtre de nuevo para visualizar los grupos.</p>                             
                                        </div>
                                    </div>
                                     <div class="mdl-card__menu">
                                        <button id="izq" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="moverIzDer(0)">
                                            <i class="mdi mdi-keyboard_arrow_left"></i>
                                        </button>
                                        <button id="der" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="moverIzDer(1)">
                                            <i class="mdi mdi-keyboard_arrow_right"></i>
                                        </button>
                                    </div>                             
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div id="tAulas" class="mdl-card" style="display: none">
                                    <div class="mdl-card__title">
                                        <h2 id="mostrarAula"class="mdl-card__title-text">Aulas</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" >
                                        <div class="table-responsive" id="contTbAulas">                                          
                                        </div>                                         
                                         <div class="img-search" id="cont_show_aula" style="display:block">
                                              <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                              <p><strong>Hey!</strong></p>
                                              <p>Seleccione un grupo para visualizar</p>
                                              <p>sus aulas</p>                             
                                         </div>        
                                         <div class="img-search" id="cont_search_aula" style="display:none">
                                              <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                              <p><strong>Ups!</strong></p>
                                              <p>El grupo seleccionado</p>
                                              <p>no contiene aulas.</p>                             
                                         </div>                                                        
                                    </div>
                                    <div class="mdl-card__menu">
                                         <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                             <i class="mdi mdi-more_vert"></i>
                                         </button>
                                    </div>
                                </div>
                            </div>                                                                               
                        </div>
                        <div class="img-search" id="cont_search_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar las aulas.</p>                             
                        </div>
                    </div>
                </section>
            </main>	
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main" >
                    <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                </button>
                <button id="btnCrearCurso" class="mfb-component__button--main mdl-only-btn__animation" data-toggle="modal" data-target="#modalFiltros" data-mfb-label="Filtrar" data-toggle="modal">
                    <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                </button>    
            </li>
        </ul>      
               
        <!-- Modals -->
        <div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="cmbYears" name="cmbYears" class="form-control pickerButn" data-live-search="true">
                			                <option value="">Selec. A&ntilde;o</option>
                			                <?php echo isset($cmbYears) ? $cmbYears : null;?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="cmbCursos" name="cmbCursos" class="form-control pickerButn" data-live-search="true">
                			                <option value="">Selec. Cursos</option>
                			                <?php echo isset($cmbCursos) ? $cmbCursos : null;?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="cmbGradoNivel" name="cmbGradoNivel" class="form-control pickerButn" data-live-search="true" title="">
                			                <option value="">Selec. Grado</option>
                			                <?php echo isset($cmbGradoNivel) ? $cmbGradoNivel : null;?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="cmbSede" name="cmbSede" class="form-control pickerButn" data-live-search="true" title="">
                			                <option value="">Selec. Sede</option>
                			                <?php echo isset($cmbSedes) ? $cmbSedes : null;?>
                			           </select>
            			           </div>
					           </div>
	                           <div class="col-sm-12" id="cont_not_filter" style="display:none">
                                   <div class="img-search">
                                       <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                       <p><strong>&#161;Ups!</strong></p>
                                       <p>No se han encontraron</p>              
                                       <p>resultados.</p>
                                   </div>
                               </div>				           
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnCDD" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getTbGrupos()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
                
        <div class="modal fade" id="modalAsignarAlumnos" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
            				<h2 class="mdl-card__title-text">Asignar Alumnos</h2>
            			</div> 
            			<div class="mdl-card__supporting-text">
                            <div class="row">
            	               <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
            		               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="buscar" name="buscar" maxlength="60" onkeyup="buscarAlumnoAula(event);">        
                                       <label class="mdl-textfield__label" for="buscar">Buscar</label>
                                   </div>
                                   <div class="mdl-btn">
            			               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buscarAlumnoAula();">
            					            <i class="mdi mdi-search"></i>
            					       </button>
            			           </div>
            		           </div>
                               <div id="contTbAlum" class="col-sm-12 p-0"></div>
                            </div>
            			</div>
            			<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="buttonDocente" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="asignarAlumno()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAlumnoGrupo" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
            				<h2 class="mdl-card__title-text">Lista Alumnos</h2>
            			</div> 
            			<div class="mdl-card__supporting-text">
                            <div class="row">
            	               <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
            		               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="buscarAlumnoGrupo" name="buscar" maxlength="60" onkeyup="buscarAlumnoGrupo(event);">        
                                       <label class="mdl-textfield__label" for="buscar">Buscar</label>
                                   </div>
                                   <div class="mdl-btn">
            			               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buscarAlumnoGrupo();">
            					            <i class="mdi mdi-search"></i>
            					       </button>
            			           </div>
            		           </div>
                               <div id="contTbAlumGrupo" class="col-sm-12 p-0"></div>
                            </div>
            			</div>
            			<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
         <div class="modal fade" id="mdAlertCambioAlum" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
               <div class="modal-content">
                   <div class="mdl-card">
                       <div class="mdl-card__title">
                           <h2 class="mdl-card__title-text">ALERTA</h2>
                       </div>
                       <div class="mdl-card__supporting-text">    	  
                           <small>Hay alumnos que est&aacute;n pendientes en ser asignados a un grupo (Quitando el check ), pueden ser cambiados a otro grupo pero estos seguir&aacute;n en el grupo que inicialmente pertenecen hasta que se haga el cambio.
                                  </small>
                       </div>
                       <div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
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
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>wizard/js/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>cropper/cropper.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsAsigAlumCurso.js"></script>
        <script>
            $('main.mdl-layout__content').addClass('is-visible');
            init();
        </script>   
	</body>
</html>
