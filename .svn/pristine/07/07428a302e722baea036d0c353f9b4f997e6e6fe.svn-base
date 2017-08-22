<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
		<title>Agenda | <?php echo NAME_MODULO_SPED;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SPED?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SPED;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-calendar-master/css/calendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED?>css/submenu.css">
        
	</head>

	<body onload="screenLoader(timeInit);">	
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
            <?php echo $menu; ?>
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">	
                        <div class="mdl-card mdl-calendar">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="fechaCalendar"></h2>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <div id="calendar"></div>
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
								<button class="mdl-button mdl-js-button mdl-button--icon" data-button-type="menu" id="more-calendar">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="more-calendar">
                                    <li class="mdl-menu__item" data-calendar-nav="today">Hoy</li>
                                    <li class="mdl-menu__item" data-calendar-view="month">Mes</li>
                                    <li class="mdl-menu__item" data-calendar-view="week">Semana</li>
                                    <li class="mdl-menu__item" data-calendar-view="day">D&iacute;a</li>
                                </ul>
							</div>
                        </div>
                    </div>
                </section>
            </main> 
        </div>
    	
    	<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                <button class="mfb-component__button--main" id="main_button" onclick="abrirCerrarModalAddTipoVisita();" data-mfb-label="Nuevo visita">
                    <i class="mfb-component__main-icon--resting mdi mdi-event"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-event"></i>
                </button>
            </li>
        </ul>
        
    	<div class="offcanvas"></div>
    	
    	<!-- No se usa en el js ni en la vista -->
    	<div class="modal fade" id="modalEditEvaluacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text custom-toolbar">Editar Evaluaci&oacute;n</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    					   
					        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" name="descripcionEdit" id="descripcionEdit">
                                <label class="mdl-textfield__label" for="descripcionEdit">Descripci&oacute;n</label>
                            </div>              
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect"  data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarEvaluacion();">Guardar</button>
                        </div>
                    </div>                    
                </div>  
            </div>
        </div>
        
        <div class="modal fade" id="evaluarPendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content"> 
                    <input type="hidden" name="evaluacion" id="evaluacion" value="">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text custom-toolbar">&#191;Qu&eacute; acci&oacute;n desea hacer?</h2>
    					</div>
    					<div class="mdl-card__actions">
                            <button id="openConfirmBorrar" class="mdl-button mdl-js-button mdl-js-ripple-effect">Borrar</button>
                            <button id="btnEvaluar" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised">Evaluar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="confirmBorrarVisita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea borrar la visita?</h2>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
    						<button id="confirmBorrar" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised">Confirmar</button>
    					</div>
    				</div>
                </div>  
            </div>
        </div>
        
        <div class="modal fade" id="modalChangeEvent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content"> 
                    <form action="c_agenda/goToEvaluarPendiente" name="formEvaluar" id="formEvaluar" method="post">
                        <input type="hidden" name="evaluacion" id="evaluacion" value="">
                        <input type="hidden" name="estado" id="estado" value="">        
                        <div class="mdl-card" >
                            <div class="mdl-card__title">
        						<h2 class="mdl-card__title-text custom-toolbar " id="fechaCambio"></h2>
        					</div>
        					<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect"  data-dismiss="modal">Cancelar</button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="changeEvaluacionDate();">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalAddTipoVisita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nueva visita</h2>
    					</div>
    					<div class="mdl-card__menu" style="right: 24px; top: 16px; width: 175px">
    						<div style="position: absolute; right: 42px; top: 2.5px; color: #FFFFFF">Google Calendar</div>
    						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch_aplicar">
    							<input type="checkbox" <?php echo (isset($code_calendar) ) ? 'checked' : null;?> id="switch_aplicar" class="mdl-switch__input" onchange="addToCalendar($(this));">
    						</label>
    					</div>
					    <div class="mdl-card__supporting-text">
					        <div class="row-fluid">
					           <div class="col-sm-6 mdl-input-group">
					                <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFechaVisitar">
				                            <i class="mdi mdi-today"></i>
			                            </button>
		                            </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                      <input class="mdl-textfield__input" type="text" id="fechaVisitar" name="fechaVisitar" onchange="changeValor($(this));">
                                      <label class="mdl-textfield__label" for="fechaVisitar">Fecha a visitar</label>
                                   </div>
                                </div>
                                <div class="col-sm-6 mdl-input-group">
                                    <div class="mdl-icon">
			                            <i class="mdi mdi-note_add"></i>
		                            </div>
                                    <div class="mdl-select">
                                       <select id="selectTipoVisita" name="selectTipoVisita" data-live-search="true" class="form-control selectButton"
                                               onchange="changeValor($(this), true);">
                                           <option value="">Selec. Tipo Visita</option>
                                           <?php echo $optTipoVisita; ?>
    	                               </select>
            			            </div>
                                </div>
					            <div class="col-sm-6 mdl-input-group">
					               <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconHoraInicio">
				                            <i class="mdi mdi-access_time"></i>
			                            </button>
		                           </div>
                                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="horaInicio" name="horaInicio" onchange="changeValor($(this));">
                                       <label class="mdl-textfield__label" for="horaInicio">Hora de inicio</label>
                                   </div>
					            </div>
					            <div class="col-sm-6 mdl-input-group">
					               <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconHoraFin">
				                            <i class="mdi mdi-timelapse"></i>
			                            </button>
		                           </div>
                                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="horaFin" name="horaFin" onchange="changeValor($(this));">
                                       <label class="mdl-textfield__label" for="horaFin">Hora de Fin</label>
                                   </div>
					            </div>
					            <div class="col-sm-6 mdl-input-group">
                                   <div class="mdl-icon">
			                           <i class="mdi mdi-filter_list"></i>
		                           </div>
                                   <div class="mdl-select">
                                       <select id="selectFiltro" name="selectFiltro" data-live-search="true" title="Tipo Filtro" class="form-control selectButton" 
                                               onchange="onchangeSelectFiltro();changeValor($(this), true);">
                                            <option value="">Selec. Filtro</option>
                                            <?php echo $optFiltro;?>
    	                               </select>
            			           </div>
					            </div>
					            <div class="col-sm-6 mdl-input-group">
                                   <div class="mdl-icon">
			                           <i class="mdi mdi-person"></i>
		                           </div>
                                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="inputFiltro" name="inputFiltro" disabled="disabled"
                                              onchange="onChangeInputFiltro(); changeValor($(this));">        
                                       <label class="mdl-textfield__label" for="inputFiltro">Filtrar</label>
                                   </div>
					            </div>
					        </div>
					        <div class="row">
                                <div class="col-sm-12 p-0" >
                                    <div class="form-group" id="contTbHorarios">
                                    </div>
                                </div>
					        </div>	        
    					</div>
    					<div class="mdl-card__actions p-t-0">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect"  data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarNuevaEvaluacion();">Agendar</button>
                        </div>
                    </div>                    
                </div>  
            </div>
        </div>
        
        <div class="modal fade" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content"> 
                    
                </div>  
            </div>
        </div>
			
		<div id="divHoras"></div>
        
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
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>        
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/language/es-ES.js"></script>     
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/calendar.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_SPED;?>js/jsagenda.js"></script>
        <script type="text/javascript">
        	marcarNodo("Agendarevaluacion");
        	initAgenda();
            $(document).ready(function() {
            	var datos = <?php echo json_encode($calendarData, JSON_NUMERIC_CHECK); ?>;
            	initCalendario(datos);
        	});
        	
            var code_calendar = <?php echo isset($code_calendar)  ? $code_calendar  : 0;?>;
        	if(code_calendar != 0) {
        	    modal('modalAddTipoVisita');
        	}
            initButtonCalendarHours('horaFin');
            initButtonCalendarHours('horaInicio');
            initMaskInputs('fechaVisitar');  
            initMaskTime('horaFin', 'horaInicio');
            initButtonCalendarDaysMinToday('fechaVisitar');
            
            setValor('fechaVisitar');
            setValor('horaInicio');
            setValor('horaFin');
            setValor('selectTipoVisita', true);
            setValor('selectFiltro', true);
            setValor('inputFiltro');
            if( $.trim($("#inputFiltro").val()).length == 0 ) {
            	$("#inputFiltro").prop('disabled', true);
            	$("#inputFiltro").parent().addClass('is-disabled');
            } else {
            	$("#inputFiltro").prop('disabled', false);
            	$("#inputFiltro").parent().removeClass('is-disabled');
            }
            if(getComboVal('selectFiltro')) {
                $('#inputFiltro').attr('disabled', false);
            }
            var horarios = sessionStorage.getItem('horarios');
            if(horarios) {
                $('#contTbHorarios').html(
                        '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" '+
                        '       style="background-color:white;border-color:white"'+
                        '       data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" data-page-size="5"'+
                        '       data-show-columns="false" data-search="false" id="tb_horarios">'+
                	    '<thead>'+
                        '<tr>'+
                        '   <th data-field="idx">#</th>'+
                        '   <th data-field="radio_button">Sel.</th>'+
                        '   <th data-field="docente">Docente</th>'+
                        '   <th data-field="curso">Curso</th>'+
                        '   <th data-field="aula">Aula</th>'+
                        ' </tr>'+
                        '</thead>'+
                        '</table>');
                var page_number = sessionStorage.getItem('pageNumber');
            	if( page_number == null ) {
            		page_number = 1;
            	}
            	$('#tb_horarios').bootstrapTable({
                    data       : JSON.parse(horarios),
                    pageNumber : parseInt(page_number)
                });
            	componentHandler.upgradeAllRegistered();
				tableEvents('tb_horarios');
            }
		</script>
	</body>
</html>