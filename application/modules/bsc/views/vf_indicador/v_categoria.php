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
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/submenu.css">
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PUBLIC_BSC?>css/shideEffect.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/categoria.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
	       <?php echo $menu ?>
	       
           <main class='mdl-layout__content'>  
                <section >
                    <div class="mdl-content-cards"> 
                        <?php if(_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_SUBDIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD){?>
            				<ol class="breadcrumb">
                				<li ><a href="<?php echo base_url()?>bsc/c_linea_estrategica" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_lineaEstrat');?>"><?php echo _getSesion('nombre_lineaEstrat');?></a></li>
                				<li class="active"><a data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_objetivo');?>"><?php echo _getSesion('nombre_objetivo');?></a></li>
                			</ol>
                		<?php }?>
            		</div>
            		<div class="mdl-content-cards"> 
                        <?php echo $categorias;?>
                    </div>
                </section>
           </main>
	   </div>				
    	
    	<div class="modal fade backModal" id="modalEditarValorAmarillo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar valores</h2>
    					</div> 
					    <div class="mdl-card__supporting-text">				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="contInputValor">
                                        <input class="mdl-textfield__input" type="text" id="valorAmarillo" name="valorAmarillo" maxlength="2">        
                                        <label class="mdl-textfield__label" for="valorAmarillo">Zona de riesgo</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="valorVerde" name="valorVerde" maxlength="3">        
                                        <label class="mdl-textfield__label" for="valorVerde">Meta</label>                            
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button  id="btnEVZRC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarValorZonaRiesgoCategoria()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jscategoria.js"></script>
    		
    	<script>
            <?php if(_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR  || _getSesion(BSC_ROL_SESS) == ID_ROL_SUBDIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) { ?>
                 returnPage();
    	    <?php } ?>
    	
    		initCategorias(<?php echo $countCategorias?>);
    		setDivHeight();
    		$('.highcharts-data-labels g rect').css('display','none');
        	$('.highcharts-series-group circle').css('fill','#959595');
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
        	//initEditValorAmarillo("c_indicador_rapido");
    	</script>
	</body>
</html>