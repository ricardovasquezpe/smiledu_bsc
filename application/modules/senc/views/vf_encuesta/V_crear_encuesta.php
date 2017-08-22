<!DOCTYPE html>
<html lang="en">
    <head>     
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script> 
        <title>Nueva Encuesta | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/submenu.css">     
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/logic/crear_encuesta.css">

    </head>
    
    <body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
    		<main class='mdl-layout__content'>
                <section> 
                    <div class="mdl-content-cards">
                        <div class="row">
                            <div class="col-md-5 col-lg-4" >
                                <div class="mdl-card">  
                                    <div class="mdl-card__title p-b-0">
                                        <button id="infoAnonima" class="mdl-button mdl-js-button mdl-button--icon" data-toggle="tooltip" data-placement="right" data-original-title="Si no es an&oacute;nima tendr&aacute;n que ingresar al sistema para llenar la encuesta">
                                            <i class="mdi mdi-info"></i>
                                        </button>
                                        <h2 class="mdl-card__title-text">An&oacute;nima</h2>
                                    </div>  
                                    <div class="mdl-card__menu" id="contSwitch" style="display : <?php echo isset($displayAnonima) ? $displayAnonima : 'block';?>">
                                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switchAnonima">
                                            <input type="checkbox" id="switchAnonima" class="mdl-switch__input" <?php echo isset($checkedAnonima) ? $checkedAnonima : null;?> onclick="changeAnonimaEncuesta($(this).is(':checked'));">
                                            <span class="mdl-switch__label"></span>
                                        </label>
                                    </div>                                        
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div class="row-fluid">
                                            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <textarea class="mdl-textfield__input mdl-Letters" name="input" id="tituloEncuesta"  maxlength="100" placeholder="T&iacute;tulo" onblur="actualizarTitulo(this.value);"><?php echo isset($tituloEnc) ? $tituloEnc : null?></textarea>
                                                    <label class="cuenta-letras" for="input" id="labelEnc">0/100</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                                <div class="mdl-select">
                                                    <select id="selectTipoEncuesta" name="selectTipoEncuesta"
                										data-live-search="true" class="form-control pickerButn"
                										onchange="crearEncuestaInactiva(1)">
                										<option value="">Seleccione Tipo Encuesta</option>
                										<?php echo $tipo_encuesta?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="tipoEncuestadoMulti" style="display:none"> 
                                                <div class="mdl-select">               										
                                                    <select id="selectTipoEncuestado" name="selectTipoEncuestado" onchange="saveTipoEncuestado();"
            											data-live-search="true" class="form-control pickerButn" multiple>
            											<?php echo $comboTipoEnc;?>
                                                    </select>
                                                </div>
                                            </div>
                    					    <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-l-15" <?php if($this->session->userdata('id_encuesta_edit') == null){?> style="display:none"<?php }?> id="contNuevaCategoria">
                				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="nuevaCategoria" onkeyup="activarBtnAgregarCate('nuevaCategoria', 'newCate')">
                                                   <label class="mdl-textfield__label" for="nuevaCategoria">Nueva Categor&iacute;a</label>                                      
                                               </div>
                                               <div class="mdl-btn">
                        			               <button class="mdl-button mdl-js-button mdl-button--icon mdl-button--raised" onclick="asignaRegistraCategoria();" id="newCate" name="newCate" disabled>
                							            <i class="mdi mdi-add"></i>
                							       </button>
                        			           </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 p-0">
                                            <div id="contTbCategorias" class="form floating-label table_distance" style="display: none;">
            									<?php echo (isset($tbCategoria)) ? $tbCategoria : null ;?>
                            				</div>
                        				</div>
                                    </div>
                                </div>
                           </div>
                           <div class="col-md-7 col-lg-8">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Preguntas</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">                                  
                                        <div class="row-fluid">
                                            <div class="img-search" id="empty">
                                                <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                                <p><strong>Hey!</strong></p>
                                                <p>Prueba el selecciona una categor&iacute;a</p>
                                                <p>para poder tener preguntas.</p>
                                            </div>
                    					    <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-l-15" id="contNuevaPregunta" style="display: none;">
                				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="nuevaPregunta" onkeyup="activarBtnAgregarCate('nuevaPregunta', 'newPreg')">
                                                   <label class="mdl-textfield__label" for="nuevaPregunta">Nueva Pregunta</label> 
                                               </div>
                                               <div class="mdl-btn">
                        			               <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-button--raised" onclick="asignarRegistraPregunta();" id="newPreg" name="newPreg" disabled>
                							            <i class="mdi mdi-add"></i>
                							       </button>
                        			           </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 p-0">
                                            <div id="contTbPreguntas"></div>
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
    		
    	<div id="modalSeleccionarTipoPreg" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			     <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar opciones a la pregunta</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <div id="contRadioButtonPreg"></div>
							<div id="contInputsOpciones"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal" >Aceptar</button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalSeleccionarTipoEncuestado" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">   				
    				<div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Seleccione los tipos de encuestados</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
    					   <div id="contTbTipoEncuestados"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
        
        <div id="modalAsignaTipoEncuestadoPregunta" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
        			<div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asigna un tipo de encuestado</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <div class="col-sm-12" id="contTipoEnc"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalAdvertencia" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
        			<div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Est&aacute;s seguro de quitar la categor&iacute;a?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>
    					       Se desasignar&aacute;n todas las preguntas 
    					   </small>
    					</div>
    					<div class="mdl-card__actions">
    					    <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="buttonDeleteCate">Aceptar</a>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalAllPreguntas" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
        			<div class="mdl-card " >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Seleccione las preguntas</h2>
    					</div>
    					<div class="mdl-card__menu">
    					   <button class="mdl-button mdl-js-button mdl-button--icon" onclick="crearEncuestaInactiva(2);">
                               <i class="mdi mdi-save"></i>
                           </button>
    					</div>
    					<div class="mdl-card__supporting-text p-0" id="contTbAllPreguntas"></div>
    					<div class="mdl-card__actions p-t-10">
    					   <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" onclick="restoreArrayCatePreg();">Cancelar</a>
                           <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="crearEncuestaInactiva(2);">Aceptar</a>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div class="offcanvas"></div>
    	
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jscrearEncuesta.js"></script>
        
    	    	
    	<script type="text/javascript">
    		returnPage();
    		$(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
        	});
 	        $('#selectTipoEncuestado').selectpicker({noneSelectedText: 'Dirigido a...'});
    	    initCrearEncuesta(<?php echo $jsonArray;?>,<?php echo $idTipoEnc;?>,<?php echo $flg_tipoEnc;?>,<?php echo $arrayTipos;?>);
            $(document).ready(function() {
            	  var input = $("#tituloEncuesta");
            	  var label = $("#labelEnc");
            	  var maxVal = $("#tituloEncuesta").attr('maxlength');
            	  var inputLength = input.val().length;
            	  $("#labelEnc").html(inputLength + "/"+maxVal);
            	  input.keyup(function() {
            	    var inputLength = input.val().length;
            	    var counter = $("#counter");
            	    
            	    $("#labelEnc").html("");
            	    $("#labelEnc").html(inputLength + "/"+maxVal);
            	    
            	    if ( inputLength >= maxVal ) {
            	      label.css("background-color", "#F3493D");
            	      label.css("color", "#F3493D");
            	    } else {
            	      label.css("background-color", "#FF9200");
            	      label.css("color", "#FF9200");
            	    }
            	  });
            	}); 
    	</script>
    </body>
</html>