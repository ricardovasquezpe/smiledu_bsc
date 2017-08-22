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
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PUBLIC_BSC?>css/shideEffect.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC;?>css/indica_rapido.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
       <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
	       <?php echo $menu ?>
           <main class='mdl-layout__content'>  
                <section>
                    <div class="mdl-content-cards"> 
                        <?php if((_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR  || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) 
                                  && (_getSesion('es_director_direc_promot_ver_cate') != null) ){?>
            				<ol class="breadcrumb">
                				<li><a href="<?php echo base_url()?>bsc/c_linea_estrategica" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_lineaEstrat');?>"><?php echo _getSesion('nombre_lineaEstrat');?></a></li>
                				<li><a href="<?php echo base_url()?>bsc/cf_indicador/c_categoria" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_objetivo');?>"><?php echo _getSesion('nombre_objetivo');?></a></li>
                				<li class="active"><a data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_categoria');?>"><?php echo _getSesion('nombre_categoria');?></a></li>
                			</ol>
                		<?php }?>
            		
                        <div class="mdl-filter__color">	
            			    <div id="divDorado"      onclick="clickFiltro(3)"  class="mdl-filter__options">Copa </div>
            			    <div id="divVerde"       onclick="clickFiltro(0)"  class="mdl-filter__options">Verde </div>
            			    <div id="divAmarillo"    onclick="clickFiltro(1)"  class="mdl-filter__options">Amarrillo </div>
            			    <div id="divRojo"        onclick="clickFiltro(2)"  class="mdl-filter__options">Rojo </div>
            			    <div id="divChangeView"  onclick="goToTablaIndi()" class="mdl-filter__options">Cambiar de vista </div>
              			</div>   
          			</div>                             
                    <div class="mdl-content-cards">     
                        <?php echo $indicadores_vistaRapida;?>
                    </div>
                    <div class="img-search" id="img_not_found_ind" style="display: none">
                        <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_not_found.png">
                        <p><strong>Ups!</strong></p>
                        <p>Tu filtro no ha sido encontrado</p>
                    </div>
                </section>
           </main>
       </div>	
               
        <div class="modal fade backModal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
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
                                        <input class="mdl-textfield__input" type="text" id="valorAmarillo" name="valorAmarillo">        
                                        <label class="mdl-textfield__label" for="valorAmarillo">Zona de riesgo</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="valorVerde" name="valorVerde" disabled readonly="readonly">        
                                        <label class="mdl-textfield__label" for="valorVerde">Meta</label>                            
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnEIR" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarValorZonaRiesgoIndicador()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> 
        
        <div class="modal fade backModal" id="modalViewResponsable" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombreReponsableModal"></h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 text-center">
					               <img id="img_repsonsable">
					           </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="btnllamada"><i class="mdi mdi-phone"></i></button>
                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="btnperfil"><i class="mdi mdi-account_circle"></i></button>
                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="btnemail"><i class="mdi mdi-email"></i></button>
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
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsindicadorRapido.js"></script>
    	
    	<script>
        	<?php if( (_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) 
        	         && (_getSesion('es_director_direc_promot_ver_cate') != null) ) { ?>
     	        returnPage();
            <?php } ?>
        	
    		init();
    		initIndicadores(<?php echo $countIndicadores?>);
    		setDivHeight();
    		$('.highcharts-data-labels g rect').css('display','none');
        	$('.highcharts-series-group circle').css('fill','#959595');
        	$(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
    	</script>
	</body>
</html>