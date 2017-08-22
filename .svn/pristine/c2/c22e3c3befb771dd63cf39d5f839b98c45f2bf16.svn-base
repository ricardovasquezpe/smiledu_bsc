<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
	    <title>Consultar Evaluaci&oacute;n | <?php echo NAME_MODULO_SPED?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SPED?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SPED?>" />
                
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>emoji-picker/lib/css/nanoscroller.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>emoji-picker/lib/css/emoji.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>imageGallery/css/blueimp-gallery.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>dropzone/dropzone-theme23ba.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED?>css/cons_eval.css"/>
        
	</head>

	<body onload="screenLoader(timeInit);">
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    	    <?php echo $menu;?>
        	<main class='mdl-layout__content is-visible'>
		          <section >
                        <div class="mdl-content-cards">
                            <div class="mdl-card">     
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text" id="titleTb">Evaluaciones</h2>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b">
                                    <div id="contTabIndicadores" class="form floating-label table_distance">
        						        <?php echo $tbEvas;?>
        						    </div>
                                </div>
                            </div>
                        </div>
                  </section>
    		</main>
    	</div>
    	
	    <div class="offcanvas"></div>
	    
	    <!-- MODAL VER EVIDENCIA -->
        <div class="modal fade" id="modalVerEvidencias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">     
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="myModalLabelTitle">Evidencias</h2>
    					</div>    	
    					<div class="mdl-card__supporting-text">
    					   <div class="row-fluid">
    					       <div id="contenidoImgRut"></div>  
    					   </div>
    					</div>				
    					<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>              
                </div>  
            </div>
        </div>
        
        <!-- MODAL CARGAR EVIDENCIAS -->
    	<div class="modal fade" id="modalCargarEvidencias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Subir evidencias</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<div class="row-fluid">
    							<div id="dropzone" class="dropzone"></div>
    						</div>
    					</div>
    					<div class="mdl-card__actions ">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnAddNewEvidencias" name="btnAddNewEvid">Subir</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
        
        <!-- MODAL VER MENSAJES -->
        <div class="modal fade" id="modalVerMensajes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">     
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombrePersMensaje"></h2>
    					</div>    	
    					<div class="mdl-card__supporting-text" id="modalVerMensajesBody">
                            <div class="nano has-scrollbar" id="scroll">
                                <div class="nano-content" id="scroll1" tabindex="0" style="right: -17px;">
                                    <div class="offcanvas-body" id="scrol2">
                                        <ul class="list-chats" id="ulMensajes"></ul>
                                    </div>
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions p-0 p-t-15">
    					   <div class="row-fluid">
    					       <div class="col-xs-11">
    					           <div class="mdl-textfield mdl-js-textfield">
    					               <textarea name="comentario" id="comentario" data-emojiable="true" class="mdl-textfield__input" type="text" rows= "3"></textarea>   					               
    					           </div>
    					       </div>
    					       <div class="col-xs-1 p-0 text-left">
    					           <button class="mdl-button mdl-js-button mdl-button--icon" onclick="guardarMsj();">
                                        <i class="mdi mdi-send"></i>
                                    </button>
    					       </div>    					       
    					   </div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" onclick="adjuntarEnChat();" data-toggle="tooltip" data-placement="left" data-original-title="adjuntar">
                                <i class="mdi mdi-attach_file"></i>
                            </button>
                            <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
                        </div>
                    </div>              
                </div>  
            </div>
        </div>
        
        <div class="modal fade" id="modalPrevioImgAdj" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">     
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Imagen adjuntada</h2>
                        </div>    	
                        <div class="mdl-card__supporting-text text-center">
                            <img id="fotoAdjuntar" src="" class="img-responsive" style="margin: auto">
                        </div>			
                        <div class="mdl-card__actions p-0 p-t-15">
                            <div class="row-fluid">
                                <div class="col-xs-11">
                                   <div class="mdl-textfield mdl-js-textfield">
                                       <textarea name="comentarioAdj" id="comentarioAdj" data-emojiable="true" class="mdl-textfield__input" type="text" rows= "3"></textarea>   					               
                                   </div>
                                </div>
                                <div class="col-xs-1 p-0 text-left">
                                   <button class="mdl-button mdl-js-button mdl-button--icon" onclick="guardarMsjAdj();">
                                        <i class="mdi mdi-send"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>              
                </div>  
            </div>
        </div>
                
        <input type="hidden" id="hidIdEval">
        <div id="blueimp-gallery" class="blueimp-gallery">
            <div class="slides"></div>
            <a class="prev"></a>
            <a class="next"></a>
            <a class="close">x</a>
            <a class="play-pause"></a>
            <ol class="indicator"></ol>
        </div>
	    
    	<form action="c_main/logout" name="logout" id="logout" method="post"></form>
    	<form action="C_cons_eval/getRubrica" name="formPdfRubrica" id="formPdfRubrica" method="post"></form>
    	
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
	    <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>wizard/js/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>dropzone/dropzone.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>nanoscroller/jquery.nanoscroller.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>imageGallery/js/blueimp-gallery.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>emoji-picker/lib/js/tether.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>emoji-picker/lib/js/config.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>emoji-picker/lib/js/util.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>emoji-picker/lib/js/jquery.emojiarea.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>emoji-picker/lib/js/emoji-picker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>canvasResize/binaryajax.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>canvasResize/exif.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>canvasResize/canvasResize.js"></script>        
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>    	
   		<script src="<?php echo RUTA_PUBLIC_SPED?>js/jsConsEval.js"></script>
        <script>
        marcarNodo("Consultarevaluaciones");
        initDropzone();
        tableEventsEvaluaciones();
        $(document).ready(function() {
        	$('#tbEvaluaciones').bootstrapTable({ });
        	initSearchTable();
        	$('.fixed-table-toolbar').addClass('mdl-card__menu');
        	window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: '../public/general/plugins/emoji-picker/lib/img',
                popupButtonClasses: 'fa fa-smile-o'
            });
            window.emojiPicker.discover();
        });
        $(document).ready(function(){
    	    $('[data-toggle="tooltip"]').tooltip();
        }); 
        </script>
	</body>
</html>