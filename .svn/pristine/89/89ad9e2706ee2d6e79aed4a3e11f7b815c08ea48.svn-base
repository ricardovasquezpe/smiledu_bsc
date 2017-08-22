<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Configuración | <?php echo NAME_MODULO_MATRICULA?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_MATRICULA?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >    
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css" >
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css" > 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css" >        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css" >       
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css" >
        
        <style type="text/css">
            .mdl-layout__tab-bar-container{
            	display: none
            }
            
            .dtp-header{
            	display: none !important;
            }
        </style>
        
    </head>
    <body onload="screenLoader(timeInit);">
        
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		<?php echo $menu ?>
            <main class='mdl-layout__content'>  
                <section class="mdl-layout__tab-panel is-active">
                    <div class="mdl-content-cards">
                        <div class="mdl-card c-1">
                            <div class="mdl-card__title">
                               <h2 class="mdl-card__title-text">Configuraci&oacute;n de traslados</h2>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <div class="row-fluid">
                                    <div class="col-sm-6 col-md-6 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" <?php echo isset($disabled) ? $disabled : null?>>
        								       <i class="mdi mdi-date_range"></i>
        							        </button>
			                            </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaInicio" name="fechaInicio" maxlength="5" value="<?php echo isset($fechaInicio) ? $fechaInicio : null?>" <?php echo isset($disabled) ? $disabled : null?>>        
                                            <label class="mdl-textfield__label" for="fechaInicio">Fecha inicio</label>                         
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
        							        <button class="mdl-button mdl-js-button mdl-button--icon">
        								       <i class="mdi mdi-date_range"></i>
        							        </button>
        							     </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaFin" name="fechaFin" maxlength="5" value="<?php echo isset($fechaFin) ? $fechaFin : null?>">        
                                            <label class="mdl-textfield__label" for="fechaFin">Fecha fin</label>                          
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <button id="botonCT" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="saveFechas()">Guardar</button>
                            </div>
                        </div>                             
                    </div>
                    
                    <div class="mdl-content-cards">
                        <div class="mdl-card c-1">
                            <div class="mdl-card__title">
                               <h2 class="mdl-card__title-text">Configuraci&oacute;n de matr&iacute;cula</h2>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <div class="row-fluid">
                                    <div class="col-sm-12 col-md-12 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" <?php echo isset($disabled) ? $disabled : null?>>
        								       <i class="mdi mdi-date_range"></i>
        							        </button>
			                            </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaInicioMatricula" name="fechaInicioMatricula" maxlength="5" value="<?php echo isset($fechaInicioMatricula) ? $fechaInicioMatricula : null?>" <?php echo isset($disabledMatricula) ? $disabledMatricula : null?>>        
                                            <label class="mdl-textfield__label" for="fechaInicioMatricula">Fecha L&iacute;mite</label>                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <button id="botonCM" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="saveFechaMatricula()">Guardar</button>
                            </div>
                        </div>                             
                    </div>
                    
                    <div class="mdl-content-cards">
                        <div class="mdl-card c-1">
                            <div class="mdl-card__title">
                               <h2 class="mdl-card__title-text">Configuraci&oacute;n de ratificaci&oacute;n</h2>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <div class="row-fluid">
                                    <div class="col-sm-12 col-md-12 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" >
        								       <i class="mdi mdi-date_range"></i>
        							        </button>
			                            </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaInicioRatificacion" name="fechaInicioRatificacion" maxlength="5" value="<?php echo isset($fechaInicioRatificacion) ? $fechaInicioRatificacion : null?>" <?php echo isset($disabled) ? $disabled : null?>>        
                                            <label class="mdl-textfield__label" for="fechaInicioRatificacion">Fecha inicio</label>                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <button id="botonCR" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="saveFechaRatificacion()">Guardar</button>
                            </div>
                        </div>                             
                    </div>
                </section>               
            </main>
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
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>jquery-mask/jquery.mask.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jsconfiguracion.js"></script>
        
        <script type="text/javascript">
            init();
        </script>    
    </body>
</html>