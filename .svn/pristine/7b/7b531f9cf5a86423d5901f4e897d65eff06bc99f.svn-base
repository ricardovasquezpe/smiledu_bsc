<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>          
		<title><?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />

        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION;?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/mdl-card-style.css">
                
        <style type="text/css">
            @media ( max-width : 750px ) {
            	header span.mdl-layout-title a{
            		display: block;
            	}
        	}            
        </style>
	</head>
	
	<body>
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
            <main class='mdl-layout__content is-visible'>
                <section> 
                    <div class="mdl-content-cards" id="cont_evento";>
                        <div id="cont_search_eventos"></div>
                        <div class="img-search" id="cont_imagen_magic">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_empty_state.png">
                            <p><strong>Hola!</strong></p>
                            <p>Prueba el buscador</p>
                            <p>m&aacute;gico.</p>           
                        </div>
                        <div class="img-search" id="cont_search_not_found" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_not_found.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No se encontraron</p>
                            <p>resultados.</p>
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored-text" onclick="reintentarBusqueda();">Reintentar</button>
                        </div>                       
                    </div>
                </section>
            </main>
        </div>
        
         <div class="modal fade" id="modalDetalleColaboradores" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Detalle de Colaboradores</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row p-0 m-0 text-center">
                                <div class="bootstrap-table">
            						<div class="table-responsive">
                                        <div id="cont_colaboradores_asistieron"></div>
                                        <div class="img-search" id="cont_teacher_empty8" style="display: none;">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                            <p><strong>&#161;Ups!</strong></p>
                                            <p>A&uacute;n no hay colaboradores</p>
                                            <p>contactados.</p>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         
          <div class="modal fade" id="modalDetalleInvitados" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Detalle de Invitados</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row p-0 m-0 text-center">
                                <div class="bootstrap-table">
            						<div class="table-responsive">
                                        <div id="cont_invitados_asistieron"></div>
                                        <div class="img-search" id="cont_teacher_empty8" style="display: none;">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                            <p><strong>&#161;Ups!</strong></p>
                                            <p>A&uacute;n no hay Invitados</p>
                                            <p>contactados.</p>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        
          <div class="modal fade" id="modalEventosEnlazados" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">           
           <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Eventos Enlazados</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">    				
					       <div class="row p-0 m-0 text-center">
					           <div id="cont_eventos_enlazados"></div>
					           <div class="img-search" id="cont_teacher_empty8" >
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>A&uacute;n no hay eventos</p>
                                    <p>enlazados.</p>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>      
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsmain.js"></script> 
        
        <script>
            magicIcon();
        	imageMainHeader("icon_admision");
        </script>
	</body>
</html>