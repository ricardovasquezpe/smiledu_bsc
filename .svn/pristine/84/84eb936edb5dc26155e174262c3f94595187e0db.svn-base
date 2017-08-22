 <?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>            
        <title>Egresos | <?php echo NAME_MODULO_PAGOS?></title>
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
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
		<link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">     
	</head>

	<body onload="screenLoader(timeInit);">
	     
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>    		
    		<main class='mdl-layout__content'>
    		    <section>
                    <div class="mdl-content-cards">                 
                        <div class="mdl-card">
                            <div class="mdl-card__title" >
                                <h2 class="mdl-card__title-text">Lista de Egresos</h2>
                            </div>
                            <div class="mdl-card__title">
                                <img class="img-circle m-r-10" width="35" height="35" alt="Colaboradores" src="<?php echo $fotoPers;?>">
                                <div style="color: #757575;">
                                    <h2 class="pago puntual m-0" style="font-size: 18px;line-height:18px;"><?php echo $nombres;?></h2>
                                    <small><?php echo $rol;?></small>
                                </div> 
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b">
                                <div class="" id="contTbEgresos" >
                                    <?php echo $tbEgresos;?>
                                </div>
                            </div>
                            <div class="mdl-card__menu">
                                <?php if(_getSesion(PAGOS_ROL_SESS) != ID_ROL_DOCENTE && _getSesion(PAGOS_ROL_SESS) != ID_ROL_RESP_COBRANZAS){?>
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="#modalAddEgreso">
                                        <i class="mdi mdi-edit"></i>
                                    </button>
                                <?php }?>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="" data-paquete-text="Egresos">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
    		</main>	
        </div>        
        
        <?php if(_getSesion(PAGOS_ROL_SESS) != ID_ROL_DOCENTE && _getSesion(PAGOS_ROL_SESS) != ID_ROL_RESP_COBRANZAS){?>
            <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
                <li class="mfb-component__wrap mfb-only-btn" id="cronograma_pago_fg">
                    <button class="mfb-component__button--main " id="main_button" data-toggle="modal" data-target="#modalAgregarConcepto" data-mfb-label="Nuevo Egreso">
                        <i class="mfb-component__main-icon--resting mdi mdi-attach_money" ></i>
                        <i class="mfb-component__main-icon--active  mdi mdi-attach_money" ></i>
                    </button>
                </li>
            </ul>
        <?php }?>
        
        <div class="modal fade" id="modalAddEgreso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Generar Concepto</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectConceptoEgreso" name="selectConceptoEgreso" class="pickerButn" onchange="getMontoReferenciaByConcepto();" data-live-search="true">
    					                   <option value="">Selec. Concepto</option>
    					                   <?php echo $optConceptos;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="montoEgreso" name="montoEgreso" maxlength="6">
                                        <label class="mdl-textfield__label" for="montoEgreso">Ingresar Monto (S/.)</label>
                                    </div>
					            </div> 
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    			                	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label ">
        			                    <textarea class="mdl-textfield__input" type="text" id="observacion" maxlength="210" rows="5"></textarea>
                                        <label class="mdl-textfield__label" for="observacion">Observaciones</label>
                                        <span class="mdl-textfield__limit" for="observacion" data-limit="200"></span>
                                    </div>
			                    </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                            <button id="registrarEgreso" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-save__load accept" onclick="registrarEgreso();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAnularEgreso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas anular el egreso?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="row">       
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only" style="min-height: 1px; margin: 0">				
                                  <small>Se anular&aacute; el egreso que registraste.</small>
                               </div>
    				           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        			               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label ">
        			                   <textarea class="mdl-textfield__input" type="text" id="observacionAnular" maxlength="210" rows="5"></textarea>
                                       <label class="mdl-textfield__label" for="observacionAnular">Observaciones</label>
                                       <span class="mdl-textfield__limit" for="observacionAnular" data-limit="200"></span>
                                   </div>
    			               </div>
                            </div>
    			        </div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                            <button id="botonAE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="anularEgresoByPersona();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalVisualizarDocumentos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Documentos</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <div class="row p-0 text-center" id="contentDocsEgreso"> 
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
       	      
       	<div class="modal fade" id="modalAgregarConcepto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nuevo egreso</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="desc_concepto" maxlength="100">
                                       <label class="mdl-textfield__label" for="desc_concepto">Nuevo concepto</label>
                                    </div>
    				           </div>
    				           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="monto_concepto">
                                       <label class="mdl-textfield__label" for="monto_concepto">Monto(S/)</label>
                                    </div>
    				           </div>
    				           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <textarea class="mdl-textfield__input" type="text" id="observacionNueva" maxlength="210" rows="5"></textarea>
                                       <label class="mdl-textfield__label" for="observacionNueva">Observaciones</label>
                                       <span class="mdl-textfield__limit" for="observacionNueva" data-limit="200"></span>
                                    </div>
    				           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                            <button id="sabeConceptoEgreso" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="saveConceptoAddEgreso()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <img id="logo_avantgard_none"
             src="<?php echo base_url()?>public/general/img/logos_colegio/avantgardLogo.png"
             style="display: none;">
    		
		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsegresos.js"></script>
        
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
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
            <?php if(_getSesion(PAGOS_ROL_SESS) != ID_ROL_DOCENTE){?>
            returnPage();
            <?php }?>
            tableEventsUpgradeMdlComponentsMDL('tb_egresos');
        </script>
	</body>
</html>