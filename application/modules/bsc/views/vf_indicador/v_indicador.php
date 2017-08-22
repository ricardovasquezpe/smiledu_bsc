<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Indicadores | <?php echo NAME_MODULO_BSC?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_BSC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BSC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <!--link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/--> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/submenu.css">
        
        <style type="text/css">
            .fixed-table-loading{
                display: none;	           
            }
            
            .dropdown-menu{
            	padding-left: 12px;
            }
            
            .page-number{
            	display: none !important
            }
        </style>
		
	</head>

	<body onload="screenLoader(timeInit);">
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
	       <?php echo $menu ?>
           <main class='mdl-layout__content'>  
                <section>
                    <div class="mdl-content-cards">
                        <?php if( (_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) 
                                  && (_getSesion('es_director_direc_promot_ver_cate') != null) ) { ?>
                            <ol class="breadcrumb">
                				<li><a href="<?php echo base_url()?>bsc/c_linea_estrategica"><?php echo _getSesion('nombre_lineaEstrat');?></a></li>
                				<li><a href="<?php echo base_url()?>bsc/cf_indicador/c_categoria" data-toggle="tooltip" data-placement="bottom" data-original-title="Categor&iacute;a"><?php echo _getSesion('nombre_objetivo');?></a></li>
                				<li class="active"><?php echo _getSesion('nombre_categoria');?></li>
                			</ol>
            			<?php } ?>            		
                        <div class="mdl-filter__color">	
            			    <div id="divDorado"   class="mdl-filter__options mdl-filter__none">Copa </div>
            			    <div id="divVerde"    class="mdl-filter__options mdl-filter__none">Verde </div>
            			    <div id="divAmarillo" class="mdl-filter__options mdl-filter__none">Amarrillo </div>
            			    <div id="divRojo"     class="mdl-filter__options mdl-filter__none">Rojo </div>
            			    <a id="divChangeView" class="mdl-filter__options" href="c_indicador_rapido">Cambiar de vista </a>
              			</div>   
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="titleTb">Indicadores</h2>
                            </div>
                            <div class="mdl-card__supporting-text br-b p-0">
                                <?php echo $tbIndicadores;?>
                            </div>                                
                        </div> 
        			</div>    			
                </section>
            </main>
       </div>
            
	   <div class="offcanvas"></div>
 		 
 		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>cookies/jquery.cookie.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsindicador.js"></script>
        
    	<script>
 		    <?php if((_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) 
 		          && (_getSesion('es_director_direc_promot_ver_cate') != null))  { ?>
            	returnPage();
        	<?php } ?>
    		init();
            $(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip(); 
            });
    		function showTableModal(){
    			html2canvas($(".fixed-table-container"), {
    		        onrendered: function(canvas) {
    		            // canvas is the final rendered <canvas> element
    		            var myImage = canvas.toDataURL("image/png");
    		            $("#imgTabla").attr("src", myImage);
    		            abrirCerrarModal("modalImgTabla");
    		        }
    		    });
    		}   
    	</script>
	</body>
</html>