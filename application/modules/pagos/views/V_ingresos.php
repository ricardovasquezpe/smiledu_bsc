<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head> 
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>         
        <title>Ingresos | <?php echo NAME_MODULO_PAGOS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >		
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/ingresos.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>    		
    		
    		<main class='mdl-layout__content'>
                <div class="mdl-filter">
        		   <div class="p-r-15 p-l-15">
        		      <div class="mdl-content-cards mdl-content__overflow">     		
    		             <?php echo isset($parientes) ? $parientes : null;?>
    		          </div>   
    		       </div>
    		    </div>
    		    <div class="p-r-15 p-l-15">
                    <div class="mdl-content-cards">          
    		          <?php echo isset($tab) ? $tab : null;?>
    		        </div>
                </div>    		        
    		</main>
    		<div id="state_empty" style="display:none">
                <div class="col-xs-12 p-0 m-0 text-center">
                   <div class="row-fluid" id="cardsAlumnos">
                 	   <div class="img-search" style="display:none">
                           <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                           <p>No se encontraron familiares</p>
                       </div>
                   </div>
                </div>
            </div>
    		
        </div>
        
        <div class="modal fade" id="modalAnularCompromiso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas anular el compromiso?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <small id="infoAnular"></small>
                        </div>
                        <div class="col-sm-12 mdl-input-group mdl-input-group__only">
			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label ">
			                    <textarea class="mdl-textfield__input" type="text" id="observacionAnular" maxlength="210" rows="5" style="color: #757575;"></textarea>
                                <label class="mdl-textfield__label" for="observacionAnular">Observaciones</label>
                                <span class="mdl-textfield__limit" for="observacionAnular" data-limit="200"></span>
                            </div>
			            </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonAC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="anularCompromisoByPersona();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalGenerarBoleta" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas generar boleta?</h2>
    					</div>
					    <div class="mdl-card__supporting-text m-b-20 p-t-20">    				
                            <small>Se generar&aacute; una boleta para el compromiso.</small>
    					</div>    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                            <button id="botonGB" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised download" onclick="generarBoletaByCompromiso();">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleConfirmar">Pagar 2 cuotas</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
                            <strong><small id="montoCobrar"></small></strong>
                            <strong><small id="montoDesc"></small></strong>
                            <div class="col-sm-12 p-0 p-t-20 p-b-10">
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkVisa">
                                    <input type="checkbox" id="checkVisa" class="mdl-checkbox__input">
                                    <span class="mdl-checkbox__label m-l-10">Pago con Tarjeta</span>
                                </label>
                            </div>
                            <div class="col-sm-12 p-0 p-b-10" id="checkAdelantoCont">
                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkAdelanto">
                                    <input type="checkbox" class="mdl-checkbox__input" id="checkAdelanto" onclick="hideShowInputAdelanto($(this));">
                                    <span class="mdl-checkbox__label m-l-10">Pago a cuenta</span>
                                </label>
                            </div>
                            <div class="col-sm-12 p-0" id="montoAdelantoCont" style="display: none;">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="monto_adelanto">
                                    <label class="mdl-textfield__label" for="monto_adelanto">Monto adelanto</label>
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                            <button id="botonRP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="goToAjax();">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        
        <div class="modal fade" id="modalVisualizarDocumentos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Documentos</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <div class="row p-0 text-center" id="contentDocs"> 
                            </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAgregarCompromiso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nuevo Compromiso</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <div class="row-fluid">
                                <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectConcepto" name="selectConcepto" class="pickerButn" onchange="getMontoReferenciaByConcepto();" data-live-search="true">
    					                   <option value="">Selec. Concepto</option>
    					                   <?php echo $optConceptos;?>
                                       </select>
                                   </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only p-0">
					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="monto" name="monto">
                                        <label class="mdl-textfield__label" for="monto">Monto(S/)</label>
                                    </div>
					            </div> 
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                            <button id="botonRC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="registrarCompromiso();">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAnularBoleta" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Eliminar boleta</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <small id="infoAnularDoc"></small>
                        </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonAB" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAnularCompromisoTotal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas anular el compromiso?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <small>El compromiso se anulara completamente.</small>
                        </div>
                        <div class="col-sm-12 mdl-input-group mdl-input-group__only">
			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label ">
			                    <textarea class="mdl-textfield__input" type="text" id="observacionAnularTotal" maxlength="210" rows="5" style="color: #757575;"></textarea>
                                <label class="mdl-textfield__label" for="observacionAnularTotal">Observaciones</label>
                                <span class="mdl-textfield__limit" for="observacionAnularTotal" data-limit="200"></span>
                            </div>
			            </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonACT" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="anularCompromisoTotal();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <img id="logo_avantgard_none"
    		src="<?php echo RUTA_IMG?>logos_colegio/avantgardLogo.png"
    		style="display: none;">
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>nanoscroller/jquery.nanoscroller.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.bootstrap3.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsingresos.js"></script>
        
        <script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/FileSaver.js/FileSaver.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Blob.js/Blob.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Blob.js/BlobBuilder.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Deflate/deflate.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Deflate/adler32cs.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/canvg/rgbcolor.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/canvg/StackBlur.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/canvg/canvg.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.addimage.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.cell.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.from_html.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.split_text_to_size.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.standard_fonts_metrics.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jquery.print.js"></script>
        <script type="text/javascript">         
            init();         
//          createReciboByCompromisos(null);          
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}       	
            returnPage();
            var promociones = <?php echo isset($promociones) ? $promociones : null;?>;
        	var currentTabl = <?php echo isset($initVal) ? $initVal : null;?>;
        	var currentPers = <?php echo isset($currentPerson) ? $currentPerson : null;?>;
        	var totalCompromisos = <?php echo isset($totalComp) ? $totalComp : 0;?>;
        	var idTable = 'tb_compromisos'+String(<?php echo isset($initVal) ? $initVal : null;?>);
        	tableEventsUpgradeMdlComponentsMDL(String(idTable));
        	init();
        	$(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
	</body>
</html>