<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Vista Previa | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>highcharts/">        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/effects-schoowl.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/animate.css">        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/logic/encuesta.css">
        
        <style type="text/css">
            header .mdl-layout__tab-bar-container{
            	display: none ;
            }
            
            .modal-backdrop.fade{
            	opacity: 0.5;
            	z-index: 4            	
            }
            
            .mdl-menu__item i.material-icons{
            	position: relative;
            	top: 7.5px;
            	margin-right: 5px
            }
            
            @media ( max-width: 640px ){
            	a#aperturar{
                    display: none;
                }
                li#aperturar{
                    display: block;
                }
            }
            
            @media ( min-width: 641px ){
            	a#aperturar{
                    display: block;
                }
                li#aperturar{
                    display: none;
                }
            }
        </style>
    </head>
    <body>
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>        		
            <header class='mdl-layout__header'>
                <div class='mdl-layout__header-row'>	    
                    <span class='mdl-layout-title'>
                        <img alt="Logo" src="<?php echo RUTA_IMG?>iconsSistem/icon_encuestas_blanco.png" style="position: absolute; top: 2.5px; left: -50px;">
                        <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium"><?php echo $titulo?></h2>
                        <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light"><?php echo $tipoEncuesta?></h2>
                    </span>
                    <div class='mdl-layout-spacer'></div>
                    <nav class="mdl-navigation">
                    <?php if($estado == ENCUESTA_BLOQUEADA || $estado == ENCUESTA_CREADA){?>
                		<a id="aperturar" onclick="openModalChangeEstado('<?php echo $idEnc?>')" class="mdl-color--light-green mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent " data-upgraded=",MaterialButton">
            			    <i class="mdi mdi-check" style="margin-left: -7px"></i>Aperturar
            			</a>
            		<?php }?>
        		        <button id="options" class="mdl-button mdl-js-button mdl-button--icon">
                          <i class="mdi mdi-more_vert"></i>
                        </button>
                        
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="options">                        
                        <?php if($estado == ENCUESTA_BLOQUEADA || $estado == ENCUESTA_CREADA){?>
                              <li class="mdl-menu__item mdl-color-text--grey-600 " id="aperturar" onclick="openModalChangeEstado('<?php echo $idEnc?>')">
                			    <i class="mdi mdi-check mdl-color-text--grey-600 "></i>Aperturar
                              </li>
                          <?php }?>
                          
                          <li class="mdl-menu__item mdl-color-text--grey-600 " id="imprimir" onclick="imprimirEncuesta('<?php echo $idEnc?>')">
            			    <i class="mdi mdi-print mdl-color-text--grey-600 "></i>Imprimir
                          </li>
                          
                        </ul>  
            		</nav>
                </div>
                <div class="mdl-layout__tab-bar" id="categorias">                                                             
                    <?php echo isset($categoriaHTML) ? $categoriaHTML : null;?>
                </div>  
            </header>
                
            <main class='mdl-layout__content is-visible' >      
		         <section class="mdl-layout__tab-panel is-active">
    		         <div class="row">
    		             <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 p-0 card-width">  
                             <div id="modal-init" style="display: <?php echo $display?>" >
                                <div class="cards">
                                    <input id="dribbble" name="card-control" type="radio" checked="checked" class="card-control" />
                                    <div class="card card--init mdl-shadow--2dp p-20 ">
                                        <div class="row-fluid">
                                            <div class="img-search">
                                                <img class="" src="<?php echo RUTA_IMG;?>smiledu_faces/smiledu_sunglasses.png">
                                                <p><strong>Hola, soy 'Steven'</strong><p>
                                                <p>No te preocupes, cuidar&eacute; que tu identidad se mantenga completamente <strong>AN&Oacute;NIMA</strong> en esta encuesta.</p>
                                                <p>&#191;Eres ...?</p>
                                            </div>                                            
                                            <?php if($tipoEnc == TIPO_ENCUESTA_LIBRE){?>
                                                <?php echo isset($arraTipoEncuestadoHTML) ? $arraTipoEncuestadoHTML : null;?>
                                                <div class="col-xs-12">
                                                    <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="avanzar();" style="display: none">EMPEZAR</label>
                                                </div>                                          
                                            <?php }else {?>
                                                <div class="col-xs-12 p-0">
                                                    <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="avanzar();" style="display: block">EMPEZAR</label>
                                                </div> 
                                            <?php }?>   						
                                        </div>
                                    </div>
                                
                                    <!--input id="vk" name="card-control" type="radio" class="card-control" /-->
                                    <!--div class="main-nav">
                                    <label for="dribbble" class="btn active">TOUR DE AYUDA</label>
                                    <?php //if($tipoEnc == TIPO_ENCUESTA_LIBRE){?>
                                        <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--indigo mdl-color-text--white" onclick="avanzar();" style="display: none">EMPEZARR</label>
                                    <?php //}else {?>
                                        <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--indigo mdl-color-text--white" onclick="avanzar();" style="display: block">EMPEZAR</label>
                                    <?php //}?> 
                                    </div-->
                                        <p class="softhy">&copy; 2016 <a class="link-smiledu" href="#" target="_blank"><strong>Smiledu.</strong></a> Todos los derechos reservados</p>
                                </div>   
                            </div>      
                         </div>                                                 
                     </div>                                 
                </section>
                  
                <div id="preguntas">
                   <?php echo isset($preguntasHTML) ? $preguntasHTML : null;?>
                </div>   
                   
                <div class="position-progress" id="barraProgreso" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 2; display: none">
                    <div class="progress progress-striped active m-0">
        				<div class="progress-bar progress-bar-warning" id="progressBar">
        					<div class="question-count" id="divAvance">

        					</div>
        				</div>
                    </div>
                </div>    
            </main> 
    	</div>
            
        <?php if($estado == ENCUESTA_BLOQUEADA || $estado == ENCUESTA_CREADA){?>
        	<div id="modalAperturarCerrarEncuesta" class="modal fade in" tabindex="-1">
        		<div class="modal-dialog modal-sm">
        			<div class="modal-content">
            			<div class="mdl-card" style="opacity: 1">
                            <div class="mdl-card__title">
        						<h2 class="mdl-card__title-text ">&#191;Deseas cambiar el estado a la encuesta?</h2>
        					</div>
        					<div class="mdl-card__supporting-text">
        					   <small id="texto">
        					       <?php echo $msj_especial?>
        					   </small>
        					   <br>
        					   <small id="textoInfo"></small>
        					</div>
        					<div class="mdl-card__actions">
                                <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                                <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="buttonEstado" onclick="cambiarEstadoEncuesta('<?php echo $idEnc?>')" >Aceptar</a>
                            </div>
                        </div>                    
        			</div>
        		</div>
        	</div>
        	
    	<?php }?>
    	
    	<script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>        
    	<script src="<?php echo RUTA_PLUGINS?>clientjs-master/dist/client.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script> 
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsencuestanew.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsvistaPrevia.js"></script>
    	
    	<script type="text/javascript">
        	   $('#encuesta-init').css('display','block');
        	   //initMagicSuggestAulas();
        	   //$.material.init();
               	// Inicializar sonido evento
               	var finishSound = createsoundbite('<?php echo RUTA_PUBLIC_SENC?>css/sound/sound_finish_survey.mp3');
               	
               	//initMagicSuggest();
               	$(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });

               	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
					$('.selectBootstrap').selectpicker('mobile');
				} else {
					$('.selectBootstrap').selectpicker();
				}

				var ci = 0;
                $('.list-icon').click(function() {                	
                	if(ci != 0){
                		$('.list-icon').each(function() {
                            $(this).removeClass('selected');
                        });
                    }
                	$(this).addClass('selected');
                    ci = 1;	
                });

               	var datosClient = [];
               	var client = new ClientJS();

               	var ratio = window.devicePixelRatio || 1;

               	var is_touch_device = 'ontouchstart' in document.documentElement;
               	var touch_status = (is_touch_device) ? 'SI' : 'NO';
               	
               	datosClient.push({
        			browser   : client.getBrowser()+' '+client.getBrowserVersion(),
        			sist_oper : client.getOS()+' '+client.getOSVersion(),
        			device    : client.getDevice(),
        			device_tipo : client.getDeviceType(),
        			device_vendor : client.getDeviceVendor(),
        			cpu : client.getCPU(),
        			screen_print : client.getScreenPrint(),
        			current_resolution : client.getCurrentResolution(),
        			available_resolution : client.getAvailableResolution(),
        			resolution_device :  screen.width * ratio+'x'+screen.height * ratio,
                    touch : touch_status,
                    js_heap_size_limit : readable(performance.memory.jsHeapSizeLimit, 2), // will give you the JS heap size
                    used_js_heap_size  : readable(performance.memory.usedJSHeapSize, 2),
                    nucleos : navigator.hardwareConcurrency
        		});
     		 console.log(datosClient);
        </script>
    </body>
</html>