<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <script type="text/javascript">
             var timeInit = performance.now();  
        </script>  
        <title> <?php echo ( _getSesion(PAGOS_ROL_SESS) == ID_ROL_SECRETARIA) ? 'Mis Incidencias' : 'Incidencias' ?> | <?php echo NAME_MODULO_PAGOS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />        
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/css/calendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/pagos.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
    		<main class='mdl-layout__content m-t-20'>
                <div class="p-r-15 p-l-15" >
                    <div class="mdl-content-cards" id="tableIncidencias">  
                    	<?php echo isset($tbIncidencias) ? $tbIncidencias : null;?>                              
                    </div>
                </div>
                <div class="p-r-15 p-l-15" >
                    <div class="mdl-content-cards">  
                    	<?php echo isset($tab)  ? $tab: null;?>                              
                    </div>
                </div>
            </main>
        </div>

        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/language/es-ES.js"></script>     
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/calendar.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsincidencia.js"></script>
        
        <script type="text/javascript">
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
        	initButtonLoad('btnMF' );
        	returnPage();
	        $(document).ready(function(){
	    	    $('[data-toggle="tooltip"]').tooltip(); 
	    	    $('[data-toggle="popover"]').popover();
	    	    $('<button>').attr({
	    			'id'   	    : 'iconColaboradores',
	    			'class'	    : 'mdl-button mdl-js-button mdl-button--icon',
	    			'onclick'   : 'abrirModalPaquete(\'Opciones de tabla\')'
	    		}).appendTo('#tableColaboradores .fixed-table-toolbar .columns-right');

	    		$('<i>').attr({
	    			'class'	: 'mdi mdi-more_vert'
	    		}).appendTo('#iconColaboradores');
	        });
        </script>
	</body>
</html>