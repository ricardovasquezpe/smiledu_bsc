<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Calendario | <?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>"/>
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/css/calendar.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        		
	</head>
    <body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
    		<main class='mdl-layout__content'>
    		    <section>
    		        <div class="mdl-content-cards">
                        <div class="mdl-card mdl-calendar">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="fechaCalendar"></h2>
                            </div>
                            <div class="mdl-card__supporting-text br-b p-r-5 p-l-5">
                                <div id="calendar" class="m-b-20"></div>
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
                <button class="mfb-component__button--main" id="main_button" onclick="abrirModalAddEvento();" data-mfb-label="Nuevo visita">
                    <i class="mfb-component__main-icon--resting mdi mdi-event"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-event"></i>
                </button>
            </li>
        </ul>
        
        <div class="offcanvas"></div>
          
		<div class="modal fade in" id="modalNewDiaNoLab" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Nuevo d&iacute;a no Laborable</h2>
                        </div>
                        <div class="mdl-card__supporting-text"> 
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" name="descripcion" id="descripcion" maxlength="300">
                                        <label class="mdl-textfield__label" for="descripcion">Descripci&oacute;n</label>
                                    </div>
                                </div>
                            </div>                               
                        </div>
                        <div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CANCELAR</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="addNewDiaNoLaborable();">GUARDAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="modal fade in" id="modalEditEvent" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="myModalLabelTitle">Editar D&iacute;a</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" name="descripcionEdit" id="descripcionEdit" maxlength="300">
                                        <label class="mdl-textfield__label" for="descripcionEdit">Descripci&oacute;n</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                		<label for="chkNoLabo" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                                            <input type="checkbox" id="chkNoLabo" value="optNoLabo" class="mdl-checkbox__input">
                                            <span class="mdl-checkbox__label">&#191;No Laborable?</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" >CERRAR</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarDiaNoLaborable();">EDITAR</button>
                        </div>
                    </div>
                </div>
            </div>
		</div>

		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jquery-ui/js/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>jsmantenimiento/jsadmision.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.js" charset="UTF-8"></script>
   		<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js" charset="UTF-8"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS;?>pace/pace.min.js"></script>
   		
   		<script src="<?php echo RUTA_PLUGINS;?>bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bootstrap-calendar-master/js/language/es-ES.js"></script>     
        <script src="<?php echo RUTA_PLUGINS;?>bootstrap-calendar-master/js/calendar.js"></script>
        <script src="<?php echo RUTA_JS?>jsmantenimiento/jscalendario.js" charset="UTF-8"></script>
   		
		<script type="text/javascript">        	
		$(document).ready(function() {
        	var datos = <?php echo json_encode($calendarData, JSON_NUMERIC_CHECK); ?>;
        	initCalendar(datos);
    	});
		$(function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        });
    		(function($) {
            $.fn.clickToggle = function(func1, func2) {
                var funcs = [func1, func2];
                this.data('toggleclicked', 0);
                this.click(function() {
                    var data = $(this).data();
                    var tc = data.toggleclicked;
                    $.proxy(funcs[tc], this)();
                    data.toggleclicked = (tc + 1) % 2;
                });
                return this;
            };
        }(jQuery));
    
        initSearchTable();
        
        var lastScrollTop = 0;
        $(window).scroll(function(event){
           var st = $(this).scrollTop();
           if (st > lastScrollTop){//OCULTAR
        	   $("#menu").fadeOut();
           } else {
        	   if(st + $(window).height() < $(document).height()) {//MOSTRAR
        		   $("#menu").fadeIn();
        	       
    	    	}
           }
           lastScrollTop = st;
        });
		</script>
	</body>
</html>