 <?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>      
        <title>Correos | <?php echo NAME_MODULO_PAGOS?></title>
         <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, 1ial-scale=1.0">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/css/calendar.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/correos.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	   
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' id="tableCompr">    		
    		<?php echo $menu ?>    		
    		<main class='mdl-layout__content'>
        		<section>
                    <div class="mdl-content-cards">
                        <div class="mdl-card mdl-calendar">
                            <div class="mdl-card__title p-rl-0">
                                <h2 class="mdl-card__title-text" id="fechaCalendar"></h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0" id="calendarioCorreos">
                            </div>
                            <div class="mdl-card__menu">
                            	<div class="pull-right form-inline" id="btn-group-dates" style="display: none">
									<button class="mdl-button mdl-js-button mdl-button--icon" data-calendar-nav="prev"><i class="mdi mdi-keyboard_arrow_left"></i></button>
                                   	<button class="mdl-button mdl-js-button mdl-button--icon" data-calendar-nav="today" data-toggle="tooltip" data-placement="bottom" title="Hoy"><i class="mdi mdi-today"></i></button>
                                   	<button class="mdl-button mdl-js-button mdl-button--icon" data-calendar-nav="next"><i class="mdi mdi-keyboard_arrow_right"></i></button>
									<button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalCrearEvento();"><i class="mdi mdi-edit"></i></button>
									<button class="mdl-button mdl-js-button mdl-button--icon" onclick="sendCorreos()"><i class="mdi mdi-mail"></i></button>
								</div> 
                            </div>
                        </div>
                    </div>
                </section>
    		</main>	
        </div>        
        
        <div class="modal fade" id="modalCrearEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nuevo correo</h2>
    					</div>
					    <div class="mdl-card__supporting-text">  
                            <div class="row-fluid">					         				
                                <div class="col-sm-12 mdl-input-group p-0" style="min-height: 1px">
                                    <small>Se agregar&aacute; una nueva fecha en la cual se enviar&aacute; un correo.</small>
                                </div>
                                <div class="col-sm-12 mdl-input-group p-0">
                                    <div class="mdl-icon">
                                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFecEnvio">				                            
                                            <i class="mdi mdi-today"></i>
			                            </button>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fecEnvio" name="fecEnvio" maxlength="10"/>
                                        <label class="mdl-textfield__label" for="fecEnvio">Fecha de env&iacute;o</label>
                                        <span class="mdl-textfield__error"></span>
                                    </div>                                           
                                </div>
                                <div class="col-sm-12 mdl-input-group p-0">
                                	<div class="mdl-icon"><i class="mdi mdi-contact_mail"></i></div>
                                    <div class="mdl-select">
						               <select id="selectTipo" name="selectTipo" class="form-group pickerButn" data-live-search="true">
						                   <option value="">Tipo de Correos</option>
						                   <option value="2">Cuota Vencida</option>
						                   <option value="3">Rec. Vencimiento</option>
	                                   </select>
                                   </div>
					            </div>
                                <div class="col-sm-12 mdl-input-group p-0" style="min-height: 1px; padding-left: 12px !important">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="replicar">
                                        <input type="checkbox" id="replicar" class="mdl-checkbox__input">
                                        <span class="mdl-checkbox__label" style="font-size: 14px; line-height: 20px;"><small>&#191;Deseas replicar en los siguientes meses&#63;</small></span>
                                    </label>
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions p-t-30">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                            <button id="botonNC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-save__load accept" onclick="guardarFechaEnvio();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Documentos</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0 br-b">
                            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					           <div class="mdl-tabs__tab-bar">
					               <a href="#editarCorreo" class="mdl-tabs__tab is-active">Editar</a>
					               <a href="#eliminarCorreo" class="mdl-tabs__tab">Eliminar</a>
					           </div>
					           <div class="mdl-tabs__panel is-active" id="editarCorreo">
		                            <div class="row-fluid">
		                            	<div class="col-sm-12 mdl-input-group p-rl-16" style="margin-top: 16px; margin-bottom: 0">
		                            	    <div class="mdl-icon mdl-icon__button">
                                                <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFecEnvioEdit">				                            
                                                    <i class="mdi mdi-today"></i>
                	                            </button>
                                            </div>
		                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		                                        <input class="mdl-textfield__input" type="text" id="fecEnvioEdit"/>
		                                        <label class="mdl-textfield__label" for="fecEnvioEdit">Fecha de env&iacute;o</label>
		                                    </div>                                           
		                                </div>
		                                <div class="col-sm-12 mdl-input-group p-rl-16">
		                                	<div class="mdl-icon">
		                                        <i class="mdi mdi-contact_mail"></i>
		                                    </div>
		                                    <div class="mdl-select">
								               <select id="selectTipoEdit" name="selectTipoEdit" class="form-group pickerButn" data-live-search="true"></select>
		                                   </div>
							            </div>
							            <div class="col-sm-12 mdl-input-group p-rl-16 text-right" style="min-height: 1px;">
				                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
				                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarFechaEnvio();">Aceptar</button>
		                            	</div>
		                            </div>
	                            </div> 
	                            <div class="mdl-tabs__panel" id="eliminarCorreo">
	                               <div class="row-fluid">
    	                            	<div class="col-sm-12 mdl-input-group p-rl-16" style="min-height: 1px; margin-top: 16px">
    	                            		&#191;Deseas eliminar el correo&#63;
    		                            </div>		                            
    		                            <div class="col-sm-12 mdl-input-group p-rl-16" style="min-height: 1px;">
    	                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="replicarElim">
    	                                        <input type="checkbox" id="replicarElim" class="mdl-checkbox__input">
    	                                        <span class="mdl-checkbox__label" style="font-size: 14px; line-height: 20px;"><small>&#191;Deseas eliminar las proximas fechas&#63;</small></span>
    	                                    </label>
    	                                </div>
    		                            <div class="col-sm-12 mdl-input-group p-rl-16 text-right" style="min-height: 1px;">
							            	<button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cancelar</button>
				                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="eliminarFechaEnvio();">Aceptar</button>
    							        </div>
							        </div>    
	                            </div>
	                    	</div>
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
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/language/es-ES.js"></script>     
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/calendar.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jscorreos.js"></script>       
            
        <script type="text/javascript">            
            var datos = <?php echo ($events);?>;
            initCalendar(datos);
            init();
            var hoy = getFechaHoy_dd_mm_yyyy();
        	$("#fecEnvio").val(hoy);
        	$("#fecEnvio").parent().addClass('is-dirty');
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}           
        </script>
	</body>
</html>