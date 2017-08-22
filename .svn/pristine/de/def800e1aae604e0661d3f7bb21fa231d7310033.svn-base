<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>        
		<title>Contactos | <?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">    

	</head>
	
	<body>        
            <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
                <header class="mdl-layout__header">
                    <div class="mdl-layout__header-row">
                        <div class="mdl-layout-title">
                            <img alt="Logo" src="<?php echo RUTA_IMG?>iconsSistem/icon_admision_blanco.png"style="margin-right: 10px; position: absolute; top: -10px; left: -50px;">
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium" style="float: left;padding-right: 15px;border-right: 1px solid;">Admisi&oacute;n</h2>
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light" style="float: right;padding-left: 15px;"><?php echo $nomEvento?></h2>
                        </div>
                    </div>
                </header>
    	        <main class="mdl-layout__content is-visible">
    	          <section>
        		      <div class="row-fluid">
        		      
        		          <?php if($opc == FLG_CONFIRMACION_AUXILIAR){?>
        		          <div id="confirmar_evento" class="mdl-card card-confirmar" >
    							<div class="mdl-card__title">
                                    <div class="img-search p-b-0" id="cont_search_empty1">
                                        <img src="<?php echo RUTA_IMG?>smiledu_faces/empty_confirmar_evento.png">
                                    </div>
                                </div>
                                <div class="mdl-card__supporting-text text-center">
                                    <p>Hola, <?php echo $nomPersona?>:</p>
                                    <p><strong>Gracias por confirmar tu asistencia.</strong></p>
                                    <p>¿Deseas agregar alguna observaci&oacute;n?</p>
                                    <div class="mdl-textfield mdl-js-textfield">
                                        <textarea class="mdl-textfield__input" type="text" rows= "3" id="observacionResp" name="observacionResp" onchange="guardarObservacion()"></textarea>
                                        <label class="mdl-textfield__label" for="observacionResp">Observaci&oacute;n</label>
                                    </div>
                                    <p>No olvides coordinar con las personas involucradas</p>
                                    <!--p>Agendar a GoogleCalendar</p>
                                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch-1">
                                        <input type="checkbox" id="switch-1" class="mdl-switch__input" checked>
                                        <span class="mdl-switch__label"></span>
                                    </label-->
                                </div>	
    							<div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised btn_modal_card" onclick="window.close();">Cerrar</button>
                                </div>
    					  </div>
    					  
    					  <?php }else{?>
    					  <div id="rechazar_evento" class="mdl-card card-confirmar">
    							<div class="mdl-card__title">
                                    <div class="img-search p-b-0" id="cont_search_empty1">
                                        <img src="<?php echo RUTA_IMG?>smiledu_faces/empty_rechazar_evento.png">
                                    </div>
                                </div>
                                <div class="mdl-card__supporting-text text-center">
                                    <p><strong>&#161;Ups! Rechazaste tu asistencia</strong></p>
                                    <p>¿Deseas agregar alguna observaci&oacute;n?</p>
                                    <div class="mdl-textfield mdl-js-textfield">
                                        <textarea class="mdl-textfield__input" type="text" rows= "3" id="observacionResp" name="observacionResp" onchange="guardarObservacion()"></textarea>
                                        <label class="mdl-textfield__label" for="observacionResp">Observaci&oacute;n</label>
                                    </div>
                                    <p>No olvides coordinar con las personas involucradas.</p>
                                </div>	
    							<div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised btn_modal_card" onclick="window.close();">Cerrar</button>
                                </div>
    					  </div>
    					  <?php }?>
    					  
    					  <p class="m-0 m-t-10 m-b-10 text-center"><a style="color:#757575;" class="link-smiledu" href="http://www.smiledu.pe" target="_blank"><strong>Smiledu</strong>&reg;</a> Created by <a class="link-smiledu" href="http://www.softhy.pe/" target="_blank" style="text-decoration:none; color:#757575;">Softhy</a></strong>.</p>
    			     </div>
    			  </section>
    		    </main>
    		</div>
    	
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>clientjs-master/dist/client.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsconfirmarevento.js"></script>
    </body>
</html>