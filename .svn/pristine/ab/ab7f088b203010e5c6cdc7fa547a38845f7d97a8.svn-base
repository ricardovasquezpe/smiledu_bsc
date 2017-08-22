<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script> 
		<title>Configuraci&oacute;n | <?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        
        <style type="text/css">
            .pace .pace-progress{
            	background-color: transparent !important;
            }
        </style>
        
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>	
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Nivel - Grados</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTbNivelGrados">
                                        <?php echo isset($nivelGrados) ? $nivelGrados : null?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-sm-12">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text" id="titleCursos">Cursos</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div class="img-search" id="cont_select_empty_cursos">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                            <p><strong>Hey!</strong></p>
                                            <p>Seleccione un Nivel - Grado</p>
                                            <p>para ver mas detalles.</p>                         
                                        </div>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0">
                                        <div class="table-responsive">
                                            <div  id="contCursosNivelGrados"></div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="abrirModalAddCurso();" id="btnAddCursos" style="display: none" data-toggle="tooltip" data-placement="bottom" data-original-title="Nueva evalucion">
                                            <i class="mdi mdi-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </section>
            </main>	
        </div>    
        
        <div class="modal fade" id="modalAddCurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nueva evaluaci&oacute;n</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="descripcionCurso" name="descripcionCurso" maxlength="100">        
                                        <label class="mdl-textfield__label" for="descripcionCurso">Descripci&oacute;n</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="btnAddCurso" onclick="addCurso()">Aceptar</button>
                        </div>
                    </div>
                </div>     
            </div>
        </div>  

        <div class="modal fade" id="modalConfiguracionGeneralCurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleConfiguracionGeneral"></h2>
    					</div>
					    <div class="mdl-card__supporting-text p-l-0 p-r-0">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect"> 
    					      <div class="mdl-tabs__tab-bar m-b-10">                                  
        					      <a href="#configObservacion" class="mdl-tabs__tab is-active">Opcional</a>
                                  <a href="#configIndicadores" class="mdl-tabs__tab">Indicadores</a>
                                  <a href="#configOpcIndicador" class="mdl-tabs__tab">Opc.Indicadores</a>
                                  <a href="#configOpciones" class="mdl-tabs__tab">Niveles</a>
                              </div>
    					       <div class="mdl-tabs__panel" id="configOpciones">			
        					       <div class="col-sm-12 mdl-input-group mdl-input-group__button">
    					                <div class="mdl-icon"><i class="mdi mdi-format_line_spacing"></i></div>
        				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="descripcionOpcionCurso" name="descripcionOpcionCurso" maxlength="100">        
                                            <label class="mdl-textfield__label" for="descripcionOpcionCurso">Niveles de diagn&oacute;stico</label>                        
                                        </div>
                                        <div class="mdl-btn">
                			               <button class="mdl-button mdl-js-button mdl-button--icon m-b-20 m-t-0" onclick="addOpcionesCurso()">
        								       <i class="mdi mdi-add"></i>
        								   </button>
                			           </div>
    				               </div>
        					       <div id="cont_tb_opciones_curso"></div>
    					       </div>                              
                               <div class="mdl-tabs__panel is-active" id="configObservacion">			
        					       <div class="col-sm-12 mdl-input-group mdl-input-group__button">
    					                <div class="mdl-icon"><i class="mdi mdi-comment"></i></div>        					       
    					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="observacionCurso" name="observacionCurso" maxlength="100" onchange="gaurdarObservacionCurso()">        
                                            <label class="mdl-textfield__label" for="observacionCurso">Opcional</label>
                                            <span class="mdl-textfield__limit" for="observacionCurso" data-limit="120"></span>                           
                                        </div>
    				               </div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="configIndicadores">			
        					       <div class="col-sm-12 mdl-input-group mdl-input-group__button">
    					                <div class="mdl-icon"><i class="mdi mdi-flag"></i></div>
        				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="descripcionTemaCurso" name="descripcionTemaCurso" maxlength="100">        
                                            <label class="mdl-textfield__label" for="descripcionTemaCurso">Descripci&oacute;n</label>                        
                                        </div>
                                        <div class="mdl-btn">
                			               <button class="mdl-button mdl-js-button mdl-button--icon m-b-20 m-t-0" onclick="addTemasCurso()">
        								       <i class="mdi mdi-add"></i>
        								   </button>
                			           </div>
    				               </div>
        					       <div id="cont_tb_temas_curso"></div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="configOpcIndicador">			
        					       <div class="col-sm-12 mdl-input-group mdl-input-group__button">
    					                <div class="mdl-icon"><i class="mdi mdi-flag"></i></div>
        				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="descripcionOpcIndicadores" name="descripcionOpcIndicadores" maxlength="100">        
                                            <label class="mdl-textfield__label" for="descripcionOpcIndicadores">Descripci&oacute;n</label>                        
                                        </div>
                                        <div class="mdl-btn">
                			               <button class="mdl-button mdl-js-button mdl-button--icon m-b-20 m-t-0" onclick="addOpcionesIndicadores()">
        								       <i class="mdi mdi-add"></i>
        								   </button>
                			           </div>
    				               </div>
        					       <div id="cont_tb_opc_indicador"></div>
    					       </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions p-t-20">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="modal fade" id="modalDeleteCurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Seguro de eliminar el curso?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>Recuerda: Esto afectar&aacute; a la manera en que se evaluar&aacute; a los estudiantes en un DRA.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete" id="btnDeleteCurso" onclick="deleteCurso()">Aceptar</button>
                        </div>
                    </div>
                </div>     
            </div>
        </div> 
        
        <div class="modal fade" id="modalDeleteNivelCurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Seguro de eliminar el nivel del curso?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>Recuerda: esto afectar&aacute; a la manera en que se evaluar&aacute; a los estudiantes en un DRA</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete" id="">Eliminar</button>
                        </div>
                    </div>
                </div>     
            </div>
        </div>
        
        <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text pregunta"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small></small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete" id="elim" onclick = "" >Eliminar</button>
                        </div>
                    </div>
                </div>     
            </div>
        </div>            
        
        <input type="file" id="subirArchivo" style="display: none">
        	
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
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsconfigeval.js"></script>
        
        <script>
            init();
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
	</body>
</html>