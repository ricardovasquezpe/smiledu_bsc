<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Administraci&oacute;n | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC;?>css/submenu.css">
        <style>
            .pace .pace-progress{
            	background-color: transparent !important;
            }
            
            .mdl-filter{
            	overflow:initial;
            }
        </style>
        
        
    </head>
    <body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
        	<main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel is-active" id="encuestas">
                    <div class="mdl-content-cards">
                        <div class="mdl-card" >
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="titleTb">Consulta</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b">
                                <div id="contTabEncuestas" class="form floating-label table_distance">
                                    <?php echo $tablaEncuestas?>
                                </div>
                            </div>
                            <div class="mdl-card__menu" style="right: 100px">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"style="top:5px" onclick="refreshTableEncuestas();" data-refresh="true">
                                    <i class="mdi mdi-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
    		</main>
    	</div>

    	<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    		<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
    			<button  class="mfb-component__button--main" onclick="redirectCrearEncuesta();" data-mfb-label="Nueva encuesta"> 
        			<i class="mfb-component__main-icon--resting mdi mdi-edit"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-edit"></i>
				</button>
        	</li>
    	</ul>
    	
    	<div class="offcanvas"></div>
    	
    	<div id="modalAperturarCerrarEncuesta" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
        			<div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&iquest;Deseas cambiar el estado a la encuesta?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small id="texto"></small>
    					   <br>
    					   <small id="textoInfo"></small>
    					</div>
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="buttonEstado" onclick="cambiarEstadoEncuesta()">Aceptar</a>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
	
    	<div id="modalDescargarExcel" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    			    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Deseas descargar la plantilla?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
				           <small>Al descargar el excel observar&aacute;s las preguntas de esta encuesta y podr&aacute;s llenarlas manualmente..</small>  
    					   <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label m-t-20">
                                       <input class="mdl-textfield__input" type="text" name="cantEncuestas" id="cantEncuestas">
                                       <label class="mdl-textfield__label" for="cantEncuestas">Ingrese la cantidad de encuestas</label>                                        
                                   </div>
                               </div>
                           </div>                      
    					</div>
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="descargarExcelByEncuesta()">Aceptar</a>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalSubirExcel" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Subir Excel con las Encuestas llenas</h2>
    					</div>
    					<div class="mdl-card__supporting-text">		
    					   <small>Al subir el excel podras insertar todas las encuestas llenadas a nuestro sistema.</small>		  
    					   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label m-t-10">
                               <input type="file" name="excelFile" id="excelFile"/>                                      
                           </div>                        
    					</div>      
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnImport" name="btnImport" onclick="subirExcel()">Aceptar</a>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>	
    	
    	<div id="modalBloquearEncuesta" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    			     <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Est&aacute;s seguro de bloquear la encuesta?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>Al bloquear la encuesta se borrar&aacute;n todos los datos ya guardados y podr&aacute;s volver a editarla si tienes alg&uacute;n error que corregir; pero no se ver&aacute; hasta que la desbloquees.</small>     
    					</div>
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="bloquearEncuesta();">Aceptar</a>
                        </div>
                    </div>    				
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalStandByEncuesta" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    			     <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Est&aacute;s seguro de detener la encuesta?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>Al detener la encuesta nadie la podra llenar hasta que la vuelvas a aperturar.</small>     
    					</div>
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="setStandByToEnc();">Aceptar</a>
                        </div>
                    </div>    				
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalGeneraUrl" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
        			<div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">URL Generada</h2>
    					</div>
    					<div class="mdl-card__supporting-text">					
    					   <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="divUrl">
                                        <input class="mdl-textfield__input" type="text" id="urlGenerada">
                                        <label class="mdl-textfield__label" for="tituloEncuesta">Link</label>
                                    </div>
                                </div>
    					   </div>
    					</div>
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="copiarUrl();">Copiar</a>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
        
        <div id="modalCompartidos" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-lg">
    			<div class="modal-content">
        			<div class="mdl-card">
                        <div class="mdl-card__title">
    					</div>
    					<div class="mdl-card__supporting-text">					
    					   <div class="row-fluid">
        					    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
        					        <div class="mdl-tabs__tab-bar">
        					            <a href="#buscar" class="mdl-tabs__tab is-active">Buscar Usuarios</a>
                                        <a href="#usuarios" class="mdl-tabs__tab">Ya tienen acceso</a>
        					        </div>
        					        <div class="mdl-tabs__panel is-active" id="buscar">
                                        <div class="row-fluid">
                                            <div class="mdl-filter">
                                                <div class="mdl-content-cards">
                                                    <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" maxlength="40" name="busqUsuario" id="busqUsuario" onchange="buscarUsuarioCompartir($(this));">
                                                            <label class="mdl-textfield__label" for="busqUsuario">Buscar usuario</label>
                                                        </div>
                                                        <div class="mdl-btn">
                                                            <button class="mdl-button mdl-js-button mdl-button--icon" data-upgraded=",MaterialButton" onclick="buscarUsuarioCompartir($('#busqUsuario'));">
                                                                <i class="mdi mdi-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="contBusqPers"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-tabs__panel" id="usuarios">
                                        <div class="row-fluid">
                                            <div class="mdl-filter">
                                                <div class="mdl-content-cards">
                                                    <div id="contCompart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    					   </div>
    					</div>
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnCompartEncu">ACEPTAR</a>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
        <form action="C_excel_plantilla" method="post" id="formExcel">
            <input type="hidden" name="id_encu" id="id_encu">
            <input type="hidden" name="cantEncuestados" id="cantEncuestados">
        </form>
        
    	<div class="offcanvas"></div>
    	
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsconsultarencuesta.js"></script>
   		
    	<script>
    	    init();
    	</script>
    	<script type="text/javascript">
            $('.fixed-table-toolbar').addClass('mdl-card__menu');

            var flgFirstLoad = null;
            modalCompartirtabActionsChange();
    		
            function subirExcel() {
            	var inputFileExcel = document.getElementById("excelFile");
            	if(!inputFileExcel) {
            	    return;
            	}
            	Pace.restart();
            	Pace.track(function() {
           		    var file = inputFileExcel.files[0];
                  	var formData = new FormData();
                  	formData.append('itFileXLS', file);
                  	formData.append('idEncuestaGlobal', idEncuestaGlobal);
                  	formData.append('client_info', datosClient);
                  	idEncuestaGlobal = null;
              	    $.ajax({
              	        data: formData,
              	        url: "c_upload_excel/subirExcelEncuesta",
              	        cache: false,
              	        contentType: false,
              	        processData: false,
              	        type: 'POST'
              	  	})
              	  	.done(function(data) {
              	  		data = JSON.parse(data);
              			if(data.error_excel == 1) {
              				$('#excelFile').val("");
              			} else {
              				if(data.error == 0) {
              					msj('success', data.msj);
                  			    $('#excelFile').val("");

                    			$('#tb_encuestas').bootstrapTable('updateCell', {
                					rowIndex   : $indexRowGlobal,
                					fieldName  : 'cant_encuestados',
                					fieldValue : data.newCantEncus
                				});
                    			componentHandler.upgradeAllRegistered();
                    			$indexRowGlobal = null;
              				} else {
              					msj('error', data.msj);
              					$('#excelFile').val("");
              				}
              			}
              			modal('modalSubirExcel');
              	  	});
            	});
        	}
    	</script>    	
    </body>
</html>