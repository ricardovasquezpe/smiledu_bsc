<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
		<title><?php echo NAME_MODULO_BSC?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_BSC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BSC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
    	<!-- link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/-->
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC;?>css/submenu.css">
				
	</head>

	<body onload="screenLoader(timeInit);">    
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
            <?php echo $menu?>
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">
                        <div class="row">   
                            <div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1" text-center">                     
                                <div class="col-sm-12">				
                				    <div class="mdl-card ">
                						<div class="mdl-card__title">
                							<h2 class="mdl-card__title-text">Grupo Educativo</h2>
                						</div>                    						
            						    <div id="contInputGrupoEduc">
                                            <?php echo $inputGrupoEduc?>
        						        </div>
                					</div>
            					</div>
                				<div id="infoBasica">
                    				<div class="col-sm-6 m-b-10">
                    				    <div class="mdl-card">
                    						<div class="mdl-card__title">
                    							<h2 class="mdl-card__title-text">Configuraci&oacute;n de Valores</h2>
                    						</div>  
                    						<div class="mdl-card__supporting-text p-0 br-b">                  						
                    						    <div id="contTbLineas" class="table-responsive">
                    						        <?php echo $tablaLineasEstrategicas?>
                						        </div>
            						        </div>
            						        <div class="mdl-card__menu">
            						          <button id="btn-save-valores_linea" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-icon__save" onclick="grabarValoresLinea()">
            						              <i class="mdi mdi-save"></i>
            						          </button>
            						        </div>
                    					</div>
                    				</div>
                    				<div class="col-sm-6 m-b-10">
                    				    <div class="mdl-card">
                    						<div class="mdl-card__title">
                    							<h2 class="mdl-card__title-text">Configuraci&oacute;n de Valores</h2>
                    						</div>  
                    						<div class="mdl-card__supporting-text p-0 br-b">                  						
                    						    <div id="contTbObjetivos" class="table-responsive">
                						        </div>
                						        <div class="img-search" id="cont_select_empty_objetivos">
                                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                                    <p><strong>Hey!</strong></p>
                                                    <p>Seleccione una L&iacute;nea Estrat&eacute;gica</p>
                                                    <p>para ver mas detalles.</p>                         
                                                </div>
            						        </div>
            						        <div class="mdl-card__menu">
            						          <button id="btn-save-valores_objetivos" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-icon__save" onclick="grabarValoresObjetivo()" style="display: none">
            						              <i class="mdi mdi-save"></i>
            						          </button>
            						        </div>
                    					</div>
                    				</div>                				        
                                </div>
                            </div>
                        </div>
                    </div>
                </section>    
            </main>
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
    	<script src="<?php echo RUTA_PLUGINS;?>bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script> 
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts-more.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/modules/exporting.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>  
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsconfigValorGraf.js"></script>
    	
        
        <script type="text/javascript">
        	initConfigValoresGraficos();
        	marcarNodo("ConfigurarValoresdeGraficos");
        	getObjetivos();
        </script>
	
	
	</body>
</html>