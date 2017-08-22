
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title><?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC;?>css/submenu.css">       
        
        <style>            
            @media ( max-width : 768px ) {
            	header span.mdl-layout-title a{
            		display: block !important;
            	}
        	}
            .pace .pace-progress{
            	background-color: transparent !important;
            }        	
        </style>
    </head>
    
    <body onload="screenLoader(timeInit);">
    	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">        		
            <?php echo $menu ?>
        		
    	   <main class='mdl-layout__content'>
                <section> 
                    <div class="mdl-content-cards">
                         <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
        	               <div class="mdl-card">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text" id="titleTb">Encuestas Pendientes</h2>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b">
                                    <?php echo $tbEnc?>
                                </div>
                            </div>
                         </div>   
                    </div>   
	           </section>	
    		</main>
    		<form action="c_main/updateDispEncuestasLibres" style="display: none"><button type="submit">UpdateDispositivos</button></form>
    		<form action="c_main/updateCantParti" style="display: none"><button type="submit">UpdateCantidadParticipantes</button></form>
    	    <form action="c_main/updateServicioDocentes" style="display: none"><button type="submit">ACEPTAR SERVICIO DOCENTES</button></form>
    	    <form action="c_main/updateServicioEstudiantes" style="display: none"><button type="submit">ACEPTAR SERVICIO ESTU</button></form>
    	    <form action="c_main/updateServicioPadres" style="display: none"><button type="submit">ACEPTAR SERVICIO PADRES</button></form>
    		<form action="c_main/updateCountValues" style="display: none"><button type="submit">AceptarUpdateCount</button></form>
    		<form action="c_main/getArrNotIn" style="display: none"><button type="submit">AceptarNin</button></form>
    		<form action="c_main/cambiarDescripcionesIdsMongo" style="display: none"><button type="submit">Aceptar</button></form>
    	</div>
    	
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>       
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsmain.js"></script>
    	
    	<script>
        	imageMainHeader("icon_encuestas");
            $('#tb_encuestas').bootstrapTable({ });
        </script>
    </body>
</html>