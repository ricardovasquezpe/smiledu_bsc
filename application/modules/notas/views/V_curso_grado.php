<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <title>Curso por grado | <?php echo NAME_MODULO_NOTAS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>fullcalendar/fullcalendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
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
                                <div id="tableCursosUgel" class="mdl-card" style="display: none">
                                    <div class="mdl-card__title">
                                        <h2 id="mostrarGradoAnio" class="mdl-card__title-text"></h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTbCursoGrado">
                                        <div class="table-responsive"></div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="getCursosPorAsignar();" id="btnAddCursosGrado">
                                            <i class="mdi mdi-edit"></i>
                                        </button>
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
                                <div id="cursosEquivalentes" class="mdl-card" style="display: none">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Cursos</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div class="img-search" id="cont_select_empty_curso">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                            <p><strong>Hey!</strong></p>
                                            <p>Seleccione un curso</p>
                                            <p>para ver mas detalles.</p>                         
                                        </div>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTbCursosEquiv" style="text-transform: lowercase;">
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="getCursosEquivalentesModal();" id="btnAddCursosEquiv">
                                            <i class="mdi mdi-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-12"  id="cont_filter_empty">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                                    <p>Primero debemos filtrar para</p>
                                    <p>visualizar los cursos.</p>                             
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
                </section>
            </main>	
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main mdl-only-btn__animation">
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <button id="btnCrearCurso" class="mfb-component__button--main" data-toggle="modal" data-mfb-label="Nuevo curso" data-toggle="modal">
                    <i class="mfb-component__main-icon--active mdi mdi-edit"></i>
                </button>
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalFiltros">
                            <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                        </button>
                    </li>                     
                </ul>    
            </li>
        </ul>
        
        <!-- MODALES -->
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
    					               <select id="cmbGradoNivel" name="cmbGradoNivel" class="form-control pickerButn" data-live-search="true" title="">
                			                <option value="">Selec. Grado</option>
                			                <?php echo isset($cmbGradoNivel) ? $cmbGradoNivel : null;?>
                			           </select>
            			           </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnFC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getCursosByGrado()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalCrearCurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nuevo curso</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input upper" type="text" id="descCurso" name="descCurso" maxlength="80">
                                       <label class="mdl-textfield__label" for="descCurso">Nombre del curso</label>
                                    </div>
					           </div>     
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="cmbTipoCurso" name="cmbTipoCurso" class="form-control pickerButn" data-live-search="true" title="Selec. tipo de curso"
    					                       onchange="tipoCursoChange();">
                			                <option value="">Selec. tipo de curso</option>
                			                <?php echo isset($cmbTipoCurso) ? $cmbTipoCurso : null;?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" style="text-transform:uppercase" type="text" id="abvrCurso" name="abvrCurso" maxlength="5">
                                       <label class="mdl-textfield__label" for="abvrCurso">Abreviatura</label>
                                    </div>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only" style="display: none;" id="divArea">
					               <div class="mdl-select">
                			           <select id="cmbArea" name="cmbArea" class="form-control pickerButn" data-live-search="true" title="">
                			                <option value="">Selec. &Aacute;rea Acad&eacute;mica</option>
                			                <?php echo isset($cmbAreas) ? $cmbAreas : null;?>
                			           </select>
            			           </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnCC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarCurso()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalNuevo" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text"></h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0 p-t-0" id="contTbCursosAsig"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnNCU" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onchange="iniTableCursoGrado" onclick="asignarCursoUgel()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalCursoEquivalente" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cursos Equivalentes</h2>
    					</div>
					    <div id="contTbCursosEquivAsig" class="mdl-card__supporting-text p-rl-0 p-t-0"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnNCE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="asignarCursoEquiv()"></button>
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
                           <h2 class="mdl-card__title-text">&#191;Desea eliminar el Curso seleccionado?</h2>
                       </div>
                       <div class="mdl-card__supporting-text">    	  
                           <small>Usted eliminar&aacute; el Curso seleccionado.<br>
                                  Recuerda: se quitar&aacute;n los cursos equivalentes que asignaste.</small>
                       </div>
                       <div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                           <button id="btnEC" class="mdl-button mdl-js-button mdl-button--raised accept" onclick="borrarCursoxGrado()"></button>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       
       <div class="modal fade" id="mdConfirmDeleteEquivalencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
               <div class="modal-content">
                   <div class="mdl-card">
                       <div class="mdl-card__title">
                           <h2 class="mdl-card__title-text">&#191;Desea eliminar el Curso Equivalente Seleccionado?</h2>
                       </div>
                       <div class="mdl-card__supporting-text">    	  
                           <small>Usted eliminar&aacute; el Curso Equivalente seleccionado.</small>
                       </div>
                       <div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                           <button id="btnECE" class="mdl-button mdl-js-button mdl-button--raised accept" onclick="borrarEquivalencia()"></button>
                       </div>
                   </div>
               </div>
           </div>
       </div>
        
        <div class="modal fade" id="mdEditarCursoxGrado" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Peso</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">			
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="peso" name="peso" pattern="^\d+(\.\d{1,2})?$">
                                       <label class="mdl-textfield__label" for="peso">Peso</label>
                                    </div>
					           </div>  
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnEPC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizarPesoCursoxGrado()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> 
        
        <div class="modal fade" id="mdEditarEquiv" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Peso</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">					           
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input id="pesoEquiv" class="mdl-textfield__input" name="pesoEquiv" type="text" pattern="^\d+(\.\d{1,2})?$">
					                   <label for="pesoEquiv" class="mdl-text__label">Peso</label>
					                   <span class="mdl-textfield__error">Ingrese solo n&uacute;meros</span>
                                   </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnEPCE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizarPesoCursoEquiv()"></button>
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
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsCursoGrado.js"></script>
        <script>
            initCursoGrado();
            $('main.mdl-layout__content').addClass('is-visible');
        </script>
	</body>
</html>