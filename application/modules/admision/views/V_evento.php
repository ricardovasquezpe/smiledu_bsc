<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>  
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>            
		<title>Eventos | <?php echo NAME_MODULO_ADMISION?></title>
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/css/calendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        <style>
            .mdl-card__menu .mdl-button.mdl-button--icon i{
            		color:#757575 !important;
            }
            .mdl-event .mdl-card__supporting-text.table-responsive{
	           overflow-y:auto;
            }
            .mdl-radio__outer-circle {
                top: 0px !important;
                width: 20px  !important;
                height: 20px  !important;
            }
        </style>
        
        
	</head>

	<body onload="screenLoader(timeInit);">	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">
                        <div class="mdl-card mdl-calendar" id="card-event">
                            <div class="mdl-card__title" style="display: inline-block;color: #757575;">
                                <h2 class="mdl-card__title-text" id="fechaCalendar"></h2>
                                <small id="calendarText" style="display:none;"></small>
                            </div>
                            <div class="mdl-card__supporting-text p-0">
                                <div class="table-responsive">
                                    <div id="tbEventos">
                                        <?php echo $tablaEventos?>
                                    </div>
                                </div>                                        
                            </div>
                            <div id="calendar" class="mdl-card__supporting-text"></div>
                            <div class="img-search" id="cont_search_not_found_eventos" style="display: <?php echo $display_not_found?>;">
                                <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                <p><strong>&#161;Ups!</strong></p>
                                <p>No se encontraon</p>
                                <p>resultados.</p>
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" data-calendar-nav="prev">
                                    <i class="mdi mdi-keyboard_arrow_left"></i>
                                </button>
                               	<button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" data-calendar-nav="next">
                               	    <i class="mdi mdi-keyboard_arrow_right"></i>
                           	    </button>
                           	    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-nav="today">Hoy</button>
                              	<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="month">Mes</button>
                               	<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="week">Semana</button>
								<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="day">D&iacute;a</button>
                                <?php if($permCrearEvento == 1){?>
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-button-type="onclick" onclick="abrirModalCrearEvento()" data-toggle="tooltip" data-placement="bottom" data-original-title="Crear">
                                       <i class="mdi mdi-edit"></i>
                                    </button>
                                <?php }?>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-button-type="onclick" onclick="abrirCerrarModal('modalFiltro')" data-toggle="tooltip" data-placement="bottom" data-original-title="Filtrar">
                                    <i class="mdi mdi-filter_list"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-button-type="calendar" onclick="showCalendarHideTable()" data-toggle="tooltip" data-placement="bottom" data-original-title="Calendario">
                                    <i class="mdi mdi-sync"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-button-type="list" onclick="showTableHideCalendar()" data-toggle="tooltip" data-placement="bottom" data-original-title="Lista">
                                    <i class="mdi mdi-sync"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-button-type="calendar" onclick="goToRegistro()" data-toggle="tooltip" data-placement="bottom" data-original-title="Registro">
                                    <i class="mdi mdi-content_paste"></i>
                                </button>
                                <button class="mdl-button mdl-js-button mdl-button--icon" data-button-type="menu" id="more-calendar">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="more-calendar">
                                    <?php if($permCrearEvento == 1){?>
                                        <li class="mdl-menu__item" onclick="abrirModalCrearEvento()">Nuevo evento</li>
                                    <?php }?>
                                    <li class="mdl-menu__item mdl-menu__item--full-bleed-divider" onclick="abrirCerrarModal('modalFiltro')">Filtrar</li>
                                    <li class="mdl-menu__item" data-calendar-nav="today">Hoy</li>
                                    <li class="mdl-menu__item" data-calendar-view="month">Mes</li>
                                    <li class="mdl-menu__item" data-calendar-view="week">Semana</li>
                                    <li class="mdl-menu__item mdl-menu__item--full-bleed-divider" data-calendar-view="day">D&iacute;a</li>
                                    <li class="mdl-menu__item" data-button-type="calendar" onclick="showCalendarHideTable()">Calendario</li>
                                    <li class="mdl-menu__item" data-button-type="list" onclick="showTableHideCalendar()">Listado</li>
                                </ul>
                            </div>
                        </div>       
                    </div>
                </section>
            </main>	
            
        </div>
        
        <!-- Modals -->
        <?php if($permCrearEvento == 1){?>
        <div class="modal fade" id="modalCrearEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Crear evento</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-6 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectTipoEventoCrear" name="selectTipoEventoCrear" data-live-search="true" onchange="selectTipoEventoCrearEvento()">
                			                <option value="">Tipo de evento (*)</option>
                			                <?php echo (isset($comboTipoEventos) ? $comboTipoEventos : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 mdl-input-group mdl-input-group__only"> 
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="yearEventoCrear" name="yearEventoCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="yearEventoCrear">Año de campaña (*)</label>                            
                                    </div>
				               </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectSedeCrear" name="selectSedeCrear" data-live-search="true">
                			                <option value="">Sede</option>
                			                <?php echo (isset($comboSubDirectores) ? $comboSubDirectores : null)?>
                			           </select>
            			           </div>
					           </div>
				               <div class="col-sm-6 mdl-input-group mdl-input-group__only"> 
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreEventoCrear" name="nombreEventoCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombreEventoCrear">Nombre (*)</label>                            
                                    </div>
				               </div>
					           <div class="col-sm-6 mdl-input-group p-l-10 p-r-15">
                                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfechaEventoCrear">
				                            <i class="mdi mdi-event_note"></i>
			                            </button>
		                            </div>					               
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fechaEventoCrear" name="fechaEventoCrear" maxlength="10">        
                                        <label class="mdl-textfield__label" for="fechaEventoCrear">Fecha</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-6 mdl-input-group p-l-10 p-r-15">
                                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconHoraInicioEventoCrear">
				                            <i class="mdi mdi-access_time"></i>
			                            </button>
		                            </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="horaInicioEventoCrear" name="horaInicioEventoCrear"  maxlength="8">        
                                        <label class="mdl-textfield__label" for="horaInicioEventoCrear">Hora inicio</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-6 mdl-input-group p-l-10 p-r-15">
                                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconHoraFinEventoCrear">
				                            <i class="mdi mdi-timelapse"></i>
			                            </button>
		                            </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="horaFinEventoCrear" name="horaFinEventoCrear"  maxlength="8">        
                                        <label class="mdl-textfield__label" for="horaFinEventoCrear">Hora fin</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" type="text" id="observacionEventoCrear" name="observacionEventoCrear" maxlength="100"></textarea>        
                                        <label class="mdl-textfield__label" for="observacionEventoCrear">Observaciones</label> 
                                        <span class="mdl-textfield__limit" for="observacionEventoCrear" data-limit="100"></span>     
                                        <span class="mdl-textfield__error"></span>                           
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only m-t-10">
                                    <div class="mdl-select">
                                        <select class="form-control selectButton" id="selectTourEventoCrear" name="selectTourEventoCrear" data-live-search="true" disabled> 
                                            <option value="">Enlazar a tour</option>
                			                <?php echo (isset($comboTourCampanaActual) ? $comboTourCampanaActual : null)?>
                			           </select>
            			           </div>
        			           </div>
        			           <div class="col-sm-12 m-t-10">
        			               <div id="cont_tabla_colaboradores_crear"></div>
        			           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonCE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="crearEvento()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <?php }?>
        
        <div class="modal fade" id="modalDetalleEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Detalle evento</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-l-0 p-r-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreEventoDetalle" name="nombreEventoDetalle" maxlength="100" disabled>        
                                        <label class="mdl-textfield__label" for="nombreEventoDetalle">Nombre</label>                            
                                    </div>
				               </div>
					           <div class="col-sm-12 p-0 m-b-15">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fechaEventoDetalle" name="fechaEventoDetalle"  data-inputmask="'alias': 'date'" maxlength="10" disabled>        
                                        <label class="mdl-textfield__label" for="fechaEventoDetalle">Fecha</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="observacionEventoDetalle" name="observacionEventoDetalle" maxlength="100" disabled>        
                                        <label class="mdl-textfield__label" for="observacionEventoDetalle">Observaciones</label>                            
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarEvento()">Editar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAsistenciaEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content-cards">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title ">
    						<h2 class="mdl-card__title-text">Asistencia evento</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">
					      <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect"> 
    					      <div class="mdl-tabs__tab-bar">
                                  <a href="#invitados" class="mdl-tabs__tab is-active">Invitados</a>
                                  <a href="#otros" class="mdl-tabs__tab">Otros</a>
                              </div>
                               <div class="mdl-tabs__panel is-active" id="invitados">			
        					       <div class="row p-0 m-0">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-r-20 p-l-20">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="buscarContactoListaAsistencia" name="buscarContactoListaAsistencia" onchange="buscarContactoListaAsistencia();" onkeyup="activeDesactiveSearchLista()">        
                                                <label class="mdl-textfield__label" for="buscarContactoListaAsistencia">Buscar en lista</label>                            
                                            </div>
                                            <div class="mdl-btn">
                    			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscarLista" onclick = "buscarContactoListaAsistencia()">
        								            <i class="mdi mdi-search"></i>
        								        </button>
                    			            </div>                                                
                                       </div>
  					                   <div id="cont_table_asistencia_invitados"></div>
        					           <div class="img-search" id="cont_teacher_empty9" style="display: none;">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                            <p><strong>&#161;Ups!</strong></p>
                                            <p>A&uacute;n no hay personas</p>
                                            <p>contactadas.</p>
                                        </div>
        					       </div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="otros">			
        					       <div class="row p-0 m-0">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-r-20 p-l-20">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label ">
                                                <input class="mdl-textfield__input" type="text" id="buscarContactoAsistencia" name="buscarContactoAsistencia" onchange="buscarContactoAsistencia();" onkeyup="activeDesactiveSearchContacto()">        
                                                <label class="mdl-textfield__label" for="buscarContactoAsistencia">Buscar Contacto</label>                            
                                            </div>
                                            <div class="mdl-btn">
                    			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscarContactoLista" onclick = "buscarContactoAsistencia()">
        								            <i class="mdi mdi-search"></i>
        								        </button>
                    			            </div>                                                 
                                       </div>
        					           <div id="cont_table_asistencia_otros"></div>
        					           <div class="img-search" id="cont_teacher_empty10" style="display: block;">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                            <p><strong>&#161;Ups!</strong></p>
                                            <p>A&uacute;n no hay personas</p>
                                            <p>contactadas.</p>
                                        </div>
        					       </div>
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
        
        <div class="modal fade" id="modalConfirmaAnular" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas anular el evento seleccionado?</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">    				
				           <small id="notaAnularEvento">Al anular el evento, se enviar&aacute; un correo a todas las personas asociadas a este.<br>Recuerda: Si tiene eventos enlazados se independizar&aacute;n.</small>
					       <br/>
					       <div class="col-sm-12 mdl-input-group mdl-input-group__only  p-rl-0">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <textarea class="mdl-textfield__input" type="text" id="observacionEventoAnulado" name="observacionEventoAnulado" maxlength="120"></textarea>        
                                    <label class="mdl-textfield__label" for="observacionEventoAnulado">Observaciones</label> 
                                    <span class="mdl-textfield__limit" for="observacionEventoAnulado" data-limit="100"></span>     
                                    <span class="mdl-textfield__error"></span>                           
                                </div>
                           </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonAE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised out" onclick="anularEvento()">Anular</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmaEliminar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Eliminar evento</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0 ">
					           &#191;Deseas eliminar el evento seleccionado?
					           <p id="notaEliminarEvento" style="font-weight: bold;">El evento seleccionado tiene eventos enlazados, los cuales se haran independientes</p>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="botonEE" onclick="eliminarEvento()">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalDetalleContactoEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="tituloDetalleContactoEvento">Detalle contactos </h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">    				
					       <div class="row p-0 m-0 text-center">
					           <div class="bootstrap-table">
					              <div class="table-responsive">
    					              <div id="cont_contactos_evento"></div>
    					          </div>    
					           </div>
					           <div class="img-search" id="cont_teacher_empty7" style="display: none;">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>A&uacute;n no hay personas</p>
                                    <p>contactadas.</p>
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
        
        
        
        <div class="modal fade" id="modalDetalleInvitadosAsistieron" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Detalle de asistencia</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row p-0 m-0 text-center">
                                <div class="bootstrap-table">
            						<div class="table-responsive">
                						<div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
                						   <div id="searchAsistieron" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                							   <input class="mdl-textfield__input" type="text" id="searchAsistente" name="searchAsistente" onkeyup="activeDesactivesearchAsistentes()" onchange="getAllAsistentes()">
                							   <label class="mdl-textfield__label" for="searchAsistente">Buscar en lista</label>
                						   </div>
                						   <div class="mdl-btn">
                							  <button class="mdl-button mdl-js-button mdl-button--icon" disabled id="btnBuscarAsistentes" onclick="getAllAsistentes()">
                								  <i class="mdi mdi-search"></i>
                							  </button>
                						   </div>
                					    </div>
                					    
                                        <div id="cont_invitados_asistieron"></div>
                   
                                        <div class="img-search" id="cont_teacher_empty8" style="display: none;">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                            <p><strong>&#161;Ups!</strong></p>
                                            <p>A&uacute;n no hay personas</p>
                                            <p>contactadas.</p>
                                        </div>
                                    </div>
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
        
        
        
         <div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar eventos</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">    				
					       <div class="row-fluid">
				               <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16"> 
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="selectNombreEventoFiltro" name="selectNombreEventoFiltro" data-live-search="true" maxlength="100" onchange="selectTipoEventoYearFiltro()">        
                                        <label class="mdl-textfield__label" for="nombreEvento">Nombre del Evento</label>                            
                                    </div>
                                    <div class="mdl-btn">
            			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscarEventoFiltro" onclick = "selectTipoEventoYearFiltro()">
								            <i class="mdi mdi-search"></i>
								        </button>
            			            </div>                                      
				               </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectTipoEventoFiltro" name="selectTipoEventoFiltro" data-live-search="true" onchange="selectTipoEventoYearFiltro()">
                			                <option value="">Seleccion Tipo de evento</option>
                			                <?php echo (isset($comboTipoEventos) ? $comboTipoEventos : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectYearFiltro" name="selectYearFiltro" data-live-search="true" onchange="selectTipoEventoYearFiltro()">
                			                <option value="">Seleccione A&ntilde;os</option>
                			                <?php echo (isset($comboYearsEventos) ? $comboYearsEventos : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectEstadoFiltro" name="selectEstadoFiltro" data-live-search="true" onchange="selectTipoEventoYearFiltro()">
                			                <option value="">Seleccione Estado</option>
                			                <option value="PENDIENTE" selected>Pendiente</option>
                			                <option value="FINALIZADO">Finalizado</option>
                			                <option value="ANULADO">Anulado</option>
                			           </select>
            			           </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
    					    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmFinalizarEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title p-b-0">
    						<h2 class="mdl-card__title-text">&#191;Est&aacute;s seguro de finalizar el evento?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
				            <small>Recuerda: Al finalizar un evento ya no podr&aacute;s editarlo. Enviaremos un correo de felicitaciones a las personas que participaron.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="finalizarEvento()">Finalizar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalLeyendaEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Leyenda de eventos</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					        <ul class="list-group">
                                <li class="list-group-item"><span class="label label-pending-today">PENDIENTE</span>Evento en espera</li>
                                <li class="list-group-item"><span class="label label-pending">PENDIENTE</span>Evento hoy</li>
                                <li class="list-group-item"><span class="label label-realized" style="margin-right: 7px">FINALIZADO</span>Evento realizado</li>
                                <li class="list-group-item"><span class="label label-canceled" style="margin-right: 17px">ANULADO</span>Evento cancelado</li>
                                <li class="list-group-item"><span class="label label-warning" style="margin-right: 17px">POR FINALIZAR</span>Evento por finalizar</li>
                            </ul>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Entend&iacute;</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal"><i class="mdi mdi-close"></i></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalProgresoEva" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Progreso de Evaluaci&oacute;n</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
				           <div class="img-search" style="display: block;">
                                <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                                <p>Falta configurar todos los cursos ingresados</p>
                            </div>                            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Entend&iacute;</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal"><i class="mdi mdi-close"></i></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalElegirEventoDraInvitar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Invitaci&oacute;n DRA</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">
				             <div class="col-sm-12 mdl-input-group mdl-input-group__only">
				               <div class="mdl-select">
					               <select class="form-control selectButton" id="selectEventoDraInvitar" name="selectEventoDraInvitar" data-live-search="true" onchange="getHorariosByEvento()">
            			                <option value="">Seleccione DRA</option>
            			           </select>
        			           </div>
				             </div>
				             <div class="col-sm-12 mdl-input-group mdl-input-group__only">
				               <div class="mdl-select">
					               <select class="form-control selectButton" id="selectHorarioDraInvitar" name="selectHorarioDraInvitar" data-live-search="true">
            			                <option value="">Seleccione Horario</option>
            			           </select>
        			           </div>
				             </div>
				             <div class="col-sm-12 mdl-input-group mdl-input-group__only"> 
				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="correoContactoDraInvitar" name="correoContactoDraInvitar" maxlength="100">        
                                    <label class="mdl-textfield__label" for="correoContactoDraInvitar">Confirmar correo</label>                            
                                </div>
			                 </div>                 
    					</div>
    					<div class="mdl-card__actions">
    					    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="cerrarInvitacionContaco()">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarInvitacionContacto()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEditarAsistenciaFamDRA" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Progreso de Evaluaci&oacute;n</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
				            <div id="cont_table_editar_asistencia_familia_dra"></div>       
    					</div>
    					<div class="mdl-card__actions">
    					    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="buttonEditarAsistenciaFamDRA">Guardar</button>
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
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.bootstrap3.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/language/es-ES.js"></script>     
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/calendar.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsevento.js"></script>
        <script>
            initCalendario('<?php echo $eventosCalendario?>');
            init();
            disableEnableCombo("selectTourEventoCrear", true);
            $(document).ready(function(){
         	       $('[data-toggle="tooltip"]').tooltip();
             });
            function goToRegistro(){
            	window.location.href = 'registro';
            }
        </script>
	</body>
</html>