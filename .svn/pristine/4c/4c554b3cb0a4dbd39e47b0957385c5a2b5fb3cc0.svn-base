<!DOCTYPE html>
<html lang="en">
    <head>  
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script> 
        <title>Lista | <?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
        
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />
	</head>
	
	<style>
        /* Scrool bar */
        ::-webkit-scrollbar {
        	width: 3px;
        	height: 3px;
        }
        
        ::-webkit-scrollbar-track {
        	background: rgba(0, 0, 0, 0.1);
        }
        
        ::-webkit-scrollbar-thumb {
        	background: rgba(0, 0, 0, 0.2);
        }    	   
	</style>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <div class="mdl-layout__drawer turnos">
            <audio id="sonido" style="display: none" src="<?php echo base_url()?>public/general/sound/audio_espera.wav" preload="auto" controls>
            </audio>
            <div class="turno">
                <div ><h1 style="color: #757575;"><i class="mdi mdi-record_voice_over" style="font-size:19px; padding-right:10px"></i><?php echo $descripcion?></h1></div>
                <h1 class="m-b-5">A entrevista...</h1>
                <div id="cont_turno"><?php echo $suTurno?></div>
            </div>
            <div class="turno_perdido">
                <h1 class="m-b-5">Perdi&oacute; turno...</h1>
                <div id="cont_turno_perdido"><?php echo $perdioTurno?></div>
            </div>
            <div class="espera_turno">
                <h1>En espera...</h1>
                <div class="img-search" id="cont_search_not_filter" style="display: none;">
                    <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                    <p><strong>&#161;Ups!</strong></p>
                    <p>A&uacute;n no hay nadie listo</p>
                    <p>para la entrevista.</p>
                </div>
                <div id="cont_espera_turno"><?php echo $enEspera?></div>
            </div>
        </div>
        <main id="video" class="mdl-layout__content">
            <div class="page-content">
                <div class="videos_relax text-center">
                    <video controls src="<?php echo base_url()?>public/general/videos/bromas.mp4"></video>
                </div>
            </div>
        </main>
    </div>

        <script src="<?php echo RUTA_PLUGINS?>socket.io/socket.io.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>artyom/artyom.min.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsespera.js"></script>
        <script type="text/javascript">
            var nodeServer = '<?php echo $server_node;?>';
            init();
        </script>
  </body>
</html>
