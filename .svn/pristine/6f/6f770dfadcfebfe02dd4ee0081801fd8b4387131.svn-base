<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>R&uacute;brica | <?php echo NAME_MODULO_SPED;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SPED; ?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SPED; ?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED?>css/submenu.css">
              
    </head>
    
    <body onload="screenLoader(timeInit);">        
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    	
        	<?php echo $menu?>
        	
        	<main class='mdl-layout__content'>
                <section >
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="titleTb">Criterios</h2>
                            </div>
            				<div class="mdl-card__supporting-text p-0 br-b">
                                <div id="contTabConsRubricas" class="form floating-label table_distance">
                                    <?php echo $tbConsRub;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
    		</main>
    	</div>
    	
        <ul id="menu" class="mfb-component--br mfb-zoomin">
            <li class="mfb-component__wrap mfb-only-btn">
                <button class="mfb-component__button--main" onclick="mostrarFicha();" data-mfb-label="Nueva R&uacute;brica">
                    <i class="mfb-component__main-icon--resting mdi mdi-edit"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-edit"></i>
                </button>
            </li>
    	</ul>

    	<div class="offcanvas"></div>

    	<div class="modal fade backModal" id="modalAsignarFicha" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Crear R&uacute;brica</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <input type="hidden" id="inputHide" name="inputHide" class="m-0 p-0">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label is-dirty">
                               <input class="mdl-textfield__input" type="text" name="descripcion" id="descripcion" maxlength="50">
                               <label class="mdl-textfield__label" for="descripcion">Descripci&oacute;n</label>
                            </div>
    					</div>
    					<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarRubricaNew();">Guardar</button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>

    	<form action="c_main/logout" name="logout" id="logout" method="post"></form>
    	
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_SPED;?>js/jsrubrica.js"></script>
        
    	<script type="text/javascript">
              marcarNodo("Perfil");
              initRubrica();
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
            </script>
    </body>
</html>