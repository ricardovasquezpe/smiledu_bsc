<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <title>Permisos | <?php echo NAME_MODULO_RRHH?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_RRHH?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_RRHH?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_RRHH;?>css/submenu.css">
        
	</head>

	<body>
	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>
                <section >
                    <div class="row">
                        <div class="col-sm-5 col-sm-offset-1">   
                            <div class="mdl-card">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text">Sistemas</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">  
                            <div class="mdl-card">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text">Roles</h2>
                                </div>
                            </div>  
                        </div>
                    </div> 
                </section>                
            </main>	
        </div>
          	     
  	    <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
  	        <li class="mfb-component__wrap mfb-only-btn">
                <button class="mfb-component__button--main is-up" id="main_button" data-toggle="modal" data-target="#modalFiltrarColaboradores" data-mfb-label="Filtrar rol">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
             </li>
        </ul>
        
        <div class="modal fade" id="modalFiltrarColaboradores" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 p-0 m-b-15">
					               <select class="form-control">
            			                <option value="">Seleccione rol</option>
            			           </select>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
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
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script> 
                
        <script>
        	returnPage();
        </script>
	</body>
</html>