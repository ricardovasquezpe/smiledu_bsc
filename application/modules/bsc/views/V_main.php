<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo NAME_MODULO_BSC?></title>
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
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">

        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/submenu.css">
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PUBLIC_BSC?>css/shideEffect.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/main.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC;?>css/indica_rapido.css">
            
        <style type="text/css">
            @media ( max-width : 768px ) {
            	header span.mdl-layout-title a{
            		display: block !important;
            	}            	
        	}
        </style>
        
	</head>

	<body>
	
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		<?php echo $menu ?>      
            <main class='mdl-layout__content is-visible'>
                <section>
                    <div id="cont_indi" class="text-center"></div>
                    <div class="mdl-content-cards"> 
                        <div class="img-search" id="cont_search">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_empty_state.png">
                            <p><strong>Hey!</strong></p>
                            <p>Prueba el buscador</p>
                            <p>m&aacute;gico.</p>
                        </div>
                        <div class="img-search" id="cont_search_not_found" style="display: none;">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_not_found.png">
                            <p><strong>&#161;Ups!</strong></p>
                            <p>No se encontraon</p>
                            <p>resultados.</p>
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored-text" onclick="reintentarBusqueda();">Reintentar</button>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    	     		
	    <div class="offcanvas"></div>
	    
    	<form action="c_main/logout" name="logout" id="logout" method="post"></form>
        
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
        
        <div class="modal fade backModal" id="modalSearchVoice" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Búsqueda por voz</h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 text-center">
					              <img src="<?php echo RUTA_IMG?>voiceGif.gif" onclick="startRecognition()" style="cursor: pointer;" class="img-responsive" id="img_voice">
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
        
        <div class="modal fade backModal" id="modalShowResponsables" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Responsables</h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row">
					           <div class="col-sm-12 text-center" id="tableResponsables">
                                    
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
        
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mark/jquery.highlight.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>artyom/artyom.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsmain.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsutilsbsc.js"></script>
        
        <script>
           imageMainHeader("icon_balance");
           magicIcon();
           var settings = {
       		    continuous:true,
       		    onResult:function(text){
       		        setearInput("searchMagic", text);
          		    buscarIndicador();
       		    }
       		};
       		var UserDictation = artyom.newDictation(settings);
       		function startRecognition(){
           		$("#img_voice").attr("onclick", "stopRecognition()");
       		    UserDictation.start();
       		}
       		function stopRecognition(){
                $("#img_voice").attr("onclick", "startRecognition()");
       		    UserDictation.stop();
       		}
        </script>        
	</body>
</html>