<!DOCTYPE html>
<html lang="en">
    <head>  
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>    
        <title>Mis cursos | <?php echo NAME_MODULO_NOTAS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>"/>   
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >		
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_NOTAS?>css/submenu.css">
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>  		
    		<?php echo $menu ?>
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">
                        <?php echo isset($card_cursos) ? $card_cursos : '<div class="img-search"><img src="'.RUTA_IMG.'smiledu_faces/smiledu_feedback.png"><p><strong>&#161;Ups!</strong></p><p>A&uacute;n no tienes cursos.</p><p>Pide que te asignen cursos.</p></div>'; ?>
                    </div>
                </section>
            </main>	
        </div>        
        
        <form action="c_cursos/go_detalleCurso" id="formGoToCurso" method="post"></form>
          	       
		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jquery-ui/js/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        
        <script>
            function goDetalleCurso(btn) {
                var data = btn.data('id_main');
            	$('<input type="hidden" name="id_main" id="id_main"/>').val(data).appendTo('#formGoToCurso');
                $('#formGoToCurso').submit();
            }
        </script>
	</body>
</html>