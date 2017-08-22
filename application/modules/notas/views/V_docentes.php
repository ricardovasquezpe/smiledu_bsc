<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Docentes y aulas | <?php echo NAME_MODULO_NOTAS?></title>
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_NOTAS?>css/docentes.css">
        
        <style type="text/css">
            #cabecera .breadcrumb li:NTH-CHILD(2){
            	padding-left: 10px
            }
            
            #cabecera .breadcrumb li:NTH-CHILD(2):BEFORE{
            	content: none;
            }
        </style>
	</head>
	<body>	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>   		
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">                                
                        <div class="row-fluid">
                            <div class="col-sm-12" id="cabecera" style="display: none;">
                                <ol class="breadcrumb">
                                    <li class="active"><strong>Filtro:</strong></li>
                    				<li class=""></li>
                    				<li class=""></li>
                    			</ol>                     
                			</div>
                            <div class="col-sm-6">
                                <div id="taulas" class="mdl-card" style="display: none">
                                    <div class="mdl-card__title">
                                        <h2 id="mostrarGrado"class="mdl-card__title-text"></h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTbAulas">                                               
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
                                <div id="tcursos" class="mdl-card" style="display: none">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Cursos</h2>
                                    </div>
                                       <div class="img-search" id="cont_select_empty_curso" >
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                            <p><strong>Hey!</strong></p>
                                            <p>Seleccione un aula</p>
                                            <p>para ver sus cursos.</p>
                                        </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTbCursos" ></div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="mdi mdi-more_vert"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>                   
                            <div class="col-sm-12"  id="cont_search_empty">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                                    <p>Primero debemos filtrar para</p>
                                    <p>visualizar las aulas.</p>                             
                                </div>
                            </div>
                            <div class="col-sm-12" id="cont_not_filter" style="display:none">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se han encontrar&oacute;n</p>              
                                    <p>resultados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>	
        </div>        
        
        <ul id="menu" class="mfb-component--br mfb-zoomin display-none" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main mdl-only-btn__animation">
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <button id="btnCrearGrupo" class="mfb-component__button--main" data-mfb-label="Nuevo grupo" data-toggle="modalGrupos">
                    <i class="mfb-component__main-icon--active mdi mdi-edit"></i>
                </button>
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalDocentes">
                            <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                        </button>
                    </li>
                    <li>
                        <button id="consultarGrupos" class="mfb-component__button--child" data-mfb-label="Consultar Grupos" data-toggle="modal" data-target="">
                            <i class="mfb-component__child-icon mdi mdi-people_outline"></i>
                        </button>
                    </li>                      
                </ul>
            </li>
        </ul>
        
        <!-- Modals -->
        <div class="modal fade" id="modalDocentes" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
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
    					               <select id="cmbYears" name="cmbYears" class="form-control pickerButn" data-live-search="true" title="Selec. A&ntilde;o">
                			                <option value="">A&ntilde;o</option>
                			                <?php echo isset($cmbYears) ? $cmbYears : null;?>
                			           </select>
            			           </div>
					           </div>				           
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="cmbGradoNivel" name="cmbGradoNivel" class="form-control pickerButn" data-live-search="true" title="Selec. Grado">
                			                <option value="">Selec. Grado</option>
                			                <?php echo isset($cmbGradoNivel) ? $cmbGradoNivel : null;?>
                			           </select>
            			           </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="btnFD" onclick="getAulas()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <!-- FALTA ARREGLAR DISEÑO -->
        <div class="modal fade" id="modalConsultarGrupos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Grupos Registrados</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0" id="contTbConsultarGrupos"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalGrupos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Grupos Registrados</h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="contTbGruposByCursos"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAsignarGrupos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Grupos</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0" id="contTbAsigGrupos"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <!-- FALTA ARREGLAR DISEÑO -->
        <div class="modal fade" id="modalRegistrarGrupo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">                           
    				        <h2 class="mdl-card__title-text">Nuevo Grupo</h2>												 						
    					</div>
    					<div class="mdl-card__supporting-text mdl-wizard" id="cont_form_colab"> 
                           <div class="form-wizard form-wizard-horizontal " id="rootwizard1">
                                <div class="form-wizard-nav">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary" Id ="progressBar"></div>
                                    </div>
                                    <ul class="nav nav-justified nav-pills">
                                        <li class="active tab1" id="li1">
                                            <a data-toggle="tab" aria-expanded="true" href="#tab1" id="step1" onclick="nextStep(1)">
                                                <span class="step"></span>
                                                <span class="title">&Aacute;rea</span>
                                            </a>
                                        </li>
                                        <li class ="tab2" id="li2">
                                            <a data-toggle="tab" aria-expanded="false" href="#tab2" class ="my-link-par" id="step2" onclick="nextStep(2)">
                                                <span class="step"></span>
                                                <span class="title" >Docente</span>
                                            </a>
                                        </li>
                                        <li class ="tab3" id="li3">
                                            <a data-toggle="tab" aria-expanded="false" href="#tab3" class ="my-link-par" id="step3" onclick="nextStep(3)">
                                                <span class="step"></span>
                                                <span class="title">Grado</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
    						</div>
                            <div class="tab-content">
                                <div class="tab-pane pane-par tabb1 active" id="tab1">
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only m-t-20">
                                        <div class="mdl-select">
                                            <select id="selecArea" name="selecArea" data-live-search="true" class="form-control pickerButn" onchange="getCmbTallerCursoModalGrupo();">
                                                <option value="">Selec. &Aacute;rea</option>
                                                  <?php echo isset($cmbAreas) ? $cmbAreas : null;?>
        	                                </select>
    	                                </div>
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">     
                                        <div class="mdl-select" id="contCmbTaller" ></div>
                                    </div>                                           
                                    <div class="row-fluid" id="taller" style="display:none">
                                       <div id="contDescTaller"></div>
            				           <div id="nomGrupo" class="col-sm-6 mdl-input-group mdl-input-group__only nomGrupo">
            				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="descGrupo" name="descGrupo" maxlength="80">
                                               <label class="mdl-textfield__label" for="descGrupo">Nombre del grupo</label>
                                            </div>
            				           </div>
            				           <div class="col-sm-6 mdl-input-group mdl-input-group__only">
            				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="number" id="capacidad" name="capacidad" step="any" min="1" max="100">
                                               <label class="mdl-textfield__label" for="capacidad">Capacidad</label>
                                            </div>
            				           </div> 
            				           <div class="col-sm-6 mdl-input-group">
            				                <div class="mdl-icon"><i class="mdi mdi-school"></i></div>
                                            <div class="mdl-select" id="contCmbAula"></div>
            				            </div>  
            					    </div>
        					    </div>
        					    <div class="tab-pane pane-par tabb2" id="tab2">
        					         <div class="col-sm-3 text-center" id="contFoto">
                                       
                                     </div>
                                    <div class="col-sm-9 mdl-input-group mdl-input-group__text-btn p-rl-5">
            					         <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                			                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label descDoc is-disabled">
                                                   <input class="mdl-textfield__input inputDocente" type="text" id="descDocente" name="docente" maxlength="80" disabled>
                                                   <label class="mdl-textfield__label" for="capacidad">Docente</label>
                                               </div>
                                         </div>  
                                           <div class="mdl-btn">
                    			               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="getDocenteGrupoModal()">
                    					            <i class="mdi mdi-add"></i>
                    					       </button>
                    			           </div>
            			                 
            	                    </div>   
        			             </div>
        			             <div class="tab-pane pane-par tabb3" id="tab3">
                                    <div class="mdl-card__supporting-text">
                                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                                           <div class="row p-0 m-0">
                                	           <div id="contTableGrados" class="text-center">
                                               </div>
                                	       </div>                       	      
                                        </div>
                                    </div> 
        			             </div> 
    			             </div>	              
					       </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarGrupo()">Registrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAsignarDocente" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
            				<h2 class="mdl-card__title-text">Asignar Docente</h2>
            			</div> 
            			<div class="mdl-card__supporting-text">
                            <div class="row">
            	               <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
            		               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="buscar" name="buscar" maxlength="60" onkeyup="buscarDocente(event);">        
                                       <label class="mdl-textfield__label" for="buscar">Buscar</label>
                                   </div>
                                   <div class="mdl-btn">
            			               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buscarDocente();">
            					            <i class="mdi mdi-search"></i>
            					       </button>
            			           </div>
            		           </div>
                               <div id="contTbDocAsig" class="col-sm-12 p-0"></div>
                            </div>
            			</div>
            			<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="buttonDocente" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="asignarDocente()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>             
        
        <div class="modal fade" id="modalAsignarTutor" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title titulo_tutor_css">
                            <h2 class="mdl-card__title-text" >Asignar tutores y cotutores</h2>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect tutor_new_css" onclick="agregarTuCo(1, $(this))" style="display: none"><i class="mdi mdi-add"></i></button>
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect cotutor_new_css" onclick="agregarTuCo(2, $(this))" style="display: none"><i class="mdi mdi-add"></i></button>
                        </div>
                        <div class="mdl-card__supporting-text p-0 br-b">
                            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                                <div class="mdl-tabs__tab-bar">
                                    <a href="#tab_tutor" class="mdl-tabs__tab is-active" onclick="clickTabAsigTutorCoTutor($(this));">Tutor</a>
                                    <a href="#tab_cotutores" class="mdl-tabs__tab"       onclick="clickTabAsigTutorCoTutor($(this));">Cotutores</a>
                                </div>
                                <div class="mdl-tabs__panel is-active" id="tab_tutor" >
                                    <div class="row p-0 m-0">
                                       <div class="mdl-list" id="contListaTutor" style="display:none"></div>
                                    </div>
                                </div>
                                <div class="mdl-tabs__panel" id="tab_cotutores">
                                    <div class="row p-0 m-0">
                                       <div class="mdl-list" id="contListaCotutor" style="display:none"></div>
                                    </div>
                                </div>
                            </div>					       
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
         
        <div class="modal fade" id="modalAgregarTuCo" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="nombreTutor">Asignaci&oacute;n</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="buscarTutor" name="buscar" maxlength="60" onkeyup="buscarTutorAsig(event);">        
                                        <label class="mdl-textfield__label" for="buscar">Buscar</label>
                                    </div>
                                    <div class="mdl-btn">
                                        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buscarTutorAsig()">
                                            <i class="mdi mdi-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-xs-12 p-0" id="contTbTutorAsig"></div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnATC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="asigReasigTutorCoTutor()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
       
       <div class="modal fade" id="mdConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
               <div class="modal-content">
                   <div class="mdl-card">
                       <div class="mdl-card__title">
                           <h2 class="mdl-card__title-text">&#191;Desea desasignar o desactivar a este docente?</h2>
                       </div>
                       <div class="mdl-card__supporting-text">    	  
                           <small>Este docente ya no estar&aacute; asignado para este curso.</small>
                           <div class="text-center p-t-15">
                               <label id="activarRadio" style="margin-right: 20px;" class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radioAct">Activar
    				               <input id="radioAct" type="radio" class="mdl-radio__button" name="radioVals"  value="" onclick="radioDesacDesasig(1)">
    				               <span></span>
    			              </label>
                               <label id="desactivarRadio" style="margin-right: 20px;" class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radioDesac">Desactivar
    				               <input id="radioDesac" type="radio" class="mdl-radio__button" value="" name="radioVals" onclick="radioDesacDesasig(2)">
    				               <span></span>
    			              </label>
                               <label  id="desasignarRadio" class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radioDesas">Desasignar
    				               <input id="radioDesas" type="radio" class="mdl-radio__button" name="radioVals" value="" onclick="radioDesacDesasig(0)">
    				               <span></span>
    			              </label>
                           </div>
                       </div>
                       <div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                           <button id="btnCDD" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="desaDesactDocente()"></button>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       
       <div class="modal fade" id="mdConfirmCotutorDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
               <div class="modal-content">
                   <div class="mdl-card">
                       <div class="mdl-card__title">
                           <h2 class="mdl-card__title-text">&#191;Desea dejar de asignar a este cotutor?</h2>
                       </div>
                       <div class="mdl-card__supporting-text">    	  
                           <small>Este cotutor ya no estar&aacute; asignado para esta aula.</small>
                       </div>
                       <div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="deleteTutCot(2)">Aceptar</button>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       
       <div class="modal fade" id="mdConfirmTutorDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
               <div class="modal-content">
                   <div class="mdl-card">
                       <div class="mdl-card__title">
                           <h2 class="mdl-card__title-text">&#191;Desea dejar de asignar a este tutor?</h2>
                       </div>
                       <div class="mdl-card__supporting-text">    	  
                           <small>Este tutor ya no estar&aacute; asignado para esta aula.</small>
                       </div>
                       <div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                           <button id="btnCTD" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="deleteTutCot(1)">Aceptar</button>
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
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsDocente.js"></script>
        <script>
            $('main.mdl-layout__content').addClass('is-visible');
            init();
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>   
	</body>
</html>