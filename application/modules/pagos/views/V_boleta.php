<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
	    <title>Boleta | <?php echo NAME_MODULO_PAGOS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
		<link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        <style type="text/css">
        .colorBoleta{
        	background-color: bisque;
        }
        </style>        
	</head>
	
	<body onload="screenLoader(timeInit);">
		<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
		
    		<?php echo $menu ?>
    		
    		<main class='mdl-layout__content'>
    		    <section class="mdl-layout__tab-panel is-active" id="tab-detalle">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                        	<div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text" id="titleTb">Correlativos</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" id="tableCorrelativos">
                                <?php echo $tb_correlativos?>
                            </div>
                        </div>
                    </div>
                </section>
        		<section class="mdl-layout__tab-panel" id="tab-compromiso">
                    <div class="mdl-content-cards" style="display:none;">
                        <div class="mdl-card">
                           <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text" id="titleTb">Boletas</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" id="tableCompromisos">  
                                 
                            </div>
                            <div class="mdl-card__menu" id="generar">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                    <i class="mdi mdi-more_vert"></i>
                                </button> 
                        	</div>
                        </div>
                    </div>
                    <div class="img-search" id="cont_img_search_alum">
        	            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                        <p>Primero debemos filtrar para</p>
                        <p>visualizar tus ingresos.</p>
        			</div>
                </section>
        		<section class="mdl-layout__tab-panel " id="tab-boleta">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                        	<div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Boletas</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" id="tableBoleta">
                                <?php echo $tb_bol_imprimir;?>
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="listarBoleta();">
                                    <i class="mdi mdi-refresh"></i>
                                </button> 
                        	</div>
                            <div class="mdl-assign" id="cabeConfirmar">
                            	<div class="text">0 item seleccionado</div>
                                <div class="option" id="botonImprimir">
                                <?php echo $btnImprimir;?>
                                </div>
                           </div>
                        </div>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="tab-correlativos">
                    <div class="mdl-content-cards">
                        <div class="col-sm-5 col-sm-offset-3">
                            <div class="mdl-card">
                            	<div class="mdl-card__title ">
                                    <h2 class="mdl-card__title-text" id="titleTb">Historico</h2>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b" id="contCorrelativosHistoricos">
                                    <?php echo $correlativos?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>	
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" style="display:none" id="generarBoletas">
                <button class="mfb-component__button--main" id="main_button">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>
        
		<div class="modal fade" id="modalGenerarBoletas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="mdl-card">
						<div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Generar Boletas</h2>
						</div>
						<div class="mdl-card__supporting-text p-r-0 p-l-0">
                            <div class="row-fluid">
    							<div class="col-sm-11 m-l-10 m-b-15">
    							    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    									<input class="mdl-textfield__input" disabled type="text" id="primeraBoleta">
    									<label class="mdl-textfield__label" for="primeraBoleta">Primera Boleta</label>
    								</div>
    							</div>
    							<div class="col-sm-11 m-l-10 m-b-15">
    								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    									<input class="mdl-textfield__input" disabled type="text" id="ultimaBoleta">
    									<label class="mdl-textfield__label" for="ultimaBoleta">Ultima Boleta</label>
    								</div>
    							</div>
    							<div class="col-sm-11 m-l-10 m-b-15 mdl-input-group">
                                    <div class="mdl-icon mdl-icon__button">
                                    	<button class="mdl-button mdl-js-button mdl-button--icon" id="iconFechaBoletas">
			                                 <i class="mdi mdi-today"></i>
		                                </button>       
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" id="fecha_boletas" name="fecha_boletas"  maxlength="10">
                                       <label class="mdl-textfield__label" for="fecha_boletas">Fecha de Emisi&oacute;n</label>
                                    </div>                                        
                                </div>
                            </div>
						</div>
						<div class="mdl-card__actions">
							<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
							<button id="botonGB" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="generarBoletas();">Generar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="modalCronogramaCuota" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="mdl-card mdl-card-fab">
						<div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Busca tu cuota</h2>
						</div>
						<div class="mdl-card__supporting-text p-r-0 p-l-0">
						    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
						        <div class="mdl-tabs__tab-bar">
					                <a href="#pagadas" class="mdl-tabs__tab is-active p-l-10 p-r-10" onclick="changeFlgBoletas('0')">Pagadas</a>
					                <a href="#noPagadas" class="mdl-tabs__tab p-l-10 p-r-10" onclick="changeFlgBoletas('1')">No Pagadas</a>
					            </div>
					            <div class="mdl-tabs__panel is-active" id="pagadas">
					                <div class="row-fluid">
    					                <div class="col-sm-12 mdl-input-group">
                                            <div class="mdl-icon">
                                                <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFecInicioBol">				                            
                                                    <i class="mdi mdi-today"></i>
        			                            </button>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="fecInicioBol" name="fecInicioBol" maxlength="10"/>
                                                <label class="mdl-textfield__label" for="fecEnvio">Fecha de env&iacute;o</label>
                                                <span class="mdl-textfield__error"></span>
                                            </div>        
                                        </div>
                                        <div class="col-sm-12 mdl-input-group">
                                            <div class="mdl-icon">
                                                <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFecFinBol">				                            
                                                    <i class="mdi mdi-today"></i>
        			                            </button>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="fecFinBol" name="fecFinBol" maxlength="10"/>
                                                <label class="mdl-textfield__label" for="fecEnvio">Fecha de env&iacute;o</label>
                                                <span class="mdl-textfield__error"></span>
                                            </div>        
                                        </div>
                                    </div>
					            </div>
					            <div class="mdl-tabs__panel" id="noPagadas">
					                <div class="row-fluid">
            							<div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					               <select id="selectCronograma" name="selectCronograma" class="form-group pickerButn" onchange="getCuotaByCronograma();" data-live-search="true">
        					                   <option value="">Selec. Cronograma</option>
        					                   <?php echo $optCronograma; ?>
                                           </select>
        					           </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					               <select id="selectCuota" name="selectCuota" class="form-group pickerButn" data-live-search="true">
        					                   <option value="">Selec. Cuota</option>
                                           </select>
        					           </div>
        					           <!--  <div class="col-sm-12 mdl-input-group text-center" id="radiosRegistrar" style="min-height: 0;">
        					               <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-r-10" for="radioPagadas">
                                               <input type="radio" id="radioPagadas" class="mdl-radio__button" name="radioBoletas" value="0">
                                               <span class="mdl-radio__label">Pagados</span>
                                           </label>
                                           <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-r-10" for="radioNoPagads">
                                               <input type="radio" id="radioNoPagads" class="mdl-radio__button" name="radioBoletas" value="1">
                                               <span class="mdl-radio__label">No pagados</span>
                                           </label>
        					           </div>-->
                                    </div>
					            </div>
                            </div>
						</div>
						<div class="mdl-card__actions">
							<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
							<button id="botonGC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getCompromisosByCuota()">Aceptar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>nanoscroller/jquery.nanoscroller.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>        
    	<script src="<?php echo RUTA_PLUGINS?>hammer/velocity.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/hammer.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>  	
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsboleta.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jshammer__boleta.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        
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
			initSearchTableNew();
			$('#iconFechaBoletas').click(function(){
                initButtonCalendarMaxDate('fecha_boletas');
            });
			$('#iconFecInicioBol').click(function(){
                initButtonCalendarMaxDate('fecInicioBol');
            });
			$('#iconFecFinBol').click(function(){
                initButtonCalendarMaxDate('fecFinBol');
            });
			initMaskInputs('fecha_boletas','fecInicioBol','fecFinBol');
        </script>
	</body>
</html>