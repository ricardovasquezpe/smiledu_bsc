<!DOCTYPE html>
<html lang="en">
    <head>          
		<title><?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />

        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/css/calendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION;?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/mdl-card-style.css">
                
        <style type="text/css">
            @media ( max-width : 750px ) {
            	header span.mdl-layout-title a{
            		display: block;
            	}
        	}        
        	
        	#observacionEntrevista[readonly]{
        		color: #757575 !important;
                cursor: default !important;        		
        	}
        </style>
	</head>
	
	<body>
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
            <main class='mdl-layout__content is-visible'>
                <section> 
                    <div class="mdl-content-cards" id="cont_evento";>
                        <div id="cont_search_eventos"></div>
                        <div class="img-search" id="cont_imagen_magic" style="display:none ">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_empty_state.png">
                            <p><strong>Hola!</strong></p>
                            <p>Prueba el buscador</p>
                            <p>m&aacute;gico.</p>           
                        </div>
                        <div class="img-search" id="cont_search_not_found" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_not_found.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No hay postulantes agendados para hoy</p>
                            
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored-text" onclick="reintentarBusqueda();">Reintentar</button>
                        </div>                       
                    </div>
                    <div class="mdl-content-cards" id="cont_general_invitados" style="display: block">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text p-0 br-b" id="title_invitados"><?php echo $weekDay;?></h2>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="leftRightDia(1)" data-toggle="tooltip" data-placement="bottom" data-original-title="Evaluar">
                                    <i class="mdi mdi-keyboard_arrow_left"></i>
                                </button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="leftRightDia(2)" data-toggle="tooltip" data-placement="bottom" data-original-title="Evaluar">
                                    <i class="mdi mdi-keyboard_arrow_right"></i>
                                </button>
                            </div>
                            <div class="mdl-card__menu" style="width: 175px">
        						<button class="mdl-button mdl-js-button mdl-button--icon" onclick="contactosHoy()">
                                    <i class="mfb-component__child-icon mdi mdi-date_range"></i>
                                </button>
        					</div>
                            <div class="mdl-card__supporting-text p-0 br-b">
                                <div class="table-responsive" id="cont_tabla_princ">
                                    <?php echo (isset($contactosHoy) ? $contactosHoy : null)?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> 
            </main>
        </div>
        
        <div class="modal fade" id="modalBuscarContacto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Buscar Contactos</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="buscarContacto" name="buscarContacto" onchange="buscarContacto()">        
                                    <label class="mdl-textfield__label" for="buscarContacto">Nombre</label>                            
                                </div>
                                <div class="mdl-btn">
        			                <button class="mdl-button mdl-js-button mdl-button--icon" id="btnBuscar" onclick="buscarContacto()">
							            <i class="mdi mdi-search"></i>
							        </button>
        			            </div>                                     
			               </div>
		                   <div id="cont_busqueda_contacto"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
       
        <div class="modal fade" id="modalQuitarContacto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas quitar el contacto seleccionado?</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">    				
				           <small id="notaQuitarContacto">Al quitar el contacto, se borrar&aacute; de cualquier registro que haya sido agendado.</small>
					       <br/>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonAE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised out" onclick="quitarContacto()">Quitar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAgendarContacto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agendar contacto</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="col-sm-12 mdl-input-group p-l-10 p-r-15">
                                <div class="mdl-icon mdl-icon__button">
			                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfechaAgendarContacto">
			                            <i class="mdi mdi-event_note"></i>
		                            </button>
	                            </div>					               
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="fechaAgendarContacto" name="fechaAgendarContacto" maxlength="10" onchange="cambioFecha()">        
                                    <label class="mdl-textfield__label" for="fechaAgendarContacto">Fecha</label>                            
                                </div>
                            </div>
                            <div class="col-sm-12 mdl-input-group p-l-10 p-r-15">
                                <div class="mdl-icon mdl-icon__button">
			                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconhoraAgendarContacto">
			                            <i class="mdi mdi-access_time"></i>
		                            </button>
	                            </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="horaAgendarContacto" name="horaAgendarContacto"  maxlength="8" onchange="cambioFecha()">        
                                    <label class="mdl-textfield__label" for="horaAgendarContacto">Hora</label>                            
                                </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                						<h6 class="mdl-card__title-text" id="msjCantContactosAgendar"></h6>
                					</div>
                               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agendarContacto()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalBuscarContactoDia" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Buscar Contacto</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="buscarContactoFiltrar" name="buscarContactoFiltrar" onchange="buscarContactoFiltroDia()">        
                                    <label class="mdl-textfield__label" for="buscarContactoFiltrar">Nombre</label>                            
                                </div>
                                <div class="mdl-btn">
        			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscarFiltrar" onclick="buscarContacto()">
							            <i class="mdi mdi-search"></i>
							        </button>
        			            </div>                                     
			               </div>
		                   <div class="col-sm-12 mdl-input-group p-l-10 p-r-15">
                                <div class="mdl-icon mdl-icon__button">
			                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfechaBuscarContacto">
			                            <i class="mdi mdi-event_note"></i>
		                            </button>
	                            </div>					               
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="fechaBuscarContacto" name="fechaBuscarContacto" maxlength="10" onchange="buscarContactoFiltroDia()">        
                                    <label class="mdl-textfield__label" for="fechaBuscarContacto">Fecha</label>                            
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="buscarContactoFiltroDia()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalMigrarMatricula" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">�Estas seguro de pasarlo al proceso de matr�cula?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0" id="msjPassProceso" style="display: none">
                                Recuerda que al pasarlo al proceso de matr�cula, todos los datos del estudiante y de sus familiares se enviar�n al SISTEMA DE MATR�CULA.
                                Se enviar� las credenciales del sistema de matr�cula al correo electr�nico del familiar registrado.
			               </div>
			               <div class="row p-0 m-0" id="msjNoPassProceso" style="display: none">
                                Parece que faltan llenar algunos datos, tanto a los parientes como al postulante.
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnProcesoMatricula">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalResumenDiag" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="tituloResumenDiag"></h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div id="cont_tb_resumen_diagnostico"></div>
					       </div>
    					</div>
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Entrevista con Sub-director</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div id="cont_tb_diagnostico_subdirector"></div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalObservacion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Observaci&oacute;n</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="col-sm-12 mdl-input-group mdl-input-group__only" >
					           <p id="observacionEntrevista"></p>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover" style="z-index: 11;" data-mfb-state="closed">
            <li class="mfb-component__wrap" id="li_menu_1" style="">
                <button class="mfb-component__button--main mdl-js-button mdl-js-ripple-effect is-up" data-upgraded=",MaterialButton,MaterialRipple">
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                    <span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span>
                </button>
                <button class="mfb-component__button--main mdl-js-button mdl-js-ripple-effect is-up" onclick="abrirModalFiltrarContacto()" data-mfb-label="Buscar" data-upgraded=",MaterialButton,MaterialRipple">
                    <i class="mfb-component__main-icon--active mdi mdi-search"></i>
                    <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating" style="width: 160.392px; height: 160.392px; transform: translate(-50%, -50%) translate(41px, 32px);"></span></span>
                </button> 
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child mdl-js-button mdl-js-ripple-effect" data-mfb-label="Filtrar" onclick="abrirModalFiltrar()" data-upgraded=",MaterialButton,MaterialRipple">
                            <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                            <span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span>
                        </button>                            
                    </li>                
                </ul>    
             </li>                   
          </ul>
        
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>   
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>   
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsevaluarrapido.js"></script> 
        
        <script>
            magicIcon();
            returnPage();
            var $_fecha = '<?php echo date('d/m/Y');?>';
            init();
        	imageMainHeader("icon_admision");
        </script>
	</body>
</html>