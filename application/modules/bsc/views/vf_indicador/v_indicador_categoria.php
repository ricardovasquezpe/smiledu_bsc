<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Categor&iacute;as | <?php echo NAME_MODULO_BSC?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_BSC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BSC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
    	<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC;?>css/submenu.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >        
    		<?php echo $menu ?>    		          
            <main class='mdl-layout__content'>  
                 <section >
                     <div class=" col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1">
                        <div class="mdl-content-cards">
                            <div class="mdl-card ">
                               <div class="mdl-card__title">
                                   <h2 class="mdl-card__title-text" id="titleTb">Indicadores</h2>
                               </div>
                               <div class="mdl-card__supporting-text br-b p-0" id="contTabCategoria">
                                   <?php echo $tableIndicadorCategoria?>
                               </div>
                            </div>
                        </div>
                     </div>    
                </section>
             </main>         
        </div>	
        
        <div class="modal fade backModal" id="modalCategorias" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Categorías</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">    		
					       <div id="contTbCategorias" class="form floating-label table_distance"></div>
    					</div>
    					<div class="mdl-card__actions">
    					   <button  class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" >Cerrar</button>
    					   <button  class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal" onclick="capturarCategoriaIndicador();">Guardar</button>
    					</div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="offcanvas"></div>
            
          
		<form action="c_main/logout" name="logout" id="logout" method="post"></form>
		
		<script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>   		
    	<script src="<?php echo RUTA_PLUGINS;?>bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>  
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script> 
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsindicador_categoria.js"></script>
        
        <script type="text/javascript">
        initIndicadorCategoria();
        </script>
	</body>
</html>